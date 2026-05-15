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

    public function getById(int $id): array|false
    {
        $stmt = $this->db->prepare("SELECT * FROM goods WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateGoods(int $id, string $name, int $category, string $material, string $sizes, int $weight, string $description_short, string $description_full, float $price, int $total_stock): bool
    {
        $stmt = $this->db->prepare("
        UPDATE goods SET 
            name = :name,
            category = :category,
            material = :material,
            sizes = :sizes,
            weight = :weight,
            description_short = :description_short,
            description_full = :description_full,
            price = :price,
            updated_at = NOW(),
            total_stock = :total_stock
        WHERE id = :id
    ");

        return $stmt->execute([
            ':id' => $id,
            ':name' => $name,
            ':category' => $category,
            ':material' => $material,
            ':sizes' => $sizes,
            ':weight' => $weight,
            ':description_short' => $description_short,
            ':description_full' => $description_full,
            ':price' => $price,
            ':total_stock' => $total_stock
        ]);
    }

    public function deleteGoods(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM goods WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    public function getImagePathById(int $id): string|false
    {
        $stmt = $this->db->prepare("SELECT image_path FROM goods WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['image_path'] : false;
    }

    public function updateImagePath(int $id, string $imagePath): bool
    {
        $stmt = $this->db->prepare("UPDATE goods SET image_path = :image_path WHERE id = :id");
        return $stmt->execute([':id' => $id, ':image_path' => $imagePath]);
    }

    public function getLastGoodsId(): int|false
    {
        return $this->db->lastInsertId();
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

    public function getForPreset(array $ids): array
    {
        try {
            $placeholders = [];
            foreach ($ids as $key => $id) {
                $placeholders[] = ":id{$key}";
            }
            $placeholders_str = implode(',', $placeholders);

            $sql = "SELECT `id`,`name`,`price`,1 as `quantity` FROM `goods` WHERE `id` IN ({$placeholders_str})";

            $stmt = $this->db->prepare($sql);

            // Привязываем значения
            foreach ($ids as $key => $id) {
                $stmt->bindValue(":id{$key}", $id, PDO::PARAM_INT);
            }
            if ($stmt->execute()) {
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
        }catch (PDOException|Exception|Error $e) {
            $this->createLog("GoodsManager", $e);
        }
        return [];
    }

    public function getAllForSelect(): array
    {
        try {
            $stmt = $this->db->query("SELECT `id`, `name`, `price` FROM `goods` WHERE `is_active` = 1");
            if ($stmt->execute()) {
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
        }catch (PDOException|Exception|Error $e) {
            $this->createLog("GoodsManager", $e);
        }
        return [];
    }

    public function getGoodsByIdForCart(int $goodsId): array
    {
        try {
            $stmt = $this->db->prepare("SELECT `id`,`name`,`price`,`total_stock` as `available_stock` FROM `goods` WHERE `id`=:id AND `is_active`=1;");
            $stmt->bindParam(":id", $goodsId, PDO::PARAM_INT);
            if ($stmt->execute()) {
                return $stmt->fetch(PDO::FETCH_ASSOC);
            }
        }catch (PDOException|Exception|Error $e) {
            $this->createLog("GoodsManager", $e);
        }
        return [];
    }

    public function order(int $id, int $quantity): bool
    {
        try {
            $stmt = $this->db->prepare("UPDATE `goods` SET `orders_count` = `orders_count` + :quantity, `total_stock` = `total_stock` - :quantity WHERE `id` = :id;");
            $stmt->bindParam(":quantity", $quantity, PDO::PARAM_INT);
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);
            if ($stmt->execute()) {
                return true;
            }
        }catch (PDOException|Exception|Error $e) {
            $this->createLog("GoodsManager", $e);
        }
        return false;
    }


}