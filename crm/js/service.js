let srv_modal_elements = {
    srv_modal_title: null,
    srv_modal_name: null,
    srv_modal_category: null,
    srv_modal_option_organization: null,
    srv_modal_option_bus: null,
    srv_modal_option_cremation: null,
    srv_modal_option_monuments: null,
    srv_modal_price: null,
    srv_modal_description: null,
    srv_modal_what_includes: null,
    srv_modal_save: null,
    srv_modal_creation: null,
    srv_modal_updated: null,
    srv_modal_updated_div: null,
    srv_modal_creation_div: null,
    srv_modal_status: null,
    srv_modal_option_active: null,
    srv_modal_option_unactive: null
};

let srv_modal_type = 0;
let opened_service = null;

// ===== ОСНОВНЫЕ ФУНКЦИИ =====

function initServices() {
    for (let element in srv_modal_elements) {
        srv_modal_elements[element] = document.getElementById(`${element}`);
    }
    console.log(srv_modal_elements);
}

async function serviceView(serviceId) {
    if (serviceId === undefined || serviceId === null) {
        Toast.warning("Внимание [1]: Обнаружено изменение данных. Перезагрузите страницу.");
        return;
    }
    if (!Number.isInteger(serviceId)) {
        Toast.warning("Внимание [2]: Обнаружено изменение данных. Перезагрузите страницу.");
        return;
    }
    let service_modal = document.getElementById("srv_modal");
    if (service_modal) {
        const sessionId = getCookie('session_id');
        const fingerprint = await collectFingerPrint();
        $.post("../../server/post/adminServiceHandler.php", {
            "type": "getById",
            "serviceId": serviceId,
            "session_id": sessionId,
            "fingerprint": fingerprint
        }, function (data) {
            var data_parsed = JSON.parse(data);
            if (data_parsed.response != null) {
                let serviceData = JSON.parse(data_parsed.response.message);
                srv_modal_elements.srv_modal_title.innerHTML = "Просмотр услуги";
                prepareServiceModal(false);
                prepareModalServiceData(serviceData);
                srv_modal_type = 2;
                service_modal.classList.add("active");
                document.body.style.overflow = "hidden";
                opened_service = serviceId;
            } else if (data_parsed.error != null && data_parsed.error.code != null && data_parsed.error.message != null) {
                Toast.error(`Ошибка сервера [${data_parsed.error.code}]: ${data_parsed.error.message}`);
            }
        });
    } else {
        Toast.warning("Внимание [3]: Обнаружено изменение данных. Перезагрузите страницу.");
    }
}

async function serviceEdit(serviceId) {
    if (serviceId === undefined || serviceId === null) {
        Toast.warning("Внимание [1]: Обнаружено изменение данных. Перезагрузите страницу.");
        return;
    }
    if (!Number.isInteger(serviceId)) {
        Toast.warning("Внимание [2]: Обнаружено изменение данных. Перезагрузите страницу.");
        return;
    }
    let service_modal = document.getElementById("srv_modal");
    if (service_modal) {
        const sessionId = getCookie('session_id');
        const fingerprint = await collectFingerPrint();
        $.post("../../server/post/adminServiceHandler.php", {
            "type": "getById",
            "serviceId": serviceId,
            "session_id": sessionId,
            "fingerprint": fingerprint
        }, function (data) {
            var data_parsed = JSON.parse(data);
            if (data_parsed.response != null) {
                let serviceData = JSON.parse(data_parsed.response.message);
                srv_modal_elements.srv_modal_title.innerHTML = "Редактирование услуги";
                prepareServiceModal(true);
                prepareModalServiceData(serviceData);
                srv_modal_type = 3;
                service_modal.classList.add("active");
                document.body.style.overflow = "hidden";
                opened_service = serviceId;
            } else if (data_parsed.error != null && data_parsed.error.code != null && data_parsed.error.message != null) {
                Toast.error(`Ошибка сервера [${data_parsed.error.code}]: ${data_parsed.error.message}`);
            } else {
                Toast.error("Сервер не отвечает.");
            }
        });
    } else {
        Toast.warning("Внимание: Обнаружено изменение данных. Перезагрузите страницу.");
    }
}

async function serviceDelete(serviceId) {
    if (confirm("Вы действительно хотите удалить услугу?")) {
        let tr_element = document.getElementById("tr_" + serviceId);
        if (tr_element) {
            tr_element.remove();
        }
        const sessionId = getCookie('session_id');
        const fingerprint = await collectFingerPrint();
        $.post("../../server/post/adminServiceHandler.php", {
            "type": "deleteById",
            "serviceId": serviceId,
            "session_id": sessionId,
            "fingerprint": fingerprint
        }, function (data) {
            try {
                var data_parsed = JSON.parse(data);
                if (data_parsed.response != null && data_parsed.response.code != null && data_parsed.response.message != null) {
                    Toast.success("Успешно удалено");
                    console.log(data_parsed.response.message);
                } else if (data_parsed.error != null != null && data_parsed.error.code != null && data_parsed.error.message != null) {
                    Toast.error(`Ошибка сервера [${data_parsed.error.code}]: ${data_parsed.error.message}`);
                }
            } catch (e) {
                console.log("An error occurred in JSON.parse() : " + e.message);
            }
        });
    }
}

function serviceAdd() {
    let service_modal = document.getElementById("srv_modal");
    if (service_modal){
        opened_service = 0;
        srv_modal_type = 1;
        srv_modal_elements.srv_modal_title.innerHTML = "Добавление услуги";
        prepareServiceModal(true);
        srv_modal_elements.srv_modal_creation_div.style.display = "none";
        srv_modal_elements.srv_modal_updated_div.style.display = "none";
        service_modal.classList.add("active");
        document.body.style.overflow = "hidden";
    }
}

async function serviceSave() {
    if (opened_service !== null) {
        let service_modal = document.getElementById("srv_modal");
        if (service_modal) {
            if (service_modal.classList.contains("active")) {
                if (!srv_modal_elements.srv_modal_name.hasAttribute("disabled")) {
                    let serviceData = validateServiceData();
                    if (serviceData === false) {
                        return;
                    }

                    var formData = new FormData();
                    var td_action = 0;

                    if (srv_modal_type === 1) {
                        formData.append("type", "addService");
                    } else if (srv_modal_type === 3) {
                        formData.append("type", "editService");
                        if (opened_service !== null && opened_service > 0) {
                            td_action = 1;
                            serviceData["serviceId"] = opened_service;
                        } else {
                            Toast.warning("Внимание: Обнаружено изменение временных данных. Перезагрузите страницу.");
                            return;
                        }
                    } else {
                        Toast.error("Нет доступа");
                        return;
                    }

                    formData.append("serviceData", JSON.stringify(serviceData));

                    const sessionId = getCookie('session_id');
                    const fingerprint = await collectFingerPrint();
                    formData.append("session_id", sessionId);
                    formData.append("fingerprint", fingerprint);
                    $.ajax({
                        url: "../../server/post/adminServiceHandler.php",
                        type: "POST",
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function (data) {
                            let data_parsed = JSON.parse(data);
                            if (data_parsed.response != null && data_parsed.response.code != null && data_parsed.response.message != null) {
                                Toast.success("Данные сохранены!");

                                if (td_action === 0) {
                                    let needleData = JSON.parse(data_parsed.response.message);
                                    if (needleData.serviceId != null) {
                                        const newServiceId = needleData.serviceId;
                                        const newRow = createServiceRow(newServiceId, serviceData);
                                        const tbody = document.querySelector('.data-table tbody');
                                        if (tbody) {
                                            tbody.appendChild(newRow);
                                        }
                                        const noDataRow = tbody.querySelector('.no-data-message');
                                        if (noDataRow) {
                                            noDataRow.remove();
                                        }
                                    }
                                } else if (td_action === 1) {
                                    if (opened_service !== null) {
                                        updateServiceRow(opened_service, serviceData);
                                    }
                                }

                                closeServiceModal();
                                serviceModalClear();
                            } else if (data_parsed.error != null && data_parsed.error.code != null && data_parsed.error.message != null) {
                                Toast.error(`Ошибка сервера [${data_parsed.error.code}]: ${data_parsed.error.message}`);
                                return;
                            } else {
                                Toast.error("Ошибка. Не получен ответ от сервера.");
                            }
                        },
                        error: function (xhr, status, error) {
                            Toast.error(`Произошла ошибка: ${error}`);
                        }
                    });
                } else console.log("Произошла ошибка. Откройте окно заново и попробуйте ещё раз.")
            } else console.log("Произошла ошибка. Откройте окно заново и попробуйте ещё раз.")
        } else Toast.warning("Произошла ошибка. Откройте окно заново и попробуйте ещё раз.")
    } else Toast.warning("Произошла ошибка. Откройте окно заново и попробуйте ещё раз.")
}

function createServiceRow(serviceId, serviceData) {
    const tr = document.createElement('tr');
    tr.id = `tr_${serviceId}`;

    const categoryNames = {
        0: 'Организация похорон',
        1: 'Кремация',
        2: 'Перевоз тела',
        3: 'Юридическая помощь',
        4: 'Ритуальный транспорт',
        5: 'Поминальные обеды',
        6: 'Памятники и благоустройство'
    };
    const categoryName = categoryNames[serviceData.category] || 'Не определено';

    const isActive = serviceData.isActive;
    const statusClass = isActive ? 'active' : 'inactive';
    const statusText = isActive ? 'Активен' : 'Не активен';

    const formattedPrice = serviceData.price.toLocaleString('ru-RU');

    tr.innerHTML = `
        <td>${serviceId}</td>
        <td>${serviceData.name}</td>
        <td>${categoryName}</td>
        <td>${formattedPrice} ₽</td>
        <td><span class="status ${statusClass}">${statusText}</span></td>
        <td>
            <button class="btn-icon view" onclick="serviceView(${serviceId})">👁️</button>
            <button class="btn-icon edit" onclick="serviceEdit(${serviceId})">✏️</button>
            <button class="btn-icon delete" onclick="serviceDelete(${serviceId})">🗑️</button>
        </td>
    `;

    return tr;
}

function updateServiceRow(serviceId, serviceData) {
    const row = document.getElementById(`tr_${serviceId}`);
    if (!row) return;

    const categoryNames = {
        0: 'Организация похорон',
        1: 'Кремация',
        2: 'Перевоз тела',
        3: 'Юридическая помощь',
        4: 'Ритуальный транспорт',
        5: 'Поминальные обеды',
        6: 'Памятники и благоустройство'
    };
    const categoryName = categoryNames[serviceData.category] || 'Не определено';

    const isActive = serviceData.isActive;
    console.log("Is active? : "+isActive);
    const statusClass = isActive ? 'active' : 'inactive';
    const statusText = isActive ? 'Активен' : 'Не активен';

    const formattedPrice = serviceData.price.toLocaleString('ru-RU');

    const cells = row.cells;
    if (cells.length >= 5) {
        cells[1].textContent = serviceData.name;
        cells[2].textContent = categoryName;
        cells[3].textContent = `${formattedPrice} ₽`;
        cells[4].innerHTML = `<span class="status ${statusClass}">${statusText}</span>`;

        const viewBtn = cells[5].querySelector('.btn-icon.view');
        const editBtn = cells[5].querySelector('.btn-icon.edit');
        const deleteBtn = cells[5].querySelector('.btn-icon.delete');

        if (viewBtn) viewBtn.setAttribute('onclick', `serviceView(${serviceId})`);
        if (editBtn) editBtn.setAttribute('onclick', `serviceEdit(${serviceId})`);
        if (deleteBtn) deleteBtn.setAttribute('onclick', `serviceDelete(${serviceId})`);
    }
}

function prepareServiceModal(editable = true) {
    if (editable) {
        srv_modal_elements.srv_modal_title.removeAttribute("disabled");
        srv_modal_elements.srv_modal_name.removeAttribute("disabled");
        srv_modal_elements.srv_modal_category.removeAttribute("disabled");
        srv_modal_elements.srv_modal_price.removeAttribute("disabled");
        srv_modal_elements.srv_modal_description.removeAttribute("disabled");
        srv_modal_elements.srv_modal_what_includes.removeAttribute("disabled");
        srv_modal_elements.srv_modal_status.removeAttribute("disabled");
        srv_modal_elements.srv_modal_save.style.display = "block";
    } else {
        srv_modal_elements.srv_modal_title.setAttribute("disabled", "true");
        srv_modal_elements.srv_modal_name.setAttribute("disabled", "true");
        srv_modal_elements.srv_modal_category.setAttribute("disabled", "true");
        srv_modal_elements.srv_modal_price.setAttribute("disabled", "true");
        srv_modal_elements.srv_modal_description.setAttribute("disabled", "true");
        srv_modal_elements.srv_modal_what_includes.setAttribute("disabled", "true");
        srv_modal_elements.srv_modal_status.setAttribute("disabled", "true");
        srv_modal_elements.srv_modal_save.style.display = "none";
    }
}

function prepareModalServiceData(serviceData) {
    srv_modal_elements.srv_modal_name.value = serviceData.name || '';
    srv_modal_elements.srv_modal_category.selectedIndex = serviceData.category+1 || 0;
    srv_modal_elements.srv_modal_price.value = serviceData.price || 0;
    srv_modal_elements.srv_modal_description.value = serviceData.description || '';
    srv_modal_elements.srv_modal_what_includes.value = serviceData.what_includes || '';
    srv_modal_elements.srv_modal_updated.value = serviceData.updated_at || '';
    srv_modal_elements.srv_modal_creation.value = serviceData.created_at || '';
    srv_modal_elements.srv_modal_status.selectedIndex = (serviceData.is_active + 1) || 1;
}

function serviceModalClear() {
    srv_modal_elements.srv_modal_name.value = '';
    srv_modal_elements.srv_modal_category.selectedIndex = 0;
    srv_modal_elements.srv_modal_price.value = '';
    srv_modal_elements.srv_modal_description.value = '';
    srv_modal_elements.srv_modal_what_includes.value = '';
    srv_modal_elements.srv_modal_updated.value = '';
    srv_modal_elements.srv_modal_creation.value = '';
    srv_modal_elements.srv_modal_status.selectedIndex = 0;
}

function serviceClose() {
    let service_modal = document.getElementById("srv_modal");
    if (service_modal) {
        if (!srv_modal_elements.srv_modal_name.hasAttribute("disabled")) {
            if (confirm('Если вы закроете окно, ваши изменения не сохранятся. Вы уверены?')) {
                closeServiceModal();
                serviceModalClear();
            }
        } else {
            closeServiceModal();
            serviceModalClear();
        }
    }
}

function closeServiceModal() {
    let service_modal = document.getElementById("srv_modal");
    if (service_modal) {
        service_modal.classList.remove("active");
        document.body.style.overflow = "auto";
        opened_service = null;
        let container = service_modal.querySelector('.modal-container');
        if (container) {
            container.scrollTop = 0;
        }
    }
}

function validateServiceData() {
    if (!srv_modal_elements.srv_modal_name.value.trim()) {
        showHint(srv_modal_elements.srv_modal_name, 'Название услуги обязательно для заполнения');
        return false;
    }
    if (srv_modal_elements.srv_modal_name.value.length > 255) {
        showHint(srv_modal_elements.srv_modal_name, 'Название не должно превышать 255 символов');
        return false;
    }

    if (srv_modal_elements.srv_modal_category.selectedIndex === -1 || srv_modal_elements.srv_modal_category.selectedIndex === 0) {
        showHint(srv_modal_elements.srv_modal_category, 'Выберите категорию услуги');
        return false;
    }
    if (srv_modal_elements.srv_modal_category.selectedIndex < 1 || srv_modal_elements.srv_modal_category.selectedIndex > 4) {
        showHint(srv_modal_elements.srv_modal_category, 'Обнаружено изменение структуры страницы. Пожалуйста, перезагрузите страницу');
        return false;
    }
    const category = srv_modal_elements.srv_modal_category.selectedIndex;

    let price = 0;
    if (!srv_modal_elements.srv_modal_price.value) {
        showHint(srv_modal_elements.srv_modal_price, 'Цена обязательна для заполнения');
        return false;
    }
    price = Number(srv_modal_elements.srv_modal_price.value);
    if (isNaN(price) || price < 0) {
        showHint(srv_modal_elements.srv_modal_price, 'Введите корректную цену (положительное число)');
        return false;
    }
    if (price.toString().length > 10) {
        showHint(srv_modal_elements.srv_modal_price, 'Цена не должна превышать 10 цифр');
        return false;
    }
    price = Math.floor(price);

    if (!srv_modal_elements.srv_modal_description.value.trim()) {
        showHint(srv_modal_elements.srv_modal_description, 'Короткое описание обязательно для заполнения');
        return false;
    }

    if (!srv_modal_elements.srv_modal_what_includes.value.trim()) {
        showHint(srv_modal_elements.srv_modal_what_includes, 'Наполнение услуги обязательно для заполнения');
        return false;
    }

    if (srv_modal_elements.srv_modal_status.selectedIndex === -1 || srv_modal_elements.srv_modal_status.selectedIndex === 0) {
        showHint(srv_modal_elements.srv_modal_status, 'Выберите статус услуги');
        return false;
    }
    if (srv_modal_elements.srv_modal_status.selectedIndex < 1 || srv_modal_elements.srv_modal_status.selectedIndex > 2) {
        showHint(srv_modal_elements.srv_modal_status, 'Обнаружено изменение структуры страницы. Пожалуйста, перезагрузите страницу');
        return false;
    }
    const isActive = srv_modal_elements.srv_modal_status.selectedIndex;

    return {
        name: srv_modal_elements.srv_modal_name.value.trim(),
        category: category-1,
        price: price,
        description: srv_modal_elements.srv_modal_description.value.trim(),
        what_includes: srv_modal_elements.srv_modal_what_includes.value.trim(),
        isActive: isActive - 1
    };
}

function showHint(element, message) {
    const existingHint = element.parentNode.querySelector('.input-hint');
    if (existingHint) existingHint.remove();

    const hint = document.createElement('div');
    hint.className = 'input-hint';
    hint.textContent = message;

    element.parentNode.style.position = 'relative';
    element.parentNode.appendChild(hint);
    element.scrollIntoView({block: "center", behavior: "smooth"});
    element.focus({preventScroll: true});

    setTimeout(() => {
        if (hint && hint.remove) hint.remove();
    }, 2000);
}