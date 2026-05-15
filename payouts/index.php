<?php
include_once __DIR__ . "/../vendor/autoload.php";
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

$loader = new FilesystemLoader('../server/twig');
$twig = new Environment($loader, [
    //'cache' => '../server/twig/cache',
        'autoescape' => false,
]);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.5, user-scalable=yes">
  <title>Пособие на погребение 2026 - Светлый Путь</title>
  <link rel="stylesheet" href="src/css/index.css">
  <link rel="stylesheet" href="../src/css/nav.css">
  <link rel="stylesheet" href="../src/css/modal.css">
  <link rel="stylesheet" href="../src/css/footer.css">
    <link rel="stylesheet" href="../src/css/toasts.css">
  <link rel="icon" href="../logo.png" type="image/png">
</head>
<body>
<!-- Хедер (такой же как на главной) -->
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
      <a href="../calculator/">Калькулятор</a>
      <a href="../autopark/">Автопарк</a>
      <a href="../orders/">Заказы</a>
      <a onclick="openModal()" class="btn-light" id="contactBtn">Связаться</a>
    </nav>
  </div>
  <div class="overlay" id="overlay"></div>
</header>

<main>
  <!-- Заголовок страницы -->
  <section class="page-header">
    <div class="container">
      <h1>Пособие на погребение в 2026 году</h1>
      <div class="section-divider"></div>
      <p class="subtitle">Актуальная информация о выплатах, документах и сроках</p>
    </div>
  </section>

  <!-- Основной контент -->
  <section class="content-section">
    <div class="container">
      <!-- Информационный блок 1: Размер пособия -->
      <div class="info-card highlight-card">
        <div class="info-icon">
          <svg viewBox="0 0 24 24" width="48" height="48" stroke="#d4a373" fill="none">
            <circle cx="12" cy="12" r="10" stroke-width="1.5"/>
            <path d="M12 6v8l4 2" stroke-width="1.5"/>
          </svg>
        </div>
        <div class="info-content">
          <h2>Размер пособия в 2026 году</h2>
          <p class="payout-amount">9 678,63 ₽</p>
          <p class="payout-note">базовый размер по РФ</p>
          <div class="region-info">
            <h3>В Москве и Московской области:</h3>
            <ul>
              <li><strong>г. Москва:</strong> дополнительная социальная выплата до 17 000 ₽ (при обращении в соцзащиту)</li>
              <li><strong>Московская область:</strong> 13 000–15 000 ₽ в зависимости от муниципалитета</li>
              <li><strong>Одинцово:</strong> 12 500 ₽ (региональная доплата)</li>
            </ul>
            <p class="region-note">* Точный размер уточняйте в Одинцовском управлении соцзащиты</p>
          </div>
        </div>
      </div>

      <!-- Информационный блок 2: Кто может получить -->
      <div class="info-grid">
        <div class="info-card">
          <div class="info-icon">
            <svg viewBox="0 0 24 24" width="40" height="40" stroke="#d4a373" fill="none">
              <!-- Голова (поднята выше) -->
              <circle cx="12" cy="6" r="4" stroke-width="1.5"/>
              <!-- Тело -->
              <path d="M4 20v-2a8 8 0 0 1 16 0v2" stroke-width="1.5"/>
            </svg>
          </div>
          <h3>Кто может получить</h3>
          <ul class="info-list">
            <li>Супруг(а) умершего</li>
            <li>Близкие родственники</li>
            <li>Законные представители</li>
            <li>Иные лица, взявшие на себя организацию похорон</li>
          </ul>
        </div>

        <div class="info-card">
          <div class="info-icon">
            <svg viewBox="0 0 24 24" width="40" height="40" stroke="#d4a373" fill="none">
              <rect x="3" y="4" width="18" height="16" rx="2" stroke-width="1.5"/>
              <line x1="8" y1="10" x2="16" y2="10" stroke-width="1.5"/>
              <line x1="8" y1="14" x2="12" y2="14" stroke-width="1.5"/>
            </svg>
          </div>
          <h3>Сроки обращения</h3>
          <p class="deadline">Не позднее <strong>6 месяцев</strong> со дня смерти</p>
        </div>

        <div class="info-card">
          <div class="info-icon">
            <svg viewBox="0 0 24 24" width="40" height="40" stroke="#d4a373" fill="none">
              <path d="M4 4h16v16H4z" stroke-width="1.5"/>
              <circle cx="12" cy="12" r="2" fill="#d4a373"/>
            </svg>
          </div>
          <h3>Куда обращаться</h3>
          <p>С 1 января 2025 года заявления подаются в <strong>Социальный фонд России (СФР)</strong></p>
          <p>Способы подачи:</p>
          <ul class="info-list" style="margin-top: 5px">
            <li>Лично в клиентской службе СФР</li>
            <li>Через <a href="https://gosuslugi.ru">Госуслуги</a></li>
          </ul>
        </div>
      </div>

      <!-- Информационный блок 3: Документы -->
      <div class="documents-section">
        <h2>Необходимые документы</h2>
        <div class="documents-grid">
          <div class="document-item">
            <div class="doc-icon">
              <svg viewBox="0 0 24 24" width="32" height="32" stroke="#d4a373" fill="none">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6z" stroke-width="1.5"/>
                <path d="M14 2v6h6" stroke-width="1.5"/>
              </svg>
            </div>
            <span>Заявление (по форме СФР)</span>
          </div>
          <div class="document-item">
            <div class="doc-icon">
              <svg viewBox="0 0 24 24" width="32" height="32" stroke="#d4a373" fill="none">
                <rect x="2" y="2" width="20" height="20" rx="2" stroke-width="1.5"/>
                <line x1="8" y1="2" x2="8" y2="22" stroke-width="1.5"/>
              </svg>
            </div>
            <span>Справка о смерти (форма № 11/СФР)</span>
          </div>
          <div class="document-item">
            <div class="doc-icon">
              <svg width="40" height="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <!-- Контур паспорта (прямоугольник) -->
                <rect x="3.41" y="1.5" width="17.18" height="21" stroke="#d4a373" stroke-width="1.5" fill="white"/>

                <!-- Внешний круг герба -->
                <circle cx="12" cy="10.09" r="4.77" stroke="#d4a373" stroke-width="1.5" fill="white"/>

                <!-- Внутренний круг герба -->
                <circle cx="12" cy="10.09" r="1.91" stroke="#d4a373" stroke-width="1.5" fill="white"/>

                <!-- Горизонтальные линии на гербе -->
                <line x1="7.23" y1="10.09" x2="16.77" y2="10.09" stroke="#d4a373" stroke-width="1.5"/>

                <!-- Нижняя полоска (как на обложке паспорта) -->
                <line x1="7.23" y1="18.68" x2="16.77" y2="18.68" stroke="#d4a373" stroke-width="1.5"/>
              </svg>
            </div>
            <span>Паспорт заявителя</span>
          </div>
          <div class="document-item">
            <div class="doc-icon">
              <svg width="40" height="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <!-- Контур карты -->
                <rect x="2" y="4" width="20" height="16" rx="2" stroke="#d4a373" stroke-width="1.5" fill="white"/>

                <!-- Магнитная полоса -->
                <rect x="4" y="7" width="16" height="2" fill="#d4a373" opacity="0.3"/>

                <!-- Чип -->
                <rect x="6" y="10" width="4" height="3" rx="0.5" stroke="#d4a373" stroke-width="1" fill="white"/>

                <!-- Полоски -->
                <line x1="12" y1="12" x2="18" y2="12" stroke="#d4a373" stroke-width="1"/>
                <line x1="12" y1="14" x2="16" y2="14" stroke="#d4a373" stroke-width="1"/>
              </svg>
            </div>
            <span>Реквизиты счета для перевода</span>
          </div>
        </div>

        <div class="additional-docs">
          <h3>Дополнительные документы (в особых случаях):</h3>
          <ul class="info-list">
            <li><strong>Для неработавших:</strong> трудовая книжка или справка с места жительства</li>
            <li><strong>Для реабилитированных:</strong> свидетельство о реабилитации (размер 25 000 ₽)</li>
            <li><strong>При смерти за границей:</strong> документ о смерти от компетентного органа иностранного государства</li>
            <li><strong>Договор и квитанции</strong> на ритуальные услуги (для компенсации)</li>
          </ul>
        </div>
      </div>

      <!-- Информационный блок 4: Кто выплачивает -->
      <div class="info-block">
        <h2 style="margin-bottom: 15px">Кто выплачивает пособие?</h2>
        <div class="payment-grid">
          <div class="payment-item">
            <h3>Если умерший работал</h3>
            <p>Пособие выплачивает <strong>работодатель</strong>, затем он возмещает расходы в СФР</p>
          </div>
          <div class="payment-item">
            <h3>Если умерший - пенсионер</h3>
            <p>Выплачивает <strong>СФР</strong> (по линии пенсионного обеспечения)</p>
          </div>
          <div class="payment-item">
            <h3>Если умерший не работал и не был пенсионером</h3>
            <p>Выплачивает <strong>орган соцзащиты</strong></p>
          </div>
          <div class="payment-item">
            <h3>Военнослужащие и силовики</h3>
            <p>Выплата через <strong>соответствующие ведомства</strong> (Минобороны, МВД и др.)</p>
          </div>
        </div>
      </div>

      <!-- Информационный блок 5: Гарантированный перечень услуг -->
      <div class="services-block">
        <h2>Гарантированный перечень услуг по погребению</h2>
        <p class="services-note">Вместо получения пособия можно получить бесплатные услуги в пределах:</p>

        <table class="services-table">
          <thead>
          <tr>
            <th>№</th>
            <th>Вид услуги</th>
            <th>Стоимость (руб.)</th>
          </tr>
          </thead>
          <tbody>
          <tr>
            <td>1</td>
            <td>Оформление документов, необходимых для погребения</td>
            <td>—</td>
          </tr>
          <tr>
            <td>2</td>
            <td>Предоставление и доставка гроба и других предметов</td>
            <td>2 716,19</td>
          </tr>
          <tr>
            <td>3</td>
            <td>Перевозка тела на кладбище (в крематорий)</td>
            <td>1 018,55</td>
          </tr>
          <tr>
            <td>4</td>
            <td>Погребение (кремация)</td>
            <td>5 943,89</td>
          </tr>
          <tr class="total-row">
            <td colspan="2"><strong>ИТОГО (размер пособия):</strong></td>
            <td><strong>9 678,63</strong></td>
          </tr>
          </tbody>
        </table>
        <p class="table-note">* Именно эту сумму вы можете получить деньгами или выбрать бесплатные услуги на эту сумму</p>
      </div>

      <!-- Информационный блок 6: Дополнительные выплаты -->
      <div class="info-card">
        <h2 style="margin-bottom: 25px;">Дополнительные выплаты в Москве и области</h2>

        <table class="payout-table">
          <thead>
          <tr>
            <th>Город / Регион</th>
            <th>Размер доплаты</th>
            <th>Примечание</th>
          </tr>
          </thead>
          <tbody>
          <tr>
            <td data-label="Город / Регион"><span class="value"><strong>Москва</strong></span></td>
            <td data-label="Размер доплаты" class="price-cell"><span class="value">до 17 000 ₽</span></td>
            <td data-label="Примечание"><span class="value">при обращении в соцзащиту</span></td>
          </tr>
          <tr>
            <td data-label="Город / Регион"><span class="value"><strong>Одинцово</strong></span></td>
            <td data-label="Размер доплаты" class="price-cell"><span class="value">12 500 ₽</span></td>
            <td data-label="Примечание"><span class="value">региональная доплата</span></td>
          </tr>
          <tr>
            <td data-label="Город / Регион"><span class="value"><strong>Московская область</strong></span></td>
            <td data-label="Размер доплаты" class="price-cell"><span class="value">13 000 – 15 000 ₽</span></td>
            <td data-label="Примечание"><span class="value">зависит от муниципалитета</span></td>
          </tr>
          </tbody>
        </table>

        <p style="color: #888; font-size: 0.9rem; margin-top: 20px; text-align: center;">
          * Точный размер уточняйте в Одинцовском управлении соцзащиты
        </p>
      </div>

      <!-- CTA блок -->
      <div class="cta-block">
        <h2>Нужна помощь в оформлении?</h2>
        <p>Наши специалисты помогут собрать документы и подать заявление</p>
        <a class="btn btn-large" onclick="openModal()" style="background-color: #1e1e1e; color: white;">Получить консультацию</a>
      </div>
    </div>
  </section>
</main>
<?php echo $twig->render('page_end.twig', ['basePath' => '../', "config" => new \lib\Config()->getConfig()]); ?>
<script src="../src/js/jquery.min.js"></script>
<script src="../src/js/toasts.js"></script>
<script src="../src/js/modal.js"></script>
<script src="src/js/index.js"></script>
</body>
</html>