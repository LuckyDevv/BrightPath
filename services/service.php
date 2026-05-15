<?php
require __DIR__ . '/../vendor/autoload.php';

use managers\ServicesManager;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Loader\FilesystemLoader;

// Настройка Twig
$loader = new FilesystemLoader('../server/twig');
$twig = new Environment($loader, [
    //'cache' => '../server/twig/cache',
        'autoescape' => false,
]);
// Получаем данные услуги
$servicesManager = new ServicesManager();

$service_id = $_GET['id'];
if ($service_id == null || !is_numeric($service_id)) {
    notFound($twig, $servicesManager);
}
$service = $servicesManager->getServiceById($service_id);

// Проверяем, найдена ли услуга

if (count($service) < 1) {
    notFound($twig, $servicesManager);
}

if (isset($service["what_includes"])) {
    $what_includes_arr = explode(";", $service["what_includes"]);
    $what_includes = "";
    foreach ($what_includes_arr as $what_include) {
        $what_includes .= "<li>✅ ". trim($what_include) . "</li>";
    }
}

// Подготавливаем данные для шаблона
$data = [
        'service' => [
            'id' => $service['id'],
            'name' => $service['name'],
            'category_name' => getCategoryName($service['category']), // функция для определения типа
            'price' => $service['price'],
            'what_includes' => $what_includes
        ],
        'page_end' => $twig->render("page_end.twig", ["basePath" => "../", "config" => new \lib\Config()->getConfig()]),
        //'similar_vehicles' => getSimilarVehicles($vehiclesManager, $vehicle['id'], $vehicle['category']) // функция для похожих авто
];

// Рендерим шаблон
echo $twig->render('service.twig', $data);

// Вспомогательные функции (можно вынести в отдельный файл)
function getCategoryName($category): string
{
    $map = [
        0 => 'Организация похорон', // Удалено!
        1 => 'Кремация',
        2 => 'Перевоз тела',
        3 => 'Юридическая помощь',
        4 => 'Ритуальный транспорт', // Удалено!
        5 => 'Поминальные обеды',
        6 => 'Памятники и благоустройство',
    ];
    return $map[$category] ?? "Не определено";
}

function notFound($twig, $servicesManager): void
{
    http_response_code(404);
    try {
        echo $twig->render('404.twig');
    } catch (LoaderError|RuntimeError|SyntaxError $e) {
        $servicesManager->createLog("vehicle", $e);
    }
    exit;
}
?>
