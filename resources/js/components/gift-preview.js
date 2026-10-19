// Stránka nákupu dárků (resources/views/gifts/index.blade.php):
// zmenšený náhled certifikátu, přepínání předplatné / poukaz a živé ceny.
//
// Ceny se tu počítají jen pro zobrazení. Skutečnou cenu dárku počítá server
// (GiftCartService) z aktuálních cen při vložení do košíku a znovu v pokladně.

function formatPrice(amount, currency) {
    if (currency === 'EUR') {
        return '€' + Math.round(amount).toLocaleString('en-US').replace(/,/g, ' ');
    }
    return Math.round(amount).toLocaleString('cs-CZ').replace(/ /g, ' ') + ' Kč';
}

function fill(template, values) {
    return Object.keys(values).reduce((text, key) => text.split(':' + key).join(values[key]), template);
}

function initPreviewScale(preview) {
    const scaler = preview.querySelector('[data-cert-scale]');
    if (!scaler) return;
    const apply = () => {
        const width = preview.clientWidth;
        if (width > 0) scaler.style.transform = 'scale(' + (width / 794) + ')';
    };
    apply();
    if (typeof ResizeObserver !== 'undefined') {
        new ResizeObserver(apply).observe(preview);
    } else {
        window.addEventListener('resize', apply);
    }
}

// Stejný výpočet jako v gifts/certificate/_front.blade.php: blok s nadpisem a věnováním
// se centruje mezi logo a spodní řádky.
function repositionCertificate(cert) {
    const bottomTop = Number(cert.dataset.bottomTop);
    const headBase = Number(cert.dataset.headBase);
    const noteWrap = cert.querySelector('[data-cert-field="note-wrap"]');
    const note = cert.querySelector('[data-cert-field="note"]');
    const text = note ? note.textContent.trim() : '';
    const long = text.length > 90;
    const lineHeight = long ? 20 : 24;

    if (noteWrap) {
        noteWrap.style.display = text === '' ? 'none' : '';
        noteWrap.style.fontSize = (long ? 14 : 16) + 'px';
        noteWrap.style.lineHeight = lineHeight + 'px';
    }

    const lines = text === '' ? 0 : Math.max(1, Math.ceil(text.length / (long ? 52 : 44)));
    const blockHeight = headBase + (lines ? 16 + lines * lineHeight : 0);
    const start = 24 + Math.round((bottomTop - 24 - blockHeight) / 2);

    cert.querySelectorAll('[data-cert-offset]').forEach((el) => {
        el.style.top = (start + Number(el.dataset.certOffset)) + 'px';
    });
}

function initGiftForm(form) {
    let config = {};
    try {
        config = JSON.parse(form.dataset.giftConfig || '{}');
    } catch (e) {
        return;
    }

    const preview = form.querySelector('[data-cert-preview]');
    if (preview) initPreviewScale(preview);

    const value = (name) => form.querySelector('input[name="' + name + '"]:checked')?.value;
    const certs = form.querySelectorAll('.kavi-cert');
    const setField = (field, text) => {
        form.querySelectorAll('[data-cert-field="' + field + '"]').forEach((el) => { el.textContent = text; });
    };

    const update = () => {
        const type = value('type') || 'subscription';
        const isSub = type === 'subscription';
        const months = Number(value('months') || 3);
        const bags = Number(value('bags') || 3);
        const amount = Number(value('voucher_amount') || 0);
        const boxPrice = Number(config.prices?.[bags] || 0);

        form.querySelectorAll('[data-gift-group]').forEach((group) => {
            group.hidden = group.dataset.giftGroup !== type;
        });
        form.querySelectorAll('[data-cert-wrap]').forEach((wrap) => {
            wrap.style.display = wrap.dataset.certWrap === type ? '' : 'none';
        });
        const lastStep = form.querySelector('[data-gift-last-step]');
        if (lastStep) lastStep.textContent = isSub ? '04' : '03';

        // Ceny u délek podle zvolené velikosti boxu
        form.querySelectorAll('[data-gift-length-price]').forEach((el) => {
            el.textContent = formatPrice(boxPrice * Number(el.dataset.giftLengthPrice), config.currency);
        });

        const total = isSub ? boxPrice * months : amount;
        const totalEl = form.querySelector('[data-gift-total]');
        if (totalEl) totalEl.textContent = formatPrice(total, config.currency);
        const noteEl = form.querySelector('[data-gift-total-note]');
        if (noteEl) {
            noteEl.textContent = isSub
                ? fill(config.noteSubscription || '', {
                    box: config.sizes?.[bags] || '',
                    price: formatPrice(boxPrice, config.currency),
                    months: String(months),
                })
                : (config.noteVoucher || '');
        }

        // Náhled certifikátu
        setField('headline', config.monthsWords?.[months] || '');
        setField('box', config.boxLabels?.[bags] || '');
        setField('amount', config.currency === 'EUR'
            ? Math.round(amount).toLocaleString('en-US').replace(/,/g, ' ')
            : Math.round(amount).toLocaleString('cs-CZ'));

        const sample = config.sample || {};
        const input = (name) => form.querySelector('[data-gift-input="' + name + '"]')?.value.trim() || '';
        setField('recipient', input('recipient') || sample.recipient || '');
        setField('giver', input('giver') || sample.giver || '');
        setField('note', input('note') || sample.note || '');
        certs.forEach(repositionCertificate);

        const recipientFields = form.querySelector('[data-gift-recipient-fields]');
        if (recipientFields) recipientFields.hidden = value('delivery') !== 'recipient';
    };

    form.addEventListener('change', update);
    form.addEventListener('input', (event) => {
        if (event.target.matches('[data-gift-input]')) update();
    });
    update();
}

export function initGiftPreview() {
    document.querySelectorAll('[data-gift-form]').forEach(initGiftForm);
}
