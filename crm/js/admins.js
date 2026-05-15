// ===== ПЕРЕМЕННЫЕ =====
let currentEditAdminId = null;
let currentEditAdminStatus = 'active';

// ===== ОТКРЫТИЕ МОДАЛКИ РЕДАКТИРОВАНИЯ =====
async function adminEdit(adminId) {
    const sessionId = getCookie('session_id');
    const fingerprint = await collectFingerPrint();
    $.post("../../server/post/adminAdminsHandler.php", {
        type: "getById",
        adminId: adminId,
        session_id: sessionId,
        fingerprint: fingerprint
    }, function (data) {
        const response = JSON.parse(data);
        if (response.response && response.response.code === 200) {
            const admin = JSON.parse(response.response.message);
            currentEditAdminId = admin.id;

            // Заполняем поля
            document.getElementById('adm_modal_edit_id').value = admin.id;
            document.getElementById('adm_modal_edit_login').value = admin.login;
            document.getElementById('adm_modal_edit_role').value = admin.role;
            document.getElementById('adm_modal_edit_status').value = admin.is_locked ? 'Заблокирован' : 'Активен';
            document.getElementById('adm_modal_edit_created_at').value = formatDate(admin.created_at);
            document.getElementById('adm_modal_edit_password_updated').value = formatDate(admin.password_updated_at) || 'Не обновлялся';
            document.getElementById('adm_modal_edit_last_login').value = formatDate(admin.last_login_at) || 'Никогда';
            document.getElementById('adm_modal_edit_last_ip').value = admin.last_login_ip || '—';
            document.getElementById('adm_modal_edit_2fa_status').value = admin.is_2fa_enabled ? 'Включена' : 'Не включена';

            // Кнопка блокировки
            const blockBtn = document.getElementById('adm_modal_edit_block_btn');
            if (admin.is_locked) {
                blockBtn.textContent = 'Разблокировать';
                blockBtn.classList.add('unblock');
                currentEditAdminStatus = 'blocked';
            } else {
                blockBtn.textContent = 'Заблокировать';
                blockBtn.classList.remove('unblock');
                currentEditAdminStatus = 'active';
            }

            document.getElementById('adm_modal_edit').classList.add('active');
            document.body.style.overflow = 'hidden';
        }
    });
}

// ===== ЗАКРЫТИЕ МОДАЛКИ =====
function adminCloseEdit() {
    document.getElementById('adm_modal_edit').classList.remove('active');
    document.body.style.overflow = '';
    currentEditAdminId = null;
}

// ===== ПЕРЕКЛЮЧЕНИЕ СТАТУСА БЛОКИРОВКИ =====
function toggleAdminBlock() {
    const statusSelect = document.getElementById('adm_modal_edit_status');
    const blockBtn = document.getElementById('adm_modal_edit_block_btn');

    if (currentEditAdminStatus === 'active') {
        // Блокируем
        statusSelect.value = 'Заблокирован';
        blockBtn.textContent = 'Разблокировать';
        blockBtn.classList.add('unblock');
        currentEditAdminStatus = 'blocked';
    } else {
        // Разблокируем
        statusSelect.value = 'Активен';
        blockBtn.textContent = 'Заблокировать';
        blockBtn.classList.remove('unblock');
        currentEditAdminStatus = 'active';
    }
}

// ===== СОХРАНЕНИЕ ИЗМЕНЕНИЙ =====
async function adminSaveEdit() {
    const adminData = {
        adminId: currentEditAdminId,
        login: document.getElementById("adm_modal_edit_login").value,
        role: document.getElementById('adm_modal_edit_role').value,
        is_locked: currentEditAdminStatus
    };

    const sessionId = getCookie('session_id');
    const fingerprint = await collectFingerPrint();
    $.post("../../server/post/adminAdminsHandler.php", {
        type: "updateAdmin",
        adminData: JSON.stringify(adminData),
        session_id: sessionId,
        fingerprint: fingerprint
    }, function (data) {
        const response = JSON.parse(data);
        if (response.response && response.response.code === 200) {
            Toast.success('Данные администратора обновлены');
            // Обновляем строку в таблице
            updateAdminRow(currentEditAdminId, adminData);
            adminCloseEdit();
        } else {
            Toast.error('Ошибка: ' + (response.error?.message || 'Не удалось обновить'));
        }
    });
}

// ===== СБРОС 2FA =====
async function resetAdmin2FA() {
    if (confirm('Сбросить двухфакторную аутентификацию для этого администратора? Ему потребуется настроить 2FA заново при следующем входе.')) {
        const sessionId = getCookie('session_id');
        const fingerprint = await collectFingerPrint();
        $.post("../../server/post/adminAdminsHandler.php", {
            type: "reset2FA",
            adminId: currentEditAdminId,
            session_id: sessionId,
            fingerprint: fingerprint
        }, function (data) {
            const response = JSON.parse(data);
            if (response.response && response.response.code === 200) {
                Toast.success('2FA сброшена');
                const statusBadge = document.getElementById('adm_modal_edit_2fa_status');
                statusBadge.value = 'Не включена';
            } else {
                Toast.error('Ошибка при сбросе 2FA');
            }
        });
    }
}

// ===== Сброс пароля =====
async function resetAdminPassword() {
    if (confirm('Сбросить пароль администратора?')) {
        const sessionId = getCookie('session_id');
        const fingerprint = await collectFingerPrint();
        $.post("../../server/post/adminAdminsHandler.php", {
            type: "resetPassword",
            adminId: currentEditAdminId,
            session_id: sessionId,
            fingerprint: fingerprint
        }, function (data) {
            const response = JSON.parse(data);
            if (response.response && response.response.code === 200) {
                alert(`Пароль успешно сброшен! Обязательно запишите новый пароль для администратора: ${response.response.message}`);
                Toast.success('Пароль сброшен');
            } else {
                Toast.error('Ошибка при сбросе пароля');
            }
        });
    }
}

// ===== Удаление аккаунта =====
async function removeAdminAccount() {
    if (confirm('Удалить учётную запись администратора?')) {
        const sessionId = getCookie('session_id');
        const fingerprint = await collectFingerPrint();
        $.post("../../server/post/adminAdminsHandler.php", {
            type: "removeAccount",
            adminId: currentEditAdminId,
            session_id: sessionId,
            fingerprint: fingerprint
        }, function (data) {
            const response = JSON.parse(data);
            if (response.response && response.response.code === 200) {
                Toast.success('Аккаунт удалён.');
                document.getElementById(`adm_${currentEditAdminId}`).remove();
                adminCloseEdit();
                currentEditAdminId = null;
            } else {
                Toast.error('Ошибка при удалении аккаунта');
            }
        });
    }
}

function addAdminModal() {
    document.getElementById('adm_modal_add').classList.add('active');
    document.body.style.overflow = 'hidden';
}

async function adminSaveAdd() {
    const login = document.getElementById("adm_modal_open_login")?.value;
    if (login === undefined || login == null || login.trim() === '') {
        Toast.warning("Введите логин!");
        return;
    }
    const role = document.getElementById("adm_modal_add_role")?.value;
    if (role === undefined || role == null || role.trim() === '') {
        Toast.warning("Выберите роль!");
        return;
    }
    const roles = ['operator', 'manager', 'admin'];
    if (!roles.includes(role)) {
        Toast.warning("Обнаружено изменение страницы. Перезагрузите.");
        return;
    }
    const sessionId = getCookie('session_id');
    const fingerprint = await collectFingerPrint();
    $.post("../../server/post/adminAdminsHandler.php", {
        "type": "addAdmin",
        "login": login,
        "role": role,
        "session_id": sessionId,
        "fingerprint": fingerprint
    }, function (data) {
        try {
            var response = JSON.parse(data);
        } catch (e) {
            Toast.warning("Не удалось связаться с сервером.");
            return;
        }
        if (response.response) {
            alert(`Учётная запись ${login} создана. Временный пароль администратора: ${response.response.data.password}`);
            const newRow = createAdminRow(response.response.data.id, login, role);
            const tbody = document.getElementById("admin_table");
            if (tbody) {
                tbody.appendChild(newRow);
                const noDataRow = tbody.querySelector('.no-data-message');
                if (noDataRow) noDataRow.remove();
            }
            closeAdminAddModal();
            return;
        }else if (response.error) {
            Toast.error(`Ошибка [${response.error.code}]: ${response.error.message}`);
            return;
        }else{
            Toast.warning("Не удалось связаться с сервером.");
            return;
        }
    });
}

function adminCloseCreate() {
    if (confirm("Если вы закроете окно, ваши изменения не сохранятся. Вы уверены?")) {
        closeAdminAddModal();
    }
}

function closeAdminAddModal() {
    document.getElementById("adm_modal_add_role").selectedIndex = 0;
    document.getElementById("adm_modal_open_login").value = '';
    document.getElementById('adm_modal_add').classList.remove('active');
    document.body.style.overflow = 'auto';
}

function createAdminRow(adminId, login, role) {
    const tr = document.createElement('tr');
    tr.id = `adm_${adminId}`;
    const currentDate = getFormattedDate(new Date());
    tr.innerHTML = `
        <td>${adminId}</td>
        <td>${escapeHtml(login)}</td>
        <td>${getRoleBadge(role)}</td>
        <td>Нет данных</td>
        <td>${currentDate}</td>
        <td><span class="status active">Активен</span></td>
        <td>
            <button class="btn-reset-2fa" style="width: auto" onclick="adminEdit(${adminId})">Редактировать</button>
        </td>
    `;

    return tr;
}

// ===== ОБНОВЛЕНИЕ СТРОКИ В ТАБЛИЦЕ =====
function updateAdminRow(adminId, adminData) {
    const row = document.getElementById(`adm_${adminId}`);
    if (!row) return;

    const cells = row.cells;
    console.log(cells);
    if (cells.length >= 5) {
        cells[1].textContent = adminData.login;
        cells[2].innerHTML = getRoleBadge(adminData.role);
        cells[5].innerHTML = adminData.is_locked === 'blocked' ?
            '<span class="status inactive">Заблокирован</span>' :
            '<span class="status active">Активен</span>';
    }
}

function getRoleBadge(role) {
    const badges = {
        'admin': '<span class="role-badge admin">Администратор</span>',
        'manager': '<span class="role-badge manager">Менеджер</span>',
        'operator': '<span class="role-badge operator">Оператор</span>'
    };
    return badges[role] || badges.operator;
}
