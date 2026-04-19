// ===== БУРГЕР МЕНЮ =====
const burger = document.getElementById('burger');
const nav = document.getElementById('nav');
const overlay = document.getElementById('overlay');

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
const demoItems = {
    transport: [
        { id: 1, name: 'Mercedes-Benz E-Class', price: 25000, category: 'transport' },
        { id: 2, name: 'Cadillac CTS', price: 30000, category: 'transport' },
        { id: 3, name: 'ГАЗель NEXT', price: 13000, category: 'transport' },
        { id: 4, name: 'Mercedes-Benz Sprinter', price: 24000, category: 'transport' }
    ],
    goods: [
        { id: 1, name: 'Гроб сосновый', price: 8900, category: 'goods' },
        { id: 2, name: 'Гроб дубовый', price: 35000, category: 'goods' },
        { id: 3, name: 'Венок траурный', price: 3500, category: 'goods' },
        { id: 4, name: 'Крест деревянный', price: 2800, category: 'goods' }
    ],
    services: [
        { id: 1, name: 'Копка могилы (ручная)', price: 8000, category: 'services' },
        { id: 2, name: 'Копка могилы (механизированная)', price: 12000, category: 'services' },
        { id: 3, name: 'Отпевание в церкви', price: 5000, category: 'services' },
        { id: 4, name: 'Оформление документов', price: 3500, category: 'services' }
    ]
};

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
            totalDetails.push({ name: item.name, price: itemTotal, quantity: item.quantity });

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
                        <button class="quantity-btn" data-category="${category}" data-index="${index}" data-delta="1">+</button>
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
function addItem(category, itemId) {
    const itemsList = demoItems[category];
    const item = itemsList.find(i => i.id === itemId);

    if (!item) return;

    const existingIndex = calculatorItems[category].findIndex(i => i.id === item.id);

    if (existingIndex !== -1) {
        calculatorItems[category][existingIndex].quantity += 1;
    } else {
        calculatorItems[category].push({
            ...item,
            quantity: 1
        });
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
        btn.addEventListener('click', () => {
            const itemId = items[idx].id;
            addItem(category, itemId);
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
            alert('Заказ оформлен! Наш менеджер свяжется с вами.');
        }
    }
});

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