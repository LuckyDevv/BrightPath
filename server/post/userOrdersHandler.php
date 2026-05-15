<?php

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/apiHelper.php';

use Egulias\EmailValidator\EmailValidator;
use Egulias\EmailValidator\Validation\DNSCheckValidation;
use Egulias\EmailValidator\Validation\MultipleValidationWithAnd;
use Egulias\EmailValidator\Validation\RFCValidation;
use lib\Mailer;
use managers\GoodsManager;
use managers\OrdersManager;
use managers\ServicesManager;
use managers\VehiclesManager;

$ordersManager = new OrdersManager();

switch ($_POST['type'] ?? null) {
    case "newOrder":
        try {
            if (!isset($_POST['transport'])) error("Не выбран транспорт", 0);
            if (!isset($_POST['goods'])) error("Не выбраны товары!", 1);
            if (!isset($_POST['services'])) error("Не выбраны услуги!", 2);
            if (!isset($_POST['userName']) || trim($_POST['userName']) == '') error("Введите имя!", 3);
            if (!isset($_POST['userPhone']) || trim($_POST['userPhone']) == '') error("Введите номер телефона!", 4);
            if (!isset($_POST['userEmail']) || trim($_POST['userEmail']) == '') error("Введите электронную почту!", 5);
            $validator = new EmailValidator();
            $multipleValidations = new MultipleValidationWithAnd([
                new RFCValidation(),
                new DNSCheckValidation()
            ]);
            if (!isValidPhone(trim($_POST['userPhone']))) error("Введён некорректный номер телефона!", 7);
            if (!$validator->isValid(trim($_POST['userEmail']), $multipleValidations)) error("Введена некорректная почта!", 8);
            $transport = json_decode($_POST['transport'], true);
            $goods = json_decode($_POST['goods'], true);
            $services = json_decode($_POST['services'], true);
            $transport_result = [];
            $goods_result = [];
            $services_result = [];
            $summary = 0;
            $vehiclesManager = new VehiclesManager();
            $allItems = [];
            foreach ($transport as $item) {
                $transport_result[] = [
                    "vehicleId" => (int)$item['id'],
                    "quantity" => (int)$item['quantity'],
                    "price" => (int)$item['price'],
                    "name" => (string)$item['name']
                ];
                $allItems[] = [
                    "quantity" => (int)$item['quantity'],
                    "price" => (int)$item['price'],
                    "name" => (string)$item['name']
                ];
                logging("userOrdersHandler", "Id vehicle: ".$item['id']);
                $vehiclesManager->order((int)$item['id'], (int)$item['quantity']);
                $summary += (int)$item['quantity'] * (int)$item['price'];
            }
            $goodsManager = new GoodsManager();
            foreach ($goods as $item) {
                $goods_result[] = [
                    "goodsId" => (int)$item['id'],
                    "quantity" => (int)$item['quantity'],
                    "price" => (int)$item['price'],
                    "name" => (string)$item['name']
                ];
                $allItems[] = [
                    "quantity" => (int)$item['quantity'],
                    "price" => (int)$item['price'],
                    "name" => (string)$item['name']
                ];
                $goodsManager->order((int)$item['id'], (int)$item['quantity']);
                $summary += (int)$item['quantity'] * (int)$item['price'];
            }
            $servicesManager = new ServicesManager();
            foreach ($services as $item) {
                $services_result[] = [
                    "serviceId" => (int)$item['id'],
                    "quantity" => (int)$item['quantity'],
                    "price" => (int)$item['price'],
                    "name" => (string)$item['name']
                ];
                $allItems[] = [
                    "quantity" => (int)$item['quantity'],
                    "price" => (int)$item['price'],
                    "name" => (string)$item['name']
                ];
                $servicesManager->order((int)$item['id'], (int)$item['quantity']);
                $summary += (int)$item['quantity'] * (int)$item['price'];
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
                $mailer = new Mailer();
                $emailBody = $mailer->sendOrderCreated(trim($_POST['userEmail']), [
                    'id' => $result,
                    'status' => 'created',
                    'created_at' => new DateTime()->format("d.m.Y H:i:s"),
                    'client_name' => trim($_POST['userName']),
                    'client_phone' => trim($_POST['userPhone']),
                    'total_price' => $summary,
                    'items' => $allItems
                ]);
                response($result, 200);
            } else error("Не удалось создать заказ!", 6);
        }catch (Exception|Error $e) {
            logging("userOrdersHandler", $e->getMessage());
        }
        break;
    case "checkStatus":
        if (!isset($_POST['orderId'])) error("Не передан номер заказа!", 0);
        if (!is_numeric($_POST['orderId'])) error("Введите номер заказа числом!", 1);
        if (!isset($_POST['email'])) error("Не передан адрес эл. почты!", 2);
        $orderId = (int)$_POST['orderId'];
        $email = trim($_POST['email']);
        $order = $ordersManager->getOrderByIdAndEmail($orderId, $email);
        if (count($order) > 0) {
            response("OK", 200, [
                'order' => [
                    'id' => $order['id'],
                    'status' => $order['status'],
                    'created_at' => $order['created_at'],
                    'summary' => (float)$order['summary'],
                    'userphone' => $order['userphone'],
                    'useremail' => $order['useremail'],
                    'transport' => json_decode($order['transport'], true) ?: [],
                    'goods' => json_decode($order['goods'], true) ?: [],
                    'services' => json_decode($order['services'], true) ?: [],
                    'agents' => $order['agents'] ? explode(',', $order['agents']) : []
                ]
            ]);
        } else error("Заказ не найден!", 404);
}

function isValidPhone(string $phone): bool
{
    // Удаляем все символы, кроме цифр и знака +
    $cleaned = preg_replace('/[^0-9+]/', '', $phone);
    // Проверяем, что номер начинается с +7 или 8 и содержит 11 цифр
    $digits = preg_replace('/[^0-9]/', '', $cleaned);
    // Российские номера: 11 цифр (8 или +7 в начале)
    if (strlen($digits) === 11 && ($digits[0] === '7' || $digits[0] === '8')) {
        return true;
    }
    return false;
}