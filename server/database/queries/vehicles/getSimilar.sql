SELECT `id`, `name`, `image_path`, `price`, (`total_stock` - `reserved_stock`) AS `available_stock`
FROM `vehicles`
WHERE `category` = :category
AND `id` != :current_id
AND `is_active` = 1
AND (`total_stock` - `reserved_stock`) > 0
ORDER BY `orders_count` DESC, `price` ASC
LIMIT :limit;