<?php
require __DIR__ . '/vendor/autoload.php';
use managers\PresetsManager;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

$presetsManager = new PresetsManager();

$loader = new FilesystemLoader('server/twig');
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
    <title>Светлый Путь - Ритуальное агентство в Одинцово</title>
    <link rel="stylesheet" href="src/css/index.css">
    <link rel="stylesheet" href="src/css/nav.css">
    <link rel="stylesheet" href="src/css/hero.css">
    <link rel="stylesheet" href="src/css/info.css">
    <link rel="stylesheet" href="src/css/advantages.css">
    <link rel="stylesheet" href="src/css/quote.css">
    <link rel="stylesheet" href="src/css/faq.css">
    <link rel="stylesheet" href="src/css/footer.css">
    <link rel="stylesheet" href="src/css/tariffs.css">
    <link rel="stylesheet" href="src/css/services.css">
    <link rel="stylesheet" href="src/css/transport.css">
    <link rel="stylesheet" href="src/css/contacts.css">
    <link rel="stylesheet" href="src/css/experience.css">
    <link rel="stylesheet" href="src/css/modal.css">
    <link rel="stylesheet" href="src/css/toasts.css">
    <link rel="icon" href="logo.png" type="image/png">
</head>
<body>
<!-- Хедер (такой же как на главной) -->
<header class="header">
    <div class="container">
        <div class="logo-container">
            <img src="logo.png" alt="Светлый Путь" class="logo-img">
            <span class="logo-text">Светлый Путь</span>
        </div>
        <button class="burger" id="burger">
            <span></span>
            <span></span>
            <span></span>
        </button>
        <nav class="nav" id="nav">
            <a href="#" class="active">Главная</a>
            <a href="goods">Товары</a>
            <a href="services/">Услуги</a>
            <a href="agents">Агенты</a>
            <a href="calculator">Калькулятор</a>
            <a href="autopark">Автопарк</a>
            <a href="orders">Заказы</a>
            <a onclick="openModal()" class="btn-light" id="contactBtn">Связаться</a>
        </nav>
    </div>
    <div class="overlay" id="overlay"></div>
</header>

<!-- Герой -->
<section id="hero" class="hero">
    <div class="container">
        <div class="hero-content">
            <div class="highlight">Ритуальное агентство в Одинцово</div>
            <h1>Полный комплекс ритуальных услуг</h1>
            <p>Организация похорон, ритуальные товары, транспорт, питание, юридическая помощь. Работаем 24/7 без выходных.</p>
            <div class="halabuga">
                <a class="btn" onclick="openModal()">Записаться на консультацию</a>
            </div>
            <div class="hero-stats">
                <div class="hero-stat-item">
                    <div class="number">2002</div>
                    <div class="label">год основания</div>
                </div>
                <div class="hero-stat-item">
                    <div class="number">3000+</div>
                    <div class="label">прощаний</div>
                </div>
                <div class="hero-stat-item">
                    <div class="number">24/7</div>
                    <div class="label">на связи</div>
                </div>
            </div>
        </div>
        <div class="hero-image">
            <img src="src/images/main.jpg" alt="Ритуальное агентство">
        </div>
    </div>
</section>

<!-- Цитата -->
<section class="quote-block">
    <div class="container">
        <div class="quote-inner">
            <div class="quote-text">
                <h2>Сервис высшей категории без лишних слов</h2>
                <div class="small-text">Александр Юрьевич, директор бюро "Светлый путь"</div>
            </div>
            <div class="quote-stats">
                <div class="stat-item">
                    <div class="number">23</div>
                    <div class="label">года опыта</div>
                </div>
                <div class="stat-item">
                    <div class="number">50+</div>
                    <div class="label">кладбищ</div>
                </div>
                <div class="stat-item">
                    <div class="number">100%</div>
                    <div class="label">деликатно</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Преимущества -->
<section class="advantages">
    <div class="container">
        <h2>Наши преимущества</h2>
        <div class="section-divider"></div>
        <div class="subtitle">Почему семьи доверяют нам самое важное</div>
        <div class="advantages-grid">
            <div class="advantage-item">
                <div class="icon-circle">
                    <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <defs>
                            <style>.cls-1,.cls-2{fill:none;stroke:#000000;stroke-linecap:round;stroke-linejoin:round;stroke-width:1.5px;}.cls-1{fill-rule:evenodd;}</style>
                        </defs>
                        <g id="ic-statistics-speed">
                            <path class="cls-1" d="M3,16V15a9,9,0,0,1,9-9h0a9,9,0,0,1,9,9v1"/>
                            <circle class="cls-2" cx="12" cy="16" r="2"/>
                            <line class="cls-2" x1="13.41" y1="14.59" x2="16.5" y2="11.5"/>
                        </g>
                    </svg>

                </div>
                <h3>Скорость</h3>
                <p>Выезд за 1 час. Помощь в любом районе</p>
            </div>
            <div class="advantage-item">
                <div class="icon-circle">




                    <svg version="1.1" id="Icons" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                         viewBox="0 0 32 32" xml:space="preserve">
                        <g>
	                        <path stroke-width="1.5" stroke="currentColor" fill="none" d="M12,17c0.8-4.2,1.9-5.3,6.1-6.1c0.5-0.1,0.8-0.5,0.8-1s-0.3-0.9-0.8-1C13.9,8.1,12.8,7,12,2.8C11.9,2.3,11.5,2,11,2
		                    c-0.5,0-0.9,0.3-1,0.8C9.2,7,8.1,8.1,3.9,8.9C3.5,9,3.1,9.4,3.1,9.9s0.3,0.9,0.8,1c4.2,0.8,5.3,1.9,6.1,6.1c0.1,0.5,0.5,0.8,1,0.8
		                    S11.9,17.4,12,17z"/>
                            <path stroke-width="1.5" stroke="currentColor" fill="none" d="M22,24c-2.8-0.6-3.4-1.2-4-4c-0.1-0.5-0.5-0.8-1-0.8s-0.9,0.3-1,0.8c-0.6,2.8-1.2,3.4-4,4c-0.5,0.1-0.8,0.5-0.8,1
		                    s0.3,0.9,0.8,1c2.8,0.6,3.4,1.2,4,4c0.1,0.5,0.5,0.8,1,0.8s0.9-0.3,1-0.8c0.6-2.8,1.2-3.4,4-4c0.5-0.1,0.8-0.5,0.8-1
		                    S22.4,24.1,22,24z"/>
                            <path stroke-width="1.5" stroke="currentColor" fill="none" d="M29.2,14c-2.2-0.4-2.7-0.9-3.1-3.1c-0.1-0.5-0.5-0.8-1-0.8c-0.5,0-0.9,0.3-1,0.8c-0.4,2.2-0.9,2.7-3.1,3.1
                            c-0.5,0.1-0.8,0.5-0.8,1s0.3,0.9,0.8,1c2.2,0.4,2.7,0.9,3.1,3.1c0.1,0.5,0.5,0.8,1,0.8c0.5,0,0.9-0.3,1-0.8
                            c0.4-2.2,0.9-2.7,3.1-3.1c0.5-0.1,0.8-0.5,0.8-1S29.7,14.1,29.2,14z"/>
                            <path stroke-width="1.5" stroke="currentColor" fill="none" d="M5.7,22.3C5.4,22,5,21.9,4.6,22.1c-0.1,0-0.2,0.1-0.3,0.2c-0.1,0.1-0.2,0.2-0.2,0.3C4,22.7,4,22.9,4,23s0,0.3,0.1,0.4
                            c0.1,0.1,0.1,0.2,0.2,0.3c0.1,0.1,0.2,0.2,0.3,0.2C4.7,24,4.9,24,5,24c0.1,0,0.3,0,0.4-0.1s0.2-0.1,0.3-0.2
                            c0.1-0.1,0.2-0.2,0.2-0.3C6,23.3,6,23.1,6,23s0-0.3-0.1-0.4C5.9,22.5,5.8,22.4,5.7,22.3z"/>
                            <path stroke-width="1.5" stroke="currentColor" fill="none" d="M28,7c0.3,0,0.5-0.1,0.7-0.3C28.9,6.5,29,6.3,29,6s-0.1-0.5-0.3-0.7c-0.1-0.1-0.2-0.2-0.3-0.2c-0.2-0.1-0.5-0.1-0.8,0
                            c-0.1,0-0.2,0.1-0.3,0.2C27.1,5.5,27,5.7,27,6c0,0.3,0.1,0.5,0.3,0.7C27.5,6.9,27.7,7,28,7z"/>
                        </g>
                    </svg>

                </div>
                <h3>Опыт</h3>
                <p>Более 3000 организованных похорон с 2002 года</p>
            </div>
            <div class="advantage-item">
                <div class="icon-circle">
                    <svg viewBox="0 0 24 24">
                        <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" stroke="currentColor" stroke-width="1.5" fill="none"/>
                    </svg>
                </div>
                <h3>Подход</h3>
                <p>Деликатность и понимание в каждой детали</p>
            </div>
            <div class="advantage-item">
                <div class="icon-circle">
                    <svg viewBox="0 0 64 64" xmlns="http://www.w3.org/2000/svg">
                        <g data-name="13 money" id="_13_money">
                            <path stroke="currentColor" stroke-width="1.8" fill="none" d="M59.2,16.19a20.767,20.767,0,0,0-12.67-.29,48.549,48.549,0,0,0-4.62,1.66,46.881,46.881,0,0,1-4.57,1.63,18.224,18.224,0,0,1-8.99.43,15.963,15.963,0,0,1-3.9-1.58,20.055,20.055,0,0,0-12.93-2.52c-3.27.47-4.29.78-4.29,1.85v4.62c-.7.07-1.39.15-2.09.26A1.979,1.979,0,0,0,3.5,24.21V43.7a1.98,1.98,0,0,0,.68,1.5,1.941,1.941,0,0,0,1.54.45,33.148,33.148,0,0,1,11.22.17,34.245,34.245,0,0,1,4.48,1.28c1.34.44,2.72.9,4.15,1.22a26.061,26.061,0,0,0,5.81.64,29.921,29.921,0,0,0,5.2-.47,40.684,40.684,0,0,0,5.16-1.37,38.768,38.768,0,0,1,4.85-1.3,23.014,23.014,0,0,1,7.09-.14,1.855,1.855,0,0,0,1.48-.46,2.017,2.017,0,0,0,.68-1.51V38.7a22.43,22.43,0,0,1,2.49.15,1.62,1.62,0,0,0,.22.01,1.956,1.956,0,0,0,1.28-.48,1.986,1.986,0,0,0,.67-1.5V18.08A2.017,2.017,0,0,0,59.2,16.19ZM5.45,24.23a34.568,34.568,0,0,1,5.51-.44v1.47a4.13,4.13,0,0,1-4,4.23H5.46Zm5.51,19.03a36.2,36.2,0,0,0-5.46.44l-.02-6.25H6.96a4.13,4.13,0,0,1,4,4.23Zm42.88.42a25.636,25.636,0,0,0-5.46-.1v-1.9a4.13,4.13,0,0,1,4-4.23h1.46Zm0-8.23H52.38a6.121,6.121,0,0,0-6,6.23v2.15c-.05.01-.1.01-.15.02a43.69,43.69,0,0,0-5.09,1.36,39.691,39.691,0,0,1-4.91,1.31,25.771,25.771,0,0,1-10.22-.15,38.483,38.483,0,0,1-3.96-1.17,37.744,37.744,0,0,0-4.72-1.34,31.622,31.622,0,0,0-4.37-.54V41.68a6.121,6.121,0,0,0-6-6.23H5.48l-.01-3.96H6.96a6.127,6.127,0,0,0,6-6.23V23.85a30.7,30.7,0,0,1,3.98.5,35.872,35.872,0,0,1,4.48,1.28,41.6,41.6,0,0,0,4.15,1.23,28.055,28.055,0,0,0,11.01.16,40.684,40.684,0,0,0,5.16-1.37,41.566,41.566,0,0,1,4.64-1.25v.86a6.127,6.127,0,0,0,6,6.23h1.46Zm0-5.96H52.38a4.13,4.13,0,0,1-4-4.23V24.11a23.8,23.8,0,0,1,5.46.11Zm2,7.21V24.22a1.966,1.966,0,0,0-1.67-1.96,25.523,25.523,0,0,0-6.76-.04h-.03a1.01,1.01,0,0,0-.17.03c-.33.05-.65.08-.98.14a41.767,41.767,0,0,0-5.09,1.35,38,38,0,0,1-4.91,1.31,25.771,25.771,0,0,1-10.22-.15,38.483,38.483,0,0,1-3.96-1.17,39.527,39.527,0,0,0-4.72-1.34,33.483,33.483,0,0,0-5.23-.58c-.05,0-.09-.02-.14-.02a.3.3,0,0,0-.1.02c-.87-.03-1.75-.02-2.63.03V17.97a24.958,24.958,0,0,1,2.65-.48A18.1,18.1,0,0,1,23.5,19.8a17.56,17.56,0,0,0,4.41,1.77,20.2,20.2,0,0,0,9.96-.45,47.1,47.1,0,0,0,4.77-1.7,44.3,44.3,0,0,1,4.44-1.59,18.642,18.642,0,0,1,11.42.25l.07,18.78A25.283,25.283,0,0,0,55.84,36.7Z"/>
                            <path stroke="currentColor" stroke-width="1.8" fill="none" d="M32.5,39.54a2.829,2.829,0,0,1-1.83,1.87v.79a1,1,0,0,1-2,0v-.96a7.207,7.207,0,0,1-1.24-.82c-.15-.12-.29-.23-.43-.33a1,1,0,1,1,1.14-1.64c.17.12.34.25.52.39.44.34.94.73,1.27.7a.843.843,0,0,0,.67-.61.8.8,0,0,0-.21-.87l-2.26-1.9a2.8,2.8,0,0,1-.55-3.64,2.586,2.586,0,0,1,1.09-.96V30.49a1,1,0,1,1,2,0v.94a2.708,2.708,0,0,1,.83.46l.91.76a1,1,0,0,1-1.29,1.53l-.9-.75a.64.64,0,0,0-.5-.15.671.671,0,0,0-.45.31.806.806,0,0,0,.14,1.04l2.27,1.9A2.748,2.748,0,0,1,32.5,39.54Z"/>
                        </g>
                    </svg>
                </div>
                <h3>Цены</h3>
                <p>Прозрачная смета. Доступные тарифы</p>
            </div>
        </div>
    </div>
</section>

<!-- Первые действия -->
<section class="info-block">
    <div class="container">
        <div class="info-grid">
            <div class="info-content">
                <h3>Что делать</h3>
                <h2>Первые шаги в трудную минуту</h2>
                <p>Мы подготовили простой план, чтобы вы не растерялись:</p>
                <ul class="info-list">
                    <li><strong>Позвоните нам +7 987 654-32-10</strong> — мы приедем в течение часа</li>
                    <li><strong>Получите свидетельство о смерти</strong> в морге или больнице</li>
                    <li><strong>Подготовьте паспорт умершего</strong> и свой паспорт</li>
                    <li><strong>Выберите ритуальные принадлежности</strong> — поможем с выбором</li>
                </ul>
                <div class="halabuga">
                    <a class="btn" onclick="openModal()" style="margin-top: 25px;">Срочная консультация</a>
                </div>
            </div>
            <div class="info-image">
                <img src="src/images/sad.jpg" alt="Поддержка">
            </div>
        </div>
    </div>
</section>

<!-- Услуги -->
<section id="services" class="services">
    <div class="container">
        <h2>Наши услуги</h2>
        <div class="section-divider"></div>
        <div class="subtitle">Полный спектр ритуальных услуг</div>
        <div class="services-grid">
            <div class="service-card">
                <div class="service-image">
                    <img src="src/images/service1.jpg" alt="Организация похорон">
                </div>
                <span>Организация похорон</span>
            </div>
            <div class="service-card">
                <div class="service-image">
                    <img src="src/images/service2.jpg" alt="Кремация">
                </div>
                <span>Кремация</span>
            </div>
            <div class="service-card">
                <div class="service-image">
                    <img src="src/images/service3.jpg" alt="Перевоз тела">
                </div>
                <span>Перевоз тела</span>
            </div>
            <div class="service-card">
                <div class="service-image">
                    <img src="src/images/service4.jpg" alt="Памятники и надгробия">
                </div>
                <span>Памятники и надгробия</span>
            </div>
            <div class="service-card">
                <div class="service-image">
                    <img src="src/images/service5.jpg" alt="Ритуальный транспорт">
                </div>
                <span>Ритуальный транспорт</span>
            </div>
            <div class="service-card">
                <div class="service-image">
                    <img src="src/images/service6.jpg" alt="Юридическая помощь">
                </div>
                <span>Юридическая помощь</span>
            </div>
        </div>
    </div>
</section>

<!-- Пособия -->
<section class="info-block dark">
    <div class="container">
        <div class="info-grid">
            <div class="info-image">
                <img src="src/images/social_help.jpg" alt="Социальная поддержка">
            </div>
            <div class="info-content dark">
                <h3>Социальная поддержка</h3>
                <h2>Пособие на погребение</h2>
                <p>Государство гарантирует выплату. Размер пособия в 2026 году:</p>
                <ul class="info-list">
                    <li><strong>Обычное пособие</strong> — 8 500 ₽ + региональные</li>
                    <li><strong>Для пенсионеров</strong> — до 15 000 ₽</li>
                    <li><strong>Для военных</strong> — до 30 000 ₽</li>
                </ul>
                <div class="halabuga">
                    <a href="payouts" class="btn-outline" style="margin-top: 20px; color: white; border-color: white;">Подробнее о выплатах</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Тарифы -->
<section id="pricing" class="pricing">
    <div class="container">
        <h2>Стоимость услуг</h2>
        <div class="section-divider"></div>
        <div class="subtitle">Выберите подходящий пакет</div>
        <div class="tariffs">
            <!-- Эконом - базовый -->
            <?php
            $presets = $presetsManager->getAllPresets();
            foreach ($presets as $preset) {
                try {
                    $transport = json_decode($preset["transport"]);
                    $goods = json_decode($preset["goods"]);
                    $services = json_decode($preset["services"]);
                    $features = [];
                    foreach ($transport as $item) {
                        $features[] = $item;
                    }
                    foreach ($goods as $item) {
                        $features[] = $item;
                    }
                    foreach ($services as $item) {
                        $features[] = $item;
                    }
                    $preset['features'] = $features;
                    echo $twig->render('preset.twig', ["preset" => $preset]);
                }catch (\Exception|\Error $e){
                    echo $e->getMessage();
                }
            }
            ?>
            <div class="tariff-card constructor">
                <div class="constructor-badge">СВОЙ ПАКЕТ</div>
                <div class="tariff-name">Конструктор</div>
                <div class="tariff-price">от 9 990 ₽</div>
                <div class="tariff-description">Соберите свой идеальный пакет</div>
                <div class="tariff-divider"></div>
                <div class="tariff-features-wrapper">
                    <div class="tariff-features collapsed" id="features-5">
                        <div class="tariff-features-inner">
                            <div class="tariff-feature">
                                <svg class="check-svg" viewBox="0 0 24 24" fill="none" stroke="#4a90e2">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg>
                                <span><strong>Гроб</strong> — любой на выбор</span>
                            </div>
                            <div class="tariff-feature">
                                <svg class="check-svg" viewBox="0 0 24 24" fill="none" stroke="#4a90e2">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg>
                                <span><strong>Катафалк</strong> — на ваш выбор</span>
                            </div>
                            <div class="tariff-feature">
                                <svg class="check-svg" viewBox="0 0 24 24" fill="none" stroke="#4a90e2">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg>
                                <span><strong>Транспорт для гостей</strong> (нужное количество)</span>
                            </div>
                            <div class="tariff-feature">
                                <svg class="check-svg" viewBox="0 0 24 24" fill="none" stroke="#4a90e2">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg>
                                <span><strong>Цветы и венки</strong> (любое количество)</span>
                            </div>
                            <div class="tariff-feature">
                                <svg class="check-svg" viewBox="0 0 24 24" fill="none" stroke="#4a90e2">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg>
                                <span><strong>Поминальный обед</strong> (под ваши пожелания)</span>
                            </div>
                            <div class="tariff-feature">
                                <svg class="check-svg" viewBox="0 0 24 24" fill="none" stroke="#4a90e2">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg>
                                <span><strong>Дополнительные услуги</strong> (оркестр, видео, памятник)</span>
                            </div>
                            <div class="tariff-feature">
                                <svg class="check-svg" viewBox="0 0 24 24" fill="none" stroke="#4a90e2">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg>
                                <span><strong>Вы платите только за то, что выбрали</strong></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tariff-actions">
                    <button class="btn-more" onclick="toggleFeatures('features-5', this)">Подробнее</button>
                    <a href="calculator/index.php?preset=custom" class="btn btn-constructor" style="width: 100%;">Собрать</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- FAQ -->
<section class="faq">
    <div class="container">
        <h2>Часто задаваемые вопросы</h2>
        <div class="section-divider"></div>
        <div class="faq-grid">
            <div class="faq-item">
                <h3>Можно ли похоронить в выходной?</h3>
                <p>Да, мы организуем похороны в любой день, включая субботу и воскресенье.</p>
            </div>
            <div class="faq-item">
                <h3>Какие документы нужны?</h3>
                <p>Паспорт умершего, паспорт заявителя, медицинское свидетельство о смерти.</p>
            </div>
            <div class="faq-item">
                <h3>Сколько времени занимает организация?</h3>
                <p>Обычно похороны проходят на 2-3 день после заказа.</p>
            </div>
            <div class="faq-item">
                <h3>Нужно ли платить за место на кладбище?</h3>
                <p>В черте города место предоставляется бесплатно.</p>
            </div>
        </div>
    </div>
</section>

<!-- Автопарк -->
<section id="transport" class="transport">
    <div class="container">
        <h2>Автопарк</h2>
        <div class="section-divider"></div>
        <div class="subtitle">Современный транспорт для любых задач</div>

        <div class="transport-grid">
            <!-- Катафалки -->
            <div class="transport-category">
                <div class="transport-icon">
                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#d4a373" stroke-width="1.5">
                        <rect x="2" y="7" width="20" height="12" rx="2" ry="2" stroke="currentColor"/>
                        <circle cx="7" cy="17" r="2" stroke="currentColor"/>
                        <circle cx="17" cy="17" r="2" stroke="currentColor"/>
                    </svg>
                </div>
                <h3>Катафалки</h3>
                <ul class="transport-list">
                    <li><strong>Mercedes-Benz E-Class</strong> — премиум-класс</li>
                    <li><strong>Cadillac DTS</strong> — представительский</li>
                    <li><strong>Hummer H2</strong> — лимузин-катафалк</li>
                    <li><strong>ГАЗель NEXT</strong> — экономичный</li>
                </ul>
            </div>

            <!-- Автобусы для гостей -->
            <div class="transport-category">
                <div class="transport-icon">
                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#d4a373" stroke-width="1.5">
                        <rect x="2" y="7" width="20" height="12" rx="2" ry="2" stroke="currentColor"/>
                        <rect x="6" y="9" width="12" height="3" stroke="currentColor"/>
                        <circle cx="7" cy="17" r="2" stroke="currentColor"/>
                        <circle cx="17" cy="17" r="2" stroke="currentColor"/>
                    </svg>
                </div>
                <h3>Автобусы для гостей</h3>
                <ul class="transport-list">
                    <li><strong>Mercedes-Benz Sprinter</strong> — 20 мест</li>
                    <li><strong>Ford Transit</strong> — 16 мест</li>
                    <li><strong>ПАЗ Вектор NEXT</strong> — 30 мест</li>
                    <li><strong>Микроавтобусы</strong> — до 8 мест</li>
                </ul>
            </div>

            <!-- Легковые автомобили -->
            <div class="transport-category">
                <div class="transport-icon">
                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#d4a373" stroke-width="1.5">
                        <circle cx="7" cy="17" r="2" stroke="currentColor"/>
                        <circle cx="17" cy="17" r="2" stroke="currentColor"/>
                        <path d="M5 17h2M17 17h2" stroke="currentColor"/>
                        <rect x="3" y="6" width="18" height="11" rx="2" ry="2" stroke="currentColor"/>
                    </svg>
                </div>
                <h3>Легковые автомобили</h3>
                <ul class="transport-list">
                    <li><strong>Toyota Camry</strong> — для сопровождения</li>
                    <li><strong>Skoda Octavia</strong> — для поездок</li>
                    <li><strong>Hyundai Solaris</strong> — экономичный</li>
                    <li><strong>Lada Vesta</strong> — отечественный</li>
                </ul>
            </div>

            <!-- Ритуальный транспорт -->
            <div class="transport-category">
                <div class="transport-icon">
                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#d4a373" stroke-width="1.5">
                        <rect x="4" y="4" width="16" height="12" rx="2" ry="2" stroke="currentColor"/>
                        <circle cx="8" cy="16" r="2" stroke="currentColor"/>
                        <circle cx="16" cy="16" r="2" stroke="currentColor"/>
                        <line x1="8" y1="8" x2="16" y2="8" stroke="currentColor"/>
                    </svg>
                </div>
                <h3>Спецтранспорт</h3>
                <ul class="transport-list">
                    <li><strong>Грузовой катафалк</strong> — для крупных грузов</li>
                    <li><strong>Ритуальный автобус</strong> — до 20 мест</li>
                    <li><strong>Траурный лимузин</strong> — VIP-класс</li>
                    <li><strong>Автомобиль сопровождения</strong> — с мигалками</li>
                </ul>
            </div>
        </div>

        <div class="transport-note">
            <p>Все автомобили оборудованы кондиционерами, имеют тонировку и поддерживаются в идеальном состоянии. Возможна подача в любой район Москвы и области.</p>
            <a href="autopark" class="btn" style="margin-top: 20px;">Перейти в раздел</a>
        </div>
    </div>
</section>

<!-- Опыт -->
<section id="experience" class="experience">
    <div class="container">
        <div class="experience-grid">
            <div class="exp-left">
                <h2>Работаем с 2002 года</h2>
                <div class="exp-sub">Более 20 лет безупречной репутации</div>
                <div class="exp-stats">
                    <div class="exp-stat-item">
                        <div class="number">3000+</div>
                        <div class="label">похорон</div>
                    </div>
                    <div class="exp-stat-item">
                        <div class="number">50+</div>
                        <div class="label">кладбищ</div>
                    </div>
                    <div class="exp-stat-item">
                        <div class="number">24/7</div>
                        <div class="label">поддержка</div>
                    </div>
                </div>
            </div>
            <div class="exp-right">
                <p>Мы имеем большой опыт в организации похорон. В нашем распоряжении собственный автопарк, профессиональные агенты, большой выбор ритуальных товаров, создание памятников на заказ. Сервис высшей категории без лишних слов.</p>
            </div>
        </div>
    </div>
</section>

<!-- Документы -->
<section class="info-block">
    <div class="container">
        <div class="info-grid">
            <div class="info-content">
                <h3>Юридическая помощь</h3>
                <h2>Необходимые документы</h2>
                <ul class="info-list">
                    <li><strong>Свидетельство о смерти</strong> — выдаётся в ЗАГСе</li>
                    <li><strong>Справка о смерти</strong> — для получения пособия</li>
                    <li><strong>Гербовое свидетельство</strong> — для наследства</li>
                    <li><strong>Договор на похороны</strong> — оформляем с вами</li>
                </ul>
            </div>
            <div class="info-image">
                <img src="src/images/documents.jpg" alt="Документы">
            </div>
        </div>
    </div>
</section>

<?php
$config = new \lib\Config()->getConfig();
echo $twig->render("main_contacts.twig", ['config' => $config]);
echo $twig->render("page_end.twig", ["basePath" => "", "config" => $config]);
?>

<script src="src/js/jquery.min.js"></script>
<script src="src/js/toasts.js"></script>
<script src="src/js/modal.js"></script>
<script src="src/js/index.js"></script>
</body>
</html>