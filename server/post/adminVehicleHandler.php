<?php

require_once __DIR__.'/../../vendor/autoload.php';

use managers\VehiclesManager;

$vehiclesManager = new VehiclesManager();

date_default_timezone_set('Europe/Moscow');

function logging(string $text)
{
    file_put_contents(__DIR__.'/post_vehicles.log', $text."\n", FILE_APPEND);
}

const JSON_OPTIONS = JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_BIGINT_AS_STRING;
function error(string $message, int $code): never
{
    die(json_encode(['error' => ['message' => $message, 'code' => $code]], JSON_OPTIONS));
}
function response(string $message, int $code): never
{
    die(json_encode(['response' => ['message' => $message, 'code' => $code]], JSON_OPTIONS));
}

logging("Тип запроса: ".($_POST["type"] ?? "не передано"));

switch ($_POST['type'] ?? null)
{
    case "getById":
        if (!empty($_POST['vehicleId']) && is_numeric($_POST['vehicleId'])) {
            $vehicleId = (int) $_POST['vehicleId'];
            $vehicle = $vehiclesManager->getVehicleById($vehicleId);
            $photos = scandir(__DIR__.'/../../src/images/vehicles/'.$vehicle["image_path"]);
            $additional_photos = [];
            if (!empty($photos)) {
                foreach ($photos as $photo) {
                    if ($photo != "." && $photo != "..") {
                        if (str_contains($photo, "additional")) {
                            $additional_photos[] = '/../../src/images/vehicles/'.$vehicle["image_path"].'/'.$photo;
                        }
                    }
                }
            }
            $vehicle["additional_photos"] = $additional_photos;
            response(json_encode($vehicle, JSON_OPTIONS), 0);
        }else{
            error("Не передан ID транспорта", 1);
        }
        break;
    case "deleteById":
        if (!empty($_POST['vehicleId']) && is_numeric($_POST['vehicleId'])) {
            $vehicleId = (int)$_POST['vehicleId'];
            if ($vehiclesManager->deleteVehicleById($vehicleId)) {
                response("Success", 0);
            }else{
                error("Error deleting vehicle", 1);
            }
        }else{
            error("Empty vehicle ID", 2);
        }
        break;
    case "addVehicle":
        $_POST = json_decode($_POST['vehicleData'], true);
        logging(json_encode($_POST));
        if (empty($_POST['name']) || mb_strlen($_POST['name']) > 255) {
            error("Не введено название транспорта", 1);
        }else{
            $name = $_POST['name'];
        }
        if (empty($_POST['category']) || ((int)$_POST['category'] < 1 || (int)$_POST['category'] > 4)) {
            error("Не выбрана категория", 2);
        }else{
            $category = (int)$_POST['category'];
        }
        if (empty($_FILES["main_photo"]) || empty($_FILES["main_photo"]["name"]) || empty($_FILES["main_photo"]["name"][0])) {
            error("Не загружено основное фото", 3);
        }else{
            $error = $_FILES["main_photo"]["error"][0];
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
            // TODO: Errors check
            try {
                $pathForDB = bin2hex(random_bytes(4));
            } catch (\Random\RandomException $e) {
                error("Не удалось сгенерировать байты: ".$e->getMessage(), 5);
            }
            $pathForDB = str_replace(" ", "_", strtolower($name)) . "__" . $pathForDB;
            $path = __DIR__."/../../src/images/vehicles/".$pathForDB."/";
            logging("Save dir for DB: ".$pathForDB."\n");
            logging("Save dir full: ".$path."\n");
            if (file_exists($path) || file_exists($path."main.jpg")) {
                error("Такой файл уже найден", 6);
            }
            $tmp = $_FILES["main_photo"]["tmp_name"][0];
            $image_name = $_FILES["main_photo"]["name"][0];
            $type = $_FILES["main_photo"]["type"][0];
            @mkdir($path);
            if ($type !== "image/jpeg" || !str_contains(mb_strtolower($image_name), "jpg")) {
                try {
                    convertToJpg($tmp, $path . "main.jpg");
                } catch (Exception $e) {
                    error("Не удалось конвертировать файл: ".$e->getMessage(), 7);
                }
            }else{
                logging("This is jpg"."\n");
                if (!move_uploaded_file($tmp, $path."main.jpg")) {
                    error("Не удалось загрузить файл.", 8);
                }
            }
        }
        if (!empty($_FILES["additional_photos"]["tmp_name"][0])) {
            $img_id = 0;
            foreach ($_FILES["additional_photos"]["tmp_name"] as $photo) {
                if ($type !== "image/jpeg" || !str_contains(mb_strtolower($_FILES["additional_photos"]["name"][$img_id]), "jpg")) {
                    try {
                        convertToJpg($photo, $path . "additional_".$img_id.".jpg");
                    } catch (Exception $e) {
                        error("Не удалось конвертировать дополнительное фото: ".$img_id, 23);
                    }
                }else{
                    logging("This is jpg"."\n");
                    if (!move_uploaded_file($photo, $path."additional_".$img_id.".jpg")) {
                        error("Не удалось переместить дополнительное фото: ".$img_id, 24);
                    }
                }
                $img_id++;
            }
        }
        if (empty($_POST['price']) || !is_numeric($_POST['price']) || (int)$_POST['price'] < 0) {
            error("Не введена цена", 9);
        }else{
            $price = (int)$_POST['price'];
        }
        if (empty($_POST['color'])) {
            error("Не введён цвет", 10);
        }else{
            $color = $_POST['color'];
        }
        if (empty($_POST['transmission']) || !is_numeric($_POST['transmission']) || ((int)$_POST['transmission'] < 1 || (int)$_POST['transmission'] > 2)) {
            error("Не выбрана КПП", 11);
        }else{
            $transmission = (int)$_POST['transmission'];
        }
        if (empty($_POST['drive_type']) || !is_numeric($_POST['drive_type']) || ((int)$_POST['drive_type'] < 1 || (int)$_POST['drive_type'] > 3)) {
            error("Не выбран привод", 12);
        }else{
            $drive_type = (int)$_POST['drive_type'];
        }
        if (empty($_POST['fuel']) || !is_numeric($_POST['fuel']) || ((int)$_POST['fuel'] < 1 || (int)$_POST['fuel'] > 2)) {
            error("Не выбран тип топлива", 13);
        }else{
            $fuel = (int)$_POST['fuel'];
        }
        if (empty($_POST['vin']) || mb_strlen(trim($_POST['vin'])) > 255) {
            error("Не введён VIN", 14);
        }else{
            $vin = $_POST['vin'];
        }
        if (empty($_POST['seats']) || !is_numeric($_POST['seats']) || (int)$_POST['seats'] < 0) {
            error("Не введена вместимость", 15);
        }else{
            $seats = (int)$_POST['seats'];
        }
        if (empty($_POST['mileage']) || !is_numeric($_POST['mileage']) || (int)$_POST['mileage'] < 0) {
            error("Не введён пробег", 16);
        }else{
            $mileage = (int)$_POST['mileage'];
        }
        if (empty($_POST['creation_year']) || !is_numeric($_POST['creation_year']) || (int)$_POST['creation_year'] < 1900 || (int)$_POST['creation_year'] > 2026) {
            error("Не введён год выпуска", 17);
        }else{
            $creation_year = (int)$_POST['creation_year'];
        }
        if (empty($_POST['total_stock']) || !is_numeric($_POST['total_stock']) || (int)$_POST['total_stock'] < 1) {
            error("Не введено кол-во в наличии", 18);
        }else{
            $total_stock = (int)$_POST['total_stock'];
        }
        if (empty($_POST['description_short'])) {
            error("Не введено короткое описание", 19);
        }else{
            $description_short = $_POST['description_short'];
        }
        if (empty($_POST['description_full'])) {
            error("Не введено полное описание", 20);
        }else{
            $description_full = $_POST['description_full'];
        }
        if (empty($_POST['isActive']) || !is_numeric($_POST['isActive']) || ($_POST['isActive'] < 0 || $_POST['isActive'] > 1)) {
            error("Не введён статус активности", 21);
        }else{
            $isActive = (bool) $_POST['isActive'];
        }

        logging("-------------------------------");
        logging("Название автомобиля: ".$name);
        logging("Цена: ".$price);
        logging("Категория: ".$category);
        logging("Вместимость: ".$seats);
        logging("VIN: ".$vin);
        logging("Цвет: ".$color);
        logging("Путь к фото: ".$pathForDB);
        logging("КПП: ".$transmission);
        logging("Привод: ".$drive_type);
        logging("Пробег: ".$mileage);
        logging("В наличии: ".$total_stock);
        logging("Год создания: ".$creation_year);
        logging("Описание (короткое): ".$description_short);
        logging("Описание (полное): ".$description_full);
        logging("Статус: ".$isActive);
        logging("-------------------------------");

        $success = $vehiclesManager->addVehicle([
            "name" => $name,
            "image_path" => $pathForDB,
            "category" => $category,
            "seats" => $seats,
            "creation_year" => $creation_year,
            "color" => $color,
            "price" => $price,
            "mileage" => $mileage,
            "vin" => $vin,
            "transmission" => $transmission,
            "fuel" => $fuel,
            "description_short" => $description_short,
            "description_full" => $description_full,
            "total_stock" => $total_stock,
            "is_active" => $isActive
        ]);
        if ($success !== false) {
            response("Добавлено успешно", 0);
        }else{
            error("Не удалось добавить транспорт. Ошибка в БД", 22);
        }

        // Массив additional_photos:
        // name[array with filenames],
        // tmp_name[array with tmp files of photos],
        // type[array with file types],
        // size[array with file size of photos],
        // error[array with error codes of photos]
        break;
    case "editVehicle":
        $_POST = json_decode($_POST['vehicleData'], true);
        // TODO: Переписать текущий код под редактирование транспорта
        // TODO: Проверять существующие доп.фото. Если сейчас они есть, а в массиве нет, то удалять тех, что нет в массиве
        // TODO: Проверять, изменилось ли main.jpg. Если да - удалять старый файл, а новый загружать
        // TODO: Добавить функцию, которая будет изменять только переданные параметры в базе данных, а остальные оставлять
        if (!empty($_POST["vehicleId"])) {

        }
        if (empty($_POST['name']) || mb_strlen($_POST['name']) > 255) {
            error("Не введено название транспорта", 1);
        }else{
            $name = $_POST['name'];
        }
        if (empty($_POST['category']) || ((int)$_POST['category'] < 1 || (int)$_POST['category'] > 4)) {
            error("Не выбрана категория", 2);
        }else{
            $category = (int)$_POST['category'];
        }
        if (empty($_FILES["main_photo"]) || empty($_FILES["main_photo"]["name"]) || empty($_FILES["main_photo"]["name"][0])) {
            error("Не загружено основное фото", 3);
        }else{
            $error = $_FILES["main_photo"]["error"][0];
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
            // TODO: Errors check
            try {
                $pathForDB = bin2hex(random_bytes(4));
            } catch (\Random\RandomException $e) {
                error("Не удалось сгенерировать байты: ".$e->getMessage(), 5);
            }
            $pathForDB = str_replace(" ", "_", strtolower($name)) . "__" . $pathForDB;
            $path = __DIR__."/../../src/images/vehicles/".$pathForDB."/";
            logging("Save dir for DB: ".$pathForDB."\n");
            logging("Save dir full: ".$path."\n");
            if (file_exists($path) || file_exists($path."main.jpg")) {
                error("Такой файл уже найден", 6);
            }
            $tmp = $_FILES["main_photo"]["tmp_name"][0];
            $image_name = $_FILES["main_photo"]["name"][0];
            $type = $_FILES["main_photo"]["type"][0];
            @mkdir($path);
            if ($type !== "image/jpeg" || !str_contains(mb_strtolower($image_name), "jpg")) {
                try {
                    convertToJpg($tmp, $path . "main.jpg");
                } catch (Exception $e) {
                    error("Не удалось конвертировать файл: ".$e->getMessage(), 7);
                }
            }else{
                logging("This is jpg"."\n");
                if (!move_uploaded_file($tmp, $path."main.jpg")) {
                    error("Не удалось загрузить файл.", 8);
                }
            }
        }
        if (!empty($_FILES["additional_photos"]["tmp_name"][0])) {
            $img_id = 0;
            foreach ($_FILES["additional_photos"]["tmp_name"] as $photo) {
                if ($type !== "image/jpeg" || !str_contains(mb_strtolower($_FILES["additional_photos"]["name"][$img_id]), "jpg")) {
                    try {
                        convertToJpg($photo, $path . "additional_".$img_id.".jpg");
                    } catch (Exception $e) {
                        error("Не удалось конвертировать дополнительное фото: ".$img_id, 23);
                    }
                }else{
                    logging("This is jpg"."\n");
                    if (!move_uploaded_file($photo, $path."additional_".$img_id.".jpg")) {
                        error("Не удалось переместить дополнительное фото: ".$img_id, 24);
                    }
                }
                $img_id++;
            }
        }
        if (empty($_POST['price']) || !is_numeric($_POST['price']) || (int)$_POST['price'] < 0) {
            error("Не введена цена", 9);
        }else{
            $price = (int)$_POST['price'];
        }
        if (empty($_POST['color'])) {
            error("Не введён цвет", 10);
        }else{
            $color = $_POST['color'];
        }
        if (empty($_POST['transmission']) || !is_numeric($_POST['transmission']) || ((int)$_POST['transmission'] < 1 || (int)$_POST['transmission'] > 2)) {
            error("Не выбрана КПП", 11);
        }else{
            $transmission = (int)$_POST['transmission'];
        }
        if (empty($_POST['drive_type']) || !is_numeric($_POST['drive_type']) || ((int)$_POST['drive_type'] < 1 || (int)$_POST['drive_type'] > 3)) {
            error("Не выбран привод", 12);
        }else{
            $drive_type = (int)$_POST['drive_type'];
        }
        if (empty($_POST['fuel']) || !is_numeric($_POST['fuel']) || ((int)$_POST['fuel'] < 1 || (int)$_POST['fuel'] > 2)) {
            error("Не выбран тип топлива", 13);
        }else{
            $fuel = (int)$_POST['fuel'];
        }
        if (empty($_POST['vin']) || mb_strlen(trim($_POST['vin'])) > 255) {
            error("Не введён VIN", 14);
        }else{
            $vin = $_POST['vin'];
        }
        if (empty($_POST['seats']) || !is_numeric($_POST['seats']) || (int)$_POST['seats'] < 0) {
            error("Не введена вместимость", 15);
        }else{
            $seats = (int)$_POST['seats'];
        }
        if (empty($_POST['mileage']) || !is_numeric($_POST['mileage']) || (int)$_POST['mileage'] < 0) {
            error("Не введён пробег", 16);
        }else{
            $mileage = (int)$_POST['mileage'];
        }
        if (empty($_POST['creation_year']) || !is_numeric($_POST['creation_year']) || (int)$_POST['creation_year'] < 1900 || (int)$_POST['creation_year'] > 2026) {
            error("Не введён год выпуска", 17);
        }else{
            $creation_year = (int)$_POST['creation_year'];
        }
        if (empty($_POST['total_stock']) || !is_numeric($_POST['total_stock']) || (int)$_POST['total_stock'] < 1) {
            error("Не введено кол-во в наличии", 18);
        }else{
            $total_stock = (int)$_POST['total_stock'];
        }
        if (empty($_POST['description_short'])) {
            error("Не введено короткое описание", 19);
        }else{
            $description_short = $_POST['description_short'];
        }
        if (empty($_POST['description_full'])) {
            error("Не введено полное описание", 20);
        }else{
            $description_full = $_POST['description_full'];
        }
        if (empty($_POST['isActive']) || !is_numeric($_POST['isActive']) || ($_POST['isActive'] < 0 || $_POST['isActive'] > 1)) {
            error("Не введён статус активности", 21);
        }else{
            $isActive = (bool) $_POST['isActive'];
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