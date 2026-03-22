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

// ===== ДАННЫЕ ТОВАРОВ =====
const goodsData = [
    {
        id: 1,
        name: 'Гроб сосновый',
        category: 'гроб',
        material: 'сосна',
        image: '../src/images/goods/grob_sosnoviy.jpg',
        description: 'Классический гроб из массива сосны. Обивка тканью, крест в комплекте.',
        price: 8900,
        orders: 156,
        stock: 25
    },
    {
        id: 2,
        name: 'Гроб дубовый',
        category: 'гроб',
        material: 'дуб',
        image: '../src/images/goods/grob_duboviy.jpg',
        description: 'Премиальный гроб из массива дуба. Ручная работа, полировка, бархатная обивка.',
        price: 35000,
        orders: 89,
        stock: 8
    },
    {
        id: 3,
        name: 'Гроб красное дерево',
        category: 'гроб',
        material: 'красное дерево',
        image: '../src/images/goods/grob_krasnoye_derevo.jpg',
        description: 'Элитный гроб из красного дерева с инкрустацией. Для особых церемоний.',
        price: 65000,
        orders: 23,
        stock: 3
    },
    {
        id: 4,
        name: 'Венок траурный',
        category: 'венок',
        material: 'искусственные цветы',
        image: '../src/images/goods/venok.jpg',
        description: 'Траурный венок из искусственных цветов. Диаметр 60 см.',
        price: 3500,
        orders: 210,
        stock: 45
    },
    {
        id: 5,
        name: 'Крест деревянный',
        category: 'крест',
        material: 'дуб',
        image: '../src/images/goods/krest_2.jpg',
        description: 'Православный крест из дуба. Высота 120 см.',
        price: 2800,
        orders: 178,
        stock: 32
    },
    {
        id: 6,
        name: 'Памятник гранит',
        category: 'памятник',
        material: 'гранит',
        image: '../src/images/goods/pamyatnik_granit.jpg',
        description: 'Гранитный памятник с гравировкой. Различные варианты оформления.',
        price: 45000,
        orders: 67,
        stock: 12
    },
    {
        id: 7,
        name: 'Одежда для погребения',
        category: 'одежда',
        material: 'ткань',
        image: '../src/images/goods/odezhda.jpg',
        description: 'Комплект одежды для погребения. Размеры от 46 до 60.',
        price: 4200,
        orders: 145,
        stock: 38
    },
    {
        id: 8,
        name: 'Подушка ритуальная',
        category: 'аксессуары',
        material: 'ткань',
        image: '../src/images/goods/podushka.jpg',
        description: 'Ритуальная подушка под голову. Белая, с вышивкой.',
        price: 1200,
        orders: 203,
        stock: 67
    },
    {
        id: 9,
        name: 'Венок живой',
        category: 'венок',
        material: 'живые цветы',
        image: '../src/images/goods/vebok_2.jpg',
        description: 'Венок из живых цветов (розы, хризантемы). Доставка в день заказа.',
        price: 8500,
        orders: 56,
        stock: 7
    },
    {
        id: 10,
        name: 'Крест металлический',
        category: 'крест',
        material: 'металл',
        image: '../src/images/goods/krest_metall.jpg',
        description: 'Кованый металлический крест. Покрытие черное матовое.',
        price: 5400,
        orders: 92,
        stock: 15
    },
    {
        id: 11,
        name: 'Памятник мрамор',
        category: 'памятник',
        material: 'мрамор',
        image: '../src/images/goods/pamyatnik_mramor.jpg',
        description: 'Мраморный памятник. Элитная отделка, возможна цветная гравировка.',
        price: 78000,
        orders: 31,
        stock: 4
    },
    {
        id: 12,
        name: 'Лампада ритуальная',
        category: 'аксессуары',
        material: 'стекло',
        image: '../src/images/goods/lampada.jpg',
        description: 'Стеклянная лампада для свечей. Высота 20 см.',
        price: 850,
        orders: 187,
        stock: 52
    }
];

// ===== ЭЛЕМЕНТЫ DOM =====
const vehiclesGrid = document.getElementById('vehiclesGrid');
const searchInput = document.getElementById('searchInput');
const mobileSearchInput = document.getElementById('mobileSearchInput');
const categoryFilter = document.getElementById('categoryFilter');
const minPrice = document.getElementById('minPrice');
const maxPrice = document.getElementById('maxPrice');
const materialFilter = document.getElementById('materialFilter');
const sortBy = document.getElementById('sortBy');
const resetBtn = document.getElementById('resetFilters');
const resetBtn_mobile = document.getElementById('resetFilters_mobile');
const noResults = document.getElementById('noResults');
const stockFilter = document.getElementById('stockFilter');
// МОБИЛЬНЫЕ ЭЛЕМЕНТЫ
const sortBy_mobile = document.getElementById('sortBy_mobile');
const stockFilter_mobile = document.getElementById('stockFilter_mobile');
const maxPrice_mobile = document.getElementById('maxPrice_mobile');
const minPrice_mobile = document.getElementById('minPrice_mobile');
const categoryFilter_mobile = document.getElementById('categoryFilter_mobile');
const materialFilter_mobile = document.getElementById('materialFilter_mobile');

// ===== СИНХРОНИЗАЦИЯ ПОИСКА =====
if (mobileSearchInput && searchInput) {
    mobileSearchInput.addEventListener('input', (e) => {
        searchInput.value = e.target.value;
        filterGoods();
    });

    searchInput.addEventListener('input', (e) => {
        mobileSearchInput.value = e.target.value;
        filterGoods();
    });
} else if (searchInput) {
    searchInput.addEventListener('input', filterGoods);
}

// ===== РЕНДЕРИНГ КАРТОЧЕК =====
function renderGoods(goods) {
    vehiclesGrid.innerHTML = '';

    if (goods.length === 0) {
        noResults.style.display = 'block';
        vehiclesGrid.style.display = 'none';
        return;
    }

    noResults.style.display = 'none';
    vehiclesGrid.style.display = 'grid';

    goods.forEach(item => {
        const card = document.createElement('div');
        card.className = 'vehicle-card';

        const formattedPrice = item.price.toLocaleString('ru-RU');

        let categoryName = '';
        switch(item.category) {
            case 'гроб': categoryName = 'Гроб'; break;
            case 'венок': categoryName = 'Венок'; break;
            case 'крест': categoryName = 'Крест'; break;
            case 'памятник': categoryName = 'Памятник'; break;
            case 'одежда': categoryName = 'Одежда'; break;
            case 'аксессуары': categoryName = 'Аксессуар'; break;
            default: categoryName = item.category;
        }

        card.innerHTML = `
            <div class="vehicle-image">
                <img src="${item.image}" alt="${item.name}" onerror="this.src='src/images/placeholder.jpg'">
            </div>
            <div class="vehicle-content">
                <span class="vehicle-category">${categoryName}</span>
                <h3 class="vehicle-name">${item.name}</h3>
                <div class="vehicle-details">
                    <span class="vehicle-detail">
                        <svg viewBox="0 0 24 24" width="16" height="16">
                            <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="1.5" fill="none"/>
                            <path d="M12 6v6l4 2" stroke="currentColor" stroke-width="1.5" fill="none"/>
                        </svg>
                        ${item.material}
                    </span>
                    <span class="vehicle-detail">
                        <svg viewBox="0 0 24 24" width="16" height="16">
                            <rect x="2" y="7" width="20" height="12" rx="2" stroke="currentColor" fill="none" stroke-width="1.5"/>
                            <circle cx="7" cy="17" r="2" stroke="currentColor" fill="none" stroke-width="1.5"/>
                            <circle cx="17" cy="17" r="2" stroke="currentColor" fill="none" stroke-width="1.5"/>
                            <text x="12" y="16" text-anchor="middle" fill="#d4a373" font-size="8" font-weight="bold">${item.stock}</text>
                        </svg>
                        ${item.stock} шт.
                    </span>
                </div>
                <p class="vehicle-description">${item.description}</p>
                <div class="vehicle-price">от ${formattedPrice} ₽</div>
                <div class="vehicle-actions">
                    <a href="#" class="btn-outline">Подробнее</a>
                    <a href="#" class="btn">В корзину</a>
                </div>
            </div>
        `;

        vehiclesGrid.appendChild(card);
    });
}

// ===== ФИЛЬТРАЦИЯ =====
function filterGoods() {
    let filtered = [...goodsData];

    // Поиск
    const searchTerm = searchInput ? searchInput.value.toLowerCase().trim() : '';
    if (searchTerm) {
        filtered = filtered.filter(item =>
            item.name.toLowerCase().includes(searchTerm) ||
            item.description.toLowerCase().includes(searchTerm) ||
            item.category.toLowerCase().includes(searchTerm) ||
            (item.material && item.material.toLowerCase().includes(searchTerm))
        );
    }

    // Категория
    let category = 'all';
    if (categoryFilter && categoryFilter.value !== 'all') {
        category = categoryFilter.value;
    } else if (categoryFilter_mobile && categoryFilter_mobile.value !== 'all') {
        category = categoryFilter_mobile.value;
    }
    if (category !== 'all') {
        filtered = filtered.filter(item => item.category === category);
    }

    // Цена
    let min = 0;
    let max = Infinity;
    if (minPrice && minPrice.value) min = parseInt(minPrice.value);
    if (maxPrice && maxPrice.value) max = parseInt(maxPrice.value);
    if ((!minPrice || !minPrice.value) && minPrice_mobile && minPrice_mobile.value) {
        min = parseInt(minPrice_mobile.value);
    }
    if ((!maxPrice || !maxPrice.value) && maxPrice_mobile && maxPrice_mobile.value) {
        max = parseInt(maxPrice_mobile.value);
    }
    filtered = filtered.filter(item => item.price >= min && item.price <= max);

    // Материал
    let material = 'all';
    if (materialFilter && materialFilter.value !== 'all') {
        material = materialFilter.value;
    } else if (materialFilter_mobile && materialFilter_mobile.value !== 'all') {
        material = materialFilter_mobile.value;
    }
    if (material !== 'all') {
        filtered = filtered.filter(item => item.material && item.material.includes(material));
    }

    // Наличие
    let stockValue = 'all';
    if (stockFilter && stockFilter.value !== 'all') {
        stockValue = stockFilter.value;
    } else if (stockFilter_mobile && stockFilter_mobile.value !== 'all') {
        stockValue = stockFilter_mobile.value;
    }
    if (stockValue !== 'all') {
        const minStock = parseInt(stockValue);
        filtered = filtered.filter(item => (item.stock || 1) >= minStock);
    }

    // Сортировка
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
        case 'popular':
            filtered.sort((a, b) => (b.orders || 0) - (a.orders || 0));
            break;
        default:
            filtered.sort((a, b) => a.id - b.id);
    }

    renderGoods(filtered);
}

// ===== СБРОС ФИЛЬТРОВ =====
function resetFilters() {
    if (searchInput) searchInput.value = '';
    if (mobileSearchInput) mobileSearchInput.value = '';
    if (categoryFilter) categoryFilter.value = 'all';
    if (minPrice) minPrice.value = '';
    if (maxPrice) maxPrice.value = '';
    if (materialFilter) materialFilter.value = 'all';
    if (stockFilter) stockFilter.value = 'all';
    if (sortBy) sortBy.value = 'default';

    if (categoryFilter_mobile) categoryFilter_mobile.value = 'all';
    if (minPrice_mobile) minPrice_mobile.value = '';
    if (maxPrice_mobile) maxPrice_mobile.value = '';
    if (materialFilter_mobile) materialFilter_mobile.value = 'all';
    if (stockFilter_mobile) stockFilter_mobile.value = 'all';
    if (sortBy_mobile) sortBy_mobile.value = 'default';

    filterGoods();
}

// ===== СЛУШАТЕЛИ =====
if (categoryFilter) categoryFilter.addEventListener('change', filterGoods);
if (categoryFilter_mobile) categoryFilter_mobile.addEventListener('change', filterGoods);
if (minPrice) minPrice.addEventListener('input', filterGoods);
if (maxPrice) maxPrice.addEventListener('input', filterGoods);
if (minPrice_mobile) minPrice_mobile.addEventListener('input', filterGoods);
if (maxPrice_mobile) maxPrice_mobile.addEventListener('input', filterGoods);
if (materialFilter) materialFilter.addEventListener('change', filterGoods);
if (materialFilter_mobile) materialFilter_mobile.addEventListener('change', filterGoods);
if (stockFilter) stockFilter.addEventListener('change', filterGoods);
if (stockFilter_mobile) stockFilter_mobile.addEventListener('change', filterGoods);
if (sortBy) sortBy.addEventListener('change', filterGoods);
if (sortBy_mobile) sortBy_mobile.addEventListener('change', filterGoods);
if (resetBtn) resetBtn.addEventListener('click', resetFilters);
if (resetBtn_mobile) resetBtn_mobile.addEventListener('click', resetFilters);

// ===== ИНИЦИАЛИЗАЦИЯ =====
document.addEventListener('DOMContentLoaded', () => {
    renderGoods(goodsData);
});