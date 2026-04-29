<?php

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/apiHelper.php';

use managers\AdminsManager;

$adminsManager = new AdminsManager();

logging("adminAuthHandler", "Тип запроса: " . ($_POST["type"] ?? "не передано"));

switch ($_POST['type'] ?? null) {
    case "getById":
        if (!isset($_POST['adminId'])) error("Не передан ID админа!", 0);
        if (!is_numeric($_POST['adminId'])) error("Некорректный ID админа!", 1);
        $adminId = (int) $_POST['adminId'];
        $admin = $adminsManager->getById($adminId);
        if (count($admin) > 0) {
            response(json_encode($admin, JSON_OPTIONS), 200);
        }
        break;
    case "reset2FA":
        if (!isset($_POST['adminId'])) error("Не передан ID админа!", 0);
        if (!is_numeric($_POST['adminId'])) error("Некорректный ID админа!", 1);
        $adminId = (int) $_POST['adminId'];
        if ($adminsManager->resetTotp($adminId)) {
            response("OK", 200);
        }else error("NOT SUCCESS", 400);
        break;
    case "updateAdmin":
        if (!isset($_POST['adminData'])) error("Не переданы данные!", 0);
        $_POST = json_decode($_POST['adminData'], true);
        logging("adminAuthHandler", json_encode($_POST));
        if (!isset($_POST['adminId'])) error("Не передан ID админа!", 1);
        if (!is_numeric($_POST['adminId'])) error("Некорректный ID админа!", 2);
        $adminId = (int) $_POST['adminId'];
        if (!isset($_POST['role'])) error("Не передана роль!", 3);
        break;
    case "resetPassword":
        if (!isset($_POST['adminId'])) error("Не передан ID админа!", 0);
        if (!is_numeric($_POST['adminId'])) error("Некорректный ID админа!", 1);
        $adminId = (int) $_POST['adminId'];
        $new_password = generate_password();
        if ($adminsManager->changePassword($adminId, $new_password)) {
            response($new_password, 200);
        }else error("Не удалось сбросить пароль!", 2);
        break;
    case "removeAccount":
        if (!isset($_POST['adminId'])) error("Не передан ID админа!", 0);
        if (!is_numeric($_POST['adminId'])) error("Некорректный ID админа!", 1);
        $adminId = (int) $_POST['adminId'];
        if ($adminsManager->removeAccount($adminId)) {
            response("OK", 200);
        }else error("NOT SUCCESS", 400);
        break;
}

function generate_password($length = 12): string
{
    $chars = 'qazxswedcvfrtgbnhyujmkiolp1234567890QAZXSWEDCVFRTGBNHYUJMKIOLP@#-=+$%';
    $size = strlen($chars) - 1;
    $password = '';
    while($length--) {
        $password .= $chars[random_int(0, $size)];
    }
    return $password;
}