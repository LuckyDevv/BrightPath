CREATE TABLE `goods` (
                `id` INT PRIMARY KEY AUTO_INCREMENT COMMENT 'ID товара',
                `name` VARCHAR(255) NOT NULL COMMENT 'Название товара',
                `image_path` VARCHAR(500) NOT NULL COMMENT 'Путь к папке с изображениями',
                `category` TINYINT NOT NULL DEFAULT 1 COMMENT 'Категория: 1-гробы, 2-венки, 3-кресты, 4-памятники, 5-одежда, 6-аксессуары',
                `material` VARCHAR(50) NOT NULL COMMENT 'Материал (сосна, дуб, гранит, ткань и т.д.)',
                `description_short` TEXT COMMENT 'Короткое описание (карточка)',
                `description_full` TEXT COMMENT 'Полное описание (детальная)',
                `price` DECIMAL(10,2) NOT NULL DEFAULT 0 COMMENT 'Цена за единицу',
                `orders_count` INT NOT NULL DEFAULT 0 COMMENT 'Сколько раз заказывали',
                `total_stock` INT NOT NULL DEFAULT 1 COMMENT 'Всего на складе',
                `reserved_stock` INT NOT NULL DEFAULT 0 COMMENT 'Зарезервировано',
                `is_active` BOOLEAN NOT NULL DEFAULT TRUE COMMENT 'Показывать на сайте',
                `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Дата создания',
                `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Дата обновления'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Таблица ритуальных товаров';