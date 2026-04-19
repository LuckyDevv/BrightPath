INSERT INTO `vehicles` (
    `name`,
    `image_path`,
    `category`,
    `seats`,
    `color`,
    `description_short`,
    `description_full`,
    `price`,
    `total_stock`,
    `reserved_stock`,
    `is_active`,
    `orders_count`
) VALUES (
    :name,
    :image_path,
    :category,
    :seats,
    :color,
    :description_short,
    :description_full,
    :price,
    :total_stock,
    :reserved_stock,
    :is_active,
    :orders_count
)