<?php
require __DIR__.'/../vendor/autoload.php';
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use managers\ServicesManager;
$servicesManager = new ServicesManager();
$loader = new FilesystemLoader('../server/twig');
$twig = new Environment($loader, [
    //'cache' => '../server/twig/cache',
        'autoescape' => false,
]);
/*$services = [
        [
                "name" => "Организация похорон под ключ",
                "category" => 0,
                "description" => "Полное сопровождение процесса: оформление документов, организация церемонии, транспорт, помощь в выборе места захоронения.",
                "what_includes" => "Консультация специалиста (выезд на дом); Оформление свидетельства о смерти; Получение гербового свидетельства; Организация транспорта (катафалк, автобусы); Подбор и доставка гроба и ритуальных принадлежностей; Организация места захоронения; Координация с моргом и кладбищем; Помощь в получении пособия на погребение.",
                "price" => 19900,
                "is_active" => true
        ],
        [
                "name" => "Кремация",
                "category" => 1,
                "description" => "Организация кремации в любом крематории Москвы и Московской области. Помощь в выборе урны и места захоронения праха.",
                "what_includes" => "Оформление всех необходимых документов; Доставка тела в крематорий; Аренда траурного зала для прощания; Предоставление урны для праха (базовая); Гравировка на урне; Организация захоронения урны в колумбарий или в землю; Помощь в получении пособия на погребение.",
                "price" => 80000,
                "is_active" => true
        ],
        [
                "name" => "Перевоз тела в другой город",
                "category" => 2,
                "description" => "Организация перевозки тела в любой регион России. Полное сопровождение, оформление всех необходимых документов.",
                "what_includes" => "Оформление разрешительных документов; Предоставление специального транспорта (санитарный автомобиль); Сопровождение груза 200; Бальзамирование тела (при необходимости); Герметичный цинковый гроб; Координация с моргом в пункте назначения; Полное юридическое сопровождение.",
                "price" => 25000,
                "is_active" => true
        ],
        [
                "name" => "Перевоз тела за границу",
                "category" => 2,
                "description" => "Организация репатриации тела за рубеж. Оформление международных документов, сопровождение.",
                "what_includes" => "Оформление международного разрешения на вывоз тела; Предоставление герметичного цинкового гроба; Бальзамирование тела; Организация перевозки (авиа, ЖД, авто); Сопровождение груза 200 до пункта назначения; Консульская поддержка; Полное юридическое сопровождение.",
                "price" => 80000,
                "is_active" => true
        ],
        [
                "name" => "Юридическая помощь",
                "category" => 3,
                "description" => "Консультации по вопросам получения пособий, оформлению наследства, взаимодействию с госорганами.",
                "what_includes" => "Консультация специалиста по ритуальным вопросам; Помощь в оформлении свидетельства о смерти; Помощь в получении гербового свидетельства; Оформление справки на пособие (форма №11); Консультация по вопросам наследства; Помощь в оформлении документов на захоронение; Представительство в государственных органах.",
                "price" => 7000,
                "is_active" => true
        ],
        [
                "name" => "Помощь в получении пособия на погребение",
                "category" => 3,
                "description" => "Сбор и оформление документов для получения государственного пособия на погребение.",
                "what_includes" => "Консультация по размеру пособия и условиям получения; Помощь в сборе необходимых документов; Заполнение заявления на выплату; Подача документов в Социальный фонд (СФР); Контроль прохождения заявки; Консультация по региональным доплатам.",
                "price" => 3000,
                "is_active" => true
        ],
        [
                "name" => "Поминальный обед",
                "category" => 5,
                "description" => "Организация поминального обеда в кафе или ресторане. Подбор меню, зала, обслуживание.",
                "what_includes" => "Подбор зала (кафе, ресторан, банкетный зал); Составление поминального меню; Организация обслуживания; Поминальные блюда (кутья, блины, кисель); Подбор ведущего (при необходимости); Украшение зала; Координация всех гостей.",
                "price" => 15000,
                "is_active" => true
        ],
        [
                "name" => "Изготовление памятников",
                "category" => 6,
                "description" => "Изготовление памятников из гранита и мрамора на заказ. Доставка и установка.",
                "what_includes" => "Консультация по выбору материала (гранит, мрамор); Разработка эскиза памятника; Изготовление памятника на заказ; Гравировка портрета и текста; Доставка на кладбище; Профессиональная установка; Гарантия на работы.",
                "price" => 30000,
                "is_active" => true
        ],
        [
                "name" => "Благоустройство могилы",
                "category" => 6,
                "description" => "Установка оград, столов, лавочек. Посадка цветов, укладка плитки.",
                "what_includes" => "Установка ограды (металлической, кованой); Монтаж стола и лавочки; Укладка тротуарной плитки; Посадка цветов и кустарников; Отсыпка гравием; Установка подставки для цветов; Полный комплекс работ по благоустройству.",
                "price" => 10000,
                "is_active" => true
        ],
        [
                "name" => "Оформление документов",
                "category" => 0,
                "description" => "Полное оформление всех необходимых документов для захоронения.",
                "what_includes" => "Оформление медицинского свидетельства о смерти; Получение гербового свидетельства в ЗАГС; Оформление справки на пособие (форма №11); Помощь в получении удостоверения о захоронении; Консультация по перечню документов; Сокращение времени оформления до 1 дня.",
                "price" => 4000,
                "is_active" => true
        ],
        [
                "name" => "Организация гражданской панихиды",
                "category" => 0,
                "description" => "Аренда зала, ведущий, музыкальное сопровождение, оформление.",
                "what_includes" => "Аренда траурного зала; Услуги ведущего церемонии (тамады); Музыкальное сопровождение (живая музыка, оркестр); Оформление зала цветами и лентами; Расстановка венков и корзин; Помощь в составлении текста прощания; Полная координация церемонии.",
                "price" => 12000,
                "is_active" => true
        ]
];
foreach ($services as $service) {
    $servicesManager->addService($service);
}*/
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.5, user-scalable=yes">
    <title>Ритуальные услуги - Светлый Путь</title>
    <link rel="stylesheet" href="src/css/style.css">
    <link rel="stylesheet" href="../src/css/index.css">
    <link rel="stylesheet" href="../src/css/nav.css">
    <link rel="stylesheet" href="../src/css/footer.css">
    <link rel="stylesheet" href="../src/css/modal.css">
    <link rel="icon" href="../logo.png" type="image/png">
    <style>
        .section-divider {
            height: 1px;
            background: linear-gradient(90deg, transparent, #d4a373, transparent);
            width: 100%;
            max-width: 200px;
            margin: 0 auto 15px;
        }
    </style>
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
            <a href="index.php" class="active">Услуги</a>
            <a href="../agents/">Агенты</a>
            <a href="../calculator/">Калькулятор</a>
            <a href="../autopark/">Автопарк</a>
            <a href="../profile/">Профиль</a>
            <a onclick="openModal()" class="btn-light">Связаться</a>
        </nav>
    </div>
    <div class="overlay" id="overlay"></div>
</header>


<main>
    <!-- Заголовок страницы -->
    <section class="page-header">
        <div class="container">
            <h1>Ритуальные услуги</h1>
            <div class="section-divider"></div>
            <p class="subtitle">Полный спектр услуг для достойных проводов</p>
        </div>
    </section>

    <!-- ФИЛЬТРЫ (те же, что в автопарке) -->
    <section class="filters-section desktop-only">
        <div class="container">
            <div class="filters-wrapper">
                <div class="search-box">
                    <input type="text" id="searchInput" placeholder="Поиск услуги...">
                    <svg class="search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <circle cx="11" cy="11" r="8" stroke-width="2"/>
                        <line x1="21" y1="21" x2="16.65" y2="16.65" stroke-width="2"/>
                    </svg>
                </div>

                <div class="filter-group">
                    <label for="categoryFilter">Категория:</label>
                    <select id="categoryFilter">
                        <option value="all">Все услуги</option>
                        <option value="1">Кремация</option>
                        <option value="2">Перевоз тела</option>
                        <option value="3">Юридическая помощь</option>
                        <option value="5">Поминальные обеды</option>
                        <option value="6">Памятники и благоустройство</option>
                    </select>
                </div>

                <div class="filter-group price-filter">
                    <label>Цена (₽):</label>
                    <div class="price-inputs">
                        <input type="number" id="minPrice" placeholder="от" min="0" step="500">
                        <span>—</span>
                        <input type="number" id="maxPrice" placeholder="до" min="0" step="500">
                    </div>
                </div>

                <div class="filter-group">
                    <label for="sortBy">Сортировка:</label>
                    <select id="sortBy">
                        <option value="default">По умолчанию</option>
                        <option value="priceAsc">Цена (возрастание)</option>
                        <option value="priceDesc">Цена (убывание)</option>
                        <option value="nameAsc">Название (А-Я)</option>
                        <option value="nameDesc">Название (Я-А)</option>
                        <option value="popular">По популярности</option>
                    </select>
                </div>

                <button class="btn-reset" id="resetFilters">Сбросить</button>
            </div>
        </div>
    </section>

    <!-- ФИЛЬТРЫ ДЛЯ МОБИЛОК -->
    <section class="filters-section mobile-only">
        <div class="container">
            <div class="mobile-filters-header">
                <div class="mobile-search">
                    <input type="text" id="mobileSearchInput" placeholder="Поиск услуги...">
                    <svg class="search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <circle cx="11" cy="11" r="8" stroke-width="2"/>
                        <line x1="21" y1="21" x2="16.65" y2="16.65" stroke-width="2"/>
                    </svg>
                </div>
                <button class="mobile-filters-toggle" id="filtersToggle">
                    <svg viewBox="0 0 24 24" width="18" height="18">
                        <path d="M4 6h16M4 12h16M4 18h16" stroke="currentColor" stroke-width="2"/>
                    </svg>
                    Фильтры
                </button>
            </div>

            <div class="filters-wrapper" id="filtersWrapper">
                <div class="filters-content">
                    <div class="filters-header">
                        <h3>Фильтры</h3>
                        <button class="close-filters" id="closeFilters">&times;</button>
                    </div>

                    <div class="filter-group">
                        <label for="categoryFilterMobile">Категория:</label>
                        <select id="categoryFilterMobile">
                            <option value="all">Все услуги</option>
                            <option value="0">Организация похорон</option>
                            <option value="1">Кремация</option>
                            <option value="2">Перевоз тела</option>
                            <option value="3">Юридическая помощь</option>
                            <option value="4">Ритуальный транспорт</option>
                            <option value="5">Поминальные обеды</option>
                            <option value="6">Памятники и благоустройство</option>
                        </select>
                    </div>

                    <div class="filter-group price-filter">
                        <label>Цена (₽):</label>
                        <div class="price-inputs">
                            <input type="number" id="minPriceMobile" placeholder="от" min="0" step="500">
                            <span>—</span>
                            <input type="number" id="maxPriceMobile" placeholder="до" min="0" step="500">
                        </div>
                    </div>

                    <div class="filter-group">
                        <label for="sortByMobile">Сортировка:</label>
                        <select id="sortByMobile">
                            <option value="default">По умолчанию</option>
                            <option value="priceAsc">Цена (возрастание)</option>
                            <option value="priceDesc">Цена (убывание)</option>
                            <option value="nameAsc">Название (А-Я)</option>
                            <option value="nameDesc">Название (Я-А)</option>
                            <option value="popular">По популярности</option>
                        </select>
                    </div>

                    <button class="btn-reset" id="resetFiltersMobile">Сбросить</button>
                </div>
            </div>
        </div>
    </section>

    <!-- Сетка услуг -->
    <section class="services-section">
        <div class="container">
            <div class="services-grid" id="servicesGrid"></div>
            <div class="no-results" id="noResults">
                <p>Услуги не найдены</p>
            </div>
        </div>
    </section>
</main>

<?php
echo $twig->render("footer.twig");
echo $twig->render("modal.twig");
?>
<script src="src/js/script.js"></script>
<script>servicesData = <?=json_encode($servicesManager->getAllServices()); ?></script>
<script src="../src/js/modal.js"></script>
</body>
</html>