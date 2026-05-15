<?php

namespace managers;

use Error;
use Exception;
use executors\AdminsExecutor;
use PDO;
use PDOException;

class AdminsManager extends Manager
{
    public const int AUTH_SUCCESS = 0;
    public const int AUTH_PASSWORD_INVALID = 1;
    public const int AUTH_TOTP_REQUIRED = 2;
    public const int AUTH_USER_BLOCKED = 3;
    public const int AUTH_USER_NOT_EXISTS = 4;
    public const int AUTH_DATABASE_ERROR = 5;

    public function __construct()
    {
        parent::__construct(AdminsExecutor::CREATE_TABLE(), true);
    }

    public function addAdminUser(string $login, string $password, string $role = 'operator', ?string $totpSecret = null): false|int
    {
        try {
            // Проверка существования логина
            $checkStmt = $this->db->prepare(AdminsExecutor::CHECK_LOGIN());
            $checkStmt->execute([':login' => $login]);
            if ($checkStmt->fetch()) {
                throw new Exception("Логин '{$login}' уже существует");
            }
            // Хеширование пароля
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            // Подготовка запроса
            $stmt = $this->db->prepare(AdminsExecutor::ADD_ADMIN_USER());
            // Выполнение
            $stmt->execute([
                ':login' => $login,
                ':password_hash' => $passwordHash,
                ':role' => $role,
                ':is_locked' => 0,
                ':totp_secret' => $totpSecret
            ]);
            return $this->db->lastInsertId();
        } catch (PDOException|Exception|Error $e) {
            $this->createLog("AdminsManager", $e);
            return false;
        }
    }

    public function checkAdminUser(string $login, string $password): null|int
    {
        try {
            $stmt = $this->db->prepare(AdminsExecutor::CHECK_ADMIN_USER());
            $result = $stmt->execute([':login' => $login]);
            if ($result) {
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                if (isset($result['is_locked'], $result['password_hash'], $result['is_2fa_enabled'])) {
                    if (!$result['is_locked']) {
                        $this->createCustomLog("AdminsManager", "Fetch: ".json_encode($result));
                        if ($result['is_2fa_enabled'] == 1) {
                            if (array_key_exists('totp_secret', $result) && $result['totp_secret'] !== "") {
                                if (password_verify($password, $result['password_hash'])) {
                                    return self::AUTH_SUCCESS;
                                }else return self::AUTH_PASSWORD_INVALID;
                            }else return self::AUTH_TOTP_REQUIRED;
                        }else return self::AUTH_TOTP_REQUIRED;
                    }else return self::AUTH_USER_BLOCKED;
                }else return self::AUTH_USER_NOT_EXISTS;
            }else return self::AUTH_USER_NOT_EXISTS;
        }catch (PDOException|Exception|Error $e) {
            $this->createLog("AdminsManager", $e);
            return self::AUTH_DATABASE_ERROR;
        }
    }

    public function checkAdminPassword(string $login, string $password): bool
    {
        try {
            $stmt = $this->db->prepare("SELECT `password_hash` FROM `admin_users` WHERE `login` = :login");
            if ($stmt->execute([':login' => $login])) {
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                if (isset($result['password_hash'])) {
                    if (password_verify($password, $result['password_hash'])) {
                        return true;
                    }
                }
            }
        }catch (PDOException|Exception|Error $e) {
            $this->createLog("AdminsManager", $e);
        }
        return false;
    }

    public function getRole(string $login): ?string
    {
        try {
            $stmt = $this->db->prepare(AdminsExecutor::GET_ROLE());
            $stmt->execute([':login' => $login]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if (is_array($result) && isset($result['role'])) {
                return $result['role'];
            }
        }catch (PDOException|Exception|Error $e) {
            $this->createLog("AdminsManager", $e);
        }
        return null;
    }

    public function getTotpSecret(string $login): ?string
    {
        try {
            $stmt = $this->db->prepare(AdminsExecutor::GET_TOTP_SECRET());
            $result = $stmt->execute([':login' => $login]);
            if ($result) {
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                if (isset($result['totp_secret'])) {
                    return $result['totp_secret'];
                }
            }
        }catch (PDOException|Exception|Error $e) {
            $this->createLog("AdminsManager", $e);
        }
        return null;
    }

    public function checkAdmin(string $login): ?bool
    {
        try {
            $this->createCustomLog("AdminsManager", "Логин проверяемого админа: ".$login);
            $stmt = $this->db->prepare(AdminsExecutor::CHECK_BLOCK());
            $stmt->bindValue(":login", $login, PDO::PARAM_STR);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->createCustomLog("AdminsManager", "Fetch по логину: ".json_encode($result));
            if (is_array($result) && count($result) > 0) {
                return !((bool) $result['is_locked']);
            }
        }catch (PDOException|\Exception|\Error $e) {
            $this->createLog("AgentsManager", $e);
        }
        return true;
    }

    public function getLastPassUpdate(string $login): ?string
    {
        try {
            $stmt = $this->db->prepare(AdminsExecutor::GET_LAST_PASSWORD_UPDATE());
            $stmt->execute([':login' => $login]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if (isset($result['password_updated_at'])) {
                return $result['password_updated_at'];
            }
        }catch (PDOException|Exception|Error $e) {
            $this->createLog("AdminsManager", $e);
        }
        return null;
    }

    public function setupTotp(string $login, string $secret, bool $is_enabled = true): bool
    {
        try {
            $stmt = $this->db->prepare(AdminsExecutor::SETUP_TOTP());
            $stmt->bindParam(':login', $login);
            $stmt->bindParam(':is_2fa_enabled', $is_enabled, PDO::PARAM_BOOL);
            $stmt->bindParam(':totp_secret', $secret, PDO::PARAM_STR);
            $result = $stmt->execute();
            if ($result) {
                return true;
            }
        }catch (PDOException|Exception|Error $e) {
            $this->createLog("AdminsManager", $e);
        }
        return false;
    }

    public function getAllAdmins(): array
    {
        try {
            $stmt = $this->db->prepare(AdminsExecutor::GET_ALL_ADMINS());
            $result = $stmt->execute();
            if ($result) {
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
        }catch (PDOException|Exception|Error $e) {
            $this->createLog("AdminsManager", $e);
        }
        return [];
    }

    public function updateLastData(string $admin_login, string $login_ip, string $current_date): bool
    {
        try {
            $stmt = $this->db->prepare(AdminsExecutor::UPDATE_LAST_DATA());
            $stmt->bindParam(':last_login_at', $current_date, PDO::PARAM_STR);
            $stmt->bindParam(':last_login_ip', $login_ip, PDO::PARAM_STR);
            $stmt->bindParam(':login', $admin_login, PDO::PARAM_STR);
            return $stmt->execute();
        }catch (PDOException|Exception|Error $e) {
            $this->createLog("AdminsManager", $e);
            return false;
        }
    }

    public function getById(int $adminId): array
    {
        try {
            $stmt = $this->db->prepare(AdminsExecutor::GET_BY_ID());
            $stmt->execute([':id' => $adminId]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if (is_array($result) && isset($result['id'])) {
                return $result;
            }
        }catch (PDOException|Exception|Error $e) {
            $this->createLog("AdminsManager", $e);
        }
        return [];
    }

    public function resetTotp(int $adminId): bool
    {
        try {
            $stmt = $this->db->prepare(AdminsExecutor::RESET_TOTP());
            $stmt->bindParam(':id', $adminId, PDO::PARAM_INT);
            $result = $stmt->execute();
            if ($result) {
                return true;
            }
        }catch (PDOException|Exception|Error $e) {
            $this->createLog("AdminsManager", $e);
        }
        return false;
    }

    public function changePassword(int $adminId, string $new_password): bool
    {
        try {
            $stmt = $this->db->prepare(AdminsExecutor::CHANGE_PASSWORD());
            $stmt->bindValue(':id', $adminId, PDO::PARAM_INT);
            $stmt->bindValue(':new_password', password_hash($new_password, PASSWORD_DEFAULT), PDO::PARAM_STR);
            $stmt->bindValue(':password_updated_at', date("Y-m-d H:i:s"), PDO::PARAM_STR);
            if ($stmt->execute()) {
                return true;
            }
        }catch (PDOException|Exception|Error $e) {
            $this->createLog("AdminsManager", $e);
        }
        return false;
    }

    public function changePasswordByLogin(mixed $admin_login, string $newPassword): bool
    {
        try {
            $stmt = $this->db->prepare("UPDATE `admin_users` SET `password_hash` = :new_password, `password_updated_at` = :password_updated_at WHERE `login` = :login;");
            $stmt->bindValue(':login', $admin_login, PDO::PARAM_STR);
            $stmt->bindValue(':new_password', password_hash($newPassword, PASSWORD_DEFAULT), PDO::PARAM_STR);
            $stmt->bindValue(':password_updated_at', date("Y-m-d H:i:s"), PDO::PARAM_STR);
            if ($stmt->execute()) {
                return true;
            }
        }catch (PDOException|Exception|Error $e) {
            $this->createLog("AdminsManager", $e);
        }
        return false;
    }

    public function removeAccount(int $adminId): bool
    {
        try {
            $stmt = $this->db->prepare(AdminsExecutor::REMOVE_ACCOUNT());
            $stmt->bindParam(':id', $adminId, PDO::PARAM_INT);
            return $stmt->execute();
        }catch (PDOException|Exception|Error $e) {
            $this->createLog("AdminsManager", $e);
        }
        return false;
    }

    public function exists(string $login): bool
    {
        try {
            $stmt = $this->db->prepare("SELECT `id` FROM `admin_users` WHERE `login` = :login");
            $stmt->bindParam(':login', $login, PDO::PARAM_STR);
            if ($stmt->execute()) {
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                if (is_array($result) && isset($result['id'])) {
                    return true;
                }
            }
        }catch (PDOException|Exception|Error $e) {
            $this->createLog("AdminsManager", $e);
        }
        return false;
    }

    public function updateAdmin(int $adminId, string $login, string $role, bool $is_locked): bool
    {
        try {
            $stmt = $this->db->prepare("UPDATE `admin_users` SET `login`=:login, `role`=:role, `is_locked`=:is_locked WHERE `id`=:id");
            $stmt->bindParam(':login', $login, PDO::PARAM_STR);
            $stmt->bindParam(':role', $role, PDO::PARAM_STR);
            $stmt->bindParam(':is_locked', $is_locked, PDO::PARAM_BOOL);
            $stmt->bindParam(':id', $adminId, PDO::PARAM_INT);
            if ($stmt->execute()) {
                return true;
            }
        }catch (PDOException|Exception|Error $e) {
            $this->createLog("AdminsManager", $e);
        }
        return false;
    }

    public function getAccountInfo(mixed $admin_login): array|false
    {
        try {
            $stmt = $this->db->prepare("SELECT `role`,`is_locked`,`last_login_at`,`password_updated_at` FROM `admin_users` WHERE `login` = :login");
            $stmt->bindParam(':login', $admin_login, PDO::PARAM_STR);
            if ($stmt->execute()) {
                return $stmt->fetch(PDO::FETCH_ASSOC);
            }
        }catch (PDOException|Exception|Error $e) {
            $this->createLog("AdminsManager", $e);
        }
        return false;
    }

    public function resetTotpByLogin(mixed $admin_login): bool
    {
        try {
            $stmt = $this->db->prepare("UPDATE `admin_users` SET `totp_secret` = NULL, `is_2fa_enabled`=0 WHERE `login` = :admin_login;");
            $stmt->bindParam(':admin_login', $admin_login, PDO::PARAM_STR);
            return $stmt->execute();
        }catch (PDOException|Exception|Error $e) {
            $this->createLog("AdminsManager", $e);
        }
        return false;
    }
}