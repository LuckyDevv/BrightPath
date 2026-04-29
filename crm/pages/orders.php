<?php
require_once __DIR__ . "/../../vendor/autoload.php";
use managers\OrdersManager;
$ordersManager = new OrdersManager();
$allOrders = $ordersManager->getAllOrders();
?>

<div class="page-orders">
    <div class="page-header-actions">
        <button class="btn-export">📊 Экспорт</button>
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
                    $status = '<span class="status pending">Новый</span>';
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
            echo '<tr>
                    <td>#'.$order['id'].'</td>
                    <td>'.$order['username'].'</td>
                    <td>'.$order['useremail'].'</td>
                    <td>'.$order['userphone'].'</td>
                    <td>'.number_format($order['summary'], 2, ',', ' ').' ₽</td>
                    <td>'.$order['created_at'].'</td>
                    <td>'.$status.'</td>
                    <td><button class="btn-icon view">👁️</button></td>
                 </tr>';
        }
        ?>
        </tbody>
    </table>
</div>