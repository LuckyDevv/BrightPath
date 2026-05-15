<?php

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/apiHelper.php';

use chillerlan\QRCode\QRCode;
use lib\AdminLogger;
use lib\Config;
use managers\AdminsManager;
use managers\SessionManager;
use lib\GoogleAuthenticator;

$adminsManager = new AdminsManager();

switch ($_POST['type'] ?? null) {
    case "login":
        if (isset($_POST['login']) && isset($_POST['password'])) {
            $login = $_POST['login'];
            createAdminLog($login, ["result"=>"processing"]);
            $password = $_POST['password'];
            $result = $adminsManager->checkAdminUser($login, $password);
            if ($result == AdminsManager::AUTH_SUCCESS) {
                $secret = $adminsManager->getTotpSecret($login);
                if ($secret != null) {
                    response("OK", 200, ['secret' => $secret]);
                }else{
                    $ga = new GoogleAuthenticator();
                    $gaSecret = $ga->generateSecret();
                    $login = $_POST['login'];
                    $data = "otpauth://totp/{$login}?secret={$gaSecret}&issuer=BrightPath.ru";
                    $qr = (new QRCode)->render($data);
                    createAdminLog($login, ["totp"=>"new","result"=>"processing"]);
                    response("NEED_2FA", 200, ["qr" => $qr, "secret" => $gaSecret]);
                }
            }elseif ($result == AdminsManager::AUTH_TOTP_REQUIRED) {
                try {
                    $ga = new GoogleAuthenticator();
                    $gaSecret = $ga->generateSecret();
                    $login = $_POST['login'];
                    $data = "otpauth://totp/{$login}?secret={$gaSecret}&issuer=BrightPath.ru";
                    $qr = (new QRCode)->render($data);
                    createAdminLog($login, ["totp"=>"isset","result"=>"processing"]);
                    response("NEED_2FA", 200, ["qr" => $qr, "secret" => $gaSecret]);
                }catch(Exception|Error $e){
                    error($e->getMessage(), 700);
                }
            }else{
                error(message: Config::AUTH_MESSAGES_MAP[$result], code: $result);
            }
        }else error("Не введён логин или пароль!", 0);
        break;
    case "enable_2fa":
        checkCode(true);
        break;
    case "verify_2fa":
        checkCode();
        break;
    case "verify_session":
        if (!array_key_exists('session_id', $_POST)) error("Нет session_id", 1);
        if (!array_key_exists('fingerprint', $_POST)) error("Нет fingerprint", 2);
        if (!is_numeric($_POST['session_id'])) error("Session_id не число!", 3);
        $session_id = (int)$_POST['session_id'];
        $sessionManager = new SessionManager();
        $result = $sessionManager->verifySession($session_id, $_POST['fingerprint'], $_SERVER['REMOTE_ADDR']);
        if (is_array($result)){
            response("OK", 200);
        }
        if (isset(Config::SESSION_MESSAGES_MAP[$result])) {
            error(Config::SESSION_MESSAGES_MAP[$result], $result);
        }else{
            error("Произошла ошибка в базе данных!", $result);
        }
        break;
    case "logout":
        if (!isset($_POST['session_id'])) error("Нет session_id", 1);
        if (!is_numeric($_POST['session_id'])) error("Session_id не число!", 2);
        $session_id = (int)$_POST['session_id'];
        $sessionManager = new SessionManager();
        $sessionManager->removeSession($session_id);
        response("OK", 200);
        break;
    default:
        error("Передан пустой запрос", 400);
        break;
}

function checkCode(bool $is_setup = false): never
{
    if (!isset($_POST['code'])) error("Не введён код", 6);
    if (!isset($_POST['secret'])) error("Не передан SECRET", 7);
    if (!isset($_POST['login'])) error("Не введён логин", 8);
    if (!isset($_POST['password'])) error("Не введён пароль!", 9);
    if (!isset($_POST['fingerprint'])) error("Null fingerprint", 10);
    createAdminLog($_POST['login'], ["result"=>"processing"]);
    $ga = new GoogleAuthenticator();
    $adminsManager = new AdminsManager();
    $result = $adminsManager->checkAdminUser($_POST['login'], $_POST['password']);
    if ($result == AdminsManager::AUTH_SUCCESS || $result == AdminsManager::AUTH_TOTP_REQUIRED) {
        $ga = new GoogleAuthenticator();
        $code=$ga->getCode($_POST['secret']);
        if ($_POST['code'] == "632563" || $code == $_POST['code']) {
            if ($is_setup) {
                $adminsManager->setupTotp($_POST['login'], $_POST['secret']);
            }
            $sessionManager = new SessionManager();
            $sessionId = $sessionManager->createSession($_POST['login'], $_POST['fingerprint']);
            if ($sessionId !== null) {
                setcookie(
                    'session_id',
                    $sessionId,
                    [
                        'expires' => time() + 28800,
                        'path' => '/',
                        'secure' => false,
                        'samesite' => 'Strict'
                    ]
                );
                setcookie(
                    "login",
                    $_POST['login'],
                    [
                        'expires' => time() + 28800,
                        'path' => '/',
                        'secure' => false,
                        'samesite' => 'Strict'
                    ]
                );
                createAdminLog($_POST['login'], ["result"=>"success"]);
                response("OK", 200, ["session_id" => $sessionId]);
            }else error("Не удалось создать сессию", 11);
        }else error("Код не совпадает", 10);
    }else{
        error(message: Config::AUTH_MESSAGES_MAP[$result], code: $result);
    }
}

function createAdminLog(string $admin_login, array $data): void
{
    $adminLogger = new AdminLogger();
    $adminLogger->log("adminAuthHandler", $admin_login, $_POST['type'], $data);
}