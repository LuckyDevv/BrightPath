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

/*$vehicles_data = [
        [
                "name" => "Mercedes-Benz E-Class",
                "image_path" => "mercedes-e-class/",
                "category" => 1, // 1 - катафалк
                "seats" => 2,
                "creation_year" => 2022,
                "color" => "Черный",
                "mileage" => 15000.00,
                "vin" => "WDD2120222A123456",
                "transmission" => 2, // 2 - автомат
                "fuel" => 2, // 2 - дизель
                "description_short" => "Премиальный катафалк для торжественных церемоний. Отделка салона премиум-класса.",
                "description_full" => "Премиальный катафалк для торжественных церемоний. Автомобиль оборудован всем необходимым для достойных проводов. Отделка салона выполнена из высококачественных материалов, предусмотрено место для размещения цветов и венков. Регулярное техническое обслуживание, идеальное состояние. В стоимость входит подача автомобиля к месту церемонии, услуги водителя (до 4 часов).",
                "price" => 25000.00,
                "total_stock" => 12,
                "reserved_stock" => 3,
                "is_active" => 1,
                "orders_count" => 45
        ],
        [
                "name" => "Cadillac CTS",
                "image_path" => "cadillac-cts/",
                "category" => 1, // 1 - катафалк
                "seats" => 2,
                "creation_year" => 2021,
                "color" => "Черный",
                "mileage" => 25000.00,
                "vin" => "1G6DE5EG7A0123456",
                "transmission" => 2, // 2 - автомат
                "fuel" => 1, // 1 - бензин
                "description_short" => "Представительский катафалк американского производства. Просторный салон.",
                "description_full" => "Представительский катафалк американского производства. Просторный салон, мягкая подвеска, полная шумоизоляция. Идеален для длительных церемоний. Отличный выбор для тех, кто ценит комфорт и надежность.",
                "price" => 30000.00,
                "total_stock" => 9,
                "reserved_stock" => 2,
                "is_active" => 1,
                "orders_count" => 38
        ],
        [
                "name" => "Hummer H2",
                "image_path" => "hummer-h2/",
                "category" => 1, // 1 - катафалк
                "seats" => 2,
                "creation_year" => 2020,
                "color" => "Черный",
                "mileage" => 35000.00,
                "vin" => "5GRGN23U3H1234567",
                "transmission" => 2, // 2 - автомат
                "fuel" => 1, // 1 - бензин
                "description_short" => "Уникальный лимузин-катафалк на базе Hummer. Вместительный и внушительный.",
                "description_full" => "Уникальный лимузин-катафалк на базе Hummer. Вместительный и внушительный. Привлекает внимание, подчеркивает статус. Подходит для нестандартных церемоний и VIP-клиентов.",
                "price" => 35000.00,
                "total_stock" => 4,
                "reserved_stock" => 1,
                "is_active" => 1,
                "orders_count" => 29
        ],
        [
                "name" => "ГАЗель NEXT",
                "image_path" => "gazel-next/",
                "category" => 1, // 1 - катафалк
                "seats" => 2,
                "creation_year" => 2023,
                "color" => "Черный",
                "mileage" => 5000.00,
                "vin" => "X96GA31B7N1234567",
                "transmission" => 1, // 1 - механика
                "fuel" => 2, // 2 - дизель
                "description_short" => "Экономичный и надежный катафалк для любых мероприятий.",
                "description_full" => "Экономичный и надежный катафалк для любых мероприятий. Отличный вариант для бюджетных похорон. Прост в управлении, малый расход топлива. Проверенный временем автомобиль.",
                "price" => 13000.00,
                "total_stock" => 19,
                "reserved_stock" => 4,
                "is_active" => 1,
                "orders_count" => 52
        ],
        [
                "name" => "Mercedes-Benz Sprinter",
                "image_path" => "mercedes-sprinter/",
                "category" => 2, // 2 - автобус для гостей
                "seats" => 20,
                "creation_year" => 2022,
                "color" => "Черный",
                "mileage" => 28000.00,
                "vin" => "WDB9066551N123456",
                "transmission" => 2, // 2 - автомат
                "fuel" => 2, // 2 - дизель
                "description_short" => "Комфортабельный автобус для перевозки гостей. Кондиционер, мягкие кресла.",
                "description_full" => "Комфортабельный автобус для перевозки гостей. Кондиционер, мягкие кресла, отличная шумоизоляция. Идеален для транспортировки больших групп. Вместимость до 20 человек.",
                "price" => 24000.00,
                "total_stock" => 11,
                "reserved_stock" => 2,
                "is_active" => 1,
                "orders_count" => 61
        ],
        [
                "name" => "Ford Transit",
                "image_path" => "ford-transit/",
                "category" => 2, // 2 - автобус для гостей
                "seats" => 16,
                "creation_year" => 2021,
                "color" => "Черный",
                "mileage" => 42000.00,
                "vin" => "WF0EXXTTFE1N12345",
                "transmission" => 1, // 1 - механика
                "fuel" => 2, // 2 - дизель
                "description_short" => "Надежный микроавтобус для перевозки гостей.",
                "description_full" => "Надежный микроавтобус для перевозки гостей. Оптимальное соотношение цены и качества. Просторный салон, удобные сиденья. Вместимость до 16 человек.",
                "price" => 18000.00,
                "total_stock" => 7,
                "reserved_stock" => 1,
                "is_active" => 1,
                "orders_count" => 43
        ],
        [
                "name" => "ПАЗ Вектор NEXT",
                "image_path" => "paz-vector-next/",
                "category" => 2, // 2 - автобус для гостей
                "seats" => 30,
                "creation_year" => 2022,
                "color" => "Белый",
                "mileage" => 18000.00,
                "vin" => "X1M3200N0N1234567",
                "transmission" => 1, // 1 - механика
                "fuel" => 2, // 2 - дизель
                "description_short" => "Вместительный автобус для больших групп. Отличная проходимость.",
                "description_full" => "Вместительный автобус для больших групп. Отличная проходимость, надежность. Подходит для перевозки большого количества гостей. Вместимость до 30 человек.",
                "price" => 10000.00,
                "total_stock" => 9,
                "reserved_stock" => 2,
                "is_active" => 1,
                "orders_count" => 27
        ],
        [
                "name" => "Toyota Camry",
                "image_path" => "toyota-camry/",
                "category" => 3, // 3 - легковой автомобиль
                "seats" => 4,
                "creation_year" => 2023,
                "color" => "Черный",
                "mileage" => 8000.00,
                "vin" => "JTDBE4FK9J1234567",
                "transmission" => 2, // 2 - автомат
                "fuel" => 1, // 1 - бензин
                "description_short" => "Автомобиль для сопровождения кортежа. Комфортный и престижный.",
                "description_full" => "Автомобиль для сопровождения кортежа. Комфортный и престижный. Идеален для перевозки близких родственников. Высокий уровень комфорта, кондиционер, обогрев сидений.",
                "price" => 8000.00,
                "total_stock" => 9,
                "reserved_stock" => 2,
                "is_active" => 1,
                "orders_count" => 73
        ],
        [
                "name" => "Skoda Octavia",
                "image_path" => "skoda-octavia/",
                "category" => 3, // 3 - легковой автомобиль
                "seats" => 4,
                "creation_year" => 2022,
                "color" => "Черный",
                "mileage" => 15000.00,
                "vin" => "TMBJJ9NE3N1234567",
                "transmission" => 2, // 2 - автомат
                "fuel" => 1, // 1 - бензин
                "description_short" => "Надежный автомобиль для поездок. Отличное соотношение цены и качества.",
                "description_full" => "Надежный автомобиль для поездок. Отличное соотношение цены и качества. Экономичный расход топлива, просторный багажник. Отличный выбор для сопровождения.",
                "price" => 6000.00,
                "total_stock" => 13,
                "reserved_stock" => 3,
                "is_active" => 1,
                "orders_count" => 68
        ],
        [
                "name" => "Hyundai Solaris",
                "image_path" => "hyundai-solaris/",
                "category" => 3, // 3 - легковой автомобиль
                "seats" => 4,
                "creation_year" => 2021,
                "color" => "Черный",
                "mileage" => 32000.00,
                "vin" => "Z94CT41AALR123456",
                "transmission" => 2, // 2 - автомат
                "fuel" => 1, // 1 - бензин
                "description_short" => "Экономичный автомобиль для поездок. Идеален для небольших групп.",
                "description_full" => "Экономичный автомобиль для поездок. Идеален для небольших групп. Малый расход топлива, компактный, удобный для города. Отличный бюджетный вариант.",
                "price" => 4000.00,
                "total_stock" => 17,
                "reserved_stock" => 4,
                "is_active" => 1,
                "orders_count" => 55
        ],
        [
                "name" => "DongFeng K33-561",
                "image_path" => "dongfeng-K33-561/",
                "category" => 4, // 4 - спецтранспорт
                "seats" => 2,
                "creation_year" => 2023,
                "color" => "Черный",
                "mileage" => 3000.00,
                "vin" => "LGB1ADE3N12345678",
                "transmission" => 1, // 1 - механика
                "fuel" => 2, // 2 - дизель
                "description_short" => "Специальный транспорт для перевозки крупногабаритных грузов.",
                "description_full" => "Специальный транспорт для перевозки крупногабаритных грузов. Просторный кузов, высокая грузоподъемность. Подходит для нестандартных ситуаций.",
                "price" => 22000.00,
                "total_stock" => 5,
                "reserved_stock" => 1,
                "is_active" => 1,
                "orders_count" => 19
        ],
        [
                "name" => "Lincoln Town Car III",
                "image_path" => "lincoln-town-car-iii/",
                "category" => 4, // 4 - спецтранспорт (лимузин)
                "seats" => 8,
                "creation_year" => 2002,
                "color" => "Черный",
                "mileage" => 85000.00,
                "vin" => "1LNHM81WXYY123456",
                "transmission" => 2, // 2 - автомат
                "fuel" => 1, // 1 - бензин
                "description_short" => "Роскошный лимузин для VIP-церемоний. Максимальный комфорт.",
                "description_full" => "Роскошный лимузин для VIP-церемоний. Максимальный комфорт, кожаный салон, отличная шумоизоляция. Идеален для самых требовательных клиентов. Вместимость до 8 человек.",
                "price" => 35000.00,
                "total_stock" => 7,
                "reserved_stock" => 2,
                "is_active" => 1,
                "orders_count" => 31
        ]
];
foreach ($vehicles_data as $vehicle) {
    $vehiclesManager->addVehicle($vehicle);
}*/

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
                        <option value="катафалк">Катафалки</option>
                        <option value="автобус">Автобусы для гостей</option>
                        <option value="легковой">Легковые автомобили</option>
                        <option value="спецтранспорт">Спецтранспорт</option>
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
                            <option value="катафалк">Катафалки</option>
                            <option value="автобус">Автобусы для гостей</option>
                            <option value="легковой">Легковые автомобили</option>
                            <option value="спецтранспорт">Спецтранспорт</option>
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
                            <option value="popular">По популярности</option>
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
                <?php

                ?>
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
echo $twig->render("footer.twig");
echo $twig->render("modal.twig");
?>
<!-- footer -->
<!-- modal -->
<script src="src/js/index.js"></script>
<script>
    vehiclesData = <?= json_encode($vehicles, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>;
</script>
<script src="../src/js/modal.js"></script>
</body>
</html>