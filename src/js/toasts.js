// ===== TOAST УВЕДОМЛЕНИЯ =====
const Toast = {
    container: null,
    maxLength: 37, // Максимальная длина текста до появления кнопки "Подробнее"

    init() {
        this.container = document.getElementById('toast-container');
        if (!this.container) {
            console.error('Toast container not found');
            return;
        }
    },

    // Экранирование HTML для безопасности
    escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    },

    show(message, type = 'success', duration = 3000) {
        if (!this.container) this.init();

        // Создаём элемент уведомления
        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;

        // Иконка в зависимости от типа
        let iconSvg = '';
        switch(type) {
            case 'success':
                iconSvg = `<svg class="toast-icon" width="20" height="20" viewBox="0 0 1024 1024" fill="white" xmlns="http://www.w3.org/2000/svg"><path d="M512 64a448 448 0 110 896 448 448 0 010-896zm-55.808 536.384l-99.52-99.584a38.4 38.4 0 10-54.336 54.336l126.72 126.72a38.272 38.272 0 0054.336 0l262.4-262.464a38.4 38.4 0 10-54.272-54.336L456.192 600.384z"/></svg>`;
                break;
            case 'error':
                iconSvg = `<svg class="toast-icon" width="20" height="20" viewBox="-3.5 0 19 19" fill="none" stroke="white" stroke-width="2" xmlns="http://www.w3.org/2000/svg"><path d="M11.383 13.644A1.03 1.03 0 0 1 9.928 15.1L6 11.172 2.072 15.1a1.03 1.03 0 1 1-1.455-1.456l3.928-3.928L.617 5.79a1.03 1.03 0 1 1 1.455-1.456L6 8.261l3.928-3.928a1.03 1.03 0 0 1 1.455 1.456L7.455 9.716z"/></svg>`;
                break;
            case 'warning':
                iconSvg = `<svg class="toast-icon" width="20" height="20" viewBox="0 0 1024 1024" fill="white" xmlns="http://www.w3.org/2000/svg"><path d="M955.7 856l-416-720c-6.2-10.7-16.9-16-27.7-16s-21.6 5.3-27.7 16l-416 720C56 877.4 71.4 904 96 904h832c24.6 0 40-26.6 27.7-48zM480 416c0-4.4 3.6-8 8-8h48c4.4 0 8 3.6 8 8v184c0 4.4-3.6 8-8 8h-48c-4.4 0-8-3.6-8-8V416zm32 352a48.01 48.01 0 0 1 0-96 48.01 48.01 0 0 1 0 96z"/></svg>`;
                break;
        }

        const needsExpand = message.length > this.maxLength;

        const escapedMessage = this.escapeHtml(message);
        const truncatedMessage = truncateString(escapedMessage, 34);
        let messageHtml;
        if (needsExpand) {
            messageHtml = `<div class="toast-message collapsed">${truncatedMessage}</div>`;
        }else{
            messageHtml = `<div class="toast-message">${escapedMessage}</div>`;
        }

        toast.innerHTML = `
            ${iconSvg}
            <div class="toast-content">
                ${messageHtml}
                ${needsExpand ? '<button class="toast-expand-btn">▼ Подробнее</button>' : ''}
            </div>
            <button class="toast-close">
                <svg viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" width="12" height="12">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        `;

        this.container.appendChild(toast);

        // Обработчик кнопки раскрытия
        const expandBtn = toast.querySelector('.toast-expand-btn');
        const messageDiv = toast.querySelector('.toast-message');

        // Кнопка закрытия
        const closeBtn = toast.querySelector('.toast-close');
        closeBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            this.close(toast);
        });

        // Клик по уведомлению (кроме кнопок) закрывает
        toast.addEventListener('click', (e) => {
            if (e.target !== expandBtn &&
                e.target !== closeBtn &&
                !expandBtn?.contains(e.target) &&
                !closeBtn?.contains(e.target)) {
                this.close(toast);
            }
        });

        // Автоматическое закрытие
        let timeoutId = setTimeout(() => {
            this.close(toast);
        }, duration);
        toast.dataset.timeoutId = timeoutId;


        if (expandBtn) {
            let isExpanded = false;
            console.log(isExpanded);
            expandBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                if (isExpanded) {
                    console.log("Свернули");
                    messageDiv.classList.add('collapsed');
                    messageDiv.innerHTML = truncatedMessage;
                    expandBtn.innerHTML = '▼ Подробнее';
                    clearTimeout(timeoutId);
                    timeoutId = setTimeout(() => {
                        this.close(toast);
                    }, duration);
                    toast.dataset.timeoutId = timeoutId;
                } else {
                    console.log("Развернули");
                    messageDiv.classList.remove('collapsed');
                    messageDiv.innerHTML = escapedMessage;
                    expandBtn.innerHTML = '▲ Свернуть';
                    clearTimeout(timeoutId);
                    timeoutId = setTimeout(() => {
                        this.close(toast);
                    }, 10000);
                    toast.dataset.timeoutId = timeoutId;
                }
                isExpanded = !isExpanded;
            });
        }

        return toast;
    },

    close(toast) {
        if (!toast) return;

        if (toast.dataset.timeoutId) {
            clearTimeout(parseInt(toast.dataset.timeoutId));
        }

        toast.classList.add('hiding');
        setTimeout(() => {
            if (toast.parentNode) toast.remove();
        }, 300);
    },

    success(message, duration = 3000) {
        return this.show(message, 'success', duration);
    },

    error(message, duration = 3000) {
        return this.show(message, 'error', duration);
    },

    warning(message, duration = 3000) {
        return this.show(message, 'warning', duration);
    }
};

// Инициализация
document.addEventListener('DOMContentLoaded', () => {
    Toast.init();
});

window.Toast = Toast;

const truncateString = (str, limit) => {
    if (str.length > limit) {
        return str.slice(0, limit) + '...';
    }
    return str;
};