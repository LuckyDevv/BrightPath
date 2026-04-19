<?php
namespace managers;

use Cassandra\Date;
use DateTime;
use executors\AgentsExecutor;
use PDO;
use PDOException;

class AgentsManager {
    protected PDO $db;
    public bool $die = false;
    private AgentsExecutor $commands;
    public function __construct()
    {
        try {
            @mkdir(__DIR__.'/../logs/');
            $this->db= new PDO("mysql:host=localhost;", "root", "5328alexRU");
            $this->db->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, false);
            $this->db->query("CREATE DATABASE IF NOT EXISTS `brightpath`;");
            $this->db->query("SET NAMES utf8;");
            $this->db->query("USE `brightpath`;");
            $this->commands= new AgentsExecutor();
            $this->db->query($this->commands->createTable());
        } catch (PDOException $e){
            $this->die = true;
            $this->createLog("AgentsManager", $e);
        }
    }
    public function getAll(): array
    {
        try {
            $stmt = $this->db->prepare($this->commands->getAll());
            $stmt->execute();
            $id = 0;
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if (is_array($result) && count($result) > 0) {
                foreach ($result as $agent) {
                    $result[$id]['experience'] = $this->calculateDiffYear($agent['created_at']);
                    $result[$id]['age'] = $this->calculateDiffYear($agent['birthdate']);
                    $id++;
                }
            }
            $this->createCustomLog(json_encode($result, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
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

            $stmt = $this->db->prepare($this->commands->addAgent());
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
            $stmt = $this->db->prepare($this->commands->getById());
            $stmt->bindValue(":id", $id, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if (is_array($result) && count($result) > 0) {
                $result['experience'] = $this->calculateDiffYear($result['created_at']);
                $result['age'] = $this->calculateDiffYear($result['birthdate']);
                $this->createCustomLog(json_encode($result, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
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
            $stmt = $this->db->prepare("SELECT `image_path` FROM `agents` WHERE `id` = :id");
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

    public function createLog(string $className, \Exception|\Error $exception): void
    {
        $text = "[" . date("d.m.Y H:i:s") . "] Произошла ошибка в ".$className.": ".$exception->getMessage().". На строке ".$exception->getLine().", файл: ".$exception->getFile()."\n\n";
        file_put_contents(__DIR__.'/../../logs/'.$className.'_Errors.log', $text, FILE_APPEND);
    }

    // ФУНКЦИЯ ДЛЯ ЛОГОВ ПРИ РАЗРАБОТКЕ
    public function createCustomLog(string $text): void
    {
        $text = "[" . date("d.m.Y H:i:s") . "] Лог: ".$text."\n\n";
        file_put_contents(__DIR__.'/../../logs/custom_log_agents.log', $text, FILE_APPEND);
    }

    public function updateAgent(int $agentId, string $name, DateTime $birthdate, int $position, string $description, string $biographic): bool
    {
        try {
            $stmt = $this->db->prepare($this->commands->updateAgent());
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
            $stmt = $this->db->prepare("DELETE FROM `agents` WHERE `id` = :id");
            $stmt->bindValue(":id", $agentId, PDO::PARAM_INT);
            return $stmt->execute();
        }catch (PDOException|\Exception|\Error $e) {
            $this->createLog("AgentsManager", $e);
            return false;
        }
    }
}