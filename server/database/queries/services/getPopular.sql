SELECT
    `id`,
    `name`,
    `orders_count`
FROM `services`
WHERE `is_active` = 1 AND `orders_count` > 0
ORDER BY `orders_count` DESC
LIMIT 7;