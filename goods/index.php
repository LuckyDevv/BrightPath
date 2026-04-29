<?php

include_once __DIR__ . "/../vendor/autoload.php";
use managers\GoodsManager;

$goodsManager = new GoodsManager();
/*$goodsData = [
    [
        'name' => 'Гроб сосновый',
        'category' => 1,
        'material' => 'сосна',
        'image_path' => 'grob_sosnoviy.jpg',
        'description_short' => 'Классический гроб из массива сосны. Обивка тканью, крест в комплекте.',
        'description_full' => 'Гроб из натуральной сосны — традиционное и достойное решение для погребения. Изделие выполнено из массива дерева, обработано антисептическими составами. Внутренняя отделка — атласная ткань светлых тонов. В комплекте идут нательный крест, икона и погребальные принадлежности. Гроб подходит для захоронения по православному обряду. Изготовлен с соблюдением всех религиозных канонов.',
        'price' => 8900,
        'total_stock' => 25,
        'sizes' => '200x80x60',
        'weight' => 25
    ],
    [
        'name' => 'Гроб дубовый',
        'category' => 1,
        'material' => 'дуб',
        'image_path' => 'grob_duboviy.jpg',
        'description_short' => 'Премиальный гроб из массива дуба. Ручная работа, полировка, бархатная обивка.',
        'description_full' => 'Элитный дубовый гроб ручной работы. Изделие выполнено из массива дуба высшего сорта, покрыто лаком и отполировано до зеркального блеска. Внутренняя отделка — натуральный бархат тёмно-бордового цвета. По углам — резные элементы с позолотой. Гроб оснащён мягким ложем и атласной подушкой. Идеальный выбор для достойных проводов близкого человека.',
        'price' => 35000,
        'total_stock' => 8,
        'sizes' => '200x80x60',
        'weight' => 35
    ],
    [
        'name' => 'Гроб красное дерево',
        'category' => 1,
        'material' => 'красное дерево',
        'image_path' => 'grob_krasnoye_derevo.jpg',
        'description_short' => 'Элитный гроб из красного дерева с инкрустацией. Для особых церемоний.',
        'description_full' => 'Эксклюзивный гроб из красного дерева премиум-класса. Корпус украшен ручной инкрустацией, выполненной из ценных пород дерева. В комплекте — шёлковое облачение, нательный крест с позолотой, икона. Внутренняя отделка выполнена из натурального шёлка. Гроб обработан специальными составами для сохранения внешнего вида. Предназначен для самых торжественных и престижных церемоний прощания.',
        'price' => 65000,
        'total_stock' => 3,
        'sizes' => '200x80x60',
        'weight' => 30
    ],
    [
        'name' => 'Венок траурный',
        'category' => 2,
        'material' => 'искусственные цветы',
        'image_path' => 'venok.jpg',
        'description_short' => 'Траурный венок из искусственных цветов. Диаметр 60 см.',
        'description_full' => 'Классический траурный венок из высококачественных искусственных цветов. Основу венка составляют хвойные ветки, цветы — розы, хризантемы, гвоздики. Венок имеет траурную ленту с возможностью нанесения текста соболезнования. Диаметр изделия — 60 см, вес — около 2 кг. Подходит для установки на могилу, у портрета или в траурном зале. Не теряет внешний вид под воздействием погодных условий.',
        'price' => 3500,
        'total_stock' => 45,
        'sizes' => '60x60x15',
        'weight' => 2
    ],
    [
        'name' => 'Крест деревянный',
        'category' => 3,
        'material' => 'дуб',
        'image_path' => 'krest_2.jpg',
        'description_short' => 'Православный крест из дуба. Высота 120 см.',
        'description_full' => 'Православный восьмиконечный крест из массива дуба. Высота креста — 120 см, ширина перекладины — 60 см. Крест выполнен по всем канонам Русской Православной Церкви. На поверхности выгравирована надпись «Спаси и сохрани», а также изображение распятия. Крест покрыт влагостойким лаком, что позволяет устанавливать его на открытом воздухе. В комплекте идёт крепёж для монтажа.',
        'price' => 2800,
        'total_stock' => 32,
        'sizes' => '120x60x5',
        'weight' => 8
    ],
    [
        'name' => 'Памятник гранит',
        'category' => 4,
        'material' => 'гранит',
        'image_path' => 'pamyatnik_granit.jpg',
        'description_short' => 'Гранитный памятник с гравировкой. Различные варианты оформления.',
        'description_full' => 'Надгробный памятник из натурального гранита. Доступные цвета: чёрный, серый, красный. Изделие состоит из стелы, подставки и цветника. Проводим гравировку любых портретов, текстов и орнаментов. Возможно нанесение дополнительных элементов: ангелов, храмов, голубей. Поверхность полируется до глянцевого блеска. Срок изготовления — от 14 дней. Гарантия на покрытие — 5 лет.',
        'price' => 45000,
        'total_stock' => 12,
        'sizes' => '100x50x10',
        'weight' => 120
    ],
    [
        'name' => 'Одежда для погребения',
        'category' => 5,
        'material' => 'ткань',
        'image_path' => 'odezhda.jpg',
        'description_short' => 'Комплект одежды для погребения. Размеры от 46 до 60.',
        'description_full' => 'Полный комплект ритуальной одежды для усопшего. В набор входят: нижнее бельё, рубашка, костюм (или платье для женщин), носки, тапочки, платок (для женщин) или нательный крест (для мужчин). Ткань — хлопок или бязь светлых тонов. Все швы выполнены снаружи согласно религиозным требованиям. Доступны размеры от 46 до 60. Возможен пошив под заказ по индивидуальным меркам.',
        'price' => 4200,
        'total_stock' => 38,
        'sizes' => 'универсальный размер',
        'weight' => 1
    ],
    [
        'name' => 'Подушка ритуальная',
        'category' => 6,
        'material' => 'ткань',
        'image_path' => 'podushka.jpg',
        'description_short' => 'Ритуальная подушка под голову. Белая, с вышивкой.',
        'description_full' => 'Ритуальная подушка для захоронения. Изделие выполнено из белого атласа, наполнено гипоаллергенным синтепоном. На подушке ручная вышивка крестом с изображением ангела или голубя (на выбор). Подушка предназначена для размещения под головой усопшего. Края обработаны золотистой тесьмой. Размер подушки — 40×25 см. Поставляется в индивидуальном пакете.',
        'price' => 1200,
        'total_stock' => 67,
        'sizes' => '40x25x5',
        'weight' => 1
    ],
    [
        'name' => 'Венок живой',
        'category' => 2,
        'material' => 'живые цветы',
        'image_path' => 'vebok_2.jpg',
        'description_short' => 'Венок из живых цветов (розы, хризантемы). Доставка в день заказа.',
        'description_full' => 'Живой траурный венок из свежесрезанных цветов. В составе: розы, хризантемы, гвоздики, хвойная лапка. Венок оформлен траурной лентой с надписью «Скорбим», «Спи спокойно» или на ваш выбор. Диаметр венка — 55 см. Доставляется в день заказа. Срок службы живого венка — от 5 до 10 дней. Хранить рекомендуется в прохладном месте. Возможен индивидуальный подбор цветов и размера.',
        'price' => 8500,
        'total_stock' => 7,
        'sizes' => '55x55x15',
        'weight' => 3
    ],
    [
        'name' => 'Крест металлический',
        'category' => 3,
        'material' => 'металл',
        'image_path' => 'krest_metall.jpg',
        'description_short' => 'Кованый металлический крест. Покрытие черное матовое.',
        'description_full' => 'Надмогильный крест из кованого металла. Крест выполнен в классическом православном стиле, высота 100 см. Покрытие — порошковая краска чёрного матового цвета, устойчивая к коррозии и погодным условиям. Крест украшен коваными элементами с позолотой. На перекладине — надпись «Царство Небесное». Надёжное крепление включает якоря для бетонирования в грунт. Срок службы — более 20 лет.',
        'price' => 5400,
        'total_stock' => 15,
        'sizes' => '100x50x5',
        'weight' => 15
    ],
    [
        'name' => 'Памятник мрамор',
        'category' => 4,
        'material' => 'мрамор',
        'image_path' => 'pamyatnik_mramor.jpg',
        'description_short' => 'Мраморный памятник. Элитная отделка, возможна цветная гравировка.',
        'description_full' => 'Элитный памятник из натурального мрамора (месторождение Италия). Доступные цвета: белый, бежевый, розовый. В комплект входит стела, подставка, цветник, вазы. Возможна полноцветная печать на керамике (фото усопшего) или глубокая гравировка портрета на камне. Проводим гравировку любого текста с использованием золочения. Финишное покрытие — воск, обеспечивающий блеск и влагостойкость. Срок изготовления — 21 день.',
        'price' => 78000,
        'total_stock' => 4,
        'sizes' => '100x50x10',
        'weight' => 150
    ],
    [
        'name' => 'Лампада ритуальная',
        'category' => 6,
        'material' => 'стекло',
        'image_path' => 'lampada.jpg',
        'description_short' => 'Стеклянная лампада для свечей. Высота 20 см.',
        'description_full' => 'Ритуальная лампада из прозрачного стекла. Предназначена для установки на могиле или в дома скорби. Высота лампады — 20 см, диаметр основания — 10 см. Лампада закрывается металлической крышкой с крестом. В комплекте идёт металлический подстаканник. Можно использовать с плавающей свечой, восковой или электрической свечой. На стекле может быть нанесена гравировка молитвы или имени усопшего (опция).',
        'price' => 850,
        'total_stock' => 52,
        'sizes' => '20x10x10',
        'weight' => 1
    ]
];

foreach ($goodsData as $good) {
    $goodsManager->addGoods($good);
}*/

?>
<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.5, user-scalable=yes">
  <title>Ритуальные товары - Светлый Путь</title>
  <link rel="stylesheet" href="src/css/index.css">
  <link rel="stylesheet" href="../src/css/nav.css">
  <link rel="stylesheet" href="../src/css/modal.css">
  <link rel="stylesheet" href="../src/css/footer.css">
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
      <a href="index.html" class="active">Товары</a>
      <a href="../services/">Услуги</a>
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
      <h1>Ритуальные товары</h1>
      <div class="section-divider"></div>
      <p class="subtitle">Гробы, венки, кресты, памятники и всё необходимое</p>
    </div>
  </section>

  <!-- ФИЛЬТРЫ ДЛЯ ПК -->
  <section class="filters-section desktop-only">
    <div class="container">
      <div class="filters-wrapper">
        <!-- Поиск -->
        <div class="search-box">
          <input type="text" id="searchInput" placeholder="Поиск товара...">
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
            <option value="гроб">Гробы</option>
            <option value="венок">Венки</option>
            <option value="крест">Кресты</option>
            <option value="памятник">Памятники</option>
            <option value="одежда">Ритуальная одежда</option>
            <option value="аксессуары">Аксессуары</option>
          </select>
        </div>

        <!-- Фильтр по цене -->
        <div class="filter-group price-filter">
          <label>Цена (₽):</label>
          <div class="price-inputs">
            <input type="number" id="minPrice" placeholder="от" min="0" step="100">
            <span>—</span>
            <input type="number" id="maxPrice" placeholder="до" min="0" step="100">
          </div>
        </div>

        <!-- Фильтр по материалу -->
        <div class="filter-group">
          <label for="materialFilter">Материал:</label>
          <select id="materialFilter">
            <option value="all">Любой</option>
            <option value="сосна">Сосна</option>
            <option value="дуб">Дуб</option>
            <option value="красное дерево">Красное дерево</option>
            <option value="металл">Металл</option>
            <option value="пластик">Пластик</option>
          </select>
        </div>

        <!-- Фильтр по наличию -->
        <div class="filter-group">
          <label for="stockFilter">В наличии:</label>
          <select id="stockFilter">
            <option value="all">Любое количество</option>
            <option value="1">от 1 шт.</option>
            <option value="3">от 3 шт.</option>
            <option value="5">от 5 шт.</option>
            <option value="10">от 10 шт.</option>
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
            <option value="popular">По популярности</option>
          </select>
        </div>

        <!-- Кнопка сброса -->
        <button class="btn-reset" id="resetFilters">Сбросить</button>
      </div>
    </div>
  </section>

  <!-- ФИЛЬТРЫ ДЛЯ МОБИЛОК -->
  <section class="filters-section mobile-only">
    <div class="container">
      <div class="mobile-filters-header">
        <div class="mobile-search">
          <input type="text" id="mobileSearchInput" placeholder="Поиск товара...">
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
            <label for="categoryFilter_mobile">Категория:</label>
            <select id="categoryFilter_mobile">
              <option value="all">Все категории</option>
              <option value="1">Гробы</option>
              <option value="2">Венки</option>
              <option value="3">Кресты</option>
              <option value="4">Памятники</option>
              <option value="5">Ритуальная одежда</option>
              <option value="6">Аксессуары</option>
            </select>
          </div>

          <div class="filter-group price-filter">
            <label>Цена (₽):</label>
            <div class="price-inputs">
              <input type="number" id="minPrice_mobile" placeholder="от" min="0" step="100">
              <span>—</span>
              <input type="number" id="maxPrice_mobile" placeholder="до" min="0" step="100">
            </div>
          </div>

          <div class="filter-group">
            <label for="materialFilter_mobile">Материал:</label>
            <select id="materialFilter_mobile">
              <option value="all">Любой</option>
              <option value="сосна">Сосна</option>
              <option value="дуб">Дуб</option>
              <option value="красное дерево">Красное дерево</option>
              <option value="металл">Металл</option>
              <option value="пластик">Пластик</option>
            </select>
          </div>

          <div class="filter-group">
            <label for="stockFilter_mobile">В наличии:</label>
            <select id="stockFilter_mobile">
              <option value="all">Любое количество</option>
              <option value="1">от 1 шт.</option>
              <option value="3">от 3 шт.</option>
              <option value="5">от 5 шт.</option>
              <option value="10">от 10 шт.</option>
            </select>
          </div>

          <div class="filter-group">
            <label for="sortBy_mobile">Сортировка:</label>
            <select id="sortBy_mobile">
              <option value="default">По умолчанию</option>
              <option value="priceAsc">Цена (возрастание)</option>
              <option value="priceDesc">Цена (убывание)</option>
              <option value="nameAsc">Название (А-Я)</option>
              <option value="nameDesc">Название (Я-А)</option>
              <option value="popular">По популярности</option>
            </select>
          </div>

          <button class="btn-reset" id="resetFilters_mobile">Сбросить</button>
        </div>
      </div>
    </div>
  </section>

  <!-- Сетка товаров -->
  <section class="vehicles-section">
    <div class="container">
      <div class="vehicles-grid" id="vehiclesGrid"></div>
      <div class="no-results" id="noResults">
        <p>Товары не найдены</p>
      </div>
    </div>
  </section>

  <!-- Блок преимуществ -->
  <section class="why-choose-us">
    <div class="container">
      <h2>Почему выбирают наши товары</h2>
      <div class="section-divider"></div>
      <div class="advantages-grid">
        <div class="advantage-item">
          <div class="advantage-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
              <circle cx="12" cy="12" r="10"/>
              <path d="M12 6v6l4 2"/>
            </svg>
          </div>
          <h3>Высокое качество</h3>
          <p>Только проверенные материалы и производители.</p>
        </div>
        <div class="advantage-item">
          <div class="advantage-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
              <rect x="2" y="7" width="20" height="12" rx="2" ry="2"/>
              <circle cx="7" cy="17" r="2"/>
              <circle cx="17" cy="17" r="2"/>
            </svg>
          </div>
          <h3>Быстрая доставка</h3>
          <p>Доставим заказ в любую точку города за 2 часа.</p>
        </div>
        <div class="advantage-item">
          <div class="advantage-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
              <path d="M12 2 L12 7 M12 12 L12 22 M12 2 L17 7 M12 2 L7 7" stroke-linecap="round"/>
              <circle cx="12" cy="12" r="2"/>
            </svg>
          </div>
          <h3>Широкий ассортимент</h3>
          <p>Более 500 наименований товаров на любой вкус и бюджет.</p>
        </div>
        <div class="advantage-item">
          <div class="advantage-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
              <circle cx="12" cy="12" r="10"/>
              <path d="M12 8v8M8 12h8"/>
            </svg>
          </div>
          <h3>Помощь в выборе</h3>
          <p>Наши консультанты всегда готовы помочь с выбором.</p>
        </div>
      </div>
    </div>
  </section>
</main>

<!-- Футер -->
<footer class="footer">
    <div class="container">
        <div class="footer-col">
            <img src="../logo.png" alt="Светлый Путь" style="height: 40px; margin-bottom: 15px;">
            <h4>Светлый Путь</h4>
            <p>Ритуальное агентство в Одинцово. Работаем с 2002 года.</p>
        </div>
        <div class="footer-col">
            <h4>Разделы</h4>
            <ul>
                <li><a href="../goods/">Товары</a></li>
                <li><a href="../services/">Услуги</a></li>
                <li><a href="../agents/">Агенты</a></li>
                <li><a href="../calculator/">Калькулятор</a></li>
                <li><a href="../autopark/">Автопарк</a></li>
                <li><a href="../profile/">Профиль</a></li>
            </ul>
        </div>
        <div class="footer-col">
            <h4>Информация</h4>
            <ul>
                <li><a href="../politics">Правовая информация</a></li>
                <li><a href="../politics/privacy/">Политика конфиденциальности</a></li>
                <li><a href="../politics/treatment/">Политика обработки данных</a></li>
                <li><a href="../politics/terms/">Пользовательское соглашение</a></li>
                <li><a href="../politics/cookies/">Политика cookie</a></li>
            </ul>
        </div>
        <div class="footer-col">
            <h4>Контакты</h4>
            <p>+7 (987) 654-32-10<br>info@svetlyput.ru<br>Одинцово, ул. Глазынинская, 18</p>
        </div>
        <div class="footer-col">
            <h4>Соцсети</h4>
            <div class="social-links">
                <a href="https://t.me/luckydevv" aria-label="Telegram">
                    <svg width="800px" height="800px" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg" fill="#000000" class="bi bi-telegram">
                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8.287 5.906c-.778.324-2.334.994-4.666 2.01-.378.15-.577.298-.595.442-.03.243.275.339.69.47l.175.055c.408.133.958.288 1.243.294.26.006.549-.1.868-.32 2.179-1.471 3.304-2.214 3.374-2.23.05-.012.12-.026.166.016.047.041.042.12.037.141-.03.129-1.227 1.241-1.846 1.817-.193.18-.33.307-.358.336a8.154 8.154 0 0 1-.188.186c-.38.366-.664.64.015 1.088.327.216.589.393.85.571.284.194.568.387.936.629.093.06.183.125.27.187.331.236.63.448.997.414.214-.02.435-.22.547-.82.265-1.417.786-4.486.906-5.751a1.426 1.426 0 0 0-.013-.315.337.337 0 0 0-.114-.217.526.526 0 0 0-.31-.093c-.3.005-.763.166-2.984 1.09z"/>
                    </svg>
                </a>
                <a href="#" aria-label="MAX">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 720 720" fill="#000000">
                        <path d="M350.4,9.6C141.8,20.5,4.1,184.1,12.8,390.4c3.8,90.3,40.1,168,48.7,253.7,2.2,22.2-4.2,49.6,21.4,59.3,31.5,11.9,79.8-8.1,106.2-26.4,9-6.1,17.6-13.2,24.2-22,27.3,18.1,53.2,35.6,85.7,43.4,143.1,34.3,299.9-44.2,369.6-170.3C799.6,291.2,622.5-4.6,350.4,9.6h0ZM269.4,504c-11.3,8.8-22.2,20.8-34.7,27.7-18.1,9.7-23.7-.4-30.5-16.4-21.4-50.9-24-137.6-11.5-190.9,16.8-72.5,72.9-136.3,150-143.1,78-6.9,150.4,32.7,183.1,104.2,72.4,159.1-112.9,316.2-256.4,218.6h0Z"/>
                    </svg>
                </a>
                <a href="https://vk.com/luckydevv" aria-label="VK">
                    <svg fill="#000000" width="800px" height="800px" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" data-name="Layer 1">
                        <path d="M15.07294,2H8.9375C3.33331,2,2,3.33331,2,8.92706V15.0625C2,20.66663,3.32294,22,8.92706,22H15.0625C20.66669,22,22,20.67706,22,15.07288V8.9375C22,3.33331,20.67706,2,15.07294,2Zm3.07287,14.27081H16.6875c-.55206,0-.71875-.44793-1.70831-1.4375-.86463-.83331-1.22919-.9375-1.44794-.9375-.30206,0-.38544.08332-.38544.5v1.3125c0,.35419-.11456.5625-1.04162.5625a5.69214,5.69214,0,0,1-4.44794-2.66668A11.62611,11.62611,0,0,1,5.35419,8.77081c0-.21875.08331-.41668.5-.41668H7.3125c.375,0,.51044.16668.65625.55212.70831,2.08331,1.91669,3.89581,2.40625,3.89581.1875,0,.27081-.08331.27081-.55206V10.10413c-.0625-.97913-.58331-1.0625-.58331-1.41663a.36008.36008,0,0,1,.375-.33337h2.29169c.3125,0,.41662.15625.41662.53125v2.89587c0,.3125.13544.41663.22919.41663.1875,0,.33331-.10413.67706-.44788a11.99877,11.99877,0,0,0,1.79169-2.97919.62818.62818,0,0,1,.63544-.41668H17.9375c.4375,0,.53125.21875.4375.53125A18.20507,18.20507,0,0,1,16.41669,12.25c-.15625.23956-.21875.36456,0,.64581.14581.21875.65625.64582,1,1.05207a6.48553,6.48553,0,0,1,1.22912,1.70837C18.77081,16.0625,18.5625,16.27081,18.14581,16.27081Z"/>
                    </svg>
                </a>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="footer-bottom">
            © 2026 Ритуальное агентство «Светлый Путь». Курсовой проект по разработке информационных систем.
        </div>
    </div>
</footer>

<!-- Модальное окно "Связаться" -->
<div class="modal-overlay" id="modalOverlay">
  <div class="modal-container">
    <button class="modal-close" id="modalClose">&times;</button>
    <div class="modal-content">
      <h2>Связаться с нами</h2>
      <p class="modal-subtitle">Оставьте свои контакты, и мы свяжемся с вами в ближайшее время</p>

      <form class="modal-form" id="contactForm">
        <div class="form-group">
          <label for="modalName">Ваше имя</label>
          <input type="text" id="modalName" placeholder="Введите ваше имя">
        </div>

        <div class="form-group">
          <label for="modalPhone">Номер телефона <span class="required">*</span></label>
          <input type="tel" id="modalPhone" placeholder="+7 (___) ___-__-__" required>
        </div>

        <div class="form-group">
          <label for="modalEmail">Электронная почта</label>
          <input type="email" id="modalEmail" placeholder="example@mail.ru">
        </div>

        <div class="form-agreement">
          <input type="checkbox" id="modalAgreement" checked>
          <label for="modalAgreement">Согласен на обработку персональных данных</label>
        </div>

        <button type="submit" class="modal-submit-btn" id="modalSubmitBtn">Отправить</button>
      </form>

      <div class="modal-contacts">
        <h3>Или свяжитесь напрямую:</h3>
        <div class="modal-contact-item">
          <svg viewBox="0 0 24 24" width="20" height="20">
            <rect x="5" y="2" width="14" height="20" rx="2" ry="2" stroke="currentColor" stroke-width="1.5" fill="none"/>
            <line x1="12" y1="18" x2="12" y2="18" stroke="currentColor" stroke-width="1.5"/>
          </svg>
          <a href="tel:+79876543210">+7 (987) 654-32-10</a>
        </div>
        <div class="modal-contact-item">
          <svg viewBox="0 0 24 24" width="20" height="20">
            <rect x="2" y="4" width="20" height="16" rx="2" ry="2" stroke="currentColor" stroke-width="1.5" fill="none"/>
            <polyline points="22,6 12,13 2,6" stroke="currentColor" stroke-width="1.5"/>
          </svg>
          <a href="mailto:info@svetlyput.ru">info@svetlyput.ru</a>
        </div>
      </div>

      <div class="modal-success" id="modalSuccess">
        <svg viewBox="0 0 24 24" width="48" height="48">
          <circle cx="12" cy="12" r="10" fill="#d4a373"/>
          <polyline points="8 12 11 15 16 9" stroke="white" stroke-width="2" fill="none"/>
        </svg>
        <h3>Спасибо!</h3>
        <p>Мы свяжемся с вами в ближайшее время.</p>
      </div>
    </div>
  </div>
</div>

<script src="../src/js/jquery.min.js"></script>
<script src="src/js/index.js"></script>
<script>goodsData = <?= json_encode($goodsManager->getAllGoods()) ?></script>
<script src="../src/js/modal.js"></script>
</body>
</html>