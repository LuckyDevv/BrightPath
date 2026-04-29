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

// ===== МОБИЛЬНЫЕ ФИЛЬТРЫ =====
const filtersToggle = document.getElementById('filtersToggle');
const filtersWrapper = document.getElementById('filtersWrapper');
const closeFilters = document.getElementById('closeFilters');

if (filtersToggle && filtersWrapper && closeFilters) {
    filtersToggle.addEventListener('click', () => {
        filtersWrapper.classList.add('active');
        document.body.style.overflow = 'hidden';
    });

    closeFilters.addEventListener('click', () => {
        filtersWrapper.classList.remove('active');
        document.body.style.overflow = '';
    });

    filtersWrapper.addEventListener('click', (e) => {
        if (e.target === filtersWrapper) {
            filtersWrapper.classList.remove('active');
            document.body.style.overflow = '';
        }
    });
}

// ===== ДАННЫЕ УСЛУГ =====
let servicesData = [];

const categoryNames = {
    0: 'Организация похорон',
    1: 'Кремация',
    2: 'Перевоз тела',
    3: 'Юридическая помощь',
    4: 'Ритуальный транспорт',
    5: 'Поминальные обеды',
    6: 'Памятники и благоустройство'
};

// ===== ЭЛЕМЕНТЫ DOM =====
const servicesGrid = document.getElementById('servicesGrid');
const searchInput = document.getElementById('searchInput');
const mobileSearchInput = document.getElementById('mobileSearchInput');
const categoryFilter = document.getElementById('categoryFilter');
const categoryFilterMobile = document.getElementById('categoryFilterMobile');
const minPrice = document.getElementById('minPrice');
const maxPrice = document.getElementById('maxPrice');
const minPriceMobile = document.getElementById('minPriceMobile');
const maxPriceMobile = document.getElementById('maxPriceMobile');
const sortBy = document.getElementById('sortBy');
const sortByMobile = document.getElementById('sortByMobile');
const resetBtn = document.getElementById('resetFilters');
const resetBtnMobile = document.getElementById('resetFiltersMobile');
const noResults = document.getElementById('noResults');

// ===== СИНХРОНИЗАЦИЯ ПОИСКА =====
if (mobileSearchInput && searchInput) {
    mobileSearchInput.addEventListener('input', (e) => {
        searchInput.value = e.target.value;
        filterServices();
    });
    searchInput.addEventListener('input', (e) => {
        mobileSearchInput.value = e.target.value;
        filterServices();
    });
} else if (searchInput) {
    searchInput.addEventListener('input', filterServices);
}

// ===== РЕНДЕРИНГ =====
function renderServices(services) {
    servicesGrid.innerHTML = '';

    if (services.length === 0) {
        noResults.style.display = 'block';
        servicesGrid.style.display = 'none';
        return;
    }

    noResults.style.display = 'none';
    servicesGrid.style.display = 'grid';

    services.forEach(service => {
        const card = document.createElement('div');
        card.className = 'service-card';

        const formattedPrice = service.price.toLocaleString('ru-RU');
        const categoryName = categoryNames[service.category] || 'Другое';

        card.innerHTML = `
            <div class="service-content">
                <span class="service-category">${categoryName}</span>
                <h3 class="service-name">${escapeHtml(service.name)}</h3>
                <p class="service-description">${escapeHtml(service.description)}</p>
                <div class="service-price">от ${formattedPrice} ₽ <small>за услугу</small></div>
                <div class="service-actions">
                    <a href="service.php?id=${service.id}" class="btn-outline">Подробнее</a>
                    <a href="#" class="btn">Заказать</a>
                </div>
            </div>
        `;

        servicesGrid.appendChild(card);
    });
}

// ===== ФИЛЬТРАЦИЯ =====
function filterServices() {
    let filtered = [...servicesData];

    const searchTerm = searchInput ? searchInput.value.toLowerCase().trim() : '';
    if (searchTerm) {
        filtered = filtered.filter(s =>
            s.name.toLowerCase().includes(searchTerm) ||
            s.description.toLowerCase().includes(searchTerm)
        );
    }

    let category = 'all';
    if (categoryFilter && categoryFilter.value !== 'all') {
        category = parseInt(categoryFilter.value);
    } else if (categoryFilterMobile && categoryFilterMobile.value !== 'all') {
        category = parseInt(categoryFilterMobile.value);
    }
    if (category !== 'all') {
        filtered = filtered.filter(s => s.category === category);
    }

    let min = 0;
    let max = Infinity;
    if (minPrice && minPrice.value) min = parseInt(minPrice.value);
    if (maxPrice && maxPrice.value) max = parseInt(maxPrice.value);
    if ((!minPrice || !minPrice.value) && minPriceMobile && minPriceMobile.value) {
        min = parseInt(minPriceMobile.value);
    }
    if ((!maxPrice || !maxPrice.value) && maxPriceMobile && maxPriceMobile.value) {
        max = parseInt(maxPriceMobile.value);
    }
    filtered = filtered.filter(s => s.price >= min && s.price <= max);

    let sort = 'default';
    if (sortBy && sortBy.value !== 'default') {
        sort = sortBy.value;
    } else if (sortByMobile && sortByMobile.value !== 'default') {
        sort = sortByMobile.value;
    }

    switch(sort) {
        case 'priceAsc':
            filtered.sort((a, b) => a.price - b.price);
            break;
        case 'priceDesc':
            filtered.sort((a, b) => b.price - a.price);
            break;
        case 'nameAsc':
            filtered.sort((a, b) => a.name.localeCompare(b.name));
            break;
        case 'nameDesc':
            filtered.sort((a, b) => b.name.localeCompare(a.name));
            break;
        case 'popular':
            filtered.sort((a, b) => b.orders - a.orders);
            break;
        default:
            filtered.sort((a, b) => a.id - b.id);
    }

    renderServices(filtered);
}

// ===== СБРОС =====
function resetFilters() {
    if (searchInput) searchInput.value = '';
    if (mobileSearchInput) mobileSearchInput.value = '';
    if (categoryFilter) categoryFilter.value = 'all';
    if (categoryFilterMobile) categoryFilterMobile.value = 'all';
    if (minPrice) minPrice.value = '';
    if (maxPrice) maxPrice.value = '';
    if (minPriceMobile) minPriceMobile.value = '';
    if (maxPriceMobile) maxPriceMobile.value = '';
    if (sortBy) sortBy.value = 'default';
    if (sortByMobile) sortByMobile.value = 'default';

    filterServices();

    if (window.innerWidth <= 768 && filtersWrapper) {
        filtersWrapper.classList.remove('active');
        document.body.style.overflow = '';
    }
}

// ===== СЛУШАТЕЛИ =====
if (categoryFilter) categoryFilter.addEventListener('change', filterServices);
if (categoryFilterMobile) categoryFilterMobile.addEventListener('change', filterServices);
if (minPrice) minPrice.addEventListener('input', filterServices);
if (maxPrice) maxPrice.addEventListener('input', filterServices);
if (minPriceMobile) minPriceMobile.addEventListener('input', filterServices);
if (maxPriceMobile) maxPriceMobile.addEventListener('input', filterServices);
if (sortBy) sortBy.addEventListener('change', filterServices);
if (sortByMobile) sortByMobile.addEventListener('change', filterServices);
if (resetBtn) resetBtn.addEventListener('click', resetFilters);
if (resetBtnMobile) resetBtnMobile.addEventListener('click', resetFilters);

// ===== ЭКРАНИРОВАНИЕ =====
function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// ===== ИНИЦИАЛИЗАЦИЯ =====
document.addEventListener('DOMContentLoaded', () => {
    renderServices(servicesData);
});