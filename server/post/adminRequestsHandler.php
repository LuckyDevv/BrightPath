<?php

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/apiHelper.php';

use lib\AdminLogger;
use lib\Config;
use managers\RequestsManager;

$requestsManager = new RequestsManager();

$admin_login = getAdminLoginFromSession();

switch ($_POST['type'] ?? null) {
    case "getById":
        if (!isset($_POST['id'])) error("Не передан ID заявки!", 0);
        if (!is_numeric($_POST['id'])) error("Передан некорректный ID заявки!", 1);
        $orderId = (int)$_POST['id'];
        $order = $requestsManager->getRequestById($orderId);
        if ($order !== false) {
            response(json_encode($order), 200);
        }else error("Не найден заказ с таким ID!", 2);
        break;
    case "changeStatus":
        if (!isset($_POST['id'])) error("Не передан ID заявки!", 0);
        if (!is_numeric($_POST['id'])) error("Передан некорректный ID заявки!", 1);
        if (!isset($_POST['newStatus'])) error("Не передан новый статус!", 2);
        $id = (int)$_POST['id'];
        $newStatus = $_POST['newStatus'];
        $response = $requestsManager->changeStatus($id, $admin_login, $newStatus);
        if ($response == RequestsManager::STATUS_CHANGED_SUCCESS) {
            response("OK", 200);
        }else{
            error(Config::REQUESTS_MESSAGES_MAP[$response], $response);
        }
        break;
    case "completeRequest":
        if (!isset($_POST['id'])) error("Не передан ID заявки!", 0);
        if (!is_numeric($_POST['id'])) error("Передан некорректный ID заявки!", 1);
        if (!isset($_POST['comment']) || trim($_POST['comment']) == '') error("Не передан комментарий!", 2);
        $id = (int)$_POST['id'];
        $comment = trim($_POST['comment']);
        $response = $requestsManager->completeRequest($id, $admin_login, $comment);
        if ($response == RequestsManager::STATUS_CHANGED_SUCCESS) {
            response("OK", 200);
        }else{
            error(Config::REQUESTS_MESSAGES_MAP[$response], $response);
        }
        break;
    case "deleteById":
        if (!isset($_POST['id'])) error("Не передан ID заявки!", 0);
        if (!is_numeric($_POST['id'])) error("Передан некорректный ID заявки!", 1);
        $id = (int)$_POST['id'];
        $response = $requestsManager->deleteById($id);
        if ($response) {
            response("OK", 200);
        }else{
            error("Не удалось удалить запись!", 2);
        }
        break;

}