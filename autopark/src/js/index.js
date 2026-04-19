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

    // Закрытие по клику на фон
    filtersWrapper.addEventListener('click', (e) => {
        if (e.target === filtersWrapper) {
            filtersWrapper.classList.remove('active');
            document.body.style.overflow = '';
        }
    });
}

// ===== ДАННЫЕ АВТОМОБИЛЕЙ =====
let vehiclesData = [];

// ===== ЭЛЕМЕНТЫ DOM =====
const vehiclesGrid = document.getElementById('vehiclesGrid');
const searchInput = document.getElementById('searchInput');
const mobileSearchInput = document.getElementById('mobileSearchInput');
const categoryFilter = document.getElementById('categoryFilter');
const minPrice = document.getElementById('minPrice');
const maxPrice = document.getElementById('maxPrice');
const seatsFilter = document.getElementById('seatsFilter');
const sortBy = document.getElementById('sortBy');
const resetBtn = document.getElementById('resetFilters');
const resetBtn_mobile = document.getElementById('resetFilters_mobile');
const noResults = document.getElementById('noResults');
const stockFilter = document.getElementById('stockFilter');
// МОБИЛЬНЫЕ ЭЛЕМЕНТЫ DOM
const sortBy_mobile = document.getElementById('sortBy_mobile');
const stockFilter_mobile = document.getElementById('stockFilter_mobile');
const seatsFilter_mobile = document.getElementById('seatsFilter_mobile');
const maxPrice_mobile = document.getElementById('maxPrice_mobile');
const minPrice_mobile = document.getElementById('minPrice_mobile');
const categoryFilter_mobile = document.getElementById('categoryFilter_mobile');

// ===== СИНХРОНИЗАЦИЯ ПОИСКА (мобильный + десктоп) =====
if (mobileSearchInput && searchInput) {
    // При вводе в мобильный поиск
    mobileSearchInput.addEventListener('input', (e) => {
        searchInput.value = e.target.value;
        filterVehicles();
    });

    // При вводе в десктопный поиск
    searchInput.addEventListener('input', (e) => {
        mobileSearchInput.value = e.target.value;
        filterVehicles();
    });
} else if (searchInput) {
    // Если только десктопный поиск
    searchInput.addEventListener('input', filterVehicles);
}

function plural(number, titles) {
    let cases = [2, 0, 1, 1, 1, 2];
    return titles[ (number%100>4 && number%100<20)? 2 : cases[(number%10<5)?number%10:5] ];
}

// ===== ФУНКЦИЯ РЕНДЕРИНГА КАРТОЧЕК =====
function renderVehicles(vehicles) {
    vehiclesGrid.innerHTML = '';

    if (vehicles.length === 0) {
        noResults.style.display = 'block';
        vehiclesGrid.style.display = 'none';
        return;
    }

    noResults.style.display = 'none';
    vehiclesGrid.style.display = 'grid';

    vehicles.forEach(vehicle => {
        const card = document.createElement('div');
        card.className = 'vehicle-card';

        // Форматирование цены
        const formattedPrice = Math.floor(vehicle.price).toLocaleString('ru-RU');

        // Получаем русское название категории
        let categoryName = '';
        switch(vehicle.category) {
            case 1: categoryName = 'Катафалк'; break;
            case 2: categoryName = 'Автобус для гостей'; break;
            case 3: categoryName = 'Легковой'; break;
            case 4: categoryName = 'Спецтранспорт'; break;
            default: categoryName = vehicle.category;
        }

        let declension = ['место', 'места', 'мест'];
        let seats = `${vehicle.seats} ${plural(vehicle.seats, declension)}`
        card.innerHTML = `
            <div class="vehicle-image">
                <img src="../src/images/vehicles/${vehicle.image_path}/main.jpg" alt="${vehicle.name}" onerror="this.src='../src/images/placeholder.jpg'">
            </div>
            <div class="vehicle-content">
                <span class="vehicle-category">${categoryName}</span>
                <h3 class="vehicle-name">${vehicle.name}</h3>
                <div class="vehicle-details">
                    <span class="vehicle-detail">   
                        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M22.5 20V22C22.5 22.5523 22.0523 23 21.5 23H7.59646C6.66266 23 5.85314 22.3538 5.64619 21.4432L1.27764 2.22162C1.13542 1.59586 1.61105 1 2.25277 1H5.70799C6.17204 1 6.57512 1.31925 6.6814 1.77096L10.5 18H20.5C21.6046 18 22.5 18.8954 22.5 20Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M6.96729 3H8.99999C9.55228 3 10 3.44772 10 4V6L8 7.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M13.5 14.375H9.625H7.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        ${seats}
                    </span>
                    <span class="vehicle-detail">               
                        <svg stroke-width="2" stroke="currentColor" viewBox="0 0 400 400" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-width="40" stroke="currentColor" fill-rule="evenodd" clip-rule="evenodd" d="M275.002 258C275.002 304.5 237.221 336 201.926 336C166.631 336 123.002 297.5 123.002 267C123.002 185.5 196.029 173 196.029 64C201.926 163 275.002 202 275.002 258Z" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        ${vehicle.color}
                    </span>
                    <span class="vehicle-detail" style="margin-left: 4px">    
                        <svg stroke="currentColor" stroke-width="0.7" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M7 20H4.6C4.03995 20 3.75992 20 3.54601 19.891C3.35785 19.7951 3.20487 19.6422 3.10899 19.454C3 19.2401 3 18.9601 3 18.4V9.0398C3 8.66343 3 8.47524 3.05919 8.31095C3.1115 8.16573 3.19673 8.03458 3.30819 7.9278C3.43428 7.80699 3.60625 7.73056 3.95018 7.5777L12 4L20.0498 7.5777C20.3938 7.73056 20.5657 7.80699 20.6918 7.9278C20.8033 8.03458 20.8885 8.16573 20.9408 8.31095C21 8.47524 21 8.66343 21 9.0398V18.4C21 18.9601 21 19.2401 20.891 19.454C20.7951 19.6422 20.6422 19.7951 20.454 19.891C20.2401 20 19.9601 20 19.4 20H17M7 20H17M7 20V14M17 20V14M7 14V10H17V14M7 14H17" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        ${vehicle.available} шт.
                    </span>
                </div>
                <p class="vehicle-description">${vehicle.description}</p>
                <div class="vehicle-price">от ${formattedPrice} ₽ <small>за мероприятие</small></div>
                <div class="vehicle-actions">
                    <a href="vehicle.php?id=${vehicle.id}" class="btn-outline">Подробнее</a>
                    <a href="#" class="btn">Заказать</a>
                </div>
            </div>
        `;

        vehiclesGrid.appendChild(card);
    });
}

function filterVehicles() {
    let filtered = [...vehiclesData];

    // Поиск по названию и описанию
    const searchTerm = searchInput ? searchInput.value.toLowerCase().trim() : '';
    if (searchTerm) {
        filtered = filtered.filter(v =>
            v.name.toLowerCase().includes(searchTerm) ||
            v.description.toLowerCase().includes(searchTerm)
        );
    }

    // ===== ФИЛЬТР ПО КАТЕГОРИИ (десктоп + мобильный) =====
    let category = 'all';
    if (categoryFilter && categoryFilter.value !== 'all') {
        category = categoryFilter.value;
    } else if (categoryFilter_mobile && categoryFilter_mobile.value !== 'all') {
        category = categoryFilter_mobile.value;
    }

    if (category !== 'all') {
        filtered = filtered.filter(v => v.category == category);
    }

    // ===== ФИЛЬТР ПО ЦЕНЕ (десктоп + мобильный) =====
    let min = 0;
    let max = Infinity;

    // Десктопные цены
    if (minPrice && minPrice.value) min = parseInt(minPrice.value);
    if (maxPrice && maxPrice.value) max = parseInt(maxPrice.value);

    // Мобильные цены (если десктопные не заданы)
    if ((!minPrice || !minPrice.value) && minPrice_mobile && minPrice_mobile.value) {
        min = parseInt(minPrice_mobile.value);
    }
    if ((!maxPrice || !maxPrice.value) && maxPrice_mobile && maxPrice_mobile.value) {
        max = parseInt(maxPrice_mobile.value);
    }

    filtered = filtered.filter(v => v.price >= min && v.price <= max);

    // ===== ФИЛЬТР ПО КОЛИЧЕСТВУ МЕСТ (десктоп + мобильный) =====
    let seats = 'all';
    if (seatsFilter && seatsFilter.value !== 'all') {
        seats = seatsFilter.value;
    } else if (seatsFilter_mobile && seatsFilter_mobile.value !== 'all') {
        seats = seatsFilter_mobile.value;
    }

    if (seats !== 'all') {
        const maxSeats = parseInt(seats);
        filtered = filtered.filter(v => v.seats <= maxSeats);
    }

    // ===== ФИЛЬТР ПО НАЛИЧИЮ (десктоп + мобильный) =====
    let stockValue = 'all';
    if (stockFilter && stockFilter.value !== 'all') {
        stockValue = stockFilter.value;
    } else if (stockFilter_mobile && stockFilter_mobile.value !== 'all') {
        stockValue = stockFilter_mobile.value;
    }

    if (stockValue !== 'all') {
        const minStock = parseInt(stockValue);
        filtered = filtered.filter(v => (v.stock || 1) >= minStock);
    }

    // ===== СОРТИРОВКА (десктоп + мобильный) =====
    let sort = 'default';
    if (sortBy && sortBy.value !== 'default') {
        sort = sortBy.value;
    } else if (sortBy_mobile && sortBy_mobile.value !== 'default') {
        sort = sortBy_mobile.value;
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
        case 'yearDesc':
            filtered.sort((a, b) => b.year - a.year);
            break;
        case 'yearAsc':
            filtered.sort((a, b) => a.year - b.year);
            break;
        default:
            filtered.sort((a, b) => (b.orders || 0) - (a.orders || 0));
    }

    renderVehicles(filtered);
}

// ===== СБРОС ФИЛЬТРОВ =====
function resetFilters() {
    // Десктопные фильтры
    if (searchInput) searchInput.value = '';
    if (mobileSearchInput) mobileSearchInput.value = '';
    if (categoryFilter) categoryFilter.value = 'all';
    if (minPrice) minPrice.value = '';
    if (maxPrice) maxPrice.value = '';
    if (seatsFilter) seatsFilter.value = 'all';
    if (stockFilter) stockFilter.value = 'all';
    if (sortBy) sortBy.value = 'default';

    // Мобильные фильтры (ДОБАВЛЯЕМ!)
    if (categoryFilter_mobile) categoryFilter_mobile.value = 'all';
    if (minPrice_mobile) minPrice_mobile.value = '';
    if (maxPrice_mobile) maxPrice_mobile.value = '';
    if (seatsFilter_mobile) seatsFilter_mobile.value = 'all';
    if (stockFilter_mobile) stockFilter_mobile.value = 'all';
    if (sortBy_mobile) sortBy_mobile.value = 'default';

    filterVehicles();
}

// ===== СЛУШАТЕЛИ СОБЫТИЙ =====
if (categoryFilter) categoryFilter.addEventListener('change', filterVehicles);
if (categoryFilter_mobile) categoryFilter_mobile.addEventListener('change', filterVehicles);
if (minPrice) minPrice.addEventListener('input', filterVehicles);
if (maxPrice) maxPrice.addEventListener('input', filterVehicles);
if (minPrice_mobile) minPrice_mobile.addEventListener('input', filterVehicles);
if (maxPrice_mobile) maxPrice_mobile.addEventListener('input', filterVehicles);
if (seatsFilter) seatsFilter.addEventListener('change', filterVehicles);
if (seatsFilter_mobile) seatsFilter_mobile.addEventListener('change', filterVehicles);
if (sortBy) sortBy.addEventListener('change', filterVehicles);
if (sortBy_mobile) sortBy_mobile.addEventListener('change', filterVehicles);
if (resetBtn) resetBtn.addEventListener('click', resetFilters);
if (resetBtn_mobile) resetBtn_mobile.addEventListener('click', resetFilters);
if (stockFilter) stockFilter.addEventListener('change', filterVehicles);
if (stockFilter_mobile) stockFilter_mobile.addEventListener('change', filterVehicles);

// ===== ИНИЦИАЛИЗАЦИЯ =====
document.addEventListener('DOMContentLoaded', () => {
    renderVehicles(vehiclesData);
});

// ===== ОБРАБОТКА КЛИКОВ ПО ССЫЛКАМ С ЯКОРЯМИ =====
document.querySelectorAll('a[href^="#"]').forEach(link => {
    link.addEventListener('click', (e) => {
        const href = link.getAttribute('href');
        if (href && href.startsWith('#') && href !== '#') {
            e.preventDefault();
            const target = document.querySelector(href);
            if (target) {
                target.scrollIntoView({ behavior: 'smooth' });
            }
        }
    });
});