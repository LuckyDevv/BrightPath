<div class="page-agents">
    <div class="page-header-actions">
        <div class="search-bar">
            <input type="text" placeholder="Поиск по имени, должности...">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <circle cx="11" cy="11" r="8" stroke-width="2"/>
                <line x1="21" y1="21" x2="16.65" y2="16.65" stroke-width="2"/>
            </svg>
        </div>
        <div class="filters">
            <select data-filter="position">
                <option value="all">Все должности</option>
                <option value="руководитель">Руководитель</option>
                <option value="ведущий агент">Ведущий агент</option>
                <option value="старший агент">Старший агент</option>
                <option value="агент">Агент</option>
                <option value="менеджер">Менеджер</option>
            </select>
            <select data-filter="status">
                <option value="all">Все статусы</option>
                <option value="активен">Активен</option>
                <option value="неактивен">Неактивен</option>
            </select>
        </div>
        <button class="btn-add">+ Добавить агента</button>
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
            <th>Цена</th>
            <th>Статус</th>
            <th>Действия</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>1</td>
            <td><img src="../src/images/agents/agent-1.jpg" class="table-thumb" onerror="this.src='../src/images/placeholder.jpg'"></td>
            <td>Александр Шидловский</td>
            <td>Руководитель</td>
            <td>52</td>
            <td>23 года</td>
            <td>1 800</td>
            <td>5 000 ₽</td>
            <td><span class="status active">Активен</span></td>
            <td>
                <button class="btn-icon edit">✏️</button>
                <button class="btn-icon delete">🗑️</button>
            </td>
        </tr>
        <tr>
            <td>2</td>
            <td><img src="../src/images/agents/agent-2.jpg" class="table-thumb" onerror="this.src='../src/images/placeholder.jpg'"></td>
            <td>Елена Воронцова</td>
            <td>Ведущий агент</td>
            <td>45</td>
            <td>18 лет</td>
            <td>1 200</td>
            <td>4 000 ₽</td>
            <td><span class="status active">Активен</span></td>
            <td>
                <button class="btn-icon edit">✏️</button>
                <button class="btn-icon delete">🗑️</button>
            </td>
        </tr>
        <tr>
            <td>3</td>
            <td><img src="../src/images/agents/agent-3.jpg" class="table-thumb" onerror="this.src='../src/images/placeholder.jpg'"></td>
            <td>Дмитрий Волков</td>
            <td>Старший агент</td>
            <td>38</td>
            <td>12 лет</td>
            <td>850</td>
            <td>3 500 ₽</td>
            <td><span class="status active">Активен</span></td>
            <td>
                <button class="btn-icon edit">✏️</button>
                <button class="btn-icon delete">🗑️</button>
            </td>
        </tr>
        <tr>
            <td>4</td>
            <td><img src="../src/images/agents/agent-4.jpg" class="table-thumb" onerror="this.src='../src/images/placeholder.jpg'"></td>
            <td>Ольга Смирнова</td>
            <td>Агент</td>
            <td>35</td>
            <td>8 лет</td>
            <td>600</td>
            <td>3 000 ₽</td>
            <td><span class="status active">Активен</span></td>
            <td>
                <button class="btn-icon edit">✏️</button>
                <button class="btn-icon delete">🗑️</button>
            </td>
        </tr>
        </tbody>
    </table>
</div>