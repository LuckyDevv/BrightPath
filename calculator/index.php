<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.5, user-scalable=yes">
    <title>Калькулятор услуг - Светлый Путь</title>
    <link rel="stylesheet" href="src/css/style.css">
    <link rel="stylesheet" href="../src/css/nav.css">
    <link rel="stylesheet" href="../src/css/footer.css">
    <link rel="stylesheet" href="../src/css/modal.css">
    <link rel="stylesheet" href="../src/css/index.css">
    <link rel="icon" href="../logo.png" type="image/png">
</head>
<body>
<!-- Хедер -->
<header class="header">
    <div class="container">
        <div class="logo-container">
            <img src="../logo.png" alt="Светлый Путь" class="logo-img">
            <span class="logo-text">Светлый Путь</span>
        </div>
        <button class="burger" id="burger">
            <span></span>
            <span></span>
            <span></span>
        </button>
        <nav class="nav" id="nav">
            <a href="../index.php">Главная</a>
            <a href="../goods/">Товары</a>
            <a href="../services/">Услуги</a>
            <a href="../agents/">Агенты</a>
            <a href="index.php" class="active">Калькулятор</a>
            <a href="../autopark/">Автопарк</a>
            <a href="../profile/">Профиль</a>
            <a onclick="openModal()" class="btn-light">Связаться</a>
        </nav>
    </div>
    <div class="overlay" id="overlay"></div>
</header>

<main>
    <!-- Заголовок -->
    <section class="page-header" style="padding: 35px 0">
        <div class="container" style="display: flex; align-items: center; justify-content: center; flex-direction: column">
            <h1>Калькулятор расходов</h1>
            <p class="subtitle">Рассчитайте стоимость организации похорон</p>
        </div>
    </section>

    <!-- Основной контент -->
    <section class="calculator-section">
        <div class="container">
            <!-- Пресеты тарифов -->
            <div class="presets-section">
                <h3>Быстрый выбор пакета</h3>
                <div class="presets-grid">
                    <div class="preset-card" data-preset="econom">
                        <div class="preset-name">Эконом</div>
                        <div class="preset-price">от 18 900 ₽</div>
                        <div class="preset-desc">Достойно и доступно</div>
                        <button class="btn-preset">Выбрать</button>
                    </div>
                    <div class="preset-card" data-preset="standard">
                        <div class="preset-name">Стандарт</div>
                        <div class="preset-price">от 39 900 ₽</div>
                        <div class="preset-desc">Оптимальный выбор</div>
                        <button class="btn-preset">Выбрать</button>
                    </div>
                    <div class="preset-card" data-preset="premium">
                        <div class="preset-name">Премиум</div>
                        <div class="preset-price">от 69 900 ₽</div>
                        <div class="preset-desc">Всё включено</div>
                        <button class="btn-preset">Выбрать</button>
                    </div>
                    <div class="preset-card" data-preset="vip">
                        <div class="preset-name">VIP</div>
                        <div class="preset-price">от 129 900 ₽</div>
                        <div class="preset-desc">Высший уровень</div>
                        <button class="btn-preset">Выбрать</button>
                    </div>
                    <div class="preset-card" data-preset="custom">
                        <div class="preset-name">Конструктор</div>
                        <div class="preset-price">своя сборка</div>
                        <div class="preset-desc">Соберите сами</div>
                        <button class="btn-preset">Очистить</button>
                    </div>
                </div>
            </div>
            <div class="calculator-grid">
                <!-- Левая колонка - выбор услуг -->
                <div class="calculator-form">
                    <!-- Категория: Транспорт -->
                    <div class="calc-category">
                        <div class="category-header">
                            <h3>🚗 Транспорт</h3>
                            <button type="button" class="btn-add-item" data-category="transport">+ Добавить</button>
                        </div>
                        <div class="category-items" id="transport-items">
                            <div class="empty-items">Нет выбранных автомобилей</div>
                        </div>
                    </div>

                    <!-- Категория: Ритуальные товары -->
                    <div class="calc-category">
                        <div class="category-header">
                            <h3>⚰️ Ритуальные товары</h3>
                            <button type="button" class="btn-add-item" data-category="goods">+ Добавить</button>
                        </div>
                        <div class="category-items" id="goods-items">
                            <div class="empty-items">Нет выбранных товаров</div>
                        </div>
                    </div>

                    <!-- Категория: Услуги -->
                    <div class="calc-category">
                        <div class="category-header">
                            <h3>🪦 Ритуальные услуги</h3>
                            <button type="button" class="btn-add-item" data-category="services">+ Добавить</button>
                        </div>
                        <div class="category-items" id="services-items">
                            <div class="empty-items">Нет выбранных услуг</div>
                        </div>
                    </div>

                    <!-- Кнопка сброса -->
                    <div class="calc-actions">
                        <button type="button" class="btn-reset-calc" id="resetCalc">Очистить всё</button>
                    </div>
                </div>

                <!-- Правая колонка - итог -->
                <div class="calculator-total">
                    <h3>Итоговая стоимость</h3>
                    <div class="total-amount" id="totalAmount">0 ₽</div>
                    <div class="total-details" id="totalDetails">
                        <p>Добавьте услуги для расчёта</p>
                    </div>
                    <button style="width: 100%" class="btn btn-order" id="orderBtn">Оформить заказ</button>
                </div>
            </div>
        </div>
    </section>
</main>

<!-- Футер -->
<footer class="footer">
    <div class="container">
        <div class="footer-col">
            <img src="../logo.png" alt="Светлый Путь" style="height: 40px; margin-bottom: 15px;">
            <h4>Светлый Путь</h4>
            <p>Ритуальное агентство в Одинцово. Работаем с 2002 года.</p>
        </div>
        <div class="footer-col">
            <h4>Разделы</h4>
            <ul>
                <li><a href="../goods/">Товары</a></li>
                <li><a href="../services/">Услуги</a></li>
                <li><a href="../agents/">Агенты</a></li>
                <li><a href="../calculator/">Калькулятор</a></li>
                <li><a href="../autopark/">Автопарк</a></li>
                <li><a href="../profile/">Профиль</a></li>
            </ul>
        </div>
        <div class="footer-col">
            <h4>Информация</h4>
            <ul>
                <li><a href="../politics">Правовая информация</a></li>
                <li><a href="../politics/privacy/">Политика конфиденциальности</a></li>
                <li><a href="../politics/treatment/">Политика обработки данных</a></li>
                <li><a href="../politics/terms/">Пользовательское соглашение</a></li>
                <li><a href="../politics/cookies/">Политика cookie</a></li>
            </ul>
        </div>
        <div class="footer-col">
            <h4>Контакты</h4>
            <p>+7 (987) 654-32-10<br>info@svetlyput.ru<br>Одинцово, ул. Глазынинская, 18</p>
        </div>
        <div class="footer-col">
            <h4>Соцсети</h4>
            <div class="social-links">
                <a href="https://t.me/luckydevv" aria-label="Telegram">
                    <svg width="800px" height="800px" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg" fill="#000000" class="bi bi-telegram">
                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8.287 5.906c-.778.324-2.334.994-4.666 2.01-.378.15-.577.298-.595.442-.03.243.275.339.69.47l.175.055c.408.133.958.288 1.243.294.26.006.549-.1.868-.32 2.179-1.471 3.304-2.214 3.374-2.23.05-.012.12-.026.166.016.047.041.042.12.037.141-.03.129-1.227 1.241-1.846 1.817-.193.18-.33.307-.358.336a8.154 8.154 0 0 1-.188.186c-.38.366-.664.64.015 1.088.327.216.589.393.85.571.284.194.568.387.936.629.093.06.183.125.27.187.331.236.63.448.997.414.214-.02.435-.22.547-.82.265-1.417.786-4.486.906-5.751a1.426 1.426 0 0 0-.013-.315.337.337 0 0 0-.114-.217.526.526 0 0 0-.31-.093c-.3.005-.763.166-2.984 1.09z"/>
                    </svg>
                </a>
                <a href="#" aria-label="MAX">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 720 720" fill="#000000">
                        <path d="M350.4,9.6C141.8,20.5,4.1,184.1,12.8,390.4c3.8,90.3,40.1,168,48.7,253.7,2.2,22.2-4.2,49.6,21.4,59.3,31.5,11.9,79.8-8.1,106.2-26.4,9-6.1,17.6-13.2,24.2-22,27.3,18.1,53.2,35.6,85.7,43.4,143.1,34.3,299.9-44.2,369.6-170.3C799.6,291.2,622.5-4.6,350.4,9.6h0ZM269.4,504c-11.3,8.8-22.2,20.8-34.7,27.7-18.1,9.7-23.7-.4-30.5-16.4-21.4-50.9-24-137.6-11.5-190.9,16.8-72.5,72.9-136.3,150-143.1,78-6.9,150.4,32.7,183.1,104.2,72.4,159.1-112.9,316.2-256.4,218.6h0Z"/>
                    </svg>
                </a>
                <a href="https://vk.com/luckydevv" aria-label="VK">
                    <svg fill="#000000" width="800px" height="800px" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" data-name="Layer 1">
                        <path d="M15.07294,2H8.9375C3.33331,2,2,3.33331,2,8.92706V15.0625C2,20.66663,3.32294,22,8.92706,22H15.0625C20.66669,22,22,20.67706,22,15.07288V8.9375C22,3.33331,20.67706,2,15.07294,2Zm3.07287,14.27081H16.6875c-.55206,0-.71875-.44793-1.70831-1.4375-.86463-.83331-1.22919-.9375-1.44794-.9375-.30206,0-.38544.08332-.38544.5v1.3125c0,.35419-.11456.5625-1.04162.5625a5.69214,5.69214,0,0,1-4.44794-2.66668A11.62611,11.62611,0,0,1,5.35419,8.77081c0-.21875.08331-.41668.5-.41668H7.3125c.375,0,.51044.16668.65625.55212.70831,2.08331,1.91669,3.89581,2.40625,3.89581.1875,0,.27081-.08331.27081-.55206V10.10413c-.0625-.97913-.58331-1.0625-.58331-1.41663a.36008.36008,0,0,1,.375-.33337h2.29169c.3125,0,.41662.15625.41662.53125v2.89587c0,.3125.13544.41663.22919.41663.1875,0,.33331-.10413.67706-.44788a11.99877,11.99877,0,0,0,1.79169-2.97919.62818.62818,0,0,1,.63544-.41668H17.9375c.4375,0,.53125.21875.4375.53125A18.20507,18.20507,0,0,1,16.41669,12.25c-.15625.23956-.21875.36456,0,.64581.14581.21875.65625.64582,1,1.05207a6.48553,6.48553,0,0,1,1.22912,1.70837C18.77081,16.0625,18.5625,16.27081,18.14581,16.27081Z"/>
                    </svg>
                </a>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="footer-bottom">
            © 2026 Ритуальное агентство «Светлый Путь». Курсовой проект по разработке информационных систем.
        </div>
    </div>
</footer>
<!-- Модальное окно "Связаться" -->
<div class="modal-overlay" id="modalOverlay">
    <div class="modal-container">
        <button class="modal-close" id="modalClose">&times;</button>
        <div class="modal-content">
            <h2>Связаться с нами</h2>
            <p class="modal-subtitle">Оставьте свои контакты, и мы свяжемся с вами</p>
            <form class="modal-form" id="contactForm">
                <div class="form-group">
                    <label>Ваше имя</label>
                    <input type="text" id="modalName" placeholder="Введите ваше имя">
                </div>
                <div class="form-group">
                    <label>Номер телефона <span class="required">*</span></label>
                    <input type="tel" id="modalPhone" placeholder="+7 (___) ___-__-__" required>
                </div>
                <div class="form-group">
                    <label>Электронная почта</label>
                    <input type="email" id="modalEmail" placeholder="example@mail.ru">
                </div>
                <div class="form-agreement">
                    <input type="checkbox" id="modalAgreement" checked>
                    <label for="modalAgreement">Согласен на обработку персональных данных</label>
                </div>
                <button type="submit" class="modal-submit-btn">Отправить</button>
            </form>
            <div class="modal-contacts">
                <h3>Или свяжитесь напрямую:</h3>
                <div class="modal-contact-item">
                    <svg viewBox="0 0 24 24" width="20" height="20">
                        <rect x="5" y="2" width="14" height="20" rx="2" ry="2" stroke="currentColor" stroke-width="1.5" fill="none"/>
                        <line x1="12" y1="18" x2="12" y2="18" stroke="currentColor" stroke-width="1.5"/>
                    </svg>
                    <a href="tel:+79876543210">+7 (987) 654-32-10</a>
                </div>
                <div class="modal-contact-item">
                    <svg viewBox="0 0 24 24" width="20" height="20">
                        <rect x="2" y="4" width="20" height="16" rx="2" ry="2" stroke="currentColor" stroke-width="1.5" fill="none"/>
                        <polyline points="22,6 12,13 2,6" stroke="currentColor" stroke-width="1.5"/>
                    </svg>
                    <a href="mailto:info@svetlyput.ru">info@svetlyput.ru</a>
                </div>
            </div>
            <div class="modal-success" id="modalSuccess">
                <svg viewBox="0 0 24 24" width="48" height="48">
                    <circle cx="12" cy="12" r="10" fill="#d4a373"/>
                    <polyline points="8 12 11 15 16 9" stroke="white" stroke-width="2" fill="none"/>
                </svg>
                <h3>Спасибо!</h3>
                <p>Мы свяжемся с вами в ближайшее время.</p>
            </div>
        </div>
    </div>
</div>

<script src="src/js/script.js"></script>
<script src="../src/js/modal.js"></script>
</body>
</html>