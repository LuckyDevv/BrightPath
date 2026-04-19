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
        <button class="btn-add">+ Добавить администратора</button>
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
        <tbody>
        <tr>
            <td>1</td>
            <td>admin</td>
            <td><span class="role-badge admin">Администратор</span></td>
            <td>22.03.2026 14:30</td>
            <td>01.01.2026</td>
            <td><span class="status active">Активен</span></td>
            <td>
                <button class="btn-icon edit">✏️</button>
                <button class="btn-icon lock">🔒</button>
                <button class="btn-icon delete">🗑️</button>
            </td>
        </tr>
        <tr>
            <td>2</td>
            <td>manager_ivanov</td>
            <td><span class="role-badge manager">Менеджер</span></td>
            <td>22.03.2026 09:15</td>
            <td>15.02.2026</td>
            <td><span class="status active">Активен</span></td>
            <td>
                <button class="btn-icon edit">✏️</button>
                <button class="btn-icon lock">🔒</button>
                <button class="btn-icon delete">🗑️</button>
            </td>
        </tr>
        <tr>
            <td>3</td>
            <td>operator_petrova</td>
            <td><span class="role-badge operator">Оператор</span></td>
            <td>21.03.2026 16:45</td>
            <td>01.03.2026</td>
            <td><span class="status inactive">Заблокирован</span></td>
            <td>
                <button class="btn-icon edit">✏️</button>
                <button class="btn-icon unlock">🔓</button>
                <button class="btn-icon delete">🗑️</button>
            </td>
        </tr>
        </tbody>
    </table>
</div>