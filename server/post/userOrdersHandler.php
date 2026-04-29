<?php

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/apiHelper.php';

use managers\OrdersManager;
$ordersManager = new OrdersManager();

logging("userOrdersHandler", "Тип запроса: " . ($_POST["type"] ?? "не передано"));

switch ($_POST['type'] ?? null) {
    case "newOrder":
        if (!isset($_POST['transport'])) error("Не выбран транспорт", 0);
        if (!isset($_POST['goods'])) error("Не выбраны товары!", 1);
        if (!isset($_POST['services'])) error("Не выбраны услуги!", 2);
        if (!isset($_POST['userName']) || trim($_POST['userName']) == '') error("Введите имя!", 3);
        if (!isset($_POST['userPhone']) || trim($_POST['userPhone']) == '') error("Введите номер телефона!", 4);
        if (!isset($_POST['userEmail']) || trim($_POST['userEmail']) == '') error("Введите электронную почту!", 5);
        $transport = json_decode($_POST['transport'], true);
        $goods = json_decode($_POST['goods'], true);
        $services = json_decode($_POST['services'], true);
        $transport_result = [];
        $goods_result = [];
        $services_result = [];
        $summary = 0;
        foreach ($transport as $item) {
            $transport_result[] = [
                "vehicleId" => (int) $item['item_id'],
                "quantity" => (int) $item['quantity'],
                "price" => (int) $item['price'],
                "name" => (string) $item['name']
            ];
            $summary += (int) $item['quantity'] * (int) $item['price'];
        }
        foreach ($goods as $item) {
            $goods_result[] = [
                "goodsId" => (int) $item['item_id'],
                "quantity" => (int) $item['quantity'],
                "price" => (int) $item['price'],
                "name" => (string) $item['name']
            ];
            $summary += (int) $item['quantity'] * (int) $item['price'];
        }
        foreach ($services as $item) {
            $services_result[] = [
                "serviceId" => (int) $item['item_id'],
                "quantity" => (int) $item['quantity'],
                "price" => (int) $item['price'],
                "name" => (string) $item['name']
            ];
            $summary += (int) $item['quantity'] * (int) $item['price'];
        }
        $result = $ordersManager->addOrder(
            trim($_POST['userName']),
            trim($_POST['userPhone']),
            trim($_POST['userEmail']),
            json_encode($transport_result),
            json_encode($goods_result),
            json_encode($services_result),
            $summary
        );
        if ($result !== false) {
            response($result, 200);
        }else error("Не удалось создать заказ!", 6);
        break;
}
