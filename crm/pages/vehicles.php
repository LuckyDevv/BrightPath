<?php
require_once __DIR__.'/../../vendor/autoload.php';

use managers\AdminsManager;
use managers\SessionManager;
use managers\VehiclesManager;
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
$vehiclesManager = new VehiclesManager();
$vehicles = $vehiclesManager->getAllVehicles(true);
?>
<div class="page-vehicles">
    <div class="page-header-actions">
        <div class="search-bar">
            <input type="text" placeholder="Поиск по модели...">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <circle cx="11" cy="11" r="8" stroke-width="2"/>
                <line x1="21" y1="21" x2="16.65" y2="16.65" stroke-width="2"/>
            </svg>
        </div>
        <div class="filters">
            <select data-filter="category">
                <option value="all">Все категории</option>
                <option value="катафалк">Катафалки</option>
                <option value="автобус">Автобусы для гостей</option>
                <option value="легковой">Легковые автомобили</option>
                <option value="спецтранспорт">Спецтранспорт</option>
            </select>
            <select data-filter="status">
                <option value="all">Все статусы</option>
                <option value="активен">Активен</option>
                <option value="неактивен">Неактивен</option>
            </select>
        </div>
        <button class="btn-add" onclick="vehicleAdd()">+</button>
    </div>

    <table class="data-table" id="vehicles_table">
        <thead>
        <tr>
            <th>ID</th>
            <th>Фото</th>
            <th>Модель</th>
            <th>Категория</th>
            <th>Цена</th>
            <th>В наличии</th>
            <th>Статус</th>
            <th>Действия</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $placeholder = "this.src='../../src/images/no_image.jpg'";
        //var_dump($vehicles);
        foreach ($vehicles as $vehicle) {
            if ($vehicle["is_active"]) {
                $status = 'active';
                $status_text = 'Активен';
            }else{
                $status = 'inactive';
                $status_text = 'Не активен';
            }
            $action = $role == 'admin' ? '<button class="btn-icon delete" onclick="vehicleDelete('.$vehicle["id"].')">🗑️</button>' : '';
            echo '
                    <tr id="tr_'.$vehicle["id"].'">
                        <td>'.$vehicle["id"].'</td>
                        <td><img src="../src/images/vehicles/'.$vehicle["image_path"].'/main.jpg" class="table-thumb" onerror='.$placeholder.' alt="'.$vehicle["name"].'"></td>
                        <td>'.$vehicle["name"].'</td>
                        <td>'.getCategoryName($vehicle["category"]).'</td>
                        <td>'. number_format($vehicle["price"], 2, ',', ' ').' ₽</td>
                        <td>'.$vehicle["total_stock"].'</td>
                        <td><span class="status '.$status.'">'.$status_text.'</span></td>
                        <td>
                            <button class="btn-icon view" onclick="vehicleView('.$vehicle["id"].')">👁️</button>
                            <button class="btn-icon edit" onclick="vehicleEdit('.$vehicle["id"].')">✏️</button>
                            '.$action.'
                        </td>
                    </tr>';
        }

        function getCategoryName($category): string
        {
            $map = [
                    1 => 'Катафалк',
                    2 => 'Автобус для гостей',
                    3 => 'Легковой автомобиль',
                    4 => 'Спецтранспорт'
            ];
            return $map[$category] ?? "Не определено";
        }
        ?>
        </tbody>
    </table>
</div>
<div class="modal-overlay" id="vhc_modal">
    <div class="modal-container" id="modal_container">
        <div class="modal-header">
            <h2 id="vhc_modal_title" class="modal-title"></h2>
            <button class="modal-close" onclick="vehicleClose()">&times;</button>
        </div>
        <div class="modal-content">
            <form class="modal-form">
                <div class="form-group">
                    <label>Название модели</label>
                    <input type="text" value="" id="vhc_modal_name" placeholder="Введите название модели...">
                </div>
                <div class="form-group">
                    <label>Категория</label>
                    <select id="vhc_modal_category">
                        <option disabled selected hidden>Выберите категорию</option>
                        <option id="vhc_modal_option_hearse">Катафалк</option>
                        <option id="vhc_modal_option_bus">Автобус для гостей</option>
                        <option id="vhc_modal_option_passenger">Легковой</option>
                        <option id="vhc_modal_option_special">Спецтранспорт</option>
                    </select>
                </div>

                <!-- Блок фото -->
                <div class="form-group">
                    <label>Основное фото</label>
                    <div class="image-upload-area" id="main_photo_area">
                        <div class="image-preview" id="main_photo_preview">
                            <img id="main_photo_img" src="" alt="Предпросмотр">
                            <span class="preview-placeholder">Нет фото</span>
                        </div>
                        <input type="file" id="vhc_modal_main_photo" accept="image/*" class="image-input">
                        <button id="vhc_modal_add_main_img" type="button" class="btn-upload" onclick="document.getElementById('vhc_modal_main_photo').click()">Выбрать фото</button>
                    </div>
                </div>

                <div class="form-group">
                    <label>Дополнительные фото</label>
                    <div class="additional-photos-container" id="additional_photos_container">
                        <div class="additional-photos-grid" id="additional_photos_grid"></div>
                        <button id="vhc_modal_add_additional_img" type="button" class="btn-upload" onclick="addAdditionalPhotoField()">+ Добавить фото</button>
                    </div>
                </div>

                <div class="form-group">
                    <label>Цена (руб.)</label>
                    <input type="number" placeholder="Введите цену..." value="" id="vhc_modal_price">
                </div>
                <div class="form-group">
                    <label>Цвет</label>
                    <input type="text" placeholder="Введите цвет..." value="" id="vhc_modal_color">
                </div>
                <div class="form-group">
                    <label>Места для пассажиров (шт.)</label>
                    <input type="number" placeholder="Введите кол-во мест..." value="" id="vhc_modal_seats">
                </div>
                <div class="form-group">
                    <label>В наличии (шт.)</label>
                    <input type="number" placeholder="Введите кол-во в наличии..." value="" id="vhc_modal_stock">
                </div>
                <div class="form-group">
                    <label>Короткое описание</label>
                    <textarea rows="2" id="vhc_modal_dsc_short" placeholder="Короткое описание автомобиля"></textarea>
                </div>
                <div class="form-group">
                    <label>Описание</label>
                    <textarea rows="4" id="vhc_modal_dsc_full" placeholder="Подробное описание автомобиля"></textarea>
                </div>
                <div class="form-group">
                    <label>Статус</label>
                    <select id="vhc_modal_status">
                        <option disabled selected hidden>Выберите статус</option>
                        <option id="vhc_modal_option_unactive">Неактивен</option>
                        <option id="vhc_modal_option_active">Активен</option>
                    </select>
                </div>
                <div class="form-group" id="vhc_modal_creation_div">
                    <label for="vhc_modal_creation">Дата создания:</label>
                    <input type="text" id="vhc_modal_creation" placeholder="Дата создания неизвестна" disabled>
                </div>
                <div class="form-group" id="vhc_modal_updated_div">
                    <label>Последнее редактирование</label>
                    <input type="text" id="vhc_modal_updated" placeholder="Дата обновления неизвестна" disabled>
                </div>
                <button id="vhc_modal_save" type="button" class="modal-submit" onclick="vehicleSave()">Сохранить</button>
            </form>
        </div>
    </div>
</div>