<div class="page-clients">
    <div class="page-header-actions">
        <div class="search-bar">
            <input type="text" placeholder="Поиск по имени, телефону, email...">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <circle cx="11" cy="11" r="8" stroke-width="2"/>
                <line x1="21" y1="21" x2="16.65" y2="16.65" stroke-width="2"/>
            </svg>
        </div>
        <div class="filters">
            <select data-filter="clientType">
                <option value="all">Все клиенты</option>
                <option value="постоянный">Постоянные</option>
                <option value="новый">Новые (30 дней)</option>
                <option value="vip">VIP</option>
            </select>
            <select data-filter="status">
                <option value="all">Все статусы</option>
                <option value="с заказами">С заказами</option>
                <option value="без заказов">Без заказов</option>
            </select>
        </div>
        <button class="btn-add">+ Добавить клиента</button>
    </div>

    <table class="data-table">
        <thead>
        <tr>
            <th>ID</th>
            <th>Клиент</th>
            <th>Телефон</th>
            <th>Email</th>
            <th>Заказов</th>
            <th>Сумма</th>
            <th>Дата регистрации</th>
            <th>Действия</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>#101</td>
            <td>
                <div class="client-info">
                    <span class="client-name">Иванов Иван Иванович</span>
                    <span class="client-badge regular">Постоянный</span>
                </div>
            </td>
            <td>+7 (999) 123-45-67</td>
            <td>ivanov@mail.ru</td>
            <td>3</td>
            <td>98 500 ₽</td>
            <td>15.01.2026</td>
            <td>
                <button class="btn-icon view">👁️</button>
                <button class="btn-icon edit">✏️</button>
            </td>
        </tr>
        <tr>
            <td>#102</td>
            <td>
                <div class="client-info">
                    <span class="client-name">Петрова Анна Сергеевна</span>
                    <span class="client-badge new">Новый</span>
                </div>
            </td>
            <td>+7 (999) 234-56-78</td>
            <td>petrova@mail.ru</td>
            <td>1</td>
            <td>32 500 ₽</td>
            <td>10.03.2026</td>
            <td>
                <button class="btn-icon view">👁️</button>
                <button class="btn-icon edit">✏️</button>
            </td>
        </tr>
        <tr>
            <td>#103</td>
            <td>
                <div class="client-info">
                    <span class="client-name">Сидоров Владимир Владимирович</span>
                    <span class="client-badge vip">VIP</span>
                </div>
            </td>
            <td>+7 (999) 345-67-89</td>
            <td>sidorov@mail.ru</td>
            <td>5</td>
            <td>215 000 ₽</td>
            <td>20.12.2025</td>
            <td>
                <button class="btn-icon view">👁️</button>
                <button class="btn-icon edit">✏️</button>
            </td>
        </tr>
        </tbody>
    </table>

    <div class="pagination">
        <button class="page-prev">← Назад</button>
        <span class="page-info">Страница 1 из 3</span>
        <button class="page-next">Вперёд →</button>
    </div>
</div>