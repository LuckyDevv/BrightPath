<?php
require_once __DIR__.'/../../vendor/autoload.php';
require_once __DIR__ . '/apiHelper.php';

use lib\AdminLogger;
use managers\GoodsManager;

$goodsManager = new GoodsManager();

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

switch ($_POST['type'] ?? null) {
    case "getById":
        createAdminLog($admin_login, ["result" => "processing"]);
        if (!empty($_POST['goodsId']) && is_numeric($_POST['goodsId'])) {
            $goodsId = (int) $_POST['goodsId'];
            $goods = $goodsManager->getById($goodsId);
            createAdminLog($admin_login, ["goodsId" => $goodsId, "result" => "success"]);
            response(json_encode($goods, JSON_OPTIONS), 0);
        } else {
            error("Не передан ID товара", 1);
        }
        break;

    case "addGoods":
        createAdminLog($admin_login, ["result" => "processing"]);
        $goodsData = json_decode($_POST['goodsData'], true);

        // Валидация
        if (!isset($goodsData['name']) || mb_strlen($goodsData['name']) < 1 || mb_strlen($goodsData['name']) > 255) {
            error("Не введено название", 1);
        }

        if (!isset($goodsData['category']) || !is_numeric($goodsData['category']) || $goodsData['category'] < 1 || $goodsData['category'] > 6) {
            error("Не выбрана категория", 2);
        }

        if (!isset($goodsData['material']) || mb_strlen($goodsData['material']) < 1) {
            error("Не введён материал", 3);
        }

        if (!isset($goodsData['sizes']) || mb_strlen($goodsData['sizes']) < 1) {
            error("Не введён размер", 4);
        }

        if (!isset($goodsData['weight']) || !is_numeric($goodsData['weight']) || $goodsData['weight'] <= 0) {
            error("Не введён вес", 5);
        }

        if (!isset($goodsData['price']) || !is_numeric($goodsData['price']) || $goodsData['price'] <= 0) {
            error("Не введена цена", 6);
        }

        if (!isset($goodsData['description_short']) || mb_strlen($goodsData['description_short']) < 1) {
            error("Не введено короткое описание", 7);
        }

        if (!isset($goodsData['description_full']) || mb_strlen($goodsData['description_full']) < 1) {
            error("Не введено полное описание", 8);
        }

        if (!isset($goodsData['total_stock']) || !is_numeric($goodsData['total_stock']) || $goodsData['total_stock'] <= 0) {
            error("Не введено количество в наличии!", 14);
        }

        // Загрузка фото
        if (empty($_FILES["main_photo"]) || empty($_FILES["main_photo"]["name"])) {
            error("Не загружено основное фото", 9);
        } else {
            $error = $_FILES["main_photo"]["error"];
            if ($error !== UPLOAD_ERR_OK) {
                error("Ошибка загрузки фото", 10);
            }

            try {
                $salt = bin2hex(random_bytes(4));
            } catch (Random\RandomException $e) {
                error("Ошибка генерации имени", 11);
            }

            $imageName = str_replace(" ", "_", mb_strtolower($goodsData['name'])) . "__" . $salt . ".jpg";
            $imagePath = "/../../src/images/goods/" . $imageName;

            if (file_exists(__DIR__ . $imagePath)) {
                error("Файл уже существует", 12);
            }

            pushMainPhoto(__DIR__ . $imagePath);
        }

        $result = $goodsManager->addGoods([
            "name" => $goodsData['name'],
            "image_path" => $imageName,
            "category" => (int) $goodsData['category'],
            "material" => $goodsData['material'],
            "sizes" => $goodsData['sizes'],
            "weight" => (int) $goodsData['weight'],
            "description_short" => $goodsData['description_short'],
            "description_full" => $goodsData['description_full'],
            "price" => (float) $goodsData['price'],
            "total_stock" => (int) $goodsData['total_stock'],
        ]);

        if ($result) {
            $goodsId = $goodsManager->getLastGoodsId();
            createAdminLog($admin_login, ["goodsId" => $goodsId, "result" => "success"]);
            response(json_encode(['text' => "Товар добавлен", 'goodsId' => $goodsId, 'image_path' => $imagePath]), 200);
        } else {
            if (isset($imagePath)) unlink(__DIR__ . $imagePath);
            error("Не удалось добавить товар", 13);
        }
        break;

    case "editGoods":
        createAdminLog($admin_login, ["result" => "processing"]);
        $goodsData = json_decode($_POST['goodsData'], true);

        if (!isset($goodsData['goodsId']) || !is_numeric($goodsData['goodsId']) || $goodsData['goodsId'] <= 0) {
            error("Не передан ID товара", 1);
        }

        $goodsId = (int) $goodsData['goodsId'];

        // Валидация (аналогично add)
        if (!isset($goodsData['name']) || mb_strlen($goodsData['name']) < 1 || mb_strlen($goodsData['name']) > 255) {
            error("Не введено название", 2);
        }
        if (!isset($goodsData['category']) || !is_numeric($goodsData['category']) || $goodsData['category'] < 1 || $goodsData['category'] > 6) {
            error("Не выбрана категория", 3);
        }
        if (!isset($goodsData['material']) || mb_strlen($goodsData['material']) < 1) {
            error("Не введён материал", 4);
        }
        if (!isset($goodsData['sizes']) || mb_strlen($goodsData['sizes']) < 1) {
            error("Не введён размер", 5);
        }
        if (!isset($goodsData['weight']) || !is_numeric($goodsData['weight']) || $goodsData['weight'] <= 0) {
            error("Не введён вес", 6);
        }
        if (!isset($goodsData['price']) || !is_numeric($goodsData['price']) || $goodsData['price'] <= 0) {
            error("Не введена цена", 7);
        }
        if (!isset($goodsData['description_short']) || mb_strlen($goodsData['description_short']) < 1) {
            error("Не введено короткое описание", 8);
        }
        if (!isset($goodsData['description_full']) || mb_strlen($goodsData['description_full']) < 1) {
            error("Не введено полное описание", 9);
        }
        if (!isset($goodsData['total_stock']) || !is_numeric($goodsData['total_stock']) || $goodsData['total_stock'] <= 0) {
            error("Не введено количество в наличии!", 14);
        }

        if (empty($_POST['existing_main_photo'])) {
            $imagePath = $goodsManager->getImagePathById($goodsId);
            if (!empty($imagePath)) {
                if (!empty($_FILES["main_photo"]) && !empty($_FILES["main_photo"]["name"])) {
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
                    pushMainPhoto(__DIR__ . "/../../src/images/goods/". $imagePath);
                }else error("Не загружено основное фото", 3);
            }
        }

        $result = $goodsManager->updateGoods(
            $goodsId,
            $goodsData['name'],
            (int) $goodsData['category'],
            $goodsData['material'],
            $goodsData['sizes'],
            (int) $goodsData['weight'],
            $goodsData['description_short'],
            $goodsData['description_full'],
            (float) $goodsData['price'],
            (int) $goodsData['total_stock']
        );

        if ($result) {
            createAdminLog($admin_login, ["goodsId" => $goodsId, "result" => "success"]);
            response("Успешно обновлено", 200);
        } else {
            error("Не удалось обновить товар", 11);
        }
        break;

    case "deleteGoods":
        createAdminLog($admin_login, ["result" => "processing"]);
        if (!empty($_POST['goodsId']) && is_numeric($_POST['goodsId'])) {
            $goodsId = (int) $_POST['goodsId'];
            $imagePath = $goodsManager->getImagePathById($goodsId);
            if (!empty($imagePath) && file_exists(__DIR__ . '/../../src/images/goods/' . $imagePath)) {
                unlink(__DIR__ . '/../../src/images/goods/' . $imagePath);
            }
            if ($goodsManager->deleteGoods($goodsId)) {
                createAdminLog($admin_login, ["goodsId" => $goodsId, "result" => "success"]);
                response("Успешно удалено", 200);
            } else {
                error("Не удалось удалить", 1);
            }
        } else {
            error("Не передан ID товара", 2);
        }
        break;

    default:
        error("Неизвестный запрос", 55);
}

function createAdminLog(string $admin_login, array $data): void
{
    $adminLogger = new AdminLogger();
    $adminLogger->log("adminGoodsHandler", $admin_login, $_POST['type'], $data);
}