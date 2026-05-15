
// ===== МОДАЛЬНОЕ ОКНО "СВЯЗАТЬСЯ" =====
const modalOverlay = document.getElementById('modalOverlay');
const modalClose = document.getElementById('modalClose');

function openModal(e){
    if (e !== undefined) e.preventDefault();
    modalOverlay.classList.add('active');
    document.body.style.overflow = 'hidden'; // Запрещаем скролл
}

function getBasePath() {
    // Получаем путь текущей страницы (например: /catalog/goods/item.html)
    let path = window.location.pathname;

    // Убираем домен и файл в конце (если есть)
    let pathSegments = path.split('/').filter(segment => segment !== '' && !segment.includes('.'));

    // Количество папок от корня = количество сегментов пути
    let depth = pathSegments.length;

    // Формируем путь к корню: если depth=0 -> './', depth=1 -> '../', depth=2 -> '../../'
    return depth === 0 ? './' : '../'.repeat(depth);
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

        let name = document.getElementById("modalName").value;
        let email = document.getElementById("modalEmail").value;
        let phone = document.getElementById("modalPhone").value;
        console.log(name + ", " + email + ", " + phone);
        let iff = name != null && email != null && phone != null;
        console.log(`If: ${iff}`)

        if (name != null && email != null && phone != null) {
            $.post(getBasePath()+"server/post/userRequestsHandler.php", {
                "type": "sendRequest",
                "name": name,
                "email": email,
                "phone": phone
            }, function (data) {
                console.log("Ответ пришёл");
                let response;
                try {
                    response = JSON.parse(data);
                    console.log("Ответ спарсирован");
                }catch(e){
                    console.log(e);
                    Toast.error("Ошибка подключения к серверу");
                    return;
                }
                console.log(response);
                if (response.response) {
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
                }else if (response.error) {
                    Toast.error(`Ошибка [${response.error.code}]: ${response.error.message}`);
                }else{
                    Toast.error("Ошибка подключения к серверу");
                }
            });
        }else{
            Toast.warning("Введите контактные данные!");
        }
    });
}

// Закрытие по Escape
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && modalOverlay && modalOverlay.classList.contains('active')) {
        closeModal();
    }
});