{{--
    Záložky jazyků pro admin formuláře s překlady (produkty, pražírny).

    Použití: <form … data-i18n-form data-active-lang="cs"> a hned za @csrf @include('admin.partials.translation-tabs').
    Pole jednotlivých jazyků jsou obalená v <div data-lang-pane="cs|en|sk">; EN/SK obaly mají
    data-source="<CSS selektor českého pole>" (u <select> se překládá text vybrané možnosti).
    Viditelnost řídí atribut data-active-lang na formuláři, takže platí i pro řádky přidané JavaScriptem.
    „Přeložit vše“ doplní přes DeepL jen prázdná EN a SK pole, vyplněné překlady nepřepisuje.
--}}
<style>
    [data-i18n-form][data-active-lang="cs"] [data-lang-pane]:not([data-lang-pane="cs"]),
    [data-i18n-form][data-active-lang="en"] [data-lang-pane]:not([data-lang-pane="en"]),
    [data-i18n-form][data-active-lang="sk"] [data-lang-pane]:not([data-lang-pane="sk"]) { display: none; }
    [data-lang-tab] { transition: background-color .15s, color .15s, box-shadow .15s; }
    [data-lang-tab][aria-selected="true"] { background: #fff; color: #111827; box-shadow: 0 1px 2px rgba(0,0,0,.08); }
    [data-lang-tab][aria-selected="false"] { color: #4b5563; }
    [data-lang-tab][aria-selected="false"]:hover { color: #111827; }
    [data-lang-badge] { display: none; }
    [data-lang-badge].is-visible { display: inline-flex; }
    [data-source-preview] { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
</style>

<div data-i18n-bar class="sticky top-0 z-20 -mx-2 mb-6 px-2 py-3 bg-white border-b border-gray-200 flex flex-wrap items-center gap-3">
    <span class="text-sm font-medium text-gray-700">Jazyk textů</span>
    <div class="inline-flex rounded-lg bg-gray-100 p-1 gap-1" role="tablist" aria-label="Jazyk textů">
        @foreach(['cs' => ['🇨🇿', 'Čeština'], 'en' => ['🇬🇧', 'English'], 'sk' => ['🇸🇰', 'Slovenčina']] as $tabLang => [$tabFlag, $tabName])
        <button type="button" role="tab" data-lang-tab="{{ $tabLang }}" aria-selected="{{ $tabLang === 'cs' ? 'true' : 'false' }}"
                class="px-3 py-1.5 rounded-md text-sm font-medium flex items-center gap-2">
            <span>{{ $tabFlag }} {{ $tabName }}</span>
            @if($tabLang !== 'cs')
            <span data-lang-badge="{{ $tabLang }}" title="Prázdná pole, která mají český text"
                  class="items-center justify-center min-w-[1.25rem] h-5 px-1.5 rounded-full bg-amber-100 text-amber-800 text-xs font-semibold"></span>
            @endif
            <span data-lang-error="{{ $tabLang }}" title="Chyba ve formuláři" class="hidden w-2 h-2 rounded-full bg-red-500"></span>
        </button>
        @endforeach
    </div>
    <button type="button" data-translate-all
            title="Doplní přes DeepL všechna prázdná anglická a slovenská pole z češtiny. Vyplněné překlady nepřepíše."
            class="ml-auto px-3 py-2 text-sm font-medium rounded-lg bg-blue-600 hover:bg-blue-700 text-white flex items-center gap-2 disabled:opacity-60 disabled:cursor-wait">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/></svg>
        <span data-translate-all-label>Přeložit vše</span>
    </button>
    <span data-translate-status class="w-full sm:w-auto text-xs text-gray-500" aria-live="polite"></span>
</div>

<script>
(function () {
    const bar = document.currentScript.previousElementSibling;
    const form = bar.closest('[data-i18n-form]');
    if (!form) return;

    const translateUrl = @json(route('admin.translate'));
    const csrfToken = @json(csrf_token());
    const status = bar.querySelector('[data-translate-status]');
    const allButton = bar.querySelector('[data-translate-all]');
    const allLabel = bar.querySelector('[data-translate-all-label]');

    const fieldIn = (pane) => pane.querySelector('textarea, input:not([type]), input[type="text"]');

    function sourceText(pane) {
        const source = pane.dataset.source ? form.querySelector(pane.dataset.source) : null;
        if (!source) return '';
        if (source.tagName === 'SELECT') {
            const option = source.options[source.selectedIndex];
            return source.value && option ? option.textContent.replace(/\p{Extended_Pictographic}|\p{Regional_Indicator}/gu, '').trim() : '';
        }
        return source.value.trim();
    }

    // Pole k překladu: má český zdroj a je prázdné
    function missing(lang) {
        return [...form.querySelectorAll(`[data-lang-pane="${lang}"][data-source]`)]
            .filter((pane) => { const field = fieldIn(pane); return field && !field.value.trim() && sourceText(pane) !== ''; });
    }

    function updateBadges() {
        bar.querySelectorAll('[data-lang-badge]').forEach((badge) => {
            const count = String(missing(badge.dataset.langBadge).length);
            // Jen při změně – zápis textu je mutace a MutationObserver níž by se jinak zacyklil
            if (badge.textContent !== count) badge.textContent = count;
            badge.classList.toggle('is-visible', count !== '0');
        });
    }

    // Pod EN/SK polem náhled českého textu, ať je při překladu vidět, z čeho se vychází
    function updatePreviews() {
        form.querySelectorAll('[data-lang-pane][data-source]').forEach((pane) => {
            let preview = pane.querySelector(':scope > [data-source-preview]');
            if (!preview) {
                preview = document.createElement('p');
                preview.dataset.sourcePreview = '';
                preview.className = 'text-xs text-gray-400 mt-1';
                pane.appendChild(preview);
            }
            const text = sourceText(pane);
            preview.textContent = text ? '🇨🇿 ' + (text.length > 180 ? text.slice(0, 180) + '…' : text) : '';
        });
    }

    function setLang(lang) {
        form.dataset.activeLang = lang;
        bar.querySelectorAll('[data-lang-tab]').forEach((tab) => tab.setAttribute('aria-selected', tab.dataset.langTab === lang ? 'true' : 'false'));
        if (lang !== 'cs') updatePreviews();
    }

    async function translateText(text, lang) {
        const response = await fetch(translateUrl, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
            body: JSON.stringify({ text, source_lang: 'CS', target_lang: lang }),
        });
        const data = await response.json();
        if (!data.success) throw new Error(data.error || 'Neznámá chyba');
        return data.translation;
    }

    async function translateAll() {
        const jobs = [...missing('en'), ...missing('sk')];
        if (jobs.length === 0) {
            status.textContent = 'Všechna pole s českým textem už jsou přeložená.';
            return;
        }

        allButton.disabled = true;
        let done = 0;
        const failed = [];
        // Postupně, ať se DeepL nezahltí; každé pole zvlášť (stejně jako tlačítka u polí)
        for (const pane of jobs) {
            const lang = pane.dataset.langPane;
            allLabel.textContent = `Překládám ${done + failed.length + 1}/${jobs.length}…`;
            try {
                const field = fieldIn(pane);
                field.value = await translateText(sourceText(pane), lang.toUpperCase());
                field.classList.add('ring-2', 'ring-green-500');
                setTimeout(() => field.classList.remove('ring-2', 'ring-green-500'), 1500);
                done++;
            } catch (error) {
                console.error('Translation error:', error);
                failed.push(pane);
            }
        }

        allButton.disabled = false;
        allLabel.textContent = 'Přeložit vše';
        status.textContent = `Přeloženo ${done} ${done === 1 ? 'pole' : (done >= 2 && done <= 4 ? 'pole' : 'polí')}`
            + (failed.length ? `, ${failed.length} se nepodařilo (zkuste znovu nebo tlačítkem u pole)` : '')
            + '. Zkontrolujte překlady a formulář uložte.';
        updateBadges();
        updatePreviews();
    }

    bar.querySelectorAll('[data-lang-tab]').forEach((tab) => tab.addEventListener('click', () => setLang(tab.dataset.langTab)));
    allButton.addEventListener('click', translateAll);
    form.addEventListener('input', updateBadges);
    form.addEventListener('change', updateBadges);
    // Řádky vlastních parametrů přidané JavaScriptem
    new MutationObserver(updateBadges).observe(form, { childList: true, subtree: true });

    document.addEventListener('DOMContentLoaded', () => {
        // Chyba validace ve skryté záložce: označit záložku a otevřít první takovou
        let firstErrorLang = null;
        ['cs', 'en', 'sk'].forEach((lang) => {
            const hasError = [...form.querySelectorAll(`[data-lang-pane="${lang}"]`)]
                .some((pane) => pane.querySelector('.text-red-600, .border-red-500'));
            bar.querySelector(`[data-lang-error="${lang}"]`).classList.toggle('hidden', !hasError);
            if (hasError && !firstErrorLang) firstErrorLang = lang;
        });
        setLang(firstErrorLang || 'cs');
        updateBadges();
    });
})();
</script>
