<?php
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/apiHelper.php';

use Egulias\EmailValidator\EmailValidator;
use Egulias\EmailValidator\Validation\DNSCheckValidation;
use Egulias\EmailValidator\Validation\MultipleValidationWithAnd;
use Egulias\EmailValidator\Validation\RFCValidation;
use managers\RequestsManager;
$requestsManager = new RequestsManager();

switch ($_POST['type'] ?? null) {
    case "sendRequest":
        if (!isset($_POST['name']) || trim($_POST['name']) == '') error("Не введено имя!", 0);
        $validator = new EmailValidator();
        $multipleValidations = new MultipleValidationWithAnd([
            new RFCValidation(),
            new DNSCheckValidation()
        ]);
        if (!isset($_POST['email']) || trim($_POST['email']) == '') error("Не введена почта!", 1);
        if (!$validator->isValid(trim($_POST['email']), $multipleValidations)) error("Введена некорректная почта!", 4);
        if (!isset($_POST['phone']) || trim($_POST['phone']) == '') error("Не введен номер телефона!", 2);
        if (!isValidPhone(trim($_POST['phone']))) error("Введён некорректный номер телефона!", 5);
        $ok = $requestsManager->addRequest(trim($_POST['phone']), trim($_POST['email']), trim($_POST['name']));
        $ok ? response("OK", 200) : error("Не удалось создать заявку на консультацию!", 3);
        break;
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