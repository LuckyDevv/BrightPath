<?php

namespace managers;

use Error;
use Exception;
use executors\ServicesExecutor;
use PDO;
use PDOException;

class ServicesManager extends Manager
{
    public function __construct()
    {
        parent::__construct(ServicesExecutor::CREATE_TABLE());
    }

    public function addService(array $data): int|false
    {
        try {
            $required = ['name', 'category', 'description', 'what_includes', 'price'];
            foreach ($required as $field) {
                if (empty($data[$field])) {
                    throw new Exception("Поле '$field' обязательно для заполнения");
                }
            }
            $stmt = $this->db->prepare(ServicesExecutor::ADD_SERVICE());
            $stmt->bindParam(":name", $data['name']);
            $stmt->bindParam(":category", $data['category'], PDO::PARAM_INT);
            $stmt->bindParam(":description", $data['description']);
            $stmt->bindParam(":what_includes", $data['what_includes']);
            $stmt->bindParam(":price", $data['price'], PDO::PARAM_INT);
            if ($stmt->execute()) {
                return $this->db->lastInsertId();
            }
        }catch (PDOException|Exception|Error $e) {
            $this->createLog("ServicesManager", $e);
        }
        return false;
    }

    public function editService(int $id, array $data): bool
    {
        try {
            $required = ['name', 'category', 'description', 'what_includes', 'price', 'is_active'];
            foreach ($required as $field) {
                if (!isset($data[$field])) {
                    throw new Exception("Поле '$field' обязательно для заполнения");
                }
            }
            $stmt = $this->db->prepare(ServicesExecutor::EDIT_SERVICE());
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);
            $stmt->bindParam(":name", $data['name'], PDO::PARAM_STR);
            $stmt->bindParam(":category", $data['category'], PDO::PARAM_INT);
            $stmt->bindParam(":description", $data['description'], PDO::PARAM_STR);
            $stmt->bindParam(":what_includes", $data['what_includes'], PDO::PARAM_STR);
            $stmt->bindParam(":price", $data['price'], PDO::PARAM_INT);
            $stmt->bindParam(":is_active", $data['is_active'], PDO::PARAM_INT);
            if ($stmt->execute()) {
                return true;
            }
        }catch (PDOException|Exception|Error $e) {
            $this->createLog("ServicesManager", $e);
        }
        return false;
    }

    public function getAllServices($is_admin = false): array
    {
        try {
            if ($is_admin) {
                $stmt = $this->db->prepare(ServicesExecutor::GET_ALL_SERVICES_ADMIN());
            }else{
                $stmt = $this->db->prepare(ServicesExecutor::GET_ALL_SERVICES());
            }
            if ($stmt->execute()) {
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
        }catch (PDOException|Exception|Error $e) {
            $this->createLog("ServicesManager", $e);
        }
        return [];
    }

    public function getServiceById(int $id, bool $is_admin = false): array
    {
        try {
            if ($is_admin) {
                $stmt = $this->db->prepare(ServicesExecutor::GET_SERVICE_BY_ID_ADMIN());
            }else{
                $stmt = $this->db->prepare(ServicesExecutor::GET_SERVICE_BY_ID());
            }
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);
            if ($stmt->execute()) {
                return $stmt->fetch(PDO::FETCH_ASSOC);
            }
        }catch (PDOException|Exception|Error $e) {
            $this->createLog("ServicesManager", $e);
        }
        return [];
    }

    public function getPopular(): array
    {
        try {
            $stmt = $this->db->prepare(ServicesExecutor::GET_POPULAR());
            if ($stmt->execute()) {
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
        }catch (PDOException|Exception|Error $e) {
            $this->createLog("ServicesManager", $e);
        }
        return [];
    }

    public function deleteServiceById(int $id): bool
    {
        try {
            $stmt = $this->db->prepare(ServicesExecutor::DELETE_SERVICE_BY_ID());
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);
            return $stmt->execute();
        }catch (PDOException|Exception|Error $e) {
            $this->createLog("ServicesManager", $e);
            return false;
        }
    }

    public function getAllForCalculator(): array
    {
        try {
            $stmt = $this->db->prepare(ServicesExecutor::GET_ALL_FOR_CALCULATOR());
            if ($stmt->execute()) {
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
        }catch (PDOException|Exception|Error $e) {
            $this->createLog("ServicesManager", $e);
        }
        return [];
    }
}