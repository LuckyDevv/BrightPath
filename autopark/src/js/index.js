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
const vehiclesData = [
    {
        id: 1,
        name: 'Mercedes-Benz E-Class',
        category: 'катафалк',
        image: 'src/images/mercedes-e-class.jpg',
        seats: 2,
        year: 2022,
        color: 'Черный',
        transmission: 'Автомат',
        fuel: 'Дизель',
        description: 'Премиальный катафалк для торжественных церемоний. Отделка салона премиум-класса.',
        price: 25000,
        available: true,
        orders: 45,
        stock: 12
    },
    {
        id: 2,
        name: 'Cadillac CTS',
        category: 'катафалк',
        image: 'src/images/cadillac-cts.jpg',
        seats: 2,
        year: 2021,
        color: 'Черный',
        transmission: 'Автомат',
        fuel: 'Бензин',
        description: 'Представительский катафалк американского производства. Просторный салон.',
        price: 30000,
        available: true,
        orders: 38,
        stock: 9
    },
    {
        id: 3,
        name: 'Hummer H2',
        category: 'катафалк',
        image: 'src/images/hummer-h2.webp',
        seats: 2,
        year: 2020,
        color: 'Черный',
        transmission: 'Автомат',
        fuel: 'Бензин',
        description: 'Уникальный лимузин-катафалк на базе Hummer. Вместительный и внушительный.',
        price: 35000,
        available: true,
        orders: 29,
        stock: 4
    },
    {
        id: 4,
        name: 'ГАЗель NEXT',
        category: 'катафалк',
        image: 'src/images/gazel-next.jpg',
        seats: 2,
        year: 2023,
        color: 'Черный',
        transmission: 'Механика',
        fuel: 'Дизель',
        description: 'Экономичный и надежный катафалк для любых мероприятий.',
        price: 13000,
        available: true,
        orders: 52,
        stock: 19
    },
    {
        id: 5,
        name: 'Mercedes-Benz Sprinter',
        category: 'автобус',
        image: 'src/images/mercedes-sprinter.webp',
        seats: 20,
        year: 2022,
        color: 'Черный',
        transmission: 'Автомат',
        fuel: 'Дизель',
        description: 'Комфортабельный автобус для перевозки гостей. Кондиционер, мягкие кресла.',
        price: 24000,
        available: true,
        orders: 61,
        stock: 11
    },
    {
        id: 6,
        name: 'Ford Transit',
        category: 'автобус',
        image: 'src/images/ford-transit.webp',
        seats: 16,
        year: 2021,
        color: 'Черный',
        transmission: 'Механика',
        fuel: 'Дизель',
        description: 'Надежный микроавтобус для перевозки гостей.',
        price: 18000,
        available: true,
        orders: 43,
        stock: 7
    },
    {
        id: 7,
        name: 'ПАЗ Вектор NEXT',
        category: 'автобус',
        image: 'src/images/paz-vector-next.webp',
        seats: 30,
        year: 2022,
        color: 'Белый',
        transmission: 'Механика',
        fuel: 'Дизель',
        description: 'Вместительный автобус для больших групп. Отличная проходимость.',
        price: 10000,
        available: true,
        orders: 27,
        stock: 9
    },
    {
        id: 8,
        name: 'Toyota Camry',
        category: 'легковой',
        image: 'src/images/toyota-camry.webp',
        seats: 4,
        year: 2023,
        color: 'Черный',
        transmission: 'Автомат',
        fuel: 'Бензин',
        description: 'Автомобиль для сопровождения кортежа. Комфортный и престижный.',
        price: 8000,
        available: true,
        orders: 73,
        stock: 9
    },
    {
        id: 9,
        name: 'Skoda Octavia',
        category: 'легковой',
        image: 'src/images/skoda-octavia.webp',
        seats: 4,
        year: 2022,
        color: 'Черный',
        transmission: 'Автомат',
        fuel: 'Бензин',
        description: 'Надежный автомобиль для поездок. Отличное соотношение цены и качества.',
        price: 6000,
        available: true,
        orders: 68,
        stock: 13
    },
    {
        id: 10,
        name: 'Hyundai Solaris',
        category: 'легковой',
        image: 'src/images/hyundai-solaris.webp',
        seats: 4,
        year: 2021,
        color: 'Черный',
        transmission: 'Автомат',
        fuel: 'Бензин',
        description: 'Экономичный автомобиль для поездок. Идеален для небольших групп.',
        price: 4000,
        available: true,
        orders: 55,
        stock: 17
    },
    {
        id: 11,
        name: 'DongFeng K33-561',
        category: 'спецтранспорт',
        image: 'src/images/dongfeng-K33-561.png',
        seats: 2,
        year: 2023,
        color: 'Черный',
        transmission: 'Механика',
        fuel: 'Дизель',
        description: 'Специальный транспорт для перевозки крупногабаритных грузов.',
        price: 22000,
        available: true,
        orders: 19,
        stock: 5
    },
    {
        id: 12,
        name: 'Lincoln Town Car III',
        category: 'спецтранспорт',
        image: 'src/images/lincoln-town-car-iii.webp',
        seats: 8,
        year: 2002,
        color: 'Черный',
        transmission: 'Автомат',
        fuel: 'Бензин',
        description: 'Роскошный лимузин для VIP-церемоний. Максимальный комфорт.',
        price: 35000,
        available: true,
        orders: 31,
        stock: 7
    }
];

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
        const formattedPrice = vehicle.price.toLocaleString('ru-RU');

        // Получаем русское название категории
        let categoryName = '';
        switch(vehicle.category) {
            case 'катафалк': categoryName = 'Катафалк'; break;
            case 'автобус': categoryName = 'Автобус для гостей'; break;
            case 'легковой': categoryName = 'Легковой'; break;
            case 'спецтранспорт': categoryName = 'Спецтранспорт'; break;
            default: categoryName = vehicle.category;
        }

        card.innerHTML = `
            
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
            v.description.toLowerCase().includes(searchTerm) ||
            v.category.toLowerCase().includes(searchTerm)
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
        filtered = filtered.filter(v => v.category === category);
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
        case 'popular':
            filtered.sort((a, b) => (b.orders || 0) - (a.orders || 0));
            break;
        default:
            filtered.sort((a, b) => a.id - b.id);
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