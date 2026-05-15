// ===== НАВИГАЦИЯ =====
document.querySelectorAll('.nav-item[data-page]').forEach(item => {
    item.addEventListener('click', async (e) => {
        e.preventDefault();

        document.querySelectorAll('.nav-item').forEach(nav => nav.classList.remove('active'));
        item.classList.add('active');

        const page = item.getAttribute('data-page');
        const headerTitle = document.querySelector('.header-title h1');
        const headerSubtitle = document.querySelector('.header-title p');

        const titles = {
            dashboard: ['Дашборд', 'Общая статистика за сегодня'],
            orders: ['Заказы', 'Управление заказами'],
            clients: ['Клиенты', 'База клиентов'],
            vehicles: ['Автопарк', 'Управление автомобилями'],
            goods: ['Товары', 'Каталог ритуальных товаров'],
            agents: ['Агенты', 'Сотрудники агентства'],
            admins: ['Администраторы', 'Управление доступом'],
            services: ['Ритуальные услуги', 'Каталог ритуальных услуг'],
            requests: ['Заявки на консультацию', 'Список заявок клиентов, которым нужна консультация'],
            settings: ['Настройки', 'Параметры сайта']
        };

        headerTitle.textContent = titles[page]?.[0] || 'Страница';
        headerSubtitle.textContent = titles[page]?.[1] || '';

        try {
            const response = await fetch(`pages/${page}.php`);
            document.getElementById('contentWrapper').innerHTML = await response.text();
            initPageScripts();
            switch (page) {
                case "vehicles":
                    initVehicles();
                    break;
                case "agents":
                    initAgents();
                    break;
                case "services":
                    initServices();
                    break;
                case "orders":
                    initOrders();
                    break;
                case "goods":
                    initGoods();
                    break;
                case "settings":
                    initSettings();
                    if (admin_role === 'manager' || admin_role === 'admin') {
                        initMoreSettings();
                    }
                    break;
            }
        } catch (error) {
            console.error('Ошибка загрузки:', error);
            document.getElementById('contentWrapper').innerHTML = '<p style="padding: 40px; text-align: center;">Ошибка загрузки страницы</p>';
        }
    });
});

const getFormattedDate = (date) => {
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0'); // Месяцы считаются с 0 (январь — 0), поэтому добавляем 1
    const day = String(date.getDate()).padStart(2, '0');
    const hour = String(date.getHours() + 1).padStart(2, '0');
    const minutes = String(date.getMinutes() + 1).padStart(2, '0');
    const seconds = String(date.getSeconds() + 1).padStart(2, '0');
    return `${year}-${month}-${day} ${hour}:${minutes}:${seconds}`;
};

// ===== ИНИЦИАЛИЗАЦИЯ СКРИПТОВ ДЛЯ СТРАНИЦЫ =====
function initPageScripts() {
    //initModals();
    //initButtons();
    //initForms();
    initFilters();
    //initPagination();
    //initTabs();
}

// ===== ФИЛЬТРЫ (РАБОЧИЕ) =====
function initFilters() {
    const filters = document.querySelectorAll('.filters select');
    const searchInput = document.querySelector('.search-bar input');

    function applyFilters() {
        const table = document.querySelector('.data-table');
        if (!table) return;

        const rows = table.querySelectorAll('tbody tr');
        const filterValues = {};

        // Собираем значения фильтров
        filters.forEach(filter => {
            const filterName = filter.getAttribute('data-filter') || filter.id;
            filterValues[filterName] = filter.value;
        });

        const searchTerm = searchInput?.value.toLowerCase().trim() || '';

        rows.forEach(row => {
            let showRow = true;

            // Фильтр по категории (для товаров и авто)
            if (showRow && filterValues.category && filterValues.category !== 'all') {
                const categoryCell = row.cells[3]; // Категория в 4-й колонке
                if (categoryCell) {
                    const categoryText = categoryCell.textContent.trim().toLowerCase();
                    if (!categoryText.includes(filterValues.category.toLowerCase())) {
                        showRow = false;
                    }
                }
            }
            // Фильтр по категории (для услуг)
            if (showRow && filterValues.category_service && filterValues.category_service !== 'all') {
                const categoryCell = row.cells[2]; // Категория в 4-й колонке
                if (categoryCell) {
                    const categoryText = categoryCell.textContent.trim().toLowerCase();
                    if (!categoryText.includes(filterValues.category_service.toLowerCase())) {
                        showRow = false;
                    }
                }
            }

            // Фильтр по материалу (для товаров)
            if (showRow && filterValues.material && filterValues.material !== 'all') {
                const materialCell = row.cells[4]; // Материал в 5-й колонке
                if (materialCell) {
                    const materialText = materialCell.textContent.trim().toLowerCase();
                    if (!materialText.includes(filterValues.material.toLowerCase())) {
                        showRow = false;
                    }
                }
            }

            // Фильтр по должности (для агентов)
            if (showRow && filterValues.position && filterValues.position !== 'all') {
                const positionCell = row.cells[3]; // Должность в 4-й колонке
                if (positionCell) {
                    const positionText = positionCell.textContent.trim().toLowerCase();
                    console.log("Position text: "+positionText);
                    console.log("Filter position text: "+filterValues.position.toLowerCase());
                    console.log("If: "+positionText === (filterValues.position.toLowerCase()));
                    if (positionText !== (filterValues.position.toLowerCase())) {
                        showRow = false;
                    }
                }
            }

            // Фильтр по роли (для админов)
            if (showRow && filterValues.role && filterValues.role !== 'all') {
                const roleCell = row.cells[2]; // Роль в 3-й колонке
                if (roleCell) {
                    const roleText = roleCell.textContent.trim().toLowerCase();
                    if (!roleText.includes(filterValues.role.toLowerCase())) {
                        showRow = false;
                    }
                }
            }

            // Фильтр по статусу (универсальный)
            if (showRow && filterValues.status && filterValues.status !== 'all') {
                const statusIndex = getColumnIndex('Статус');
                if (statusIndex !== -1) {
                    const statusCell = row.cells[statusIndex];
                    if (statusCell) {
                        const statusSpan = statusCell.querySelector('.status');
                        const statusText = statusSpan ? statusSpan.textContent.trim().toLowerCase() : statusCell.textContent.trim().toLowerCase();
                        if (!statusText.includes(filterValues.status.toLowerCase())) {
                            showRow = false;
                        }
                    }
                }
            }

            // Фильтр по типу клиента
            if (showRow && filterValues.clientType && filterValues.clientType !== 'all') {
                const clientInfoCell = row.cells[1];
                if (clientInfoCell) {
                    const badge = clientInfoCell.querySelector('.client-badge');
                    if (badge) {
                        const badgeText = badge.textContent.trim().toLowerCase();
                        if (!badgeText.includes(filterValues.clientType.toLowerCase())) {
                            showRow = false;
                        }
                    } else if (filterValues.clientType !== 'all') {
                        showRow = false;
                    }
                }
            }

            // Фильтр по дате (для заказов)
            if (showRow && filterValues.date && filterValues.date !== 'all') {
                const dateIndex = getColumnIndex('Дата');
                if (dateIndex !== -1) {
                    const dateCell = row.cells[dateIndex];
                    if (dateCell) {
                        const dateText = dateCell.textContent.trim();
                        const rowDate = parseDate(dateText);
                        const today = new Date();

                        switch(filterValues.date) {
                            case 'сегодня':
                                if (!isSameDay(rowDate, today)) showRow = false;
                                break;
                            case 'эта неделя':
                                const weekAgo = new Date();
                                weekAgo.setDate(today.getDate() - 7);
                                if (rowDate < weekAgo) showRow = false;
                                break;
                            case 'этот месяц':
                                if (rowDate.getMonth() !== today.getMonth() || rowDate.getFullYear() !== today.getFullYear()) showRow = false;
                                break;
                        }
                    }
                }
            }

            // ПОИСК (по всем ячейкам)
            if (showRow && searchTerm) {
                let rowText = '';
                for (let i = 0; i < row.cells.length; i++) {
                    rowText += row.cells[i].textContent.toLowerCase() + ' ';
                }
                if (!rowText.includes(searchTerm)) {
                    showRow = false;
                }
            }

            row.style.display = showRow ? '' : 'none';
        });

        // Показываем сообщение "Нет данных"
        const visibleRows = Array.from(rows).filter(row => row.style.display !== 'none');
        const noDataMsg = document.querySelector('.no-data-message');

        if (visibleRows.length === 0) {
            if (!noDataMsg) {
                const tbody = table.querySelector('tbody');
                const msg = document.createElement('tr');
                msg.className = 'no-data-message';
                msg.innerHTML = `<td colspan="100" style="text-align: center; padding: 40px; color: #999;">Нет данных, соответствующих фильтрам</td>`;
                tbody.appendChild(msg);
            }
        } else if (noDataMsg) {
            noDataMsg.remove();
        }
    }

    // Функция для получения индекса колонки по названию
    function getColumnIndex(columnName) {
        const headers = document.querySelectorAll('.data-table thead th');
        for (let i = 0; i < headers.length; i++) {
            if (headers[i].textContent.includes(columnName)) {
                return i;
            }
        }
        return -1;
    }

    // Функция для парсинга даты
    function parseDate(dateStr) {
        const parts = dateStr.split('.');
        if (parts.length === 3) {
            return new Date(parts[2], parts[1] - 1, parts[0]);
        }
        return new Date();
    }

    function isSameDay(date1, date2) {
        return date1.getDate() === date2.getDate() &&
            date1.getMonth() === date2.getMonth() &&
            date1.getFullYear() === date2.getFullYear();
    }

    // Добавляем слушатели
    filters.forEach(filter => {
        filter.addEventListener('change', applyFilters);
    });

    if (searchInput) {
        searchInput.addEventListener('input', applyFilters);
    }

    // Применяем при загрузке
    applyFilters();
}

// ===== МОДАЛЬНЫЕ ОКНА =====
function initModals() {
    document.querySelectorAll('.btn-add').forEach(btn => {
        btn.addEventListener('click', () => {
            const page = getCurrentPage();
            openAddModal(page);
        });
    });

    document.querySelectorAll('.btn-icon.edit').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.stopPropagation();
            const row = btn.closest('tr');
            const page = getCurrentPage();
            openEditModal(page, row);
        });
    });

    document.querySelectorAll('.btn-icon.view').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.stopPropagation();
            const row = btn.closest('tr');
            const page = getCurrentPage();
            openViewModal(page, row);
        });
    });

    document.querySelectorAll('.btn-icon.delete').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.stopPropagation();
            const row = btn.closest('tr');
            const page = getCurrentPage();
            confirmDelete(page, row);
        });
    });

    document.querySelectorAll('.btn-icon.lock, .btn-icon.unlock').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.stopPropagation();
            const row = btn.closest('tr');
            const page = getCurrentPage();
            const isLock = btn.classList.contains('lock');
            confirmStatusChange(page, row, isLock);
        });
    });
}

function getCurrentPage() {
    const activeNav = document.querySelector('.nav-item.active');
    return activeNav ? activeNav.getAttribute('data-page') : 'dashboard';
}

function openAddModal(page) {
    const modalHTML = getModalHTML(page, 'add');
    document.body.insertAdjacentHTML('beforeend', modalHTML);
    const modal = document.querySelector('.modal-overlay:last-child');
    modal.classList.add('active');

    modal.querySelector('.modal-submit').addEventListener('click', () => {
        alert('Данные сохранены (демо)');
        modal.remove();
    });
}

function openEditModal(page, row) {
    const modalHTML = getModalHTML(page, 'edit', row);
    document.body.insertAdjacentHTML('beforeend', modalHTML);
    const modal = document.querySelector('.modal-overlay:last-child');
    modal.classList.add('active');

    modal.querySelector('.modal-submit').addEventListener('click', () => {
        alert('Данные обновлены (демо)');
        modal.remove();
    });
}

function openViewModal(page, row) {
    const modalHTML = getModalHTML(page, 'view', row);
    document.body.insertAdjacentHTML('beforeend', modalHTML);
    const modal = document.querySelector('.modal-overlay:last-child');
    modal.classList.add('active');


}

function confirmDelete(page, row) {
    if (confirm('Вы уверены, что хотите удалить эту запись?')) {
        row.remove();
        alert('Запись удалена (демо)');

        // Обновляем фильтры после удаления
        initFilters();
    }
}

function confirmStatusChange(page, row, isLock) {
    const action = isLock ? 'заблокировать' : 'разблокировать';
    if (confirm(`Вы уверены, что хотите ${action} этого пользователя?`)) {
        const statusIndex = getColumnIndexByRow(row, 'Статус');
        if (statusIndex !== -1) {
            const statusCell = row.cells[statusIndex];
            const btn = row.querySelector('.btn-icon');

            if (isLock) {
                statusCell.innerHTML = '<span class="status inactive">Заблокирован</span>';
                if (btn) {
                    btn.textContent = '🔓';
                    btn.classList.remove('lock');
                    btn.classList.add('unlock');
                }
                alert('Пользователь заблокирован (демо)');
            } else {
                statusCell.innerHTML = '<span class="status active">Активен</span>';
                if (btn) {
                    btn.textContent = '🔒';
                    btn.classList.remove('unlock');
                    btn.classList.add('lock');
                }
                alert('Пользователь разблокирован (демо)');
            }
        }
    }
}

function getColumnIndexByRow(row, columnName) {
    const headers = document.querySelectorAll('.data-table thead th');
    for (let i = 0; i < headers.length; i++) {
        if (headers[i].textContent.includes(columnName)) {
            return i;
        }
    }
    return -1;
}

function getModalHTML(page, mode, row = null) {
    const templates = {
        goods: getGoodsModal(mode, row),
        agents: getAgentModal(mode, row),
        clients: getClientModal(mode, row),
        orders: getOrderModal(mode, row),
        admins: getAdminModal(mode, row)
    };

    return templates[page] || getDefaultModal(page, mode);
}

function getGoodsModal(mode, row) {
    const isView = mode === 'view';
    const title = mode === 'add' ? 'Добавление товара' : mode === 'edit' ? 'Редактирование товара' : 'Просмотр товара';
    const name = row ? row.cells[2]?.textContent?.trim() || '' : '';
    const category = row ? row.cells[3]?.textContent?.trim() || 'Гробы' : 'Гробы';
    const material = row ? row.cells[4]?.textContent?.trim() || 'Дуб' : 'Дуб';
    const price = row ? row.cells[5]?.textContent?.trim() || '' : '';

    return `
        <div class="modal-overlay">
            <div class="modal-container">
                <button class="modal-close">&times;</button>
                <div class="modal-content">
                    <h2>${title}</h2>
                    <form class="modal-form">
                        <div class="form-group">
                            <label>Название товара</label>
                            <input type="text" value="${name}" ${isView ? 'disabled' : ''}>
                        </div>
                        <div class="form-group">
                            <label>Категория</label>
                            <select ${isView ? 'disabled' : ''}>
                                <option ${category === 'Гробы' ? 'selected' : ''}>Гробы</option>
                                <option ${category === 'Венки' ? 'selected' : ''}>Венки</option>
                                <option ${category === 'Кресты' ? 'selected' : ''}>Кресты</option>
                                <option ${category === 'Памятники' ? 'selected' : ''}>Памятники</option>
                                <option ${category === 'Одежда' ? 'selected' : ''}>Одежда</option>
                                <option ${category === 'Аксессуары' ? 'selected' : ''}>Аксессуары</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Материал</label>
                            <input type="text" value="${material}" ${isView ? 'disabled' : ''}>
                        </div>
                        <div class="form-group">
                            <label>Цена</label>
                            <input type="text" value="${price}" ${isView ? 'disabled' : ''}>
                        </div>
                        <div class="form-group">
                            <label>На складе</label>
                            <input type="number" value="25" ${isView ? 'disabled' : ''}>
                        </div>
                        <div class="form-group">
                            <label>Описание</label>
                            <textarea rows="4" ${isView ? 'disabled' : ''}>Классический гроб из массива сосны...</textarea>
                        </div>
                        ${!isView ? '<button type="button" class="modal-submit">Сохранить</button>' : ''}
                    </form>
                </div>
            </div>
        </div>
    `;
}

function getAgentModal(mode, row) {
    const isView = mode === 'view';
    const title = mode === 'add' ? 'Добавление агента' : mode === 'edit' ? 'Редактирование агента' : 'Просмотр агента';
    const name = row ? row.cells[2]?.textContent?.trim() || '' : '';
    const position = row ? row.cells[3]?.textContent?.trim() || '' : '';

    return `
        <div class="modal-overlay">
            <div class="modal-container">
                <button class="modal-close">&times;</button>
                <div class="modal-content">
                    <h2>${title}</h2>
                    <form class="modal-form">
                        <div class="form-group">
                            <label>ФИО</label>
                            <input type="text" value="${name}" ${isView ? 'disabled' : ''}>
                        </div>
                        <div class="form-group">
                            <label>Должность</label>
                            <input type="text" value="${position}" ${isView ? 'disabled' : ''}>
                        </div>
                        <div class="form-group">
                            <label>Возраст</label>
                            <input type="number" value="35" ${isView ? 'disabled' : ''}>
                        </div>
                        <div class="form-group">
                            <label>Стаж (лет)</label>
                            <input type="number" value="10" ${isView ? 'disabled' : ''}>
                        </div>
                        <div class="form-group">
                            <label>Телефон</label>
                            <input type="text" value="+7 (999) 123-45-67" ${isView ? 'disabled' : ''}>
                        </div>
                        <div class="form-group">
                            <label>Цена за услугу</label>
                            <input type="text" value="5 000 ₽" ${isView ? 'disabled' : ''}>
                        </div>
                        <div class="form-group">
                            <label>Описание</label>
                            <textarea rows="4" ${isView ? 'disabled' : ''}>Опытный агент...</textarea>
                        </div>
                        ${!isView ? '<button type="button" class="modal-submit">Сохранить</button>' : ''}
                    </form>
                </div>
            </div>
        </div>
    `;
}

function getClientModal(mode, row) {
    const isView = mode === 'view';
    const title = mode === 'add' ? 'Добавление клиента' : mode === 'edit' ? 'Редактирование клиента' : 'Просмотр клиента';
    const name = row ? row.cells[1]?.querySelector('.client-name')?.textContent?.trim() || '' : '';
    const phone = row ? row.cells[2]?.textContent?.trim() || '' : '';
    const email = row ? row.cells[3]?.textContent?.trim() || '' : '';

    return `
        <div class="modal-overlay">
            <div class="modal-container">
                <button class="modal-close">&times;</button>
                <div class="modal-content">
                    <h2>${title}</h2>
                    <form class="modal-form">
                        <div class="form-group">
                            <label>ФИО</label>
                            <input type="text" value="${name}" ${isView ? 'disabled' : ''}>
                        </div>
                        <div class="form-group">
                            <label>Телефон</label>
                            <input type="text" value="${phone}" ${isView ? 'disabled' : ''}>
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" value="${email}" ${isView ? 'disabled' : ''}>
                        </div>
                        <div class="form-group">
                            <label>Адрес</label>
                            <input type="text" value="г. Одинцово, ул. Ленина, д. 1" ${isView ? 'disabled' : ''}>
                        </div>
                        ${!isView ? '<button type="button" class="modal-submit">Сохранить</button>' : ''}
                    </form>
                </div>
            </div>
        </div>
    `;
}

function getOrderModal(mode, row) {
    const isView = mode === 'view';
    const title = mode === 'add' ? 'Создание заказа' : mode === 'edit' ? 'Редактирование заказа' : 'Просмотр заказа';

    return `
        <div class="modal-overlay">
            <div class="modal-container">
                <button class="modal-close">&times;</button>
                <div class="modal-content">
                    <h2>${title}</h2>
                    <form class="modal-form">
                        <div class="form-group">
                            <label>Клиент</label>
                            <select ${isView ? 'disabled' : ''}>
                                <option>Иванов Иван</option>
                                <option>Петрова Анна</option>
                                <option>Сидоров Владимир</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Услуги</label>
                            <div class="order-services">
                                <label><input type="checkbox"> Катафалк Mercedes (25 000 ₽)</label>
                                <label><input type="checkbox"> Гроб дубовый (35 000 ₽)</label>
                                <label><input type="checkbox"> Венок (3 500 ₽)</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Статус</label>
                            <select ${isView ? 'disabled' : ''}>
                                <option>Новый</option>
                                <option>В работе</option>
                                <option>Выполнен</option>
                                <option>Отменён</option>
                            </select>
                        </div>
                        ${!isView ? '<button type="button" class="modal-submit">Сохранить</button>' : ''}
                    </form>
                </div>
            </div>
        </div>
    `;
}

function getAdminModal(mode, row) {
    const isView = mode === 'view';
    const title = mode === 'add' ? 'Добавление администратора' : mode === 'edit' ? 'Редактирование администратора' : 'Просмотр администратора';
    const login = row ? row.cells[1]?.textContent?.trim() || '' : '';
    const role = row ? row.cells[2]?.textContent?.trim() || '' : '';

    let roleValue = 'оператор';
    if (role.includes('Администратор')) roleValue = 'администратор';
    else if (role.includes('Менеджер')) roleValue = 'менеджер';

    return `
        <div class="modal-overlay">
            <div class="modal-container">
                <button class="modal-close">&times;</button>
                <div class="modal-content">
                    <h2>${title}</h2>
                    <form class="modal-form">
                        <div class="form-group">
                            <label>Логин</label>
                            <input type="text" value="${login}" ${isView ? 'disabled' : ''}>
                        </div>
                        <div class="form-group">
                            <label>Пароль</label>
                            <input type="password" placeholder="••••••••" ${isView ? 'disabled' : ''}>
                        </div>
                        <div class="form-group">
                            <label>Роль</label>
                            <select ${isView ? 'disabled' : ''}>
                                <option ${roleValue === 'администратор' ? 'selected' : ''}>Администратор</option>
                                <option ${roleValue === 'менеджер' ? 'selected' : ''}>Менеджер</option>
                                <option ${roleValue === 'оператор' ? 'selected' : ''}>Оператор</option>
                            </select>
                        </div>
                        ${!isView ? '<button type="button" class="modal-submit">Сохранить</button>' : ''}
                    </form>
                </div>
            </div>
        </div>
    `;
}

function getDefaultModal(page, mode) {
    const title = mode === 'add' ? `Добавление записи` : mode === 'edit' ? `Редактирование записи` : `Просмотр записи`;
    return `
        <div class="modal-overlay">
            <div class="modal-container">
                <button class="modal-close">&times;</button>
                <div class="modal-content">
                    <h2>${title}</h2>
                    <form class="modal-form">
                        <div class="form-group">
                            <label>Название</label>
                            <input type="text" placeholder="Введите название">
                        </div>
                        <div class="form-group">
                            <label>Описание</label>
                            <textarea rows="4" placeholder="Введите описание"></textarea>
                        </div>
                        <button type="button" class="modal-submit">Сохранить</button>
                    </form>
                </div>
            </div>
        </div>
    `;
}

// ===== КНОПКИ =====
function initButtons() {
    document.querySelectorAll('.btn-save').forEach(btn => {
        btn.addEventListener('click', () => {
            alert('Настройки сохранены (демо)');
        });
    });

    document.querySelectorAll('.btn-cancel').forEach(btn => {
        btn.addEventListener('click', () => {
            alert('Изменения отменены (демо)');
        });
    });

    document.querySelectorAll('.btn-export').forEach(btn => {
        btn.addEventListener('click', () => {
            alert('Экспорт данных (демо)');
        });
    });
}

// ===== ФОРМЫ =====
function initForms() {
    document.querySelectorAll('.file-upload button').forEach(btn => {
        btn.addEventListener('click', () => {
            alert('Выберите файл (демо)');
        });
    });
}

// ===== ПАГИНАЦИЯ =====
function initPagination() {
    document.querySelectorAll('.page-prev, .page-next').forEach(btn => {
        btn.addEventListener('click', () => {
            alert('Смена страницы (демо)');
        });
    });
}

// ===== ВКЛАДКИ НАСТРОЕК =====
function initTabs() {
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.addEventListener('click', (e) => {
            const tabId = btn.getAttribute('data-tab');
            const container = btn.closest('.page-settings');

            if (container) {
                container.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
                container.querySelectorAll('.tab-pane').forEach(pane => pane.classList.remove('active'));

                btn.classList.add('active');
                const activePane = container.querySelector(`#tab-${tabId}`);
                if (activePane) activePane.classList.add('active');
            }
        });
    });
}

function logoutUser() {
    let session_id = getCookie("session_id");
    if (session_id !== null) {
        $.post("../../server/post/adminAuthHandler.php", {"type": "logout", "session_id": session_id}, function (data) {
            let response = JSON.parse(data);
            if (response.response && response.response.code === 200) {
                deleteCookie("session_id");
            }
        });
    }
    setLocalStorage("is_logout", true);
    location.href = "auth.php";
}

// ===== ЗАГРУЗКА ДАШБОРДА ПО УМОЛЧАНИЮ =====
document.addEventListener('DOMContentLoaded', () => {
    document.querySelector('.nav-item[data-page="dashboard"]')?.click();
});