<?php
include_once __DIR__."/../../vendor/autoload.php";
use managers\GoodsManager;
$goodsManager = new GoodsManager();
$allGoods = $goodsManager->getAllGoods(true);

// Вспомогательные функции (можно вынести в отдельный файл)
function getCategoryName($category): string
{
    $map = [
            1 => 'Гробы',
            2 => 'Венки',
            3 => 'Кресты',
            4 => 'Памятники',
            5 => 'Одежда',
            6 => 'Аксессуар',
    ];
    return $map[$category] ?? "Не определено";
}
function upfirstutf(string $str): string
{
    $char = mb_strtoupper(substr($str,0,2), "utf-8"); // это первый символ
    $str[0] = $char[0];
    $str[1] = $char[1];
    return $str;
}
?>
<div class="page-goods">
    <div class="page-header-actions">
        <button class="btn-export">📊 Экспорт</button>
        <div class="search-bar">
            <input type="text" placeholder="Поиск по названию...">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <circle cx="11" cy="11" r="8" stroke-width="2"/>
                <line x1="21" y1="21" x2="16.65" y2="16.65" stroke-width="2"/>
            </svg>
        </div>
        <div class="filters">
            <select data-filter="category">
                <option value="all">Все категории</option>
                <option value="гробы">Гробы</option>
                <option value="венки">Венки</option>
                <option value="кресты">Кресты</option>
                <option value="памятники">Памятники</option>
                <option value="одежда">Одежда</option>
                <option value="аксессуары">Аксессуары</option>
            </select>
            <select data-filter="material">
                <option value="all">Все материалы</option>
                <option value="сосна">Сосна</option>
                <option value="дуб">Дуб</option>
                <option value="красное дерево">Красное дерево</option>
                <option value="гранит">Гранит</option>
                <option value="мрамор">Мрамор</option>
                <option value="ткань">Ткань</option>
                <option value="искусственные цветы">Искусственные цветы</option>
            </select>
            <select data-filter="status">
                <option value="all">Все статусы</option>
                <option value="активен">Активен</option>
                <option value="неактивен">Неактивен</option>
            </select>
        </div>
        <button class="btn-add">+</button>
    </div>

    <table class="data-table">
        <thead>
        <tr>
            <th>ID</th>
            <th>Фото</th>
            <th>Название</th>
            <th>Категория</th>
            <th>Материал</th>
            <th>Цена</th>
            <th>На складе</th>
            <th>Статус</th>
            <th>Действия</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $placeholder = '../../src/images/no-image.jpg';
        foreach ($allGoods as $goods) {
            $img = "../../src/images/goods/".$goods["image_path"];
            echo "
        <tr>
            <td>".$goods["id"]."</td>
            <td><img src='".$img."' class='table-thumb' onerror='this.src=".$placeholder."'></td>
            <td>".$goods["name"]."</td>
            <td>".getCategoryName($goods["category"])."</td>
            <td>".upfirstutf($goods["material"])."</td>
            <td>".number_format($goods["price"], 2, ',', ' ')." ₽</td>
            <td>".$goods["total_stock"]."</td>
            <td><span class='status active'>Активен</span></td>
            <td>
                <button class='btn-icon view' onclick='goodsView(".$goods["id"].")'>👁️</button>
                <button class='btn-icon edit' onclick='goodsEdit(".$goods["id"].")'>✏️</button>
                <button class='btn-icon delete' onclick='goodsRemove(".$goods["id"].")'>🗑️</button>
            </td>
        </tr>";
        }
        ?>
        </tbody>
    </table>
</div>