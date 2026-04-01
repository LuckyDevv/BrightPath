<?php
namespace managers;

use Error;
use Exception;
use PDO;
use PDOException;
use executors\VehiclesExecutor;

class VehiclesManager
{
    protected PDO $db;
    private VehiclesExecutor $commands;
    public bool $die = false;
    public function __construct()
    {
        try {
            @mkdir(__DIR__.'/../../logs/');
            $this->db= new PDO("mysql:host=localhost;", "root", "5328alexRU");
            $this->db->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, false);
            $this->db->query("CREATE DATABASE IF NOT EXISTS `brightpath`;");
            $this->db->query("SET NAMES utf8;");
            $this->db->query("USE `brightpath`;");
            $this->commands = new VehiclesExecutor();
            $this->db->query($this->commands->createTable());
        }catch (PDOException|Exception|Error $e) {
            $this->die = true;
            $this->createLog("VehiclesManager", $e);
        }
    }
    public function getAllVehicles(): array
    {
        try {
            $stmt = $this->db->prepare($this->commands->getAllVehicles());
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException|Exception|Error $e) {
            $this->createLog("VehiclesManager", $e);
            return [];
        }
    }

    public function deleteVehicleById(int $id): bool
    {
        try {
            $stmt = $this->db->prepare($this->commands->getDeleteVehicle());
            $stmt->bindValue(":vehicleId", $id, PDO::PARAM_INT);
            return $stmt->execute();
        }catch (PDOException|Exception|Error $e) {
            $this->createLog("VehiclesManager", $e);
            return false;
        }
    }

    public function getVehicleById(int $idVehicle): array
    {
        try {
            $stmt = $this->db->prepare($this->commands->getVehicleById());
            $stmt->execute([':id' => $idVehicle]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException|Exception|Error $e) {
            $this->createLog("VehiclesManager", $e);
            return [];
        }
    }

    public function addVehicle(array $data): int|false
    {
        try {
            // Проверка обязательных полей
            $required = ['name', 'image_path', 'seats', 'creation_year', 'color', 'price'];
            foreach ($required as $field) {
                if (empty($data[$field])) {
                    throw new Exception("Поле '$field' обязательно для заполнения");
                }
            }

            $stmt = $this->db->prepare($this->commands->addVehicle());



            $stmt->execute([
                ':name' => $data['name'],
                ':image_path' => rtrim($data['image_path'], '/'),
                ':category' => $data['category'] ?? 1,
                ':seats' => $data['seats'] ?? 2,
                ':creation_year' => $data['creation_year'],
                ':color' => $data['color'],
                ':mileage' => $data['mileage'] ?? 0,
                ':vin' => $data['vin'] ?? '',
                ':transmission' => $data['transmission'] ?? 1,
                ':fuel' => $data['fuel'] ?? 1,
                ':description_short' => $data['description_short'] ?? null,
                ':description_full' => $data['description_full'] ?? null,
                ':price' => $data['price'],
                ':total_stock' => $data['total_stock'] ?? 1,
                ':reserved_stock' => $data['reserved_stock'] ?? 0,
                ':is_active' => $data['is_active'] ?? 1,
                ':orders_count' => $data['orders_count'] ?? 0
            ]);

            return $this->db->lastInsertId();

        } catch (PDOException|Exception|Error $e) {
            $this->createLog("VehiclesManager", $e);
            return false;
        }
    }

    public function getPopular(): array
    {
        try {
            $stmt = $this->db->prepare($this->commands->getPopularVehicles());
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }catch (PDOException|Exception|Error $e) {
            $this->createLog("VehiclesManager", $e);
            return [];
        }
    }

    public function getAllAdmin(): array
    {
        try {
            $stmt = $this->db->prepare($this->commands->getAllVehiclesForAdmin());
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }catch (PDOException|Exception|Error $e) {
            $this->createLog("VehiclesManager", $e);
            return [];
        }
    }

    public function getSimilar(int $id, int $category): array
    {
        try {
            $stmt = $this->db->prepare($this->commands->getSimilar());
            $stmt->bindValue(':current_id', $id);
            $stmt->bindValue(':category', $category);
            $stmt->bindValue(':limit', 4, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }catch (PDOException|Exception|Error $e) {
            $this->createLog("VehiclesManager", $e);
            return [];
        }
    }

    public function createLog(string $className, Exception|Error $exception): void
    {
        $text = "[" . date("d.m.Y H:i:s") . "] Произошла ошибка в ".$className.": ".$exception->getMessage().". На строке ".$exception->getLine().", файл: ".$exception->getFile()."\n\n";
        file_put_contents(__DIR__.'/../../logs/'.$className.'_Errors.log', $text, FILE_APPEND);
    }
}