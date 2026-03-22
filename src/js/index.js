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

        const href = link.getAttribute('href');
        if (href && href.startsWith('#')) {
            e.preventDefault();
            const target = document.querySelector(href);
            if (target) {
                target.scrollIntoView({ behavior: 'smooth' });
            }
        }
    });
});

// Слайдер
const track = document.getElementById('sliderTrack');
const leftBtn = document.getElementById('sliderLeft');
const rightBtn = document.getElementById('sliderRight');

if (track && leftBtn && rightBtn) {
    const slides = document.querySelectorAll('.slide-card');
    let currentIndex = 0;

    const getSlidesPerView = () => {
        if (window.innerWidth <= 576) return 1;
        if (window.innerWidth <= 992) return 2;
        return 3;
    };

    const updateSlider = () => {
        const slidesPerView = getSlidesPerView();
        const maxIndex = Math.max(0, slides.length - slidesPerView);
        currentIndex = Math.min(currentIndex, maxIndex);

        const slideWidth = slides[0].offsetWidth + 20;
        track.style.transform = `translateX(-${currentIndex * slideWidth}px)`;

        leftBtn.disabled = currentIndex === 0;
        rightBtn.disabled = currentIndex >= maxIndex;
    };

    leftBtn.addEventListener('click', () => {
        if (currentIndex > 0) { currentIndex--; updateSlider(); }
    });

    rightBtn.addEventListener('click', () => {
        const slidesPerView = getSlidesPerView();
        if (currentIndex < slides.length - slidesPerView) { currentIndex++; updateSlider(); }
    });

    window.addEventListener('resize', updateSlider);
    setTimeout(updateSlider, 100);
}

// Функция для раскрытия/скрытия тарифов
function toggleFeatures(featuresId, button) {
    const features = document.getElementById(featuresId);
    const isCollapsed = features.classList.contains('collapsed');

    if (isCollapsed) {
        features.classList.remove('collapsed');
        button.textContent = 'Скрыть';
    } else {
        features.classList.add('collapsed');
        button.textContent = 'Подробнее';
    }
}