<?php
require_once __DIR__."/../vendor/autoload.php";
use managers\OrdersManager;

header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);
$orderId = isset($input['orderId']) ? (int)$input['orderId'] : 0;
$email = isset($input['email']) ? trim($input['email']) : '';

if ($orderId <= 0 || empty($email)) {
    echo json_encode(['success' => false, 'error' => 'Неверные данные']);
    exit;
}

$ordersManager = new OrdersManager();
$order = $ordersManager->getOrderByIdAndEmail($orderId, $email)[0];

if ($order) {
    echo json_encode([
        'success' => true,
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
} else {
    echo json_encode(['success' => false, 'error' => 'Заказ не найден']);
}
