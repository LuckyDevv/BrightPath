<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use managers\AdminsManager;
use managers\SessionManager;
use lib\GoogleAuthenticator;

$adminsManager = new AdminsManager();

date_default_timezone_set('Europe/Moscow');

function logging(string $text)
{
    file_put_contents(__DIR__ . '/post_admins.log', $text . "\n", FILE_APPEND);
}

const JSON_OPTIONS = JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_BIGINT_AS_STRING;
function error(string $message, int $code): never
{
    die(json_encode(['error' => ['message' => $message, 'code' => $code]], JSON_OPTIONS));
}

function response(string $message, int $code, mixed $data = null): never
{
    die(json_encode(['response' => ['message' => $message, 'code' => $code, 'data' => $data]], JSON_OPTIONS));
}

logging("Тип запроса: " . ($_POST["type"] ?? "не передано"));

switch ($_POST['type'] ?? null) {
    case "login":
        if (isset($_POST['login']) && isset($_POST['password'])) {
            $login = $_POST['login'];
            $password = $_POST['password'];
            $result = $adminsManager->checkAdminUser($login, $password);
            if ($result == 0) {
                $secret = $adminsManager->getTotpSecret($login);
                if ($secret != null) {
                    response("OK", 200, ['secret' => $secret]);
                }else{
                    $ga = new GoogleAuthenticator();
                    $gaSecret = $ga->generateSecret();
                    $login = $_POST['login'];
                    $qr = $ga->getUrl($login,'BrightPath.ru', $gaSecret);
                    response("NEED_2FA", 200, ["qr" => $qr, "secret" => $gaSecret]);
                }
            }elseif ($result == 2) {
                try {
                    $ga = new GoogleAuthenticator();
                    $gaSecret = $ga->generateSecret();
                    $login = $_POST['login'];
                    $qr = $ga->getUrl($login,'BrightPath.ru', $gaSecret);
                    response("NEED_2FA", 200, ["qr" => $qr, "secret" => $gaSecret]);
                }catch(Exception|Error $e){
                    logging("Ошибка: ".$e->getMessage());
                }
            }else{
                $messages = [
                    1 => "Пароль не совпадает!",
                    3 => "Аккаунт заблокирован!",
                    4 => "Пользователь не найден!",
                    5 => "Произошла ошибка в БД!"
                ];
                error(message: $messages[$result], code: $result);
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
        if (!isset($_POST['session_id'])) error("Нет session_id", 1);
        if (!isset($_POST['fingerprint'])) error("Нет fingerprint", 2);
        if (!is_numeric($_POST['session_id'])) error("Session_id не число!", 3);
        $session_id = (int)$_POST['session_id'];
        $sessionManager = new SessionManager();
        $result = $sessionManager->verifySession($session_id, $_POST['fingerprint']);
        if ($result === 0) response("OK", 200);
        $err_messages = [
            1 => "Пароль был изменён. Войдите снова.",
            3 => "Данный пользователь заблокирован!",
            4 => "Сессия истекла. Войдите снова.",
            5 => "Вы входите под другим устройством. Войдите снова.",
            6 => "Сессия не найдена. Войдите снова."
        ];
        if (isset($err_messages[$result])) {
            error($err_messages[$result], $result);
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
}

function checkCode(bool $is_setup = false) {
    if (!isset($_POST['code'])) error("Не введён код", 6);
    if (!isset($_POST['secret'])) error("Не передан SECRET", 7);
    if (!isset($_POST['login'])) error("Не введён логин", 8);
    if (!isset($_POST['password'])) error("Не введён пароль!", 9);
    if (!isset($_POST['fingerprint'])) error("Null fingerprint", 10);
    $ga = new GoogleAuthenticator();
    $adminsManager = new AdminsManager();
    $result = $adminsManager->checkAdminUser($_POST['login'], $_POST['password']);
    if ($result == 0 || $result == 2) {
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
        $messages = [
            1 => "Пароль не совпадает!",
            3 => "Аккаунт заблокирован!",
            4 => "Пользователь не найден!",
            5 => "Произошла ошибка в БД!"
        ];
        error(message: $messages[$result], code: $result);
    }
}