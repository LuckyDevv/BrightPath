<?php
include_once __DIR__."/../../vendor/autoload.php";

use managers\AdminsManager;
use managers\GoodsManager;
use managers\SessionManager;

$adminsManager = new AdminsManager();
if (isset($_COOKIE['session_id'])) {
    if (is_numeric($_COOKIE['session_id'])) {
        $login = new SessionManager()->getLoginById((int)$_COOKIE['session_id']);
        if ($login === false) {
            not_authorized();
        }
    }else not_authorized();
}else not_authorized();
$role = $adminsManager->getRole($login);
if ($role !== 'admin' && $role !== 'manager') {
    exit("У вас нет доступа к данной странице!");
}
function not_authorized(): never {
    setcookie('session_id', '', time() - 3600, '/', '', false);
    setcookie('login', '', time() - 3600, '/', '', false);
    header('Location: ../auth.php');
    exit;
}
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
        <button class="btn-add" onclick="goodsAdd()">+</button>
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
            $action = $role == 'admin' ? "<button class='btn-icon delete' onclick='goodsRemove(".$goods["id"].")'>🗑️</button>" : '';
            echo "
        <tr id='tr_".$goods["id"]."'>
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
                {$action}
            </td>
        </tr>";
        }
        ?>
        </tbody>
    </table>
</div>
<!-- Модальное окно товара -->
<div class="modal-overlay" id="gds_modal">
    <div class="modal-container">
        <div class="modal-header">
            <h2 id="gds_modal_title" class="modal-title"></h2>
            <button class="modal-close" onclick="goodsClose()">&times;</button>
        </div>
        <div class="modal-content">
            <form class="modal-form">
                <div class="form-group">
                    <label>Название товара</label>
                    <input type="text" id="gds_modal_name" placeholder="Введите название...">
                </div>

                <div class="form-group">
                    <label>Категория</label>
                    <select id="gds_modal_category">
                        <option value="1">Гробы</option>
                        <option value="2">Венки</option>
                        <option value="3">Кресты</option>
                        <option value="4">Памятники</option>
                        <option value="5">Одежда</option>
                        <option value="6">Аксессуары</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Материал</label>
                    <input type="text" id="gds_modal_material" placeholder="Например: сосна, дуб, гранит...">
                </div>

                <div class="form-group">
                    <label>Размер (Д×Ш×В)</label>
                    <input type="text" id="gds_modal_sizes" placeholder="Например: 200×60×40 см">
                </div>

                <div class="form-group">
                    <label>Вес (кг)</label>
                    <input type="number" id="gds_modal_weight" placeholder="Введите вес">
                </div>

                <div class="form-group">
                    <label>Цена (₽)</label>
                    <input type="number" id="gds_modal_price" placeholder="Введите цену" step="0.01">
                </div>

                <div class="form-group">
                    <label>На складе (шт.)</label>
                    <input type="number" id="gds_modal_stock" placeholder="Количество" value="1">
                </div>

                <div class="form-group">
                    <label>Короткое описание</label>
                    <textarea rows="2" id="gds_modal_dsc_short" placeholder="Краткое описание товара"></textarea>
                </div>

                <div class="form-group">
                    <label>Полное описание</label>
                    <textarea rows="4" id="gds_modal_dsc_full" placeholder="Подробное описание товара"></textarea>
                </div>

                <!-- Фото -->
                <div class="form-group">
                    <label>Основное фото</label>
                    <div class="image-upload-area" id="main_photo_area">
                        <div class="image-preview" id="main_photo_preview">
                            <img id="main_photo_img" src="" alt="Предпросмотр">
                            <span class="preview-placeholder">Нет фото</span>
                        </div>
                        <input type="file" id="gds_modal_main_photo" accept="image/*" class="image-input">
                        <button type="button" class="btn-upload" onclick="document.getElementById('gds_modal_main_photo').click()">Выбрать фото</button>
                    </div>
                </div>

                <div class="form-group" id="gds_modal_creation_div">
                    <label>Дата создания:</label>
                    <input type="text" id="gds_modal_creation" disabled>
                </div>

                <div class="form-group" id="gds_modal_updated_div">
                    <label>Последнее редактирование:</label>
                    <input type="text" id="gds_modal_updated" disabled>
                </div>

                <button id="gds_modal_save" type="button" class="modal-submit" onclick="goodsSave()">Сохранить</button>
            </form>
        </div>
    </div>
</div>