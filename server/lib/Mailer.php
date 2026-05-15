<?php

namespace lib;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class Mailer
{
    private PHPMailer $mail;
    private string $fromEmail = 'brightpath.notify@gmail.com';
    private string $fromName = 'Светлый Путь';
    private Environment $twig;

    public function __construct()
    {
        $this->mail = new PHPMailer(true);
        $this->setupSMTP();
        $this->setupTwig();
    }

    private function setupSMTP(): void
    {
        $this->mail->isSMTP();
        $this->mail->Host = 'smtp.gmail.com';
        $this->mail->SMTPAuth = true;
        $this->mail->Username = 'brightpath.notify@gmail.com';
        $this->mail->Password = 'nlvg fxgg ouig ouwd';
        $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $this->mail->Port = 465;
        $this->mail->setFrom($this->fromEmail, $this->fromName);
    }

    private function setupTwig(): void
    {
        $loader = new FilesystemLoader(__DIR__ . '/../twig');
        $this->twig = new Environment($loader);
    }

    /**
     * Отправка уведомления о создании заказа (с полным составом)
     *
     * @param string $to        Email клиента
     * @param array  $orderData Данные заказа
     * @return bool
     */
    public function sendOrderCreated(string $to, array $orderData): bool
    {
        $html = $this->twig->render('email.twig', [
            'title' => "🕯️ Заказ #{$orderData['id']} создан",
            'message' => 'Ваш заказ принят и передан в обработку. Мы свяжемся с вами в ближайшее время.',
            'status_text' => 'Новый заказ',
            'status_color' => '#3498db',
            'order' => $orderData,
            'show_items' => true,
            'agents' => [],
            'config' => new Config()->getConfig()
        ]);

        return $this->sendHTMLEmail($to, "Заказ #{$orderData['id']} создан", $html);
    }

    /**
     * Отправка уведомления об изменении статуса заказа (без состава)
     *
     * @param string $to        Email клиента
     * @param array  $orderData Данные заказа (только id, статус, клиент)
     * @return bool
     */
    public function sendOrderStatusUpdated(string $to, array $orderData): bool
    {
        $statusColors = [
            'created' => '#3498db',
            'in_work' => '#f39c12',
            'completed' => '#27ae60',
            'cancelled' => '#e74c3c'
        ];

        $statusText = [
            'created' => 'Новый заказ',
            'in_work' => 'В работе',
            'completed' => 'Выполнен',
            'cancelled' => 'Отменён'
        ];

        $html = $this->twig->render('email.twig', [
            'title' => "📦 Заказ #{$orderData['id']} обновлён",
            'message' => 'Статус вашего заказа изменился.',
            'status_text' => $statusText[$orderData['status']],
            'status_color' => $statusColors[$orderData['status']],
            'order' => $orderData,
            'show_items' => false,
            'agents' => [],
            'config' => new Config()->getConfig()
        ]);

        return $this->sendHTMLEmail($to, "Заказ #{$orderData['id']} обновлён", $html);
    }

    /**
     * Отправка уведомления о выполнении заказа (с агентами, без состава)
     *
     * @param string $to        Email клиента
     * @param array  $orderData Данные заказа (только id, статус, клиент)
     * @param array  $agents    Список агентов, работавших над заказом
     * @return bool
     */
    public function sendOrderCompleted(string $to, array $orderData, array $agents): bool
    {
        $html = $this->twig->render('email.twig', [
            'title' => "✅ Заказ #{$orderData['id']} выполнен",
            'message' => 'Ваш заказ успешно выполнен. Благодарим за доверие!',
            'status_text' => 'Выполнен',
            'status_color' => '#27ae60',
            'order' => $orderData,
            'show_items' => false,
            'agents' => $agents,
            'config' => new Config()->getConfig()
        ]);

        return $this->sendHTMLEmail($to, "Заказ #{$orderData['id']} выполнен", $html);
    }

    /**
     * Отправка HTML-письма
     *
     * @param string $to      Email получателя
     * @param string $subject Тема письма
     * @param string $html    Текст письма (HTML)
     * @return bool
     */
    public function sendHTMLEmail(string $to, string $subject, string $html): bool
    {
        try {
            $this->mail->clearAddresses();
            $this->mail->addAddress($to);
            $this->mail->isHTML(true);
            $this->mail->CharSet = 'UTF-8';
            $this->mail->Encoding = 'base64';
            $this->mail->Subject = $subject;
            $this->mail->Body = $html;
            $this->mail->AltBody = strip_tags(str_replace(['<br>', '</p>'], "\n", $html));

            $this->mail->send();
            return true;
        } catch (Exception $e) {
            error_log("Mailer error: " . $e->getMessage());
            return false;
        }
    }
}