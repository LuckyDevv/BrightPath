// ===== БУРГЕР МЕНЮ =====
const burger = document.getElementById('burger');
const nav = document.getElementById('nav');
const overlay = document.getElementById('overlay');
let temp_order_data = null;

function toggleMenu() {
    burger.classList.toggle('active');
    nav.classList.toggle('active');
    overlay.classList.toggle('active');
    document.body.style.overflow = nav.classList.contains('active') ? 'hidden' : '';
}

burger.addEventListener('click', toggleMenu);
overlay.addEventListener('click', toggleMenu);

document.querySelectorAll('.nav a').forEach(link => {
    link.addEventListener('click', (e) => {
        if (nav.classList.contains('active')) {
            toggleMenu();
        }
    });
});

// ===== КАЛЬКУЛЯТОР =====
// Хранилище выбранных элементов
let calculatorItems = {
    transport: [],
    goods: [],
    services: []
};

// Демо-данные (для выбора)
let demoItems = {};

// Рендеринг выбранных элементов
function renderItems() {
    const categories = ['transport', 'goods', 'services'];
    let totalPrice = 0;
    let totalDetails = [];

    categories.forEach(category => {
        const container = document.getElementById(`${category}-items`);
        const items = calculatorItems[category];

        if (!container) return;

        if (items.length === 0) {
            container.innerHTML = '<div class="empty-items">Нет выбранных элементов</div>';
            return;
        }

        container.innerHTML = '';

        items.forEach((item, index) => {
            const itemTotal = item.price * item.quantity;
            totalPrice += itemTotal;
            totalDetails.push({ name: item.name, price: itemTotal, quantity: item.quantity, is_full: item.is_full });

            const itemDiv = document.createElement('div');
            itemDiv.className = 'calc-item';
            itemDiv.innerHTML = `
                <div class="calc-item-info">
                    <div class="calc-item-name">${escapeHtml(item.name)}</div>
                    <div class="calc-item-price">${item.price.toLocaleString()} ₽ × ${item.quantity}</div>
                </div>
                <div class="calc-item-actions">
                    <div class="calc-item-quantity">
                        <button class="quantity-btn" data-category="${category}" data-index="${index}" data-delta="-1">-</button>
                        <span class="quantity-value">${item.quantity}</span>
                        <button class="quantity-btn" ${item.is_full ? 'disabled' : ''} data-category="${category}" data-index="${index}" data-delta="1">+</button>
                    </div>
                    <button class="calc-item-remove" data-category="${category}" data-index="${index}">✕</button>
                </div>
            `;
            container.appendChild(itemDiv);
        });
    });

    // Обновляем итог
    const totalAmount = document.getElementById('totalAmount');
    const totalDetailsContainer = document.getElementById('totalDetails');

    totalAmount.textContent = `${totalPrice.toLocaleString()} ₽`;

    if (totalDetails.length === 0) {
        totalDetailsContainer.innerHTML = '<p>Добавьте услуги для расчёта</p>';
    } else {
        let detailsHtml = '';
        totalDetails.forEach(item => {
            detailsHtml += `
                <div class="total-item">
                    <span class="total-item-name">${escapeHtml(item.name)}</span>
                    <span class="total-item-price">${item.price.toLocaleString()} ₽</span>
                </div>
            `;
        });
        detailsHtml += `
            <div class="total-grand">
                <span>Итого:</span>
                <span>${totalPrice.toLocaleString()} ₽</span>
            </div>
        `;
        totalDetailsContainer.innerHTML = detailsHtml;
    }

    // Сохраняем в localStorage
    localStorage.setItem('calculatorItems', JSON.stringify(calculatorItems));
}

// Добавление элемента
function addItem(category, itemId, event) {
    console.log(event);
    const itemsList = demoItems[category];
    const item = itemsList.find(i => i.id === itemId);

    if (!item) return;

    const existingIndex = calculatorItems[category].findIndex(i => i.id === item.id);
    console.log(itemId);
    let difference = 0;
    if (existingIndex !== -1) {
        let newQuantity = calculatorItems[category][existingIndex].quantity + 1;
        difference = demoItems[category][itemId]["available_stock"] - newQuantity;
        console.log(difference);
        calculatorItems[category][existingIndex].quantity += 1
        if (difference === 0) {
            calculatorItems[category][existingIndex].is_full = true;
        }
    } else {
        difference = demoItems[category][itemId-1]["available_stock"] - 1;
        if (difference === 0) {
            calculatorItems[category].push({
                ...item,
                quantity: 1,
                is_full: true,
                item_id: itemId
            });
        }else{
            calculatorItems[category].push({
                ...item,
                quantity: 1,
                is_full: false,
                item_id: itemId
            });
        }
    }

    renderItems();
}

// Удаление элемента
function removeItem(category, index) {
    calculatorItems[category].splice(index, 1);
    renderItems();
}

// Изменение количества
function changeQuantity(category, index, delta) {
    const item = calculatorItems[category][index];
    const newQuantity = item.quantity + delta;

    if (newQuantity <= 0) {
        removeItem(category, index);
    } else {
        let difference = demoItems[category][item.item_id]["available_stock"] - newQuantity;
        console.log(difference);
        item.quantity += 1
        item.is_full = difference === 0;
        item.quantity = newQuantity;
        renderItems();
    }
}

// Очистка всего
function resetCalculator() {
    if (confirm('Очистить все выбранные услуги?')) {
        calculatorItems = {
            transport: [],
            goods: [],
            services: []
        };
        renderItems();
    }
}

// Модальное окно выбора элемента
function showSelectionModal(category) {
    const items = demoItems[category];
    const categoryNames = {
        transport: 'Транспорт',
        goods: 'Ритуальные товары',
        services: 'Ритуальные услуги'
    };

    let optionsHtml = '';
    items.forEach(item => {
        optionsHtml += `
            <div class="selection-item" data-id="${item.id}">
                <div class="selection-item-info">
                    <div class="selection-item-name">${escapeHtml(item.name)}</div>
                    <div class="selection-item-price">${item.price.toLocaleString()} ₽</div>
                </div>
                <button class="selection-item-add">+ Добавить</button>
            </div>
        `;
    });

    // Создаём временное модальное окно
    const modal = document.createElement('div');
    modal.className = 'selection-modal';
    modal.innerHTML = `
        <div class="selection-modal-overlay"></div>
        <div class="selection-modal-container">
            <div class="selection-modal-header">
                <h3>Выберите ${categoryNames[category]}</h3>
                <button class="selection-modal-close">&times;</button>
            </div>
            <div class="selection-modal-content">
                ${optionsHtml}
            </div>
        </div>
    `;

    document.body.appendChild(modal);
    document.body.style.overflow = 'hidden';

    // Закрытие
    modal.querySelector('.selection-modal-close').addEventListener('click', () => {
        modal.remove();
        document.body.style.overflow = '';
    });

    modal.querySelector('.selection-modal-overlay').addEventListener('click', () => {
        modal.remove();
        document.body.style.overflow = '';
    });

    // Обработчики кнопок
    modal.querySelectorAll('.selection-item-add').forEach((btn, idx) => {
        btn.addEventListener('click', (e) => {
            const itemId = items[idx].id;
            addItem(category, itemId, e);
            modal.remove();
            document.body.style.overflow = '';
        });
    });
}

// Обработчики событий
document.addEventListener('click', (e) => {
    // Удаление
    if (e.target.classList.contains('calc-item-remove')) {
        const category = e.target.dataset.category;
        const index = parseInt(e.target.dataset.index);
        removeItem(category, index);
    }

    // Изменение количества
    if (e.target.classList.contains('quantity-btn')) {
        const category = e.target.dataset.category;
        const index = parseInt(e.target.dataset.index);
        const delta = parseInt(e.target.dataset.delta);
        changeQuantity(category, index, delta);
    }

    // Добавление элемента
    if (e.target.classList.contains('btn-add-item')) {
        const category = e.target.dataset.category;
        showSelectionModal(category);
    }

    // Сброс
    if (e.target.id === 'resetCalc') {
        resetCalculator();
    }

    // Оформление заказа
    if (e.target.id === 'orderBtn') {
        if (calculatorItems.transport.length === 0 && calculatorItems.goods.length === 0 &&
            calculatorItems.services.length) {
            alert('Добавьте хотя бы одну услугу для оформления заказа');
        } else {
            document.getElementById('order_modal').classList.add('active');
            document.body.style.overflow = 'hidden';
            temp_order_data = {
                "transport": JSON.stringify(calculatorItems.transport),
                "goods": JSON.stringify(calculatorItems.goods),
                "services": JSON.stringify(calculatorItems.services)
            }
        }
    }
});

function orderCreate() {
    let order_modal_name = document.getElementById("order_modal_name");
    let order_modal_phone = document.getElementById("order_modal_phone");
    let order_modal_email = document.getElementById("order_modal_email");
    if (order_modal_name.value.trim() === '') {
        Toast.warning("Введите имя!");
        return;
    }
    if (order_modal_phone.value.trim() === '') {
        Toast.warning("Введите имя!");
        return;
    }
    if (order_modal_email.value.trim() === '') {
        Toast.warning("Введите имя!");
        return;
    }
    if (temp_order_data != null) {
        $.post(
            "../../server/post/userOrdersHandler.php",
            {
                "type": "newOrder",
                "userName": order_modal_name.value.trim(),
                "userPhone": order_modal_phone.value.trim(),
                "userEmail": order_modal_email.value.trim(),
                "transport": temp_order_data.transport,
                "goods": temp_order_data.goods,
                "services": temp_order_data.services
            },
            function (data) {
                let response = JSON.parse(data);
                if (response.response && response.response.code === 200) {
                    Toast.success("Заказ успешно оформлен.");
                    Toast.success("21424332 - Ваш номер заказа");
                    document.getElementById('order_modal').classList.remove('active');
                    document.body.style.overflow = 'auto';
                }
            }
        )
    }
}

function orderClose() {
    document.getElementById("order_modal_name").value = '';
    document.getElementById("order_modal_phone").value = '';
    document.getElementById("order_modal_email").value = '';
    document.getElementById('order_modal').classList.remove('active');
    document.body.style.overflow = 'auto';
}

// Экранирование HTML
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// Загрузка сохранённых данных
function loadSavedData() {
    const saved = localStorage.getItem('calculatorItems');
    if (saved) {
        try {
            calculatorItems = JSON.parse(saved);
            renderItems();
        } catch (e) {
            console.error('Ошибка загрузки сохранённых данных');
        }
    }
}

// ===== ПРЕСЕТЫ ТАРИФОВ =====
const presets = {
    econom: {
        transport: [{ id: 3, name: 'ГАЗель NEXT', price: 13000, quantity: 1 }], // ГАЗель
        goods: [{ id: 1, name: 'Гроб сосновый', price: 8900, quantity: 1 }],
        services: [{ id: 1, name: 'Копка могилы (ручная)', price: 8000, quantity: 1 }]
    },
    standard: {
        transport: [{ id: 1, name: 'Mercedes-Benz E-Class', price: 25000, quantity: 1 }],
        goods: [{ id: 2, name: 'Гроб дубовый', price: 35000, quantity: 1 }],
        services: [
            { id: 2, name: 'Копка могилы (механизированная)', price: 12000, quantity: 1 },
            { id: 4, name: 'Оформление документов', price: 3500, quantity: 1 }
        ]
    },
    premium: {
        transport: [{ id: 1, name: 'Mercedes-Benz E-Class', price: 25000, quantity: 1 }],
        goods: [
            { id: 2, name: 'Гроб дубовый', price: 35000, quantity: 1 },
            { id: 3, name: 'Венок траурный', price: 3500, quantity: 2 }
        ],
        services: [
            { id: 2, name: 'Копка могилы (механизированная)', price: 12000, quantity: 1 },
            { id: 3, name: 'Отпевание в церкви', price: 5000, quantity: 1 },
            { id: 4, name: 'Оформление документов', price: 3500, quantity: 1 }
        ]
    },
    vip: {
        transport: [
            { id: 1, name: 'Mercedes-Benz E-Class', price: 25000, quantity: 1 },
            { id: 4, name: 'Mercedes-Benz Sprinter', price: 24000, quantity: 2 }
        ],
        goods: [
            { id: 2, name: 'Гроб дубовый', price: 35000, quantity: 1 },
            { id: 3, name: 'Венок траурный', price: 3500, quantity: 5 }
        ],
        services: [
            { id: 2, name: 'Копка могилы (механизированная)', price: 12000, quantity: 1 },
            { id: 3, name: 'Отпевание в церкви', price: 5000, quantity: 1 },
            { id: 4, name: 'Оформление документов', price: 3500, quantity: 1 }
        ]
    },
    custom: {
        transport: [],
        goods: [],
        services: []
    }
};

// Функция загрузки пресета
function loadPreset(presetName) {
    const preset = presets[presetName];
    if (!preset) return;

    if (presetName === 'custom') {
        if (confirm('Очистить все выбранные услуги?')) {
            calculatorItems = {
                transport: [],
                goods: [],
                services: []
            };
            renderItems();
        }
        return;
    }

    // Спрашиваем подтверждение
    if (confirm(`Загрузить пакет "${getPresetTitle(presetName)}"? Текущие выбранные услуги будут заменены.`)) {
        // Копируем пресет в calculatorItems
        calculatorItems = {
            transport: preset.transport.map(item => ({ ...item })),
            goods: preset.goods.map(item => ({ ...item })),
            services: preset.services.map(item => ({ ...item }))
        };
        renderItems();
    }
}

function getPresetTitle(presetName) {
    const titles = {
        econom: 'Эконом',
        standard: 'Стандарт',
        premium: 'Премиум',
        vip: 'VIP',
        custom: 'Конструктор'
    };
    return titles[presetName] || presetName;
}

// Обработчики для кнопок пресетов
function initPresets() {
    document.querySelectorAll('.preset-card').forEach(card => {
        const presetName = card.dataset.preset;
        const btn = card.querySelector('.btn-preset');

        if (btn) {
            btn.addEventListener('click', () => {
                loadPreset(presetName);
            });
        }
    });
}

// Инициализация
document.addEventListener('DOMContentLoaded', () => {
    loadSavedData();
    renderItems();
    initPresets();  // Добавь эту строку
});