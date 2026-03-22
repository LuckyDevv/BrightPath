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

// ===== ДАННЫЕ АГЕНТОВ =====
const agentsData = [
    {
        id: 1,
        name: 'Александр Шидловский',
        position: 'руководитель',
        image: '../src/images/agents/aleksandr_shidlovskiy.jpg',
        age: 20,
        experience: 2,
        events: 1800,
        description: 'Основатель агентства. Огромный опыт в организации похорон.',
        price: 5000,
        phone: '+7 (926) 111-22-33'
    },
    {
        id: 2,
        name: 'Елена Воронцова',
        position: 'ведущий агент',
        image: '../src/images/agents/elena_vorontsova.jpg',
        age: 45,
        experience: 18,
        events: 1200,
        description: 'Специалист по сложным случаям. Помощь в получении пособий и оформлении документов.',
        price: 4000,
        phone: '+7 (926) 222-33-44'
    },
    {
        id: 3,
        name: 'Дмитрий Волков',
        position: 'старший агент',
        image: '../src/images/agents/dmitriy_volkov.jpg',
        age: 38,
        experience: 12,
        events: 850,
        description: 'Ответственный за транспортировку и автопарк. Организация перевозок любой сложности.',
        price: 3500,
        phone: '+7 (926) 333-44-55'
    },
    {
        id: 4,
        name: 'Ольга Смирнова',
        position: 'агент',
        image: '../src/images/agents/olga_smirnova.jpg',
        age: 35,
        experience: 8,
        events: 600,
        description: 'Помощь в выборе ритуальных товаров, организация прощания. Внимательна к деталям.',
        price: 3000,
        phone: '+7 (926) 444-55-66'
    },
    {
        id: 5,
        name: 'Игорь Морозов',
        position: 'ведущий агент',
        image: '../src/images/agents/igor_morozov.jpg',
        age: 49,
        experience: 20,
        events: 1500,
        description: 'Эксперт по взаимодействию с госорганами. Быстрое оформление всех документов.',
        price: 4500,
        phone: '+7 (926) 555-66-77'
    },
    {
        id: 6,
        name: 'Наталья Кузнецова',
        position: 'агент',
        image: '../src/images/agents/natalya_kuznetsova.jpg',
        age: 32,
        experience: 7,
        events: 450,
        description: 'Психологическая поддержка семей. Помощь в организации поминальных обедов.',
        price: 2800,
        phone: '+7 (926) 666-77-88'
    },
    {
        id: 7,
        name: 'Сергей Лопаткин',
        position: 'менеджер',
        image: '../src/images/agents/sergey_lopatkin.jpg',
        age: 42,
        experience: 15,
        events: 950,
        description: 'Координация работы агентов, контроль качества услуг. Решение спорных вопросов.',
        price: 3800,
        phone: '+7 (926) 777-88-99'
    },
    {
        id: 8,
        name: 'Анна Соколова',
        position: 'старший агент',
        image: '../src/images/agents/anna-sokolova.jpg',
        age: 41,
        experience: 16,
        events: 1100,
        description: 'Специалист по кремации и захоронению урн. Знает все тонкости процесса.',
        price: 4200,
        phone: '+7 (926) 888-99-00'
    },
    {
        id: 9,
        name: 'Михаил Федоров',
        position: 'агент',
        image: '../src/images/agents/mihail_fedorov.jpg',
        age: 29,
        experience: 5,
        events: 320,
        description: 'Молодой специалист. Отвечает за взаимодействие с кладбищами и крематорием.',
        price: 2500,
        phone: '+7 (926) 999-00-11'
    },
    {
        id: 10,
        name: 'Татьяна Павлова',
        position: 'агент',
        image: '../src/images/agents/tatyana_pavlovna.jpg',
        age: 21,
        experience: 2,
        events: 700,
        description: 'Помощь в подготовке некрологов, текстов прощания. Организация гражданских панихид.',
        price: 3200,
        phone: '+7 (926) 000-11-22'
    }
];

// ===== ЭЛЕМЕНТЫ DOM =====
const vehiclesGrid = document.getElementById('vehiclesGrid');
const searchInput = document.getElementById('searchInput');
const mobileSearchInput = document.getElementById('mobileSearchInput');
const positionFilter = document.getElementById('positionFilter');
const experienceFilter = document.getElementById('experienceFilter');
const ageFilter = document.getElementById('ageFilter');
const minPrice = document.getElementById('minPrice');
const maxPrice = document.getElementById('maxPrice');
const sortBy = document.getElementById('sortBy');
const resetBtn = document.getElementById('resetFilters');
const resetBtn_mobile = document.getElementById('resetFilters_mobile');
const noResults = document.getElementById('noResults');
// МОБИЛЬНЫЕ ЭЛЕМЕНТЫ
const sortBy_mobile = document.getElementById('sortBy_mobile');
const maxPrice_mobile = document.getElementById('maxPrice_mobile');
const minPrice_mobile = document.getElementById('minPrice_mobile');
const positionFilter_mobile = document.getElementById('positionFilter_mobile');
const experienceFilter_mobile = document.getElementById('experienceFilter_mobile');
const ageFilter_mobile = document.getElementById('ageFilter_mobile');

// ===== СИНХРОНИЗАЦИЯ ПОИСКА =====
if (mobileSearchInput && searchInput) {
    mobileSearchInput.addEventListener('input', (e) => {
        searchInput.value = e.target.value;
        filterAgents();
    });

    searchInput.addEventListener('input', (e) => {
        mobileSearchInput.value = e.target.value;
        filterAgents();
    });
} else if (searchInput) {
    searchInput.addEventListener('input', filterAgents);
}

// ===== РЕНДЕРИНГ КАРТОЧЕК =====
function renderAgents(agents) {
    vehiclesGrid.innerHTML = '';

    if (agents.length === 0) {
        noResults.style.display = 'block';
        vehiclesGrid.style.display = 'none';
        return;
    }

    noResults.style.display = 'none';
    vehiclesGrid.style.display = 'grid';

    agents.forEach(agent => {
        const card = document.createElement('div');
        card.className = 'vehicle-card';

        const formattedPrice = agent.price.toLocaleString('ru-RU');

        card.innerHTML = `
            <div class="vehicle-image">
                <img src="${agent.image}" alt="${agent.name}" onerror="this.src='src/images/placeholder.jpg'">
            </div>
            <div class="vehicle-content">
                <h3 class="vehicle-name">${agent.name}</h3>
                <div class="vehicle-position">${agent.position}</div>
                <div class="vehicle-details">
                    <span class="vehicle-detail">
                        <svg viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="1.5" fill="none"/>
                            <path d="M12 6v6l4 2" stroke="currentColor" stroke-width="1.5" fill="none"/>
                        </svg>
                        ${agent.age} лет
                    </span>
                    <span class="vehicle-detail">
                        <svg viewBox="0 0 24 24">
                            <rect x="2" y="7" width="20" height="12" rx="2" stroke="currentColor" fill="none" stroke-width="1.5"/>
                            <circle cx="7" cy="17" r="2" stroke="currentColor" fill="none" stroke-width="1.5"/>
                            <circle cx="17" cy="17" r="2" stroke="currentColor" fill="none" stroke-width="1.5"/>
                        </svg>
                        Стаж ${agent.experience} лет
                    </span>
                </div>
                <div class="vehicle-stats">
                    <div class="vehicle-stat">
                        <div class="vehicle-stat-value">${agent.events}+</div>
                        <div class="vehicle-stat-label">мероприятий</div>
                    </div>
                </div>
                <p class="vehicle-description">${agent.description}</p>
                <div class="vehicle-price">от ${formattedPrice} ₽ <small>за услугу</small></div>
                <div class="vehicle-actions">
                    <a href="#" class="btn-outline">Подробнее</a>
                    <a href="tel:${agent.phone}" class="btn">Позвонить</a>
                </div>
            </div>
        `;

        vehiclesGrid.appendChild(card);
    });
}

// ===== ФИЛЬТРАЦИЯ =====
function filterAgents() {
    let filtered = [...agentsData];

    // Поиск
    const searchTerm = searchInput ? searchInput.value.toLowerCase().trim() : '';
    if (searchTerm) {
        filtered = filtered.filter(agent =>
            agent.name.toLowerCase().includes(searchTerm) ||
            agent.position.toLowerCase().includes(searchTerm) ||
            agent.description.toLowerCase().includes(searchTerm)
        );
    }

    // Должность
    let position = 'all';
    if (positionFilter && positionFilter.value !== 'all') {
        position = positionFilter.value;
    } else if (positionFilter_mobile && positionFilter_mobile.value !== 'all') {
        position = positionFilter_mobile.value;
    }
    if (position !== 'all') {
        filtered = filtered.filter(agent => agent.position === position);
    }

    // Стаж
    let experience = 'all';
    if (experienceFilter && experienceFilter.value !== 'all') {
        experience = experienceFilter.value;
    } else if (experienceFilter_mobile && experienceFilter_mobile.value !== 'all') {
        experience = experienceFilter_mobile.value;
    }
    if (experience !== 'all') {
        const minExp = parseInt(experience);
        filtered = filtered.filter(agent => agent.experience >= minExp);
    }

    // Возраст
    let age = 'all';
    if (ageFilter && ageFilter.value !== 'all') {
        age = ageFilter.value;
    } else if (ageFilter_mobile && ageFilter_mobile.value !== 'all') {
        age = ageFilter_mobile.value;
    }
    if (age !== 'all') {
        const maxAge = parseInt(age);
        filtered = filtered.filter(agent => agent.age <= maxAge);
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
    filtered = filtered.filter(agent => agent.price >= min && agent.price <= max);

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
        case 'experienceDesc':
            filtered.sort((a, b) => b.experience - a.experience);
            break;
        case 'ageAsc':
            filtered.sort((a, b) => a.age - b.age);
            break;
        default:
            filtered.sort((a, b) => a.id - b.id);
    }

    renderAgents(filtered);
}

// ===== СБРОС ФИЛЬТРОВ =====
function resetFilters() {
    if (searchInput) searchInput.value = '';
    if (mobileSearchInput) mobileSearchInput.value = '';
    if (positionFilter) positionFilter.value = 'all';
    if (experienceFilter) experienceFilter.value = 'all';
    if (ageFilter) ageFilter.value = 'all';
    if (minPrice) minPrice.value = '';
    if (maxPrice) maxPrice.value = '';
    if (sortBy) sortBy.value = 'default';

    if (positionFilter_mobile) positionFilter_mobile.value = 'all';
    if (experienceFilter_mobile) experienceFilter_mobile.value = 'all';
    if (ageFilter_mobile) ageFilter_mobile.value = 'all';
    if (minPrice_mobile) minPrice_mobile.value = '';
    if (maxPrice_mobile) maxPrice_mobile.value = '';
    if (sortBy_mobile) sortBy_mobile.value = 'default';

    filterAgents();
}

// ===== СЛУШАТЕЛИ =====
if (positionFilter) positionFilter.addEventListener('change', filterAgents);
if (positionFilter_mobile) positionFilter_mobile.addEventListener('change', filterAgents);
if (experienceFilter) experienceFilter.addEventListener('change', filterAgents);
if (experienceFilter_mobile) experienceFilter_mobile.addEventListener('change', filterAgents);
if (ageFilter) ageFilter.addEventListener('change', filterAgents);
if (ageFilter_mobile) ageFilter_mobile.addEventListener('change', filterAgents);
if (minPrice) minPrice.addEventListener('input', filterAgents);
if (maxPrice) maxPrice.addEventListener('input', filterAgents);
if (minPrice_mobile) minPrice_mobile.addEventListener('input', filterAgents);
if (maxPrice_mobile) maxPrice_mobile.addEventListener('input', filterAgents);
if (sortBy) sortBy.addEventListener('change', filterAgents);
if (sortBy_mobile) sortBy_mobile.addEventListener('change', filterAgents);
if (resetBtn) resetBtn.addEventListener('click', resetFilters);
if (resetBtn_mobile) resetBtn_mobile.addEventListener('click', resetFilters);

// ===== ИНИЦИАЛИЗАЦИЯ =====
document.addEventListener('DOMContentLoaded', () => {
    renderAgents(agentsData);
});