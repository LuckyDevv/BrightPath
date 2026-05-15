<?php

require_once __DIR__.'/../../vendor/autoload.php';
require_once __DIR__ . '/apiHelper.php';

use lib\AdminLogger;
use managers\VehiclesManager;

$vehiclesManager = new VehiclesManager();

try {
    $admin_login = getAdminLoginFromSession();
}catch (\Exception|\Error $e){
    error("Не удалось получить логин из сессии: ".$e->getMessage(), 502);
}

function pushMainPhoto(string $path): void
{
    $tmp = $_FILES["main_photo"]["tmp_name"][0];
    $image_name = $_FILES["main_photo"]["name"][0];
    $type = $_FILES["main_photo"]["type"][0];
    @mkdir($path);
    if ($type !== "image/jpeg" || !str_contains(mb_strtolower($image_name), "jpg")) {
        try {
            convertToJpg($tmp, $path . "main.jpg");
        } catch (Exception $e) {
            error("Не удалось конвертировать файл: " . $e->getMessage(), 7);
        }
    } else {
        if (!move_uploaded_file($tmp, $path . "main.jpg")) {
            error("Не удалось загрузить файл.", 8);
        }
    }
}

switch ($_POST['type'] ?? null)
{
    case "getById":
        createAdminLog($admin_login, ["result"=>"processing"]);
        if (!empty($_POST['vehicleId']) && is_numeric($_POST['vehicleId'])) {
            $vehicleId = (int) $_POST['vehicleId'];
            $vehicle = $vehiclesManager->getVehicleById($vehicleId, true);
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
            createAdminLog($admin_login, ["vehicleId"=>$vehicleId, "result"=>"success"]);
            response(json_encode($vehicle, JSON_OPTIONS), 0);
        }else{
            error("Не передан ID транспорта", 1);
        }
    case "deleteById":
        createAdminLog($admin_login, ["result"=>"processing"]);
        if (!empty($_POST['vehicleId']) && is_numeric($_POST['vehicleId'])) {
            $vehicleId = (int)$_POST['vehicleId'];
            $imagePath = $vehiclesManager->getImagePath($vehicleId);
            rmdir($imagePath);
            if ($vehiclesManager->deleteVehicleById($vehicleId)) {
                $imagePath = __DIR__.'/../../src/images/vehicles/'.$imagePath."/";
                $files = scandir($imagePath);
                foreach ($files as $file) {
                    if ($file != "." && $file != "..") {
                        unlink($imagePath.$file);
                    }
                }
                rmdir($imagePath);
                createAdminLog($admin_login, ["vehicleId"=>$vehicleId, "result"=>"success"]);
                response("Запись успешно удалена", 0);
            }else{
                error("Не удалось удалить запись.", 1);
            }
        }else{
            error("ID не передан!", 2);
        }
    case "addVehicle":
        createAdminLog($admin_login, ["result"=>"processing"]);
        $vehicleData = json_decode($_POST['vehicleData'], true);
        if (empty($vehicleData['name']) || mb_strlen($vehicleData['name']) > 255) {
            error("Не введено название транспорта", 1);
        }else{
            $name = $vehicleData['name'];
        }
        if (empty($vehicleData['category']) || ((int)$vehicleData['category'] < 1 || (int)$vehicleData['category'] > 4)) {
            error("Не выбрана категория", 2);
        }else{
            $category = (int)$vehicleData['category'];
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
            if (file_exists($path) || file_exists($path."main.jpg")) {
                error("Такой файл уже найден", 6);
            }
            pushMainPhoto($path);
        }
        try {
            if (!empty($_FILES['additional_photos']) && !empty($_FILES["additional_photos"]["tmp_name"][0])) {
                $img_id = 0;
                foreach ($_FILES["additional_photos"]["tmp_name"] as $photo) {
                    $type = $_FILES["additional_photos"]["type"][$img_id];
                    if ($type !== "image/jpeg" || !str_contains(mb_strtolower($_FILES["additional_photos"]["name"][$img_id]), "jpg")) {
                        try {
                            convertToJpg($photo, $path . "/additional_" . $img_id . ".jpg");
                        } catch (Exception $e) {
                            error("Не удалось конвертировать дополнительное фото: " . $img_id, 23);
                        }
                    } else {
                        if (!move_uploaded_file($photo, $path . "/additional_" . $img_id . ".jpg")) {
                            error("Не удалось переместить дополнительное фото: " . $img_id, 24);
                        }
                    }
                    $img_id++;
                }
            }
        }catch(Exception|Error $e){
            error($e->getMessage(), 700);
        }
        if (empty($vehicleData['price']) || !is_numeric($vehicleData['price']) || (int)$vehicleData['price'] < 0) {
            error("Не введена цена", 9);
        }else{
            $price = (int)$vehicleData['price'];
        }
        if (empty($vehicleData['color'])) {
            error("Не введён цвет", 10);
        }else{
            $color = $vehicleData['color'];
        }
        if (empty($vehicleData['seats']) || !is_numeric($vehicleData['seats']) || (int)$vehicleData['seats'] < 0) {
            error("Не введена вместимость", 15);
        }else{
            $seats = (int)$vehicleData['seats'];
        }
        if (empty($vehicleData['total_stock']) || !is_numeric($vehicleData['total_stock']) || (int)$vehicleData['total_stock'] < 1) {
            error("Не введено кол-во в наличии", 18);
        }else{
            $total_stock = (int)$vehicleData['total_stock'];
        }
        if (empty($vehicleData['description_short'])) {
            error("Не введено короткое описание", 19);
        }else{
            $description_short = $vehicleData['description_short'];
        }
        if (empty($vehicleData['description_full'])) {
            error("Не введено полное описание", 20);
        }else{
            $description_full = $vehicleData['description_full'];
        }
        if (!isset($vehicleData['isActive']) || !is_numeric($vehicleData['isActive']) || ($vehicleData['isActive'] < 0 || $vehicleData['isActive'] > 1)) {
            error("Не введён статус активности", 21);
        }else{
            $isActive = $vehicleData['isActive'];
        }

        $success = $vehiclesManager->addVehicle([
            "name" => $name,
            "image_path" => $pathForDB,
            "category" => $category,
            "seats" => $seats,
            "color" => $color,
            "price" => $price,
            "description_short" => $description_short,
            "description_full" => $description_full,
            "total_stock" => $total_stock,
            "is_active" => $isActive
        ]);
        if ($success !== false) {
            createAdminLog($admin_login, [
                "vehicleId"=>$success,
                "name" => $name,
                "image_path" => $pathForDB,
                "category" => $category,
                "seats" => $seats,
                "color" => $color,
                "price" => $price,
                "description_short" => $description_short,
                "description_full" => $description_full,
                "total_stock" => $total_stock,
                "is_active" => $isActive,
                "result"=>"success"
            ]);
            $id = $vehiclesManager->getLastInsertId();
            response(json_encode(['text' => "Добавлено успешно", 'vehicleId' => $id, 'image_path' => $pathForDB]), 0);
        }else{
            error("Не удалось добавить транспорт. Ошибка в БД", 22);
        }
        break;
    case "editVehicle":
        createAdminLog($admin_login, ["result"=>"processing"]);
        $vehicleData = json_decode($_POST['vehicleData'], true);
        if (!empty($vehicleData["vehicleId"])) {
            if (is_numeric($vehicleData["vehicleId"])) {
                $vehicleId = (int) $vehicleData["vehicleId"];
            }else error("ID не является числом!", 25);
        }else error("ID не передан", 26);

        if (empty($vehicleData['name']) || mb_strlen($vehicleData['name']) > 255) {
            error("Не введено название транспорта", 1);
        }else{
            $name = $vehicleData['name'];
        }
        if (empty($vehicleData['category']) || ((int)$vehicleData['category'] < 1 || (int)$vehicleData['category'] > 4)) {
            error("Не выбрана категория", 2);
        }else{
            $category = (int)$vehicleData['category'];
        }
        if (empty($vehicleData['price']) || !is_numeric($vehicleData['price']) || (int)$vehicleData['price'] < 0) {
            error("Не введена цена", 9);
        }else{
            $price = (int)$vehicleData['price'];
        }
        if (empty($vehicleData['color'])) {
            error("Не введён цвет", 10);
        }else{
            $color = $vehicleData['color'];
        }
        if (empty($vehicleData['seats']) || !is_numeric($vehicleData['seats']) || (int)$vehicleData['seats'] < 0) {
            error("Не введена вместимость", 15);
        }else{
            $seats = (int)$vehicleData['seats'];
        }
        if (empty($vehicleData['total_stock']) || !is_numeric($vehicleData['total_stock']) || (int)$vehicleData['total_stock'] < 1) {
            error("Не введено кол-во в наличии", 18);
        }else{
            $total_stock = (int)$vehicleData['total_stock'];
        }
        if (empty($vehicleData['description_short'])) {
            error("Не введено короткое описание", 19);
        }else{
            $description_short = $vehicleData['description_short'];
        }
        if (empty($vehicleData['description_full'])) {
            error("Не введено полное описание", 20);
        }else{
            $description_full = $vehicleData['description_full'];
        }
        if (!isset($vehicleData['isActive']) || !is_numeric($vehicleData['isActive']) || ($vehicleData['isActive'] < 0 || $vehicleData['isActive'] > 1)) {
            error("Не введён статус активности", 21);
        }else{
            $isActive = (bool) $vehicleData['isActive'];
        }
        $oldVehicleData = $vehiclesManager->getVehicleById($vehicleId, true);
        $path = "../../src/images/vehicles/".$oldVehicleData['image_path'];
        if (!empty($oldVehicleData) && count($oldVehicleData) > 1) {
            if (empty($vehicleData['existing_main_photo'])) {
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
                    pushMainPhoto(__DIR__."/".$path."/");
                }
            }

            try {
                $dirscan = scandir(__DIR__.'/'.$path);
                if (!empty($_FILES["additional_photos"]["name"][0])) {
                    $max_additional_id = 0;
                    if (empty($vehicleData['existing_additional_photos']) || count($vehicleData['existing_additional_photos']) < 1) {
                        foreach ($dirscan as $file) {
                            $additional_id = explode("_", explode('.jpg', $file)[0])[1];
                            if (is_numeric($additional_id)) {
                                $max_additional_id = (int) $additional_id;
                            }
                            if (!str_contains($file, "main")){
                                unlink(__DIR__.'/'.$path.'/'.$file);
                            }
                        }
                    }else{
                        $unlink_photos = [];
                        foreach ($vehicleData['existing_additional_photos'] as $file) {
                            $file_explode = explode('/', $file);
                            $count_explode = count($file_explode);
                            $unlink_photos[] = $file_explode[$count_explode-1];
                        }
                        foreach ($dirscan as $file) {
                            $additional_id = explode("_", explode('.jpg', $file)[0])[1];
                            if (is_numeric($additional_id)) {
                                $max_additional_id = (int) $additional_id;
                            }
                            if (!in_array($file, $unlink_photos)) {
                                if (!str_contains($file, 'main')) {
                                    unlink(__DIR__.'/'.$path.'/'.$file);
                                }
                            }
                        }
                    }

                    $img_id = $max_additional_id + 1;
                    $foreach_id = 0;
                    foreach ($_FILES["additional_photos"]["tmp_name"] as $photo) {
                        $type = $_FILES["additional_photos"]["type"][$foreach_id];
                        if ($type !== "image/jpeg" || !str_contains(mb_strtolower($_FILES["additional_photos"]["name"][$foreach_id]), "jpg")) {
                            try {
                                convertToJpg($photo, __DIR__."/".$path . "/additional_" . $img_id . ".jpg");
                            } catch (Exception $e) {
                                error("Не удалось конвертировать дополнительное фото: " . $foreach_id, 23);
                            }
                        } else {
                            if (!move_uploaded_file($photo, __DIR__."/".$path . "/additional_" . $img_id . ".jpg")) {
                                error("Не удалось переместить дополнительное фото: " . $foreach_id, 24);
                            }
                        }
                        $img_id++;
                        $foreach_id++;
                    }
                }else{
                    if (empty($vehicleData['existing_additional_photos']) || count($vehicleData['existing_additional_photos']) < 1) {
                        foreach ($dirscan as $file) {
                            if (!str_contains($file, "main")){
                                unlink(__DIR__.'/'.$path.'/'.$file);
                            }
                        }
                    }else{
                        $unlink_photos = [];
                        foreach ($vehicleData['existing_additional_photos'] as $file) {
                            $file_explode = explode('/', $file);
                            $count_explode = count($file_explode);
                            $unlink_photos[] = $file_explode[$count_explode-1];
                        }
                        foreach ($dirscan as $file) {
                            if (!in_array($file, $unlink_photos)) {
                                if (!str_contains($file, 'main')) {
                                    unlink(__DIR__.'/'.$path.'/'.$file);
                                }
                            }
                        }
                    }
                }

            }catch(Exception|Error $e){
                error($e->getMessage(), 700);
            }

            $newVehicleData = [];
            if ($name !== $oldVehicleData['name']) {
                $newVehicleData['name'] = $name;
            }
            if ($category !== $oldVehicleData['category']) {
                $newVehicleData['category'] = $category;
            }
            if ($seats !== $oldVehicleData['seats']) {
                $newVehicleData['seats'] = $seats;
            }
            if ($color !== $oldVehicleData['color']) {
                $newVehicleData['color'] = $color;
            }
            if (trim($description_short) !== trim($oldVehicleData['description_short'])) {
                $newVehicleData['description_short'] = trim($description_short);
            }
            if (trim($description_full) !== trim($oldVehicleData['description_full'])) {
                $newVehicleData['description_full'] = trim($description_full);
            }
            if ($price !== $oldVehicleData['price']) {
                $newVehicleData['price'] = $price;
            }
            if ($total_stock !== $oldVehicleData['total_stock']) {
                $newVehicleData['total_stock'] = $total_stock;
            }
            if ($isActive !== $oldVehicleData['is_active']) {
                $newVehicleData['is_active'] = (bool) $isActive;
            }
            $ans = $vehiclesManager->changeVehicle($vehicleId, $newVehicleData);
            if ($ans) {
                createAdminLog($admin_login, [
                    "vehicleId" => $vehicleId,
                    $newVehicleData,
                    "result" => "success"
                ]);
                response(json_encode(["text" => 'Данные успешно сохранены', 'image_path' => $path]), 1);
            }else{
                error("Не удалось сохранить изменения", 27);
            }
        }else{
            error("Не удалось получить данные авто", 28);
        }
    default:
        error("Неизсветный запрос", 55);
}

function createAdminLog(string $admin_login, array $data): void
{
    $adminLogger = new AdminLogger();
    $adminLogger->log("adminVehicleHandler", $admin_login, $_POST['type'], $data);
}