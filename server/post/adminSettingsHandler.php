<?php

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/apiHelper.php';

use Egulias\EmailValidator\EmailValidator;
use Egulias\EmailValidator\Validation\DNSCheckValidation;
use Egulias\EmailValidator\Validation\MultipleValidationWithAnd;
use Egulias\EmailValidator\Validation\RFCValidation;
use lib\AdminLogger;
use managers\AdminsManager;
use managers\PresetsManager;
use managers\SessionManager;

if ($_POST['type'] !== 'getAllPresets') {
    $admin_login = getAdminLoginFromSession();
}


switch ($_POST['type'] ?? null) {
    case "loadAccountInfo":
        $adminsManager = new AdminsManager();
        $accInfo = $adminsManager->getAccountInfo($admin_login);
        if ($accInfo === false) error("Не удалось загрузить данные", 0);
        $sessionManager = new SessionManager();
        $sessCount = $sessionManager->getSessionCount($admin_login);
        if ($sessCount === false) error("Не удалось загрузить данные", 1);
        $accInfo["sessions_count"] = $sessCount;
        $accInfo["login"] = $admin_login;
        response("OK", 200, $accInfo);
        break;
    case "killAllSessions":
        $sessionManager = new SessionManager();
        if ($sessionManager->removeAllSessions($admin_login, (int) $_POST['session_id'])) {
            response("OK", 200);
        }else{
            error("Не удалось завершить сессии!", 0);
        }
        break;
    case "reset2fa":
        $adminsManager = new AdminsManager();
        if ($adminsManager->resetTotpByLogin($admin_login)) {
            response("OK", 200);
        }else{
            error("Не удалось сбросить 2FA!", 0);
        }
        break;
    case "changePassword":
        if (!isset($_POST['oldPassword']) || trim($_POST['oldPassword']) == '') error("Не введён старый пароль!", 0);
        if (!isset($_POST['newPassword']) || trim($_POST['newPassword']) == '') error("Не введён новый пароль!", 1);
        $newPassword = trim($_POST['newPassword']);
        $valid = validatePassword($newPassword);
        if ($valid['valid'] === false) {
            error($valid['message'], 2);
        }
        $oldPassword = trim($_POST['oldPassword']);
        $adminsManager = new AdminsManager();
        logging("adminSettingsHandler", "Старый пароль: ".$oldPassword. ", Новый пароль: ".$newPassword);
        if ($adminsManager->checkAdminPassword($admin_login, $oldPassword)) {
            if ($adminsManager->changePasswordByLogin($admin_login, $newPassword)) {
                response("OK", 200);
            }else error("Не удалось сменить пароль!", 3);
        }else error("Старый пароль неверный!", 4);
        break;
    case "changeContacts":
        try {
            if (!isset($_POST['contactsData'])) error("Не переданы данные!", 0);
            $contactsData = $_POST['contactsData'];
            if (!is_array($contactsData) || count($contactsData) < 7) error("Переданы некорректные данные!", 1);
            if (!isValidPhone($contactsData['phone'])) error("Введён некорректный номер телефона!", 2);
            $phone = $contactsData['phone'];
            $validator = new EmailValidator();
            $multipleValidations = new MultipleValidationWithAnd([
                new RFCValidation(),
                new DNSCheckValidation()
            ]);
            if (!$validator->isValid(trim($contactsData['email']), $multipleValidations)) error("Введена некорректная почта!", 3);
            if (!isValidAddress($contactsData['address'])) error("Введён некорректный адрес! Пример: г. Одинцово, ул. Глазынинская, д. 18", 4);
            $contactsData['phone_stock'] = $phone;
            $contactsData['phone'] = formatPhone($phone);
            $config = new \lib\Config();
            try {
                if ($config->editConfig($contactsData)) {
                    response("OK", 200);
                } else {
                    error("Не удалось обновить конфиг", 5);
                }
            } catch (Exception $e) {
                error($e->getMessage(), 6);
            }
        }catch (\Exception|\Error $e){
            logging("adminSettingsHandler", $e->getMessage());
        }
        break;
    case "getAllPresets":
        $presetsManager = new PresetsManager();
        response("OK", 200, $presetsManager->getAllPresets());
        break;
    case "deletePreset":
        if (!isset($_POST['presetId']) || !is_numeric($_POST['presetId'])) error("Не передан ID пресета!", 0);
        $presetsManager = new PresetsManager();
        if ($presetsManager->deletePreset((int) $_POST['presetId'])) {
            response("OK", 200);
        }else{
            error("Не удалось удалить пресет!", 1);
        }
        break;
    case "editPreset":
        if (!isset($_POST['presetId']) || !is_numeric($_POST['presetId'])) error("Не передан ID пресета!", 0);
        if (!isset($_POST['presetData'])) error("Не переданы данные пресета!", 1);
        $presetData = $_POST['presetData'];
        if (!is_array($presetData)) error("Переданы некорректные данные пресета!", 2);
        if (trim($presetData['name']) === '') error("Введите название!", 3);
        if (trim($presetData['quote']) === '') error("Введите цитату!", 4);
        $decorations = ['default', 'popular', 'vip', 'primary'];
        if (!in_array($presetData['decoration'], $decorations)) error("Выбрано некорректное украшение пресета!", 5);
        if ($presetData['decoration'] !== 'default') {
            if (trim($presetData['decoration_quote']) === '') error("Введите цитату украшения пресета!", 6);
        }
        if (!isset($presetData['is_active']) || !is_numeric($presetData['is_active'])) error("Выберите статус пресета!", 7);
        $transport = $presetData['transport'];
        $goods = $presetData['goods'];
        $services = $presetData['services'];
        if (!is_array($transport) || count($transport) < 1) error("Выберите транспорт!", 8);
        if (!is_array($goods) || count($goods) < 1) error("Выберите товары!", 9);
        if (!is_array($services) || count($services) < 1) error("Выберите услуги!", 10);
        $summary = 0;
        foreach ($goods as $good) {
            $good['id'] = (int) $good['id'];
            $good['price'] = (int) $good['price'];
            $good['quantity'] = 1;
            $summary += $good['price'];
        }
        foreach ($transport as $vehicle) {
            $vehicle['id'] = (int) $vehicle['id'];
            $vehicle['price'] = (int) $vehicle['price'];
            $vehicle['quantity'] = 1;
            $summary += $vehicle['price'];
        }
        foreach ($services as $service) {
            $service['id'] = (int) $service['id'];
            $service['price'] = (int) $service['price'];
            $service['quantity'] = 1;
            $summary += $service['price'];
        }
        $presetData['transport'] = $transport;
        $presetData['goods'] = $goods;
        $presetData['services'] = $services;
        $presetData['price'] = $summary;
        logging("adminSettingsHandler", json_encode($presetData));
        $presetsManager = new PresetsManager();
        if ($presetsManager->editPreset((int)$_POST['presetId'], $presetData)) {
            response("OK", 200);
        }else{
            error("Не удалось обновить пресет!", 11);
        }
        break;
    case "addPreset":
        if (!isset($_POST['presetData'])) error("Не переданы данные пресета!", 1);
        $presetData = $_POST['presetData'];
        if (!is_array($presetData)) error("Переданы некорректные данные пресета!", 2);
        if (trim($presetData['name']) === '') error("Введите название!", 3);
        if (trim($presetData['quote']) === '') error("Введите цитату!", 4);
        $decorations = ['default', 'popular', 'vip', 'primary'];
        if (!in_array($presetData['decoration'], $decorations)) error("Выбрано некорректное украшение пресета!", 5);
        if ($presetData['decoration'] !== 'default') {
            if (trim($presetData['decoration_quote']) === '') error("Введите цитату украшения пресета!", 6);
        }
        if (!isset($presetData['is_active']) || !is_numeric($presetData['is_active'])) error("Выберите статус пресета!", 7);
        $transport = $presetData['transport'];
        $goods = $presetData['goods'];
        $services = $presetData['services'];
        if (!is_array($transport) || count($transport) < 1) error("Выберите транспорт!", 8);
        if (!is_array($goods) || count($goods) < 1) error("Выберите товары!", 9);
        if (!is_array($services) || count($services) < 1) error("Выберите услуги!", 10);
        $summary = 0;
        foreach ($goods as $good) {
            $good['id'] = (int) $good['id'];
            $good['price'] = (int) $good['price'];
            $good['quantity'] = 1;
            $summary += $good['price'];
        }
        foreach ($transport as $vehicle) {
            $vehicle['id'] = (int) $vehicle['id'];
            $vehicle['price'] = (int) $vehicle['price'];
            $vehicle['quantity'] = 1;
            $summary += $vehicle['price'];
        }
        foreach ($services as $service) {
            $service['id'] = (int) $service['id'];
            $service['price'] = (int) $service['price'];
            $service['quantity'] = 1;
            $summary += $service['price'];
        }
        $presetData['transport'] = $transport;
        $presetData['goods'] = $goods;
        $presetData['services'] = $services;
        $presetData['price'] = $summary;
        $presetsManager = new PresetsManager();
        if ($presetsManager->addPreset($presetData)) {
            response("OK", 200);
        }else{
            error("Не удалось добавить пресет!", 11);
        }
        break;

}

// ВСПОМОГАТЕЛЬНЫЕ ФУНКЦИИ
function validatePassword(string $password): array
{
    // 1. Не менее 12 символов
    if (mb_strlen($password) < 12) {
        return ['valid' => false, 'message' => 'Пароль должен содержать не менее 12 символов'];
    }
    // 2. Не менее 2 заглавные буквы
    $upperCaseCount = preg_match_all('/[A-ZА-Я]/u', $password);
    if ($upperCaseCount < 2) {
        return ['valid' => false, 'message' => 'Пароль должен содержать не менее 2 заглавных букв'];
    }
    // 3. Не менее 2 строчные буквы
    $lowerCaseCount = preg_match_all('/[a-zа-я]/u', $password);
    if ($lowerCaseCount < 2) {
        return ['valid' => false, 'message' => 'Пароль должен содержать не менее 2 строчных букв'];
    }
    // 4. Не менее 2 цифры
    $digitCount = preg_match_all('/\d/', $password);
    if ($digitCount < 2) {
        return ['valid' => false, 'message' => 'Пароль должен содержать не менее 2 цифр'];
    }
    // 5. Не менее 2 спец.символа
    $specialCount = preg_match_all('/[@#$!_&^%*()]/', $password);
    if ($specialCount < 2) {
        return ['valid' => false, 'message' => 'Пароль должен содержать не менее 2 специальных символов (@, #, $, !, _, &, ^, %, *, (, ))'];
    }
    return ['valid' => true, 'message' => 'Пароль надёжный'];
}
function isValidPhone(string $phone): bool
{
    // Удаляем все символы, кроме цифр и знака +
    $cleaned = preg_replace('/[^0-9+]/', '', $phone);
    // Проверяем, что номер начинается с +7 или 8 и содержит 11 цифр
    $digits = preg_replace('/[^0-9]/', '', $cleaned);
    // Российские номера: 11 цифр (8 или +7 в начале)
    if (strlen($digits) === 11 && ($digits[0] === '7' || $digits[0] === '8')) {
        return true;
    }
    return false;
}
function isValidAddress(string $address): bool
{
    // Удаляем лишние пробелы и обрезаем
    $address = trim(preg_replace('/\s+/', ' ', $address));

    // Минимальная длина адреса (например: "г. А, ул. Б, д. 1" - 15 символов)
    if (mb_strlen($address) < 15) {
        return false;
    }

    // Проверяем наличие города (г., город, г, пос., д., с., рп.)
    $hasCity = preg_match('/(?:г\.|город|г\s|пос\.|деревня|с\.|рп\.)\s/i', $address);
    if (!$hasCity) {
        return false;
    }

    // Проверяем наличие улицы (ул., улица, проспект, пр-т, пер., бульвар, шоссе, наб., аллея)
    $hasStreet = preg_match('/(?:ул\.|улица|пр-т|проспект|пер\.|переулок|бульвар|шоссе|наб\.|аллея)/i', $address);
    if (!$hasStreet) {
        return false;
    }

    // Проверяем наличие дома (д., дом, д, влд., стр., к.)
    $hasHouse = preg_match('/(?:д\.|дом|д\s|влд\.|стр\.|к\.)/i', $address);
    if (!$hasHouse) {
        return false;
    }

    return true;
}
function formatPhone(string $phone): string
{
    // Удаляем все символы, кроме цифр
    $digits = preg_replace('/[^0-9]/', '', $phone);
    // Если номер начинается с 8, заменяем на +7
    if (strlen($digits) === 11 && $digits[0] === '8') {
        $digits = '7' . substr($digits, 1);
    }
    // Если номер начинается с 7 и длина 11 цифр
    if (strlen($digits) === 11 && $digits[0] === '7') {
        return '+7 (' . substr($digits, 1, 3) . ') ' .
            substr($digits, 4, 3) . '-' .
            substr($digits, 7, 2) . '-' .
            substr($digits, 9, 2);
    }
    // Если номер не подходит под формат, возвращаем исходный
    return $phone;
}