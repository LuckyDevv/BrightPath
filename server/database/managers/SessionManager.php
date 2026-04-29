<?php
namespace managers;
use DateTime;
use Error;
use Exception;
use executors\SessionExecutor;
use PDO;
use PDOException;

class SessionManager extends Manager {

    public final const int SESSION_SUCCESS = 0;
    public final const int SESSION_PASSWORD_UPDATED = 1;
    public final const int SESSION_PASSWORD_UPDATE_INVALID = 2;
    public final const int SESSION_USER_BLOCKED = 3;
    public final const int SESSION_EXPIRED = 4;
    public final const int SESSION_DECRYPT_ERROR = 5;
    public final const int SESSION_NOT_EXISTS = 6;
    public final const int SESSION_DATABASE_ERROR = 7;


    public function __construct()
    {
        parent::__construct(SessionExecutor::CREATE_TABLE());
    }

    public function verifySession(int $session_id, string $master_key, string $login_ip, bool $is_login=true): int
    {
        try {
            $stmt = $this->db->prepare(SessionExecutor::VERIFY_SESSION());
            $stmt->execute([":session_id" => $session_id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $stmt->closeCursor();
            if (is_array($row) && count($row) > 0) {
                $encryptedData = $row["session_hash"];
                $decryptedData = $this->decodeSession($encryptedData, $master_key);
                if (is_array($decryptedData)) {
                    if ($decryptedData["session_expires_at"] > time()) {
                        $admin_login = $decryptedData["admin_login"];
                        $adminsManager = new AdminsManager();
                        if ($adminsManager->checkAdmin($admin_login)) {
                            $password_update_at = $adminsManager->getLastPassUpdate($admin_login);
                            if ($password_update_at !== null) {
                                $date = new DateTime($password_update_at);
                                if ($date->getTimestamp() < $decryptedData["session_created_at"]) {
                                    if ($is_login) {
                                        $current_date = date("Y-m-d H:i:s");
                                        $adminsManager->updateLastData($admin_login, $login_ip, $current_date);
                                    }
                                    return self::SESSION_SUCCESS;
                                }else $err_code = self::SESSION_PASSWORD_UPDATED;
                            }else $err_code = self::SESSION_PASSWORD_UPDATE_INVALID;
                        }else $err_code = self::SESSION_USER_BLOCKED;
                    }else $err_code = self::SESSION_EXPIRED;
                }else $err_code = self::SESSION_DECRYPT_ERROR;
            }else $err_code = self::SESSION_NOT_EXISTS;
        }catch (PDOException|Exception|Error $e) {
            $this->createLog("SessionManager", $e);
            $err_code = self::SESSION_DATABASE_ERROR;
        }
        if (
            $err_code == self::SESSION_PASSWORD_UPDATED ||
            $err_code == self::SESSION_USER_BLOCKED ||
            $err_code == self::SESSION_EXPIRED
        ) $this->removeSession($session_id);
        return $err_code;
    }

    public function removeSession(int $session_id): void
    {
        try {
            $stmt = $this->db->prepare(SessionExecutor::REMOVE_SESSION());
            $stmt->execute([":session_id" => $session_id]);
        }catch (PDOException|Exception|Error $e) {
            $this->createLog("SessionManager", $e);
        }
    }

    public function createSession(string $admin_login, string $master_key): ?int {
        try {
            $stmt = $this->db->prepare(SessionExecutor::CREATE_SESSION());
            $sessionData = [
                'admin_login' => $admin_login,
                'session_created_at' => time(),
                'session_expires_at' => time() + 28800, // 8 часов
            ];
            // 5. Шифруем данные (ключ = masterKey)
            $iv = random_bytes(12);
            $encrypted = openssl_encrypt(
                json_encode($sessionData),
                'aes-256-gcm',
                $master_key,
                OPENSSL_RAW_DATA,
                $iv,
                $tag
            );
            $encryptedData = base64_encode($iv . $encrypted . $tag);
            $stmt->bindParam(":session_hash", $encryptedData);
            if ($stmt->execute()) {
                return $this->db->lastInsertId();
            }
        }catch (PDOException|Exception|Error $e) {
            $this->createLog("SessionManager", $e);
        }
        return null;
    }

    private function decodeSession(string $encryptedData, string $master_key): array|false
    {
        $decoded = base64_decode($encryptedData);
        $iv = substr($decoded, 0, 12);           // первые 12 байт — IV
        $tag = substr($decoded, -16);            // последние 16 байт — TAG
        $encrypted = substr($decoded, 12, -16);  // всё, что между — зашифрованные данные
        $decryptedJson = openssl_decrypt(
            $encrypted,
            'aes-256-gcm',
            $master_key,
            OPENSSL_RAW_DATA,
            $iv,
            $tag
        );
        $sessionData = json_decode($decryptedJson, true);
        if ($sessionData === null) {
            return false;
        }
        $admin_login = $sessionData['admin_login'];
        $session_created_at = $sessionData['session_created_at'];
        $session_expires_at = $sessionData['session_expires_at'];
        return [
            "admin_login" => $admin_login,
            "session_created_at" => $session_created_at,
            "session_expires_at" => $session_expires_at,
        ];
    }
}