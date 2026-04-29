<?php
require_once __DIR__.'/../../vendor/autoload.php';
use managers\ServicesManager;
$servicesManager = new ServicesManager();
$services = $servicesManager->getAllServices(true);
?>
<div class="page-services">
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
            <select data-filter="category_service">
                <option value="all">Все категории</option>
                <option value="организация похорон">Организация похорон</option>
                <option value="кремация">Кремация</option>
                <option value="перевоз тела">Перевоз тела</option>
                <option value="юридическая помощь">Юридическая помощь</option>
                <option value="ритуальный транспорт">Ритуальный транспорт</option>
                <option value="поминальные обеды">Поминальные обеды</option>
                <option value="памятники и благоустройство">Памятники и благоустройство</option>
            </select>
            <select data-filter="status">
                <option value="all">Все статусы</option>
                <option value="активен">Активен</option>
                <option value="неактивен">Неактивен</option>
            </select>
        </div>
        <button class="btn-add" onclick="serviceAdd()">+</button>
    </div>

    <table class="data-table" id="services_table">
        <thead>
        <tr>
            <th>ID</th>
            <th>Название</th>
            <th>Категория</th>
            <th>Цена</th>
            <th>Статус</th>
            <th>Действия</th>
        </tr>
        </thead>
        <tbody>
        <?php
        foreach ($services as $service) {
            if ($service["is_active"]) {
                $status = 'active';
                $status_text = 'Активен';
            }else{
                $status = 'inactive';
                $status_text = 'Не активен';
            }
            echo '
                    <tr id="tr_'.$service["id"].'">
                        <td>'.$service["id"].'</td>
                        <td>'.$service["name"].'</td>
                        <td>'.getCategoryName($service["category"]).'</td>
                        <td>'. number_format($service["price"], 2, ',', ' ').' ₽</td>
                        <td><span class="status '.$status.'">'.$status_text.'</span></td>
                        <td>
                            <button class="btn-icon view" onclick="serviceView('.$service["id"].')">👁️</button>
                            <button class="btn-icon edit" onclick="serviceEdit('.$service["id"].')">✏️</button>
                            <button class="btn-icon delete" onclick="serviceDelete('.$service["id"].')">🗑️</button>
                        </td>
                    </tr>';
        }

        function getCategoryName($category): string
        {
            $map = [
                0 => 'Организация похорон', // Удалено!
                1 => 'Кремация',
                2 => 'Перевоз тела',
                3 => 'Юридическая помощь',
                4 => 'Ритуальный транспорт', // Удалено!
                5 => 'Поминальные обеды',
                6 => 'Памятники и благоустройство',
            ];
            return $map[$category] ?? "Не определено";
        }
        ?>
        </tbody>
    </table>
</div>
<div class="modal-overlay" id="srv_modal">
    <div class="modal-container" id="modal_container">
        <div class="modal-header">
            <h2 id="srv_modal_title" class="modal-title"></h2>
            <button class="modal-close" onclick="serviceClose()">&times;</button>
        </div>
        <div class="modal-content">
            <form class="modal-form">
                <div class="form-group">
                    <label>Название услуги</label>
                    <input type="text" value="" id="srv_modal_name" placeholder="Введите название услуги...">
                </div>
                <div class="form-group">
                    <label>Категория</label>
                    <select id="srv_modal_category">
                        <option disabled selected hidden>Выберите категорию</option>
                        <option id="srv_modal_option_organization">Организация похорон</option>
                        <option id="srv_modal_option_cremation">Кремация</option>
                        <option id="src_modal_option_perevoz">Перевоз тела</option>
                        <option id="src_modal_option_urpomosh">Юридическая помощь</option>
                        <option id="src_modal_option_transport">Ритуальный транспорт</option>
                        <option id="src_modal_option_lunch">Поминальные обеды</option>
                        <option id="srv_modal_option_monuments">Памятники и благоустройство</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Цена (руб.)</label>
                    <input type="number" placeholder="Введите цену..." value="" id="srv_modal_price">
                </div>
                <div class="form-group">
                    <label>Короткое описание</label>
                    <textarea rows="2" id="srv_modal_description" placeholder="Короткое описание услуги"></textarea>
                </div>
                <div class="form-group">
                    <label>Наполнение услуги</label>
                    <textarea rows="4" id="srv_modal_what_includes" placeholder="Введите наполнение услуги. Для перечисления используйте знак ';'"></textarea>
                </div>
                <div class="form-group">
                    <label>Статус</label>
                    <select id="srv_modal_status">
                        <option disabled selected hidden>Выберите статус</option>
                        <option id="srv_modal_option_unactive">Неактивен</option>
                        <option id="srv_modal_option_active">Активен</option>
                    </select>
                </div>
                <div class="form-group" id="srv_modal_creation_div">
                    <label for="srv_modal_creation">Дата создания:</label>
                    <input type="text" id="srv_modal_creation" placeholder="Дата создания неизвестна" disabled>
                </div>
                <div class="form-group" id="srv_modal_updated_div">
                    <label>Последнее редактирование</label>
                    <input type="text" id="srv_modal_updated" placeholder="Дата обновления неизвестна" disabled>
                </div>
                <button id="srv_modal_save" type="button" class="modal-submit" onclick="serviceSave()">Сохранить</button>
            </form>
        </div>
    </div>
</div>