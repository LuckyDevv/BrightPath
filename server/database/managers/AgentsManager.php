<?php
namespace managers;

use DateTime;
use executors\AgentsExecutor;
use PDO;
use PDOException;

class AgentsManager extends Manager {
    public function __construct()
    {
        parent::__construct(AgentsExecutor::CREATE_TABLE());
    }
    public function getAll(): array
    {
        try {
            $stmt = $this->db->prepare(AgentsExecutor::GET_ALL());
            $stmt->execute();
            $id = 0;
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if (count($result) > 0) {
                foreach ($result as $agent) {
                    $result[$id]['experience'] = $this->calculateDiffYear($agent['created_at']);
                    $result[$id]['age'] = $this->calculateDiffYear($agent['birthdate']);
                    $id++;
                }
            }
            $this->createCustomLog("AgentsManager", json_encode($result, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
            return $result;
        } catch (PDOException|\Exception|\Error $e) {
            $this->createLog("AgentsManager", $e);
            return [];
        }
    }

    public function addAgent(array $data): bool
    {
        try {
            // Проверка обязательных полей
            $required = ['name', 'image', 'position', 'birthdate', 'description', 'biographic'];
            foreach ($required as $field) {
                if (empty($data[$field])) {
                    throw new \Exception("Поле '$field' обязательно для заполнения");
                }
            }

            $stmt = $this->db->prepare(AgentsExecutor::ADD_AGENT());
            if ($data['birthdate'] instanceof DateTime) {
                $data['birthdate'] = $data['birthdate']->format('Y-m-d H:i:s');
            }
            $stmt->bindValue(":name", $data['name']);
            $stmt->bindValue(":image_path", $data['image']);
            $stmt->bindValue(":position", $data['position'], PDO::PARAM_INT);
            $stmt->bindValue(":birthdate", $data['birthdate']);
            $stmt->bindValue(":events_count", 0, PDO::PARAM_INT);
            $stmt->bindValue(":description", $data['description']);
            $stmt->bindValue(":biographic", $data['biographic']);
            $stmt->bindValue(":is_active", $data['is_active'] ?? 1, PDO::PARAM_INT);

            return $stmt->execute();
        } catch (PDOException|\Exception|\Error $e) {
            $this->createLog("AgentsManager", $e);
            return false;
        }
    }

    public function getById(int $id): array|false
    {
        try {
            $stmt = $this->db->prepare(AgentsExecutor::GET_BY_ID());
            $stmt->bindValue(":id", $id, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if (is_array($result) && count($result) > 0) {
                $result['experience'] = $this->calculateDiffYear($result['created_at']);
                $result['age'] = $this->calculateDiffYear($result['birthdate']);
                $this->createCustomLog("AgentsManager", json_encode($result, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
                return $result;
            }
        }catch (PDOException|\Exception|\Error $e) {
            $this->createLog("AgentsManager", $e);
        }
        return false;
    }

    public function getImagePathById(int $id): ?string
    {
        try {
            $stmt = $this->db->prepare(AgentsExecutor::GET_IMAGE_PATH_BY_ID());
            $stmt->bindValue(":id", $id, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if (is_array($result) && count($result) > 0) {
                return $result['image_path'];
            }
        }catch (PDOException|\Exception|\Error $e) {
            $this->createLog("AgentsManager", $e);
        }
        return null;
    }

    private function calculateDiffYear($date): ?int
    {
        try {
            if (!$date) return null;

            $date = new DateTime($date);
            $today = new DateTime();
            return $today->diff($date)->y;
        }catch (PDOException|\Exception|\Error $e) {
            $this->createLog("AgentsManager", $e);
            return null;
        }
    }

    public function updateAgent(int $agentId, string $name, DateTime $birthdate, int $position, string $description, string $biographic): bool
    {
        try {
            $stmt = $this->db->prepare(AgentsExecutor::UPDATE_AGENT());
            $stmt->bindValue(":id", $agentId, PDO::PARAM_INT);
            $stmt->bindValue(":name", $name, PDO::PARAM_STR);
            $stmt->bindValue(":birthdate", $birthdate->format('Y-m-d H:i:s'));
            $stmt->bindValue(":position", $position, PDO::PARAM_INT);
            $stmt->bindValue(":description", $description, PDO::PARAM_STR);
            $stmt->bindValue(":biographic", $biographic, PDO::PARAM_STR);
            return $stmt->execute();
        }catch (PDOException|\Exception|\Error $e) {
            $this->createLog("AgentsManager", $e);
            return false;
        }
    }

    public function getLastAgentId(): int
    {
        return $this->db->lastInsertId();
    }

    public function deleteAgent(int $agentId): bool
    {
        try {
            $stmt = $this->db->prepare(AgentsExecutor::DELETE_AGENT());
            $stmt->bindValue(":id", $agentId, PDO::PARAM_INT);
            return $stmt->execute();
        }catch (PDOException|\Exception|\Error $e) {
            $this->createLog("AgentsManager", $e);
            return false;
        }
    }
}