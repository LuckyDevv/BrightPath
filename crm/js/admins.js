// ===== ПЕРЕМЕННЫЕ =====
let currentEditAdminId = null;
let currentEditAdminStatus = 'active';

// ===== ОТКРЫТИЕ МОДАЛКИ РЕДАКТИРОВАНИЯ =====
function adminEdit(adminId) {
    $.post("../../server/post/adminAdminsHandler.php", { type: "getById", adminId: adminId }, function(data) {
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
function adminSaveEdit() {
    const adminData = {
        adminId: currentEditAdminId,
        login: document.getElementById("adm_modal_edit_login").value,
        role: document.getElementById('adm_modal_edit_role').value,
        is_locked: document.getElementById('adm_modal_edit_status').value === 'blocked'
    };

    $.post("../../server/post/adminAdminsHandler.php", {
        type: "updateAdmin",
        adminData: JSON.stringify(adminData)
    }, function(data) {
        const response = JSON.parse(data);
        if (response.response && response.response.code === 200) {
            Toast.success('Данные администратора обновлены');
            adminCloseEdit();
            // Обновляем строку в таблице
            updateAdminRow(currentEditAdminId, adminData);
        } else {
            Toast.error('Ошибка: ' + (response.error?.message || 'Не удалось обновить'));
        }
    });
}

// ===== СБРОС 2FA =====
function resetAdmin2FA() {
    if (confirm('Сбросить двухфакторную аутентификацию для этого администратора? Ему потребуется настроить 2FA заново при следующем входе.')) {
        $.post("../../server/post/adminAdminsHandler.php", {
            type: "reset2FA",
            adminId: currentEditAdminId
        }, function(data) {
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
function resetAdminPassword() {
    if (confirm('Сбросить пароль администратора?')) {
        $.post("../../server/post/adminAdminsHandler.php", {
            type: "resetPassword",
            adminId: currentEditAdminId
        }, function(data) {
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
function removeAdminAccount() {
    if (confirm('Удалить учётную запись администратора?')) {
        $.post("../../server/post/adminAdminsHandler.php", {
            type: "removeAccount",
            adminId: currentEditAdminId
        }, function(data) {
            const response = JSON.parse(data);
            if (response.response && response.response.code === 200) {
                Toast.success('Аккаунт удалён.');
                adminCloseEdit();
                document.getElementById(`adm_${currentEditAdminId}`).remove();
                currentEditAdminId = null;
            } else {
                Toast.error('Ошибка при удалении аккаунта');
            }
        });
    }
}

// ===== ОБНОВЛЕНИЕ СТРОКИ В ТАБЛИЦЕ =====
function updateAdminRow(adminId, adminData) {
    const row = document.getElementById(`tr_${adminId}`);
    if (!row) return;

    const cells = row.cells;
    if (cells.length >= 5) {
        cells[1].textContent = adminData.login;
        cells[2].innerHTML = getRoleBadge(adminData.role);
        cells[3].innerHTML = adminData.is_locked ?
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