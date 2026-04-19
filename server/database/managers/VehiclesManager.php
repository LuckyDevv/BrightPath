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

    public function getImagePath(int $id): string|false
    {
        try {
            $stmt = $this->db->prepare("SELECT `image_path` FROM `vehicles` WHERE `id`=:id");
            $stmt->bindValue(":id", $id, PDO::PARAM_INT);
            $stmt->execute();
            $ans = $stmt->fetch(PDO::FETCH_ASSOC);
            if (is_array($ans)) {
                return $ans["image_path"];
            }
        }catch (PDOException|Exception|Error $e) {
            $this->createLog("VehiclesManager", $e);
        }
        return false;
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

    public function getVehicleById(int $idVehicle, bool $ignoreActivity = false): array
    {
        try {
            $command = $this->commands->getVehicleById();
            if ($ignoreActivity) {
                $command = str_replace(" AND `is_active`=1", '', $command);
            }
            $stmt = $this->db->prepare($command);
            $stmt->execute([':id' => $idVehicle]);
            $ans = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!is_array($ans)) {
                return [];
            }else{
                return $ans;
            }
        } catch (PDOException|Exception|Error $e) {
            $this->createLog("VehiclesManager", $e);
            return [];
        }
    }

    public function addVehicle(array $data): int|false
    {
        try {
            // Проверка обязательных полей
            $required = ['name', 'image_path', 'seats', 'color', 'price'];
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
                ':color' => $data['color'],
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

    public function changeVehicle(int $vehicleId, array $data): bool
    {
        if (!empty($data)) {
            $setParts = [];
            $params = [];
            $fields = [];
            foreach ($data as $field => $value) {
                $fields[] = ':'.$field;
                $setParts[] = "`$field` = :$field";
                $params[] = $value;
            }
            $sql = "UPDATE `vehicles` SET " . implode(', ', $setParts) . " WHERE `id` = :id;";
            try {
                $stmt = $this->db->prepare($sql);
                $index_r = 0;
                $stmt->bindValue(":id", $vehicleId, PDO::PARAM_INT);
                foreach ($params as $param) {
                    if (is_int($param)) {
                        $stmt->bindValue("$fields[$index_r]", $param, PDO::PARAM_INT);
                    }
                    if (is_bool($param)) {
                        $stmt->bindValue("$fields[$index_r]", $param, PDO::PARAM_BOOL);
                    }
                    if (is_string($param)) {
                        $stmt->bindValue("$fields[$index_r]", $param);
                    }
                    $index_r++;
                }
                return $stmt->execute();
            }catch (PDOException|Exception|Error $e) {
                $this->createLog("VehiclesManager", $e);
            }
        }
        return false;
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

    // ФУНКЦИЯ ДЛЯ ЛОГОВ ПРИ РАЗРАБОТКЕ
    public function createCustomLog(string $text): void
    {
        $text = "[" . date("d.m.Y H:i:s") . "] Лог: ".$text."\n\n";
        file_put_contents(__DIR__.'/../../logs/custom_log.log', $text, FILE_APPEND);
    }

    public function getLastInsertId(): false|string
    {
        return $this->db->lastInsertId();
    }
}