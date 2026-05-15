CREATE TABLE IF NOT EXISTS `admin_sessions` (
    `id` INT PRIMARY KEY AUTO_INCREMENT COMMENT 'ID сессии',
    `session_hash` TEXT NOT NULL COMMENT 'Зашифрованные данные сессии',
    `login` VARCHAR(50) NOT NULL COMMENT 'Логин администратора',
    INDEX idx_session_hash (`session_hash`(255))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;