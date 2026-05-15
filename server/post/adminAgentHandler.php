<?php

require_once __DIR__.'/../../vendor/autoload.php';
require_once __DIR__ . '/apiHelper.php';

use lib\AdminLogger;
use managers\AgentsManager;

$agentsManager = new AgentsManager();

function pushMainPhoto(string $path): void
{
    $tmp = $_FILES["main_photo"]["tmp_name"];
    $image_name = $_FILES["main_photo"]["name"];
    $type = $_FILES["main_photo"]["type"];
    if ($type !== "image/jpeg" || !str_contains(mb_strtolower($image_name), "jpg")) {
        try {
            convertToJpg($tmp, $path);
        } catch (Exception $e) {
            error("Не удалось конвертировать файл: " . $e->getMessage(), 7);
        }
    } else {
        if (!move_uploaded_file($tmp, $path)) {
            error("Не удалось загрузить файл.", 8);
        }
    }
}

$admin_login = getAdminLoginFromSession();

switch ($_POST['type'] ?? null)
{
    case "getById":
        createAdminLog($admin_login, ["result"=>"processing"]);
        if (!empty($_POST['agentId']) && is_numeric($_POST['agentId'])) {
            $agentId = (int) $_POST['agentId'];
            $agent = $agentsManager->getById($agentId);
            createAdminLog($admin_login, ["agentId" => $agentId, "result"=>"success"]);
            response(json_encode($agent, JSON_OPTIONS), 0);
        }else{
            error("Не передан ID агента", 1);
        }
        break;
    case "addAgent":
        createAdminLog($admin_login, ["result"=>"processing"]);
        $agentData = json_decode($_POST['agentData'], true);
        if (!isset($agentData['name']) || mb_strlen($agentData['name']) < 1 || mb_strlen($agentData['name']) > 255) {
            error("Не введено ФИО", 1);
        }
        $name = $agentData['name'];
        if (!isset($agentData['position']) || !is_numeric($agentData['position']) || (int) $agentData['position'] < 1 || (int) $agentData['position'] > 5) {
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
        if (isset($agentData['birthdate']) || strlen($agentData['birthdate']) > 0) {
            try {
                $birthdate = new DateTime($agentData['birthdate']);
            } catch (DateMalformedStringException $e) {
                error("Неверный формат даты", 3);
            }
        }else error("Не введена дата рождения", 10);
        if (!isset($agentData['description']) || mb_strlen($agentData['description']) < 1) {
            error("Не введено описание", 4);
        }
        if (!isset($agentData['biographic']) || mb_strlen($agentData['biographic']) < 1) {
            error("Не введена биография", 5);
        }
        $position = (int) $agentData['position'];
        $description = $agentData['description'];
        $biographic = $agentData['biographic'];
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
        createAdminLog($admin_login, ["result"=>"processing"]);
        $agentData = json_decode($_POST['agentData'], true);
        if (!isset($agentData['agentId']) || !is_numeric($agentData['agentId']) || (int) $agentData['agentId'] <= 0) {
            error("Не передан ID агента", 7);
        }
        $agentId = (int) $agentData['agentId'];
        if (!isset($agentData['name']) || mb_strlen($agentData['name']) < 1 || mb_strlen($agentData['name']) > 255) {
            error("Не введено ФИО", 1);
        }
        if (!isset($agentData['position']) || !is_numeric($agentData['position']) || (int) $agentData['position'] < 1 || (int) $agentData['position'] > 5) {
            error("Не выбрана должность", 2);
        }
        if (empty($_POST['existing_main_photo'])) {
            $imagePath = $agentsManager->getImagePathById($agentId);
            if (!empty($imagePath)) {
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
                    pushMainPhoto($path);
                }
            }
        }
        if (isset($agentData['birthdate']) || strlen($agentData['birthdate']) > 0) {
            try {
                $birthdate = new DateTime($agentData['birthdate']);
            } catch (DateMalformedStringException $e) {
                error("Неверный формат даты", 3);
            }
        }else error("Не введена дата рождения", 10);
        if (!isset($agentData['description']) || mb_strlen($agentData['description']) < 1) {
            error("Не введено описание", 4);
        }
        if (!isset($agentData['biographic']) || mb_strlen($agentData['biographic']) < 1) {
            error("Не введена биография", 5);
        }
        $name = $agentData['name'];
        $position = (int) $agentData['position'];
        $description = $agentData['description'];
        $biographic = $agentData['biographic'];
        if ($agentsManager->updateAgent($agentId, $name, $birthdate, $position, $description, $biographic)) {
            createAdminLog($admin_login, [
                "agentId"=>$agentId,
                "name"=>$name,
                "position"=>$position,
                "birthdate"=>$_POST['birthdate'],
                "description"=>$description,
                "biographic"=>$biographic,
                "result"=>"success",
            ]);
            response("Успешно изменено", 200);
        }else{
            error("Не удалось сохранить изменения", 6);
        }
        break;
    case "deleteAgent":
        createAdminLog($admin_login, ["result"=>"processing"]);
        if (!empty($_POST['agentId']) && is_numeric($_POST['agentId'])) {
            $agentId = (int) $_POST['agentId'];
            $imagePath = $agentsManager->getImagePathById($agentId);
            if (!empty($imagePath)) {
                if (file_exists(__DIR__.'/../../src/images/agents/'.$imagePath)) {
                    unlink(__DIR__.'/../../src/images/agents/'.$imagePath);
                }
                if ($agentsManager->deleteAgent($agentId)) {
                    createAdminLog($admin_login, ["agentId"=>$agentId, "result"=>"success"]);
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
        error("Неизвестный запрос", 55);
}

function createAdminLog(string $admin_login, array $data): void
{
    $adminLogger = new AdminLogger();
    $adminLogger->log("adminAgentHandler", $admin_login, $_POST['type'], $data);
}