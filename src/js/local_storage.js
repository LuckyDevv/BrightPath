// 1. Сохранить в localStorage
function setLocalStorage(key, data) {
    try {
        const serialized = JSON.stringify(data);
        localStorage.setItem(key, serialized);
    } catch (e) {
        console.error('Ошибка сохранения в localStorage:', e);
    }
}

// 2. Получить из localStorage
function getLocalStorage(key) {
    try {
        const serialized = localStorage.getItem(key);
        if (serialized === null) return null;
        return JSON.parse(serialized);
    } catch (e) {
        console.error('Ошибка чтения из localStorage:', e);
        return null;
    }
}

// 3. Удалить из localStorage
function removeLocalStorage(key) {
    try {
        localStorage.removeItem(key);
    } catch (e) {
        console.error('Ошибка удаления из localStorage:', e);
    }
}