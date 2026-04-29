<?php

date_default_timezone_set('Europe/Moscow');

function logging(string $fileName, string $text): void
{
    @mkdir(__DIR__.'/../logs');
    @mkdir(__DIR__.'/../logs/dev_post');
    file_put_contents(__DIR__ . '/../logs/dev_post/'.$fileName.'.log', $text . "\n", FILE_APPEND);
}

const JSON_OPTIONS = JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_BIGINT_AS_STRING;
function error(string $message, int $code): never
{
    die(json_encode(['error' => ['message' => $message, 'code' => $code]], JSON_OPTIONS));
}

function response(string $message, int $code, mixed $data = null): never
{
    die(json_encode(['response' => ['message' => $message, 'code' => $code, 'data' => $data]], JSON_OPTIONS));
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

?>