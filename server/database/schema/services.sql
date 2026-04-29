CREATE TABLE IF NOT EXISTS `services` (
    `id` INT PRIMARY KEY AUTO_INCREMENT COMMENT 'ID услуги',
    `name` VARCHAR(255) NOT NULL COMMENT 'Название услуги',
    `category` TINYINT NOT NULL DEFAULT 0 COMMENT 'Категория услуги',
    `description` TEXT COMMENT 'Краткое описание',
    `what_includes` TEXT COMMENT 'Что входит в услугу',
    `price` DECIMAL(10,2) NOT NULL DEFAULT 0 COMMENT 'Цена за услугу',
    `orders_count` INT NOT NULL DEFAULT 0 COMMENT 'Сколько раз заказывали',
    `is_active` BOOLEAN NOT NULL DEFAULT TRUE COMMENT 'Показывать на сайте',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Дата создания',
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Дата обновления'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;