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
    <title>Пользовательское соглашение - Светлый Путь</title>
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
            <span class="current">Пользовательское соглашение</span>
        </div>
    </div>
    <section class="page-header" style="padding: 35px 0">
        <div class="container" style="display: flex; align-items: center; justify-content: center; flex-direction: column">
            <h1>Пользовательское соглашение</h1>
            <p class="subtitle">Актуально на 2026 год</p>
        </div>
    </section>
    <section class="privacy-content">
        <div class="container">
            <div class="privacy-card">
                <h2>1. Термины и определения</h2>
                <p><strong>1.1. Сайт</strong> — интернет-ресурс, расположенный по адресу https://svetlyput.ru, предназначенный для предоставления информации об услугах Ритуального агентства «Светлый Путь».</p>
                <p><strong>1.2. Пользователь</strong> — любое лицо, осуществляющее доступ к Сайту и использующее его функциональные возможности.</p>
                <p><strong>1.3. Агентство</strong> — Ритуальное агентство «Светлый Путь», предоставляющее ритуальные услуги.</p>
                <h2>2. Общие положения</h2>
                <p>2.1. Использование Сайта означает безоговорочное принятие Пользователем условий настоящего Соглашения.</p>
                <p>2.2. Агентство оставляет за собой право вносить изменения в настоящее Соглашение без предварительного уведомления Пользователя.</p>
                <p>2.3. Пользователь обязуется самостоятельно ознакомляться с актуальной версией Соглашения при каждом посещении Сайта.</p>
                <h2>3. Права и обязанности Пользователя</h2>
                <p><strong>Пользователь имеет право:</strong></p>
                <ul>
                    <li>Пользоваться всеми доступными функциями Сайта;</li>
                    <li>Оставлять заявки на получение услуг Агентства через формы обратной связи;</li>
                    <li>Получать достоверную информацию об услугах и ценах;</li>
                    <li>Обращаться в Агентство с вопросами и предложениями.</li>
                </ul>
                <p><strong>Пользователь обязуется:</strong></p>
                <ul>
                    <li>Предоставлять достоверную информацию при заполнении форм;</li>
                    <li>Не использовать Сайт для распространения вредоносного программного обеспечения;</li>
                    <li>Не нарушать работу Сайта и не предпринимать действий, направленных на его дестабилизацию;</li>
                    <li>Соблюдать законодательство Российской Федерации.</li>
                </ul>
                <h2>4. Права и обязанности Агентства</h2>
                <p><strong>Агентство имеет право:</strong></p>
                <ul>
                    <li>Изменять содержание и функциональность Сайта без предварительного уведомления;</li>
                    <li>Ограничивать доступ к Сайту для проведения технических работ;</li>
                    <li>Отказывать в предоставлении услуг при нарушении Пользователем условий Соглашения.</li>
                </ul>
                <p><strong>Агентство обязуется:</strong></p>
                <ul>
                    <li>Обеспечивать доступность Сайта, за исключением времени проведения технических работ;</li>
                    <li>Обрабатывать заявки Пользователей в установленном порядке;</li>
                    <li>Обеспечивать конфиденциальность персональных данных Пользователей.</li>
                </ul>
                <h2>5. Интеллектуальная собственность</h2>
                <p>Все материалы, размещённые на Сайте, включая тексты, изображения, логотипы, дизайн, являются объектами интеллектуальной собственности Агентства и не могут быть использованы без письменного разрешения.</p>
                <h2>6. Ограничение ответственности</h2>
                <p>6.1. Агентство не несёт ответственности за убытки, возникшие в результате использования или невозможности использования Сайта.</p>
                <p>6.2. Агентство не гарантирует, что Сайт будет работать без ошибок и перерывов.</p>
                <p>6.3. Пользователь самостоятельно несёт ответственность за достоверность предоставленной информации.</p>
                <h2>7. Заключительные положения</h2>
                <p>7.1. Настоящее Соглашение регулируется законодательством Российской Федерации.</p>
                <p>7.2. Все споры и разногласия решаются путём переговоров. При недостижении согласия — в судебном порядке по месту нахождения Агентства.</p>
                <p>7.3. Если какие-либо положения Соглашения признаются недействительными, остальные положения сохраняют силу.</p>
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