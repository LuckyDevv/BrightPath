// ===== ОБЩИЕ ПЕРЕМЕННЫЕ =====
let currentPage = 'login';
let tempSecret = null;
let tempLogin = null;
let tempPassword = null;

// ===== ФУНКЦИЯ ПЕРЕКЛЮЧЕНИЯ СТРАНИЦ =====
function switchPage(page) {
    document.querySelectorAll('.auth-page').forEach(p => p.classList.remove('active'));
    document.getElementById(`page-${page}`).classList.add('active');
    currentPage = page;

    // Обновляем подзаголовок
    const subtitle = document.getElementById('pageSubtitle');
    if (page === 'login') {
        subtitle.textContent = 'CRM Админ-панель';
    } else if (page === '2fa-setup') {
        subtitle.textContent = 'Настройка двухфакторной аутентификации';
    } else if (page === '2fa-verify') {
        subtitle.textContent = 'Подтверждение входа';
    }
}

// ===== ИНИЦИАЛИЗАЦИЯ ПЕРЕКЛЮЧЕНИЯ ПАРОЛЯ =====
function initPasswordToggle() {
    const passwordInput = document.getElementById('password');
    const toggleBtn = document.getElementById('togglePasswordBtn');

    if (!passwordInput || !toggleBtn) return;

    toggleBtn.innerHTML = `
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" stroke="currentColor"/>
                <circle cx="12" cy="12" r="3" stroke="currentColor"/>
            </svg>
        `;

    toggleBtn.addEventListener('click', function() {
        const isPassword = passwordInput.getAttribute('type') === 'password';
        if (isPassword) {
            passwordInput.setAttribute('type', 'text');
            toggleBtn.innerHTML = `
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" stroke="currentColor"/>
                        <circle cx="12" cy="12" r="3" stroke="currentColor"/>
                        <line x1="3" y1="3" x2="21" y2="21" stroke="currentColor" stroke-width="1.8"/>
                    </svg>
                `;
        } else {
            passwordInput.setAttribute('type', 'password');
            toggleBtn.innerHTML = `
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" stroke="currentColor"/>
                        <circle cx="12" cy="12" r="3" stroke="currentColor"/>
                    </svg>
                `;
        }
    });
}

// ===== ЗАПРЕТ ПРОБЕЛОВ =====
function preventSpaces(inputElement) {
    inputElement.addEventListener('input', function() {
        if (this.value.includes(' ')) {
            this.value = this.value.replace(/\s/g, '');
            showHint(this, 'Пробелы запрещены');
        }
    });
    inputElement.addEventListener('paste', function(e) {
        const pastedText = (e.clipboardData || window.clipboardData).getData('text');
        if (pastedText.includes(' ')) {
            e.preventDefault();
            this.value = pastedText.replace(/\s/g, '');
            showHint(this, 'Пробелы удалены');
        }
    });
}

// ===== ПОДСКАЗКА =====
let hintTimeout;
function showHint(element, message) {
    const existingHint = element.parentNode.querySelector('.input-hint');
    if (existingHint) existingHint.remove();

    const hint = document.createElement('div');
    hint.className = 'input-hint';
    hint.textContent = message;
    hint.style.cssText = `
            position: absolute; right: 0; top: -28px; font-size: 0.7rem;
            color: #d4a373; background: #fff; padding: 4px 10px;
            border-radius: 20px; white-space: nowrap; box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            z-index: 10; animation: fadeInOut 2s ease forwards;
        `;
    element.parentNode.style.position = 'relative';
    element.parentNode.appendChild(hint);
    setTimeout(() => hint.remove(), 2000);
}

// ===== НАСТРОЙКА ШАГОВ 2FA =====
function init2FASteps() {
    let currentStep = 1;
    const steps = document.querySelectorAll('.setup-step');
    const stepIndicators = document.querySelectorAll('.step');

    function goToStep(step) {
        steps.forEach(s => s.classList.remove('active'));
        stepIndicators.forEach(i => i.classList.remove('active'));
        document.querySelector(`.setup-step[data-step="${step}"]`).classList.add('active');
        document.querySelector(`.step[data-step="${step}"]`).classList.add('active');
        currentStep = step;
    }

    document.querySelectorAll('.step-next').forEach(btn => {
        btn.addEventListener('click', () => {
            const nextStep = parseInt(btn.getAttribute('data-next-step'));
            if (nextStep) goToStep(nextStep);
        });
    });
}

// ===== 6-ЗНАЧНЫЙ КОД (ПОЛЯ) =====
function initCodeInputs() {
    const digits = document.querySelectorAll('.code-digit');
    if (!digits.length) return;

    digits.forEach((input, index) => {
        input.addEventListener('input', (e) => {
            if (e.target.value.length === 1 && index < digits.length - 1) {
                digits[index + 1].focus();
            }
        });
        input.addEventListener('keydown', (e) => {
            if (e.key === 'Backspace' && index > 0 && !e.target.value) {
                digits[index - 1].focus();
            }
        });
    });
}

// ===== ОБРАБОТЧИК ЛОГИНА =====
function initLoginHandler() {
    document.getElementById('loginForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const login = document.getElementById('login').value;
        const password = document.getElementById('password').value;

        $.post("../server/post/adminAuthHandler.php", {
            "type": "login",
            "login": login,
            "password": password
        }, function(data) {
            const response = JSON.parse(data);
            console.log(response);

            if (response.response && response.response.code === 200) {
                tempLogin = login;
                tempPassword = password;
                if (response.response.message === "NEED_2FA") {
                    // Нужно настроить 2FA
                    let qrImg = document.getElementById("qr_img");
                    let accountSpan = document.getElementById("accountSpan");
                    let secretSpan = document.getElementById("secretSpan");
                    if (qrImg && accountSpan && secretSpan) {
                        const responseData = response.response.data;
                        qrImg.src = responseData.qr;
                        accountSpan.innerHTML = login;
                        secretSpan.innerHTML = responseData.secret;
                        tempSecret = responseData.secret;
                    }
                    switchPage('2fa-setup');
                    setTimeout(() => initCodeInputs(), 100);
                    setTimeout(() => init2FASteps(), 100);
                } else if (response.response.message === "OK") {
                    const responseData = response.response.data;
                    tempSecret = responseData.secret;
                    switchPage('2fa-verify');
                    setTimeout(() => initCodeInputs(), 100);
                } else {
                    showHint(document.getElementById('password'), response.error.message || 'Ошибка входа');
                }
            } else if (response.error) {
                showHint(document.getElementById('password'), response.error.message || 'Ошибка входа');
            }
        }).fail(function() {
            showHint(document.getElementById('password'), 'Ошибка подключения к серверу');
        });
    });
}

// ===== ЗАВЕРШЕНИЕ НАСТРОЙКИ 2FA =====
function init2FASetupHandler() {
    document.getElementById('finish2FASetup')?.addEventListener('click',  async function () {
        let setupCodeInputs = document.getElementById("setupCodeInputs").childNodes;
        let code = '';
        document.querySelectorAll('.enable-2fa-input').forEach(input => {
            code += input.value;
        });
        console.log(code);
        if (code.length !== 6) {
            showHint(document.getElementById('setupCodeInputs'), 'Введите 6-значный код');
            return;
        }
        $.post("../server/post/adminAuthHandler.php", {
            "type": "enable_2fa",
            "secret": tempSecret,
            "code": code,
            "login": tempLogin,
            "password": tempPassword,
            "fingerprint": await collectFingerPrint()
        }, function (data) {
            const response = JSON.parse(data);
            if (response.response && response.response.code === 200) {
                window.location.href = 'index.php';
            } else if (response.error) {
                showHint(document.getElementById('setupCode'), response.error.message || 'Неверный код');
            }
        });
    });
}

// ===== ПРОВЕРКА 2FA КОДА =====
function init2FAVerifyHandler() {
    document.getElementById('verify2FACode')?.addEventListener('click',  async function () {
        let code = '';
        document.querySelectorAll('.verify-2fa-input').forEach(input => {
            code += input.value;
        });
        if (code.length !== 6) {
            alert('Введите 6-значный код');
            return;
        }
        $.post("../server/post/adminAuthHandler.php", {
            "type": "verify_2fa",
            "secret": tempSecret,
            "code": code,
            "login": tempLogin,
            "password": tempPassword,
            "fingerprint": await collectFingerPrint()
        }, async function (data) {
            const response = JSON.parse(data);
            if (response.response && response.response.code === 200) {
                window.location.href = 'index.php';
            } else if (response.error) {
                showHint(document.getElementById("verifyCodeInputs"), response.error.message || 'Неверный код');
            }
        });
    });
}

// ===== КНОПКА НАЗАД =====
function initBackButton() {
    document.getElementById('backToLogin')?.addEventListener('click', function() {
        switchPage('login');
    });
}

// ===== РУЧНОЙ ВВОД КЛЮЧА =====
function initManualKey() {
    const showManualBtn = document.getElementById('showManualBtn');
    const showQrBtn = document.getElementById("showQrBtn");
    const manualSection = document.getElementById('manualSection');
    const qrSection = document.getElementById("qrSection");
    if (showManualBtn && manualSection && qrSection && showQrBtn) {
        showManualBtn.addEventListener('click', () => {
            manualSection.style.display = 'block';
            qrSection.style.display = 'none';
        });
        showQrBtn.addEventListener("click", () => {
            manualSection.style.display = 'none';
            qrSection.style.display = 'block';
        })
    }
}

window.copySecret = function() {
    const secretText = document.querySelector('.secret-key');
    const range = document.createRange();
    range.selectNode(secretText);
    window.getSelection().removeAllRanges();
    window.getSelection().addRange(range);
    document.execCommand('copy');
    window.getSelection().removeAllRanges();
    alert('Ключ скопирован в буфер обмена');
};

// ===== ИНИЦИАЛИЗАЦИЯ =====
document.addEventListener('DOMContentLoaded', async function () {
    initPasswordToggle();
    const loginInput = document.getElementById('login');
    const passwordInput = document.getElementById('password');
    if (loginInput) preventSpaces(loginInput);
    if (passwordInput) preventSpaces(passwordInput);
    initLoginHandler();
    init2FASetupHandler();
    init2FAVerifyHandler();
    initBackButton();
    initManualKey();
});