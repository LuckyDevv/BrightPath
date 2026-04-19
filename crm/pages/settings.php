<div class="page-settings">
    <div class="settings-tabs">
        <button class="tab-btn active" data-tab="general">Общие настройки</button>
        <button class="tab-btn" data-tab="contacts">Контакты</button>
        <button class="tab-btn" data-tab="payments">Выплаты</button>
        <button class="tab-btn" data-tab="tariffs">Тарифы</button>
    </div>

    <div class="settings-content">
        <!-- Общие настройки -->
        <div class="tab-pane active" id="tab-general">
            <div class="settings-card">
                <h3>Основная информация</h3>
                <div class="settings-form">
                    <div class="form-group">
                        <label>Название сайта</label>
                        <input type="text" value="Светлый Путь">
                    </div>
                    <div class="form-group">
                        <label>Логотип</label>
                        <div class="file-upload">
                            <input type="file" accept="image/*">
                            <button class="btn-outline">Загрузить</button>
                        </div>
                        <div class="current-logo">
                            <img src="../logo5.png" alt="Текущий логотип">
                            <span>logo5.png</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Год основания</label>
                        <input type="text" value="2002">
                    </div>
                    <div class="form-group">
                        <label>Слоган</label>
                        <textarea rows="2">Ритуальное агентство в Одинцово. Работаем с 2002 года.</textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Контакты -->
        <div class="tab-pane" id="tab-contacts">
            <div class="settings-card">
                <h3>Контактная информация</h3>
                <div class="settings-form">
                    <div class="form-group">
                        <label>Телефон</label>
                        <input type="text" value="+7 (987) 654-32-10">
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" value="info@svetlyput.ru">
                    </div>
                    <div class="form-group">
                        <label>Адрес</label>
                        <input type="text" value="г. Одинцово, ул. Глазынинская, д 18">
                    </div>
                    <div class="form-group">
                        <label>График работы</label>
                        <input type="text" value="Круглосуточно, без выходных">
                    </div>
                </div>
            </div>

            <div class="settings-card">
                <h3>Социальные сети</h3>
                <div class="settings-form">
                    <div class="form-group">
                        <label>Telegram</label>
                        <input type="text" value="https://t.me/luckydevv">
                    </div>
                    <div class="form-group">
                        <label>VK</label>
                        <input type="text" value="https://vk.com/luckydevv">
                    </div>
                </div>
            </div>
        </div>

        <!-- Выплаты -->
        <div class="tab-pane" id="tab-payments">
            <div class="settings-card">
                <h3>Пособие на погребение</h3>
                <div class="settings-form">
                    <div class="form-group">
                        <label>Базовый размер (РФ)</label>
                        <input type="text" value="9 678 ₽">
                    </div>
                    <div class="form-group">
                        <label>Москва (доплата)</label>
                        <input type="text" value="до 17 000 ₽">
                    </div>
                    <div class="form-group">
                        <label>Одинцово (доплата)</label>
                        <input type="text" value="12 500 ₽">
                    </div>
                    <div class="form-group">
                        <label>Московская область</label>
                        <input type="text" value="13 000 – 15 000 ₽">
                    </div>
                </div>
            </div>
        </div>

        <!-- Тарифы -->
        <div class="tab-pane" id="tab-tariffs">
            <div class="settings-card">
                <h3>Тарифы на услуги</h3>
                <div class="settings-form">
                    <div class="form-group">
                        <label>Эконом (от)</label>
                        <input type="text" value="18 900 ₽">
                    </div>
                    <div class="form-group">
                        <label>Стандарт (от)</label>
                        <input type="text" value="39 900 ₽">
                    </div>
                    <div class="form-group">
                        <label>Премиум (от)</label>
                        <input type="text" value="69 900 ₽">
                    </div>
                    <div class="form-group">
                        <label>VIP (от)</label>
                        <input type="text" value="129 900 ₽">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="settings-actions">
        <button class="btn-save">Сохранить изменения</button>
        <button class="btn-cancel">Отмена</button>
    </div>
</div>