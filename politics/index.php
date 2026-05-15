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
    <title>Правовая информация - Светлый Путь</title>
    <link rel="stylesheet" href="src/css/style.css">
    <link rel="stylesheet" href="../src/css/index.css">
    <link rel="stylesheet" href="../src/css/nav.css">
    <link rel="stylesheet" href="../src/css/footer.css">
    <link rel="stylesheet" href="../src/css/modal.css">
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
            <a href="../orders/">Заказы</a>
            <a onclick="openModal()" class="btn-light">Связаться</a>
        </nav>
    </div>
    <div class="overlay" id="overlay"></div>
</header>

<main>
    <!-- Хлебные крошки -->
    <div class="breadcrumbs">
        <div class="container">
            <a href="../index.php">Главная</a>
            <span class="separator">›</span>
            <span class="current">Правовая информация</span>
        </div>
    </div>

    <section class="page-header" style="padding: 35px 0">
        <div class="container" style="display: flex; align-items: center; justify-content: center; flex-direction: column">
            <h1>Правовая информация</h1>
            <p class="subtitle">Документы, регулирующие использование сайта</p>
        </div>
    </section>

    <section class="politics-section">
        <div class="container">
            <div class="politics-grid">
                <!-- Политика конфиденциальности -->
                <a href="privacy/" class="politics-card">
                    <div class="card-icon">
                        <svg width="48" height="48" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <rect x="0" fill="none" width="20" height="20"/>
                            <g>
                                <path d="M10 9.6c-.6 0-1 .4-1 1 0 .4.3.7.6.8l-.3 1.4h1.3l-.3-1.4c.4-.1.6-.4.6-.8.1-.6-.3-1-.9-1zm.1-4.3c-.7 0-1.4.5-1.4 1.2V8h2.7V6.5c-.1-.7-.6-1.2-1.3-1.2zM10 2L3 5v3c.1 4.4 2.9 8.3 7 9.9 4.1-1.6 6.9-5.5 7-9.9V5l-7-3zm4 11c0 .6-.4 1-1 1H7c-.6 0-1-.4-1-1V9c0-.6.4-1 1-1h.3V6.5C7.4 5.1 8.6 4 10 4c1.4 0 2.6 1.1 2.7 2.5V8h.3c.6 0 1 .4 1 1v4z"/>
                            </g>
                        </svg>
                    </div>
                    <h3>Политика конфиденциальности</h3>
                    <p>Узнайте, как мы собираем, используем и защищаем ваши персональные данные.</p>
                    <span class="card-link">Подробнее →</span>
                </a>

                <!-- Пользовательское соглашение -->
                <a href="terms/" class="politics-card">
                    <div class="card-icon">
                        <svg width="48" height="48" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M6.7964 11.8996L6.79897 11.9002C6.7972 11.8999 6.79638 11.8996 6.7964 11.8996Z" fill="#000000"/>
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M1 1.5C1 0.671573 1.67157 0 2.5 0H10.7071L14 3.29289V13.5C14 14.3284 13.3284 15 12.5 15H2.5C1.67157 15 1 14.3284 1 13.5V1.5ZM7 4H4V5H7V4ZM11 7H4V8H11V7ZM6.3047 10.9078C5.90085 11.0163 5.58546 11.3247 5.47432 11.6581L4.52563 11.3419C4.74783 10.6753 5.33244 10.1336 6.04525 9.94209C6.75237 9.75212 7.55858 9.91125 8.25663 10.5534C8.46631 10.4917 8.67406 10.457 8.87948 10.446C9.41817 10.4171 9.90228 10.553 10.3173 10.7259C10.6221 10.8529 10.9176 11.0135 11.1661 11.1485C11.2454 11.1916 11.3201 11.2322 11.3886 11.2682C11.7119 11.4379 11.8898 11.5 12 11.5V12.5C11.6101 12.5 11.2255 12.312 10.9238 12.1536C10.8307 12.1047 10.7406 12.0558 10.6517 12.0075C10.4144 11.8786 10.1856 11.7544 9.93267 11.649C9.59767 11.5094 9.26929 11.4265 8.93298 11.4446C8.88409 11.4472 8.83418 11.452 8.78324 11.4593L8.78443 11.4726C8.81115 11.7974 8.67709 12.0645 8.50444 12.2563C8.18586 12.6103 7.66686 12.796 7.27692 12.8669C7.07379 12.9038 6.8553 12.9193 6.66293 12.8912C6.56876 12.8774 6.44734 12.8485 6.33295 12.7804C6.21132 12.708 6.06717 12.5695 6.03022 12.3478C5.99585 12.1416 6.07346 11.9716 6.14128 11.8671C6.21124 11.7593 6.30544 11.6661 6.40277 11.5856C6.59219 11.4287 6.86814 11.2591 7.23685 11.0726L7.26373 11.0531C6.91545 10.8496 6.58098 10.8336 6.3047 10.9078Z" fill="#000000"/>
                        </svg>
                    </div>
                    <h3>Пользовательское соглашение</h3>
                    <p>Правила использования сайта и предоставления услуг.</p>
                    <span class="card-link">Подробнее →</span>
                </a>

                <!-- Политика cookie -->
                <a href="cookies/" class="politics-card">
                    <svg width="48" height="48" viewBox="0 0 24 24" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                        <g id="🔍-Product-Icons" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                            <g id="ic_fluent_cookies_24_filled" fill="#212121" fill-rule="nonzero">
                                <path d="M12,2 C12.7139344,2 13.4186669,2.07493649 14.1058513,2.22228153 C14.6865234,2.34678839 14.8990219,3.06470877 14.4796691,3.48521478 C14.0147885,3.95137375 13.75,4.57867916 13.75,5.25 C13.75,6.42043414 14.5611837,7.42718287 15.6858365,7.68625206 C16.0559035,7.77149876 16.3038519,8.11989963 16.2631619,8.49747198 C16.2544079,8.57870262 16.25,8.66307444 16.25,8.75 C16.25,10.1307119 17.3692881,11.25 18.75,11.25 C19.4766017,11.25 20.151276,10.9392994 20.6235262,10.4053218 C21.0526462,9.92011177 21.8536336,10.1704416 21.9300905,10.8136579 C21.9765784,11.2047517 22,11.6008646 22,12 C22,17.5228475 17.5228475,22 12,22 C6.4771525,22 2,17.5228475 2,12 C2,6.4771525 6.4771525,2 12,2 Z M15,16 C14.4477153,16 14,16.4477153 14,17 C14,17.5522847 14.4477153,18 15,18 C15.5522847,18 16,17.5522847 16,17 C16,16.4477153 15.5522847,16 15,16 Z M8,15 C7.44771525,15 7,15.4477153 7,16 C7,16.5522847 7.44771525,17 8,17 C8.55228475,17 9,16.5522847 9,16 C9,15.4477153 8.55228475,15 8,15 Z M12,11 C11.4477153,11 11,11.4477153 11,12 C11,12.5522847 11.4477153,13 12,13 C12.5522847,13 13,12.5522847 13,12 C13,11.4477153 12.5522847,11 12,11 Z M7,8 C6.44771525,8 6,8.44771525 6,9 C6,9.55228475 6.44771525,10 7,10 C7.55228475,10 8,9.55228475 8,9 C8,8.44771525 7.55228475,8 7,8 Z" id="🎨-Color">
                                </path>
                            </g>
                        </g>
                    </svg>
                    <h3>Политика cookie</h3>
                    <p>Информация о файлах cookie, которые мы используем на сайте.</p>
                    <span class="card-link">Подробнее →</span>
                </a>

                <!-- Соглашение об обработке персональных данных -->
                <a href="treatment/" class="politics-card">
                    <div class="card-icon">
                        <svg fill="#000000" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="48" height="48" viewBox="0 0 208 256" enable-background="new 0 0 208 256" xml:space="preserve">
                            <path d="M2,35c0,0,0,184.486,0,186c0,17.87,14.416,32.192,32.084,32.899V254H205.82v-9.288c-13.226,0-23.928-10.702-23.928-23.928
                            s10.702-23.928,23.928-23.928v-0.202V2H35.8C17.526,2,2,16.726,2,35z M172.603,220.683c0,9.389,4.038,17.87,10.298,23.928H35.397
                            c-13.226,0.101-24.029-10.702-24.029-23.928c0-12.519,9.793-22.817,22.111-23.726l149.726-0.303
                            C176.743,202.712,172.603,211.192,172.603,220.683z M66.011,84.012h10.127V65.997c0-17.589,14.391-31.98,31.98-31.98
                            s31.98,14.391,31.98,31.98v18.015h8.954v76.644H66.011V84.012z M114.513,126.438l2.558,15.03H97.884l2.558-15.03
                            c-3.518-2.239-5.756-6.076-5.756-10.553c0-7.036,5.756-12.792,12.792-12.792s12.792,5.756,12.792,12.792
                            C120.27,120.362,118.031,124.2,114.513,126.438z M127.412,65.997v18.015h-7.142H89.036V65.997c0-10.553,8.634-19.188,19.188-19.188
                            C118.777,46.809,127.412,55.444,127.412,65.997z"/>
                        </svg>
                    </div>
                    <h3>Соглашение об обработке персональных данных</h3>
                    <p>Порядок обработки и защиты ваших персональных данных.</p>
                    <span class="card-link">Подробнее →</span>
                </a>
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