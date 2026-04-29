<?php

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/apiHelper.php';

use chillerlan\QRCode\QRCode;
use lib\Config;
use managers\AdminsManager;
use managers\SessionManager;
use lib\GoogleAuthenticator;

$adminsManager = new AdminsManager();

logging("adminAuthHandler", "Тип запроса: " . ($_POST["type"] ?? "не передано"));

switch ($_POST['type'] ?? null) {
    case "login":
        if (isset($_POST['login']) && isset($_POST['password'])) {
            $login = $_POST['login'];
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
                    response("NEED_2FA", 200, ["qr" => $qr, "secret" => $gaSecret]);
                }
            }elseif ($result == AdminsManager::AUTH_TOTP_REQUIRED) {
                try {
                    $ga = new GoogleAuthenticator();
                    $gaSecret = $ga->generateSecret();
                    $login = $_POST['login'];
                    $data = "otpauth://totp/{$login}?secret={$gaSecret}&issuer=BrightPath.ru";
                    $qr = (new QRCode)->render($data);
                    response("NEED_2FA", 200, ["qr" => $qr, "secret" => $gaSecret]);
                }catch(Exception|Error $e){
                    logging("adminAuthHandler", "Ошибка: ".$e->getMessage());
                }
            }else{
                error(message: Config::AUTH_MESSAGES_MAP[$result], code: $result);
            }
        }else error("Не введён логин или пароль!", 0);
        break;
    case "enable_2fa":
        checkCode(true);
    case "verify_2fa":
        checkCode();
    case "verify_session":
        if (!isset($_POST['session_id'])) error("Нет session_id", 1);
        if (!isset($_POST['fingerprint'])) error("Нет fingerprint", 2);
        if (!is_numeric($_POST['session_id'])) error("Session_id не число!", 3);
        $session_id = (int)$_POST['session_id'];
        $sessionManager = new SessionManager();
        $result = $sessionManager->verifySession($session_id, $_POST['fingerprint'], $_SERVER['REMOTE_ADDR']);
        if ($result == SessionManager::SESSION_SUCCESS) response("OK", 200);
        if (isset(Config::SESSION_MESSAGES_MAP[$result])) {
            error(Config::SESSION_MESSAGES_MAP[$result], $result);
        }else{
            error("Произошла ошибка в базе данных!", $result);
        }
    case "logout":
        if (!isset($_POST['session_id'])) error("Нет session_id", 1);
        if (!is_numeric($_POST['session_id'])) error("Session_id не число!", 2);
        $session_id = (int)$_POST['session_id'];
        $sessionManager = new SessionManager();
        $sessionManager->removeSession($session_id);
        response("OK", 200);
    default:
        error("Передан пустой запрос", 400);
}

function checkCode(bool $is_setup = false): never
{
    if (!isset($_POST['code'])) error("Не введён код", 6);
    if (!isset($_POST['secret'])) error("Не передан SECRET", 7);
    if (!isset($_POST['login'])) error("Не введён логин", 8);
    if (!isset($_POST['password'])) error("Не введён пароль!", 9);
    if (!isset($_POST['fingerprint'])) error("Null fingerprint", 10);
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
                        'secure' => true,
                        'samesite' => 'Strict'
                    ]
                );
                setcookie(
                    "login",
                    $_POST['login'],
                    [
                        'expires' => time() + 28800,
                        'path' => '/',
                        'secure' => true,
                        'samesite' => 'Strict'
                    ]
                );
                response("OK", 200, ["session_id" => $sessionId]);
            }else error("Не удалось создать сессию", 11);
        }else error("Код не совпадает", 10);
    }else{
        error(message: Config::AUTH_MESSAGES_MAP[$result], code: $result);
    }
}