<?php

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/apiHelper.php';

use managers\GoodsManager;
use managers\OrdersManager;
use managers\ServicesManager;
use managers\VehiclesManager;

$ordersManager = new OrdersManager();

switch ($_POST['type'] ?? null) {
    case "getVehicleById":
        if (!isset($_POST['id'])) error("Не передан ID транспорта!", 0);
        if (!is_numeric($_POST['id'])) error("Передан некорректный ID транспорта!", 1);
        $vehicleId = (int)$_POST['id'];
        $vehicle = new VehiclesManager()->getVehicleByIdForCart($vehicleId);
        if (count($vehicle) > 0) {
            response(json_encode($vehicle), 200);
        }else error("Автомобиль с таким ID не найден!", 404);
        break;
    case "getGoodsById":
        if (!isset($_POST['id'])) error("Не передан ID транспорта!", 0);
        if (!is_numeric($_POST['id'])) error("Передан некорректный ID транспорта!", 1);
        $goodsId = (int)$_POST['id'];
        $goods = new GoodsManager()->getGoodsByIdForCart($goodsId);
        if (count($goods) > 0) {
            response(json_encode($goods), 200);
        }else error("Товар с таким ID не найден!", 404);
        break;
    case "getServiceById":
        if (!isset($_POST['id'])) error("Не передан ID транспорта!", 0);
        if (!is_numeric($_POST['id'])) error("Передан некорректный ID транспорта!", 1);
        $serviceId = (int)$_POST['id'];
        $service = new ServicesManager()->getServiceByIdForCart($serviceId);
        if (count($service) > 0) {
            response(json_encode($service), 200);
        }else error("Услуга с таким ID не найдена!", 404);
        break;
}