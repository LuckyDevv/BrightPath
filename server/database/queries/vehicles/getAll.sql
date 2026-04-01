SELECT
    `id`,
    `name`,
    `image_path`,
    `seats`,
    `category`,
    `creation_year` AS `year`,
    `color`,
    `transmission`,
    `fuel`,
    `description_short` AS `description`,
    `price`,
    `orders_count` AS `orders`,
    `total_stock` AS `stock`,
    (`total_stock` - `reserved_stock`) AS `available`,
    `is_active`
FROM `vehicles`
WHERE `is_active` = 1
ORDER BY `orders_count` DESC, `price` ASC;