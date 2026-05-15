<?php
require_once __DIR__ . "/../../vendor/autoload.php";

use managers\AdminsManager;
use managers\AgentsManager;
use managers\OrdersManager;
use managers\SessionManager;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
$adminsManager = new AdminsManager();
if (isset($_COOKIE['session_id'])) {
    if (is_numeric($_COOKIE['session_id'])) {
        $login = new SessionManager()->getLoginById((int)$_COOKIE['session_id']);
        if ($login === false) {
            not_authorized();
        }
    }else not_authorized();
}else not_authorized();
$role = $adminsManager->getRole($login);
function not_authorized(): never {
    setcookie('session_id', '', time() - 3600, '/', '', false);
    setcookie('login', '', time() - 3600, '/', '', false);
    header('Location: ../auth.php');
    exit;
}
$ordersManager = new OrdersManager();
$agentsManager = new AgentsManager();
$allOrders = $ordersManager->getAllOrders();
?>

<div class="page-orders">
    <div class="page-header-actions">
        <div class="search-bar">
            <input type="text" placeholder="Поиск по номеру заказа, клиенту...">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <circle cx="11" cy="11" r="8" stroke-width="2"/>
                <line x1="21" y1="21" x2="16.65" y2="16.65" stroke-width="2"/>
            </svg>
        </div>
        <div class="filters">
            <select data-filter="status">
                <option value="all">Все статусы</option>
                <option value="новый">Новый</option>
                <option value="в работе">В работе</option>
                <option value="выполнен">Выполнен</option>
                <option value="отменён">Отменён</option>
            </select>
            <select data-filter="date">
                <option value="all">Все даты</option>
                <option value="сегодня">Сегодня</option>
                <option value="эта неделя">Эта неделя</option>
                <option value="этот месяц">Этот месяц</option>
            </select>
        </div>
    </div>

    <table class="data-table">
        <thead>
        <tr>
            <th>ID</th>
            <th>Клиент</th>
            <th>Почта</th>
            <th>Телефон</th>
            <th>Сумма</th>
            <th>Дата</th>
            <th>Статус</th>
            <th>Действия</th>
        </tr>
        </thead>
        <tbody>
        <?php
        foreach ($allOrders as $order) {
            switch ($order['status']) {
                case "created":
                    $status = '<span class="status pending">Создан</span>';
                    break;
                case 'completed':
                    $status = '<span class="status completed">Выполнен</span>';
                    break;
                case 'in_work':
                    $status = '<span class="status in-progress">В работе</span>';
                    break;
                case 'cancelled':
                    $status = '<span class="status inactive">Отменён</span>';
                    break;
                default:
                    $status = '<span class="status inactive">Неизвестно</span>';
                    break;
            }
            $action = $role == 'admin' ? '<button class="btn-icon delete" onclick="orderDelete('.$order['id'].')">🗑️</button>' : '';
            echo '<tr id="tr_'.$order['id'].'">
                    <td>#'.$order['id'].'</td>
                    <td>'.$order['username'].'</td>
                    <td>'.$order['useremail'].'</td>
                    <td>'.$order['userphone'].'</td>
                    <td>'.number_format($order['summary'], 2, ',', ' ').' ₽</td>
                    <td>'.(new DateTime($order['created_at'])->format('d.m.Y')).'</td>
                    <td>'.$status.'</td>
                    <td>
                        <button class="btn-reset-2fa" onclick="openOrderStatusModal('.$order['id'].')"">📊 Статус</button>
                        '.$action.'
                    </td>
                 </tr>';
        }
        ?>
        </tbody>
    </table>
</div>
<!-- 1. Модальное окно для смены статуса заказа -->
<div class="modal-overlay order-status-modal" id="orderStatusModal">
    <div class="modal-container" style="max-width: 480px;">
        <div class="modal-header">
            <h2 class="modal-title">Управление заказом</h2>
            <button class="modal-close" onclick="closeOrderStatusModal()">&times;</button>
        </div>
        <div class="modal-content">
            <!-- Информация о заказе (для примера) -->
            <div class="order-info" style="background: #f5f5f5; padding: 12px; border-radius: 8px; margin-bottom: 20px;">
                <p><strong></strong></p>
                <p></p>
                <p></p>
            </div>

            <!-- Текущий статус -->
            <div class="current-status" style="margin-bottom: 20px;">
                <label>Текущий статус:</label>
                <span class="status-badge status-created" id="currentStatusBadge">Создан</span>
            </div>

            <div class="form-group" id="contentText">
                <label for="contentOrderList">Состав заказа:</label>
                <div class="agents-list-complete" style="max-height: 170px" id="contentOrderList">
                </div>
            </div>

            <!-- Новый статус (выбор) -->
            <div class="form-group" id="statusForm">
                <label for="newOrderStatus">Новый статус:</label>
                <select id="newOrderStatus" class="order-status-select">
                    <option value="">-- Выберите статус --</option>
                    <option value="in_work" class="status-option-in_work">В работе</option>
                    <option value="cancelled" class="status-option-cancelled">Отменён</option>
                    <option value="completed" class="status-option-completed">Выполнен</option>
                </select>
            </div>

            <div class="form-group" id="agentsCompletedForm">
                <label for="agentsCompletedList">Агенты, выполнившие заказ:</label>
                <div class="agents-list-complete" id="agentsCompletedList">
                </div>
            </div>

            <div class="modal-actions" style="display: flex; gap: 12px; margin-top: 20px; justify-content: flex-end;">
                <button class="modal-submit" id="statusSaveButton" onclick="saveOrderModal()" style="background: #d4a373; color: white;">Сохранить</button>
            </div>
        </div>
    </div>
</div>

<!-- 2. Модальное окно для выбора агентов (вызывается при статусе completed) -->
<div class="modal-overlay order-agents-modal" id="orderAgentsModal">
    <div class="modal-container" style="max-width: 600px;">
        <div class="modal-header">
            <h2 class="modal-title">Выбор агентов для заказа</h2>
            <button class="modal-close" onclick="closeOrderAgentsModal()">&times;</button>
        </div>
        <div class="modal-content">
            <p style="margin-bottom: 15px;">Выберите агентов, которые выполнили этот заказ:</p>

            <div class="agents-list">
                <?php
                $loader = new FilesystemLoader('../../server/twig');
                $twig = new Environment($loader, [
                    //'cache' => '../server/twig/cache',
                        'autoescape' => false,
                ]);
                $positions_map = [
                        1 => 'Руководитель',
                        2 => "Ведущий агент",
                        3 => "Старший агент",
                        4 => "Менеджер",
                        5 => "Агент"
                ];
                $allAgents = $agentsManager->getAllForOrders();
                foreach ($allAgents as $agent) {
                    echo $twig->render('order_agent.twig', [
                        "id" => $agent['id'],
                        "fullname" => $agent['name'],
                        "image" => "../../src/images/agents/{$agent['image_path']}",
                        "position" => $positions_map[$agent['position']],
                    ]);
                }
                ?>

            </div>

            <!-- Чекбокс "Выбрать всех" -->
            <div class="select-all" style="margin-bottom: 20px; padding-top: 10px;">
                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                    <input type="checkbox" id="selectAllAgents">
                    <span>Выбрать всех</span>
                </label>
            </div>

            <div class="modal-actions" style="display: flex; gap: 12px; justify-content: flex-end;">
                <button class="modal-submit" id="saveAgentsBtn" style="background: #d4a373; color: white;">Подтвердить</button>
            </div>
        </div>
    </div>
</div>