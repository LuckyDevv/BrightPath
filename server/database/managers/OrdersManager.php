<?php

namespace managers;

use executors\OrdersExecutor;
use PDO;

class OrdersManager extends Manager
{
    public function __construct()
    {
        parent::__construct(OrdersExecutor::CREATE_TABLE());
    }

    public function addOrder(string $userName, string $userPhone, string $userEmail, string $transport, string $goods, string $services, float $summary): int|false
    {
        try {
            $stmt = $this->db->prepare(OrdersExecutor::ADD_ORDER());
            $stmt->bindParam(":transport", $transport);
            $stmt->bindParam(":goods", $goods);
            $stmt->bindParam(":services", $services);
            $stmt->bindParam(":userName", $userName);
            $stmt->bindParam(":userPhone", $userPhone);
            $stmt->bindParam(":userEmail", $userEmail);
            $stmt->bindParam(":summary", $summary, PDO::PARAM_INT);
            if ($stmt->execute()) {
                return $this->db->lastInsertId();
            }
        }catch (\PDOException|\Error|\Exception $e){
            $this->createLog("OrdersManager", $e);
        }
        return false;
    }

    public function getOrderByIdAndEmail(int $orderId, string $email): array
    {
        try {
            $stmt = $this->db->prepare(OrdersExecutor::GET_ORDER_BY_ID_AND_EMAIL());
            $stmt->bindParam(":id", $orderId, PDO::PARAM_INT);
            $stmt->bindParam(":email", $email);
            if ($stmt->execute()) {
                return $stmt->fetch();
            }
        }catch (\PDOException|\Error|\Exception $e){
            $this->createLog("OrdersManager", $e);
        }
        return [];
    }

    public function getAllOrders(): array
    {
        try {
            $stmt = $this->db->prepare(OrdersExecutor::GET_ALL_ORDERS());
            if ($stmt->execute()) {
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
        }catch (\PDOException|\Error|\Exception $e){
            $this->createLog("OrdersManager", $e);
        }
        return [];
    }

    public function getLastOrders(): array
    {
        try {
            $stmt = $this->db->prepare(OrdersExecutor::GET_LAST_ORDERS());
            if ($stmt->execute()) {
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
        }catch (\PDOException|\Error|\Exception $e){
            $this->createLog("OrdersManager", $e);
        }
        return [];
    }

    public function getOrdersToday(): int
    {
        try {
            $stmt = $this->db->prepare(OrdersExecutor::GET_ORDERS_TODAY());
            if ($stmt->execute()) {
                return (int)$stmt->fetch()['count_today'];
            }
        } catch (\PDOException|\Error|\Exception $e){
            $this->createLog("OrdersManager", $e);
        }
        return 0;
    }

    public function getOrdersYesterday(): int
    {
        try {
            $stmt = $this->db->prepare(OrdersExecutor::GET_ORDERS_YESTERDAY());
            if ($stmt->execute()) {
                return (int)$stmt->fetch()['count_yesterday'];
            }
        } catch (\PDOException|\Error|\Exception $e){
            $this->createLog("OrdersManager", $e);
        }
        return 0;
    }

    public function getSummaryToday(): int
    {
        try {
            $stmt = $this->db->prepare(OrdersExecutor::GET_SUMMARY_TODAY());
            if ($stmt->execute()) {
                return $stmt->fetch(PDO::FETCH_ASSOC)['total_today'];
            }
        }catch (\PDOException|\Error|\Exception $e){
            $this->createLog("OrdersManager", $e);
        }
        return 0;
    }

    public function getSummaryYesterday(): int
    {
        try {
            $stmt = $this->db->prepare(OrdersExecutor::GET_SUMMARY_YESTERDAY());
            if ($stmt->execute()) {
                return $stmt->fetch(PDO::FETCH_ASSOC)['total_yesterday'];
            }
        }catch (\PDOException|\Error|\Exception $e){
            $this->createLog("OrdersManager", $e);
        }
        return 0;
    }

    public function getRoundSummaryToday(): int
    {
        try {
            $stmt = $this->db->prepare(OrdersExecutor::GET_ROUND_SUMMARY_TODAY());
            if ($stmt->execute()) {
                return $stmt->fetch(PDO::FETCH_ASSOC)['avg_check_today'];
            }
        }catch (\PDOException|\Error|\Exception $e){
            $this->createLog("OrdersManager", $e);
        }
        return 0;
    }

    public function getRoundSummary(): int
    {
        try {
            $stmt = $this->db->prepare(OrdersExecutor::GET_ROUND_SUMMARY());
            if ($stmt->execute()) {
                return $stmt->fetch(PDO::FETCH_ASSOC)['avg_check_all_time'];
            }
        }catch (\PDOException|\Error|\Exception $e){
            $this->createLog("OrdersManager", $e);
        }
        return 0;
    }

    public function getOrdersAll(): array
    {
        try {
            $stmt = $this->db->prepare(OrdersExecutor::GET_ORDERS_ALL());
            if ($stmt->execute()) {
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
        }catch (\PDOException|\Error|\Exception $e){
            $this->createLog("OrdersManager", $e);
        }
        return [];
    }

    public function getNewClientsToday(): int
    {
        try {
            $stmt = $this->db->prepare(OrdersExecutor::GET_NEW_CLIENTS_TODAY());
            if ($stmt->execute()) {
                return $stmt->fetch(PDO::FETCH_ASSOC)['unique_clients_today'];
            }
        }catch (\PDOException|\Error|\Exception $e){
            $this->createLog("OrdersManager", $e);
        }
        return 0;
    }

    public function getNewClientsYesterday(): int
    {
        try {
            $stmt = $this->db->prepare(OrdersExecutor::GET_NEW_CLIENTS_YESTERDAY());
            if ($stmt->execute()) {
                return $stmt->fetch(PDO::FETCH_ASSOC)['unique_clients_yesterday'];
            }
        }catch (\PDOException|\Error|\Exception $e){
            $this->createLog("OrdersManager", $e);
        }
        return 0;
    }

    public function getOrderById(int $orderId): false|array
    {
        try {
            $stmt = $this->db->prepare("SELECT `agents`,`username`,`useremail`,`userphone`,`summary`,`status`,`transport`,`goods`,`services` FROM `orders` WHERE `id` = :id");
            $stmt->bindParam(':id', $orderId);
            if ($stmt->execute()) {
                return $stmt->fetch(PDO::FETCH_ASSOC);
            }
        }catch (\PDOException|\Error|\Exception $e){
            $this->createLog("OrdersManager", $e);
        }
        return false;
    }

    public function changeStatus(int $id, string $newStatus): bool
    {
        try {
            $stmt = $this->db->prepare("UPDATE `orders` SET `status` = :status WHERE `id` = :id");
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->bindValue(':status', $newStatus);
            return $stmt->execute();
        }catch (\PDOException|\Error|\Exception $e){
            $this->createLog("OrdersManager", $e);
        }
        return false;
    }

    public function completeOrder(int $id, string $agents): bool
    {
        try {
            $stmt = $this->db->prepare("UPDATE `orders` SET `status` = 'completed', `agents` = :agents WHERE `id` = :id");
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->bindValue(':agents', $agents);
            return $stmt->execute();
        }catch (\PDOException|\Error|\Exception $e){
            $this->createLog("OrdersManager", $e);
        }
        return false;
    }

    public function deleteById(int $id): bool
    {
        try {
            $stmt = $this->db->prepare("DELETE FROM `orders` WHERE `id` = :id;");
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        }catch(\PDOException|\Exception|\Error $e){
            $this->createLog("OrdersManager", $e);
            return false;
        }
    }

    public function getClientById(int $id): array|false
    {
        try {
            $stmt = $this->db->prepare("SELECT `username`,`useremail`,`userphone`,`created_at` FROM `orders` WHERE `id` = :id;");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            if ($stmt->execute()) {
                return $stmt->fetch(PDO::FETCH_ASSOC);
            }
        }catch (\PDOException|\Error|\Exception $e){
            $this->createLog("OrdersManager", $e);
        }
        return false;
    }
}