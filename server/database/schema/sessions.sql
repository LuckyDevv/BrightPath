CREATE TABLE IF NOT EXISTS `admin_sessions` (
    `id` INT PRIMARY KEY AUTO_INCREMENT COMMENT 'ID сессии',
    `session_hash` TEXT NOT NULL COMMENT 'Зашифрованные данные сессии'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;