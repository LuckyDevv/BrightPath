<?php
namespace managers;

use Error;
use Exception;
use PDO;
use PDOException;
use executors\VehiclesExecutor;

class VehiclesManager extends Manager
{

    public function __construct()
    {
        parent::__construct(VehiclesExecutor::CREATE_TABLE());
    }

    public function getAllVehicles(bool $is_admin = false): array
    {
        try {
            if ($is_admin) {
                $stmt = $this->db->prepare(VehiclesExecutor::GET_ALL_VEHICLES_ADMIN());
            }else{
                $stmt = $this->db->prepare(VehiclesExecutor::GET_ALL_VEHICLES());
            }
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
            $stmt = $this->db->prepare(VehiclesExecutor::GET_IMAGE_PATH());
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
            $stmt = $this->db->prepare(VehiclesExecutor::DELETE_VEHICLE_BY_ID());
            $stmt->bindValue(":vehicleId", $id, PDO::PARAM_INT);
            return $stmt->execute();
        }catch (PDOException|Exception|Error $e) {
            $this->createLog("VehiclesManager", $e);
            return false;
        }
    }

    public function getVehicleById(int $idVehicle, bool $isAdmin = false): array
    {
        try {
            if ($isAdmin) {
                $stmt = $this->db->prepare(VehiclesExecutor::GET_VEHICLE_BY_ID_ADMIN());
            }else{
                $stmt = $this->db->prepare(VehiclesExecutor::GET_VEHICLE_BY_ID());
            }
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
            $stmt = $this->db->prepare(VehiclesExecutor::ADD_VEHICLE());
            $result = $stmt->execute([
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
            if ($result) return $this->db->lastInsertId();
        } catch (PDOException|Exception|Error $e) {
            $this->createLog("VehiclesManager", $e);
        }
        return false;
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
            $stmt = $this->db->prepare(VehiclesExecutor::GET_POPULAR());
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
            $stmt = $this->db->prepare(VehiclesExecutor::GET_SIMILAR());
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

    public function getAllForCalculator(): array
    {
        try {
            $stmt = $this->db->prepare(VehiclesExecutor::GET_ALL_FOR_CALCULATOR());
            if ($stmt->execute()) {
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
        }catch (PDOException|Exception|Error $e) {
            $this->createLog("VehiclesManager", $e);
        }
        return [];
    }
}