<?php

require __DIR__."/../vendor/autoload.php";

$files = scandir(__DIR__."/../src/images/goods/");
foreach ($files as $file) {
    if ($file != "." && $file != "..") {
        if (!str_contains($file, ".jpg")) {
            if (str_contains($file, ".png") || str_contains($file, ".webp") || str_contains($file, ".jfif")) {
                print $file."\n";
                convertToJpg(__DIR__."/../src/images/goods/".$file);
            }
        }
    }
}

function convertToJpg($sourcePath, $destinationPath = null, $quality = 90)
{
    // Если путь назначения не указан, заменяем расширение
    if ($destinationPath === null) {
        $destinationPath = pathinfo($sourcePath, PATHINFO_DIRNAME) . '/'
            . pathinfo($sourcePath, PATHINFO_FILENAME) . '.jpg';
    }

    // Получаем тип изображения
    $imageInfo = getimagesize($sourcePath);
    $mimeType = $imageInfo['mime'];

    // Создаём ресурс в зависимости от типа
    switch ($mimeType) {
        case 'image/jpeg':
            $image = imagecreatefromjpeg($sourcePath);
            break;
        case 'image/png':
            $image = imagecreatefrompng($sourcePath);
            // Сохраняем прозрачность, заменяя её на белый фон
            $bg = imagecreatetruecolor(imagesx($image), imagesy($image));
            $white = imagecolorallocate($bg, 255, 255, 255);
            imagefill($bg, 0, 0, $white);
            imagecopy($bg, $image, 0, 0, 0, 0, imagesx($image), imagesy($image));
            $image = $bg;
            break;
        case 'image/webp':
            $image = imagecreatefromwebp($sourcePath);
            break;
        case 'image/gif':
            $image = imagecreatefromgif($sourcePath);
            break;
        case 'image/bmp':
            $image = imagecreatefrombmp($sourcePath);
            break;
        default:
            throw new Exception("Неподдерживаемый тип изображения: $mimeType");
    }

    // Сохраняем как JPG
    imagejpeg($image, $destinationPath, $quality);

    // Освобождаем память
    imagedestroy($image);

    return $destinationPath;
}