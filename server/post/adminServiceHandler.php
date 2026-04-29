<?php

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/apiHelper.php';

use managers\ServicesManager;
use lib\GoogleAuthenticator;

$servicesManager = new ServicesManager();

logging("adminServiceHandler", "Тип запроса: " . ($_POST["type"] ?? "не передано"));

switch ($_POST['type'] ?? null) {
    case "getById":
        if (!isset($_POST['serviceId'])) error("Не передан ID услуги!", 0);
        if (!is_numeric($_POST['serviceId'])) error("Передан некорректный ID услуги!", 1);
        $serviceId = (int)$_POST['serviceId'];
        $service = $servicesManager->getServiceById($serviceId, true);
        if (count($service) > 0) {
            response(json_encode($service), 200);
        }else error("Не найдена услуга с таким ID!", 3);
        break;
    case "editService":
        if (!isset($_POST['serviceData'])) error("Не переданы данные!", 11);
        $_POST = json_decode($_POST['serviceData'], true);
        if (!isset($_POST['serviceId'])) error("Не передан ID услуги!", 0);
        if (!is_numeric($_POST['serviceId'])) error("Передан некорректный ID услуги!", 1);
        $serviceId = (int)$_POST['serviceId'];
        if (!isset($_POST['name']) || trim($_POST['name']) == '') error("Не введено название услуги!", 2);
        $name = $_POST['name'];
        if (!isset($_POST['category'])) error("Не выбрана категория!", 3);
        if (!is_numeric($_POST['category'])) error("Некорректная категория!", 4);
        $category = (int)$_POST['category'];
        if (!isset($_POST['price'])) error("Не введена цена!",5);
        if (!is_numeric($_POST['price'])) error("Некорректная цена!", 6);
        $price = (int)$_POST['price'];
        if (!isset($_POST['description']) || trim($_POST['description']) == '') error("Не введено описание!", 7);
        $description = $_POST['description'];
        if (!isset($_POST['what_includes']) || trim($_POST['what_includes']) == '') error("Не введено наполнение!", 8);
        $what_includes = $_POST['what_includes'];
        if (!isset($_POST['isActive'])) error("Не выбран статус активности!", 9);
        if (!is_numeric($_POST['isActive'])) error("Некорректный статус активности!", 10);
        $isActive = (int)$_POST['isActive'];
        if ($isActive < 0 || $isActive > 1) error("Некорректный статус активности!", 10);
        $isActive = (bool)$_POST['isActive'];
        $result = $servicesManager->editService($serviceId, [
            'name' => $name,
            'category' => $category,
            'price' => $price,
            'description' => $description,
            'what_includes' => $what_includes,
            'is_active' => $isActive
        ]);
        $result ? response("Success", 200) : error("Not success", 400);
        break;
    case "addService":
        if (!isset($_POST['serviceData'])) error("Не переданы данные!", 11);
        $_POST = json_decode($_POST['serviceData'], true);
        if (!isset($_POST['name']) || trim($_POST['name']) == '') error("Не введено название услуги!", 2);
        $name = $_POST['name'];
        if (!isset($_POST['category'])) error("Не выбрана категория!", 3);
        if (!is_numeric($_POST['category'])) error("Некорректная категория!", 4);
        $category = (int)$_POST['category'];
        if (!isset($_POST['price'])) error("Не введена цена!",5);
        if (!is_numeric($_POST['price'])) error("Некорректная цена!", 6);
        $price = (int)$_POST['price'];
        if (!isset($_POST['description']) || trim($_POST['description']) == '') error("Не введено описание!", 7);
        $description = $_POST['description'];
        if (!isset($_POST['what_includes']) || trim($_POST['what_includes']) == '') error("Не введено наполнение!", 8);
        $what_includes = $_POST['what_includes'];
        if (!isset($_POST['isActive'])) error("Не выбран статус активности!", 9);
        if (!is_numeric($_POST['isActive'])) error("Некорректный статус активности!", 10);
        $isActive = (int)$_POST['isActive'];
        if ($isActive < 0 || $isActive > 1) error("Некорректный статус активности!", 10);
        $isActive = (bool)$_POST['isActive'];
        $result = $servicesManager->addService([
            'name' => $name,
            'category' => $category,
            'price' => $price,
            'description' => $description,
            'what_includes' => $what_includes,
            'isActive' => $isActive
        ]);
        if (is_int($result)) {
            response(json_encode(['text' => "Добавлено успешно", 'serviceId' => $result]), 200);
        }else error("Not success", 400);
        break;
    case "deleteById":
        if (!isset($_POST['serviceId'])) error("Не передан ID услуги!", 0);
        if (!is_numeric($_POST['serviceId'])) error("Передан некорректный ID услуги!", 1);
        $serviceId = (int)$_POST['serviceId'];
        if ($servicesManager->deleteServiceById($serviceId)) {
            response("Success", 200);
        }else error("Not success", 400);
        break;
}
