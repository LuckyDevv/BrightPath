<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.5, user-scalable=yes">
    <title>CRM Админ-панель - Светлый Путь</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" href="../logo5.png" type="image/png">
</head>
<body>
<div class="admin-wrapper">
    <!-- Боковое меню -->
    <aside class="admin-sidebar">
        <div class="sidebar-logo">
            <img src="../logo5.png" alt="Светлый Путь">
            <span>CRM Админ</span>
        </div>
        <nav class="sidebar-nav">
            <a href="#" class="nav-item active" data-page="dashboard">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="3" width="7" height="9" stroke="currentColor"/>
                    <rect x="14" y="3" width="7" height="5" stroke="currentColor"/>
                    <rect x="14" y="12" width="7" height="9" stroke="currentColor"/>
                    <rect x="3" y="16" width="7" height="5" stroke="currentColor"/>
                </svg>
                <span>Дашборд</span>
            </a>
            <a href="#" class="nav-item" data-page="orders">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M6 2 L6 7 M18 2 L18 7" stroke="currentColor"/>
                    <rect x="3" y="4" width="18" height="18" rx="2" stroke="currentColor"/>
                    <path d="M8 12 L16 12" stroke="currentColor"/>
                    <path d="M8 16 L13 16" stroke="currentColor"/>
                </svg>
                <span>Заказы</span>
            </a>
            <a href="#" class="nav-item" data-page="clients">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" stroke="currentColor"/>
                    <circle cx="12" cy="7" r="4" stroke="currentColor"/>
                </svg>
                <span>Клиенты</span>
            </a>
            <a href="#" class="nav-item" data-page="vehicles">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="2" y="7" width="20" height="12" rx="2" stroke="currentColor"/>
                    <circle cx="7" cy="17" r="2" stroke="currentColor"/>
                    <circle cx="17" cy="17" r="2" stroke="currentColor"/>
                </svg>
                <span>Автопарк</span>
            </a>
            <a href="#" class="nav-item" data-page="goods">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="2" y="7" width="20" height="14" rx="2" stroke="currentColor"/>
                    <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16" stroke="currentColor"/>
                </svg>
                <span>Товары</span>
            </a>
            <a href="#" class="nav-item" data-page="agents">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="8" r="4" stroke="currentColor"/>
                    <path d="M5 20v-2a7 7 0 0 1 14 0v2" stroke="currentColor"/>
                </svg>
                <span>Агенты</span>
            </a>
            <a href="#" class="nav-item" data-page="admins">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 2 L15 8 L22 9 L17 14 L18 21 L12 17 L6 21 L7 14 L2 9 L9 8 L12 2Z" stroke="currentColor"/>
                </svg>
                <span>Администраторы</span>
            </a>
            <a href="#" class="nav-item" data-page="settings">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="3" stroke="currentColor"/>
                    <path d="M19.4 15a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H5.78a1.65 1.65 0 0 0-1.51 1 1.65 1.65 0 0 0 .33 1.82l.04.04A10 10 0 0 0 12 17.66a10 10 0 0 0 6.36-2.62l.04-.04z" stroke="currentColor"/>
                </svg>
                <span>Настройки</span>
            </a>
        </nav>
        <div class="sidebar-footer">
            <a href="../index.php" class="nav-item" target="_blank">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M3 9L12 3L21 9L12 15L3 9Z" stroke="currentColor"/>
                    <path d="M12 15L12 21" stroke="currentColor"/>
                </svg>
                <span>На сайт</span>
            </a>
            <a href="#" class="nav-item logout">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" stroke="currentColor"/>
                    <polyline points="16 17 21 12 16 7" stroke="currentColor"/>
                    <line x1="21" y1="12" x2="9" y2="12" stroke="currentColor"/>
                </svg>
                <span>Выход</span>
            </a>
        </div>
    </aside>

    <!-- Основной контент -->
    <main class="admin-content">
        <header class="admin-header">
            <div class="header-title">
                <h1>Дашборд</h1>
                <p>Общая статистика за сегодня</p>
            </div>
            <div class="header-user">
                <span class="user-name">Администратор</span>
                <div class="user-avatar">А</div>
            </div>
        </header>

        <div class="content-wrapper" id="contentWrapper">
            <!-- Контент будет загружаться сюда -->
        </div>
    </main>
</div>
<script src="../src/js/jquery.min.js"></script>
<script src="js/vehicles.js"></script>
<script src="js/script.js"></script>
</body>
</html>