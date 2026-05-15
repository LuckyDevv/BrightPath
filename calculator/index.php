<?php
require_once __DIR__ . '/../vendor/autoload.php';
use managers\GoodsManager;
use managers\PresetsManager;
use managers\VehiclesManager;
use managers\ServicesManager;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

$goodsManager = new GoodsManager();
$vehiclesManager = new VehiclesManager();
$servicesManager = new ServicesManager();
$presetsManager = new PresetsManager();
$demoItems = [
        "transport" => $vehiclesManager->getAllForCalculator(),
        "goods" => $goodsManager->getAllForCalculator(),
        "services" => $servicesManager->getAllForCalculator(),
];

$loadPresetId = null;
if (isset($_GET['preset'])) {
    if (is_numeric($_GET['preset'])) {
        $loadPresetId = (int)$_GET['preset'];
    }elseif ($_GET['preset'] == 'custom') {
        $loadPresetId = 'custom';
    }
}

$presets = $presetsManager->getAllPresets();
try {
    foreach ($presets as $key => $preset) {
        $transportIds = [];
        $goodsIds = [];
        $servicesIds = [];
        foreach (json_decode($preset['transport'], true) as $item) {
            $transportIds[] = $item["id"];
        }
        foreach (json_decode($preset['goods'], true) as $item) {
            $goodsIds[] = $item["id"];
        }
        foreach (json_decode($preset['services'], true) as $item) {
            $servicesIds[] = $item["id"];
        }
        $preset['transport'] = $vehiclesManager->getForPreset($transportIds);
        $preset['goods'] = $goodsManager->getForPreset($goodsIds);
        $preset['services'] = $servicesManager->getForPreset($servicesIds);
        $presets[$key] = $preset;
    }
}catch (Error|Exception $e){
}
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
    <title>Калькулятор услуг - Светлый Путь</title>
    <link rel="stylesheet" href="src/css/style.css">
    <link rel="stylesheet" href="../src/css/nav.css">
    <link rel="stylesheet" href="../src/css/footer.css">
    <link rel="stylesheet" href="../src/css/modal.css">
    <link rel="stylesheet" href="../src/css/index.css">
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
            <a href="index.php" class="active">Калькулятор</a>
            <a href="../autopark/">Автопарк</a>
            <a href="../orders/">Заказы</a>
            <a onclick="openModal()" class="btn-light">Связаться</a>
        </nav>
    </div>
    <div class="overlay" id="overlay"></div>
</header>

<main>
    <!-- Заголовок -->
    <section class="page-header" style="padding: 35px 0">
        <div class="container" style="display: flex; align-items: center; justify-content: center; flex-direction: column">
            <h1>Калькулятор расходов</h1>
            <p class="subtitle">Рассчитайте стоимость организации похорон</p>
        </div>
    </section>

    <!-- Основной контент -->
    <section class="calculator-section">
        <div class="container">
            <!-- Пресеты тарифов -->
            <div class="presets-section">
                <h3>Быстрый выбор пакета</h3>
                <div class="presets-grid">
                    <?php
                    foreach ($presets as $preset) {
                        echo '<div class="preset-card">
                        <div class="preset-name">'.$preset['name'].'</div>
                        <div class="preset-price">от '.$preset['price'].' ₽</div>
                        <div class="preset-desc">'.$preset['quote'].'</div>
                        <button class="btn-preset" onclick="loadPreset('.$preset['id'].')">Выбрать</button>
                        </div>';
                    }
                    ?>
                    <div class="preset-card">
                        <div class="preset-name">Конструктор</div>
                        <div class="preset-price">своя сборка</div>
                        <div class="preset-desc">Соберите сами</div>
                        <button class="btn-preset" onclick="loadPreset('custom')">Очистить</button>
                    </div>
                </div>
            </div>
            <div class="calculator-grid">
                <!-- Левая колонка - выбор услуг -->
                <div class="calculator-form">
                    <!-- Категория: Транспорт -->
                    <div class="calc-category">
                        <div class="category-header">
                            <h3>🚗 Транспорт</h3>
                            <button type="button" class="btn-add-item" data-category="transport">+ Добавить</button>
                        </div>
                        <div class="category-items" id="transport-items">
                            <div class="empty-items">Нет выбранных автомобилей</div>
                        </div>
                    </div>

                    <!-- Категория: Ритуальные товары -->
                    <div class="calc-category">
                        <div class="category-header">
                            <h3>⚰️ Ритуальные товары</h3>
                            <button type="button" class="btn-add-item" data-category="goods">+ Добавить</button>
                        </div>
                        <div class="category-items" id="goods-items">
                            <div class="empty-items">Нет выбранных товаров</div>
                        </div>
                    </div>

                    <!-- Категория: Услуги -->
                    <div class="calc-category">
                        <div class="category-header">
                            <h3>🪦 Ритуальные услуги</h3>
                            <button type="button" class="btn-add-item" data-category="services">+ Добавить</button>
                        </div>
                        <div class="category-items" id="services-items">
                            <div class="empty-items">Нет выбранных услуг</div>
                        </div>
                    </div>

                    <!-- Кнопка сброса -->
                    <div class="calc-actions">
                        <button type="button" class="btn-reset-calc" id="resetCalc">Очистить всё</button>
                    </div>
                </div>

                <!-- Правая колонка - итог -->
                <div class="calculator-total">
                    <h3>Итоговая стоимость</h3>
                    <div class="total-amount" id="totalAmount">0 ₽</div>
                    <div class="total-details" id="totalDetails">
                        <p>Добавьте услуги для расчёта</p>
                    </div>
                    <button style="width: 100%" class="btn btn-order" id="orderBtn">Оформить заказ</button>
                </div>
            </div>
        </div>
    </section>
</main>
<div class="modal-overlay" id="order_modal">
    <div class="modal-container">
        <div class="modal-header">
            <h2 class="modal-title">Оформление заказа</h2>
            <button class="modal-close" onclick="orderClose()">&times;</button>
        </div>
        <div class="modal-content" style="padding: 20px 30px 10px;">
            <form class="modal-form">
                <div class="form-group">
                    <label>Как вас зовут?</label>
                    <input type="text" value="" id="order_modal_name" placeholder="Введите ваше имя">
                </div>
                <div class="form-group">
                    <label>Ваш номер телефона</label>
                    <input type="text" value="" id="order_modal_phone" placeholder="+7 (___) ___-__-__">
                </div>
                <div class="form-group">
                    <label>Ваша электронная почта</label>
                    <input type="text" value="" id="order_modal_email" placeholder="example@mail.ru">
                </div>
                <button type="button" class="modal-submit" onclick="orderCreate()">Оплатить</button>
            </form>
        </div>
    </div>
</div>
<?php echo $twig->render('page_end.twig', ['basePath' => '../', "config" => new \lib\Config()->getConfig()]); ?>
<script src="../src/js/jquery.min.js"></script>
<script src="../src/js/toasts.js"></script>
<script src="src/js/script.js"></script>
<script src="../src/js/modal.js"></script>
<script>
    demoItems = <?= json_encode($demoItems); ?>;
</script>
<script>
    presets = <?= json_encode($presets); ?>;
</script>
<?php if ($loadPresetId == 'custom'): ?>
    <script>
        loadPreset( <?= json_encode($loadPresetId); ?> , true);
    </script>
<?php else: ?>
    <script>
        loadPreset( <?= json_encode($loadPresetId); ?> , true);
    </script>
<?php endif; ?>
</body>

</html>