INSERT INTO `agents` (
    `name`,
    `image_path`,
    `position`,
    `birthdate`,
    `events_count`,
    `description`,
    `biographic`,
    `is_active`
) VALUES (
    :name,
    :image_path,
    :position,
    :birthdate,
    :events_count,
    :description,
    :biographic,
    :is_active
);