let modal_elements = {
    vhc_modal_title: null,
    vhc_modal_name: null,
    vhc_modal_category: null,
    vhc_modal_option_hearse: null,
    vhc_modal_option_bus: null,
    vhc_modal_option_passenger: null,
    vhc_modal_option_special: null,
    vhc_modal_price: null,
    vhc_modal_year: null,
    vhc_modal_stock: null,
    vhc_modal_dsc_short: null,
    vhc_modal_dsc_full: null,
    vhc_modal_save: null,
    vhc_modal_creation: null,
    vhc_modal_updated: null,
    vhc_modal_updated_div: null,
    vhc_modal_creation_div: null,
    vhc_modal_color: null,
    vhc_modal_transmission: null,
    vhc_modal_option_manual: null,
    vhc_modal_option_automatic: null,
    vhc_modal_drive: null,
    vhc_modal_option_back: null,
    vhc_modal_option_front: null,
    vhc_modal_option_full: null,
    vhc_modal_fuel: null,
    vhc_modal_option_petrol: null,
    vhc_modal_option_diesel: null,
    vhc_modal_seats: null,
    vhc_modal_mileage: null,
    vhc_modal_vin: null,
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
        additionalPhotos = additionalPhotos.filter(p => p.id != photoId);
        existingAdditionalPhotos = existingAdditionalPhotos.filter(p => p.id != photoId);
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
    for (let element in modal_elements) {
        modal_elements[element] = document.getElementById(`${element}`);
    }
    // ДОБАВЛЕНО: инициализация фото
    initPhotoPreview();
    console.log(modal_elements);
}

function vehicleView(vehicleId) {
    if (vehicleId === undefined || vehicleId === null){
        console.log("null");
        return;
    }
    if (!Number.isInteger(vehicleId)) {
        console.log("int");
        return;
    }
    let vehicle_modal = document.getElementById("vhc_modal");
    if (vehicle_modal) {
        $.post("../../server/post/adminVehicleHandler.php", {"type": "getById", "vehicleId": vehicleId}, function (data) {
            var data_parsed = JSON.parse(data);
            if (data_parsed.response != null) {
                let vehicleData = JSON.parse(data_parsed.response.message);
                modal_elements.vhc_modal_title.innerHTML = "Просмотр транспорта";
                prepareVehicleModal(false);
                prepareModalVehicleData(vehicleData);
                // ДОБАВЛЕНО: загрузка существующих фото
                loadExistingPhotos(vehicleData, false);
                vhc_modal_type = 2;
                vehicle_modal.classList.add("active");
                document.body.style.overflow = "hidden";
                opened_vehicle = vehicleId;
            }
        });
    }
}

function vehicleEdit(vehicleId) {
    if (vehicleId === undefined || vehicleId === null){
        console.log("null");
        return;
    }
    if (!Number.isInteger(vehicleId)) {
        console.log("int");
        return;
    }
    let vehicle_modal = document.getElementById("vhc_modal");
    if (vehicle_modal) {
        $.post("../../server/post/adminVehicleHandler.php", {"type": "getById", "vehicleId": vehicleId}, function (data) {
            var data_parsed = JSON.parse(data);
            if (data_parsed.response != null) {
                let vehicleData = JSON.parse(data_parsed.response.message);
                modal_elements.vhc_modal_title.innerHTML = "Редактирование транспорта";
                prepareVehicleModal(true);
                prepareModalVehicleData(vehicleData);
                // ДОБАВЛЕНО: загрузка существующих фото
                loadExistingPhotos(vehicleData);
                vhc_modal_type = 3;
                vehicle_modal.classList.add("active");
                document.body.style.overflow = "hidden";
                opened_vehicle = vehicleId;
            }
        });
    }
}

function vehicleDelete(vehicleId) {
    if (confirm("Вы действительно хотите удалить автомобиль?")) {
        // Здесь будет логика удаления
        let tr_element = document.getElementById("tr_" + vehicleId);
        if (tr_element) {
            tr_element.remove();
        }
        $.post("../../server/post/adminVehicleHandler.php", {"type": "deleteById", "vehicleId": vehicleId}, function (data){
            try {
                var data_parsed = JSON.parse(data);
                console.log(data_parsed);
                if (data_parsed.response != null){
                    console.log("Success delete");
                }else if (data_parsed.error != null){
                    console.log("An error occurred");
                }
            }catch(e) {
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
        modal_elements.vhc_modal_title.innerHTML = "Добавление транспорта";
        prepareVehicleModal(true);
        modal_elements.vhc_modal_creation_div.style.display = "none";
        modal_elements.vhc_modal_updated_div.style.display = "none";
        // ДОБАВЛЕНО: сброс фото при добавлении
        resetPhotos();
        vehicle_modal.classList.add("active");
        document.body.style.overflow = "hidden";
    }
}

function vehicleSave() {
    if (opened_vehicle != null) {
        let vehicle_modal = document.getElementById("vhc_modal");
        if (vehicle_modal) {
            if (vehicle_modal.classList.contains("active")) {
                if (!modal_elements.vhc_modal_name.hasAttribute("disabled")) {
                    let vehicleData = validateVehicleData();
                    if (vehicleData === false) {
                        return;
                    }
                    // ДОБАВЛЕНО: получение данных фото
                    const photosData = getPhotosData();
                    console.log(vehicleData.category);
                    vehicleData["existing_main_photo"] = photosData.existing_main_photo;
                    vehicleData["existing_additional_photos"] = photosData.existing_additional_photos;

                    console.log("Фотографии авто:", photosData);
                    console.log("Сохраняемые данные:", vehicleData);
                    // Создаем FormData
                    var formData = new FormData();
                    if (vhc_modal_type === 1) {
                        formData.append("type", "addVehicle");
                    }else if (vhc_modal_type === 3){
                        formData.append("type", "editVehicle");
                        if (opened_vehicle != null && opened_vehicle > 0) {
                            vehicleData["vehicleId"] = opened_vehicle;
                        }else{
                            console.log("Изменение данных");
                            return;
                        }
                    }else{
                        console.log("Нет доступа");
                        return;
                    }
                    formData.append("vehicleData", JSON.stringify(vehicleData));
                    // Добавляем файлы из вашего массива vehicleData
                    // Предполагается, что в vehicleData есть поле с файлами
                    if (photosData.main_photo != null) {
                        formData.append("main_photo[]", photosData.main_photo);
                    }
                    if (photosData.additional_photos && photosData.additional_photos.length) {
                        for (var i = 0; i < photosData.additional_photos.length; i++) {
                            formData.append("additional_photos[]", photosData.additional_photos[i]);
                        }
                    }
                    // Отправляем через $.ajax, поскольку у нас в массиве есть фото, а обычный $.post для этого не подходит
                    $.ajax({
                        url: "../../server/post/adminVehicleHandler.php",
                        type: "POST",
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(data) {
                            console.log("Успех:", data);
                        },
                        error: function(xhr, status, error) {
                            console.error("Ошибка:", error);
                        }
                    });
                    closeVehicleModal();
                    vehicleModalClear();
                }else console.log("vehicle inputs has 'disabled' attribute")
            }else console.log("vehicle_modal is not active")
        }else console.log("if vehicle_modal false")
    }else console.log("opened_modal = null")
}

function prepareVehicleModal(editable = true) {
    if (editable) {
        modal_elements.vhc_modal_title.removeAttribute("disabled");
        modal_elements.vhc_modal_name.removeAttribute("disabled");
        modal_elements.vhc_modal_category.removeAttribute("disabled");
        modal_elements.vhc_modal_price.removeAttribute("disabled");
        modal_elements.vhc_modal_year.removeAttribute("disabled");
        modal_elements.vhc_modal_stock.removeAttribute("disabled");
        modal_elements.vhc_modal_dsc_short.removeAttribute("disabled");
        modal_elements.vhc_modal_dsc_full.removeAttribute("disabled");
        modal_elements.vhc_modal_color.removeAttribute("disabled");
        modal_elements.vhc_modal_transmission.removeAttribute("disabled");
        modal_elements.vhc_modal_drive.removeAttribute("disabled");
        modal_elements.vhc_modal_vin.removeAttribute("disabled");
        modal_elements.vhc_modal_seats.removeAttribute("disabled");
        modal_elements.vhc_modal_fuel.removeAttribute("disabled");
        modal_elements.vhc_modal_mileage.removeAttribute("disabled");
        modal_elements.vhc_modal_status.removeAttribute("disabled");
        modal_elements.vhc_modal_add_main_img.style.display = "block";
        modal_elements.vhc_modal_add_additional_img.style.display = "block";
        modal_elements.vhc_modal_save.style.display = "block";
    }else{
        modal_elements.vhc_modal_title.setAttribute("disabled", "true");
        modal_elements.vhc_modal_name.setAttribute("disabled", "true");
        modal_elements.vhc_modal_category.setAttribute("disabled", "true");
        modal_elements.vhc_modal_price.setAttribute("disabled", "true");
        modal_elements.vhc_modal_year.setAttribute("disabled", "true");
        modal_elements.vhc_modal_stock.setAttribute("disabled", "true");
        modal_elements.vhc_modal_dsc_short.setAttribute("disabled", "true");
        modal_elements.vhc_modal_dsc_full.setAttribute("disabled", "true");
        modal_elements.vhc_modal_color.setAttribute("disabled", "true");
        modal_elements.vhc_modal_transmission.setAttribute("disabled", "true");
        modal_elements.vhc_modal_drive.setAttribute("disabled", "true");
        modal_elements.vhc_modal_vin.setAttribute("disabled", "true");
        modal_elements.vhc_modal_mileage.setAttribute("disabled", "true");
        modal_elements.vhc_modal_seats.setAttribute("disabled", "true");
        modal_elements.vhc_modal_fuel.setAttribute("disabled", "true");
        modal_elements.vhc_modal_status.setAttribute("disabled", "true");
        modal_elements.vhc_modal_add_main_img.style.display = "none";
        modal_elements.vhc_modal_add_additional_img.style.display = "none";
        modal_elements.vhc_modal_save.style.display = "none";
    }
}

function prepareModalVehicleData(vehicleData) {
    modal_elements.vhc_modal_name.value = vehicleData.name || '';
    modal_elements.vhc_modal_category.selectedIndex = (vehicleData.category || 1);
    modal_elements.vhc_modal_price.value = vehicleData.price || 0;
    modal_elements.vhc_modal_year.value = vehicleData.creation_year || 2022;
    modal_elements.vhc_modal_stock.value = vehicleData.total_stock || 1;
    modal_elements.vhc_modal_dsc_short.value = vehicleData.description_short || '';
    modal_elements.vhc_modal_dsc_full.value = vehicleData.description_full || '';
    modal_elements.vhc_modal_updated.value = vehicleData.updated_at || '';
    modal_elements.vhc_modal_creation.value = vehicleData.created_at || '';
    modal_elements.vhc_modal_color.value = vehicleData.color || '';
    modal_elements.vhc_modal_transmission.selectedIndex = (vehicleData.transmission || 1);
    console.log(vehicleData.transmission || 1)
    modal_elements.vhc_modal_drive.selectedIndex = (vehicleData.drive_type || 1);
    modal_elements.vhc_modal_vin.value = vehicleData.vin || '';
    modal_elements.vhc_modal_mileage.value = vehicleData.mileage || 0;
    modal_elements.vhc_modal_seats.value = vehicleData.seats || 2;
    modal_elements.vhc_modal_fuel.selectedIndex = (vehicleData.fuel || 1);
    modal_elements.vhc_modal_status.selectedIndex = (vehicleData.is_active + 1 || 1);
}

function vehicleModalClear() {
    modal_elements.vhc_modal_name.value = null;
    modal_elements.vhc_modal_category.selectedIndex = 0;
    modal_elements.vhc_modal_price.value = null;
    modal_elements.vhc_modal_year.value = null;
    modal_elements.vhc_modal_stock.value = null;
    modal_elements.vhc_modal_dsc_short.value = null;
    modal_elements.vhc_modal_dsc_full.value = null;
    modal_elements.vhc_modal_updated.value = null;
    modal_elements.vhc_modal_creation.value = null;
    modal_elements.vhc_modal_color.value = null;
    modal_elements.vhc_modal_transmission.selectedIndex = 0;
    modal_elements.vhc_modal_drive.selectedIndex = 0;
    modal_elements.vhc_modal_vin.value = null;
    modal_elements.vhc_modal_mileage.value = null;
    modal_elements.vhc_modal_seats.value = null;
    modal_elements.vhc_modal_fuel.selectedIndex = 0;
    modal_elements.vhc_modal_status.selectedIndex = 0;
    // ДОБАВЛЕНО: сброс фото
    resetPhotos();
}

function vehicleClose() {
    let vehicle_modal = document.getElementById("vhc_modal");
    if (vehicle_modal) {
        if (!modal_elements.vhc_modal_name.hasAttribute("disabled")) {
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
    if (!modal_elements.vhc_modal_name.value.trim()) {
        showHint(modal_elements.vhc_modal_name, 'Название модели обязательно для заполнения');
        return false;
    }
    if (modal_elements.vhc_modal_name.value.length > 255) {
        showHint(modal_elements.vhc_modal_name, 'Название не должно превышать 255 символов');
        return false;
    }

    // Проверка категории (select: 1-4)
    if (modal_elements.vhc_modal_category.selectedIndex === -1 || modal_elements.vhc_modal_category.selectedIndex === 0) {
        showHint(modal_elements.vhc_modal_category, 'Выберите категорию автомобиля');
        return false;
    }
    if (modal_elements.vhc_modal_category.selectedIndex < 1 || modal_elements.vhc_modal_category.selectedIndex > 4) {
        showHint(modal_elements.vhc_modal_category, 'Обнаружено изменение структуры страницы. Пожалуйста, перезагрузите страницу');
        return false;
    }
    const category = modal_elements.vhc_modal_category.selectedIndex;

    // Проверка, добавлено ли основное изображение
    if (!validateMainPhoto()) {
        return false;
    }

    // Проверка цены (не более 10 цифр, положительное число)
    let price = 0;
    if (!modal_elements.vhc_modal_price.value) {
        showHint(modal_elements.vhc_modal_price, 'Цена обязательна для заполнения');
        return false;
    }
    price = Number(modal_elements.vhc_modal_price.value);
    if (isNaN(price) || price < 0) {
        showHint(modal_elements.vhc_modal_price, 'Введите корректную цену (положительное число)');
        return false;
    }
    if (price.toString().length > 10) {
        showHint(modal_elements.vhc_modal_price, 'Цена не должна превышать 10 цифр');
        return false;
    }
    price = Math.floor(price);

    // Проверка цвета (не более 50 символов)
    if (!modal_elements.vhc_modal_color.value.trim()) {
        showHint(modal_elements.vhc_modal_color, 'Цвет обязательно для заполнения');
        return false;
    }
    if (modal_elements.vhc_modal_color.value.length > 50) {
        showHint(modal_elements.vhc_modal_color, 'Цвет не должен превышать 50 символов');
        return false;
    }

    // Проверка КПП (select: 1-2)
    if (modal_elements.vhc_modal_transmission.selectedIndex === -1 || modal_elements.vhc_modal_transmission.selectedIndex === 0) {
        showHint(modal_elements.vhc_modal_transmission, 'Выберите тип коробки передач');
        return false;
    }
    if (modal_elements.vhc_modal_transmission.selectedIndex < 1 || modal_elements.vhc_modal_transmission.selectedIndex > 2) {
        showHint(modal_elements.vhc_modal_transmission, 'Обнаружено изменение структуры страницы. Пожалуйста, перезагрузите страницу');
        return false;
    }
    const transmission = modal_elements.vhc_modal_transmission.selectedIndex;

    // Проверка привода (select: 1-3)
    if (modal_elements.vhc_modal_drive.selectedIndex === -1 || modal_elements.vhc_modal_drive.selectedIndex === 0) {
        showHint(modal_elements.vhc_modal_drive, 'Выберите тип привода');
        return false;
    }
    if (modal_elements.vhc_modal_drive.selectedIndex < 1 || modal_elements.vhc_modal_drive.selectedIndex > 3) {
        showHint(modal_elements.vhc_modal_drive, 'Обнаружено изменение структуры страницы. Пожалуйста, перезагрузите страницу');
        return false;
    }
    const drive = modal_elements.vhc_modal_drive.selectedIndex;

    // Проверка топлива (select: 1-2)
    if (modal_elements.vhc_modal_fuel.selectedIndex === -1 || modal_elements.vhc_modal_fuel.selectedIndex === 0) {
        showHint(modal_elements.vhc_modal_fuel, 'Выберите тип топлива');
        return false;
    }
    if (modal_elements.vhc_modal_fuel.selectedIndex < 1 || modal_elements.vhc_modal_fuel.selectedIndex > 2) {
        showHint(modal_elements.vhc_modal_fuel, 'Обнаружено изменение структуры страницы. Пожалуйста, перезагрузите страницу');
        return false;
    }
    const fuel = modal_elements.vhc_modal_fuel.selectedIndex;

    // Проверка VIN (не более 255 символов)
    if (!modal_elements.vhc_modal_vin.value.trim()) {
        showHint(modal_elements.vhc_modal_vin, 'VIN-номер обязателен для заполнения');
        return false;
    }
    if (modal_elements.vhc_modal_vin.value.length > 255) {
        showHint(modal_elements.vhc_modal_vin, 'VIN-номер не должен превышать 255 символов');
        return false;
    }

    // Проверка количества мест (TINYINT: 1-255)
    let seats = 0;
    if (!modal_elements.vhc_modal_seats.value) {
        showHint(modal_elements.vhc_modal_seats, 'Количество мест обязательно для заполнения');
        return false;
    }
    seats = Number(modal_elements.vhc_modal_seats.value);
    if (isNaN(seats) || seats < 1 || seats > 255 || !Number.isInteger(seats)) {
        showHint(modal_elements.vhc_modal_seats, 'Введите корректное количество мест (1-255)');
        return false;
    }

    // Проверка пробега (не более 10 цифр)
    let mileage = 0;
    if (!modal_elements.vhc_modal_mileage.value) {
        showHint(modal_elements.vhc_modal_mileage, 'Пробег обязателен для заполнения');
        return false;
    }
    mileage = Number(modal_elements.vhc_modal_mileage.value);
    if (isNaN(mileage) || mileage < 0) {
        showHint(modal_elements.vhc_modal_mileage, 'Введите корректный пробег (положительное число)');
        return false;
    }
    if (mileage.toString().length > 10) {
        showHint(modal_elements.vhc_modal_mileage, 'Пробег не должен превышать 10 цифр');
        return false;
    }
    mileage = Math.floor(mileage);

    // Проверка года (реальный год)
    let year = 0;
    if (!modal_elements.vhc_modal_year.value) {
        showHint(modal_elements.vhc_modal_year, 'Год выпуска обязателен для заполнения');
        return false;
    }
    year = Number(modal_elements.vhc_modal_year.value);
    const currentYear = new Date().getFullYear();
    if (isNaN(year) || year < 1900 || year > currentYear + 1) {
        showHint(modal_elements.vhc_modal_year, `Введите корректный год (1900-${currentYear + 1})`);
        return false;
    }

    // Проверка количества в наличии
    let stock = 1;
    if (!modal_elements.vhc_modal_stock.value) {
        showHint(modal_elements.vhc_modal_stock, 'Количество в наличии обязательно для заполнения');
        return false;
    }
    stock = Number(modal_elements.vhc_modal_stock.value);
    if (isNaN(stock) || stock < 1 || !Number.isInteger(stock)) {
        showHint(modal_elements.vhc_modal_stock, 'Введите корректное количество (целое положительное число)');
        return false;
    }

    // Проверка короткого описания
    if (!modal_elements.vhc_modal_dsc_short.value.trim()) {
        showHint(modal_elements.vhc_modal_dsc_short, 'Короткое описание обязательно для заполнения');
        return false;
    }

    // Проверка полного описания
    if (!modal_elements.vhc_modal_dsc_full.value.trim()) {
        showHint(modal_elements.vhc_modal_dsc_full, 'Полное описание обязательно для заполнения');
        return false;
    }


    // Проверка статуса (select: 1-2)
    if (modal_elements.vhc_modal_status.selectedIndex === -1 || modal_elements.vhc_modal_status.selectedIndex === 0) {
        showHint(modal_elements.vhc_modal_status, 'Выберите статус автомобиля');
        return false;
    }
    if (modal_elements.vhc_modal_status.selectedIndex < 1 || modal_elements.vhc_modal_status.selectedIndex > 2) {
        showHint(modal_elements.vhc_modal_status, 'Обнаружено изменение структуры страницы. Пожалуйста, перезагрузите страницу');
        return false;
    }
    const isActive = modal_elements.vhc_modal_status.selectedIndex;

    // Возвращаем объект с валидными данными
    return {
        name: modal_elements.vhc_modal_name.value.trim(),
        category: category,
        price: price,
        color: modal_elements.vhc_modal_color.value.trim(),
        transmission: transmission ,
        drive_type: drive,
        fuel: fuel,
        vin: modal_elements.vhc_modal_vin.value.trim(),
        seats: seats,
        mileage: mileage,
        creation_year: year,
        total_stock: stock,
        description_short: modal_elements.vhc_modal_dsc_short.value.trim(),
        description_full: modal_elements.vhc_modal_dsc_full.value.trim(),
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