<?php
require_once __DIR__ . "/../../vendor/autoload.php";

use managers\AdminsManager;
use managers\RequestsManager;
use managers\SessionManager;

$adminsManager = new AdminsManager();
if (isset($_COOKIE['session_id'])) {
    if (is_numeric($_COOKIE['session_id'])) {
        $login = new SessionManager()->getLoginById((int)$_COOKIE['session_id']);
        if ($login === false) {
            not_authorized();
        }
    }else not_authorized();
}else not_authorized();
function not_authorized(): never {
    setcookie('session_id', '', time() - 3600, '/', '', false);
    setcookie('login', '', time() - 3600, '/', '', false);
    header('Location: ../auth.php');
    exit;
}
$role = $adminsManager->getRole($login);
$requestsManager = new RequestsManager();

$allRequests = $requestsManager->getAllRequests();
?>

<div class="page-orders">
    <div class="page-header-actions">
        <div class="search-bar">
            <input type="text" placeholder="Поиск по номеру заявки, клиенту...">
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
            <th>Имя</th>
            <th>Почта</th>
            <th>Телефон</th>
            <th>Дата</th>
            <th>Статус</th>
            <th>Действия</th>
        </tr>
        </thead>
        <tbody>
        <?php
        foreach ($allRequests as $request) {
            switch ($request['status']) {
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
            $action = $role == 'admin' ? '<button class="btn-icon delete" onclick="requestDelete('.$request['id'].')">🗑️</button>' : '';
            echo '<tr id="tr_'.$request['id'].'">
                    <td>#'.$request['id'].'</td>
                    <td>'.$request['username'].'</td>
                    <td>'.$request['useremail'].'</td>
                    <td>'.$request['userphone'].'</td>
                    <td>'.(new DateTime($request['created_at'])->format('d.m.Y')).'</td>
                    <td>'.$status.'</td>
                    <td>
                        <button class="btn-reset-2fa" onclick="openRequestStatusModal('.$request['id'].')"">📊 Статус</button>
                        '.$action.'
                    </td>
                 </tr>';
        }
        ?>
        </tbody>
    </table>
</div>
<!-- 1. Модальное окно для смены статуса заказа -->
<div class="modal-overlay" id="requestStatusModal">
    <div class="modal-container" style="max-width: 480px;">
        <div class="modal-header">
            <h2 class="modal-title">Управление заявкой</h2>
            <button class="modal-close" onclick="closeRequestStatusModal()">&times;</button>
        </div>
        <div class="modal-content">
            <!-- Информация о заказе (для примера) -->
            <div class="order-info">
                <p><strong></strong></p>
                <p></p>
                <p></p>
            </div>

            <!-- Текущий статус -->
            <div class="current-status" style="margin-bottom: 20px;">
                <label>Текущий статус:</label>
                <span class="status-badge status-created" id="currentRequestStatusBadge">Создан</span>
            </div>

            <!-- Новый статус (выбор) -->
            <div class="form-group" id="statusForm">
                <label for="newRequestStatus">Новый статус:</label>
                <select id="newRequestStatus" class="order-status-select">
                    <option value="">-- Выберите статус --</option>
                    <option value="in_work" class="status-option-in_work">В работе</option>
                    <option value="cancelled" class="status-option-cancelled">Отменён</option>
                    <option value="completed" class="status-option-completed">Выполнен</option>
                </select>
            </div>

            <div class="form-group" id="operatorComment">
                <label for="operatorCommentText">Комментарий оператора:</label>
                <div class="agents-list-complete" id="operatorCommentText" style="">
                </div>
            </div>

            <div class="modal-actions" style="display: flex; gap: 12px; margin-top: 20px; justify-content: flex-end;">
                <button class="modal-submit" id="requestSaveButton" onclick="saveRequestModal()" style="background: #d4a373; color: white;">Сохранить</button>
            </div>
        </div>
    </div>
</div>


<!-- 2. Модальное окно для выбора агентов (вызывается при статусе completed) -->
<div class="modal-overlay order-agents-modal" id="requestCommentModal">
    <div class="modal-container" style="max-width: 600px;">
        <div class="modal-header">
            <h2 class="modal-title">Ввод комментария к заявке</h2>
            <button class="modal-close" onclick="closeRequestCommentModal()">&times;</button>
        </div>
        <div class="modal-content">

            <div class="form-group">
                <label for="commentInput">Введите комментарий, заключение по заявке на консультацию</label>
                <textarea id="commentInput" placeholder="Комментарий..."></textarea>
            </div>

            <div class="modal-actions" style="display: flex; gap: 12px; justify-content: flex-end;">
                <button class="modal-submit" onclick="saveRequestComment()" style="background: #d4a373; color: white;">Подтвердить</button>
            </div>
        </div>
    </div>
</div>