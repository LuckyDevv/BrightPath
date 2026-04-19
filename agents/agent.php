<?php
require_once __DIR__.'/../vendor/autoload.php';
use managers\AgentsManager;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

// Настройка Twig
$loader = new FilesystemLoader('../server/twig');
$twig = new Environment($loader, [
    //'cache' => '../server/twig/cache',
    'autoescape' => false,
]);
$agentsManager = new AgentsManager();

$agentId = $_GET['id'];
if ($agentId == null || !is_numeric($agentId)) {
    notFound($twig, $agentsManager);
}
$agent = $agentsManager->getById($agentId);
if (!$agent) {
    notFound($twig, $agentsManager);
}

$image = __DIR__.'/../src/images/agents/'.$agent['image_path'];
if (!file_exists($image)) {
    $image = '../src/images/no_image.jpg';
}else{
    $image = '/../src/images/agents/'.$agent['image_path'];
}

$age = $agent['age'].' '.number($agent['age'], array('год', 'года', 'лет'));
$experience = $agent['experience'].' '.number($agent['experience'], array('год', 'года', 'лет'));
$position = [
    1 => "Руководитель",
    2 => "Ведущий агент",
    3 => "Старший агент",
    4 => "Менеджер",
    5 => "Агент"
];
$position = $position[$agent['position']];
$data = [
    'name' => $agent['name'],
    'position' => $position,
    'age' => $age,
    'experience' => $experience,
    'events_count' => $agent['events_count'],
    'image' => $image,
    'description' => $agent['description'],
    'biographic' => $agent['biographic'],
];

try {
    echo $twig->render('agent.twig', $data);
}catch (\Exception $exception){
    notFound($twig, $agentsManager);
}

function number($n, $titles) {
    $cases = array(2, 0, 1, 1, 1, 2);
    return $titles[($n % 100 > 4 && $n % 100 < 20) ? 2 : $cases[min($n % 10, 5)]];
}

function notFound($twig, AgentsManager $agentsManager): void
{
    http_response_code(404);
    try {
        echo $twig->render('404.twig');
    } catch (\Twig\Error\LoaderError|\Twig\Error\RuntimeError|\Twig\Error\SyntaxError $e) {
        $agentsManager->createLog("vehicle", $e);
    }
    exit;
}
