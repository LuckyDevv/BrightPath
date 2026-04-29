<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.5, user-scalable=yes">
    <title>Вход в CRM - Светлый Путь</title>
    <link rel="stylesheet" href="css/login.css">
    <link rel="stylesheet" href="../src/css/toasts.css">
    <link rel="icon" href="../logo.png" type="image/png">
</head>
<body>
<div class="login-wrapper">
    <div class="login-container">
        <div class="login-card">
            <!-- Общий хедер -->
            <div class="login-header">
                <div class="logo-wrapper">
                    <img src="../logo_black.png" alt="Светлый Путь" class="login-logo">
                </div>
                <h1>Светлый Путь</h1>
                <p class="subtitle" id="pageSubtitle">CRM Админ-панель</p>
            </div>

            <!-- СТРАНИЦА 1: Вход (логин + пароль) -->
            <div id="page-login" class="auth-page active">
                <form class="login-form" id="loginForm">
                    <div class="form-group">
                        <label for="login">Логин</label>
                        <div class="input-wrapper">
                            <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" stroke="currentColor"/>
                                <circle cx="12" cy="7" r="4" stroke="currentColor"/>
                            </svg>
                            <input type="text" id="login" name="login" placeholder="Введите логин" autofocus>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="password">Пароль</label>
                        <div class="input-wrapper">
                            <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2" stroke="currentColor"/>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4" stroke="currentColor"/>
                            </svg>
                            <input type="password" id="password" name="password" placeholder="Введите пароль">
                            <button type="button" class="toggle-password" id="togglePasswordBtn"></button>
                        </div>
                    </div>

                    <button type="submit" class="login-btn">Войти в систему</button>

                    <div class="login-footer">
                        <div class="forgot-password">
                            <span>Если вы забыли пароль, обратитесь к администратору сайта для его восстановления</span>
                        </div>
                    </div>
                </form>

                <div class="login-info">
                    <div class="info-item">
                        <svg stroke="currentColor" stroke-width="1.5" width="18" height="18" viewBox="0 0 36 36">
                            <path stroke="currentColor" d="M16.43,16.69a7,7,0,1,1,7-7A7,7,0,0,1,16.43,16.69Zm0-11.92a5,5,0,1,0,5,5A5,5,0,0,0,16.43,4.77Z"/>
                            <path stroke="currentColor" d="M22,17.9A25.41,25.41,0,0,0,5.88,19.57a4.06,4.06,0,0,0-2.31,3.68V29.2a1,1,0,1,0,2,0V23.25a2,2,0,0,1,1.16-1.86,22.91,22.91,0,0,1,9.7-2.11,23.58,23.58,0,0,1,5.57.66Z"/>
                            <rect stroke="currentColor" x="22.14" y="27.41" width="6.14" height="1.4"/>
                            <path stroke="currentColor" d="M33.17,21.47H28v2h4.17v8.37H18V23.47h6.3v.42a1,1,0,0,0,2,0V20a1,1,0,0,0-2,0v1.47H17a1,1,0,0,0-1,1V32.84a1,1,0,0,0,1,1H33.17a1,1,0,0,0,1-1V22.47A1,1,0,0,0,33.17,21.47Z"/>
                        </svg>
                        <span>Доступ только для авторизованных сотрудников</span>
                    </div>
                    <div class="info-item">
                        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5">
                            <rect x="2" y="3" width="20" height="14" rx="2" ry="2" stroke="currentColor"/>
                            <line x1="8" y1="21" x2="16" y2="21" stroke="currentColor"/>
                            <line x1="12" y1="17" x2="12" y2="21" stroke="currentColor"/>
                        </svg>
                        <span>Все действия логируются</span>
                    </div>
                </div>
            </div>

            <!-- СТРАНИЦА 2: Настройка 2FA (если не включена) -->
            <div id="page-2fa-setup" class="auth-page">
                <div class="setup-steps">
                    <div class="step active" data-step="1">
                        <div class="step-number">1</div>
                        <div class="step-text">Установите приложение</div>
                    </div>
                    <div class="step" data-step="2">
                        <div class="step-number">2</div>
                        <div class="step-text">Отсканируйте QR-код</div>
                    </div>
                    <div class="step" data-step="3">
                        <div class="step-number">3</div>
                        <div class="step-text">Подтвердите код</div>
                    </div>
                </div>

                <div class="setup-content">
                    <!-- Шаг 1: Приложение -->
                    <div class="setup-step active" data-step="1">
                        <div class="app-options">
                            <h3>Установите приложение-аутентификатор</h3>
                            <div class="app-grid">
                                <a href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2&hl=ru" class="app-item">
                                    <svg stroke-width="2" width="40" height="40" viewBox="0 0 48 48" id="Layer_2" data-name="Layer 2" xmlns="http://www.w3.org/2000/svg"><defs><style>.cls-1{fill:none;stroke:#000000;stroke-linecap:round;stroke-linejoin:round;}</style></defs><path class="cls-1" d="M23.78,2.5h0A21.52,21.52,0,0,1,36,6.13l-5.63,8.41a11.39,11.39,0,1,0,3.85,14.52H24V18.94H45.5V24A21.5,21.5,0,1,1,23.24,2.51ZM24,5a2.53,2.53,0,1,0,2.53,2.53A2.53,2.53,0,0,0,24,5ZM7.56,21.47A2.53,2.53,0,1,0,10.09,24,2.53,2.53,0,0,0,7.56,21.47Zm32.88,0A2.53,2.53,0,1,0,43,24,2.53,2.53,0,0,0,40.44,21.47ZM24,37.91a2.53,2.53,0,1,0,2.53,2.53A2.53,2.53,0,0,0,24,37.91Z"/></svg>
                                    <span>Google Authenticator</span>
                                </a>
                                <a href="https://play.google.com/store/apps/details?id=com.azure.authenticator&hl=ru&ysclid=mo301sgo3k507793112" class="app-item">
                                    <svg width="40" height="40" viewBox="0 0 192 192" xmlns="http://www.w3.org/2000/svg" fill="none"><path stroke="#000000" stroke-linecap="round" stroke-linejoin="round" stroke-width="12" d="M96 62H68c-17.673 0-32.389 14.46-29.302 31.862C47.497 143.453 75.94 170 96 170m0-108h28c17.673 0 32.389 14.46 29.302 31.862C144.503 143.453 116.06 170 96 170"/><path fill="#000000" fill-rule="evenodd" d="M68 56c0-15.464 12.536-28 28-28s28 12.536 28 28c0 2.06-.222 4.067-.644 6h12.197c.294-1.957.447-3.96.447-6 0-22.091-17.909-40-40-40S56 33.909 56 56c0 2.04.153 4.043.447 6h12.197A28.105 28.105 0 0 1 68 56Zm-.367 98.946C70.417 141.836 82.06 132 96 132c12.903 0 23.838 8.427 27.601 20.077l8.982-9.608C125.816 129.136 111.975 120 96 120c-15.922 0-29.725 9.076-36.516 22.338L65.5 152l2.133 2.946Z" clip-rule="evenodd"/><circle cx="96" cy="94" r="14" stroke="#000000" stroke-width="12"/></svg>
                                    <span>Microsoft Authenticator</span>
                                </a>
                                <a href="https://play.google.com/store/apps/details?id=com.authy.authy&hl=ru&ysclid=mo302bfiy9577125664" class="app-item">
                                    <svg stroke-width="2.5" width="40" height="40" viewBox="0 0 48 48" id="Layer_2" data-name="Layer 2" xmlns="http://www.w3.org/2000/svg"><defs><style>.cls-1{fill:none;stroke:#000000;stroke-linecap:round;stroke-linejoin:round;}</style></defs><path class="cls-1" d="M31.76,8.91a2.38,2.38,0,0,1,1.7.7l6.5,6.5A12.14,12.14,0,0,1,40.19,33l-.11.13L40,33.2l0,.06-.13.12a12.15,12.15,0,0,1-16.91-.24l-6.49-6.49a2.41,2.41,0,0,1,3.4-3.41l6.49,6.5A7.23,7.23,0,1,0,36.55,19.51L30.06,13a2.4,2.4,0,0,1,1.7-4.1Zm-15.1,2.41a12.12,12.12,0,0,1,8.42,3.54l6.49,6.5a2.4,2.4,0,0,1-3.4,3.39l-6.49-6.49A7.23,7.23,0,1,0,11.45,28.49L17.94,35a2.4,2.4,0,0,1-3.4,3.4L8,31.9A12.15,12.15,0,0,1,7.81,15l.11-.13L8,14.8l0-.06.13-.11a12.09,12.09,0,0,1,8.49-3.31Z"/></svg>
                                    <span>Authy</span>
                                </a>
                                <a href="https://play.google.com/store/apps/details?id=ru.yandex.key&hl=ru&ysclid=mo3034n398446626528" class="app-item">
                                    <svg xmlns="http://www.w3.org/2000/svg" stroke-width="2.5" width="40" height="40" viewBox="0 0 48 48"><title>Yandex-key SVG Icon</title><circle cx="15.09" cy="15.804" r="3.798" fill="none" stroke="#000000" stroke-linecap="round" stroke-linejoin="round"/><path fill="none" stroke="#000000" stroke-linecap="round" stroke-linejoin="round" d="m43.435 36.763l-14.211-14.21c1.355-4.072.425-8.736-2.815-11.977c-4.57-4.569-11.978-4.569-16.547 0s-4.57 11.978 0 16.547c3.709 3.709 9.288 4.407 13.701 2.095l2.15 2.15l1.32-1.32l9.568 9.57l6.19.331z"/><path fill="none" stroke="#000000" stroke-linecap="round" stroke-linejoin="round" d="M42.791 39.949L29.935 27.092l-2.903 2.957"/></svg>
                                    <span>Yandex Key</span>
                                </a>
                            </div>
                            <button class="step-next" data-next-step="2">Продолжить →</button>
                        </div>
                    </div>

                    <!-- Шаг 2: QR-код -->
                    <div class="setup-step" data-step="2">
                        <div class="qr-section" id="qrSection">
                            <div class="qr-code">
                                <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 200 200'%3E%3Crect width='200' height='200' fill='%23fafafa'/%3E%3Crect x='40' y='40' width='30' height='30' fill='%230a0a0a'/%3E%3Crect x='80' y='40' width='30' height='30' fill='%230a0a0a'/%3E%3Crect x='120' y='40' width='30' height='30' fill='%230a0a0a'/%3E%3Crect x='40' y='80' width='30' height='30' fill='%230a0a0a'/%3E%3Crect x='120' y='80' width='30' height='30' fill='%230a0a0a'/%3E%3Crect x='80' y='120' width='30' height='30' fill='%230a0a0a'/%3E%3Crect x='120' y='120' width='30' height='30' fill='%230a0a0a'/%3E%3Crect x='40' y='160' width='30' height='30' fill='%230a0a0a'/%3E%3Crect x='80' y='160' width='30' height='30' fill='%230a0a0a'/%3E%3C/svg%3E" alt="QR-код" id="qr_img">
                            </div>
                            <p class="qr-hint">Отсканируйте QR-код приложением-аутентификатором</p>
                            <div class="manual-toggle">
                                <button class="btn-manual" id="showManualBtn">Добавить вручную →</button>
                            </div>
                        </div>
                        <div class="manual-section" id="manualSection" style="display: none;">
                            <h4>Введите код вручную</h4>
                            <div class="manual-data">
                                <div class="manual-item">
                                    <span class="manual-label">Аккаунт:</span>
                                    <span class="manual-value" id="accountSpan">admin@svetlyput.ru</span>
                                </div>
                                <div class="manual-item">
                                    <span class="manual-label">Ключ:</span>
                                    <span class="manual-value secret-key" id="secretSpan"></span>
                                    <button class="btn-copy" onclick="copySecret()">📋</button>
                                </div>
                            </div>
                            <div class="manual-toggle">
                                <button class="btn-manual" id="showQrBtn">Отсканировать QR-код →</button>
                            </div>
                        </div>
                        <button class="step-next" data-next-step="3">Продолжить →</button>
                    </div>

                    <!-- Шаг 3: Подтверждение -->
                    <div class="setup-step" data-step="3">
                        <div class="code-input-wrapper">
                            <label>Введите код из приложения</label>
                            <div class="code-inputs" id="setupCodeInputs">
                                <input type="text" maxlength="1" class="code-digit enable-2fa-input" data-index="0">
                                <input type="text" maxlength="1" class="code-digit enable-2fa-input" data-index="1">
                                <input type="text" maxlength="1" class="code-digit enable-2fa-input" data-index="2">
                                <input type="text" maxlength="1" class="code-digit enable-2fa-input" data-index="3">
                                <input type="text" maxlength="1" class="code-digit enable-2fa-input" data-index="4">
                                <input type="text" maxlength="1" class="code-digit enable-2fa-input" data-index="5">
                            </div>
                        </div>

                        <div class="verify-actions">
                            <button class="btn-verify" id="finish2FASetup">Войти</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- СТРАНИЦА 3: Проверка 2FA (если уже включена) -->
            <div id="page-2fa-verify" class="auth-page">
                <div class="verify-content">
                    <div class="auth-icon">
                        <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="#d4a373" stroke-width="1.5">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2" stroke="currentColor"/>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4" stroke="currentColor"/>
                            <circle cx="12" cy="16" r="1.5" fill="#d4a373"/>
                        </svg>
                    </div>

                    <div class="code-input-wrapper">
                        <label>Код подтверждения</label>
                        <div class="code-inputs" id="verifyCodeInputs">
                            <input type="text" maxlength="1" class="code-digit verify-2fa-input" data-index="0">
                            <input type="text" maxlength="1" class="code-digit verify-2fa-input" data-index="1">
                            <input type="text" maxlength="1" class="code-digit verify-2fa-input" data-index="2">
                            <input type="text" maxlength="1" class="code-digit verify-2fa-input" data-index="3">
                            <input type="text" maxlength="1" class="code-digit verify-2fa-input" data-index="4">
                            <input type="text" maxlength="1" class="code-digit verify-2fa-input" data-index="5">
                        </div>
                    </div>

                    <div class="verify-actions">
                        <button class="btn-verify" id="verify2FACode">Войти</button>
                        <button class="btn-back" id="backToLogin">← Вернуться</button>
                    </div>

                    <div class="verify-footer">
                        <p class="info-text">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <circle cx="12" cy="12" r="10" stroke="currentColor"/>
                                <path d="M12 8v4M12 16h.01" stroke="currentColor"/>
                            </svg>
                            Откройте приложение-аутентификатор на вашем телефоне
                        </p>
                        <a href="#" class="recovery-link">Не получается войти? Восстановить доступ</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="login-decoration">
            <div class="decoration-shape shape-1"></div>
            <div class="decoration-shape shape-2"></div>
            <div class="decoration-shape shape-3"></div>
        </div>
    </div>

    <div class="login-copyright">
        © 2026 Ритуальное агентство «Светлый Путь». Курсовой проект по разработке информационных систем.
    </div>
</div>
<!-- Контейнер для уведомлений -->
<div id="toast-container" class="toast-container"></div>
<script src="../src/js/jquery.min.js"></script>
<script src="../src/js/fingerprint.js"></script>
<script src="../src/js/local_storage.js"></script>
<script src="../src/js/toasts.js"></script>
<script src="js/session-checker.js"></script>
<script src="js/auth.js"></script>
</body>
</html>