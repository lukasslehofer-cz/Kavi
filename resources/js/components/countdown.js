// Odpočet do uzávěrky objednávek (resources/views/components/countdown.blade.php).
//
// Počítá se podle času serveru (data-server-now), ne podle hodin v prohlížeči,
// aby špatně nastavený čas v počítači neukazoval jinou uzávěrku.
// Po dosažení nuly se stránka jednou obnoví, server pak vykreslí další cyklus.

const pad = (n) => String(n).padStart(2, '0');

function pluralIndex(n, locale) {
    if (n === 1) return 0;
    if (locale !== 'en' && n >= 2 && n <= 4) return 1;
    return 2;
}

function initCountdown(el) {
    const deadline = Date.parse(el.dataset.deadline);
    const serverNow = Date.parse(el.dataset.serverNow);
    if (Number.isNaN(deadline) || Number.isNaN(serverNow)) return;

    const skew = serverNow - Date.now();
    const locale = el.dataset.locale || 'cs';
    let plural = {};
    try {
        plural = JSON.parse(el.dataset.plural || '{}');
    } catch (e) {
        plural = {};
    }

    const units = {};
    el.querySelectorAll('[data-countdown-unit]').forEach((node) => {
        units[node.dataset.countdownUnit] = node;
    });

    const word = (unit, n) => {
        const forms = plural[unit] || [];
        return n + ' ' + (forms[pluralIndex(n, locale)] || '');
    };

    let lastAriaMinute = null;
    let timer = null;

    const render = () => {
        const left = Math.max(0, Math.floor((deadline - (Date.now() + skew)) / 1000));
        const values = {
            days: Math.floor(left / 86400),
            hours: Math.floor((left % 86400) / 3600),
            minutes: Math.floor((left % 3600) / 60),
            seconds: left % 60,
        };

        Object.entries(values).forEach(([unit, value]) => {
            if (units[unit]) units[unit].textContent = pad(value);
        });

        // Čtečky obrazovky: popisek jen jednou za minutu, ne každou sekundu
        const minuteKey = values.days + ':' + values.hours + ':' + values.minutes;
        if (minuteKey !== lastAriaMinute && el.dataset.ariaTemplate) {
            lastAriaMinute = minuteKey;
            el.setAttribute('aria-label', el.dataset.ariaTemplate
                .replace(':days', word('days', values.days))
                .replace(':hours', word('hours', values.hours))
                .replace(':minutes', word('minutes', values.minutes)));
        }

        if (left === 0) {
            if (timer) clearInterval(timer);
            timer = null;
            onFinished();
            return false;
        }

        return true;
    };

    const onFinished = () => {
        const key = 'kavi-countdown-reload:' + el.dataset.deadline;
        let alreadyReloaded = false;
        try {
            alreadyReloaded = sessionStorage.getItem(key) === '1';
            if (!alreadyReloaded) sessionStorage.setItem(key, '1');
        } catch (e) {
            // Bez sessionStorage radši neobnovovat, hrozila by smyčka
            alreadyReloaded = true;
        }

        if (!alreadyReloaded) {
            window.location.reload();
            return;
        }

        const label = el.querySelector('[data-countdown-label]');
        if (label && el.dataset.passed) label.textContent = el.dataset.passed;
    };

    if (render()) {
        timer = setInterval(render, 1000);
    }
}

export function initCountdowns() {
    document.querySelectorAll('[data-countdown]').forEach(initCountdown);
}
