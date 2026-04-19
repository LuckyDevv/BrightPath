async function getFingerprint() {
    return {
        // Базовые данные (легко получить)
        userAgent: navigator.userAgent,
        platform: navigator.platform,
        language: navigator.language,
        languages: navigator.languages.join(','),
        hardwareConcurrency: navigator.hardwareConcurrency,
        deviceMemory: navigator.deviceMemory || 0,
        maxTouchPoints: navigator.maxTouchPoints,

        // Экран
        screenWidth: screen.width,
        screenHeight: screen.height,
        screenColorDepth: screen.colorDepth,
        screenPixelDepth: screen.pixelDepth,
        devicePixelRatio: window.devicePixelRatio,

        // Время и часовой пояс
        timezoneOffset: new Date().getTimezoneOffset(),

        // Canvas fingerprint
        canvas: await getCanvasFingerprint(),

        // WebGL fingerprint
        webgl: await getWebGLFingerprint()
    };
}

// Canvas fingerprint
async function getCanvasFingerprint() {
    const canvas = document.createElement('canvas');
    canvas.width = 200;
    canvas.height = 50;
    const ctx = canvas.getContext('2d');

    ctx.textBaseline = 'top';
    ctx.font = '14px Arial';
    ctx.fillStyle = '#f60';
    ctx.fillRect(0, 0, 100, 50);
    ctx.fillStyle = '#069';
    ctx.fillText('Светлый Путь 🕯️', 2, 15);
    ctx.fillStyle = 'rgba(102, 204, 0, 0.7)';
    ctx.fillRect(100, 20, 80, 20);

    return canvas.toDataURL();
}

// WebGL fingerprint
async function getWebGLFingerprint() {
    const canvas = document.createElement('canvas');
    const gl = canvas.getContext('webgl') || canvas.getContext('experimental-webgl');
    if (!gl) return null;

    const debugInfo = gl.getExtension('WEBGL_debug_renderer_info');
    if (debugInfo) {
        const vendor = gl.getParameter(debugInfo.UNMASKED_VENDOR_WEBGL);
        const renderer = gl.getParameter(debugInfo.UNMASKED_RENDERER_WEBGL);
        return { vendor, renderer };
    }
    return null;
}

async function collectFingerPrint() {
    const fingerprintData = await getFingerprint();

    // Хешируем fingerprint для отправки (чтобы не передавать лишнего)
    return await sha256(JSON.stringify(fingerprintData));
}

// Простая функция хеширования SHA-256
async function sha256(message) {
    const msgBuffer = new TextEncoder().encode(message);
    const hashBuffer = await crypto.subtle.digest('SHA-256', msgBuffer);
    const hashArray = Array.from(new Uint8Array(hashBuffer));
    return hashArray.map(b => b.toString(16).padStart(2, '0')).join('');
}