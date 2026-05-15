// ===== ГЛОБАЛЬНЫЕ ПЕРЕМЕННЫЕ =====
let presetsData = [];
let originalContactsData = {};

// ===== ИНИЦИАЛИЗАЦИЯ СТРАНИЦЫ =====
function initMoreSettings() {
    loadPresets();
    saveOriginalContacts();

    // Контакты
    document.getElementById('saveContactsBtn')?.addEventListener('click', saveContacts);
    document.getElementById('cancelContactsBtn')?.addEventListener('click', cancelContacts);

    // Пресеты
    document.getElementById('addPresetBtn')?.addEventListener('click', () => openPresetModal(null));
    document.getElementById('presetForm')?.addEventListener('submit', savePreset);
}

// ===== КОНТАКТЫ =====
function saveOriginalContacts() {
    originalContactsData = {
        phone: document.getElementById('contact_phone')?.value || '',
        email: document.getElementById('contact_email')?.value || '',
        address: document.getElementById('contact_address')?.value || '',
        schedule: document.getElementById('contact_schedule')?.value || '',
        telegram: document.getElementById('contact_telegram')?.value || '',
        vk: document.getElementById('contact_vk')?.value || '',
        max: document.getElementById('contact_max')?.value || ''
    };
}

async function saveContacts() {
    const newData = {
        phone: document.getElementById('contact_phone')?.value || '',
        email: document.getElementById('contact_email')?.value || '',
        address: document.getElementById('contact_address')?.value || '',
        schedule: document.getElementById('contact_schedule')?.value || '',
        telegram: document.getElementById('contact_telegram')?.value || '',
        vk: document.getElementById('contact_vk')?.value || '',
        max: document.getElementById('contact_max')?.value || ''
    };

    const sessionId = getCookie('session_id');
    const fingerprint = await collectFingerPrint();

    $.post("../../server/post/adminSettingsHandler.php", {
        "type": "changeContacts",
        "contactsData": newData,
        "session_id": sessionId,
        "fingerprint": fingerprint
    }, function (data) {
        try {
            var response = JSON.parse(data);
        } catch(e) {
            Toast.error("Не удалось связаться с сервером.");
            return;
        }
        if (response.response) {
            originalContactsData = {...newData};
            Toast.success('Контакты сохранены');
        } else if (response.error) {
            Toast.error(`Ошибка [${response.error.code}]: ${response.error.message}`);
        } else {
            Toast.error("Не удалось связаться с сервером.");
        }
    });
}

function cancelContacts() {
    document.getElementById('contact_phone').value = originalContactsData.phone;
    document.getElementById('contact_email').value = originalContactsData.email;
    document.getElementById('contact_address').value = originalContactsData.address;
    document.getElementById('contact_schedule').value = originalContactsData.schedule;
    document.getElementById('contact_telegram').value = originalContactsData.telegram;
    document.getElementById('contact_vk').value = originalContactsData.vk;
    document.getElementById('contact_max').value = originalContactsData.max;
    Toast.success('Изменения отменены');
}

// ===== ПРЕСЕТЫ =====
function loadPresets() {
    const grid = document.getElementById('presetsGrid');
    if (!grid) return;

    $.post("../../server/post/adminSettingsHandler.php", {
        "type": "getAllPresets"
    }, function (data) {
        try {
            var response = JSON.parse(data);
        } catch(e) {
            Toast.error("Не удалось загрузить пресеты!");
            return;
        }

        if (response.response && response.response.data) {
            // Парсим JSON-строки в объекты
            presetsData = response.response.data.map(function(preset) {
                return {
                    ...preset,
                    transport: typeof preset.transport === 'string'
                        ? JSON.parse(preset.transport)
                        : preset.transport,
                    goods: typeof preset.goods === 'string'
                        ? JSON.parse(preset.goods)
                        : preset.goods,
                    services: typeof preset.services === 'string'
                        ? JSON.parse(preset.services)
                        : preset.services
                };
            });

            renderPresets();
        } else {
            Toast.error("Не удалось загрузить пресеты!");
        }
    });
}

function renderPresets() {
    const grid = document.getElementById('presetsGrid');
    if (!grid) return;

    grid.innerHTML = '';

    presetsData.forEach(preset => {
        const card = document.createElement('div');
        card.className = `preset-card ${preset.decoration}`;

        const decorationBadge = preset.decoration_quote
            ? `<div class="preset-badge ${preset.decoration}">${escapeHtml(preset.decoration_quote)}</div>`
            : '';

        // Формируем списки
        const transportNames = preset.transport.map(t => t.name).join(', ');
        const goodsNames = preset.goods.map(g => g.name).join(', ');
        const servicesNames = preset.services.map(s => s.name).join(', ');

        card.innerHTML = `
            <div class="preset-header">
                <div class="preset-name">${escapeHtml(preset.name)}</div>
                <div class="preset-price">${preset.price.toLocaleString()} ₽</div>
                <div class="preset-quote">${escapeHtml(preset.quote)}</div>
                ${decorationBadge}
            </div>
            <div class="preset-body">
                ${transportNames ? `
                <div class="preset-section">
                    <div class="preset-section-title">🚗 Транспорт</div>
                    <ul class="preset-items"><li>${escapeHtml(transportNames)}</li></ul>
                </div>
                ` : ''}
                ${goodsNames ? `
                <div class="preset-section">
                    <div class="preset-section-title">📦 Товары</div>
                    <ul class="preset-items"><li>${escapeHtml(goodsNames)}</li></ul>
                </div>
                ` : ''}
                ${servicesNames ? `
                <div class="preset-section">
                    <div class="preset-section-title">🪦 Услуги</div>
                    <ul class="preset-items"><li>${escapeHtml(servicesNames)}</li></ul>
                </div>
                ` : ''}
            </div>
            <div class="preset-footer">
                <button class="btn-outline" onclick="editPreset(${preset.id})">Редактировать</button>
                <button class="btn-outline" onclick="deletePreset(${preset.id})">Удалить</button>
            </div>
        `;
        grid.appendChild(card);
    });
}

// ===== РАБОТА С МУЛЬТИСЕЛЕКТАМИ (PHP уже отрендерил чекбоксы) =====
function getSelectedFromMultiselect(containerId) {
    const container = document.getElementById(containerId);
    if (!container) return [];

    const selected = [];
    const checkboxes = container.querySelectorAll('input[type="checkbox"]:checked');

    checkboxes.forEach(cb => {
        selected.push({
            id: parseInt(cb.value),
            name: cb.getAttribute('data-name'),
            price: parseFloat(cb.getAttribute('data-price')) || 0
        });
    });

    return selected;
}

function setSelectedFromMultiselect(containerId, selectedIds) {
    const container = document.getElementById(containerId);
    if (!container) return;

    const checkboxes = container.querySelectorAll('input[type="checkbox"]');
    checkboxes.forEach(cb => {
        if (selectedIds.includes(cb.value) || selectedIds.includes(parseInt(cb.value))) {
            cb.checked = true;
        }
    });
}

// ===== ОТКРЫТИЕ МОДАЛЬНОГО ОКНА ПРЕСЕТА =====
function openPresetModal(preset) {
    const modal = document.getElementById('presetModal');
    const title = document.getElementById('presetModalTitle');

    // Сброс формы
    document.getElementById('presetForm').reset();
    document.getElementById('preset_decoration').value = 'default';
    document.getElementById('preset_is_active').value = 1;

    // Снимаем все выделения в мультиселектах
    setSelectedFromMultiselect('transportMultiselect', []);
    setSelectedFromMultiselect('goodsMultiselect', []);
    setSelectedFromMultiselect('servicesMultiselect', []);

    if (preset) {
        title.textContent = 'Редактирование пресета';
        document.getElementById('preset_name').value = preset.name;
        document.getElementById('preset_quote').value = preset.quote || '';
        document.getElementById('preset_decoration').value = preset.decoration || 'default';
        document.getElementById('preset_decoration_quote').value = preset.decoration_quote || '';
        document.getElementById('preset_is_active').value = preset.is_active;

        // Отмечаем выбранные элементы
        const transportIds = (preset.transport || []).map(t => t.id);
        const goodsIds = (preset.goods || []).map(g => g.id);
        const servicesIds = (preset.services || []).map(s => s.id);

        console.log(transportIds);
        console.log(goodsIds);
        console.log(servicesIds);

        setSelectedFromMultiselect('transportMultiselect', transportIds);
        setSelectedFromMultiselect('goodsMultiselect', goodsIds);
        setSelectedFromMultiselect('servicesMultiselect', servicesIds);

        window.currentPresetId = preset.id;
    } else {
        title.textContent = 'Добавление пресета';
        window.currentPresetId = null;
    }

    modal.classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closePresetModal() {
    const modal = document.getElementById('presetModal');
    modal.classList.remove('active');
    document.body.style.overflow = 'auto';
}

function editPreset(id) {
    const preset = presetsData.find(p => p.id === id);
    if (preset) openPresetModal(preset);
}

// ===== СОХРАНЕНИЕ ПРЕСЕТА =====
async function savePreset(e) {
    e.preventDefault();

    const preset = {
        name: document.getElementById('preset_name').value,
        quote: document.getElementById('preset_quote').value,
        decoration: document.getElementById('preset_decoration').value,
        decoration_quote: document.getElementById('preset_decoration_quote').value || null,
        transport: getSelectedFromMultiselect('transportMultiselect'),
        goods: getSelectedFromMultiselect('goodsMultiselect'),
        services: getSelectedFromMultiselect('servicesMultiselect'),
        is_active: parseInt(document.getElementById('preset_is_active').value)
    };

    // Валидация
    if (!preset.name.trim()) {
        Toast.error('Введите название пресета');
        return;
    }
    if (preset.transport.length === 0 && preset.goods.length === 0 && preset.services.length === 0) {
        Toast.error('Добавьте хотя бы один транспорт, товар или услугу');
        return;
    }

    const sessionId = getCookie('session_id');
    const fingerprint = await collectFingerPrint();

    $.post("../../server/post/adminSettingsHandler.php", {
        "type": window.currentPresetId ? "editPreset" : "addPreset",
        "presetData": preset,
        "presetId": window.currentPresetId,
        "session_id": sessionId,
        "fingerprint": fingerprint
    }, function(data) {
        try {
            var response = JSON.parse(data);
        } catch(e) {
            Toast.error("Не удалось связаться с сервером");
            return;
        }

        if (response.response) {
            Toast.success(window.currentPresetId ? 'Пресет обновлён' : 'Пресет добавлен');
            closePresetModal();
            loadPresets();
        } else if (response.error) {
            Toast.error(`Ошибка [${response.error.code}]: ${response.error.message}`);
        } else {
            Toast.error("Не удалось сохранить пресет");
        }
    }).fail(function() {
        Toast.error("Ошибка соединения с сервером");
    });
}

// ===== УДАЛЕНИЕ ПРЕСЕТА =====
async function deletePreset(id) {
    if (!confirm('Удалить пресет?')) return;

    const sessionId = getCookie('session_id');
    const fingerprint = await collectFingerPrint();

    $.post("../../server/post/adminSettingsHandler.php", {
        "type": "deletePreset",
        "presetId": id,
        "session_id": sessionId,
        "fingerprint": fingerprint
    }, function(data) {
        try {
            var response = JSON.parse(data);
        } catch(e) {
            Toast.error("Не удалось связаться с сервером");
            return;
        }

        if (response.response) {
            Toast.success('Пресет удалён');
            loadPresets();
        } else if (response.error) {
            Toast.error(`Ошибка [${response.error.code}]: ${response.error.message}`);
        } else {
            Toast.error("Не удалось удалить пресет");
        }
    });
}

// ===== ВСПОМОГАТЕЛЬНЫЕ ФУНКЦИИ =====
function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}
