CREATE TABLE IF NOT EXISTS `presets` (
    `id` INT PRIMARY KEY AUTO_INCREMENT COMMENT 'ID пресета',
    `name` VARCHAR(20) NOT NULL COMMENT 'Название пресета',
    `transport` JSON NOT NULL COMMENT 'Транспорт, включённый в набор',
    `goods` JSON NOT NULL COMMENT 'Товары, включённые в набор',
    `services` JSON NOT NULL COMMENT 'Услуги, включённые в набор',
    `quote` VARCHAR(29) NOT NULL COMMENT 'Цитата пресета',
    `decoration` ENUM ('default', 'popular', 'vip', 'primary') NOT NULL DEFAULT 'default' COMMENT 'Визуальный стиль набора',
    `decoration_quote` VARCHAR(19) COMMENT 'Цитата стиля набора',
    `price` INT NOT NULL DEFAULT 10000 COMMENT 'Стоимость набора',
    `is_active` BOOLEAN NOT NULL DEFAULT 1 COMMENT 'Статус активности набора',
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Дата создания набора',
    `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Дата обновления набора',
    INDEX idx_is_active (`is_active`),
    INDEX idx_price (`price`),
    INDEX idx_decoration (`decoration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Таблица наборов (пресетов)';