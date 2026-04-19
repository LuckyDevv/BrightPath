async function checkSession() {
    if (getLocalStorage("is_logout") !== null) {
        return;
    }
    // Получаем session_id из cookie
    const sessionId = getCookie('session_id');
    // Если session_id нет и мы на index.php - сразу редирект
    const currentPage = window.location.pathname.split('/').pop();
    if (sessionId === null) {
        if (currentPage === 'index.php' || currentPage === '') {
            window.location.href = 'auth.php';
            setLocalStorage("session_message", {
                "err_code": 13,
                "err_message": "Вы не вошли в аккаунт!"
            });
        }
        return;
    }
    const fingerprint = await collectFingerPrint();
    // Отправляем на проверку
    try {
        $.post("../server/post/adminAuthHandler.php", {
            "type": "verify_session",
            "session_id": sessionId,
            "fingerprint": fingerprint
        }, async function (data) {
            const response = JSON.parse(data);
            if (response.response && response.response.code === 200) {
                if (currentPage === 'auth.php') {
                    window.location.href = 'index.php';
                }
            } else if (response.error) {
                deleteCookie('session_id');
                if (currentPage === 'index.php' || currentPage === '') {
                    window.location.href = 'auth.php';
                    setLocalStorage("session_message", {
                        "err_code": response.error.code,
                        "err_message": response.error.message
                    });
                }else{
                    Toast.error(response.error.message)
                }
            }
        });
    } catch (error) {
        console.error('Session check error:', error);
        // При ошибке лучше перестраховаться
        if (currentPage === 'index.php' || currentPage === '') {
            window.location.href = 'auth.php';
            setLocalStorage("session_message", {
                "err_code": 12,
                "err_message": "Произошла ошибка сервера. Войдите снова."
            });
        }
    }
}

function checkSessionMessage() {
    let session_msg_data = getLocalStorage("session_message");
    const currentPage = window.location.pathname.split('/').pop();
    if (currentPage === 'auth.php') {
        if (session_msg_data !== null) {
            Toast.error(`Ошибка [${session_msg_data.err_code}]: ${session_msg_data.err_message}`);
        }
    }
    removeLocalStorage("session_message")
}

// Вспомогательные функции
function getCookie(name) {
    const value = `; ${document.cookie}`;
    const parts = value.split(`; ${name}=`);
    if (parts.length === 2) return parts.pop().split(';').shift();
    return null;
}

function deleteCookie(name) {
    document.cookie = `${name}=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;`;
}

// Запускаем проверку при загрузке страницы
document.addEventListener('DOMContentLoaded', checkSession);
document.addEventListener('DOMContentLoaded', checkSessionMessage)