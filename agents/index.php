<?php
require_once __DIR__.'/../vendor/autoload.php';
use managers\AgentsManager;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

$agentManager = new AgentsManager();

$loader = new FilesystemLoader('../server/twig');
$twig = new Environment($loader, [
    //'cache' => '../server/twig/cache',
        'autoescape' => false,
]);

$agents = $agentManager->getAll();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.5, user-scalable=yes">
    <title>Наши агенты - Светлый Путь</title>
    <link rel="stylesheet" href="src/css/index.css">
    <link rel="stylesheet" href="../src/css/nav.css">
    <link rel="stylesheet" href="../src/css/modal.css">
    <link rel="stylesheet" href="../src/css/footer.css">
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
            <a href="index.php" class="active">Агенты</a>
            <a href="../calculator/">Калькулятор</a>
            <a href="../autopark/">Автопарк</a>
            <a href="../orders/">Заказы</a>
            <a onclick="openModal()" class="btn-light">Связаться</a>
        </nav>
    </div>
    <div class="overlay" id="overlay"></div>
</header>

<main>
    <!-- Заголовок страницы -->
    <section class="page-header">
        <div class="container">
            <h1>Наши агенты</h1>
            <div class="section-divider"></div>
            <p class="subtitle">Профессионалы с многолетним опытом работы</p>
        </div>
    </section>

    <!-- Сетка агентов -->
    <section class="vehicles-section">
        <div class="container">
            <div class="vehicles-grid" id="vehiclesGrid"></div>
            <div class="no-results" id="noResults">
                <p>Агенты не найдены</p>
            </div>
        </div>
    </section>

    <!-- Блок преимуществ -->
    <section class="why-choose-us">
        <div class="container">
            <h2>Почему выбирают наших агентов</h2>
            <div class="section-divider"></div>
            <div class="advantages-grid">
                <div class="advantage-item">
                    <div class="advantage-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <circle cx="12" cy="12" r="10"/>
                            <path d="M12 6v6l4 2"/>
                        </svg>
                    </div>
                    <h3>Опыт работы</h3>
                    <p>Средний стаж наших агентов — более 10 лет.</p>
                </div>
                <div class="advantage-item">
                    <div class="advantage-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <rect x="2" y="7" width="20" height="12" rx="2" ry="2"/>
                            <circle cx="7" cy="17" r="2"/>
                            <circle cx="17" cy="17" r="2"/>
                        </svg>
                    </div>
                    <h3>Индивидуальный подход</h3>
                    <p>К каждому клиенту — особое внимание и забота.</p>
                </div>
                <div class="advantage-item">
                    <div class="advantage-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path d="M12 2 L12 7 M12 12 L12 22 M12 2 L17 7 M12 2 L7 7" stroke-linecap="round"/>
                            <circle cx="12" cy="12" r="2"/>
                        </svg>
                    </div>
                    <h3>Конфиденциальность</h3>
                    <p>Гарантируем полную приватность и тактичность.</p>
                </div>
                <div class="advantage-item">
                    <div class="advantage-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <circle cx="12" cy="12" r="10"/>
                            <path d="M12 8v8M8 12h8"/>
                        </svg>
                    </div>
                    <h3>Поддержка 24/7</h3>
                    <p>Наши агенты всегда на связи в любое время.</p>
                </div>
            </div>
        </div>
    </section>
</main>

<?php
echo $twig->render("page_end.twig", ["basePath" => "../", "config" => new \lib\Config()->getConfig()]);
?>
<script src="../src/js/jquery.min.js"></script>
<script src="../src/js/toasts.js"></script>
<script src="../src/js/modal.js"></script>
<script src="src/js/index.js"></script>
<script>agentsData = <?= json_encode($agents, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?></script>
</body>
</html>