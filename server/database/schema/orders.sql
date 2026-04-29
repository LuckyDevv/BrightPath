CREATE TABLE IF NOT EXISTS `orders` (
    `id` INT PRIMARY KEY AUTO_INCREMENT COMMENT 'Номер заказа (ID)',
    `status` ENUM('created', 'in_work', 'completed', 'cancelled') NOT NULL DEFAULT 'created' COMMENT 'Статус заказа',
    `agents` TEXT NULL COMMENT 'ID агентов через запятую (например: "1,3,5")',
    `transport` JSON NOT NULL COMMENT 'JSON с заказанными автомобилями',
    `goods` JSON NOT NULL COMMENT 'JSON с заказанными товарами',
    `services` JSON NOT NULL COMMENT 'JSON с заказанными услугами',
    `username` VARCHAR(255) NOT NULL COMMENT 'Имя пользователя',
    `userphone` VARCHAR(20) NOT NULL COMMENT 'Номер телефона пользователя',
    `useremail` VARCHAR(255) NULL COMMENT 'Email пользователя',
    `summary` DECIMAL(12,2) NOT NULL DEFAULT 0 COMMENT 'Окончательная сумма заказа (₽)',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Дата создания заказа',
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Дата последнего обновления'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Таблица заказов';