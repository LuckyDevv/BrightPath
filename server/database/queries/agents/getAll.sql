SELECT
    `id`,
    `name`,
    `image_path` AS `image`,
    `position`,
    `birthdate`,
    `events_count`,
    `description`,
    `biographic`,
    `is_active`,
    `created_at`
FROM `agents`
WHERE `is_active` = 1;