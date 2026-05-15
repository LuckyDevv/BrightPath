<?php

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/apiHelper.php';

use lib\AdminLogger;
use managers\AdminsManager;

$adminsManager = new AdminsManager();

$admin_login = getAdminLoginFromSession();

switch ($_POST['type'] ?? null) {
    case "getById":
        createAdminLog($admin_login, ["result"=>"processing"]);
        if (!isset($_POST['adminId'])) error("Не передан ID админа!", 0);
        if (!is_numeric($_POST['adminId'])) error("Некорректный ID админа!", 1);
        $adminId = (int) $_POST['adminId'];
        $admin = $adminsManager->getById($adminId);
        if (count($admin) > 0) {
            createAdminLog($admin_login, ["adminId"=>$adminId, "result"=>"success"]);
            response(json_encode($admin, JSON_OPTIONS), 200);
        }else{
            createAdminLog($admin_login, ["adminId"=>$adminId, "result"=>"error"]);
            error("Данный администратор не найден!", 2);
        }
        break;
    case "reset2FA":
        createAdminLog($admin_login, ["result"=>"processing"]);
        if (!isset($_POST['adminId'])) error("Не передан ID админа!", 0);
        if (!is_numeric($_POST['adminId'])) error("Некорректный ID админа!", 1);
        $adminId = (int) $_POST['adminId'];
        if ($adminsManager->resetTotp($adminId)) {
            createAdminLog($admin_login, ["adminId"=>$adminId, "result"=>"success"]);
            response("OK", 200);
        }else{
            createAdminLog($admin_login, ["adminId"=>$adminId, "result"=>"error"]);
            error("NOT SUCCESS", 400);
        }
        break;
    case "updateAdmin":
        createAdminLog($admin_login, ["result"=>"processing"]);
        if (!isset($_POST['adminData'])) error("Не переданы данные!", 0);
        $newData = json_decode($_POST['adminData'], true);
        if (!isset($newData['adminId'])) error("Не передан ID админа!", 1);
        if (!is_numeric($newData['adminId'])) error("Некорректный ID админа!", 2);
        $adminId = (int) $newData['adminId'];
        if (!isset($newData['role']) || trim($newData['role']) == '') error("Не передана роль!", 3);
        $roles = ['operator', 'manager', 'admin'];
        if (!in_array(trim($newData['role']), $roles)) error("Выбрана некорректная роль!", 4);
        $role = trim($newData['role']);
        if (!isset($newData['login']) || trim($newData['login']) == '') error("Не введён логин!", 5);
        $login = trim($newData['login']);
        $statuses = ['active' => false, 'blocked' => true];
        logging("adminAdminsHandler", json_encode($newData['is_locked']));
        if (!array_key_exists('is_locked', $newData) || !array_key_exists($newData['is_locked'], $statuses)) error("Не выбран статус аккаунта!", 6);
        if ($adminsManager->updateAdmin($adminId, $login, $role, $statuses[$newData['is_locked']])) {
            response("OK", 200);
        }else error("Не удалось обновить данные!", 7);
        break;
    case "resetPassword":
        createAdminLog($admin_login, ["result"=>"processing"]);
        if (!isset($_POST['adminId'])) error("Не передан ID админа!", 0);
        if (!is_numeric($_POST['adminId'])) error("Некорректный ID админа!", 1);
        $adminId = (int) $_POST['adminId'];
        $new_password = generate_password();
        if ($adminsManager->changePassword($adminId, $new_password)) {
            createAdminLog($admin_login, ["adminId"=>$adminId, "result"=>"success"]);
            response($new_password, 200);
        }else{
            createAdminLog($admin_login, ["adminId"=>$adminId, "result"=>"error"]);
            error("Не удалось сбросить пароль!", 2);
        }
        break;
    case "removeAccount":
        createAdminLog($admin_login, ["result"=>"processing"]);
        if (!isset($_POST['adminId'])) error("Не передан ID админа!", 0);
        if (!is_numeric($_POST['adminId'])) error("Некорректный ID админа!", 1);
        $adminId = (int) $_POST['adminId'];
        if ($adminsManager->removeAccount($adminId)) {
            createAdminLog($admin_login, ["adminId"=>$adminId, "result"=>"success"]);
            response("OK", 200);
        }else{
            createAdminLog($admin_login, ["adminId"=>$adminId, "result"=>"error"]);
            error("NOT SUCCESS", 400);
        }
        break;
    case "addAdmin":
        if (!isset($_POST['login']) || trim($_POST['login']) == '') error("Не введён логин!", 0);
        if (!isset($_POST['role']) || trim($_POST['role']) == '') error("Не выбрана роль!", 1);
        $roles = ['operator', 'manager', 'admin'];
        if (!in_array($_POST['role'], $roles)) error("Выбрана некорректная роль!", 2);
        $login = trim($_POST['login']);
        if (!$adminsManager->exists($login)) {
            $password = generate_password();
            $role = trim($_POST['role']);
            $result = $adminsManager->addAdminUser($login, $password, $role);
            if ($result !== false) {
                response("OK", 200, ['password'=>$password, 'id'=>$result]);
            }else error("Не удалось добавить администратора!", 3);
        }else error("Такой логин уже существует!", 4);
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

function createAdminLog(string $admin_login, array $data): void
{
    $adminLogger = new AdminLogger();
    $adminLogger->log("adminAdminsHandler", $admin_login, $_POST['type'], $data);
}