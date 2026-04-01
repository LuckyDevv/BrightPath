<div class="page-goods">
    <div class="page-header-actions">
        <div class="search-bar">
            <input type="text" placeholder="Поиск по названию...">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <circle cx="11" cy="11" r="8" stroke-width="2"/>
                <line x1="21" y1="21" x2="16.65" y2="16.65" stroke-width="2"/>
            </svg>
        </div>
        <div class="filters">
            <select data-filter="category">
                <option value="all">Все категории</option>
                <option value="гробы">Гробы</option>
                <option value="венки">Венки</option>
                <option value="кресты">Кресты</option>
                <option value="памятники">Памятники</option>
                <option value="одежда">Одежда</option>
                <option value="аксессуары">Аксессуары</option>
            </select>
            <select data-filter="material">
                <option value="all">Все материалы</option>
                <option value="сосна">Сосна</option>
                <option value="дуб">Дуб</option>
                <option value="красное дерево">Красное дерево</option>
                <option value="гранит">Гранит</option>
                <option value="мрамор">Мрамор</option>
                <option value="ткань">Ткань</option>
                <option value="искусственные цветы">Искусственные цветы</option>
            </select>
            <select data-filter="status">
                <option value="all">Все статусы</option>
                <option value="активен">Активен</option>
                <option value="неактивен">Неактивен</option>
            </select>
        </div>
        <button class="btn-add">+ Добавить товар</button>
    </div>

    <table class="data-table">
        <thead>
        <tr>
            <th>ID</th>
            <th>Фото</th>
            <th>Название</th>
            <th>Категория</th>
            <th>Материал</th>
            <th>Цена</th>
            <th>На складе</th>
            <th>Статус</th>
            <th>Действия</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>1</td>
            <td><img src="../src/images/goods/grob-sosna.jpg" class="table-thumb" onerror="this.src='../src/images/placeholder.jpg'"></td>
            <td>Гроб сосновый</td>
            <td>Гробы</td>
            <td>Сосна</td>
            <td>8 900 ₽</td>
            <td>25</td>
            <td><span class="status active">Активен</span></td>
            <td>
                <button class="btn-icon edit">✏️</button>
                <button class="btn-icon delete">🗑️</button>
            </td>
        </tr>
        <tr>
            <td>2</td>
            <td><img src="../src/images/goods/grob-dub.jpg" class="table-thumb" onerror="this.src='../src/images/placeholder.jpg'"></td>
            <td>Гроб дубовый</td>
            <td>Гробы</td>
            <td>Дуб</td>
            <td>35 000 ₽</td>
            <td>8</td>
            <td><span class="status active">Активен</span></td>
            <td>
                <button class="btn-icon edit">✏️</button>
                <button class="btn-icon delete">🗑️</button>
            </td>
        </tr>
        <tr>
            <td>3</td>
            <td><img src="../src/images/goods/venok.jpg" class="table-thumb" onerror="this.src='../src/images/placeholder.jpg'"></td>
            <td>Венок траурный</td>
            <td>Венки</td>
            <td>Искусственные цветы</td>
            <td>3 500 ₽</td>
            <td>45</td>
            <td><span class="status active">Активен</span></td>
            <td>
                <button class="btn-icon edit">✏️</button>
                <button class="btn-icon delete">🗑️</button>
            </td>
        </tr>
        <tr>
            <td>4</td>
            <td><img src="../src/images/goods/pamyatnik-granit.jpg" class="table-thumb" onerror="this.src='../src/images/placeholder.jpg'"></td>
            <td>Памятник гранит</td>
            <td>Памятники</td>
            <td>Гранит</td>
            <td>45 000 ₽</td>
            <td>12</td>
            <td><span class="status active">Активен</span></td>
            <td>
                <button class="btn-icon edit">✏️</button>
                <button class="btn-icon delete">🗑️</button>
            </td>
        </tr>
        </tbody>
    </table>
</div>