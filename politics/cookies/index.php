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
    <title>Политика использования файлов cookie - Светлый Путь</title>
    <link rel="stylesheet" href="../src/css/style.css">
    <link rel="stylesheet" href="../../src/css/index.css">
    <link rel="stylesheet" href="../../src/css/nav.css">
    <link rel="stylesheet" href="../../src/css/footer.css">
    <link rel="stylesheet" href="../../src/css/modal.css">
    <link rel="stylesheet" href="../../src/css/toasts.css">
    <link rel="icon" href="../../logo.png" type="image/png">
</head>
<body>
<!-- Хедер -->
<header class="header">
    <div class="container">
        <div class="logo-container">
            <img src="../../logo.png" alt="Светлый Путь" class="logo-img">
            <span class="logo-text">Светлый Путь</span>
        </div>
        <button class="burger" id="burger">
            <span></span>
            <span></span>
            <span></span>
        </button>
        <nav class="nav" id="nav">
            <a href="../../index.php">Главная</a>
            <a href="../../goods/">Товары</a>
            <a href="../../services/">Услуги</a>
            <a href="../../agents/">Агенты</a>
            <a href="../../calculator/">Калькулятор</a>
            <a href="../../autopark/">Автопарк</a>
            <a href="../../orders/">Заказы</a>
            <a onclick="openModal()" class="btn-light">Связаться</a>
        </nav>
    </div>
    <div class="overlay" id="overlay"></div>
</header>

<main>
    <!-- Хлебные крошки -->
    <div class="breadcrumbs">
        <div class="container">
            <a href="../../index.php">Главная</a>
            <span class="separator">›</span>
            <a href="../index.php">Правовая информация</a>
            <span class="separator">›</span>
            <span class="current">Политика cookie</span>
        </div>
    </div>

    <section class="page-header" style="padding: 35px 0">
        <div class="container" style="display: flex; align-items: center; justify-content: center; flex-direction: column">
            <h1>Политика использования файлов cookie</h1>
            <p class="subtitle">Актуально на 2026 год</p>
        </div>
    </section>

    <section class="privacy-content">
        <div class="container">
            <div class="privacy-card">
                <h2>1. Что такое cookie?</h2>
                <p>Cookie — это небольшие текстовые файлы, которые веб-сайт сохраняет на устройстве пользователя (компьютере, планшете, смартфоне) при его посещении. Они содержат информацию о действиях пользователя на сайте и позволяют улучшить его работу.</p>

                <h2>2. Какие типы cookie мы используем</h2>
                <p><strong>2.1. Необходимые cookie (обязательные)</strong></p>
                <p>Эти cookie необходимы для корректной работы сайта. Они обеспечивают возможность авторизации, сохранения корзины, навигации по страницам. Без них сайт не может функционировать должным образом.</p>

                <p><strong>2.2. Функциональные cookie</strong></p>
                <p>Эти cookie запоминают выбранные пользователем настройки (язык, регион, отображение элементов), чтобы сделать использование сайта более удобным.</p>

                <p><strong>2.3. Аналитические cookie</strong></p>
                <p>Собирают обезличенную информацию о посещениях сайта: количество посетителей, просмотренные страницы, источники переходов. Это помогает нам улучшать качество сайта.</p>

                <h2>3. Как управлять cookie</h2>
                <p>Вы можете управлять cookie через настройки вашего браузера. В большинстве браузеров можно:</p>
                <ul>
                    <li>Заблокировать все cookie;</li>
                    <li>Удалить уже сохранённые cookie;</li>
                    <li>Настроить уведомление перед сохранением cookie;</li>
                    <li>Разрешить cookie только для определённых сайтов.</li>
                </ul>
                <p>Обратите внимание: отключение необходимых cookie может привести к некорректной работе некоторых функций сайта.</p>

                <h2>4. Срок хранения cookie</h2>
                <ul>
                    <li><strong>Сессионные cookie:</strong> хранятся до закрытия браузера;</li>
                    <li><strong>Постоянные cookie:</strong> сохраняются на устройстве в течение определённого времени (до 12 месяцев).</li>
                </ul>

                <h2>5. Использование сторонних сервисов</h2>
                <p>На сайте могут использоваться сторонние сервисы (например, аналитические системы), которые также могут сохранять свои cookie. Мы не контролируем эти cookie и рекомендуем ознакомиться с их политиками конфиденциальности.</p>

                <h2>6. Изменение политики cookie</h2>
                <p>Агентство оставляет за собой право вносить изменения в настоящую Политику. Актуальная версия всегда доступна на этой странице.</p>

                <div class="contact-info">
                    <p><strong>Ритуальное агентство «Светлый Путь»</strong></p>
                    <p>📞 Телефон: <a href="tel:+79876543210">+7 (987) 654-32-10</a></p>
                    <p>✉️ Email: <a href="mailto:info@svetlyput.ru">info@svetlyput.ru</a></p>
                </div>

                <div class="privacy-footer">
                    <a href="../../index.php" class="btn">Вернуться на главную</a>
                </div>
            </div>
        </div>
    </section>
</main>
<?php echo $twig->render('page_end.twig', ['basePath' => '../', "config" => new \lib\Config()->getConfig()]); ?>
<script src="../../src/js/jquery.min.js"></script>
<script src="../../src/js/toasts.js"></script>
<script src="../../src/js/modal.js"></script>
<script src="../src/js/script.js"></script>
</body>
</html>