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
let goodsData = [];

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
            case 1: categoryName = 'Гроб'; break;
            case 2: categoryName = 'Венок'; break;
            case 3: categoryName = 'Крест'; break;
            case 4: categoryName = 'Памятник'; break;
            case 5: categoryName = 'Одежда'; break;
            case 6: categoryName = 'Аксессуар'; break;
            default: categoryName = item.category;
        }

        card.innerHTML = `
            <div class="vehicle-image">
                <img src="../src/images/goods/${item.image_path}" alt="${item.name}" onerror="this.src='src/images/placeholder.jpg'">
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
                        ${item.total_stock} шт.
                    </span>
                </div>
                <p class="vehicle-description">${item.description_short}</p>
                <div class="vehicle-price">${formattedPrice} ₽</div>
                <div class="vehicle-actions">
                    <a href="goods_detail.php?id=${item.id}" class="btn-outline">Подробнее</a>
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
            item.description_short.toLowerCase().includes(searchTerm) ||
            (item.material && item.material.toLowerCase().includes(searchTerm))
        );
    }

    // Категория
    let category = 'all';
    if (categoryFilter && categoryFilter.value !== 'all') {
        category = categoryFilter.selectedIndex;
    } else if (categoryFilter_mobile && categoryFilter_mobile.value !== 'all') {
        category = categoryFilter_mobile.selectedIndex;
    }
    if (category !== 'all') {
        filtered = filtered.filter(item => item.category === category);
    }
    console.log(category);

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
        filtered = filtered.filter(item => (item.total_stock || 1) >= minStock);
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