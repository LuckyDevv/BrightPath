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

// ===== ПРОВЕРКА ЗАКАЗА =====
const trackForm = document.getElementById('trackForm');
const resultDiv = document.getElementById('trackResult');
const errorDiv = document.getElementById('trackError');
const loadingDiv = document.getElementById('trackLoading');

function getStatusText(status) {
    const statusMap = {
        'created': 'Создан',
        'in_work': 'В работе',
        'completed': 'Выполнен',
        'cancelled': 'Отменён'
    };
    return statusMap[status] || status;
}

function getStatusClass(status) {
    const classMap = {
        'created': 'status-created',
        'in_work': 'status-in_work',
        'completed': 'status-completed',
        'cancelled': 'status-cancelled'
    };
    return classMap[status] || 'status-created';
}

function renderItems(items, containerId, categoryName) {
    const container = document.getElementById(containerId);
    const parentCategory = container?.closest('.items-category');

    if (!container) return;

    if (!items || items.length === 0) {
        if (parentCategory) parentCategory.style.display = 'none';
        return;
    }

    if (parentCategory) parentCategory.style.display = 'block';
    container.innerHTML = '';

    items.forEach(item => {
        const itemDiv = document.createElement('div');
        itemDiv.className = 'order-item';
        if (categoryName === "agentsItems") {
            itemDiv.innerHTML = `
            <span class="order-item-name">${escapeHtml(item)}</span>
        `;
        }else{
            itemDiv.innerHTML = `
            <span class="order-item-name">${escapeHtml(item.name)} × ${item.quantity || 1}</span>
            <span class="order-item-price">${(item.price * (item.quantity || 1)).toLocaleString()} ₽</span>
        `;
        }
        container.appendChild(itemDiv);
    });
}

function displayOrder(order) {
    // Основная информация
    document.getElementById('resultOrderId').textContent = order.id;
    document.getElementById('orderDate').textContent = formatDate(order.created_at);
    document.getElementById('orderTotal').textContent = order.summary.toLocaleString() + ' ₽';
    document.getElementById('orderPhone').textContent = order.userphone;
    document.getElementById('orderEmailResult').textContent = order.useremail;
    // Статус
    const statusDiv = document.getElementById('orderStatus');
    console.log(order.status);
    statusDiv.textContent = getStatusText(order.status);
    statusDiv.className = `order-status ${getStatusClass(order.status)}`;
    // Рендерим категории
    renderItems(order.transport, 'transportList', 'transportItems');
    renderItems(order.goods, 'goodsList', 'goodsItems');
    renderItems(order.services, 'servicesList', 'servicesItems');
    renderItems(order.agents, 'agentsList', 'agentsItems');
    // Показываем результат
    resultDiv.style.display = 'block';
    errorDiv.style.display = 'none';
    // Прокрутка к результату
    resultDiv.scrollIntoView({ behavior: 'smooth', block: 'start' });
}

function formatDate(dateStr) {
    if (!dateStr) return 'Дата неизвестна';
    const date = new Date(dateStr);
    return date.toLocaleDateString('ru-RU', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric'
    });
}

function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// Отправка формы
trackForm.addEventListener('submit', async (e) => {
    e.preventDefault();

    const orderId = document.getElementById('orderId').value.trim();
    const orderEmail = document.getElementById('orderEmail').value.trim();

    if (!orderId || !orderEmail) {
        Toast.warning('Заполните оба поля');
        return;
    }

    // Показываем загрузку
    resultDiv.style.display = 'none';
    errorDiv.style.display = 'none';
    loadingDiv.style.display = 'block';

    $.post("../server/post/userOrdersHandler.php", {
        "type": "checkStatus",
        "orderId": orderId,
        "email": orderEmail
    }, function(data) {
        let response;
        try {
            response = JSON.parse(data);
        }catch(e) {
            Toast.error("Ошибка подключения к серверу");
            return;
        }
        if (response.response) {
            console.log(response.response);
            try {
                displayOrder(response.response.data.order);
            }catch(e){
                Toast.error("Ошибка обработки данных!");
            }
        }else{
            errorDiv.style.display = 'block';
            resultDiv.style.display = 'none';
        }
    });
});