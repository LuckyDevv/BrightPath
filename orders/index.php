<?php
include_once __DIR__ . "/../vendor/autoload.php";
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

$loader = new FilesystemLoader('../server/twig');
$twig = new Environment($loader, [
    //'cache' => '../server/twig/cache',
        'autoescape' => false,
]);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.5, user-scalable=yes">
    <title>Проверка заказа - Светлый Путь</title>
    <link rel="stylesheet" href="../src/css/index.css">
    <link rel="stylesheet" href="../src/css/nav.css">
    <link rel="stylesheet" href="../src/css/footer.css">
    <link rel="stylesheet" href="../src/css/modal.css">
    <link rel="stylesheet" href="src/css/style.css">
    <link rel="stylesheet" href="../src/css/toasts.css">
    <link rel="icon" href="../logo.png" type="image/png">
</head>
<body>
<!-- Хедер -->
<header class="header">
    <div class="container">
        <div class="logo-container">
            <img src="../logo.png" alt="Светлый Путь" class="logo-img">
            <span class="logo-text">Светлый Путь</span>
        </div>
        <button class="burger" id="burger">
            <span></span>
            <span></span>
            <span></span>
        </button>
        <nav class="nav" id="nav">
            <a href="../index.php">Главная</a>
            <a href="../goods/">Товары</a>
            <a href="../services/">Услуги</a>
            <a href="../agents/">Агенты</a>
            <a href="../calculator/">Калькулятор</a>
            <a href="../autopark/">Автопарк</a>
            <a href="../orders/" class="active">Заказы</a>
            <a href="#" class="btn-light" onclick="openModal()">Связаться</a>
        </nav>
    </div>
    <div class="overlay" id="overlay"></div>
</header>

<main>
    <!-- Заголовок -->
    <section class="page-header">
        <div class="container">
            <h1>Проверка статуса заказа</h1>
            <div class="section-divider"></div>
            <p class="subtitle">Введите данные вашего заказа</p>
        </div>
    </section>

    <!-- Форма проверки -->
    <section class="track-section">
        <div class="container">
            <div class="track-card">
                <form class="track-form" id="trackForm">
                    <div class="form-group">
                        <label for="orderId">Номер заказа</label>
                        <div class="input-wrapper">
                            <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <rect x="3" y="4" width="18" height="16" rx="2" ry="2" stroke="currentColor"/>
                                <line x1="16" y1="2" x2="16" y2="6" stroke="currentColor"/>
                                <line x1="8" y1="2" x2="8" y2="6" stroke="currentColor"/>
                                <line x1="3" y1="10" x2="21" y2="10" stroke="currentColor"/>
                            </svg>
                            <input type="text" id="orderId" placeholder="Введите номер заказа (например: 1245)">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="orderEmail">Email</label>
                        <div class="input-wrapper">
                            <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <rect x="2" y="4" width="20" height="16" rx="2" ry="2" stroke="currentColor"/>
                                <polyline points="22,6 12,13 2,6" stroke="currentColor"/>
                            </svg>
                            <input type="text" id="orderEmail" placeholder="Введите email, указанный при заказе">
                        </div>
                    </div>

                    <button type="submit" class="btn-track">Проверить заказ</button>
                </form>
            </div>

            <!-- Результат проверки -->
            <div class="track-result" id="trackResult" style="display: none;">
                <div class="result-card">
                    <div class="result-header">
                        <h2>Заказ №<span id="resultOrderId"></span></h2>
                        <div class="order-status" id="orderStatus"></div>
                    </div>

                    <div class="order-date">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <circle cx="12" cy="12" r="10" stroke="currentColor"/>
                            <polyline points="12 6 12 12 16 14" stroke="currentColor"/>
                        </svg>
                        <span>Дата заказа: <strong id="orderDate"></strong></span>
                    </div>

                    <!-- Состав заказа -->
                    <div class="order-items">
                        <h3>Состав заказа</h3>

                        <div class="items-category" id="transportItems" style="display: none;">
                            <h4>🚗 Транспорт</h4>
                            <div class="items-list" id="transportList"></div>
                        </div>

                        <div class="items-category" id="goodsItems" style="display: none;">
                            <h4>⚰️ Ритуальные товары</h4>
                            <div class="items-list" id="goodsList"></div>
                        </div>

                        <div class="items-category" id="servicesItems" style="display: none;">
                            <h4>🪦 Ритуальные услуги</h4>
                            <div class="items-list" id="servicesList"></div>
                        </div>

                        <div class="items-category" id="agentsItems" style="display: none;">
                            <h4>👥 Агенты, выполнившие заказ</h4>
                            <div class="items-list" id="agentsList"></div>
                        </div>
                    </div>

                    <!-- Итоговая сумма -->
                    <div class="order-total">
                        <span>Итого к оплате:</span>
                        <span class="total-amount" id="orderTotal">0 ₽</span>
                    </div>

                    <!-- Контактная информация -->
                    <div class="order-contact">
                        <p><strong>Контактная информация:</strong></p>
                        <p>📞 Телефон: <span id="orderPhone"></span></p>
                        <p>✉️ Email: <span id="orderEmailResult"></span></p>
                    </div>
                </div>
            </div>

            <!-- Сообщение об ошибке -->
            <div class="track-error" id="trackError" style="display: none;">
                <div class="error-card">
                    <svg width="64" height="64" fill="#000000" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg">
                        <path d="M0 14.545L1.455 16 8 9.455 14.545 16 16 14.545 9.455 8 16 1.455 14.545 0 8 6.545 1.455 0 0 1.455 6.545 8z" fill-rule="evenodd"/>
                    </svg>
                    <h3>Заказ не найден</h3>
                    <p>Проверьте правильность введённых данных и попробуйте снова.</p>
                </div>
            </div>

            <!-- Загрузка -->
            <div class="track-loading" id="trackLoading" style="display: none;">
                <div class="spinner"></div>
                <p>Поиск заказа...</p>
            </div>
        </div>
    </section>
</main>
<?php echo $twig->render('page_end.twig', ['basePath' => '../', "config" => new \lib\Config()->getConfig()]); ?>
<script src="../src/js/jquery.min.js"></script>
<script src="../src/js/toasts.js"></script>
<script src="../src/js/modal.js"></script>
<script src="src/js/script.js"></script>
</body>
</html>