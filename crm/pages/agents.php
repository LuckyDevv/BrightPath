<?php

require_once  __DIR__.'/../../vendor/autoload.php';

use managers\AgentsManager;

$agentsManager = new AgentsManager();
$agents = $agentsManager->getAll(true);

?>
<div class="page-agents">
    <div class="page-header-actions">
        <button class="btn-export">📊 Экспорт</button>
        <div class="search-bar">
            <label>
                <input type="text" placeholder="Поиск по имени, должности...">
            </label>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <circle cx="11" cy="11" r="8" stroke-width="2"/>
                <line x1="21" y1="21" x2="16.65" y2="16.65" stroke-width="2"/>
            </svg>
        </div>
        <div class="filters">
            <label>
                <select data-filter="position">
                    <option value="all">Все должности</option>
                    <option value="руководитель">Руководитель</option>
                    <option value="ведущий агент">Ведущий агент</option>
                    <option value="старший агент">Старший агент</option>
                    <option value="агент">Агент</option>
                    <option value="менеджер">Менеджер</option>
                </select>
            </label>
            <label>
                <select data-filter="status">
                    <option value="all">Все статусы</option>
                    <option value="активен">Активен</option>
                    <option value="неактивен">Неактивен</option>
                </select>
            </label>
        </div>
        <button class="btn-add" onclick="agentAdd()">+</button>
    </div>

    <table class="data-table">
        <thead>
        <tr>
            <th>ID</th>
            <th>Фото</th>
            <th>ФИО</th>
            <th>Должность</th>
            <th>Возраст</th>
            <th>Стаж</th>
            <th>Мероприятий</th>
            <th>Статус</th>
            <th>Действия</th>
        </tr>
        </thead>
        <tbody>
        <?php
        function number($n, $titles) {
            $cases = array(2, 0, 1, 1, 1, 2);
            return $titles[($n % 100 > 4 && $n % 100 < 20) ? 2 : $cases[min($n % 10, 5)]];
        }
        $positions_map = [
            1 => 'Руководитель',
            2 => "Ведущий агент",
            3 => "Старший агент",
            4 => "Менеджер",
            5 => "Агент"
        ];
        foreach ($agents as $agent) {
            $placeholder = '../../src/images/no-image.jpg';
            if ((bool)$agent['is_active']) {
                $statusText = 'Активен';
                $statusClass = 'active';
            }else{
                $statusText = 'Не активен';
                $statusClass = '';
            }
            $position = $positions_map[$agent['position']];
            $age = $agent['age'].' '.number($agent['age'], ["год", "года", "лет"]);
            $experience = $agent['experience'].' '.number($agent['experience'], ["год", "года", "лет"]);
            echo '<tr id="tr_'.$agent["id"].'">
            <td>'.$agent['id'].'</td>
            <td><img src="../src/images/agents/'.$agent['image'].'" class="table-thumb" onerror="this.src='.$placeholder.'"></td>
            <td>'.$agent['name'].'</td>
            <td>'.$position.'</td>
            <td>'.$age.'</td>
            <td>'.$experience.'</td>
            <td>'.$agent['events_count'].'</td>
            <td><span class="status '.$statusClass.'">'.$statusText.'</span></td>
            <td>
                <button class="btn-icon view" onclick="agentView('.$agent['id'].')">👁️</button>
            <button class="btn-icon edit" onclick="agentEdit('.$agent['id'].')">✏️</button>
            <button class="btn-icon delete" onclick="agentDelete('.$agent['id'].')">🗑️</button>
            </td>
        </tr>';
        }
        ?>
        </tbody>
    </table>
</div>
<div class="modal-overlay" id="agt_modal">
    <div class="modal-container" id="modal_container">
        <div class="modal-header">
            <h2 id="agt_modal_title" class="modal-title"></h2>
            <button class="modal-close" onclick="agentClose()">&times;</button>
        </div>
        <div class="modal-content">
            <form class="modal-form">
                <div class="form-group">
                    <label for="agt_modal_surname">Фамилия агента</label>
                    <input type="text" value="" id="agt_modal_surname" placeholder="Введите фамилию агента...">
                </div>
                <div class="form-group">
                    <label for="agt_modal_name">Имя агента</label>
                    <input type="text" value="" id="agt_modal_name" placeholder="Введите имя агента...">
                </div>
                <div class="form-group">
                    <label for="agt_modal_patronymic">Отчество агента (если есть)</label>
                    <input type="text" value="" id="agt_modal_patronymic" placeholder="Введите отчество агента...">
                </div>
                <div class="form-group">
                    <label for="agt_modal_position">Должность</label>
                    <select id="agt_modal_position">
                        <option disabled selected hidden>Выберите должность</option>
                        <option id="agt_modal_option_boss">Руководитель</option>
                        <option id="agt_modal_option_staff">Ведущий агент</option>
                        <option id="agt_modal_option_staff2">Старший агент</option>
                        <option id="agt_modal_option_manager">Менеджер</option>
                        <option id="agt_modal_option_agent">Агент</option>
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
                        <input type="file" id="agt_modal_main_photo" accept="image/*" class="image-input">
                        <button id="agt_modal_add_main_img" type="button" class="btn-upload" onclick="document.getElementById('agt_modal_main_photo').click()">Выбрать фото</button>
                    </div>
                </div>
                <div class="form-group">
                    <label for="agt_modal_birthdate">Дата рождения</label>
                    <input type="date" id="agt_modal_birthdate" name="birthdate">
                    <small class="form-hint">Формат: ДД.ММ.ГГГГ</small>
                </div>
                <div class="form-group">
                    <label for="agt_modal_dsc_short">Короткое описание</label>
                    <textarea rows="2" id="agt_modal_dsc_short" placeholder="Короткое описание агента"></textarea>
                </div>
                <div class="form-group">
                    <label for="agt_modal_biography">Биография</label>
                    <textarea rows="4" id="agt_modal_biography" placeholder="Подробное биографию агента"></textarea>
                </div>
                <div class="form-group" id="agt_modal_creation_div">
                    <label for="agt_modal_creation">Дата создания:</label>
                    <input type="text" id="agt_modal_creation" placeholder="Дата создания неизвестна" disabled>
                </div>
                <div class="form-group" id="agt_modal_updated_div">
                    <label for="agt_modal_updated">Последнее редактирование</label>
                    <input type="text" id="agt_modal_updated" placeholder="Дата обновления неизвестна" disabled>
                </div>
                <button id="agt_modal_save" type="button" class="modal-submit" onclick="agentSave()">Сохранить</button>
            </form>
        </div>
    </div>
</div>