<?php
require __DIR__ . '/../vendor/autoload.php';

use managers\GoodsManager;
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
$goodsManager = new GoodsManager();

$service_id = $_GET['id'];
if ($service_id == null || !is_numeric($service_id)) {
    notFound($twig, $goodsManager);
}
$goods = $goodsManager->getGoodsById($service_id);

// Проверяем, найдена ли услуга

if (count($goods) < 1) {
    notFound($twig, $goodsManager);
}

// Подготавливаем данные для шаблона
$data = [
        'goods' => [
            'id' => $goods['id'],
            'name' => $goods['name'],
            'price' => $goods['price'],
            'image_path' => $goods['image_path'],
            'category' => getCategoryName($goods['category']),
            'description_full' => $goods['description_full'],
            'material' => upfirstutf($goods['material']),
            'sizes' => $goods['sizes'],
            'weight' => $goods['weight'],
            'total_stock' => $goods['total_stock'],
        ]
    //'similar_vehicles' => getSimilarVehicles($vehiclesManager, $vehicle['id'], $vehicle['category']) // функция для похожих авто
];

// Рендерим шаблон
echo $twig->render('goods_detail.twig', $data);

// Вспомогательные функции (можно вынести в отдельный файл)
function getCategoryName($category): string
{
    $map = [
            1 => 'Гробы',
            2 => 'Венки',
            3 => 'Кресты',
            4 => 'Памятники',
            5 => 'Одежда',
            6 => 'Аксессуар',
    ];
    return $map[$category] ?? "Не определено";
}

function notFound($twig, $goodsManager): void
{
    http_response_code(404);
    try {
        echo $twig->render('404.twig');
    } catch (LoaderError|RuntimeError|SyntaxError $e) {
        $goodsManager->createLog("goods_detail", $e);
    }
    exit;
}

function upfirstutf(string $str): string
{
    $char = mb_strtoupper(substr($str,0,2), "utf-8"); // это первый символ
    $str[0] = $char[0];
    $str[1] = $char[1];
    return $str;
}
?>
