<?php

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/apiHelper.php';

use lib\AdminLogger;
use managers\ServicesManager;

$servicesManager = new ServicesManager();

$admin_login = getAdminLoginFromSession();

switch ($_POST['type'] ?? null) {
    case "getById":
        createAdminLog($admin_login, ["result"=>"processing"]);
        if (!isset($_POST['serviceId'])) error("Не передан ID услуги!", 0);
        if (!is_numeric($_POST['serviceId'])) error("Передан некорректный ID услуги!", 1);
        $serviceId = (int)$_POST['serviceId'];
        $service = $servicesManager->getServiceById($serviceId, true);
        if (count($service) > 0) {
            createAdminLog($admin_login, ["serviceId"=>$serviceId, "result"=>"success"]);
            response(json_encode($service), 200);
        }else error("Не найдена услуга с таким ID!", 3);
        break;
    case "editService":
        createAdminLog($admin_login, ["result"=>"processing"]);
        if (!isset($_POST['serviceData'])) error("Не переданы данные!", 11);
        $serviceData = json_decode($_POST['serviceData'], true);
        if (!isset($serviceData['serviceId'])) error("Не передан ID услуги!", 0);
        if (!is_numeric($serviceData['serviceId'])) error("Передан некорректный ID услуги!", 1);
        $serviceId = (int)$serviceData['serviceId'];
        if (!isset($serviceData['name']) || trim($serviceData['name']) == '') error("Не введено название услуги!", 2);
        $name = $serviceData['name'];
        if (!isset($serviceData['category'])) error("Не выбрана категория!", 3);
        if (!is_numeric($serviceData['category'])) error("Некорректная категория!", 4);
        $category = (int)$serviceData['category'];
        if (!isset($serviceData['price'])) error("Не введена цена!",5);
        if (!is_numeric($serviceData['price'])) error("Некорректная цена!", 6);
        $price = (int)$serviceData['price'];
        if (!isset($serviceData['description']) || trim($serviceData['description']) == '') error("Не введено описание!", 7);
        $description = $serviceData['description'];
        if (!isset($serviceData['what_includes']) || trim($serviceData['what_includes']) == '') error("Не введено наполнение!", 8);
        $what_includes = $serviceData['what_includes'];
        if (!isset($serviceData['isActive'])) error("Не выбран статус активности!", 9);
        if (!is_numeric($serviceData['isActive'])) error("Некорректный статус активности!", 10);
        $isActive = (int)$serviceData['isActive'];
        if ($isActive < 0 || $isActive > 1) error("Некорректный статус активности!", 10);
        $isActive = (bool)$serviceData['isActive'];
        $result = $servicesManager->editService($serviceId, [
            'name' => $name,
            'category' => $category,
            'price' => $price,
            'description' => $description,
            'what_includes' => $what_includes,
            'is_active' => $isActive
        ]);
        if ($result) {
            createAdminLog($admin_login, [
                "serviceId"=>$serviceId,
                "name"=>$name,
                "category"=>$category,
                "price"=>$price,
                "description"=>$description,
                "what_includes"=>$what_includes,
                "is_active"=>$isActive,
                "result"=>"success"
            ]);
        }
        $result ? response("Success", 200) : error("Not success", 400);
        break;
    case "addService":
        createAdminLog($admin_login, ["result"=>"processing"]);
        if (!isset($_POST['serviceData'])) error("Не переданы данные!", 11);
        $serviceData = json_decode($_POST['serviceData'], true);
        if (!isset($serviceData['name']) || trim($serviceData['name']) == '') error("Не введено название услуги!", 2);
        $name = $serviceData['name'];
        if (!isset($serviceData['category'])) error("Не выбрана категория!", 3);
        if (!is_numeric($serviceData['category'])) error("Некорректная категория!", 4);
        $category = (int)$serviceData['category'];
        if (!isset($serviceData['price'])) error("Не введена цена!",5);
        if (!is_numeric($serviceData['price'])) error("Некорректная цена!", 6);
        $price = (int)$serviceData['price'];
        if (!isset($serviceData['description']) || trim($serviceData['description']) == '') error("Не введено описание!", 7);
        $description = $serviceData['description'];
        if (!isset($serviceData['what_includes']) || trim($serviceData['what_includes']) == '') error("Не введено наполнение!", 8);
        $what_includes = $serviceData['what_includes'];
        if (!isset($serviceData['isActive'])) error("Не выбран статус активности!", 9);
        if (!is_numeric($serviceData['isActive'])) error("Некорректный статус активности!", 10);
        $isActive = (int)$serviceData['isActive'];
        if ($isActive < 0 || $isActive > 1) error("Некорректный статус активности!", 10);
        $isActive = (bool)$serviceData['isActive'];
        $result = $servicesManager->addService([
            'name' => $name,
            'category' => $category,
            'price' => $price,
            'description' => $description,
            'what_includes' => $what_includes,
            'isActive' => $isActive
        ]);
        if (is_int($result)) {
            createAdminLog($admin_login, [
                "serviceId"=>$result,
                "name"=>$name,
                "category"=>$category,
                "price"=>$price,
                "description"=>$description,
                "what_includes"=>$what_includes,
                "is_active"=>$isActive,
                "result"=>"success"
            ]);
            response(json_encode(['text' => "Добавлено успешно", 'serviceId' => $result]), 200);
        }else error("Not success", 400);
        break;
    case "deleteById":
        createAdminLog($admin_login, ["result"=>"processing"]);
        if (!isset($_POST['serviceId'])) error("Не передан ID услуги!", 0);
        if (!is_numeric($_POST['serviceId'])) error("Передан некорректный ID услуги!", 1);
        $serviceId = (int)$_POST['serviceId'];
        if ($servicesManager->deleteServiceById($serviceId)) {
            createAdminLog($admin_login, ["serviceId"=>$serviceId,"result"=>"success"]);
            response("Success", 200);
        }else error("Not success", 400);
        break;
}

function createAdminLog(string $admin_login, array $data): void
{
    $adminLogger = new AdminLogger();
    $adminLogger->log("adminServiceHandler", $admin_login, $_POST['type'], $data);
}
