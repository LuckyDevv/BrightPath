<?php
require __DIR__.'/../vendor/autoload.php';

use managers\VehiclesManager;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

// Настройка Twig
$loader = new FilesystemLoader('../server/twig');
$twig = new Environment($loader, [
    //'cache' => '../server/twig/cache',
    'autoescape' => false,
]);
// Получаем данные автомобиля
$vehiclesManager = new VehiclesManager();

$vehicle_id = $_GET['id'];
if ($vehicle_id == null) {
    notFound($twig, $vehiclesManager);
}
$vehicle = $vehiclesManager->getVehicleById($vehicle_id);

// Проверяем, найден ли автомобиль

if (count($vehicle) < 1) {
    notFound($twig, $vehiclesManager);
}

// Подготавливаем данные для шаблона
$data = [
    'vehicle' => [
        'id' => $vehicle['id'],
        'name' => $vehicle['name'],
        'images' => getImages($vehicle['image_path']),
        'category_name' => getCategoryName($vehicle['category']), // функция для определения типа
        'price' => $vehicle['price'],
        'available_stock' => $vehicle['total_stock'] - $vehicle['reserved_stock'],
        'creation_year' => $vehicle['creation_year'],
        'color' => $vehicle['color'],
        'transmission_text' => getTransmissionText($vehicle['transmission']),
        'fuel_text' => getFuelText($vehicle['fuel']),
        'drive_type' => 'Задний',
        'seats' => $vehicle['seats'],
        'mileage' => $vehicle['mileage'] ?? 15000,
        'vin' => $vehicle['vin'] ?? 'WDD0000000XXXXXX',
        'description_full' => $vehicle['description_full']
    ],
    'similar_vehicles' => getSimilarVehicles($vehiclesManager, $vehicle['id'], $vehicle['category']) // функция для похожих авто
];

// Рендерим шаблон
echo $twig->render('vehicle.twig', $data);

// Вспомогательные функции (можно вынести в отдельный файл)
function getCategoryName($category): string
{
    $map = [
        1 => 'Катафалк',
        2 => 'Автобус для гостей',
        3 => 'Легковой автомобиль',
        4 => 'Спецтранспорт'
    ];
    return $map[$category] ?? "Не определено";
}

function getImages($imagesPath): array
{
    $images = scandir(__DIR__."/../src/images/vehicles/".$imagesPath);
    $result = [
        "main" => '/../src/images/no_image.jpg" style="object-fit: fill;',
        "additional_images" => []
    ];
    foreach ($images as $image) {
        if ($image != "." && $image != "..") {
            if (str_contains($image, "main")) {
                $result["main"] =  "/../src/images/vehicles/".$imagesPath."/".$image;
            }elseif (str_contains($image, "additional_")) {
                $result["additional_images"][] =  "/../src/images/vehicles/".$imagesPath."/".$image;
            }else continue;
        }
    }
    return $result;
}

function getTransmissionText($code): string
{
    $map = [
        1 => 'Механика',
        2 => 'Автомат',
        3 => 'Робот',
        4 => 'Вариатор'
    ];
    return $map[$code] ?? 'Автомат';
}

function getFuelText($code): string
{
    $map = [
        1 => 'Бензин',
        2 => 'Дизель',
        3 => 'Электро',
        4 => 'Гибрид'
    ];
    return $map[$code] ?? 'Бензин';
}

function getSimilarVehicles(VehiclesManager $vehiclesManager, int $currentId, int $category): array
{
    // Здесь логика получения похожих авто
    // Например, те же категории, но не текущее авто
    return $vehiclesManager->getSimilar($currentId, $category); // временно
}

function notFound($twig, $vehiclesManager): void
{
    http_response_code(404);
    try {
        echo $twig->render('404.twig');
    } catch (\Twig\Error\LoaderError|\Twig\Error\RuntimeError|\Twig\Error\SyntaxError $e) {
        $vehiclesManager->createLog("vehicle", $e);
    }
    exit;
}
?>