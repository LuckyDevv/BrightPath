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
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
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
}