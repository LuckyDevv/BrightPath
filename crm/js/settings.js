

function initSettings() {
    loadAccountInfo();

    // Слушатели вкладок
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const tab = this.dataset.tab;
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            document.querySelectorAll('.tab-pane').forEach(p => p.classList.remove('active'));
            this.classList.add('active');
            document.getElementById(`tab-${tab}`).classList.add('active');
        });
    });

    // Кнопки аккаунта
    document.getElementById('reset2FABtn')?.addEventListener('click', async () => {
        if (confirm('Вы уверены, что хотите сбросить двухфакторную аутентификацию?')) {
            const sessionId = getCookie('session_id');
            const fingerprint = await collectFingerPrint();
            $.post("../../server/post/adminSettingsHandler.php", {
                "type": "reset2fa",
                "session_id": sessionId,
                "fingerprint": fingerprint
            }, function (data) {
                try {
                    var response = JSON.parse(data);
                }catch(e){
                    Toast.error("Не удалось связаться с сервером.");
                    return;
                }
                if (response.response) {
                    Toast.success("2FA сброшена. Настройте её при следующем входе.");
                }else if (response.error) {
                    Toast.error(`Ошибка [${response.error.code}]: ${response.error.message}`);
                }else{
                    Toast.error("Не удалось связаться с сервером.");
                }
            })
        }
    });

    document.getElementById('changePasswordBtn')?.addEventListener('click', () => {
        document.getElementById('passwordModal').classList.add('active');
        document.body.style.overflow = 'hidden';
    });

    document.getElementById('killAllSessionsBtn')?.addEventListener('click', async () => {
        if (confirm('Завершить все сессии? На всех устройствах, кроме текущего, нужно будет заново совершить вход.')) {
            const sessionId = getCookie('session_id');
            const fingerprint = await collectFingerPrint();
            $.post("../../server/post/adminSettingsHandler.php", {
                "type": "killAllSessions",
                "session_id": sessionId,
                "fingerprint": fingerprint
            }, function (data) {
                try {
                    var response = JSON.parse(data);
                }catch(e){
                    Toast.error("Не удалось связаться с сервером.");
                    return;
                }
                if (response.response) {
                    Toast.success('Все сессии (кроме текущей) завершены');
                    document.getElementById('account_sessions_count').textContent = 1;
                }else if (response.error) {
                    Toast.error(`Ошибка [${response.error.code}]: ${response.error.message}`);
                }else{
                    Toast.error("Не удалось связаться с сервером.");
                }
            });
        }
    });

    // Смена пароля
    document.getElementById('passwordForm')?.addEventListener('submit', async (e) => {
        e.preventDefault();
        const oldPass = document.getElementById('old_password').value;
        const newPass = document.getElementById('new_password').value;
        const confirmPass = document.getElementById('confirm_password').value;

        if (newPass !== confirmPass) {
            Toast.error('Новые пароли не совпадают');
            return;
        }
        const valid = validatePassword(newPass);
        if (!valid.valid) {
            Toast.error(valid.message);
            return;
        }
        const sessionId = getCookie('session_id');
        const fingerprint = await collectFingerPrint();
        $.post("../../server/post/adminSettingsHandler.php", {
            "type": "changePassword",
            "oldPassword": oldPass,
            "newPassword": newPass,
            "session_id": sessionId,
            "fingerprint": fingerprint
        }, function (data) {
            try {
                var response = JSON.parse(data);
            }catch(e){
                Toast.error("Не удалось связаться с сервером!");
                return;
            }
            if (response.response) {
                Toast.success('Пароль изменён!');
                document.getElementById('account_password_updated').textContent = getFormattedDate(new Date());
            }else if (response.error) {
                Toast.error(`Ошибка [${response.error.code}]: ${response.error.message}`);
            }else{
                Toast.error("Не удалось связаться с сервером.");
            }
        });
        closePasswordModal();
    });
}

// ===== АККАУНТ (заглушка, потом подключишь к API) =====
async function loadAccountInfo() {
    const sessionId = getCookie('session_id');
    const fingerprint = await collectFingerPrint();
    $.post("../../server/post/adminSettingsHandler.php", {
        "type": "loadAccountInfo",
        "session_id": sessionId,
        "fingerprint": fingerprint
    }, function (data) {
        try {
            var response = JSON.parse(data);
        }catch(e){
            Toast.error("Не удалось загрузить данные");
            return;
        }
        if (response.response) {
            console.log(response.response.data);
            document.getElementById('account_login').textContent = response.response.data.login;
            document.getElementById('account_role').textContent = getRoleText(response.response.data.role);
            document.getElementById('account_status').innerHTML = getStatusBadge(response.response.data.is_locked);
            document.getElementById('account_last_login').textContent = response.response.data.last_login_at;
            document.getElementById('account_password_updated').textContent = response.response.data.password_updated_at;
            document.getElementById('account_sessions_count').textContent = response.response.data.sessions_count;
        }
    })
}

function getRoleText(role) {
    const roles = {
        "operator": "Оператор",
        "manager": "Менеджер",
        "admin": "Администратор"
    }
    return roles[role] || "Неизвестно";
}

function getStatusBadge(status) {
    const statuses = {
        0: "<span class=\"status active\">Активен</span>",
        1: "<span class=\"status inactive\">Заблокирован</span>"
    }
    return statuses[status] || "Неизвестно";
}

function closePasswordModal() {
    document.getElementById('passwordModal').classList.remove('active');
    document.getElementById('passwordForm').reset();
    document.body.style.overflow = 'auto';
}

function validatePassword(password) {
    // 1. Не менее 12 символов
    if (password.length < 12) {
        return { valid: false, message: "Пароль должен содержать не менее 12 символов" };
    }
    // 2. Не менее 2 заглавные буквы
    const upperCaseCount = (password.match(/[A-ZА-Я]/g) || []).length;
    if (upperCaseCount < 2) {
        return { valid: false, message: "Пароль должен содержать не менее 2 заглавных букв" };
    }
    // 3. Не менее 2 обычные буквы (строчные)
    const lowerCaseCount = (password.match(/[a-zа-я]/g) || []).length;
    if (lowerCaseCount < 2) {
        return { valid: false, message: "Пароль должен содержать не менее 2 строчных букв" };
    }
    // 4. Не менее 2 цифры
    const digitCount = (password.match(/\d/g) || []).length;
    if (digitCount < 2) {
        return { valid: false, message: "Пароль должен содержать не менее 2 цифр" };
    }
    // 5. Не менее 2 спец.символа
    const specialChars = /[@#$!_&^%*()]/g;
    const specialCount = (password.match(specialChars) || []).length;
    if (specialCount < 2) {
        return { valid: false, message: "Пароль должен содержать не менее 2 специальных символов (@, #, $, !, _, &, ^, %, *, (, ))" };
    }
    return { valid: true, message: "Пароль надёжный" };
}

// ===== ВСПОМОГАТЕЛЬНЫЕ =====
function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}
