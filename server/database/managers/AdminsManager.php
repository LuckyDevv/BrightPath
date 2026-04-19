<?php

namespace managers;

use Error;
use Exception;
use executors\AdminsExecutor;
use PDO;
use PDOException;

class AdminsManager
{
    protected PDO $db;
    private AdminsExecutor $commands;
    public bool $die = false;
    public function __construct()
    {
        try {
            @mkdir(__DIR__.'/../../logs/');
            $this->db = new PDO("mysql:host=localhost;", "root", "5328alexRU");
            $this->db->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, false);
            $this->db->query("CREATE DATABASE IF NOT EXISTS `brightpath_admins`;");
            $this->db->query("SET NAMES utf8;");
            $this->db->query("USE `brightpath_admins`;");
            $this->commands = new AdminsExecutor();
            $this->db->query($this->commands->createTable());
        }catch (PDOException|Exception|Error $e) {
            $this->die = true;
            $this->createLog("AdminsManager", $e);
        }
    }

    public function addAdminUser(string $login, string $password, string $role = 'operator', ?string $totpSecret = null): false|int
    {
        try {
            // Подключение к БД
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // Проверка существования логина
            $checkStmt = $this->db->prepare("SELECT `id` FROM `admin_users` WHERE `login` = :login");
            $checkStmt->execute([':login' => $login]);
            if ($checkStmt->fetch()) {
                throw new Exception("Логин '{$login}' уже существует");
            }
            // Хеширование пароля
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            // Подготовка запроса
            $stmt = $this->db->prepare($this->commands->addAdmin());
            // Выполнение
            $stmt->execute([
                ':login' => $login,
                ':password_hash' => $passwordHash,
                ':role' => $role,
                ':is_locked' => 0,
                ':totp_secret' => $totpSecret
            ]);
            return $this->db->lastInsertId();
        } catch (PDOException|Exception|Error $e) {
            $this->createLog("AdminsManager", $e);
            return false;
        }
    }

    public function checkAdminUser(string $login, string $password): null|int
    {
        try {
            $stmt = $this->db->prepare($this->commands->checkUser());
            $result = $stmt->execute([':login' => $login]);
            if ($result) {
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                if (isset($result['is_locked'], $result['password_hash'], $result['is_2fa_enabled'])) {
                    if (!$result['is_locked']) {
                        $this->createCustomLog("Fetch: ".json_encode($result));
                        if ($result['is_2fa_enabled'] == 1) {
                            if (array_key_exists('totp_secret', $result) && $result['totp_secret'] !== "") {
                                if (password_verify($password, $result['password_hash'])) {
                                    return 0;
                                }else return 1;
                            }else return 2;
                        }else return 2;
                    }else return 3;
                }else return 4;
            }else return 4;
        }catch (PDOException|Exception|Error $e) {
            $this->createLog("AdminsManager", $e);
            return 5;
        }
    }

    public function getRole(string $login): ?string
    {
        try {
            $stmt = $this->db->prepare("SELECT `role` FROM `admin_users` WHERE `login` = :login");
            $stmt->execute([':login' => $login]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if (is_array($result) && isset($result['role'])) {
                return $result['role'];
            }
        }catch (PDOException|Exception|Error $e) {
            $this->createLog("AdminsManager", $e);
        }
        return null;
    }

    public function getTotpSecret(string $login): ?string
    {
        try {
            $stmt = $this->db->prepare("SELECT `totp_secret` FROM `admin_users` WHERE `login` = :login");
            $result = $stmt->execute([':login' => $login]);
            if ($result) {
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                if (isset($result['totp_secret'])) {
                    return $result['totp_secret'];
                }
            }
        }catch (PDOException|Exception|Error $e) {
            $this->createLog("AdminsManager", $e);
        }
        return null;
    }

    public function checkAdmin(string $login): ?bool
    {
        try {
            $this->createCustomLog("Логин проверяемого админа: ".$login);
            $stmt = $this->db->prepare("SELECT `is_active` FROM `admin_users` WHERE `login` = :login");
            $stmt->bindValue(":login", $login, PDO::PARAM_STR);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->createCustomLog("Fetch по логину: ".json_encode($result));
            if (is_array($result) && count($result) > 0) {
                return !((bool) $result['is_active']);
            }
        }catch (PDOException|\Exception|\Error $e) {
            $this->createLog("AgentsManager", $e);
        }
        return true;
    }

    public function getLastPassUpdate(string $login): ?string
    {
        try {
            $stmt = $this->db->prepare("SELECT `password_updated_at` FROM `admin_users` WHERE `login` = :login");
            $stmt->execute([':login' => $login]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if (isset($result['password_updated_at'])) {
                return $result['password_updated_at'];
            }
        }catch (PDOException|Exception|Error $e) {
            $this->createLog("AdminsManager", $e);
        }
        return null;
    }

    public function setupTotp(string $login, string $secret, bool $is_enabled = true): bool
    {
        try {
            $stmt = $this->db->prepare("UPDATE `admin_users` SET `is_2fa_enabled` = :is_2fa_enabled, `totp_secret` = :totp_secret WHERE `login` = :login");
            $stmt->bindParam(':login', $login);
            $stmt->bindParam(':is_2fa_enabled', $is_enabled, PDO::PARAM_BOOL);
            $stmt->bindParam(':totp_secret', $secret, PDO::PARAM_STR);
            $result = $stmt->execute();
            if ($result) {
                return true;
            }
        }catch (PDOException|Exception|Error $e) {
            $this->createLog("AdminsManager", $e);
        }
        return false;
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