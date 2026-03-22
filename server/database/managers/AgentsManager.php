<?php
namespace managers;

use PDO;
use PDOException;
use executors\VehiclesExecutor;

class AgentsManager {
    protected PDO $db;
    public bool $die = false;
    public function __construct() {
        try {
            @mkdir(__DIR__.'/../logs/');
            $this->db= new PDO("mysql:host=localhost;", "root", "5328alexRU");
            $this->db->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, false);
            $this->db->query("CREATE DATABASE IF NOT EXISTS `brightpath`;");
            $this->db->query("SET NAMES utf8;");
            $this->db->query("USE `brightpath`;");
            $this->db->query(new VehiclesExecutor()->createAgentsTable());
        }catch (PDOException $e){
            $this->die = true;
            $this->createLog("AgentsManager", $e);
        }
    }
    public function addVehicle(){

    }
    protected function createLog(string $className, PDOException $exception): void
    {
        $text = "[" . date("d.m.Y H:i:s") . "] Произошла ошибка в ".$className.": ".$exception->getMessage().". На строке ".$exception->getLine().", файл: ".$exception->getFile()."\n\n";
        file_put_contents(__DIR__.'/../logs/'.$className.'_Errors.log', $text, FILE_APPEND);
    }
}