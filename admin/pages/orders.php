<div class="page-orders">
    <div class="page-header-actions">
        <div class="search-bar">
            <input type="text" placeholder="Поиск по номеру заказа, клиенту...">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <circle cx="11" cy="11" r="8" stroke-width="2"/>
                <line x1="21" y1="21" x2="16.65" y2="16.65" stroke-width="2"/>
            </svg>
        </div>
        <div class="filters">
            <select data-filter="status">
                <option value="all">Все статусы</option>
                <option value="новый">Новый</option>
                <option value="в работе">В работе</option>
                <option value="выполнен">Выполнен</option>
                <option value="отменён">Отменён</option>
            </select>
            <select data-filter="date">
                <option value="all">Все даты</option>
                <option value="сегодня">Сегодня</option>
                <option value="эта неделя">Эта неделя</option>
                <option value="этот месяц">Этот месяц</option>
            </select>
        </div>
        <button class="btn-export">📊 Экспорт</button>
    </div>

    <table class="data-table">
        <thead>
        <tr>
            <th>ID</th>
            <th>Клиент</th>
            <th>Телефон</th>
            <th>Сумма</th>
            <th>Дата</th>
            <th>Статус</th>
            <th>Действия</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>#1245</td>
            <td>Иванов Иван Иванович</td>
            <td>+7 (999) 123-45-67</td>
            <td>45 000 ₽</td>
            <td>22.03.2026</td>
            <td><span class="status completed">Выполнен</span></td>
            <td><button class="btn-icon view">👁️</button></td>
        </tr>
        <tr>
            <td>#1244</td>
            <td>Петрова Анна Сергеевна</td>
            <td>+7 (999) 234-56-78</td>
            <td>32 500 ₽</td>
            <td>22.03.2026</td>
            <td><span class="status in-progress">В работе</span></td>
            <td><button class="btn-icon view">👁️</button></td>
        </tr>
        <tr>
            <td>#1243</td>
            <td>Сидоров Владимир Владимирович</td>
            <td>+7 (999) 345-67-89</td>
            <td>28 000 ₽</td>
            <td>21.03.2026</td>
            <td><span class="status pending">Новый</span></td>
            <td><button class="btn-icon view">👁️</button></td>
        </tr>
        </tbody>
    </table>
</div>