CREATE TABLE IF NOT EXISTS `agents` (
    `id` INT PRIMARY KEY AUTO_INCREMENT COMMENT 'ID агента',
    `name` VARCHAR(255) NOT NULL COMMENT 'ФИО агента',
    `image_path` VARCHAR(500) NOT NULL COMMENT 'Путь к папке с изображениями (фото)',
    `position` INT NOT NULL COMMENT 'Должность',
    `birthdate` DATE NULL COMMENT 'Дата рождения',
    `events_count` INT NOT NULL DEFAULT 0 COMMENT 'Количество проведённых мероприятий',
    `description` TEXT COMMENT 'Описание, опыт работы',
    `biographic` TEXT COMMENT 'Полная подробная биография',
    `is_active` BOOLEAN NOT NULL DEFAULT TRUE COMMENT 'Работает ли агент',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Дата добавления',
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Дата обновления',
    INDEX idx_is_active (`is_active`),
    INDEX idx_id_active (`id`, `is_active`),
    INDEX idx_events_count (`events_count`),
    INDEX idx_created_at (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Таблица агентов';