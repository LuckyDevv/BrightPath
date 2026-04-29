<?php
namespace lib;
use managers\AdminsManager;
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
}