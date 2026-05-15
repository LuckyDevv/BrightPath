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
    <title>Политика конфиденциальности - Светлый Путь</title>
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
            <span class="current">Политика конфиденциальности</span>
        </div>
    </div>

    <section class="page-header" style="padding: 35px 0">
        <div class="container" style="display: flex; align-items: center; justify-content: center; flex-direction: column">
            <h1>Политика конфиденциальности</h1>
            <p class="subtitle">Актуально на 2026 год</p>
        </div>
    </section>

    <section class="privacy-content">
        <div class="container">
            <div class="privacy-card">
                <h2>1. Общие положения</h2>
                <p>Настоящая Политика конфиденциальности (далее — Политика) регулирует порядок обработки и защиты персональных данных пользователей сайта Ритуального агентства «Светлый Путь» (далее — Агентство).</p>
                <p>Политика разработана в соответствии с Федеральным законом от 27.07.2006 № 152-ФЗ «О персональных данных» и другими нормативными правовыми актами Российской Федерации в области защиты персональных данных.</p>

                <h2>2. Какие данные мы собираем</h2>
                <p>При использовании сайта и его сервисов мы можем собирать следующую информацию:</p>
                <ul>
                    <li><strong>Личная информация:</strong> ФИО, контактный телефон, адрес электронной почты (предоставляется добровольно при заполнении форм).</li>
                    <li><strong>Техническая информация:</strong> IP-адрес, тип браузера, версия операционной системы, данные о посещаемых страницах, время посещения.</li>
                    <li><strong>Файлы cookie:</strong> автоматически сохраняются на устройстве пользователя для обеспечения работы сайта.</li>
                </ul>

                <h2>3. Цели сбора и обработки данных</h2>
                <p>Персональные данные пользователей используются в следующих целях:</p>
                <ul>
                    <li>Обработка заявок и обращений через формы обратной связи;</li>
                    <li>Предоставление консультационных услуг по вопросам организации похорон;</li>
                    <li>Улучшение качества работы сайта и пользовательского опыта;</li>
                    <li>Анализ посещаемости и поведения пользователей на сайте.</li>
                </ul>

                <h2>4. Конфиденциальность и защита данных</h2>
                <p>Агентство обязуется не разглашать персональные данные пользователей третьим лицам без их согласия, за исключением случаев, предусмотренных законодательством Российской Федерации.</p>
                <p>Для защиты персональных данных применяются организационные и технические меры, включая шифрование, ограничение доступа, использование защищённого соединения HTTPS.</p>

                <h2>5. Сроки хранения данных</h2>
                <p>Персональные данные хранятся не дольше, чем это требуется для целей их обработки. После достижения целей обработки или в случае отзыва согласия, персональные данные подлежат уничтожению.</p>

                <h2>6. Права пользователей</h2>
                <p>Пользователи имеют право:</p>
                <ul>
                    <li>Получать информацию об обработке своих персональных данных;</li>
                    <li>Требовать уточнения, блокирования или уничтожения своих персональных данных;</li>
                    <li>Отозвать согласие на обработку персональных данных;</li>
                    <li>Обжаловать действия Агентства в уполномоченном органе.</li>
                </ul>

                <h2>7. Контактная информация</h2>
                <div class="contact-info">
                    <p><strong>Ритуальное агентство «Светлый Путь»</strong></p>
                    <p>📞 Телефон: <a href="tel:+79876543210">+7 (987) 654-32-10</a></p>
                    <p>✉️ Email: <a href="mailto:info@svetlyput.ru">info@svetlyput.ru</a></p>
                    <p>📍 Адрес: г. Одинцово, ул. Глазынинская, д. 18</p>
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