<?php

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/apiHelper.php';

use lib\AdminLogger;
use lib\Mailer;
use managers\OrdersManager;

$ordersManager = new OrdersManager();

$admin_login = getAdminLoginFromSession();

switch ($_POST['type'] ?? null) {
    case "getById":
        if (!isset($_POST['id'])) error("Не передан ID заказа!", 0);
        if (!is_numeric($_POST['id'])) error("Передан некорректный ID заказа!", 1);
        $orderId = (int)$_POST['id'];
        $order = $ordersManager->getOrderById($orderId);
        if ($order !== false) {
            response(json_encode($order), 200);
        }else error("Не найден заказ с таким ID!", 2);
        break;
    case "changeStatus":
        if (!isset($_POST['id'])) error("Не передан ID заказа!", 0);
        if (!is_numeric($_POST['id'])) error("Передан некорректный ID заказа!", 1);
        if (!isset($_POST['newStatus'])) error("Не передан новый статус!", 2);
        $id = (int)$_POST['id'];
        $newStatus = $_POST['newStatus'];
        if ($ordersManager->changeStatus($id, $newStatus)) {
            $client = $ordersManager->getClientById($id);
            if ($client !== false) {
                $orderStatusData = [
                    'id' => $id,
                    'status' => $newStatus,
                    'created_at' => $client['created_at'],
                    'client_name' => $client['username'],
                    'client_phone' => $client['userphone']
                ];
                $mailer = new Mailer();
                $mailer->sendOrderStatusUpdated($client['useremail'], $orderStatusData);
            }
            response("OK", 200);
        }else{
            error("Непредвиденная ошибка", 3);
        }
        break;
    case "completeOrder":
        if (!isset($_POST['id'])) error("Не передан ID заказа!", 0);
        if (!is_numeric($_POST['id'])) error("Передан некорректный ID заказа!", 1);
        if (!isset($_POST['agents']) || !isset($_POST['agentsOrderDDD']) || !is_array($_POST['agentsOrderDDD'])) error("Не переданы агенты!", 2);
        $id = (int)$_POST['id'];
        $agents = $_POST['agents'];
        if ($ordersManager->completeOrder($id, $agents)) {
            logging("adminOrdersHandler", json_encode($_POST['agentsOrderDDD']));
            $client = $ordersManager->getClientById($id);
            if ($client !== false) {
                $orderStatusData = [
                    'id' => $id,
                    'status' => 'completed',
                    'created_at' => $client['created_at'],
                    'client_name' => $client['username'],
                    'client_phone' => $client['userphone']
                ];
                $mailer = new Mailer();
                $mailer->sendOrderCompleted($client['useremail'], $orderStatusData, $_POST['agentsOrderDDD']);
            }
            response("OK", 200);
        }else{
            error("Непредвиденная ошибка", 3);
        }
        break;
    case "deleteById":
        if (!isset($_POST['id'])) error("Не передан ID заказа!", 0);
        if (!is_numeric($_POST['id'])) error("Передан некорректный ID заказа!", 1);
        $id = (int)$_POST['id'];
        $response = $ordersManager->deleteById($id);
        if ($response) {
            response("OK", 200);
        }else{
            error("Не удалось удалить запись!", 2);
        }
        break;

}