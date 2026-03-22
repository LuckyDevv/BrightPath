CREATE TABLE `agents` (
                `id` INT PRIMARY KEY AUTO_INCREMENT COMMENT 'ID агента',
                `name` VARCHAR(255) NOT NULL COMMENT 'ФИО агента',
                `image_path` VARCHAR(500) NOT NULL COMMENT 'Путь к папке с изображениями (фото)',
                `position` VARCHAR(100) NOT NULL COMMENT 'Должность',
                `age` TINYINT NOT NULL COMMENT 'Возраст',
                `experience` INT NOT NULL DEFAULT 0 COMMENT 'Стаж работы (лет)',
                `events_count` INT NOT NULL DEFAULT 0 COMMENT 'Количество проведённых мероприятий',
                `description` TEXT COMMENT 'Описание, опыт работы',
                `price` DECIMAL(10,2) NOT NULL DEFAULT 0 COMMENT 'Цена за услугу',
                `phone` VARCHAR(20) NOT NULL COMMENT 'Контактный телефон',
                `is_active` BOOLEAN NOT NULL DEFAULT TRUE COMMENT 'Работает ли агент',
                `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Дата добавления',
                `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Дата обновления'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Таблица агентов';