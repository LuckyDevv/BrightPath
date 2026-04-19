<?php
require_once __DIR__ . "/../../vendor/autoload.php";
use managers\VehiclesManager;
$vehiclesManager = new VehiclesManager();
$popularVehicles = $vehiclesManager->getPopular();
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
                <div class="stat-value">12</div>
                <span class="stat-change positive">+23%</span>
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
                <div class="stat-value">156 800 ₽</div>
                <span class="stat-change positive">+8%</span>
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
                <div class="stat-value">8</div>
                <span class="stat-change positive">+15%</span>
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
                <div class="stat-value">13 067 ₽</div>
                <span class="stat-change neutral">0%</span>
            </div>
        </div>
    </div>

    <div class="charts-row">
        <div class="chart-card">
            <h3>Популярные автомобили</h3>
            <div class="popular-list">
                <?php
                if (!empty($popularVehicles)) {
                    $maxOrders = max(array_column($popularVehicles, 'orders_count'));
                    foreach ($popularVehicles as $vehicle) {
                       // var_dump($vehicle);
                        echo '<div class="popular-item">
                                <span class="popular-name">'.$vehicle["name"].'</span>
                                <div class="popular-bar"><div style="width: '. ($vehicle["orders_count"] / $maxOrders) * 100 .'%"></div></div>
                                <span class="popular-count">'.$vehicle["orders_count"].' заказов</span>
                              </div>';
                    }
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
                <tr>
                    <td>#1245</td>
                    <td>Иванов И.И.</td>
                    <td>45 000 ₽</td>
                    <td><span class="status completed">Выполнен</span></td>
                </tr>
                <tr>
                    <td>#1244</td>
                    <td>Петрова А.С.</td>
                    <td>32 500 ₽</td>
                    <td><span class="status in-progress">В работе</span></td>
                </tr>
                <tr>
                    <td>#1243</td>
                    <td>Сидоров В.В.</td>
                    <td>28 000 ₽</td>
                    <td><span class="status pending">Новый</span></td>
                </tr>
                <tr>
                    <td>#1242</td>
                    <td>Кузнецова Е.М.</td>
                    <td>67 800 ₽</td>
                    <td><span class="status completed">Выполнен</span></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="chart-card full-width">
        <h3>Динамика заказов</h3>
        <div class="chart-placeholder">
            <svg viewBox="0 0 800 200" preserveAspectRatio="none">
                <polyline points="0,180 80,140 160,100 240,120 320,80 400,60 480,90 560,70 640,50 720,30 800,40" fill="none" stroke="#d4a373" stroke-width="2"/>
                <polygon points="0,180 80,140 160,100 240,120 320,80 400,60 480,90 560,70 640,50 720,30 800,40 800,200 0,200" fill="rgba(212,163,115,0.1)"/>
            </svg>
        </div>
    </div>
</div>