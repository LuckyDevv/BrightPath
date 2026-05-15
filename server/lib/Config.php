<?php
namespace lib;
use Exception;
use managers\AdminsManager;
use managers\RequestsManager;
use managers\SessionManager;

class Config {
    public final const string MYSQL_CONNECTION = "mysql:host=localhost;charset=utf8mb4";
    public final const string MYSQL_USER = "root";
    public final const string MYSQL_PASSWORD = "5328alexRU";
    public final const array AUTH_MESSAGES_MAP = [
        AdminsManager::AUTH_SUCCESS => "Успешная авторизация!",
        AdminsManager::AUTH_PASSWORD_INVALID => "Введён неверный пароль!",
        AdminsManager::AUTH_TOTP_REQUIRED => "Необходима настройка TOTP!",
        AdminsManager::AUTH_USER_BLOCKED => "Пользователь заблокирован!",
        AdminsManager::AUTH_USER_NOT_EXISTS => "Пользователь не найден!",
        AdminsManager::AUTH_DATABASE_ERROR => "Произошла ошибка в базе данных!"
    ];
    public final const array SESSION_MESSAGES_MAP = [
        SessionManager::SESSION_SUCCESS => "Успешная проверка!",
        SessionManager::SESSION_PASSWORD_UPDATED => "Пароль был изменён. Войдите снова.",
        SessionManager::SESSION_PASSWORD_UPDATE_INVALID => "Не удалось получить дату обновления пароля!",
        SessionManager::SESSION_USER_BLOCKED => "Данный пользователь заблокирован!",
        SessionManager::SESSION_EXPIRED => "Сессия истекла. Войдите снова.",
        SessionManager::SESSION_DECRYPT_ERROR => "Вы входите под другим устройством. Войдите снова.",
        SessionManager::SESSION_NOT_EXISTS => "Сессия не найдена! Войдите снова.",
        SessionManager::SESSION_DATABASE_ERROR => "Произошла ошибка в базе данных!"
    ];
    public final const array REQUESTS_MESSAGES_MAP = [
        RequestsManager::NEW_OPERATOR_NULL => "Не передан логин оператора!",
        RequestsManager::OPERATORS_NOT_MATCH => "У вас нет доступа к этой заявке! Над ней работает другой оператор.",
        RequestsManager::STATUS_CHANGED_SUCCESS => "Статус успешно сменён",
        RequestsManager::STATUS_CHANGED_FAILURE => "Не удалось сменить статус заявки",
        RequestsManager::DATABASE_ERROR => "Произошла ошибка в базе данных!"
    ];

    private string $configFilePath = __DIR__."/../config.json";

    private array $defaultConfig = [
        'phone' => "+7 (925) 356-29-59",
        'phone_stock' => "+79253562959",
        'email' => "info@svetliyput.ru",
        'address' => "Московская обл., г. Одинцово, ул. Глазынинская, д. 18",
        'schedule' => 'Круглосуточно, без выходных',
        'telegram' => 'https://t.me/luckydevv',
        'vk' => 'https://vk.com/luckydevv',
        'max' => 'https://max.ru/u/f9LHodD0cOLbpcFnreZY30frnCGbVcW6VUJVr7kGvaiAQkt4N15YqAwx8ZE'
    ];

    private int $jsonOptions = JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_BIGINT_AS_STRING;

    public function __construct() {
        if (file_exists($this->configFilePath)) {
            $config = json_decode(file_get_contents($this->configFilePath), true);
            $required = ['phone', 'phone_stock', 'email', 'address', 'schedule', 'telegram', 'vk', 'max'];
            $config_broken = false;
            foreach ($required as $field) {
                if (empty($config[$field])) {
                    $config_broken = true;
                }
            }
            if ($config_broken) $this->loadDefaults();
        }else $this->loadDefaults();
    }

    private function loadDefaults(): void
    {
        file_put_contents($this->configFilePath, json_encode($this->defaultConfig, $this->jsonOptions));
    }

    public function getConfig(): array
    {
        $config = json_decode(file_get_contents($this->configFilePath), true);
        $config['addressForMap'] = str_replace(" ", "%20", $config['address']);
        return $config;
    }

    /**
     * @throws Exception
     */
    public function editConfig(array $newData): bool
    {
        $required = ['phone', 'phone_stock', 'email', 'address', 'schedule', 'telegram', 'vk', 'max'];
        foreach ($required as $field) {
            if (empty($newData[$field])) {
                throw new Exception("Поле {$field} обязательно для заполнения!");
            }
        }
        file_put_contents($this->configFilePath, json_encode($newData, $this->jsonOptions));
        return true;
    }
}