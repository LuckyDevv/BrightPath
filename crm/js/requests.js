// ===== МОДАЛЬНОЕ ОКНО СТАТУСА ЗАКАЗА =====
let currentRequestId = null;

async function openRequestStatusModal(requestId) {

    const sessionId = getCookie('session_id');
    const fingerprint = await collectFingerPrint();

    $.post("../../server/post/adminRequestsHandler.php", {"type": "getById", "id": requestId, "session_id": sessionId, "fingerprint": fingerprint}, function (data){
        var response = JSON.parse(data);
        if (response.response) {
            var requestData = JSON.parse(response.response.message);
            if (requestData) {
                currentRequestId = requestId;
                const requestInfo = document.querySelector('#requestStatusModal .order-info');
                var currentStatus = 'created';
                let operatorNotMatch = false;
                if (requestInfo) {
                    currentStatus = requestData.status || 'created';
                    var requestHTML = '';
                    requestHTML = `
                        <p><strong>Заявка №${requestId}</strong></p>
                        <p>Имя: ${requestData.username || 'Неизвестно'}</p>
                        <p>Почта: ${requestData.useremail || 'Неизвестно'}</p>
                        <p>Телефон: ${requestData.userphone || 'Неизвестно'}</p>
                    `;
                    if (requestData.operator) {
                        requestHTML += `<p>Оператор: ${requestData.operator}</p>`;
                        if (requestData.operator !== getCookie("login")) {
                            operatorNotMatch = true;
                            requestHTML += "<br><p class='request-in-work'>* Вы не можете работать над данной заявкой, поскольку её уже взял в работу другой оператор</p>";
                        }
                    }
                    requestInfo.innerHTML = requestHTML;
                }

                const select = document.getElementById('newRequestStatus');

                const statusBadge = document.getElementById('currentRequestStatusBadge');
                const statusText = getRequestStatusText(currentStatus);
                const statusClass = getRequestStatusClass(currentStatus);
                statusBadge.textContent = statusText;
                statusBadge.className = `status-badge ${statusClass}`;

                document.getElementById('requestSaveButton').style.display = 'block';
                document.getElementById('statusForm').style.display = 'block';
                document.getElementById('operatorComment').style.display = 'none';

                if (operatorNotMatch) {
                    // Если статус cancelled или completed — ничего не доступно
                    select.disabled = true;
                    select.innerHTML = '<option value="">Статус нельзя изменить</option>';
                    document.getElementById('requestSaveButton').style.display = 'none';
                    document.getElementById('statusForm').style.display = 'none';
                    console.log(requestData.comment !== null);
                    if (requestData.comment !== null && requestHTML.comment.trim() !== '' && requestData.comment !== undefined) {
                        document.getElementById('operatorComment').style.display = 'block';
                        document.getElementById('operatorCommentText').innerHTML = requestData.comment;
                    }
                    document.getElementById('requestStatusModal').classList.add('active');
                    document.body.style.overflow = 'hidden';
                    return;
                }

                // Настраиваем доступные опции для выбора в зависимости от текущего статуса
                select.innerHTML = '<option value="">-- Выберите статус --</option>';
                select.disabled = false;
                if (currentStatus === 'created') {
                    addRequestStatusOption(select, 'in_work', 'В работе');
                    addRequestStatusOption(select, 'cancelled', 'Отменена');
                } else if (currentStatus === 'in_work') {
                    addRequestStatusOption(select, 'cancelled', 'Отменена');
                    addRequestStatusOption(select, 'completed', 'Выполнена');
                } else {
                    // Если статус cancelled или completed — ничего не доступно
                    select.disabled = true;
                    select.innerHTML = '<option value="">Статус нельзя изменить</option>';
                    document.getElementById('requestSaveButton').style.display = 'none';
                    document.getElementById('statusForm').style.display = 'none';
                    if (requestData.comment) {
                        document.getElementById('operatorComment').style.display = 'block';
                        document.getElementById('operatorCommentText').innerHTML = requestData.comment;
                    }
                }
                document.getElementById('requestStatusModal').classList.add('active');
                document.body.style.overflow = 'hidden';
            }else{
                Toast.error("Не удалось получить информацию о заказе.");
            }
        }else{
            Toast.error(response.error.message);
        }
    });
}

function addRequestStatusOption(select, value, text) {
    const option = document.createElement('option');
    option.value = value;
    option.textContent = text;
    option.className = `status-option-${value}`;
    select.appendChild(option);
}

function getRequestStatusText(status) {
    const map = {
        'created': 'Создана',
        'in_work': 'В работе',
        'cancelled': 'Отменена',
        'completed': 'Выполнена'
    };
    return map[status] || status;
}

function getRequestStatusClass(status) {
    const map = {
        'created': 'status pending',
        'in_work': 'status in-progress',
        'cancelled': 'status inactive',
        'completed': 'status completed'
    };
    return map[status] || 'status inactive';
}

function closeRequestStatusModal(clean = true) {
    document.getElementById('requestStatusModal').classList.remove('active');
    document.body.style.overflow = '';
    if (clean) currentRequestId = null;
}

function openRequestCommentModal(requestId) {
    document.getElementById('requestCommentModal').classList.add('active');
    document.body.style.overflow = 'hidden';
}

async function requestDelete(requestId) {
    if (confirm('Вы действительно хотите удалить эту заявку?')) {
        const sessionId = getCookie('session_id');
        const fingerprint = await collectFingerPrint();
        $.post("../../server/post/adminRequestsHandler.php", {
            "type": "deleteById",
            "id": requestId,
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
                document.getElementById(`tr_${requestId}`)?.remove();
                Toast.success("Заявка удалена успешно!");
            }else if (response.response.error) {
                Toast.error(`Ошибка [${response.error.code}]: ${response.error.message}`);
            }else{
                Toast.error("Не удалось связаться с сервером!");
            }
        });
    }
}

async function saveRequestModal() {
    const newStatus = document.getElementById('newRequestStatus').value;
    if (!newStatus) {
        Toast.warning('Выберите новый статус');
        return;
    }

    if (newStatus === 'completed') {
        // Если выбран статус "Выполнен" — открываем модалку выбора агентов
        closeRequestStatusModal(false);
        openRequestCommentModal(currentRequestId);
    } else {
        const sessionId = getCookie('session_id');
        const fingerprint = await collectFingerPrint();
        // Имитация сохранения статуса (здесь будет AJAX-запрос)
        console.log(`Current request id: ${currentRequestId}`);
        $.post("../../server/post/adminRequestsHandler.php", {
            "type": "changeStatus",
            "id": currentRequestId,
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
                Toast.success(`Статус заказа изменён на "${getRequestStatusText(newStatus)}"`);
                updateRequestStatusInTable(currentRequestId, newStatus);
                closeRequestStatusModal();
            }else if (response.error) {
                Toast.error(`Ошибка [${response.error.code}]: ${response.error.message}`);
            }else{
                Toast.error("Не удалось связаться с сервером");
            }
        });
        // Обновляем статус в таблице (имитация)
    }
}

function closeRequestCommentModal() {
    document.getElementById('requestCommentModal').classList.remove('active');
    document.body.style.overflow = '';
    document.getElementById('commentInput').value = '';
    currentRequestId = null;
}

function updateRequestStatusInTable(requestId, newStatus) {
    console.log("Updateee");
    const row = document.getElementById(`tr_${requestId}`);
    if (row) {
        const statusCell = row.childNodes[11];
        if (statusCell) {
            statusCell.innerHTML = `<span class="${getRequestStatusClass(newStatus)}">${getRequestStatusText(newStatus)}</span>`;
        }
    }
}

async function saveRequestComment() {
    const commentInput = document.getElementById('commentInput');
    if (commentInput) {
        const sessionId = getCookie('session_id');
        const fingerprint = await collectFingerPrint();
        const comment = commentInput.value;
        $.post("../../server/post/adminRequestsHandler.php", {"type": "completeRequest", "id": currentRequestId, "comment": comment, "session_id": sessionId, "fingerprint": fingerprint}, function (data) {
            let response;
            try {
                response = JSON.parse(data);
            }catch(e){
                Toast.error("Не удалось связаться с сервером!");
            }
            if (response.response) {
                Toast.success('Статус заявки сменён на "Выполнена"')
                updateRequestStatusInTable(currentRequestId, "completed");
                closeRequestCommentModal();
            }else if (response.error) {
                Toast.error(`Ошибка [${response.error.code}]: ${response.error.message}`);
            }else{
                Toast.error("Не удалось связаться с сервером!");
            }
        });
    }
}
