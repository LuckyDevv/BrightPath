<!-- Модальное окно "Связаться" -->
<div class="modal-overlay" id="modalOverlay">
    <div class="modal-container">
        <button class="modal-close" id="modalClose">&times;</button>
        <div class="modal-content">
            <h2>Связаться с нами</h2>
            <p class="modal-subtitle">Оставьте свои контакты, и мы свяжемся с вами в ближайшее время</p>

            <form class="modal-form" id="contactForm">
                <div class="form-group">
                    <label for="modalName">Ваше имя</label>
                    <input type="text" id="modalName" placeholder="Введите ваше имя">
                </div>

                <div class="form-group">
                    <label for="modalPhone">Номер телефона <span class="required">*</span></label>
                    <input type="tel" id="modalPhone" placeholder="+7 (___) ___-__-__" required>
                </div>

                <div class="form-group">
                    <label for="modalEmail">Электронная почта</label>
                    <input type="email" id="modalEmail" placeholder="example@mail.ru">
                </div>

                <div class="form-agreement">
                    <input type="checkbox" id="modalAgreement" checked>
                    <label for="modalAgreement">Согласен на обработку персональных данных</label>
                </div>

                <button type="submit" class="modal-submit-btn" id="modalSubmitBtn">Отправить</button>
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