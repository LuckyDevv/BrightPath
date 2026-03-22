// ===== БУРГЕР МЕНЮ =====
const burger = document.getElementById('burger');
const nav = document.getElementById('nav');
const overlay = document.getElementById('overlay');

function toggleMenu() {
    burger.classList.toggle('active');
    nav.classList.toggle('active');
    overlay.classList.toggle('active');
    document.body.style.overflow = nav.classList.contains('active') ? 'hidden' : '';
}

burger.addEventListener('click', toggleMenu);
overlay.addEventListener('click', toggleMenu);

document.querySelectorAll('.nav a').forEach(link => {
    link.addEventListener('click', (e) => {
        if (nav.classList.contains('active')) {
            toggleMenu();
        }
    });
});

// ===== ГАЛЕРЕЯ (переключение фото) =====
function changeImage(thumbnail) {
    // Получаем главное изображение
    const mainImage = document.getElementById('mainImage');

    // Получаем путь к изображению из миниатюры
    // Меняем главное изображение
    mainImage.src = thumbnail.src;

    // Убираем класс active у всех миниатюр
    document.querySelectorAll('.thumbnail').forEach(thumb => {
        thumb.classList.remove('active');
    });

    // Добавляем класс active текущей миниатюре
    thumbnail.closest('.thumbnail').classList.add('active');
}

// Делаем функцию глобальной
window.changeImage = changeImage;