<?php
include_once __DIR__."/../../vendor/autoload.php";
use managers\AdminsManager;
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
if ($role !== 'admin') {
    exit("У вас нет доступа к данной странице!");
}
function not_authorized(): never {
    setcookie('session_id', '', time() - 3600, '/', '', false);
    setcookie('login', '', time() - 3600, '/', '', false);
    header('Location: ../auth.php');
    exit;
}
?>
<div class="page-admins">
    <div class="page-header-actions">
        <div class="search-bar">
            <input type="text" placeholder="Поиск по логину...">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <circle cx="11" cy="11" r="8" stroke-width="2"/>
                <line x1="21" y1="21" x2="16.65" y2="16.65" stroke-width="2"/>
            </svg>
        </div>
        <div class="filters">
            <select data-filter="role">
                <option value="all">Все роли</option>
                <option value="администратор">Администратор</option>
                <option value="менеджер">Менеджер</option>
                <option value="оператор">Оператор</option>
            </select>
            <select data-filter="status">
                <option value="all">Все статусы</option>
                <option value="активен">Активен</option>
                <option value="заблокирован">Заблокирован</option>
            </select>
        </div>
        <button class="btn-add" onclick="addAdminModal()">+</button>
    </div>

    <table class="data-table">
        <thead>
        <tr>
            <th>ID</th>
            <th>Логин</th>
            <th>Роль</th>
            <th>Последний вход</th>
            <th>Дата создания</th>
            <th>Статус</th>
            <th>Действия</th>
        </tr>
        </thead>
        <tbody id="admin_table">
        <?php
        $admins = $adminsManager->getAllAdmins();
        if (count($admins) > 0) {
            $roles_map = [
                    "admin" => '<span class="role-badge admin">Администратор</span>',
                    "manager" => '<span class="role-badge manager">Менеджер</span>',
                    "operator" => '<span class="role-badge operator">Оператор</span>',
            ];
            foreach ($admins as $admin) {
                $status = $admin['is_locked'] ? '<span class="status inactive">Заблокирован</span>' : '<span class="status active">Активен</span>';
                $last_login = $admin['last_login_at'];
                if ($last_login == 1 || $last_login == NULL) {
                    $last_login = "Нет данных";
                }
                echo '<tr id="adm_'.$admin['id'].'">
                          <td>'.$admin['id'].'</td>
                          <td>'.$admin['login'].'</td>
                          <td>'.$roles_map[$admin['role']].'</td>
                          <td>'.$last_login.'</td>
                          <td>'.$admin['created_at'].'</td>
                          <td>'.$status.'</td>
                          <td>
                              <button class="btn-reset-2fa" style="width: auto" onclick="adminEdit('.$admin['id'].')">Редактировать</button>
                          </td>
                      </tr>';
            }
        }
        ?>
        </tbody>
    </table>
</div>
<div class="modal-overlay" id="adm_modal_add">
    <div class="modal-container" id="adm_modal_edit_container">
        <div class="modal-header">
            <h2 id="adm_modal_edit_title" class="modal-title">Добавление администратора</h2>
            <button class="modal-close" onclick="adminCloseCreate()">&times;</button>
        </div>
        <div class="modal-content">
            <form class="modal-form">
                <div class="form-group">
                    <label>Логин администратора</label>
                    <input type="text" id="adm_modal_open_login">
                </div>
                <!-- Роль (выбор) -->
                <div class="form-group">
                    <label>Роль</label>
                    <select id="adm_modal_add_role">
                        <option value="operator">Оператор</option>
                        <option value="manager">Менеджер</option>
                        <option value="admin">Администратор</option>
                    </select>
                </div>
                <!-- Кнопки действий -->
                <div class="form-actions">
                    <button type="button" class="modal-submit" id="adm_modal_add_save" onclick="adminSaveAdd()">Сохранить изменения</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal-overlay" id="adm_modal_edit">
    <div class="modal-container" id="adm_modal_edit_container">
        <div class="modal-header">
            <h2 id="adm_modal_edit_title" class="modal-title">Панель управления</h2>
            <button class="modal-close" onclick="adminCloseEdit()">&times;</button>
        </div>
        <div class="modal-content">
            <form class="modal-form">
                <!-- ID администратора (только чтение) -->
                <div class="form-group">
                    <label>ID администратора</label>
                    <input type="text" id="adm_modal_edit_id" readonly disabled class="readonly-field">
                </div>

                <!-- Логин (редактируемый) -->
                <div class="form-group">
                    <label>Логин администратора</label>
                    <input type="text" value="" id="adm_modal_edit_login" placeholder="Введите логин...">
                </div>

                <!-- Роль (выбор) -->
                <div class="form-group">
                    <label>Роль</label>
                    <select id="adm_modal_edit_role">
                        <option value="operator">Оператор</option>
                        <option value="manager">Менеджер</option>
                        <option value="admin">Администратор</option>
                    </select>
                </div>

                <!-- Статус блокировки с кнопкой -->
                <div class="form-group">
                    <label>Статус аккаунта</label>
                    <div class="status-with-button">
                        <input disabled type="text" value='' id='adm_modal_edit_status'>
                        <button type="button" class="btn-block" id="adm_modal_edit_block_btn" onclick="toggleAdminBlock()">
                            Заблокировать
                        </button>
                    </div>
                </div>


                <!-- 2FA секция -->
                <div class="form-group">
                    <label>Двухфакторная авторизация</label>
                    <div class="status-with-button">
                        <input disabled type="text" value='' id='adm_modal_edit_2fa_status'>
                        <button type="button" class="btn-reset-2fa" id="adm_modal_edit_reset_2fa" onclick="resetAdmin2FA()">
                            Сбросить 2FA
                        </button>
                    </div>
                    <p class="form-hint">После сброса администратору потребуется настроить 2FA заново при следующем входе.</p>
                </div>

                <!-- Разделитель -->
                <div class="form-divider"></div>

                <!-- Информационные поля (только чтение) -->
                <div class="info-section">
                    <h3>Информация об аккаунте</h3>

                    <div class="form-group">
                        <label>Дата создания учетной записи</label>
                        <div class="status-with-button">
                            <input type="text" id="adm_modal_edit_created_at" readonly disabled class="readonly-field">
                            <button type="button" class="btn-block" id="adm_modal_edit_remove_account" onclick="removeAdminAccount()">
                                Удалить аккаунт
                            </button>
                        </div>
                        <p class="form-hint">После удаления аккаунта, он будет недоступен для входа и редактирования.</p>
                    </div>

                    <div class="form-group">
                        <label>Дата последнего обновления пароля</label>
                        <div class="status-with-button">
                            <input type="text" id="adm_modal_edit_password_updated" readonly disabled class="readonly-field">
                            <button type="button" class="btn-reset-2fa" id="adm_modal_edit_reset_password" onclick="resetAdminPassword()">
                                Сбросить пароль
                            </button>
                        </div>
                        <p class="form-hint">После сброса пароля, будет сгенерирован новый пароль. Передайте его администратору.</p>
                    </div>

                    <div class="form-group">
                        <label>Дата последнего входа</label>
                        <input type="text" id="adm_modal_edit_last_login" readonly disabled class="readonly-field">
                    </div>

                    <div class="form-group">
                        <label>Последний IP входа</label>
                        <input type="text" id="adm_modal_edit_last_ip" readonly disabled class="readonly-field">
                    </div>
                </div>
                <!-- Кнопки действий -->
                <div class="form-actions">
                    <button type="button" class="modal-submit" id="adm_modal_edit_save" onclick="adminSaveEdit()">Сохранить изменения</button>
                </div>
            </form>
        </div>
    </div>
</div>