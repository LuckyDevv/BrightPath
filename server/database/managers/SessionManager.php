<?php
namespace managers;
use DateTime;
use Error;
use Exception;
use executors\SessionExecutor;
use PDO;
use PDOException;

class SessionManager {
    protected PDO $db;
    private SessionExecutor $commands;
    public bool $die = false;
    public function __construct()
    {
        try {
            @mkdir(__DIR__.'/../../logs/');
            $this->db = new PDO("mysql:host=localhost;", "root", "5328alexRU");
            $this->db->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
            $this->db->query("CREATE DATABASE IF NOT EXISTS `brightpath_admins`;");
            $this->db->query("SET NAMES utf8;");
            $this->db->query("USE `brightpath_admins`;");
            $this->commands = new SessionExecutor();
            $this->db->query($this->commands->createTable());
        }catch (PDOException|Exception|Error $e) {
            $this->die = true;
            $this->createLog("SessionManager", $e);
        }
    }

    public function verifySession(int $session_id, string $master_key): int
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM `admin_sessions` WHERE `id` = :session_id;");
            $stmt->execute([":session_id" => $session_id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $stmt->closeCursor();
            if (is_array($row) && count($row) > 0) {
                $encryptedData = $row["session_hash"];
                $decryptedData = $this->decodeSession($encryptedData, $master_key);
                if (is_array($decryptedData)) {
                    if ($decryptedData["session_expires_at"] > time()) {
                        $admin_login = $decryptedData["admin_login"];
                        $adminsManager = new AdminsManager();
                        if ($adminsManager->checkAdmin($admin_login)) {
                            $password_update_at = $adminsManager->getLastPassUpdate($admin_login);
                            if ($password_update_at !== null) {
                                $date = new DateTime($password_update_at);
                                if ($date->getTimestamp() < $decryptedData["session_created_at"]) {
                                    return 0; // Успешная проверка сессии
                                }else $err_code = 1; // ОШИБКА: Пароль изменён
                            }else $err_code = 2; // ОШИБКА: Не удалось получить дату обновления пароля
                        }else $err_code = 3; // ОШИБКА: Пользователь заблокирован
                    }else $err_code = 4; // ОШИБКА: Сессия истекла
                }else $err_code = 5; // ОШИБКА: Не удалось расшифровать сессию (неверный ключ)
            }else $err_code = 6; // ОШИБКА: Не найдена сессия с таким ID
        }catch (PDOException|Exception|Error $e) {
            $this->createLog("SessionManager", $e);
            $err_code = 7;
            // ОШИБКА: Произошла ошибка в базе данных
        }
        if ($err_code == 4 || $err_code == 3 || $err_code == 1) $this->removeSession($session_id);
        return $err_code;
    }

    public function removeSession(int $session_id): void
    {
        try {
            $stmt = $this->db->prepare("DELETE FROM `admin_sessions` WHERE `id` = :session_id;");
            $stmt->execute([":session_id" => $session_id]);
        }catch (PDOException|Exception|Error $e) {
            $this->createLog("SessionManager", $e);
        }
    }

    public function createSession(string $admin_login, string $master_key): ?int {
        try {
            $stmt = $this->db->prepare("INSERT INTO `admin_sessions` (`session_hash`) VALUES (:session_hash);");
            $sessionData = [
                'admin_login' => $admin_login,
                'session_created_at' => time(),
                'session_expires_at' => time() + 28800, // 8 часов
            ];
            // 5. Шифруем данные (ключ = masterKey)
            $iv = random_bytes(12);
            $encrypted = openssl_encrypt(
                json_encode($sessionData),
                'aes-256-gcm',
                $master_key,
                OPENSSL_RAW_DATA,
                $iv,
                $tag
            );
            $encryptedData = base64_encode($iv . $encrypted . $tag);
            $stmt->bindParam(":session_hash", $encryptedData);
            if ($stmt->execute()) {
                return $this->db->lastInsertId();
            }
        }catch (PDOException|Exception|Error $e) {
            $this->createLog("SessionManager", $e);
        }
        return null;
    }

    private function decodeSession(string $encryptedData, string $master_key): array|false
    {
        $decoded = base64_decode($encryptedData);
        $iv = substr($decoded, 0, 12);           // первые 12 байт — IV
        $tag = substr($decoded, -16);            // последние 16 байт — TAG
        $encrypted = substr($decoded, 12, -16);  // всё, что между — зашифрованные данные
        $decryptedJson = openssl_decrypt(
            $encrypted,
            'aes-256-gcm',
            $master_key,
            OPENSSL_RAW_DATA,
            $iv,
            $tag
        );
        $sessionData = json_decode($decryptedJson, true);
        if ($sessionData === null) {
            return false;
        }
        $admin_login = $sessionData['admin_login'];
        $session_created_at = $sessionData['session_created_at'];
        $session_expires_at = $sessionData['session_expires_at'];
        return [
            "admin_login" => $admin_login,
            "session_created_at" => $session_created_at,
            "session_expires_at" => $session_expires_at,
        ];
    }

    public function createLog(string $className, Exception|Error $exception): void
    {
        $text = "[" . date("d.m.Y H:i:s") . "] Произошла ошибка в ".$className.": ".$exception->getMessage().". На строке ".$exception->getLine().", файл: ".$exception->getFile()."\n\n";
        file_put_contents(__DIR__.'/../../logs/'.$className.'_Errors.log', $text, FILE_APPEND);
    }

    // ФУНКЦИЯ ДЛЯ ЛОГОВ ПРИ РАЗРАБОТКЕ
    public function createCustomLog(string $text): void
    {
        $text = "[" . date("d.m.Y H:i:s") . "] Лог: ".$text."\n\n";
        file_put_contents(__DIR__.'/../../logs/custom_log_admins.log', $text, FILE_APPEND);
    }
}