<?php
ini_set('max_execution_time', 120);  // 120 секунд
ini_set('memory_limit', '256M');     // Увеличить память
require_once __DIR__ . "/../../vendor/autoload.php";

use managers\GoodsManager;
use managers\OrdersManager;
use managers\ServicesManager;
use managers\VehiclesManager;
$vehiclesManager = new VehiclesManager();
$popularVehicles = $vehiclesManager->getPopular();
$servicesManager = new ServicesManager();
$popularServices = $servicesManager->getPopular();
$goodsManager = new GoodsManager();
$popularGoods = $goodsManager->getPopular();
$ordersManager = new OrdersManager();
$orders = $ordersManager->getLastOrders();
$ordersTodayCount = $ordersManager->getOrdersToday();
$ordersYesterdayCount = $ordersManager->getOrdersYesterday();
$delimiter_orders = $ordersYesterdayCount == 0 ? 1 : $ordersYesterdayCount;
$increase_orders = (($ordersTodayCount - $ordersYesterdayCount) / $delimiter_orders) * 100;

$ordersSummaryToday = $ordersManager->getSummaryToday();
$ordersSummaryYesterday = $ordersManager->getSummaryYesterday();
$delimiter_summary = $ordersSummaryYesterday == 0 ? 1 : $ordersSummaryYesterday;
$increase_summary = (($ordersSummaryToday - $ordersSummaryYesterday) / $delimiter_summary) * 100;

$ordersRoundSummary = $ordersManager->getRoundSummary();
$ordersAll = $ordersManager->getOrdersAll();

$ordersClientsToday = $ordersManager->getNewClientsToday();
$ordersClientsYesterday = $ordersManager->getNewClientsYesterday();
$delimiter_clients = $ordersClientsYesterday == 0 ? 1 : $ordersClientsYesterday;
$increase_clients = (($ordersClientsToday - $ordersClientsYesterday) / $delimiter_summary) * 100;

function formatIncrease(float|int $increase): void
{
    if ($increase == 0) {
        echo '<span class="stat-change neutral">' . $increase . '%</span>';
    } elseif ($increase > 0) {
        echo '<span class="stat-change positive">+' . $increase . '%</span>';
    } elseif ($increase < 0) {
        echo '<span class="stat-change negative">-' . ($increase * -1) . '%</span>';
    } else {
        echo '<span class="stat-change negative">Некорректные данные</span>';
    }
}
?>
<div class="dashboard">
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M3 3v18h18" stroke="currentColor"/>
                    <rect x="7" y="10" width="3" height="7" stroke="currentColor"/>
                    <rect x="12" y="6" width="3" height="11" stroke="currentColor"/>
                    <rect x="17" y="8" width="3" height="9" stroke="currentColor"/>
                </svg>
            </div>
            <div class="stat-info">
                <h3>Заказов сегодня</h3>
                <div class="stat-value"><?php echo number_format($ordersTodayCount, 0, ',', ' '); ?></div>
                <?php formatIncrease($increase_orders); ?>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10" stroke="currentColor"/>
                    <path d="M12 6v6l4 2" stroke="currentColor"/>
                </svg>
            </div>
            <div class="stat-info">
                <h3>Выручка сегодня</h3>
                <div class="stat-value"><?php echo number_format($ordersSummaryToday, 0, ',', ' '); ?> ₽</div>
                <?php formatIncrease($increase_summary); ?>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" stroke="currentColor"/>
                    <circle cx="12" cy="7" r="4" stroke="currentColor"/>
                </svg>
            </div>
            <div class="stat-info">
                <h3>Новых клиентов</h3>
                <div class="stat-value"><?php echo $ordersClientsToday; ?></div>
                <?php formatIncrease($ordersClientsYesterday); ?>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="2" y="3" width="20" height="14" rx="2" stroke="currentColor"/>
                    <path d="M8 21h8M12 17v4" stroke="currentColor"/>
                </svg>
            </div>
            <div class="stat-info">
                <h3>Средний чек</h3>
                <div class="stat-value"><?php echo number_format($ordersRoundSummary, 0, ',', ' '); ?> ₽</div>
                <span class="stat-change negative"></span>
            </div>
        </div>
    </div>

    <div class="charts-row">
        <div class="chart-card">
            <h3>Популярные автомобили</h3>
            <div class="popular-list">
                <?php
                $popularVehiclesNull = true;
                if (!empty($popularVehicles)) {
                    $maxOrders = max(array_column($popularVehicles, 'orders_count'));
                    if ($maxOrders > 0) {
                        $popularVehiclesNull = false;
                        foreach ($popularVehicles as $vehicle) {
                            // var_dump($vehicle);
                            echo '<div class="popular-item">
                                <span class="popular-name">'.$vehicle["name"].'</span>
                                <div class="popular-bar"><div style="width: '. ($vehicle["orders_count"] / $maxOrders) * 100 .'%"></div></div>
                                <span class="popular-count">'.$vehicle["orders_count"].' заказов</span>
                              </div>';
                        }
                    }
                }
                if ($popularVehiclesNull) {
                    echo "На данный момент нет данных о популярных автомобилях.";
                }
                ?>
            </div>
        </div>
        <div class="chart-card">
            <h3>Последние заказы</h3>
            <table class="recent-orders">
                <thead>
                <tr>
                    <th>№</th>
                    <th>Клиент</th>
                    <th>Сумма</th>
                    <th>Статус</th>
                </tr>
                </thead>
                <tbody>
                <?php
                foreach ($orders as $order) {
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
                            <td>'.number_format($order['summary'], 2, ',', ' ').' ₽</td>
                            <td>'.$status.'</td>
                          </tr>';
                }
                ?>

                </tbody>
            </table>
        </div>
        <div class="chart-card">
            <h3>Популярные услуги</h3>
            <div class="popular-list">
                <?php
                $popularServicesNull = true;
                if (!empty($popularServices)) {
                    $maxOrders = max(array_column($popularServices, 'orders_count'));
                    if ($maxOrders > 0) {
                        $popularServicesNull = false;
                        foreach ($popularServices as $service) {
                            echo '<div class="popular-item">
                                <span class="popular-name">'.$service["name"].'</span>
                                <div class="popular-bar"><div style="width: '.($service["orders_count"] / $maxOrders * 100).'%"></div></div>
                                <span class="popular-count">'.$service["orders_count"].' заказов</span>
                              </div>';
                        }
                    }
                }
                if ($popularServicesNull) {
                    echo "На данный момент нет данных о популярных услугах.";
                }
                ?>
            </div>
        </div>
        <div class="chart-card">
            <h3>Популярные Товары</h3>
            <div class="popular-list">
                <?php
                $popularGoodsNull = true;
                if (!empty($popularGoods)) {
                    $maxOrders = max(array_column($popularGoods, 'orders_count'));
                    if ($maxOrders > 0) {
                        $popularGoodsNull = false;
                        foreach ($popularGoods as $goods) {
                            echo '<div class="popular-item">
                                <span class="popular-name">'.$goods["name"].'</span>
                                <div class="popular-bar"><div style="width: '.($goods["orders_count"] / $maxOrders * 100).'%"></div></div>
                                <span class="popular-count">'.$goods["orders_count"].' заказов</span>
                              </div>';
                        }
                    }
                }
                if ($popularGoodsNull) {
                    echo "На данный момент нет данных о популярных товарах.";
                }
                ?>
            </div>
        </div>
    </div>

    <div class="chart-card full-width">
        <h3>Динамика заказов</h3>
        <div class="chart-placeholder">
            <svg viewBox="0 0 800 200" preserveAspectRatio="none">
                <?php
                $dates = [];
                $counts = [];

                // Заполняем все дни последних 30 дней (даже где 0 заказов)
                for ($i = 29; $i >= 0; $i--) {
                    $date = date('Y-m-d', strtotime("-$i days"));
                    $dates[] = $date;
                    $counts[$date] = 0; // по умолчанию 0
                }

                // Заполняем реальные данные
                foreach ($ordersAll as $order) {
                    $counts[$order['date']] = (int)$order['orders_count'];
                }

                // Преобразуем в массив значений для графика
                $chartData = array_values($counts);

                // Находим максимальное значение для масштабирования
                $maxCount = max($chartData);
                $maxCount = $maxCount == 0 ? 1 : $maxCount; // избегаем деления на 0
                ?>
                <?php
                $width = 800;
                $height = 180;
                $padding = 40; // отступы для осей

                $graphWidth = $width - $padding * 2;
                $graphHeight = $height - $padding;
                $stepX = $graphWidth / (count($chartData) - 1);

                // Формируем точки для ломаной и области
                $points = [];
                $areaPoints = [];

                for ($i = 0; $i < count($chartData); $i++) {
                    $x = $padding + ($i * $stepX);
                    $y = $padding + $graphHeight - (($chartData[$i] / $maxCount) * $graphHeight);
                    $points[] = "$x,$y";
                    $areaPoints[] = "$x,$y";
                }

                // Добавляем нижние углы для заливки области
                $lastX = $padding + ((count($chartData) - 1) * $stepX);
                $areaPoints[] = "$lastX," . ($padding + $graphHeight);
                $areaPoints[] = $padding . "," . ($padding + $graphHeight);

                $polylinePoints = implode(' ', $points);
                $polygonPoints = implode(' ', $areaPoints);
                ?>

                <!-- Ось X -->
                <line x1="<?= $padding ?>" y1="<?= $padding + $graphHeight ?>"
                      x2="<?= $width - $padding ?>" y2="<?= $padding + $graphHeight ?>"
                      stroke="#ddd" stroke-width="1"/>

                <!-- Ось Y -->
                <line x1="<?= $padding ?>" y1="<?= $padding ?>"
                      x2="<?= $padding ?>" y2="<?= $padding + $graphHeight ?>"
                      stroke="#ddd" stroke-width="1"/>

                <!-- График (заливка области) -->
                <polygon points="<?= $polygonPoints ?>" fill="rgba(212, 163, 115, 0.1)"/>

                <!-- График (линия) -->
                <polyline points="<?= $polylinePoints ?>" fill="none" stroke="#d4a373" stroke-width="2"/>

                <!-- Точки на графике -->
                <?php for ($i = 0; $i < count($chartData); $i++): ?>
                    <?php if ($chartData[$i] > 0): ?>
                        <circle cx="<?= $padding + ($i * $stepX) ?>"
                                cy="<?= $padding + $graphHeight - (($chartData[$i] / $maxCount) * $graphHeight) ?>"
                                r="3" fill="#d4a373"/>
                    <?php endif; ?>
                <?php endfor; ?>

                <!-- Подписи по оси X (каждый 3-й день) -->
                <?php for ($i = 0; $i < count($chartData); $i++): ?>
                    <?php if ($i % 3 == 0 || $i == count($chartData) - 1): ?>
                        <text x="<?= $padding + ($i * $stepX) ?>"
                              y="<?= $padding + $graphHeight + 15 ?>"
                              font-size="8" fill="#999" text-anchor="middle">
                            <?= date('d.m', strtotime($dates[$i])) ?>
                        </text>
                    <?php endif; ?>
                <?php endfor; ?>

                <!-- Подписи по оси Y -->
                <?php for ($i = 0; $i <= 4; $i++): ?>
                    <?php $val = round($maxCount * (4 - $i) / 4); ?>
                    <text x="<?= $padding - 5 ?>"
                          y="<?= $padding + ($i * $graphHeight / 4) + 3 ?>"
                          font-size="8" fill="#999" text-anchor="end">
                        <?= $val ?>
                    </text>
                <?php endfor; ?>
            </svg>
        </div>
    </div>
</div>