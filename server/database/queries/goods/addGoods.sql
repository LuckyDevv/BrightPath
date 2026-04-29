INSERT INTO `goods` (
     `name`,
     `category`,
     `image_path`,
     `material`,
     `description_short`,
     `description_full`,
     `price`, `total_stock`,
     `sizes`,
     `weight`
)
VALUES (
     :name,
     :category,
     :image_path,
     :material,
     :description_short,
     :description_full,
     :price, :total_stock,
     :sizes,
     :weight
);