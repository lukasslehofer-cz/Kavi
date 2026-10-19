// Stránka uplatnění dárku (resources/views/gifts/redeem.blade.php):
// AJAX ověření kódu, karta dárku vlevo, u předplatného volba kávy, startu a shrnutí.

function el(tag, className, text) {
    const node = document.createElement(tag);
    if (className) node.className = className;
    if (text !== undefined && text !== null) node.textContent = text;
    return node;
}

function initRedeem(root) {
    let config = {};
    try {
        config = JSON.parse(root.dataset.redeemConfig || '{}');
    } catch (e) {
        return;
    }

    const q = (selector) => root.querySelector(selector);
    const verifyForm = q('[data-redeem-verify]');
    const input = q('#redeem-code');
    const button = q('[data-redeem-verify-button]');
    const okBox = q('[data-redeem-ok]');
    const errorBox = q('[data-redeem-error]');
    const aside = q('[data-redeem-aside]');
    const subForm = q('[data-redeem-subscription]');
    const voucherBox = q('[data-redeem-voucher]');
    const placeholder = q('[data-redeem-placeholder]');
    let gift = null;

    const setField = (name, value) => {
        root.querySelectorAll('[data-redeem-field="' + name + '"]').forEach((node) => { node.textContent = value || ''; });
    };

    const showError = (message) => {
        okBox.classList.add('hidden');
        okBox.classList.remove('flex');
        errorBox.textContent = message;
        errorBox.classList.remove('hidden');
    };

    const resetState = () => {
        gift = null;
        q('[data-redeem-intro]').hidden = false;
        q('[data-redeem-card]').hidden = true;
        subForm.hidden = true;
        voucherBox.hidden = true;
        placeholder.hidden = false;
        // Na mobilu je před ověřením formulář první, návod pod ním
        aside.classList.add('order-2');
    };

    const renderGift = (data) => {
        gift = data;
        errorBox.classList.add('hidden');
        q('[data-redeem-ok-text]').textContent = data.ok;
        okBox.classList.remove('hidden');
        okBox.classList.add('flex');

        setField('code', data.code);
        setField('title0', data.title[0]);
        setField('title1', data.title[1]);
        setField('giver', data.giver);
        setField('note', data.note);
        setField('rules', data.rules);
        q('[data-redeem-wish]').hidden = !data.note;

        const rows = q('[data-redeem-rows]');
        rows.innerHTML = '';
        (data.rows || []).forEach((row) => {
            const line = el('div', 'flex justify-between gap-4 py-3 border-b border-warm-300');
            line.appendChild(el('dt', 'text-xs uppercase tracking-widest text-warm-600', row.l));
            line.appendChild(el('dd', 'font-display text-dark-800 text-right', row.v));
            rows.appendChild(line);
        });

        q('[data-redeem-intro]').hidden = true;
        q('[data-redeem-card]').hidden = false;
        placeholder.hidden = true;
        aside.classList.remove('order-2');

        const isSub = data.type === 'subscription';
        subForm.hidden = !isSub;
        voucherBox.hidden = isSub;

        if (isSub) {
            renderStarts(data.starts || []);
            setMix(Math.ceil(data.bags / 2));
            updateSummary();
        }
    };

    const renderStarts = (starts) => {
        const container = q('[data-redeem-starts]');
        container.innerHTML = '';
        starts.forEach((start, i) => {
            const label = el('label', 'group relative flex items-center justify-between gap-4 py-5 pl-4 pr-2 cursor-pointer border-b border-dark-800/10 transition-colors hover:bg-warm-300/30 has-[:checked]:bg-warm-300/40 has-[:focus-visible]:outline has-[:focus-visible]:outline-2 has-[:focus-visible]:-outline-offset-2 has-[:focus-visible]:outline-primary-500');
            const radio = el('input', 'sr-only');
            radio.type = 'radio';
            radio.name = 'start';
            radio.value = String(start.index);
            radio.checked = i === 0;
            label.appendChild(radio);
            const bar = el('span', 'absolute left-0 top-0 bottom-0 w-1 bg-transparent group-has-[:checked]:bg-primary-500 transition-colors');
            bar.setAttribute('aria-hidden', 'true');
            label.appendChild(bar);
            const text = el('span', 'flex-1 min-w-0');
            text.appendChild(el('span', 'block font-display text-2xl uppercase text-dark-800', start.name));
            text.appendChild(el('span', 'block text-xs font-light text-warm-600', start.desc));
            label.appendChild(text);
            label.appendChild(el('span', 'font-display text-lg text-dark-800 whitespace-nowrap', start.date));
            container.appendChild(label);
        });
    };

    // Rozdělení u kombinace: aspoň jedno espresso a jeden filtr, dohromady velikost boxu
    let espresso = 1;
    const setMix = (value) => {
        if (!gift) return;
        espresso = Math.min(Math.max(1, value), gift.bags - 1);
        const filter = gift.bags - espresso;
        q('[data-redeem-mix-value="espresso"]').textContent = String(espresso);
        q('[data-redeem-mix-value="filter"]').textContent = String(filter);
        q('[data-redeem-mix-input="espresso"]').value = String(espresso);
        q('[data-redeem-mix-input="filter"]').value = String(filter);
    };
    root.querySelectorAll('[data-redeem-mix-step]').forEach((stepButton) => {
        stepButton.addEventListener('click', () => {
            const delta = Number(stepButton.dataset.delta);
            setMix(stepButton.dataset.redeemMixStep === 'espresso' ? espresso + delta : espresso - delta);
            updateSummary();
        });
    });

    const updateSummary = () => {
        if (!gift || gift.type !== 'subscription') return;
        const type = subForm.querySelector('input[name="type"]:checked')?.value || 'espresso';
        q('[data-redeem-mix]').hidden = type !== 'mix';
        const start = (gift.starts || []).find((s) => String(s.index) === (subForm.querySelector('input[name="start"]:checked')?.value || '0')) || (gift.starts || [])[0];
        let typeText = config.types?.[type]?.name || type;
        if (type === 'mix') {
            typeText += ' ' + espresso + '+' + (gift.bags - espresso);
        }
        const summary = { box: gift.box, type: typeText, count: String(gift.months), first: start?.first, last: start?.last };
        Object.entries(summary).forEach(([key, value]) => {
            const node = q('[data-redeem-summary="' + key + '"]');
            if (node) node.textContent = value || '-';
        });
    };
    subForm.addEventListener('change', updateSummary);

    const verify = () => {
        const code = input.value.trim();
        if (!code) return;
        button.disabled = true;

        fetch(config.verifyUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify({ code: code }),
        })
            .then((response) => response.json().catch(() => ({})).then((data) => ({ status: response.status, data: data })))
            .then(({ status, data }) => {
                if (status === 429) {
                    resetState();
                    showError(config.errors.throttled);
                } else if (data.valid) {
                    renderGift(data);
                } else {
                    resetState();
                    showError(data.message || config.errors.generic);
                }
            })
            .catch(() => {
                resetState();
                showError(config.errors.generic);
            })
            .finally(() => { button.disabled = false; });
    };

    verifyForm.addEventListener('submit', (event) => {
        event.preventDefault();
        verify();
    });

    // Kód z QR kódu (?kod=) se předvyplní a rovnou ověří
    if (root.dataset.prefill) {
        verify();
    }
}

export function initGiftRedeem() {
    document.querySelectorAll('[data-gift-redeem]').forEach(initRedeem);
}
