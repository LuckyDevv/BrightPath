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
let agentsData;

// ===== ЭЛЕМЕНТЫ DOM =====
const vehiclesGrid = document.getElementById('vehiclesGrid');
const searchInput = document.getElementById('searchInput');
const mobileSearchInput = document.getElementById('mobileSearchInput');
const positionFilter = document.getElementById('positionFilter');
const experienceFilter = document.getElementById('experienceFilter');
const ageFilter = document.getElementById('ageFilter');
const sortBy = document.getElementById('sortBy');
const resetBtn = document.getElementById('resetFilters');
const resetBtn_mobile = document.getElementById('resetFilters_mobile');
const noResults = document.getElementById('noResults');
// МОБИЛЬНЫЕ ЭЛЕМЕНТЫ
const sortBy_mobile = document.getElementById('sortBy_mobile');
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


function plural(number, titles) {
    let cases = [2, 0, 1, 1, 1, 2];
    return titles[ (number%100>4 && number%100<20)? 2 : cases[(number%10<5)?number%10:5] ];
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

        let declension = ['год', 'года', 'лет'];
        let age = `${agent.age} ${plural(agent.age, declension)}`;
        let experience = `${agent.experience} ${plural(agent.experience, declension)}`;
        let position = {
            1: "Руководитель",
            2: "Ведущий агент",
            3: "Старший агент",
            4: "Менеджер",
            5: "Агент"
        }
        position = position[agent.position]
        card.innerHTML = `
            <div class="vehicle-image">
                <img src="../src/images/agents/${agent.image}" alt="${agent.name}" onerror="this.src='src/images/placeholder.jpg'">
            </div>
            <div class="vehicle-content">
                <h3 class="vehicle-name">${agent.name}</h3>
                <div class="vehicle-position">${position}</div>
                <div class="vehicle-details">
                    <span class="vehicle-detail">
                        <svg viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="1.5" fill="none"/>
                            <path d="M12 6v6l4 2" stroke="currentColor" stroke-width="1.5" fill="none"/>
                        </svg>
                        ${age}
                    </span>
                    <span class="vehicle-detail">
                        <svg viewBox="0 0 24 24">
                            <rect x="2" y="7" width="20" height="12" rx="2" stroke="currentColor" fill="none" stroke-width="1.5"/>
                            <circle cx="7" cy="17" r="2" stroke="currentColor" fill="none" stroke-width="1.5"/>
                            <circle cx="17" cy="17" r="2" stroke="currentColor" fill="none" stroke-width="1.5"/>
                        </svg>
                        Стаж ${experience}
                    </span>
                </div>
                <div class="vehicle-stats">
                    <div class="vehicle-stat">
                        <div class="vehicle-stat-value">${agent.events_count}</div>
                        <div class="vehicle-stat-label">мероприятий</div>
                    </div>
                </div>
                <p class="vehicle-description">${agent.description}</p>
                <div class="vehicle-actions">
                    <a href="agent.php?id=${agent.id}" class="btn-outline">Подробнее</a>
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


    // Сортировка
    let sort = 'default';
    if (sortBy && sortBy.value !== 'default') {
        sort = sortBy.value;
    } else if (sortBy_mobile && sortBy_mobile.value !== 'default') {
        sort = sortBy_mobile.value;
    }

    switch(sort) {
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
    if (sortBy) sortBy.value = 'default';

    if (positionFilter_mobile) positionFilter_mobile.value = 'all';
    if (experienceFilter_mobile) experienceFilter_mobile.value = 'all';
    if (ageFilter_mobile) ageFilter_mobile.value = 'all';
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
if (sortBy) sortBy.addEventListener('change', filterAgents);
if (sortBy_mobile) sortBy_mobile.addEventListener('change', filterAgents);
if (resetBtn) resetBtn.addEventListener('click', resetFilters);
if (resetBtn_mobile) resetBtn_mobile.addEventListener('click', resetFilters);

// ===== ИНИЦИАЛИЗАЦИЯ =====
document.addEventListener('DOMContentLoaded', () => {
    renderAgents(agentsData);
});