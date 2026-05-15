<?php
require_once __DIR__.'/../../vendor/autoload.php';

use managers\AdminsManager;
use managers\GoodsManager;
use managers\ServicesManager;
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
function not_authorized(): never {
    setcookie('session_id', '', time() - 3600, '/', '', false);
    setcookie('login', '', time() - 3600, '/', '', false);
    header('Location: ../auth.php');
    exit;
}
$role = $adminsManager->getRole($login);
$vehiclesManager = new VehiclesManager();
$goodsManager = new GoodsManager();
$servicesManager = new ServicesManager();

$transportList = $vehiclesManager->getAllForSelect();
$goodsList = $goodsManager->getAllForSelect();
$servicesList = $servicesManager->getAllForSelect();
?>
<div class="page-settings">
    <?php if ($role == 'manager' || $role == 'admin') : ?>
    <div class="settings-tabs">
        <button class="tab-btn active" data-tab="account">Аккаунт</button>
        <button class="tab-btn" data-tab="contacts">Контакты</button>
        <button class="tab-btn" data-tab="presets">Пресеты</button>
    </div>
    <?php endif; ?>

    <div class="settings-content">
        <!-- ==================== ВКЛАДКА АККАУНТ ==================== -->
        <div class="tab-pane active" id="tab-account">
            <div class="settings-card">
                <h3>Информация об аккаунте</h3>
                <div class="account-info">
                    <div class="info-row">
                        <span class="info-label">Логин:</span>
                        <span class="info-value" id="account_login">Загрузка...</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Роль:</span>
                        <span class="info-value" id="account_role">Загрузка...</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Статус:</span>
                        <span class="info-value" id="account_status">Загрузка...</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Последний вход:</span>
                        <span class="info-value" id="account_last_login">—</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Последнее обновление пароля:</span>
                        <span class="info-value" id="account_password_updated">—</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Активные сессии:</span>
                        <span class="info-value" id="account_sessions_count">Загрузка...</span>
                        <button class="btn-cancel btn-danger" id="killAllSessionsBtn">Завершить все сессии</button>
                    </div>
                </div>
                <div class="account-actions">
                    <button class="btn btn-reset-2fa" id="reset2FABtn">Сбросить 2FA</button>
                    <button class="btn btn-primary" id="changePasswordBtn">Сменить пароль</button>
                </div>
            </div>
        </div>

        <?php
        if ($role == 'manager' || $role == 'admin'):
            $config = new \lib\Config()->getConfig();
        ?>

        <!-- ==================== ВКЛАДКА КОНТАКТЫ ==================== -->
        <div class="tab-pane" id="tab-contacts">
            <div class="settings-card">
                <h3>Контактная информация агентства</h3>
                <div class="settings-form" id="contactsForm">
                    <div class="form-group">
                        <label>Телефон</label>
                        <input type="text" id="contact_phone" value="<?php echo $config['phone_stock']; ?>">
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" id="contact_email" value="<?php echo $config['email']; ?>">
                    </div>
                    <div class="form-group">
                        <label>Адрес</label>
                        <input type="text" id="contact_address" value="<?php echo $config['address']; ?>">
                    </div>
                    <div class="form-group">
                        <label>График работы</label>
                        <input type="text" id="contact_schedule" value="<?php echo $config['schedule']; ?>">
                    </div>
                    <div class="form-group">
                        <label>Telegram</label>
                        <input type="text" id="contact_telegram" value="<?php echo $config['telegram']; ?>">
                    </div>
                    <div class="form-group">
                        <label>VK</label>
                        <input type="text" id="contact_vk" value="<?php echo $config['vk']; ?>">
                    </div>
                    <div class="form-group">
                        <label>MAX</label>
                        <input type="text" id="contact_max" value="<?php echo $config['max']; ?>">
                    </div>
                </div>
            </div>
            <div class="settings-actions">
                <button class="btn-save" id="saveContactsBtn">Сохранить изменения</button>
                <button class="btn-cancel" id="cancelContactsBtn">Отмена</button>
            </div>
        </div>

        <!-- ==================== ВКЛАДКА ПРЕСЕТЫ ==================== -->
        <div class="tab-pane" id="tab-presets">
            <div class="presets-header">
                <button class="btn-add" id="addPresetBtn">+ Добавить пресет</button>
            </div>
            <div class="presets-grid" id="presetsGrid">
                <!-- Карточки будут загружаться через JS -->
            </div>
        </div>

        <?php endif; ?>
    </div>
</div>

<!-- Модальное окно смены пароля -->
<div class="modal-overlay" id="passwordModal">
    <div class="modal-container">
        <div class="modal-header">
            <h2>Смена пароля</h2>
            <button class="modal-close" onclick="closePasswordModal()">&times;</button>
        </div>
        <div class="modal-content">
            <form id="passwordForm">
                <div class="form-group">
                    <label>Текущий пароль</label>
                    <input type="text" id="old_password" required>
                </div>
                <div class="form-group">
                    <label>Новый пароль</label>
                    <input type="text" id="new_password" required>
                </div>
                <div class="form-group">
                    <label>Подтверждение пароля</label>
                    <input type="text" id="confirm_password" required>
                </div>
                <button type="submit" class="modal-submit">Сменить пароль</button>
            </form>
        </div>
    </div>
</div>
<?php if ($role == 'manager' || $role == 'admin'): ?>
<!-- Модальное окно редактирования пресета -->
<div class="modal-overlay" id="presetModal">
    <div class="modal-container">
        <div class="modal-header">
            <h2 id="presetModalTitle">Редактирование пресета</h2>
            <button class="modal-close" onclick="closePresetModal()">&times;</button>
        </div>
        <div class="modal-content">
            <form id="presetForm">
                <div class="form-group">
                    <label>Название пресета</label>
                    <input type="text" id="preset_name" required>
                </div>
                <div class="form-group">
                    <label>Цитата</label>
                    <input type="text" id="preset_quote" placeholder="Краткое описание">
                </div>
                <div class="form-group">
                    <label>Стиль оформления</label>
                    <select id="preset_decoration">
                        <option value="default">Обычный</option>
                        <option value="popular">Популярный</option>
                        <option value="vip">VIP</option>
                        <option value="primary">Основной</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Текст стиля (опционально)</label>
                    <input type="text" id="preset_decoration_quote" placeholder="Например: ВЫБОР 80% СЕМЕЙ">
                </div>
                <div class="form-group">
                    <label>Транспорт в наборе</label>
                    <div class="multiselect-container" id="transportMultiselect">
                        <?php if (!empty($transportList)): ?>
                            <?php foreach ($transportList as $item): ?>
                                <div class="multiselect-item">
                                    <input type="checkbox" value="<?= $item['id'] ?>" data-name="<?= htmlspecialchars($item['name']) ?>" data-price="<?= $item['price'] ?>">
                                    <label><?= htmlspecialchars($item['name']) ?></label>
                                    <span class="item-price"><?= number_format($item['price'], 0, ',', ' ') ?> ₽</span>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="multiselect-item">Нет данных</div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="form-group">
                    <label>Товары в наборе</label>
                    <div class="multiselect-container" id="goodsMultiselect">
                        <?php if (!empty($goodsList)): ?>
                            <?php foreach ($goodsList as $item): ?>
                                <div class="multiselect-item">
                                    <input type="checkbox" value="<?= $item['id'] ?>" data-name="<?= htmlspecialchars($item['name']) ?>" data-price="<?= $item['price'] ?>">
                                    <label><?= htmlspecialchars($item['name']) ?></label>
                                    <span class="item-price"><?= number_format($item['price'], 0, ',', ' ') ?> ₽</span>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="multiselect-item">Нет данных</div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="form-group">
                    <label>Услуги в наборе</label>
                    <div class="multiselect-container" id="servicesMultiselect">
                        <?php if (!empty($servicesList)): ?>
                            <?php foreach ($servicesList as $item): ?>
                                <div class="multiselect-item">
                                    <input type="checkbox" value="<?= $item['id'] ?>" data-name="<?= htmlspecialchars($item['name']) ?>" data-price="<?= $item['price'] ?>">
                                    <label><?= htmlspecialchars($item['name']) ?></label>
                                    <span class="item-price"><?= number_format($item['price'], 0, ',', ' ') ?> ₽</span>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="multiselect-item">Нет данных</div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="form-group">
                    <label>Статус</label>
                    <select id="preset_is_active">
                        <option value="1">Активен</option>
                        <option value="0">Неактивен</option>
                    </select>
                </div>
                <button type="submit" class="modal-submit">Сохранить</button>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>