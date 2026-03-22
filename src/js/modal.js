
// ===== МОДАЛЬНОЕ ОКНО "СВЯЗАТЬСЯ" =====
const modalOverlay = document.getElementById('modalOverlay');
const modalClose = document.getElementById('modalClose');

function openModal(e){
    if (e !== undefined) e.preventDefault();
    modalOverlay.classList.add('active');
    document.body.style.overflow = 'hidden'; // Запрещаем скролл
}

// Закрытие модального окна
function closeModal() {
    modalOverlay.classList.remove('active');
    document.body.style.overflow = ''; // Возвращаем скролл

    // Сброс формы
    const form = document.getElementById('contactForm');
    const success = document.getElementById('modalSuccess');
    if (success) success.classList.remove('active');
    if (form) {
        form.style.display = 'block';
        form.classList.remove('submitted'); // Убираем класс отправки
    }
    contactForm.style.display = 'block';
    contactForm.classList.remove('submitted');
    success.classList.remove('active');
    contactForm.reset();
    checkAgreement(); // Проверяем чекбокс после сброса
}

if (modalClose) {
    modalClose.addEventListener('click', closeModal);
}

// Закрытие по клику на оверлей
if (modalOverlay) {
    modalOverlay.addEventListener('click', (e) => {
        if (e.target === modalOverlay) {
            closeModal();
        }
    });
}

// Обработка отправки формы
const contactForm = document.getElementById('contactForm');
const agreementCheckbox = document.getElementById('modalAgreement');
const submitBtn = document.querySelector('.modal-submit-btn');

// Функция проверки согласия
function checkAgreement() {
    if (agreementCheckbox && submitBtn) {
        if (!agreementCheckbox.checked) {
            submitBtn.disabled = true;
            submitBtn.style.opacity = '0.5';
            submitBtn.style.cursor = 'not-allowed';
            submitBtn.title = 'Необходимо согласие на обработку данных';
        } else {
            submitBtn.disabled = false;
            submitBtn.style.opacity = '1';
            submitBtn.style.cursor = 'pointer';
            submitBtn.title = '';
        }
    }
}

// Проверяем при загрузке
if (agreementCheckbox) {
    agreementCheckbox.addEventListener('change', checkAgreement);
    checkAgreement(); // Первоначальная проверка
}

if (contactForm) {
    contactForm.addEventListener('submit', (e) => {
        e.preventDefault();

        // Дополнительная проверка перед отправкой
        if (!agreementCheckbox || !agreementCheckbox.checked) {
            // Если чекбокс не отмечен, подсвечиваем его
            if (agreementCheckbox) {
                agreementCheckbox.parentElement.style.color = '#d4a373';
                agreementCheckbox.style.outline = '2px solid #d4a373';
                setTimeout(() => {
                    agreementCheckbox.parentElement.style.color = '';
                    agreementCheckbox.style.outline = '';
                }, 1000);
            }
            return; // Прерываем отправку
        }

        // Здесь можно добавить отправку данных на сервер
        console.log('Форма отправлена');

        // Показываем сообщение об успехе
        contactForm.style.display = 'none';
        const success = document.getElementById('modalSuccess');
        if (success) success.classList.add('active');

        // Автоматическое закрытие через 3 секунды
        setTimeout(() => {
            closeModal();

        }, 3000);
    });
}

// Закрытие по Escape
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && modalOverlay && modalOverlay.classList.contains('active')) {
        closeModal();
    }
});