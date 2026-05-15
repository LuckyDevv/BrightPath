// ===== ПЕРЕМЕННЫЕ ДЛЯ МОДАЛЬНОГО ОКНА ТОВАРОВ =====
let gds_modal_elements = {
    gds_modal_title: null,
    gds_modal_name: null,
    gds_modal_category: null,
    gds_modal_material: null,
    gds_modal_sizes: null,
    gds_modal_weight: null,
    gds_modal_price: null,
    gds_modal_stock: null,
    gds_modal_dsc_short: null,
    gds_modal_dsc_full: null,
    gds_modal_main_photo: null,
    gds_modal_creation_div: null,
    gds_modal_creation: null,
    gds_modal_updated_div: null,
    gds_modal_updated: null,
    gds_modal_add_main_img: null,
    gds_modal_save: null
};

// ===== ПЕРЕМЕННЫЕ ДЛЯ ФОТО =====
let mainGoodsPhotoFile = null;
let existingGoodsMainPhoto = null;

let gds_modal_type = 0; // 1 - add, 2 - view, 3 - edit
let opened_goods = null;

// ===== ИНИЦИАЛИЗАЦИЯ ПРЕДПРОСМОТРА ФОТО =====
function initGoodsPhotoPreview() {
    const mainPhotoInput = document.getElementById('gds_modal_main_photo');
    const mainPhotoImg = document.getElementById('main_photo_img');
    const previewPlaceholder = document.querySelector('#main_photo_preview .preview-placeholder');

    if (mainPhotoInput) {
        mainPhotoInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                mainGoodsPhotoFile = file;
                existingGoodsMainPhoto = null;
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
function loadGoodsExistingPhotos(goodsData) {
    const mainPhotoImg = document.getElementById('main_photo_img');
    const previewPlaceholder = document.querySelector('#main_photo_preview .preview-placeholder');

    if (goodsData.image_path) {
        existingGoodsMainPhoto = `../../src/images/goods/${goodsData.image_path}`;
        mainPhotoImg.src = `../../src/images/goods/${goodsData.image_path}`;
        mainPhotoImg.classList.add('active');
        if (previewPlaceholder) previewPlaceholder.classList.add('hide');
        mainGoodsPhotoFile = null;
    } else {
        existingGoodsMainPhoto = null;
    }
}

// ===== СБРОС ФОТО =====
function resetGoodsPhotos() {
    const mainPhotoImg = document.getElementById('main_photo_img');
    const mainPhotoInput = document.getElementById('gds_modal_main_photo');
    const previewPlaceholder = document.querySelector('#main_photo_preview .preview-placeholder');

    if (mainPhotoImg) {
        mainPhotoImg.src = '';
        mainPhotoImg.classList.remove('active');
    }
    if (mainPhotoInput) mainPhotoInput.value = '';
    if (previewPlaceholder) previewPlaceholder.classList.remove('hide');

    mainGoodsPhotoFile = null;
    existingGoodsMainPhoto = null;
}

// ===== ПОЛУЧЕНИЕ ДАННЫХ ФОТО =====
function getGoodsPhotosData() {
    return {
        main_photo: mainGoodsPhotoFile,
        existing_main_photo: existingGoodsMainPhoto
    };
}

// ===== ИНИЦИАЛИЗАЦИЯ =====
function initGoods() {
    for (let element in gds_modal_elements) {
        gds_modal_elements[element] = document.getElementById(`${element}`);
    }
    initGoodsPhotoPreview();
}

// ===== ПОДГОТОВКА МОДАЛЬНОГО ОКНА =====
function prepareGoodsModal(editable = true) {
    const fields = [
        'gds_modal_name', 'gds_modal_category', 'gds_modal_material',
        'gds_modal_sizes', 'gds_modal_weight', 'gds_modal_price',
        'gds_modal_stock', 'gds_modal_dsc_short', 'gds_modal_dsc_full'
    ];

    if (gds_modal_type === 1) {
        if (gds_modal_elements.gds_modal_creation_div) {
            gds_modal_elements.gds_modal_creation_div.style.display = 'none';
        }
        if (gds_modal_elements.gds_modal_updated_div) {
            gds_modal_elements.gds_modal_updated_div.style.display = 'none';
        }
    } else {
        if (gds_modal_elements.gds_modal_creation_div) {
            gds_modal_elements.gds_modal_creation_div.style.display = 'flex';
        }
        if (gds_modal_elements.gds_modal_updated_div) {
            gds_modal_elements.gds_modal_updated_div.style.display = 'flex';
        }
    }

    if (editable) {
        fields.forEach(field => {
            if (gds_modal_elements[field]) {
                gds_modal_elements[field].removeAttribute('disabled');
            }
        });
        if (gds_modal_elements.gds_modal_add_main_img) {
            gds_modal_elements.gds_modal_add_main_img.style.display = 'block';
        }
        if (gds_modal_elements.gds_modal_save) {
            gds_modal_elements.gds_modal_save.style.display = 'block';
        }
    } else {
        fields.forEach(field => {
            if (gds_modal_elements[field]) {
                gds_modal_elements[field].setAttribute('disabled', 'true');
            }
        });
        if (gds_modal_elements.gds_modal_add_main_img) {
            gds_modal_elements.gds_modal_add_main_img.style.display = 'none';
        }
        if (gds_modal_elements.gds_modal_save) {
            gds_modal_elements.gds_modal_save.style.display = 'none';
        }
    }
}

// ===== ЗАПОЛНЕНИЕ ДАННЫМИ =====
function prepareModalGoodsData(goodsData) {
    gds_modal_elements.gds_modal_name.value = goodsData.name || '';
    if (gds_modal_elements.gds_modal_category) {
        gds_modal_elements.gds_modal_category.value = goodsData.category || '1';
    }
    gds_modal_elements.gds_modal_material.value = goodsData.material || '';
    gds_modal_elements.gds_modal_sizes.value = goodsData.sizes || '';
    gds_modal_elements.gds_modal_weight.value = goodsData.weight || '';
    gds_modal_elements.gds_modal_price.value = goodsData.price || '';
    gds_modal_elements.gds_modal_stock.value = goodsData.total_stock || 1;
    gds_modal_elements.gds_modal_dsc_short.value = goodsData.description_short || '';
    gds_modal_elements.gds_modal_dsc_full.value = goodsData.description_full || '';
    gds_modal_elements.gds_modal_creation.value = goodsData.created_at || '';
    gds_modal_elements.gds_modal_updated.value = goodsData.updated_at || '';
}

// ===== СБРОС ФОРМЫ =====
function goodsModalClear() {
    gds_modal_elements.gds_modal_name.value = '';
    if (gds_modal_elements.gds_modal_category) gds_modal_elements.gds_modal_category.value = '1';
    gds_modal_elements.gds_modal_material.value = '';
    gds_modal_elements.gds_modal_sizes.value = '';
    gds_modal_elements.gds_modal_weight.value = '';
    gds_modal_elements.gds_modal_price.value = '';
    gds_modal_elements.gds_modal_stock.value = '';
    gds_modal_elements.gds_modal_dsc_short.value = '';
    gds_modal_elements.gds_modal_dsc_full.value = '';
    gds_modal_elements.gds_modal_creation.value = '';
    gds_modal_elements.gds_modal_updated.value = '';

    resetGoodsPhotos();
    opened_goods = null;
}

// ===== ЗАКРЫТИЕ МОДАЛЬНОГО ОКНА =====
function closeGoodsModal() {
    let goods_modal = document.getElementById("gds_modal");
    goods_modal?.classList.remove("active");
    document.body.style.overflow = "auto";
    const container = goods_modal?.querySelector('.modal-container');
    if (container) container.scrollTop = 0;
    goodsModalClear();
}

function goodsClose() {
    let goods_modal = document.getElementById("gds_modal");
    if (goods_modal) {
        if (!gds_modal_elements.gds_modal_name?.hasAttribute("disabled")) {
            if (confirm('Если вы закроете окно, ваши изменения не сохранятся. Вы уверены?')) {
                closeGoodsModal();
            }
        } else {
            closeGoodsModal();
        }
    }
}

// ===== ОТКРЫТИЕ ПРОСМОТРА =====
async function goodsView(goodsId) {
    if (!goodsId || !Number.isInteger(goodsId)) {
        console.log("Неверный ID товара");
        return;
    }

    const goods_modal = document.getElementById("gds_modal");
    if (!goods_modal) return;

    const sessionId = getCookie('session_id');
    const fingerprint = await collectFingerPrint();

    $.post("../../server/post/adminGoodsHandler.php", {
        "type": "getById",
        "goodsId": goodsId,
        "session_id": sessionId,
        "fingerprint": fingerprint
    }, function(data) {
        const data_parsed = JSON.parse(data);
        if (data_parsed.response?.message) {
            const goodsData = JSON.parse(data_parsed.response.message);
            gds_modal_elements.gds_modal_title.innerHTML = "Просмотр товара";
            prepareGoodsModal(false);
            prepareModalGoodsData(goodsData);
            loadGoodsExistingPhotos(goodsData);
            gds_modal_type = 2;
            goods_modal.classList.add("active");
            document.body.style.overflow = "hidden";
            opened_goods = goodsId;
        } else if (data_parsed.error) {
            Toast.error(`Ошибка [${data_parsed.error.code}]: ${data_parsed.error.message}`);
        }
    });
}

// ===== ОТКРЫТИЕ РЕДАКТИРОВАНИЯ =====
async function goodsEdit(goodsId) {
    if (!goodsId || !Number.isInteger(goodsId)) {
        console.log("Неверный ID товара");
        return;
    }

    const goods_modal = document.getElementById("gds_modal");
    if (!goods_modal) return;

    const sessionId = getCookie('session_id');
    const fingerprint = await collectFingerPrint();

    $.post("../../server/post/adminGoodsHandler.php", {
        "type": "getById",
        "goodsId": goodsId,
        "session_id": sessionId,
        "fingerprint": fingerprint
    }, function(data) {
        const data_parsed = JSON.parse(data);
        if (data_parsed.response?.message) {
            const goodsData = JSON.parse(data_parsed.response.message);
            gds_modal_elements.gds_modal_title.innerHTML = "Редактирование товара";
            prepareGoodsModal(true);
            prepareModalGoodsData(goodsData);
            loadGoodsExistingPhotos(goodsData);
            gds_modal_type = 3;
            goods_modal.classList.add("active");
            document.body.style.overflow = "hidden";
            opened_goods = goodsId;
        } else if (data_parsed.error) {
            Toast.error(`Ошибка [${data_parsed.error.code}]: ${data_parsed.error.message}`);
        }
    });
}

// ===== ДОБАВЛЕНИЕ НОВОГО ТОВАРА =====
function goodsAdd() {
    const goods_modal = document.getElementById("gds_modal");
    if (!goods_modal) return;

    gds_modal_type = 1;
    gds_modal_elements.gds_modal_title.innerHTML = "Добавление товара";
    prepareGoodsModal(true);
    goodsModalClear();
    goods_modal.classList.add("active");
    document.body.style.overflow = "hidden";
}

// ===== УДАЛЕНИЕ ТОВАРА =====
async function goodsRemove(goodsId) {
    if (confirm('Вы действительно хотите удалить этот товар?')) {
        const sessionId = getCookie('session_id');
        const fingerprint = await collectFingerPrint();
        $.post("../../server/post/adminGoodsHandler.php", {
            "type": "deleteGoods",
            "goodsId": goodsId,
            "session_id": sessionId,
            "fingerprint": fingerprint
        }, function(data) {
            const data_parsed = JSON.parse(data);
            if (data_parsed.response?.code === 200) {
                Toast.success("Товар удалён");
                const row = document.getElementById(`tr_${goodsId}`);
                if (row) row.remove();
            } else if (data_parsed.error) {
                Toast.error(`Ошибка: ${data_parsed.error.message}`);
            }
        });
    }
}

// ===== СОХРАНЕНИЕ ТОВАРА =====
async function goodsSave() {
    if (!opened_goods && gds_modal_type !== 1) {
        Toast.warning("Произошла ошибка. Перезагрузите страницу.");
        return;
    }

    const goods_modal = document.getElementById("gds_modal");
    if (!goods_modal || !goods_modal.classList.contains("active")) return;

    if (gds_modal_elements.gds_modal_name?.hasAttribute("disabled")) {
        console.log("Форма в режиме просмотра");
        return;
    }

    // Сбор данных
    const goodsData = {
        name: gds_modal_elements.gds_modal_name?.value || '',
        category: gds_modal_elements.gds_modal_category?.value || '1',
        material: gds_modal_elements.gds_modal_material?.value || '',
        sizes: gds_modal_elements.gds_modal_sizes?.value || '',
        weight: parseFloat(gds_modal_elements.gds_modal_weight?.value) || 0,
        price: parseFloat(gds_modal_elements.gds_modal_price?.value) || 0,
        total_stock: parseInt(gds_modal_elements.gds_modal_stock?.value) || 1,
        description_short: gds_modal_elements.gds_modal_dsc_short?.value || '',
        description_full: gds_modal_elements.gds_modal_dsc_full?.value || ''
    };

    if (gds_modal_type === 3) {
        goodsData.goodsId = opened_goods;
    }

    const photosData = getGoodsPhotosData();

    // FormData
    const formData = new FormData();

    if (gds_modal_type === 1) {
        formData.append("type", "addGoods");
    } else if (gds_modal_type === 3) {
        formData.append("type", "editGoods");
    }

    formData.append("goodsData", JSON.stringify(goodsData));

    if (photosData.main_photo) {
        formData.append("main_photo", photosData.main_photo);
    }
    if (photosData.existing_main_photo) {
        formData.append("existing_main_photo", photosData.existing_main_photo);
    }

    const sessionId = getCookie('session_id');
    const fingerprint = await collectFingerPrint();
    formData.append("session_id", sessionId);
    formData.append("fingerprint", fingerprint);

    $.ajax({
        url: "../../server/post/adminGoodsHandler.php",
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: async function(data) {
            const data_parsed = JSON.parse(data);
            if (data_parsed.response?.code === 200) {
                Toast.success("Данные сохранены!");

                if (gds_modal_type === 1) {
                    // Добавление
                    const responseData = JSON.parse(data_parsed.response.message);
                    const newGoodsId = responseData.goodsId;
                    const imagePath = responseData.image_path;

                    // Обновляем данные
                    await new Promise((resolve) => {
                        $.post("../../server/post/adminGoodsHandler.php", {
                            "type": "getById",
                            "goodsId": newGoodsId,
                            "session_id": sessionId,
                            "fingerprint": fingerprint
                        }, function(data2) {
                            const parsed2 = JSON.parse(data2);
                            if (parsed2.response?.message) {
                                const newGoodsData = JSON.parse(parsed2.response.message);
                                const newRow = createGoodsRow(newGoodsId, newGoodsData, imagePath);
                                const tbody = document.querySelector('.data-table tbody');
                                if (tbody) {
                                    tbody.appendChild(newRow);
                                    const noDataRow = tbody.querySelector('.no-data-message');
                                    if (noDataRow) noDataRow.remove();
                                }
                            }
                            resolve();
                        });
                    });
                } else if (gds_modal_type === 3 && opened_goods) {
                    // Редактирование
                    updateGoodsRow(opened_goods, goodsData);
                }

                closeGoodsModal();
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
function createGoodsRow(goodsId, goodsData, imagePath) {
    const tr = document.createElement('tr');
    tr.id = `tr_${goodsId}`;

    const categoryNames = {
        1: 'Гробы',
        2: 'Венки',
        3: 'Кресты',
        4: 'Памятники',
        5: 'Одежда',
        6: 'Аксессуары'
    };
    const categoryName = categoryNames[goodsData.category] || 'Не определено';
    const placeholder = '../../src/images/no-image.jpg';
    const formattedPrice = goodsData.price.toLocaleString('ru-RU');

    tr.innerHTML = `
        <td>${goodsId}</td>
        <td><img src="${imagePath}" class="table-thumb" onerror="this.src='${placeholder}'" alt="${goodsData.name}"></td>
        <td>${escapeHtml(goodsData.name)}</td>
        <td>${categoryName}</td>
        <td>${escapeHtml(goodsData.material)}</td>
        <td>${formattedPrice} ₽</td>
        <td>${goodsData.total_stock}</td>
        <td><span class="status active">Активен</span></td>
        <td>
            <button class="btn-icon view" onclick="goodsView(${goodsId})">👁️</button>
            <button class="btn-icon edit" onclick="goodsEdit(${goodsId})">✏️</button>
            <button class="btn-icon delete" onclick="goodsRemove(${goodsId})">🗑️</button>
        </td>
    `;

    return tr;
}

// ===== ОБНОВЛЕНИЕ СТРОКИ В ТАБЛИЦЕ =====
function updateGoodsRow(goodsId, goodsData) {
    const row = document.getElementById(`tr_${goodsId}`);
    if (!row) return;

    const categoryNames = {
        1: 'Гробы',
        2: 'Венки',
        3: 'Кресты',
        4: 'Памятники',
        5: 'Одежда',
        6: 'Аксессуары'
    };
    const categoryName = categoryNames[goodsData.category] || 'Не определено';
    const formattedPrice = goodsData.price.toLocaleString('ru-RU');

    const cells = row.cells;
    if (cells.length >= 9) {
        const imgElement = cells[1].querySelector('img');
        if (imgElement) {
            const timestamp = new Date().getTime();
            imgElement.src = `${imgElement.src}?t=${timestamp}`;
        }
        cells[2].textContent = goodsData.name;
        cells[3].textContent = categoryName;
        cells[4].textContent = goodsData.material;
        cells[5].textContent = `${formattedPrice} ₽`;
        cells[6].textContent = goodsData.total_stock;
    }
}

// ===== ЭКРАНИРОВАНИЕ HTML =====
function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}