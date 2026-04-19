<?php

require_once __DIR__.'/../../vendor/autoload.php';

use managers\AgentsManager;

$agentsManager = new AgentsManager();

date_default_timezone_set('Europe/Moscow');

function logging(string $text)
{
    file_put_contents(__DIR__.'/post_agents.log', $text."\n", FILE_APPEND);
}

const JSON_OPTIONS = JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_BIGINT_AS_STRING;
function error(string $message, int $code): never
{
    die(json_encode(['error' => ['message' => $message, 'code' => $code]], JSON_OPTIONS));
}
function response(string $message, int $code, int|null $id = null): never
{
    if ($id !== null) {
        die(json_encode(['response' => ['message' => $message, 'code' => $code, 'vehicleId' => $id]], JSON_OPTIONS));
    }else{
        die(json_encode(['response' => ['message' => $message, 'code' => $code]], JSON_OPTIONS));
    }
}

logging("Тип запроса: ".($_POST["type"] ?? "не передано"));

/**
 * @param string $path
 * @return void
 */
function pushMainPhoto(string $path): void
{
    logging("Started photo uploading. Path: ".$path);
    $tmp = $_FILES["main_photo"]["tmp_name"];
    $image_name = $_FILES["main_photo"]["name"];
    $type = $_FILES["main_photo"]["type"];
    logging("Photo meta. Name: ".$tmp.", image: ".$image_name.", type: ".$type);
    if ($type !== "image/jpeg" || !str_contains(mb_strtolower($image_name), "jpg")) {
        try {
            logging("Converting to jpg");
            convertToJpg($tmp, $path);
        } catch (Exception $e) {
            error("Не удалось конвертировать файл: " . $e->getMessage(), 7);
        }
    } else {
        logging("Moving tmp file");
        if (!move_uploaded_file($tmp, $path)) {
            error("Не удалось загрузить файл.", 8);
        }
    }
}

switch ($_POST['type'] ?? null)
{
    case "getById":
        if (!empty($_POST['agentId']) && is_numeric($_POST['agentId'])) {
            $agentId = (int) $_POST['agentId'];
            $agent = $agentsManager->getById($agentId);
            response(json_encode($agent, JSON_OPTIONS), 0);
        }else{
            error("Не передан ID агента", 1);
        }
        break;
    case "addAgent":
        $_POST = json_decode($_POST['agentData'], true);
        if (!isset($_POST['name']) || mb_strlen($_POST['name']) < 1 || mb_strlen($_POST['name']) > 255) {
            error("Не введено ФИО", 1);
        }
        $name = $_POST['name'];
        if (!isset($_POST['position']) || !is_numeric($_POST['position']) || (int) $_POST['position'] < 1 || (int) $_POST['position'] > 5) {
            error("Не выбрана должность", 2);
        }
        if (empty($_FILES["main_photo"]) || empty($_FILES["main_photo"]["name"])) {
            error("Не загружено основное фото", 3);
        }else{
            $error = $_FILES["main_photo"]["error"];
            $errors = [
                UPLOAD_ERR_INI_SIZE => 'Файл превышает max_file_size',
                UPLOAD_ERR_FORM_SIZE => 'Файл превышает MAX_FILE_SIZE',
                UPLOAD_ERR_PARTIAL => 'Файл загружен частично',
                UPLOAD_ERR_NO_FILE => 'Файл не загружен',
                UPLOAD_ERR_NO_TMP_DIR => 'Нет временной папки',
                UPLOAD_ERR_CANT_WRITE => 'Не удалось записать файл',
                UPLOAD_ERR_EXTENSION => 'Расширение PHP остановило загрузку'
            ];
            // Проверяем ошибки загрузки главного фото
            if ($error !== UPLOAD_ERR_OK) {
                error("Ошибка загрузки фото: ".$errors[$error], 4);
            }
            try {
                $pathForDB = bin2hex(random_bytes(4));
            } catch (\Random\RandomException $e) {
                error("Не удалось сгенерировать байты: ".$e->getMessage(), 5);
            }
            $pathForDB = str_replace(" ", "_", mb_strtolower($name)) . "__" . $pathForDB.".jpg";
            $path = "/../../src/images/agents/".$pathForDB;
            if (file_exists(__DIR__.$path)) {
                error("Такой файл уже найден", 6);
            }
            pushMainPhoto(__DIR__.$path);
        }
        if (isset($_POST['birthdate']) || strlen($_POST['birthdate']) > 0) {
            try {
                $birthdate = new DateTime($_POST['birthdate']);
            } catch (DateMalformedStringException $e) {
                error("Неверный формат даты", 3);
            }
        }else { logging("Нет даты рождения"); return; } // todo: error
        if (!isset($_POST['description']) || mb_strlen($_POST['description']) < 1) {
            error("Не введено описание", 4);
        }
        if (!isset($_POST['biographic']) || mb_strlen($_POST['biographic']) < 1) {
            error("Не введена биография", 5);
        }
        $position = (int) $_POST['position'];
        $description = $_POST['description'];
        $biographic = $_POST['biographic'];
        $result = $agentsManager->addAgent([
            "name" => $name,
            "position" => $position,
            "birthdate" => $birthdate,
            "description" => $description,
            "biographic" => $biographic,
            "image" => $pathForDB
        ]);
        if ($result) {
            $agentId = $agentsManager->getLastAgentId();
            response(json_encode(['text' => "Добавлено успешно", 'agentId' => $agentId, 'image_path' => $path]), 200);
        }else{
            if (isset($path)) {
                unlink($path);
            }
            error("Не удалось добавить агента", 9);
        }
        break;
    case "editAgent":
        logging("Edit agent");
        $oldPost = $_POST;
        $_POST = json_decode($_POST['agentData'], true);
        if (!isset($_POST['agentId']) || !is_numeric($_POST['agentId']) || (int) $_POST['agentId'] <= 0) {
            error("Не передан ID агента", 7);
        }
        $agentId = (int) $_POST['agentId'];
        if (!isset($_POST['name']) || mb_strlen($_POST['name']) < 1 || mb_strlen($_POST['name']) > 255) {
            error("Не введено ФИО", 1);
        }
        if (!isset($_POST['position']) || !is_numeric($_POST['position']) || (int) $_POST['position'] < 1 || (int) $_POST['position'] > 5) {
            error("Не выбрана должность", 2);
        }
        logging("WFT");
        logging("Existing: ".json_encode(empty($oldPost['existing_main_photo']), JSON_UNESCAPED_UNICODE));
        if (empty($oldPost['existing_main_photo'])) {
            logging("Получение пути к изображению...");
            $imagePath = $agentsManager->getImagePathById($agentId);
            logging("Путь к изображению: ".$imagePath);
            if (!empty($imagePath)) {
                logging("Loaded new main photo");
                logging("Main photo data: ".json_encode($_FILES["main_photo"], JSON_UNESCAPED_UNICODE));
                if (empty($_FILES["main_photo"]) || empty($_FILES["main_photo"]["name"])) {
                    error("Не загружено основное фото", 3);
                }else{
                    $error = $_FILES["main_photo"]["error"];
                    $errors = [
                        UPLOAD_ERR_INI_SIZE => 'Файл превышает max_file_size',
                        UPLOAD_ERR_FORM_SIZE => 'Файл превышает MAX_FILE_SIZE',
                        UPLOAD_ERR_PARTIAL => 'Файл загружен частично',
                        UPLOAD_ERR_NO_FILE => 'Файл не загружен',
                        UPLOAD_ERR_NO_TMP_DIR => 'Нет временной папки',
                        UPLOAD_ERR_CANT_WRITE => 'Не удалось записать файл',
                        UPLOAD_ERR_EXTENSION => 'Расширение PHP остановило загрузку'
                    ];
                    // Проверяем ошибки загрузки главного фото
                    if ($error !== UPLOAD_ERR_OK) {
                        error("Ошибка загрузки фото: ".$errors[$error], 4);
                    }
                    $path = __DIR__."/../../src/images/agents/".$imagePath;
                    logging("Start updating main photo");
                    pushMainPhoto($path);
                }
            }
        }
        if (isset($_POST['birthdate']) || strlen($_POST['birthdate']) > 0) {
            try {
                $birthdate = new DateTime($_POST['birthdate']);
            } catch (DateMalformedStringException $e) {
                error("Неверный формат даты", 3);
            }
        }else { logging("Нет даты рождения"); return; } // todo: error
        if (!isset($_POST['description']) || mb_strlen($_POST['description']) < 1) {
            error("Не введено описание", 4);
        }
        if (!isset($_POST['biographic']) || mb_strlen($_POST['biographic']) < 1) {
            error("Не введена биография", 5);
        }
        $name = $_POST['name'];
        $position = (int) $_POST['position'];
        $description = $_POST['description'];
        $biographic = $_POST['biographic'];
        if ($agentsManager->updateAgent($agentId, $name, $birthdate, $position, $description, $biographic)) {
            response("Успешно изменено", 200);
        }else{
            error("Не удалось сохранить изменения", 6);
        }
        break;
    case "deleteAgent":
        if (!empty($_POST['agentId']) && is_numeric($_POST['agentId'])) {
            $agentId = (int) $_POST['agentId'];
            $imagePath = $agentsManager->getImagePathById($agentId);
            if (!empty($imagePath)) {
                if (file_exists(__DIR__.'/../../src/images/agents/'.$imagePath)) {
                    unlink(__DIR__.'/../../src/images/agents/'.$imagePath);
                }
                if ($agentsManager->deleteAgent($agentId)) {
                    response("Успешно удалено", 200);
                }else{
                    error("Не удалось удалить", 1);
                }
            }else{
                error("Не найден агент с таким ID", 2);
            }
        }else{
            error("Не передан ID агента", 3);
        }
        break;
    default:
        error("Неизсветный запрос", 55);
}

function convertToJpg($sourcePath, $destinationPath = null, $quality = 90)
{
    // Если путь назначения не указан, заменяем расширение
    if ($destinationPath === null) {
        $destinationPath = pathinfo($sourcePath, PATHINFO_DIRNAME) . '/'
            . pathinfo($sourcePath, PATHINFO_FILENAME) . '.jpg';
    }

    // Получаем тип изображения
    $imageInfo = getimagesize($sourcePath);
    $mimeType = $imageInfo['mime'];

    // Создаём ресурс в зависимости от типа
    switch ($mimeType) {
        case 'image/jpeg':
            $image = imagecreatefromjpeg($sourcePath);
            break;
        case 'image/png':
            $image = imagecreatefrompng($sourcePath);
            // Сохраняем прозрачность, заменяя её на белый фон
            $bg = imagecreatetruecolor(imagesx($image), imagesy($image));
            $white = imagecolorallocate($bg, 255, 255, 255);
            imagefill($bg, 0, 0, $white);
            imagecopy($bg, $image, 0, 0, 0, 0, imagesx($image), imagesy($image));
            $image = $bg;
            break;
        case 'image/webp':
            $image = imagecreatefromwebp($sourcePath);
            break;
        case 'image/gif':
            $image = imagecreatefromgif($sourcePath);
            break;
        case 'image/bmp':
            $image = imagecreatefrombmp($sourcePath);
            break;
        default:
            throw new Exception("Неподдерживаемый тип изображения: $mimeType");
    }

    // Сохраняем как JPG
    imagejpeg($image, $destinationPath, $quality);

    // Освобождаем память
    imagedestroy($image);

    return $destinationPath;
}