<?php

namespace managers;

use Error;
use Exception;
use lib\Config;
use PDO;
use PDOException;

class Manager
{
    protected PDO $db;

    // Manager.php
    public function __construct(string $createTableCommand, bool $is_admin = false)
    {
        try {
            @mkdir(__DIR__.'/../../logs/');
            @mkdir(__DIR__.'/../../logs/db');
            @mkdir(__DIR__.'/../../logs/dev');
            $this->db = new PDO(Config::MYSQL_CONNECTION, Config::MYSQL_USER, Config::MYSQL_PASSWORD);
            $this->db->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $database_name = $is_admin ? "brightpath_admins" : "brightpath";
            $this->db->exec("CREATE DATABASE IF NOT EXISTS `".$database_name."`;");
            $this->db->exec("SET NAMES utf8mb4;");
            $this->db->exec("USE `".$database_name."`;");
            $this->db->exec($createTableCommand);
        } catch (PDOException $e){
            $this->createLog("ParentManager", $e);
            die();
        }
    }

    protected function createLog(string $className, Exception|Error $exception): void
    {
        $text = "[" . date("d.m.Y H:i:s") . "] Произошла ошибка в ".$className.": ".$exception->getMessage().". На строке ".$exception->getLine().", файл: ".$exception->getFile()."\n\n";
        file_put_contents(__DIR__.'/../../logs/db/'.$className.'_errors.log', $text, FILE_APPEND);
    }

    // ФУНКЦИЯ ДЛЯ ЛОГОВ ПРИ РАЗРАБОТКЕ
    protected function createCustomLog(string $className, string $text): void
    {
        $text = "[" . date("d.m.Y H:i:s") . "] Лог: ".$text."\n\n";
        file_put_contents(__DIR__.'/../../logs/dev/'.$className.'_dev.log', $text, FILE_APPEND);
    }

    public function getLastInsertId(): false|string
    {
        return $this->db->lastInsertId();
    }
}