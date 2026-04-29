<?php
namespace managers;

use executors\GoodsExecutor;
use PDO;
use PDOException;
use Exception;
use Error;

class GoodsManager extends Manager {
    public function __construct()
    {
        parent::__construct(GoodsExecutor::CREATE_TABLE());
    }

    public function addGoods(array $data): int|false
    {
        try {
            $required = ['name', 'category', 'image_path', 'material', 'description_short', 'description_full', 'price', 'total_stock', 'sizes', 'weight'];
            foreach ($required as $field) {
                if (empty($data[$field])) {
                    throw new Exception("Поле '$field' обязательно для заполнения");
                }
            }
            $stmt = $this->db->prepare(GoodsExecutor::ADD_GOODS());
            $stmt->bindParam(":name", $data['name']);
            $stmt->bindParam(":category", $data['category'], PDO::PARAM_INT);
            $stmt->bindParam(":image_path", $data['image_path']);
            $stmt->bindParam(":material", $data['material']);
            $stmt->bindParam(":description_short", $data['description_short']);
            $stmt->bindParam(":description_full", $data['description_full']);
            $stmt->bindParam(":price", $data['price']);
            $stmt->bindParam(":total_stock", $data['total_stock'], PDO::PARAM_INT);
            $stmt->bindParam(":sizes", $data['sizes']);
            $stmt->bindParam(":weight", $data['weight'], PDO::PARAM_INT);
            if ($stmt->execute()) {
                return $this->db->lastInsertId();
            }
        }catch (PDOException|Exception|Error $e) {
            $this->createLog("GoodsManager", $e);
        }
        return false;
    }

    public function getAllGoods(bool $is_admin = false): array
    {
        try {
            if ($is_admin) {
                $stmt = $this->db->prepare(GoodsExecutor::GET_ALL_GOODS_ADMIN());
            }else{
                $stmt = $this->db->prepare(GoodsExecutor::GET_ALL_GOODS());
            }
            if ($stmt->execute()) {
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
        }catch (PDOException|Exception|Error $e) {
            $this->createLog("GoodsManager", $e);
        }
        return [];
    }

    public function getGoodsById(int $goods_id, bool $is_admin = false): array
    {
        try {
            if ($is_admin) {
                $stmt = $this->db->prepare(GoodsExecutor::GET_GOODS_BY_ID_ADMIN());
            }else{
                $stmt = $this->db->prepare(GoodsExecutor::GET_GOODS_BY_ID());
            }
            $stmt->bindParam(":id", $goods_id, PDO::PARAM_INT);
            if ($stmt->execute()) {
                return $stmt->fetch(PDO::FETCH_ASSOC);
            }
        }catch (PDOException|Exception|Error $e) {
            $this->createLog("GoodsManager", $e);
        }
        return [];
    }

    public function getPopular(): array
    {
        try {
            $stmt = $this->db->prepare(GoodsExecutor::GET_POPULAR());
            if ($stmt->execute()) {
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
        }catch (PDOException|Exception|Error $e) {
            $this->createLog("GoodsManager", $e);
        }
        return [];
    }

    public function getAllForCalculator(): array
    {
        try {
            $stmt = $this->db->prepare(GoodsExecutor::GET_ALL_FOR_CALCULATOR());
            if ($stmt->execute()) {
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
        }catch (PDOException|Exception|Error $e) {
            $this->createLog("GoodsManager", $e);
        }
        return [];
    }


}