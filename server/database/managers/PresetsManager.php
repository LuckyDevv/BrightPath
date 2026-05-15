<?php

namespace managers;

use executors\PresetsExecutor;

class PresetsManager extends Manager
{
    public function __construct(){
        parent::__construct(PresetsExecutor::CREATE_TABLE());
    }

    public function addPreset(array $data): bool
    {
        try {
            $required = ['name', 'transport', 'goods', 'services', 'quote', 'price'];
            foreach ($required as $field) {
                if (empty($data[$field])) {
                    throw new \Exception('Поле "'.$field.'" обязательно для заполнения!');
                }
            }
            if (!is_array($data['transport']) || !is_array($data['goods']) || !is_array($data['services'])) {
                throw new \Exception('Данные о транспорте, товарах и услугах должны быть массивом (array)!');
            }
            $stmt = $this->db->prepare("INSERT INTO `presets` (`name`, `transport`, `goods`, `services`, `quote`, `decoration`, `decoration_quote`, `price`) VALUES (:name, :transport, :goods, :services, :quote, :decoration, :decoration_quote, :price);");
            $stmt->bindValue(':name', $data["name"], \PDO::PARAM_STR);
            $stmt->bindValue(':transport', json_encode($data["transport"]), \PDO::PARAM_STR);
            $stmt->bindValue(':goods', json_encode($data["goods"]), \PDO::PARAM_STR);
            $stmt->bindValue(':services', json_encode($data["services"]), \PDO::PARAM_STR);
            $stmt->bindValue(':quote', $data["quote"], \PDO::PARAM_STR);
            $stmt->bindValue(':decoration', $data["decoration"] ?? 'default', \PDO::PARAM_STR);
            $stmt->bindValue(':decoration_quote', $data["decoration_quote"] ?? null, \PDO::PARAM_STR);
            $stmt->bindValue(':price', $data["price"], \PDO::PARAM_INT);
            return $stmt->execute();
        }catch (\PDOException|\Exception|\Error $e) {
            $this->createLog("PresetsManager", $e);
        }
        return false;
    }

    public function editPreset(int $id, mixed $presetData): bool
    {
        try {
            $required = ['name', 'transport', 'goods', 'services', 'quote', 'price', 'decoration', 'is_active'];
            foreach ($required as $field) {
                if (empty($presetData[$field])) {
                    throw new \Exception('Поле "'.$field.'" обязательно для заполнения!');
                }
            }
            if (!is_array($presetData['transport']) || !is_array($presetData['goods']) || !is_array($presetData['services'])) {
                throw new \Exception('Данные о транспорте, товарах и услугах должны быть массивом (array)!');
            }
            $stmt = $this->db->prepare("UPDATE `presets` SET `name`=:name,`transport`=:transport,`goods`=:goods,`services`=:services,`quote`=:quote,`price`=:price,`decoration`=:decoration,`decoration_quote`=:decoration_quote,`is_active`=:is_active WHERE `id`=:id;");
            $stmt->bindValue(':name', $presetData["name"], \PDO::PARAM_STR);
            $stmt->bindValue(':transport', json_encode($presetData["transport"]), \PDO::PARAM_STR);
            $stmt->bindValue(':goods', json_encode($presetData["goods"]), \PDO::PARAM_STR);
            $stmt->bindValue(':services', json_encode($presetData["services"]), \PDO::PARAM_STR);
            $stmt->bindValue(':quote', $presetData["quote"], \PDO::PARAM_STR);
            $stmt->bindValue(':decoration', $presetData["decoration"], \PDO::PARAM_STR);
            $stmt->bindValue(':decoration_quote', $presetData["decoration_quote"] ?? "", \PDO::PARAM_STR);
            $stmt->bindValue(':price', $presetData["price"], \PDO::PARAM_INT);
            $stmt->bindValue(':is_active', $presetData["is_active"], \PDO::PARAM_BOOL);
            $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
            return $stmt->execute();
        }catch (\PDOException|\Exception|\Error $e) {
            $this->createLog("PresetsManager", $e);
        }
        return false;
    }

    public function getAllPresets(bool $is_admin = false): array
    {
        try {
            if ($is_admin) {
                $stmt = $this->db->prepare("SELECT * FROM `presets`;");
            }else{
                $stmt = $this->db->prepare("SELECT * FROM `presets` WHERE `is_active`=1;");
            }
            if ($stmt->execute()) {
                return $stmt->fetchAll(\PDO::FETCH_ASSOC);
            }
        }catch (\PDOException|\Exception|\Error $e){
            $this->createLog("PresetsManager", $e);
        }
        return [];
    }

    public function getPresetById(int $presetId): array|false
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM `presets` WHERE `id`=:id;");
            $stmt->bindValue(':id', $presetId, \PDO::PARAM_INT);
            if ($stmt->execute()) {
                return $stmt->fetch(\PDO::FETCH_ASSOC);
            }
        }catch (\PDOException|\Exception|\Error $e){
            $this->createLog("PresetsManager", $e);
        }
        return false;
    }

    public function deletePreset(int $id): bool
    {
        try {
            $stmt = $this->db->prepare("DELETE FROM `presets` WHERE `id`=:id;");
            $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
            return $stmt->execute();
        }catch (\PDOException|\Exception|\Error $e){
            $this->createLog("PresetsManager", $e);
        }
        return false;
    }
}