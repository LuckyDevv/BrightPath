// ===== ПЕРЕМЕННЫЕ ДЛЯ МОДАЛЬНОГО ОКНА АГЕНТОВ =====
let agt_modal_elements = {
    agt_modal_title: null,
    agt_modal_name: null,
    agt_modal_surname: null,
    agt_modal_patronymic: null,
    agt_modal_position: null,
    agt_modal_option_boss: null,
    agt_modal_option_staff: null,
    agt_modal_option_staff2: null,
    agt_modal_option_manager: null,
    agt_modal_option_agent: null,
    agt_modal_main_photo: null,
    agt_modal_birthdate: null,
    agt_modal_dsc_short: null,
    agt_modal_biography: null,
    agt_modal_creation_div: null,
    agt_modal_creation: null,
    agt_modal_updated_div: null,
    agt_modal_updated: null,
    agt_modal_add_main_img: null,
    agt_modal_add_additional_img: null,
    agt_modal_save: null
};

// ===== ПЕРЕМЕННЫЕ ДЛЯ ФОТО =====
let mainAgentPhotoFile = null;
let existingAgentMainPhoto = null;

let agt_modal_type = 0; // 1 - add, 2 - view, 3 - edit
let opened_agent = null;

// ===== ИНИЦИАЛИЗАЦИЯ ПРЕДПРОСМОТРА ФОТО =====
function initAgentPhotoPreview() {
    const mainPhotoInput = document.getElementById('agt_modal_main_photo');
    const mainPhotoImg = document.getElementById('main_photo_img');
    const previewPlaceholder = document.querySelector('#main_photo_preview .preview-placeholder');

    if (mainPhotoInput) {
        mainPhotoInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                mainAgentPhotoFile = file;
                existingAgentMainPhoto = null;
                const reader = new FileReader();
                reader.onload = function(event) {
                    mainPhotoImg.src = event.target.result;
                    mainPhotoImg.classList.add('active');
                    if (previewPlaceholder) previewPlaceholder.classList.add('hide');
                };
                reader.readAsDataURL(file);
            }
        });
    }
}

// ===== ЗАГРУЗКА СУЩЕСТВУЮЩИХ ФОТО =====
function loadAgentExistingPhotos(agentData, editable = true) {
    const mainPhotoImg = document.getElementById('main_photo_img');
    const previewPlaceholder = document.querySelector('#main_photo_preview .preview-placeholder');
    console.log(agentData.image_path);
    if (agentData.image_path) {
        existingAgentMainPhoto = `../../src/images/agents/${agentData.image_path}`;
        mainPhotoImg.src = `../../src/images/agents/${agentData.image_path}`;
        mainPhotoImg.classList.add('active');
        if (previewPlaceholder) previewPlaceholder.classList.add('hide');
        mainAgentPhotoFile = null;
    } else {
        existingAgentMainPhoto = null;
    }
}

// ===== СБРОС ФОТО =====
function resetAgentPhotos() {
    const mainPhotoImg = document.getElementById('main_photo_img');
    const mainPhotoInput = document.getElementById('agt_modal_main_photo');
    const previewPlaceholder = document.querySelector('#main_photo_preview .preview-placeholder');

    if (mainPhotoImg) {
        mainPhotoImg.src = '';
        mainPhotoImg.classList.remove('active');
    }
    if (mainPhotoInput) mainPhotoInput.value = '';
    if (previewPlaceholder) previewPlaceholder.classList.remove('hide');

    mainAgentPhotoFile = null;
    existingAgentMainPhoto = null;
}

// ===== ПОЛУЧЕНИЕ ДАННЫХ ФОТО =====
function getAgentPhotosData() {
    return {
        main_photo: mainAgentPhotoFile,
        existing_main_photo: existingAgentMainPhoto
    };
}

// ===== ИНИЦИАЛИЗАЦИЯ =====
function initAgents() {
    for (let element in agt_modal_elements) {
        agt_modal_elements[element] = document.getElementById(`${element}`);
    }
    initAgentPhotoPreview();
    console.log("Agent modal elements initialized:", agt_modal_elements);
}

// ===== ПОДГОТОВКА МОДАЛЬНОГО ОКНА (EDITABLE/NON-EDITABLE) =====
function prepareAgentModal(editable = true) {
    const fields = [
        'agt_modal_name', 'agt_modal_surname', 'agt_modal_patronymic',
        'agt_modal_position', 'agt_modal_age', 'agt_modal_experience',
        'agt_modal_events', 'agt_modal_dsc_short', 'agt_modal_biography'
    ];

    console.log(agt_modal_type);
    if (agt_modal_type === 1) {
        if (agt_modal_elements.agt_modal_creation_div) {
            agt_modal_elements.agt_modal_creation_div.style.display = 'none';
        }
        if (agt_modal_elements.agt_modal_updated_div) {
            agt_modal_elements.agt_modal_updated_div.style.display = 'none';
        }
    }else{
        if (agt_modal_elements.agt_modal_creation_div) {
            agt_modal_elements.agt_modal_creation_div.style.display = 'flex';
        }
        if (agt_modal_elements.agt_modal_updated_div) {
            agt_modal_elements.agt_modal_updated_div.style.display = 'flex';
        }
    }
    if (editable) {
        fields.forEach(field => {
            if (agt_modal_elements[field]) {
                agt_modal_elements[field].removeAttribute('disabled');
            }
        });
        if (agt_modal_elements.agt_modal_add_main_img) {
            agt_modal_elements.agt_modal_add_main_img.style.display = 'block';
        }
        if (agt_modal_elements.agt_modal_save) {
            agt_modal_elements.agt_modal_save.style.display = 'block';
        }
    } else {
        fields.forEach(field => {
            if (agt_modal_elements[field]) {
                agt_modal_elements[field].setAttribute('disabled', 'true');
            }
        });
        if (agt_modal_elements.agt_modal_add_main_img) {
            agt_modal_elements.agt_modal_add_main_img.style.display = 'none';
        }
        if (agt_modal_elements.agt_modal_save) {
            agt_modal_elements.agt_modal_save.style.display = 'none';
        }
    }
}

// ===== ЗАПОЛНЕНИЕ ДАННЫМИ =====
function prepareModalAgentData(agentData) {
    // Разбиваем ФИО на части (если пришло полное имя)
    if (agentData.name) {
        const nameParts = agentData.name.split(' ');
        agt_modal_elements.agt_modal_surname.value = nameParts[0] || '';
        agt_modal_elements.agt_modal_name.value = nameParts[1] || '';
        agt_modal_elements.agt_modal_patronymic.value = nameParts[2] || '';
    }

    if (agentData.position && agt_modal_elements.agt_modal_position) {
        agt_modal_elements.agt_modal_position.selectedIndex = agentData.position;
    }
    if (agentData.birthdate) {
        agt_modal_elements.agt_modal_birthdate.value = agentData.birthdate;
    }

    agt_modal_elements.agt_modal_dsc_short.value = agentData.description || '';
    agt_modal_elements.agt_modal_biography.value = agentData.biographic || '';
    agt_modal_elements.agt_modal_creation.value = agentData.created_at || '';
    agt_modal_elements.agt_modal_updated.value = agentData.updated_at || '';
}

// ===== СБРОС ФОРМЫ =====
function agentModalClear() {
    agt_modal_elements.agt_modal_name.value = '';
    agt_modal_elements.agt_modal_surname.value = '';
    agt_modal_elements.agt_modal_patronymic.value = '';
    if (agt_modal_elements.agt_modal_position) agt_modal_elements.agt_modal_position.selectedIndex = 0;
    agt_modal_elements.agt_modal_birthdate.value = '';
    agt_modal_elements.agt_modal_dsc_short.value = '';
    agt_modal_elements.agt_modal_biography.value = '';
    agt_modal_elements.agt_modal_creation.value = '';
    agt_modal_elements.agt_modal_updated.value = '';

    resetAgentPhotos();
    opened_agent = null;
}

// ===== ЗАКРЫТИЕ МОДАЛЬНОГО ОКНА =====
function closeAgentModal() {
    let agent_modal = document.getElementById("agt_modal");
    agent_modal?.classList.remove("active");
    document.body.style.overflow = "auto";
    const container = agent_modal?.querySelector('.modal-container');
    if (container) container.scrollTop = 0;
    agentModalClear();
}

function agentClose() {
    let agent_modal = document.getElementById("agt_modal");
    if (agent_modal) {
        if (!agt_modal_elements.agt_modal_name?.hasAttribute("disabled")) {
            if (confirm('Если вы закроете окно, ваши изменения не сохранятся. Вы уверены?')) {
                closeAgentModal();
            }
        } else {
            closeAgentModal();
        }
    }
}

// ===== ОТКРЫТИЕ ПРОСМОТРА =====
function agentView(agentId) {
    if (!agentId || !Number.isInteger(agentId)) {
        console.log("Неверный ID агента");
        return;
    }

    const agent_modal = document.getElementById("agt_modal");
    if (!agent_modal) return;

    $.post("../../server/post/adminAgentHandler.php", {"type": "getById", "agentId": agentId}, function(data) {
        const data_parsed = JSON.parse(data);
        if (data_parsed.response?.message) {
            const agentData = JSON.parse(data_parsed.response.message);
            agt_modal_elements.agt_modal_title.innerHTML = "Просмотр агента";
            prepareAgentModal(false);
            prepareModalAgentData(agentData);
            loadAgentExistingPhotos(agentData, false);
            agt_modal_type = 2;
            agent_modal.classList.add("active");
            document.body.style.overflow = "hidden";
            opened_agent = agentId;
        }
    });
}

// ===== ОТКРЫТИЕ РЕДАКТИРОВАНИЯ =====
function agentEdit(agentId) {
    if (!agentId || !Number.isInteger(agentId)) {
        console.log("Неверный ID агента");
        return;
    }

    const agent_modal = document.getElementById("agt_modal");
    if (!agent_modal) return;

    $.post("../../server/post/adminAgentHandler.php", {"type": "getById", "agentId": agentId}, function(data) {
        const data_parsed = JSON.parse(data);
        if (data_parsed.response?.message) {
            const agentData = JSON.parse(data_parsed.response.message);
            agt_modal_elements.agt_modal_title.innerHTML = "Редактирование агента";
            prepareAgentModal(true);
            prepareModalAgentData(agentData);
            loadAgentExistingPhotos(agentData, true);
            agt_modal_type = 3;
            agent_modal.classList.add("active");
            document.body.style.overflow = "hidden";
            opened_agent = agentId;
        }
    });
}

// ===== ДОБАВЛЕНИЕ НОВОГО АГЕНТА =====
function agentAdd() {
    const agent_modal = document.getElementById("agt_modal");
    if (!agent_modal) return;

    agt_modal_type = 1;
    agt_modal_elements.agt_modal_title.innerHTML = "Добавление агента";
    prepareAgentModal(true);
    agentModalClear();
    agent_modal.classList.add("active");
    document.body.style.overflow = "hidden";
}

// ===== СОХРАНЕНИЕ АГЕНТА =====
function agentSave() {
    if (!opened_agent && agt_modal_type !== 1) {
        Toast.warning("Произошла ошибка. Перезагрузите страницу.");
        return;
    }

    const agent_modal = document.getElementById("agt_modal");
    if (!agent_modal || !agent_modal.classList.contains("active")) return;

    if (agt_modal_elements.agt_modal_name?.hasAttribute("disabled")) {
        console.log("Форма в режиме просмотра");
        return;
    }

    const fullName = `${agt_modal_elements.agt_modal_surname?.value || ''} ${agt_modal_elements.agt_modal_name?.value || ''} ${agt_modal_elements.agt_modal_patronymic?.value || ''}`.trim();

    const agentData = {
        name: fullName,
        position: agt_modal_elements.agt_modal_position?.selectedIndex || 4,
        birthdate: agt_modal_elements.agt_modal_birthdate?.value || null,  // ДАТА РОЖДЕНИЯ
        description: agt_modal_elements.agt_modal_dsc_short?.value || '',
        biographic: agt_modal_elements.agt_modal_biography?.value || ''
    };

    if (agt_modal_type === 1) {
        agentData.is_active = 1;
    } else if (agt_modal_type === 3) {
        agentData.agentId = opened_agent;
    }

    const photosData = getAgentPhotosData();

    // Создаём FormData
    const formData = new FormData();

    if (agt_modal_type === 1) {
        formData.append("type", "addAgent");
    } else if (agt_modal_type === 3) {
        formData.append("type", "editAgent");
    }

    formData.append("agentData", JSON.stringify(agentData));

    if (photosData.main_photo) {
        formData.append("main_photo", photosData.main_photo);
    }
    if (photosData.existing_main_photo) {
        formData.append("existing_main_photo", photosData.existing_main_photo);
    }

    $.ajax({
        url: "../../server/post/adminAgentHandler.php",
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: function(data) {
            const data_parsed = JSON.parse(data);
            if (data_parsed.response?.code === 200) {
                Toast.success("Данные сохранены!");

                let agentId; let action; let image_path;
                if (agt_modal_type === 1) {
                    action = 0;
                    let response_data = JSON.parse(data_parsed.response.message);
                    if (response_data.agentId != null && response_data.image_path != null && response_data.text != null) {
                        agentId = response_data.agentId;
                        image_path = response_data.image_path;
                    }
                } else if (agt_modal_type === 3 && opened_agent) {
                    agentId = agentData.agentId;
                    action = 1;
                }
                console.log("Agent id: "+agentId);
                console.log("Action: "+action);
                console.log("Image path: "+image_path || "Null");
                $.post("../../server/post/adminAgentHandler.php", {"type": "getById", "agentId": agentId}, function(data) {
                    const data_parsed = JSON.parse(data);
                    if (data_parsed.response?.message) {
                        const agentData = JSON.parse(data_parsed.response.message);
                        if (action === 0) {
                            const newRow = createAgentRow(agentId, agentData, image_path);
                            const tbody = document.querySelector('.data-table tbody');
                            if (tbody) {
                                tbody.appendChild(newRow);
                                const noDataRow = tbody.querySelector('.no-data-message');
                                if (noDataRow) noDataRow.remove();
                            }
                        }else if (action === 1) {
                            updateAgentRow(agentId, agentData);
                        }
                    }
                });
                closeAgentModal();
            } else if (data_parsed.error) {
                Toast.error(`Ошибка [${data_parsed.error.code}]: ${data_parsed.error.message}`);
            }
        },
        error: function(xhr, status, error) {
            Toast.error(`Ошибка: ${error}`);
        }
    });
}

// ===== СОЗДАНИЕ СТРОКИ В ТАБЛИЦЕ =====
function createAgentRow(agentId, agentData, imagePath) {
    const tr = document.createElement('tr');
    tr.id = `tr_${agentId}`;

    agentData.position = getStringPosition(agentData.position);
    let declension = ['год', 'года', 'лет'];
    agentData.age = `${agentData.age} ${plural(agentData.age, declension)}`;
    const placeholder = '../../src/images/placeholder.jpg';

    tr.innerHTML = `
        <td>${agentId}</td>
        <td><img src="${imagePath}" class="table-thumb" onerror="this.src='${placeholder}'" alt="${agentData.name}"></td>
        <td>${escapeHtml(agentData.name)}</td>
        <td>${escapeHtml(agentData.position)}</td>
        <td>${agentData.age}</td>
        <td>${agentData.experience} лет</td>
        <td>${agentData.events_count}</td>
        <td><span class="status active">Активен</span></td>
        <td>
            <button class="btn-icon view" onclick="agentView(${agentId})">👁️</button>
            <button class="btn-icon edit" onclick="agentEdit(${agentId})">✏️</button>
            <button class="btn-icon delete" onclick="agentDelete(${agentId})">🗑️</button>
        </td>
    `;

    return tr;
}

// ===== ОБНОВЛЕНИЕ СТРОКИ В ТАБЛИЦЕ =====
function updateAgentRow(agentId, agentData) {
    const row = document.getElementById(`tr_${agentId}`);
    if (!row) return;
    agentData.position = getStringPosition(agentData.position);
    let declension = ['год', 'года', 'лет'];
    agentData.age = `${agentData.age} ${plural(agentData.age, declension)}`;
    const cells = row.cells;
    if (cells.length >= 8) {
        const imgElement = cells[1].querySelector('img');
        if (imgElement) {
            const timestamp = new Date().getTime();
            imgElement.src = `${imgElement.src}?t=${timestamp}`;
        }
        cells[2].textContent = agentData.name;
        cells[3].textContent = agentData.position;
        cells[4].textContent = agentData.age;
        cells[5].textContent = `${agentData.experience} лет`;
        cells[6].textContent = agentData.events_count;
    }
}

// ===== УДАЛЕНИЕ АГЕНТА =====
function agentDelete(agentId) {
    if (confirm('Вы действительно хотите удалить этого агента?')) {
        $.post("../../server/post/adminAgentHandler.php", {
            "type": "deleteAgent",
            "agentId": agentId
        }, function(data) {
            const data_parsed = JSON.parse(data);
            if (data_parsed.response?.code === 200) {
                Toast.success("Агент удалён");
                const row = document.getElementById(`tr_${agentId}`);
                if (row) row.remove();
            } else if (data_parsed.error) {
                Toast.error(`Ошибка: ${data_parsed.error.message}`);
            }
        });
    }
}

function getStringPosition(position) {
    let positions = {
        1: "Руководитель",
        2: "Ведущий агент",
        3: "Старший агент",
        4: "Менеджер",
        5: "Агент"
    };
    return positions[position];
}

function plural(number, titles) {
    let cases = [2, 0, 1, 1, 1, 2];
    return titles[ (number%100>4 && number%100<20)? 2 : cases[(number%10<5)?number%10:5] ];
}

// ===== ЭКРАНИРОВАНИЕ HTML =====
function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}