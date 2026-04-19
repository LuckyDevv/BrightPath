CREATE TABLE IF NOT EXISTS `admin_users` (
    `id` INT PRIMARY KEY AUTO_INCREMENT COMMENT 'ID администратора',
    `login` VARCHAR(50) NOT NULL UNIQUE COMMENT 'Логин для входа',
    `password_hash` VARCHAR(255) NOT NULL COMMENT 'Хеш пароля',
    `role` ENUM('admin', 'manager', 'operator') NOT NULL DEFAULT 'operator' COMMENT 'Роль пользователя',
    `is_locked` BOOLEAN NOT NULL DEFAULT FALSE COMMENT 'Статус блокировки аккаунта',
    `totp_secret` VARCHAR(255) NULL COMMENT 'Секретный ключ для 2FA (TOTP)',
    `is_2fa_enabled` BOOLEAN NOT NULL DEFAULT FALSE COMMENT 'Включена ли двухфакторная аутентификация',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Дата создания аккаунта',
    `password_updated_at` TIMESTAMP NULL COMMENT 'Дата последнего обновления пароля',
    `last_login_at` TIMESTAMP NULL COMMENT 'Дата последнего входа',
    `last_login_ip` VARCHAR(45) NULL COMMENT 'IP адрес последнего входа',
    INDEX idx_login (`login`),
    INDEX idx_role (`role`),
    INDEX idx_is_locked (`is_locked`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Таблица администраторов';