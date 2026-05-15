CREATE TABLE IF NOT EXISTS `requests` (
    `id` INT PRIMARY KEY AUTO_INCREMENT COMMENT 'ID (номер) заявки',
    `userphone` VARCHAR(20) NOT NULL COMMENT 'Телефон клиента',
    `useremail` VARCHAR(255) NOT NULL COMMENT 'Эл. почта клиента',
    `username` VARCHAR (255) NOT NULL COMMENT 'Имя клиента',
    `operator` VARCHAR(50) COMMENT 'Логин оператора, который обработал заявку',
    `comment` TEXT COMMENT 'Комментарий по закрытии заявки',
    `status` ENUM('created', 'in_work', 'completed') NOT NULL DEFAULT 'created' COMMENT 'Статус заявки',
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Дата создания заявки',
    `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Дата обновления заявки',
    INDEX idx_status (`status`),
    INDEX idx_operator (`operator`),
    INDEX idx_created_at (`created_at`),
    INDEX idx_userphone (`userphone`(20)),
    INDEX idx_useremail (`useremail`(100))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Таблица заявок на консультацию';