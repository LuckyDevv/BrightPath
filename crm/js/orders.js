// ===== МОДАЛЬНОЕ ОКНО СТАТУСА ЗАКАЗА =====
let currentOrderId = null;
let currentOrderStatus = null;

async function openOrderStatusModal(orderId) {

    const sessionId = getCookie('session_id');
    const fingerprint = await collectFingerPrint();

    $.post("../../server/post/adminOrdersHandler.php", {"type": "getById", "id": orderId, "session_id": sessionId, "fingerprint": fingerprint}, function (data){
        var response = JSON.parse(data);
        if (response.response) {
            var orderData = JSON.parse(response.response.message);
            if (orderData) {
                currentOrderId = orderId;
                const orderInfo = document.querySelector('#orderStatusModal .order-info');
                var currentStatus = 'created';
                if (orderInfo) {
                    currentStatus = orderData.status || 'created';
                    let content_text = '';
                    JSON.parse(orderData.transport).forEach(vehicle => {
                        content_text += `<p>${vehicle.name} x ${vehicle.quantity}</p>`;
                    });
                    JSON.parse(orderData.goods).forEach(goods => {
                        content_text += `<p>${goods.name} x ${goods.quantity}</p>`;
                    });
                    JSON.parse(orderData.services).forEach(service => {
                        content_text += `<p>${service.name} x ${service.quantity}</p>`;
                    });
                    orderInfo.innerHTML = `
                        <p><strong>Заказ №${orderId}</strong></p>
                        <p>Клиент: ${orderData.username || 'Неизвестно'}</p>
                        <p>Почта: ${orderData.useremail || 'Неизвестно'}</p>
                        <p>Телефон: ${orderData.userphone || 'Неизвестно'}</p>
                        <p>Сумма: ${orderData.summary || 'Неизвестно'} ₽</p>
                    `;
                    document.getElementById('contentOrderList').innerHTML = content_text;
                }
                const statusBadge = document.getElementById('currentStatusBadge');
                const statusText = getStatusText(currentStatus);
                const statusClass = getStatusClass(currentStatus);
                statusBadge.textContent = statusText;
                statusBadge.className = `status-badge ${statusClass}`;

                // Настраиваем доступные опции для выбора в зависимости от текущего статуса
                const select = document.getElementById('newOrderStatus');
                select.innerHTML = '<option value="">-- Выберите статус --</option>';

                document.getElementById('statusSaveButton').style.display = 'block';
                document.getElementById('statusForm').style.display = 'block';
                document.getElementById('agentsCompletedForm').style.display = 'none';
                if (currentStatus === 'created') {
                    addStatusOption(select, 'in_work', 'В работе');
                    addStatusOption(select, 'cancelled', 'Отменён');
                } else if (currentStatus === 'in_work') {
                    addStatusOption(select, 'cancelled', 'Отменён');
                    addStatusOption(select, 'completed', 'Выполнен');
                } else {
                    // Если статус cancelled или completed — ничего не доступно
                    select.disabled = true;
                    select.innerHTML = '<option value="">Статус нельзя изменить</option>';
                    document.getElementById('statusSaveButton').style.display = 'none';
                    document.getElementById('statusForm').style.display = 'none';
                    if (orderData.agents) {
                        document.getElementById('agentsCompletedForm').style.display = 'block';
                        let agentsCompletedOrder = "";
                        orderData.agents.split(',').forEach(agent => agentsCompletedOrder += `<p>${agent}</p>`);
                        document.getElementById('agentsCompletedList').innerHTML = agentsCompletedOrder;
                    }
                }
                document.getElementById('orderStatusModal').classList.add('active');
                document.body.style.overflow = 'hidden';
            }else{
                Toast.error("Не удалось получить информацию о заказе.");
            }
        }else{
            Toast.error(response.error.message);
        }
    });
}

async function orderDelete(orderId) {
    if (confirm('Вы действительно хотите удалить этот заказ?')) {
        const sessionId = getCookie('session_id');
        const fingerprint = await collectFingerPrint();
        $.post("../../server/post/adminOrdersHandler.php", {
            "type": "deleteById",
            "id": orderId,
            "session_id": sessionId,
            "fingerprint": fingerprint
        }, function(data){
            try {
                var response = JSON.parse(data);
            }catch(e){
                Toast.error("Не удалось связаться с сервером!");
                return;
            }
            if (response.response) {
                document.getElementById(`tr_${orderId}`)?.remove();
                Toast.success("Заказ удалён успешно!");
            }else if (response.response.error) {
                Toast.error(`Ошибка [${response.error.code}]: ${response.error.message}`);
            }else{
                Toast.error("Не удалось связаться с сервером!");
            }
        });
    }
}

function addStatusOption(select, value, text) {
    const option = document.createElement('option');
    option.value = value;
    option.textContent = text;
    option.className = `status-option-${value}`;
    select.appendChild(option);
}

function getStatusText(status) {
    const map = {
        'created': 'Создан',
        'in_work': 'В работе',
        'cancelled': 'Отменён',
        'completed': 'Выполнен'
    };
    return map[status] || status;
}

function getStatusClass(status) {
    const map = {
        'created': 'status pending',
        'in_work': 'status in-progress',
        'cancelled': 'status inactive',
        'completed': 'status completed'
    };
    return map[status] || 'status inactive';
}

function closeOrderStatusModal(clean = true) {
    document.getElementById('orderStatusModal').classList.remove('active');
    document.body.style.overflow = '';
    if (clean) currentOrderId = null;
}



// ===== МОДАЛЬНОЕ ОКНО ВЫБОРА АГЕНТОВ =====
let pendingOrderId = null;

function openOrderAgentsModal(orderId) {
    pendingOrderId = orderId;
    document.getElementById('orderAgentsModal').classList.add('active');
    document.body.style.overflow = 'hidden';
}

async function saveOrderModal() {
    const newStatus = document.getElementById('newOrderStatus').value;
    if (!newStatus) {
        Toast.warning('Выберите новый статус');
        return;
    }

    if (newStatus === 'completed') {
        // Если выбран статус "Выполнен" — открываем модалку выбора агентов
        closeOrderStatusModal(false);
        openOrderAgentsModal(currentOrderId);
    } else {
        const sessionId = getCookie('session_id');
        const fingerprint = await collectFingerPrint();
        // Имитация сохранения статуса (здесь будет AJAX-запрос)
        console.log(`Current order id: ${currentOrderId}`);
        $.post("../../server/post/adminOrdersHandler.php", {
            "type": "changeStatus",
            "id": currentOrderId,
            "newStatus": newStatus,
            "session_id": sessionId,
            "fingerprint": fingerprint
        }, function (data){
            let response;
            try {
                response = JSON.parse(data);
            }catch (e) {
                Toast.error("Не удалось связаться с сервером");
                return;
            }
            if (response.response) {
                Toast.success(`Статус заказа изменён на "${getStatusText(newStatus)}"`);
            }else if (response.error) {
                Toast.error(`Ошибка [${response.error.code}]: ${response.error.message}`);
            }else{
                Toast.error("Не удалось связаться с сервером");
            }
        });
        updateOrderStatusInTable(currentOrderId, newStatus);
        closeOrderStatusModal();
        // Обновляем статус в таблице (имитация)
    }
}

function closeOrderAgentsModal() {
    document.getElementById('orderAgentsModal').classList.remove('active');
    document.body.style.overflow = '';
    // Сбрасываем выбранных агентов
    document.querySelectorAll('.agent-checkbox').forEach(cb => cb.checked = false);
    document.getElementById('selectAllAgents').checked = false;
    currentOrderId = null;
}

// Обновление статуса в таблице (имитация)
function updateOrderStatusInTable(orderId, newStatus) {
    console.log("Updateee");
    const row = document.getElementById(`tr_${orderId}`);
    if (row) {
        const statusCell = row.childNodes[13];
        if (statusCell) {
            statusCell.innerHTML = `<span class="${getStatusClass(newStatus)}">${getStatusText(newStatus)}</span>`;
        }
    }
}

// Инициализация кнопок в таблице (пример)
function initOrders() {
    // Выбрать всех агентов
    document.getElementById('selectAllAgents')?.addEventListener('change', function(e) {
        document.querySelectorAll('.agent-checkbox').forEach(cb => {
            cb.checked = e.target.checked;
        });
    });

    // Подтверждение выбора агентов
    document.getElementById('saveAgentsBtn')?.addEventListener('click', async function () {
        let selectedAgents = "";
        let selectedAgentsOrderDDD = [];
        document.querySelectorAll('.agent-checkbox:checked').forEach(cb => {
            if (selectedAgents === "") {
                selectedAgents += cb.dataset.agentName;
            } else {
                selectedAgents += "," + cb.dataset.agentName;
            }
            selectedAgentsOrderDDD.push({"name": cb.dataset.agentName, "position": cb.dataset.agentPosition});
        });
        if (selectedAgents === "") {
            Toast.warning("Выберите агентов, которые выполняли заказ.");
            return;
        }

        console.log(selectedAgentsOrderDDD);


        const sessionId = getCookie('session_id');
        const fingerprint = await collectFingerPrint();

        $.post("../../server/post/adminOrdersHandler.php", {
            "type": "completeOrder",
            "id": currentOrderId,
            "agents": selectedAgents,
            "session_id": sessionId,
            "fingerprint": fingerprint,
            "agentsOrderDDD": selectedAgentsOrderDDD
        }, function (data) {
            let response;
            try {
                response = JSON.parse(data);
            }catch(e) {
                Toast.error("Не удалось связаться с сервером!");
                return;
            }
            if (response.response) {
                Toast.success(`Статус заказа изменён на "Выполнен"`);
            }else if (response.error) {
                Toast.error(`Ошибка [${response.error.code}]: ${response.error.message}`);
            }else{
                Toast.error("Не удалось связаться с сервером");
                return;
            }

            console.log(`Заказ ${currentOrderId}: выбранные агенты:`, selectedAgents);
            updateOrderStatusInTable(currentOrderId, 'completed');
            closeOrderAgentsModal();
        });
    });
}