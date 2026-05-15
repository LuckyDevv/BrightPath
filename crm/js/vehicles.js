let vhc_modal_elements = {
    vhc_modal_title: null,
    vhc_modal_name: null,
    vhc_modal_category: null,
    vhc_modal_option_hearse: null,
    vhc_modal_option_bus: null,
    vhc_modal_option_passenger: null,
    vhc_modal_option_special: null,
    vhc_modal_price: null,
    vhc_modal_stock: null,
    vhc_modal_dsc_short: null,
    vhc_modal_dsc_full: null,
    vhc_modal_save: null,
    vhc_modal_creation: null,
    vhc_modal_updated: null,
    vhc_modal_updated_div: null,
    vhc_modal_creation_div: null,
    vhc_modal_color: null,
    vhc_modal_seats: null,
    vhc_modal_status: null,
    vhc_modal_option_active: null,
    vhc_modal_option_unactive: null,
    vhc_modal_add_main_img: null,
    vhc_modal_add_additional_img: null
};

// ===== ДОБАВЛЕННЫЕ ПЕРЕМЕННЫЕ ДЛЯ ФОТО =====
let mainPhotoFile = null;
let additionalPhotos = [];
let existingMainPhoto = null;
let existingAdditionalPhotos = [];

let vhc_modal_type = 0;
let opened_vehicle = null;

// ===== ДОБАВЛЕННЫЕ ФУНКЦИИ ДЛЯ РАБОТЫ С ФОТО =====

// Инициализация предпросмотра главного фото
function initPhotoPreview() {
    const mainPhotoInput = document.getElementById('vhc_modal_main_photo');
    const mainPhotoImg = document.getElementById('main_photo_img');
    const previewPlaceholder = document.querySelector('#main_photo_preview .preview-placeholder');

    if (mainPhotoInput) {
        mainPhotoInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                mainPhotoFile = file;
                existingMainPhoto = null;
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

// Добавление дополнительного фото
function addAdditionalPhotoField() {
    const input = document.createElement('input');
    input.type = 'file';
    input.accept = 'image/*';
    input.classList.add('image-input');
    input.style.display = 'none';
    input.setAttribute("multiple", "");

    input.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            addAdditionalPhotoPreview(file, input);
        }
    });

    document.body.appendChild(input);
    input.click();
}

function addAdditionalPhotoPreview(file, inputElement) {
    const grid = document.getElementById('additional_photos_grid');
    const photoId = Date.now() + Math.random();

    const reader = new FileReader();
    reader.onload = function(event) {
        const photoItem = document.createElement('div');
        photoItem.className = 'additional-photo-item';
        photoItem.setAttribute('data-id', photoId);
        photoItem.innerHTML = `
            <img src="${event.target.result}" alt="Дополнительное фото">
            <button class="remove-photo" onclick="removeAdditionalPhoto(this, '${photoId}')">×</button>
        `;
        grid.appendChild(photoItem);

        additionalPhotos.push({
            id: photoId,
            file: file,
            element: photoItem
        });
    };
    reader.readAsDataURL(file);

    setTimeout(() => {
        if (inputElement && inputElement.parentNode) {
            inputElement.parentNode.removeChild(inputElement);
        }
    }, 100);
}

function removeAdditionalPhoto(button, photoId) {
    const photoItem = button.closest('.additional-photo-item');
    if (photoItem) {
        photoItem.remove();
        additionalPhotos = additionalPhotos.filter(p => p.id !== photoId);
        existingAdditionalPhotos = existingAdditionalPhotos.filter(p => p.id !== photoId);
    }
}

// Загрузка существующих фото при редактировании
function loadExistingPhotos(vehicleData, editable=true) {
    console.log(vehicleData);
    const mainPhotoImg = document.getElementById('main_photo_img');
    const previewPlaceholder = document.querySelector('#main_photo_preview .preview-placeholder');
    const grid = document.getElementById('additional_photos_grid');

    // Загрузка главного фото
    if (vehicleData.image_path) {
        console.log(vehicleData.image_path);
        existingMainPhoto = `../../src/images/vehicles/${vehicleData.image_path}/main.jpg`;
        mainPhotoImg.src = `../../src/images/vehicles/${vehicleData.image_path}/main.jpg`;
        mainPhotoImg.classList.add('active');
        if (previewPlaceholder) previewPlaceholder.classList.add('hide');
        mainPhotoFile = null;
    } else {
        existingMainPhoto = null;
    }

    // Загрузка дополнительных фото
    grid.innerHTML = '';
    additionalPhotos = [];
    existingAdditionalPhotos = [];

    if (vehicleData.additional_photos && vehicleData.additional_photos.length) {
        vehicleData.additional_photos.forEach((photo, index) => {
            console.log(photo);
            const photoId = Date.now() + index;
            const photoItem = document.createElement('div');
            photoItem.className = 'additional-photo-item';
            photoItem.setAttribute('data-id', photoId);
            if (editable) {
                photoItem.innerHTML = `
                <img src="${photo}" alt="Дополнительное фото">
                <button class="remove-photo" onclick="removeAdditionalPhoto(this, '${photoId}')">×</button>
                `;
            }else{
                photoItem.innerHTML = `
                <img src="${photo}" alt="Дополнительное фото">
                `;
            }
            grid.appendChild(photoItem);

            existingAdditionalPhotos.push({
                id: photoId,
                url: photo
            });
        });
    }
}

// Сброс фото при закрытии модалки
function resetPhotos() {
    const mainPhotoImg = document.getElementById('main_photo_img');
    const mainPhotoInput = document.getElementById('vhc_modal_main_photo');
    const previewPlaceholder = document.querySelector('#main_photo_preview .preview-placeholder');
    const grid = document.getElementById('additional_photos_grid');

    if (mainPhotoImg) {
        mainPhotoImg.src = '';
        mainPhotoImg.classList.remove('active');
    }
    if (mainPhotoInput) mainPhotoInput.value = '';
    if (previewPlaceholder) previewPlaceholder.classList.remove('hide');
    if (grid) grid.innerHTML = '';

    mainPhotoFile = null;
    additionalPhotos = [];
    existingMainPhoto = null;
    existingAdditionalPhotos = [];
}

// Получение данных фото для отправки
function getPhotosData() {
    return {
        main_photo: mainPhotoFile,
        existing_main_photo: existingMainPhoto,
        additional_photos: additionalPhotos.map(p => p.file),
        existing_additional_photos: existingAdditionalPhotos.map(p => p.url)
    };
}

// ===== ОСНОВНЫЕ ФУНКЦИИ =====

function initVehicles() {
    for (let element in vhc_modal_elements) {
        vhc_modal_elements[element] = document.getElementById(`${element}`);
    }
    // ДОБАВЛЕНО: инициализация фото
    initPhotoPreview();
    console.log(vhc_modal_elements);
}

async function vehicleView(vehicleId) {
    if (vehicleId === undefined || vehicleId === null) {
        Toast.warning("Внимание [1]: Обнаружено изменение данных. Перезагрузите страницу.");
        return;
    }
    if (!Number.isInteger(vehicleId)) {
        Toast.warning("Внимание [2]: Обнаружено изменение данных. Перезагрузите страницу.");
        return;
    }
    let vehicle_modal = document.getElementById("vhc_modal");
    if (vehicle_modal) {
        const sessionId = getCookie('session_id');
        const fingerprint = await collectFingerPrint();
        $.post("../../server/post/adminVehicleHandler.php", {
            "type": "getById",
            "vehicleId": vehicleId,
            "session_id": sessionId,
            "fingerprint": fingerprint
        }, function (data) {
            var data_parsed = JSON.parse(data);
            if (data_parsed.response != null) {
                let vehicleData = JSON.parse(data_parsed.response.message);
                vhc_modal_elements.vhc_modal_title.innerHTML = "Просмотр транспорта";
                prepareVehicleModal(false);
                prepareModalVehicleData(vehicleData);
                // ДОБАВЛЕНО: загрузка существующих фото
                loadExistingPhotos(vehicleData, false);
                vhc_modal_type = 2;
                vehicle_modal.classList.add("active");
                document.body.style.overflow = "hidden";
                opened_vehicle = vehicleId;
            } else if (data_parsed.error != null && data_parsed.error.code != null && data_parsed.error.message != null) {
                Toast.error(`Ошибка сервера [${data_parsed.error.code}]: ${data_parsed.error.message}`);
            }
        });
    } else {
        Toast.warning("Внимание [3]: Обнаружено изменение данных. Перезагрузите страницу.");
    }
}

async function vehicleEdit(vehicleId) {
    if (vehicleId === undefined || vehicleId === null) {
        Toast.warning("Внимание [1]: Обнаружено изменение данных. Перезагрузите страницу.");
        return;
    }
    if (!Number.isInteger(vehicleId)) {
        Toast.warning("Внимание [2]: Обнаружено изменение данных. Перезагрузите страницу.");
        return;
    }
    let vehicle_modal = document.getElementById("vhc_modal");
    if (vehicle_modal) {
        const sessionId = getCookie('session_id');
        const fingerprint = await collectFingerPrint();
        $.post("../../server/post/adminVehicleHandler.php", {
            "type": "getById",
            "vehicleId": vehicleId,
            "session_id": sessionId,
            "fingerprint": fingerprint
        }, function (data) {
            var data_parsed = JSON.parse(data);
            if (data_parsed.response != null) {
                let vehicleData = JSON.parse(data_parsed.response.message);
                vhc_modal_elements.vhc_modal_title.innerHTML = "Редактирование транспорта";
                prepareVehicleModal(true);
                prepareModalVehicleData(vehicleData);
                // ДОБАВЛЕНО: загрузка существующих фото
                loadExistingPhotos(vehicleData);
                vhc_modal_type = 3;
                vehicle_modal.classList.add("active");
                document.body.style.overflow = "hidden";
                opened_vehicle = vehicleId;
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

async function vehicleDelete(vehicleId) {
    if (confirm("Вы действительно хотите удалить автомобиль?")) {
        // Здесь будет логика удаления
        let tr_element = document.getElementById("tr_" + vehicleId);
        if (tr_element) {
            tr_element.remove();
        }
        const sessionId = getCookie('session_id');
        const fingerprint = await collectFingerPrint();
        $.post("../../server/post/adminVehicleHandler.php", {
            "type": "deleteById",
            "vehicleId": vehicleId,
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

function vehicleAdd() {
    let vehicle_modal = document.getElementById("vhc_modal");
    if (vehicle_modal){
        opened_vehicle = 0;
        vhc_modal_type = 1;
        vhc_modal_elements.vhc_modal_title.innerHTML = "Добавление транспорта";
        prepareVehicleModal(true);
        vhc_modal_elements.vhc_modal_creation_div.style.display = "none";
        vhc_modal_elements.vhc_modal_updated_div.style.display = "none";
        // ДОБАВЛЕНО: сброс фото при добавлении
        resetPhotos();
        vehicle_modal.classList.add("active");
        document.body.style.overflow = "hidden";
    }
}

async function vehicleSave() {
    if (opened_vehicle != null) {
        let vehicle_modal = document.getElementById("vhc_modal");
        if (vehicle_modal) {
            if (vehicle_modal.classList.contains("active")) {
                if (!vhc_modal_elements.vhc_modal_name.hasAttribute("disabled")) {
                    let vehicleData = validateVehicleData();
                    if (vehicleData === false) {
                        return;
                    }
                    const photosData = getPhotosData();
                    vehicleData["existing_main_photo"] = photosData.existing_main_photo;
                    vehicleData["existing_additional_photos"] = photosData.existing_additional_photos;

                    // Создаем FormData
                    var formData = new FormData();
                    var td_action = 0;

                    if (vhc_modal_type === 1) {
                        formData.append("type", "addVehicle");
                    } else if (vhc_modal_type === 3) {
                        formData.append("type", "editVehicle");
                        if (opened_vehicle != null && opened_vehicle > 0) {
                            td_action = 1;
                            vehicleData["vehicleId"] = opened_vehicle;
                        } else {
                            Toast.warning("Внимание: Обнаружено изменение временных данных. Перезагрузите страницу.");
                            return;
                        }
                    } else {
                        Toast.error("Нет доступа");
                        return;
                    }

                    formData.append("vehicleData", JSON.stringify(vehicleData));

                    // Добавляем файлы
                    if (photosData.main_photo != null) {
                        formData.append("main_photo[]", photosData.main_photo);
                    }
                    if (photosData.additional_photos && photosData.additional_photos.length) {
                        for (var i = 0; i < photosData.additional_photos.length; i++) {
                            formData.append("additional_photos[]", photosData.additional_photos[i]);
                        }
                    }

                    const sessionId = getCookie('session_id');
                    const fingerprint = await collectFingerPrint();
                    formData.append("session_id", sessionId);
                    formData.append("fingerprint", fingerprint);
                    // Отправляем через $.ajax
                    $.ajax({
                        url: "../../server/post/adminVehicleHandler.php",
                        type: "POST",
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function (data) {
                            let data_parsed = JSON.parse(data);
                            if (data_parsed.response != null && data_parsed.response.code != null && data_parsed.response.message != null) {
                                Toast.success("Данные сохранены!");

                                // При добавлении нового автомобиля
                                if (td_action === 0) {
                                    let needleData = JSON.parse(data_parsed.response.message);
                                    if (needleData.vehicleId != null && needleData.image_path != null) {
                                        const newVehicleId = needleData.vehicleId;

                                        // Создаём новую строку в таблице
                                        const newRow = createVehicleRow(newVehicleId, vehicleData, needleData.image_path);

                                        // Добавляем в таблицу
                                        const tbody = document.querySelector('.data-table tbody');
                                        if (tbody) {
                                            tbody.appendChild(newRow);
                                        }

                                        // Убираем сообщение "Нет данных", если оно есть
                                        const noDataRow = tbody.querySelector('.no-data-message');
                                        if (noDataRow) {
                                            noDataRow.remove();
                                        }
                                    }
                                }
                                // При редактировании существующего автомобиля
                                else if (td_action === 1) {
                                    if (opened_vehicle != null) {
                                        let needleData = JSON.parse(data_parsed.response.message);
                                        if (needleData.image_path != null) {
                                            updateVehicleRow(opened_vehicle, vehicleData, needleData.image_path);
                                        } else {
                                            updateVehicleRow(opened_vehicle, vehicleData);
                                        }
                                    }
                                }

                                closeVehicleModal();
                                vehicleModalClear();
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

// Функция для создания новой строки таблицы
function createVehicleRow(vehicleId, vehicleData, image_path = null) {
    const tr = document.createElement('tr');
    tr.id = `tr_${vehicleId}`;

    // Получаем название категории
    const categoryNames = {
        1: 'Катафалк',
        2: 'Автобус для гостей',
        3: 'Легковой автомобиль',
        4: 'Спецтранспорт'
    };
    const categoryName = categoryNames[vehicleData.category] || 'Не определено';

    // Статус (активен/неактивен)
    const isActive = vehicleData.isActive !== 2;
    const statusClass = isActive ? 'active' : 'inactive';
    const statusText = isActive ? 'Активен' : 'Не активен';

    // Форматируем цену
    const formattedPrice = vehicleData.price.toLocaleString('ru-RU');

    // Заглушка для фото (пока не загрузилось)
    const imagePlaceholder = '../src/images/no_image.jpg';
    const imagePath = `../src/images/vehicles/${image_path}/main.jpg`;

    tr.innerHTML = `
        <td>${vehicleId}</td>
        <td><img src="${imagePath}" class="table-thumb" onerror="this.src='${imagePlaceholder}'" alt="${vehicleData.name}"></td>
        <td>${vehicleData.name}</td>
        <td>${categoryName}</td>
        <td>${formattedPrice} ₽</td>
        <td>${vehicleData.total_stock}</td>
        <td><span class="status ${statusClass}">${statusText}</span></td>
        <td>
            <button class="btn-icon view" onclick="vehicleView(${vehicleId})">👁️</button>
            <button class="btn-icon edit" onclick="vehicleEdit(${vehicleId})">✏️</button>
            <button class="btn-icon delete" onclick="vehicleDelete(${vehicleId})">🗑️</button>
        </td>
    `;

    return tr;
}

// Функция для обновления существующей строки
function updateVehicleRow(vehicleId, vehicleData, image_path = null) {
    const row = document.getElementById(`tr_${vehicleId}`);
    if (!row) return;

    // Получаем название категории
    const categoryNames = {
        1: 'Катафалк',
        2: 'Автобус для гостей',
        3: 'Легковой автомобиль',
        4: 'Спецтранспорт'
    };
    const categoryName = categoryNames[vehicleData.category] || 'Не определено';

    const statusClass = vehicleData.isActive ? 'active' : 'inactive';
    const statusText = vehicleData.isActive ? 'Активен' : 'Не активен';

    // Форматируем цену
    const formattedPrice = vehicleData.price.toLocaleString('ru-RU');

    // Обновляем ячейки
    const cells = row.cells;
    if (cells.length >= 8) {
        const imgElement = cells[1].querySelector('img');
        if (imgElement) {
            console.log(image_path);
            const timestamp = new Date().getTime();
            imgElement.src = `${image_path}/main.jpg?t=${timestamp}`;
        }
        // Имя
        cells[2].textContent = vehicleData.name;
        // Категория
        cells[3].textContent = categoryName;
        // Цена
        cells[4].textContent = `${formattedPrice} ₽`;
        // Количество
        cells[5].textContent = vehicleData.total_stock;
        // Статус
        cells[6].innerHTML = `<span class="status ${statusClass}">${statusText}</span>`;

        // Обновляем атрибуты кнопок (на случай, если ID не изменился)
        const viewBtn = cells[7].querySelector('.btn-icon.view');
        const editBtn = cells[7].querySelector('.btn-icon.edit');
        const deleteBtn = cells[7].querySelector('.btn-icon.delete');

        if (viewBtn) viewBtn.setAttribute('onclick', `vehicleView(${vehicleId})`);
        if (editBtn) editBtn.setAttribute('onclick', `vehicleEdit(${vehicleId})`);
        if (deleteBtn) deleteBtn.setAttribute('onclick', `vehicleDelete(${vehicleId})`);
    }
}


function prepareVehicleModal(editable = true) {
    if (editable) {
        vhc_modal_elements.vhc_modal_title.removeAttribute("disabled");
        vhc_modal_elements.vhc_modal_name.removeAttribute("disabled");
        vhc_modal_elements.vhc_modal_category.removeAttribute("disabled");
        vhc_modal_elements.vhc_modal_price.removeAttribute("disabled");
        vhc_modal_elements.vhc_modal_stock.removeAttribute("disabled");
        vhc_modal_elements.vhc_modal_dsc_short.removeAttribute("disabled");
        vhc_modal_elements.vhc_modal_dsc_full.removeAttribute("disabled");
        vhc_modal_elements.vhc_modal_color.removeAttribute("disabled");
        vhc_modal_elements.vhc_modal_seats.removeAttribute("disabled");
        vhc_modal_elements.vhc_modal_status.removeAttribute("disabled");
        vhc_modal_elements.vhc_modal_add_main_img.style.display = "block";
        vhc_modal_elements.vhc_modal_add_additional_img.style.display = "block";
        vhc_modal_elements.vhc_modal_save.style.display = "block";
    }else{
        vhc_modal_elements.vhc_modal_title.setAttribute("disabled", "true");
        vhc_modal_elements.vhc_modal_name.setAttribute("disabled", "true");
        vhc_modal_elements.vhc_modal_category.setAttribute("disabled", "true");
        vhc_modal_elements.vhc_modal_price.setAttribute("disabled", "true");
        vhc_modal_elements.vhc_modal_stock.setAttribute("disabled", "true");
        vhc_modal_elements.vhc_modal_dsc_short.setAttribute("disabled", "true");
        vhc_modal_elements.vhc_modal_dsc_full.setAttribute("disabled", "true");
        vhc_modal_elements.vhc_modal_color.setAttribute("disabled", "true");
        vhc_modal_elements.vhc_modal_seats.setAttribute("disabled", "true");
        vhc_modal_elements.vhc_modal_status.setAttribute("disabled", "true");
        vhc_modal_elements.vhc_modal_add_main_img.style.display = "none";
        vhc_modal_elements.vhc_modal_add_additional_img.style.display = "none";
        vhc_modal_elements.vhc_modal_save.style.display = "none";
    }
}

function prepareModalVehicleData(vehicleData) {
    vhc_modal_elements.vhc_modal_name.value = vehicleData.name || '';
    vhc_modal_elements.vhc_modal_category.selectedIndex = (vehicleData.category || 1);
    vhc_modal_elements.vhc_modal_price.value = vehicleData.price || 0;
    vhc_modal_elements.vhc_modal_stock.value = vehicleData.total_stock || 1;
    vhc_modal_elements.vhc_modal_dsc_short.value = vehicleData.description_short || '';
    vhc_modal_elements.vhc_modal_dsc_full.value = vehicleData.description_full || '';
    vhc_modal_elements.vhc_modal_updated.value = vehicleData.updated_at || '';
    vhc_modal_elements.vhc_modal_creation.value = vehicleData.created_at || '';
    vhc_modal_elements.vhc_modal_color.value = vehicleData.color || '';
    vhc_modal_elements.vhc_modal_seats.value = vehicleData.seats || 2;
    vhc_modal_elements.vhc_modal_status.selectedIndex = (vehicleData.is_active + 1 || 1);
}

function vehicleModalClear() {
    vhc_modal_elements.vhc_modal_name.value = null;
    vhc_modal_elements.vhc_modal_category.selectedIndex = 0;
    vhc_modal_elements.vhc_modal_price.value = null;
    vhc_modal_elements.vhc_modal_stock.value = null;
    vhc_modal_elements.vhc_modal_dsc_short.value = null;
    vhc_modal_elements.vhc_modal_dsc_full.value = null;
    vhc_modal_elements.vhc_modal_updated.value = null;
    vhc_modal_elements.vhc_modal_creation.value = null;
    vhc_modal_elements.vhc_modal_color.value = null;
    vhc_modal_elements.vhc_modal_seats.value = null;
    vhc_modal_elements.vhc_modal_status.selectedIndex = 0;
    // ДОБАВЛЕНО: сброс фото
    resetPhotos();
}

function vehicleClose() {
    let vehicle_modal = document.getElementById("vhc_modal");
    if (vehicle_modal) {
        if (!vhc_modal_elements.vhc_modal_name.hasAttribute("disabled")) {
            if (confirm('Если вы закроете окно, ваши изменения не сохранятся. Вы уверены?')) {
                closeVehicleModal();
                vehicleModalClear();
            }
        }else{
            closeVehicleModal()
            vehicleModalClear();
        }
    }
}

function closeVehicleModal() {
    let vehicle_modal = document.getElementById("vhc_modal");
    if (vehicle_modal) {
        vehicle_modal.classList.remove("active");
        document.body.style.overflow = "auto";
        opened_vehicle = null;
        let container = vehicle_modal.childNodes[1];
        if (container instanceof Element) {
            container.scrollTop = 0;
        }
    }
}

function validateVehicleData() {
    // Проверка названия (не более 255 символов, не пустое)
    if (!vhc_modal_elements.vhc_modal_name.value.trim()) {
        showHint(vhc_modal_elements.vhc_modal_name, 'Название модели обязательно для заполнения');
        return false;
    }
    if (vhc_modal_elements.vhc_modal_name.value.length > 255) {
        showHint(vhc_modal_elements.vhc_modal_name, 'Название не должно превышать 255 символов');
        return false;
    }

    // Проверка категории (select: 1-4)
    if (vhc_modal_elements.vhc_modal_category.selectedIndex === -1 || vhc_modal_elements.vhc_modal_category.selectedIndex === 0) {
        showHint(vhc_modal_elements.vhc_modal_category, 'Выберите категорию автомобиля');
        return false;
    }
    if (vhc_modal_elements.vhc_modal_category.selectedIndex < 1 || vhc_modal_elements.vhc_modal_category.selectedIndex > 4) {
        showHint(vhc_modal_elements.vhc_modal_category, 'Обнаружено изменение структуры страницы. Пожалуйста, перезагрузите страницу');
        return false;
    }
    const category = vhc_modal_elements.vhc_modal_category.selectedIndex;

    // Проверка, добавлено ли основное изображение
    if (!validateMainPhoto()) {
        return false;
    }

    // Проверка цены (не более 10 цифр, положительное число)
    let price = 0;
    if (!vhc_modal_elements.vhc_modal_price.value) {
        showHint(vhc_modal_elements.vhc_modal_price, 'Цена обязательна для заполнения');
        return false;
    }
    price = Number(vhc_modal_elements.vhc_modal_price.value);
    if (isNaN(price) || price < 0) {
        showHint(vhc_modal_elements.vhc_modal_price, 'Введите корректную цену (положительное число)');
        return false;
    }
    if (price.toString().length > 10) {
        showHint(vhc_modal_elements.vhc_modal_price, 'Цена не должна превышать 10 цифр');
        return false;
    }
    price = Math.floor(price);

    // Проверка цвета (не более 50 символов)
    if (!vhc_modal_elements.vhc_modal_color.value.trim()) {
        showHint(vhc_modal_elements.vhc_modal_color, 'Цвет обязательно для заполнения');
        return false;
    }
    if (vhc_modal_elements.vhc_modal_color.value.length > 50) {
        showHint(vhc_modal_elements.vhc_modal_color, 'Цвет не должен превышать 50 символов');
        return false;
    }

    // Проверка количества мест (TINYINT: 1-255)
    let seats = 0;
    if (!vhc_modal_elements.vhc_modal_seats.value) {
        showHint(vhc_modal_elements.vhc_modal_seats, 'Количество мест обязательно для заполнения');
        return false;
    }
    seats = Number(vhc_modal_elements.vhc_modal_seats.value);
    if (isNaN(seats) || seats < 1 || seats > 255 || !Number.isInteger(seats)) {
        showHint(vhc_modal_elements.vhc_modal_seats, 'Введите корректное количество мест (1-255)');
        return false;
    }

    // Проверка количества в наличии
    let stock = 1;
    if (!vhc_modal_elements.vhc_modal_stock.value) {
        showHint(vhc_modal_elements.vhc_modal_stock, 'Количество в наличии обязательно для заполнения');
        return false;
    }
    stock = Number(vhc_modal_elements.vhc_modal_stock.value);
    if (isNaN(stock) || stock < 1 || !Number.isInteger(stock)) {
        showHint(vhc_modal_elements.vhc_modal_stock, 'Введите корректное количество (целое положительное число)');
        return false;
    }

    // Проверка короткого описания
    if (!vhc_modal_elements.vhc_modal_dsc_short.value.trim()) {
        showHint(vhc_modal_elements.vhc_modal_dsc_short, 'Короткое описание обязательно для заполнения');
        return false;
    }

    // Проверка полного описания
    if (!vhc_modal_elements.vhc_modal_dsc_full.value.trim()) {
        showHint(vhc_modal_elements.vhc_modal_dsc_full, 'Полное описание обязательно для заполнения');
        return false;
    }


    // Проверка статуса (select: 1-2)
    if (vhc_modal_elements.vhc_modal_status.selectedIndex === -1 || vhc_modal_elements.vhc_modal_status.selectedIndex === 0) {
        showHint(vhc_modal_elements.vhc_modal_status, 'Выберите статус автомобиля');
        return false;
    }
    if (vhc_modal_elements.vhc_modal_status.selectedIndex < 1 || vhc_modal_elements.vhc_modal_status.selectedIndex > 2) {
        showHint(vhc_modal_elements.vhc_modal_status, 'Обнаружено изменение структуры страницы. Пожалуйста, перезагрузите страницу');
        return false;
    }
    const isActive = vhc_modal_elements.vhc_modal_status.selectedIndex;

    // Возвращаем объект с валидными данными
    return {
        name: vhc_modal_elements.vhc_modal_name.value.trim(),
        category: category,
        price: price,
        color: vhc_modal_elements.vhc_modal_color.value.trim(),
        seats: seats,
        total_stock: stock,
        description_short: vhc_modal_elements.vhc_modal_dsc_short.value.trim(),
        description_full: vhc_modal_elements.vhc_modal_dsc_full.value.trim(),
        isActive: isActive - 1
    };
}

function validateMainPhoto() {
    // Получаем элементы
    const mainPhotoInput = document.getElementById('vhc_modal_main_photo');
    const mainPhotoImg = document.getElementById('main_photo_img');

    // Проверяем, есть ли уже существующее фото (при редактировании)
    const hasExistingPhoto = mainPhotoImg && mainPhotoImg.src &&
        mainPhotoImg.src !== '' &&
        !mainPhotoImg.src.includes('blob:') &&
        mainPhotoImg.classList.contains('active');

    // Проверяем, загружено ли новое фото через input
    const hasNewPhoto = mainPhotoInput && mainPhotoInput.files && mainPhotoInput.files.length > 0;

    // Если есть существующее фото или новое загруженное — валидация пройдена
    if (hasExistingPhoto || hasNewPhoto) {
        return true;
    }

    // Если нет ни того, ни другого — показываем ошибку
    const photoArea = document.getElementById('main_photo_area');
    if (photoArea) {
        showHint(photoArea, 'Основное фото обязательно для загрузки');
    } else if (mainPhotoInput) {
        showHint(mainPhotoInput, 'Загрузите основное фото автомобиля');
    }

    return false;
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