<?php
require __DIR__.'/../vendor/autoload.php';
use managers\VehiclesManager;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
$vehiclesManager = new VehiclesManager();
$vehicles = $vehiclesManager->getAllVehicles();
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
    <title>Автопарк - Светлый Путь</title>
    <link rel="stylesheet" href="src/css/index.css">
    <link rel="stylesheet" href="../src/css/nav.css">
    <link rel="stylesheet" href="../src/css/modal.css">
    <link rel="stylesheet" href="../src/css/footer.css">
    <link rel="stylesheet" href="../src/css/toasts.css">
    <link rel="icon" href="../logo.png" type="image/png">
</head>
<body>
<?php
echo $twig->render("header.twig");
?>
<!-- header -->
<main>
    <!-- Заголовок страницы -->
    <section class="page-header">
        <div class="container">
            <h1>Автопарк</h1>
            <div class="section-divider"></div>
            <p class="subtitle">Современный транспорт для любых задач</p>
        </div>
    </section>

    <!-- ФИЛЬТРЫ ДЛЯ ПК (видно только на больших экранах) -->
    <section class="filters-section desktop-only">
        <div class="container">
            <div class="filters-wrapper">
                <!-- Поиск -->
                <div class="search-box">
                    <input type="text" id="searchInput" placeholder="Поиск автомобиля...">
                    <svg class="search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <circle cx="11" cy="11" r="8" stroke-width="2"/>
                        <line x1="21" y1="21" x2="16.65" y2="16.65" stroke-width="2"/>
                    </svg>
                </div>

                <!-- Фильтр по категории -->
                <div class="filter-group">
                    <label for="categoryFilter">Категория:</label>
                    <select id="categoryFilter">
                        <option value="all">Все категории</option>
                        <option value="1">Катафалки</option>
                        <option value="2">Автобусы для гостей</option>
                        <option value="3">Легковые автомобили</option>
                        <option value="4">Спецтранспорт</option>
                    </select>
                </div>

                <!-- Фильтр по цене -->
                <div class="filter-group price-filter">
                    <label>Цена (₽):</label>
                    <div class="price-inputs">
                        <input type="number" id="minPrice" placeholder="от" min="0" step="1000">
                        <span>—</span>
                        <input type="number" id="maxPrice" placeholder="до" min="0" step="1000">
                    </div>
                </div>

                <!-- Фильтр по количеству мест -->
                <div class="filter-group">
                    <label for="seatsFilter">Мест:</label>
                    <select id="seatsFilter">
                        <option value="all">Любое</option>
                        <option value="4">до 4 мест</option>
                        <option value="8">до 8 мест</option>
                        <option value="16">до 16 мест</option>
                        <option value="20">до 20 мест</option>
                        <option value="30">до 30 мест</option>
                    </select>
                </div>

                <!-- Сортировка -->
                <div class="filter-group">
                    <label for="sortBy">Сортировка:</label>
                    <select id="sortBy">
                        <option value="default">По умолчанию</option>
                        <option value="priceAsc">Цена (возрастание)</option>
                        <option value="priceDesc">Цена (убывание)</option>
                        <option value="nameAsc">Название (А-Я)</option>
                        <option value="nameDesc">Название (Я-А)</option>
                        <option value="yearDesc">Новые сначала</option>
                        <option value="yearAsc">Старые сначала</option>
                    </select>
                </div>

                <!-- Фильтр по количеству доступных машин -->
                <div class="filter-group">
                    <label for="stockFilter">В наличии:</label>
                    <select id="stockFilter">
                        <option value="all">Любое количество</option>
                        <option value="1">от 1 машины</option>
                        <option value="3">от 3 машин</option>
                        <option value="5">от 5 машин</option>
                        <option value="10">от 10 машин</option>
                    </select>
                </div>

                <!-- Кнопка сброса -->
                <button class="btn-reset" id="resetFilters">Сбросить</button>
            </div>
        </div>
    </section>

    <!-- ФИЛЬТРЫ ДЛЯ МОБИЛОК (видно только на маленьких экранах) -->
    <section class="filters-section mobile-only">
        <div class="container">
            <!-- Мобильный хедер фильтров -->
            <div class="mobile-filters-header">
                <div class="mobile-search">
                    <input type="text" id="mobileSearchInput" placeholder="Поиск автомобиля...">
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

            <!-- Выпадающая панель с фильтрами -->
            <div class="filters-wrapper" id="filtersWrapper">
                <div class="filters-content">
                    <div class="filters-header">
                        <h3>Фильтры</h3>
                        <button class="close-filters" id="closeFilters">&times;</button>
                    </div>

                    <!-- Фильтр по категории -->
                    <div class="filter-group">
                        <label for="categoryFilter_mobile">Категория:</label>
                        <select id="categoryFilter_mobile">
                            <option value="all">Все категории</option>
                            <option value="1">Катафалки</option>
                            <option value="2">Автобусы для гостей</option>
                            <option value="3">Легковые автомобили</option>
                            <option value="4">Спецтранспорт</option>
                        </select>
                    </div>

                    <!-- Фильтр по цене -->
                    <div class="filter-group price-filter">
                        <label>Цена (₽):</label>
                        <div class="price-inputs">
                            <input type="number" id="minPrice_mobile" placeholder="от" min="0" step="1000">
                            <span>—</span>
                            <input type="number" id="maxPrice_mobile" placeholder="до" min="0" step="1000">
                        </div>
                    </div>

                    <!-- Фильтр по количеству мест -->
                    <div class="filter-group">
                        <label for="seatsFilter_mobile">Мест:</label>
                        <select id="seatsFilter_mobile">
                            <option value="all">Любое</option>
                            <option value="4">до 4 мест</option>
                            <option value="8">до 8 мест</option>
                            <option value="16">до 16 мест</option>
                            <option value="20">до 20 мест</option>
                            <option value="30">до 30 мест</option>
                        </select>
                    </div>

                    <!-- Сортировка -->
                    <div class="filter-group">
                        <label for="sortBy_mobile">Сортировка:</label>
                        <select id="sortBy_mobile">
                            <option value="default">По умолчанию</option>
                            <option value="priceAsc">Цена (возрастание)</option>
                            <option value="priceDesc">Цена (убывание)</option>
                            <option value="nameAsc">Название (А-Я)</option>
                            <option value="nameDesc">Название (Я-А)</option>
                            <option value="yearDesc">Новые сначала</option>
                            <option value="yearAsc">Старые сначала</option>
                        </select>
                    </div>

                    <div class="filter-group">
                        <label for="stockFilter_mobile">В наличии:</label>
                        <select id="stockFilter_mobile">
                            <option value="all">Любое количество</option>
                            <option value="1">от 1 машины</option>
                            <option value="3">от 3 машин</option>
                            <option value="5">от 5 машин</option>
                            <option value="10">от 10 машин</option>
                        </select>
                    </div>

                    <!-- Кнопка сброса -->
                    <button class="btn-reset" id="resetFilters_mobile">Сбросить</button>
                </div>
            </div>
        </div>
    </section>

    <!-- Сетка автомобилей -->
    <section class="vehicles-section">
        <div class="container">
            <div class="vehicles-grid" id="vehiclesGrid">
                <!-- Карточки будут добавляться через JavaScript -->
            </div>

            <!-- Сообщение о пустом результате -->
            <div class="no-results" id="noResults">
                <p>Автомобили не найдены</p>
            </div>
        </div>
    </section>

    <!-- Блок преимуществ -->
    <section class="why-choose-us">
        <div class="container">
            <h2>Почему выбирают наш автопарк</h2>
            <div class="section-divider"></div>
            <div class="advantages-grid">
                <div class="advantage-item">
                    <div class="advantage-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <circle cx="12" cy="12" r="10"/>
                            <path d="M12 6v6l4 2"/>
                        </svg>
                    </div>
                    <h3>Быстрая подача</h3>
                    <p>Автомобиль подаётся в любую точку города от 30 минут.</p>
                </div>
                <div class="advantage-item">
                    <div class="advantage-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <rect x="2" y="7" width="20" height="12" rx="2" ry="2"/>
                            <circle cx="7" cy="17" r="2"/>
                            <circle cx="17" cy="17" r="2"/>
                        </svg>
                    </div>
                    <h3>Современный парк</h3>
                    <p>Большинство автомобилей не старше 5 лет, регулярное техобслуживание.</p>
                </div>
                <div class="advantage-item">
                    <div class="advantage-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path d="M12 2 L12 7 M12 12 L12 22 M12 2 L17 7 M12 2 L7 7" stroke-linecap="round"/>
                            <circle cx="12" cy="12" r="2"/>
                        </svg>
                    </div>
                    <h3>Внимание к деталям</h3>
                    <p>Чистота, исправность, комфорт – мы следим за каждой мелочью.</p>
                </div>
                <div class="advantage-item">
                    <div class="advantage-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <circle cx="12" cy="12" r="10"/>
                            <path d="M12 8v8M8 12h8"/>
                        </svg>
                    </div>
                    <h3>Дополнительные услуги</h3>
                    <p>Водитель, ритуальное оформление, помощь в организации.</p>
                </div>
            </div>
        </div>
    </section>
</main>
<?php
echo $twig->render("page_end.twig", ['basePath' => '../', "config" => new \lib\Config()->getConfig()]);
?>
<script src="../src/js/jquery.min.js"></script>
<script src="../src/js/toasts.js"></script>
<script src="../src/js/modal.js"></script>
<script src="src/js/index.js"></script>
<script src="../src/js/cartHelper.js"></script>
<script>
    vehiclesData = <?= json_encode($vehicles, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>;
</script>
</body>
</html>