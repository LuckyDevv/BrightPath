SELECT
    id,
    name,
    image_path,
    price,
    orders_count,
    (total_stock - reserved_stock) AS available_stock
FROM vehicles
WHERE is_active = 1
  AND (total_stock - reserved_stock) > 0
ORDER BY orders_count DESC
    LIMIT 7;