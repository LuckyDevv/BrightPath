<?php

namespace managers;

use executors\RequestsExecutor;
use PDO;

class RequestsManager extends Manager
{
    public const int NEW_OPERATOR_NULL = 0;
    public const int OPERATORS_NOT_MATCH = 1;
    public const int STATUS_CHANGED_SUCCESS = 2;
    public const int STATUS_CHANGED_FAILURE = 3;
    public const int DATABASE_ERROR = 4;

    public function __construct(){
        parent::__construct(RequestsExecutor::CREATE_TABLE());
    }

    public function addRequest(string $phone, string $email, string $name): bool
    {
        try {
            $stmt = $this->db->prepare("INSERT INTO `requests` (`userphone`, `useremail`, `username`) VALUES (:phone, :email, :name);");
            $stmt->bindParam(':phone', $phone);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':name', $name);
            return $stmt->execute();
        }catch(\PDOException|\Exception|\Error $e){
            $this->createLog("RequestsManager", $e);
        }
        return false;
    }

    public function getAllRequests(): array
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM `requests`;");
            if ($stmt->execute()) {
                return $stmt->fetchAll(\PDO::FETCH_ASSOC);
            }
        }catch(\PDOException|\Exception|\Error $e){
            $this->createLog("RequestsManager", $e);
        }
        return [];
    }

    public function getRequestById(int $id): array|false
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM `requests` WHERE `id` = :id;");
            $stmt->bindParam(':id', $id);
            if ($stmt->execute()) {
                return $stmt->fetch(\PDO::FETCH_ASSOC);
            }
        }catch(\PDOException|\Exception|\Error $e){
            $this->createLog("RequestsManager", $e);
        }
        return false;
    }

    private function getOperator(int $id): ?string
    {
        try {
            $stmt = $this->db->prepare("SELECT `operator` FROM `requests` WHERE `id` = :id;");
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            if ($stmt->execute()) {
                $operator = $stmt->fetch(\PDO::FETCH_ASSOC)['operator'];
                if (trim($operator) !== '') {
                    return $operator;
                }
            }
        }catch(\PDOException|\Exception|\Error $e){
            $this->createLog("RequestsManager", $e);
        }
        return null;
    }

    public function changeStatus(int $id, string $operator, string $newStatus): int
    {
        if (trim($operator) == '') return self::NEW_OPERATOR_NULL;
        $oldOperator = $this->getOperator($id);
        $newOperator = trim($operator);
        if ($oldOperator != null) {
            if ($oldOperator !== $newOperator) {
                return self::OPERATORS_NOT_MATCH;
            }
        }
        try {
            $stmt = $this->db->prepare("UPDATE `requests` SET `status` = :status, `operator` = :operator WHERE `id` = :id;");
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->bindValue(':status', $newStatus);
            $stmt->bindValue(':operator', $newOperator);
            if ($stmt->execute()) {
                return self::STATUS_CHANGED_SUCCESS;
            }else{
                return self::STATUS_CHANGED_FAILURE;
            }
        }catch(\PDOException|\Exception|\Error $e){
            $this->createLog("RequestsManager", $e);
            return self::DATABASE_ERROR;
        }
    }

    public function completeRequest(int $id, string $operator, string $comment): int
    {
        if (trim($operator) == '') return self::NEW_OPERATOR_NULL;
        $oldOperator = $this->getOperator($id);
        $newOperator = trim($operator);
        if ($oldOperator != null) {
            if ($oldOperator !== $newOperator) {
                return self::OPERATORS_NOT_MATCH;
            }
        }
        try {
            $stmt = $this->db->prepare("UPDATE `requests` SET `status`='completed', `operator` = :operator, `comment` = :comment WHERE `id` = :id;");
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->bindValue(':comment', $comment);
            $stmt->bindValue(':operator', $newOperator);
            if ($stmt->execute()) {
                return self::STATUS_CHANGED_SUCCESS;
            }else{
                return self::STATUS_CHANGED_FAILURE;
            }
        }catch(\PDOException|\Exception|\Error $e){
            $this->createLog("RequestsManager", $e);
            return self::DATABASE_ERROR;
        }
    }

    public function deleteById(int $id): bool
    {
        try {
            $stmt = $this->db->prepare("DELETE FROM `requests` WHERE `id` = :id;");
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        }catch(\PDOException|\Exception|\Error $e){
            $this->createLog("RequestsManager", $e);
            return false;
        }
    }
}