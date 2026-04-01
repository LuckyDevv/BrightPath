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

    public function checkAdminUser(string $login, string $password): null|bool
    {
        try {
            $stmt = $this->db->prepare($this->commands->checkUser());
            $stmt->execute([':login' => $login, ':password_hash' => password_hash($password, PASSWORD_DEFAULT)]);
            if ($stmt->fetch()) {
                return true;
            }else{
                return false;
            }
        }catch (PDOException|Exception|Error $e) {
            $this->createLog("AdminsManager", $e);
            return null;
        }
    }

    public function createLog(string $className, Exception|Error $exception): void
    {
        $text = "[" . date("d.m.Y H:i:s") . "] Произошла ошибка в ".$className.": ".$exception->getMessage().". На строке ".$exception->getLine().", файл: ".$exception->getFile()."\n\n";
        file_put_contents(__DIR__.'/../../logs/'.$className.'_Errors.log', $text, FILE_APPEND);
    }
}