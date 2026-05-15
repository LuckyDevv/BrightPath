<?php
namespace lib;
class AdminLogger {
    public function __construct() {
        @mkdir(__DIR__."/../admins_logs/");
    }
    public function log(string $handler, string $login, string $type, array $data): void
    {
        $fileName = __DIR__."/../admins_logs/".$login."_log.json";
        if (!file_exists($fileName)) {
            file_put_contents($fileName, json_encode([
                "handler_name" => $handler,
                "type" => $type,
                "datetime" => date("d.m.Y H:i:s"),
                "timestamp" => time(),
                "admin_login" => $login,
                "data" => $data
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        }else{
            $log = json_decode(file_get_contents($fileName), true);
            $log[] = [
                "handler_name" => $handler,
                "type" => $type,
                "datetime" => date("d.m.Y H:i:s"),
                "timestamp" => time(),
                "admin_login" => $login,
                "data" => $data
            ];
            file_put_contents($fileName, json_encode($log, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        }
    }
}