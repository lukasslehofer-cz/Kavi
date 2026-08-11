@extends('layouts.admin')

@section('title', 'Mail zákazníkům')

@section('content')
@php
    $segmentLabels = [
        'all' => 'Všichni',
        'customers' => 'Zákazníci (zaplacená objednávka nebo předplatné)',
        'subscribers' => 'Aktivní předplatitelé',
        'no_orders' => 'Registrovaní bez objednávky',
    ];
    $domainLabel = $locale === 'en' ? 'kavibox.com' : 'kavi.cz';
@endphp
<div class="p-6">
    <div class="max-w-5xl mx-auto">

        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Mail zákazníkům</h1>
            <p class="text-sm text-gray-500 mt-1">Vlastní zpráva odeslaná v Kavi šabloně. Jazyk určuje šablonu, odesílatele i doménu odkazů.</p>
        </div>

        <!-- Doména / jazyk -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h2 class="text-lg font-bold text-gray-900 mb-1">Doména</h2>
            <p class="text-sm text-gray-500 mb-4">Volba zároveň filtruje seznam příjemců. Smíšené publikum se posílá dvakrát.</p>

            <div class="flex flex-wrap gap-3">
                @foreach(['cs' => 'kavi.cz (česky)', 'en' => 'kavibox.com (English)'] as $key => $label)
                <a href="{{ route('admin.customer-mail.create', ['locale' => $key, 'segment' => $segment]) }}"
                   class="px-4 py-2 rounded-full text-sm font-medium border transition-colors {{ $locale === $key ? 'bg-gray-900 text-white border-gray-900' : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50' }}">
                    {{ $label }}
                </a>
                @endforeach
            </div>
        </div>

        <!-- Segment -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <form method="GET" action="{{ route('admin.customer-mail.create') }}" class="flex flex-wrap items-end gap-4">
                <input type="hidden" name="locale" value="{{ $locale }}">
                <div class="flex-1 min-w-[280px]">
                    <label for="segment" class="block text-sm font-medium text-gray-700 mb-1">Skupina příjemců</label>
                    <select name="segment" id="segment" onchange="this.form.submit()"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-gray-900 focus:ring-gray-900">
                        @foreach($segmentLabels as $key => $label)
                        <option value="{{ $key }}" @selected($segment === $key)>{{ $label }} ({{ $counts[$key] }})</option>
                        @endforeach
                    </select>
                </div>
                <noscript>
                    <button type="submit" class="px-4 py-2 bg-gray-900 text-white rounded-md hover:bg-gray-800">Filtrovat</button>
                </noscript>
            </form>
        </div>

        @if($recipients->isEmpty())
        <div class="bg-white rounded-lg shadow p-12 text-center">
            <p class="text-gray-500">Pro tuto doménu a skupinu nejsou žádní příjemci.</p>
        </div>
        @else
        <form method="POST" id="customer-mail-form" action="{{ route('admin.customer-mail.send') }}">
            @csrf
            <input type="hidden" name="locale" value="{{ $locale }}">
            <input type="hidden" name="segment" value="{{ $segment }}">

            <!-- Příjemci -->
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
                    <h2 class="text-lg font-bold text-gray-900">
                        Příjemci
                        <span class="text-sm font-normal text-gray-500">({{ $recipients->count() }} v seznamu)</span>
                    </h2>
                    <div class="flex items-center gap-3 text-sm">
                        <button type="button" id="select-shown" class="text-gray-700 underline hover:text-gray-900">Vybrat všechny zobrazené</button>
                        <button type="button" id="clear-selection" class="text-gray-700 underline hover:text-gray-900">Zrušit výběr</button>
                        <span id="selected-count" class="font-medium text-gray-900">Vybráno: 0</span>
                    </div>
                </div>

                <input type="search" id="recipient-search" placeholder="Hledat podle jména nebo e-mailu…"
                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-gray-900 focus:ring-gray-900 mb-4">

                <div id="recipient-list" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-2 overflow-y-auto" style="max-height: 480px;">
                    @foreach($recipients as $recipient)
                    <label data-search="{{ \Illuminate\Support\Str::lower($recipient->name.' '.$recipient->email) }}"
                           class="flex items-start gap-2 p-3 border border-gray-200 rounded-md hover:bg-gray-50 cursor-pointer">
                        <input type="checkbox" name="recipients[]" value="{{ $recipient->id }}"
                               @checked(in_array($recipient->id, old('recipients', [])))
                               class="recipient-checkbox mt-1 rounded border-gray-300 text-gray-900 focus:ring-gray-900">
                        <span class="min-w-0">
                            <span class="block text-sm font-medium text-gray-900 truncate">{{ $recipient->name }}</span>
                            <span class="block text-xs text-gray-500 truncate">{{ $recipient->email }}</span>
                        </span>
                    </label>
                    @endforeach
                </div>

                <p class="text-xs text-gray-500 mt-3">Najednou lze odeslat nejvýš {{ $maxRecipients }} mailů.</p>

                @error('recipients')
                    <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                @enderror
            </div>

            <!-- Zpráva -->
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <div class="mb-4">
                    <label for="subject" class="block text-sm font-medium text-gray-700 mb-1">Předmět</label>
                    <input type="text" name="subject" id="subject" required maxlength="200"
                           value="{{ old('subject') }}"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-gray-900 focus:ring-gray-900">
                    @error('subject')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="body" class="block text-sm font-medium text-gray-700 mb-1">Text zprávy</label>
                    <textarea name="body" id="body" rows="12" required maxlength="20000"
                              class="w-full rounded-md border-gray-300 shadow-sm focus:border-gray-900 focus:ring-gray-900">{{ old('body') }}</textarea>
                    <div class="text-xs text-gray-500 mt-1 space-y-0.5">
                        <p>Prázdný řádek oddělí odstavce, vložené odkazy se udělají klikací. HTML se nepoužije.</p>
                        <p>
                            Formátování:
                            <code class="px-1 py-0.5 bg-gray-100 rounded">**tučně**</code>,
                            řádek začínající <code class="px-1 py-0.5 bg-gray-100 rounded">-</code> udělá odrážku,
                            <code class="px-1 py-0.5 bg-gray-100 rounded">1.</code> číslovaný seznam.
                        </p>
                    </div>
                    @error('body')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="button_label" class="block text-sm font-medium text-gray-700 mb-1">Popisek tlačítka <span class="text-gray-400">(volitelné)</span></label>
                        <input type="text" name="button_label" id="button_label" maxlength="60"
                               value="{{ old('button_label') }}"
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-gray-900 focus:ring-gray-900">
                        @error('button_label')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="button_url" class="block text-sm font-medium text-gray-700 mb-1">Odkaz tlačítka <span class="text-gray-400">(volitelné)</span></label>
                        <input type="url" name="button_url" id="button_url" maxlength="500"
                               placeholder="https://{{ $domainLabel }}/…"
                               value="{{ old('button_url') }}"
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-gray-900 focus:ring-gray-900">
                        @error('button_url')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <p class="text-xs text-gray-500 mt-4 pt-4 border-t border-gray-100">
                    Servisní sdělení zákazníkům. Marketingové kampaně posílej přes Ecomail, ten má odhlašování.
                </p>
            </div>

            <!-- Akce -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex flex-wrap gap-3">
                    <button type="button" onclick="openPreview()"
                            class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50">
                        Náhled
                    </button>
                    <button type="submit" formaction="{{ route('admin.customer-mail.send-test') }}"
                            class="px-4 py-2 bg-gray-700 text-white rounded-md hover:bg-gray-800">
                        Poslat test na sebe
                    </button>
                    <button type="submit" id="send-button" onclick="return confirmSend()"
                            class="px-4 py-2 bg-gray-900 text-white rounded-md hover:bg-black">
                        Odeslat vybraným
                    </button>
                </div>
                <p class="text-xs text-gray-500 mt-3">Náhled i test se generují pro prvního vybraného příjemce, jinak pro tebe.</p>
            </div>
        </form>

        <!-- Náhled -->
        <div id="preview-wrapper" class="bg-white rounded-lg shadow p-6 mt-6 hidden">
            <h2 class="text-lg font-bold text-gray-900 mb-4">Náhled</h2>
            <iframe id="preview-frame" name="preview-frame" class="w-full border border-gray-200 rounded" style="height: 700px;"></iframe>
        </div>

        <form method="POST" action="{{ route('admin.customer-mail.preview') }}" target="preview-frame" id="preview-form" class="hidden">
            @csrf
            <input type="hidden" name="locale" value="{{ $locale }}">
            <input type="hidden" name="segment" value="{{ $segment }}">
            <input type="hidden" name="subject" id="preview-subject">
            <input type="hidden" name="body" id="preview-body">
            <input type="hidden" name="button_label" id="preview-button-label">
            <input type="hidden" name="button_url" id="preview-button-url">
            <div id="preview-recipients"></div>
        </form>
        @endif
    </div>
</div>

@if($recipients->isNotEmpty())
<script>
(function () {
    const list = document.getElementById('recipient-list');
    const boxes = () => Array.from(list.querySelectorAll('.recipient-checkbox'));
    const shown = () => boxes().filter(cb => cb.closest('label').style.display !== 'none');
    const selected = () => boxes().filter(cb => cb.checked);
    const counter = document.getElementById('selected-count');
    const maxRecipients = {{ $maxRecipients }};

    function refreshCount() {
        const n = selected().length;
        counter.textContent = 'Vybráno: ' + n;
        counter.classList.toggle('text-red-600', n > maxRecipients);
    }

    list.addEventListener('change', refreshCount);

    // Musí přeskočit skryté řádky, jinak by hledání bylo past
    document.getElementById('select-shown').addEventListener('click', function () {
        shown().forEach(cb => { cb.checked = true; });
        refreshCount();
    });

    document.getElementById('clear-selection').addEventListener('click', function () {
        boxes().forEach(cb => { cb.checked = false; });
        refreshCount();
    });

    document.getElementById('recipient-search').addEventListener('input', function () {
        const q = this.value.trim().toLowerCase();
        list.querySelectorAll('[data-search]').forEach(row => {
            row.style.display = (!q || row.dataset.search.includes(q)) ? '' : 'none';
        });
    });

    // Rozepsaný text přežije přepnutí domény nebo skupiny
    const storageKey = 'kavi-customer-mail-{{ $locale }}-';
    const drafted = ['subject', 'body', 'button_label', 'button_url'];

    @if(session('success'))
        drafted.forEach(id => sessionStorage.removeItem(storageKey + id));
    @endif

    drafted.forEach(id => {
        const el = document.getElementById(id);
        const saved = sessionStorage.getItem(storageKey + id);
        if (saved && !el.value) el.value = saved;
        el.addEventListener('input', () => sessionStorage.setItem(storageKey + id, el.value));
    });

    window.confirmSend = function () {
        const n = selected().length;
        if (n === 0) {
            alert('Vyberte alespoň jednoho příjemce.');
            return false;
        }
        if (n > maxRecipients) {
            alert('Najednou lze odeslat nejvýš ' + maxRecipients + ' mailů. Vybráno: ' + n + '.');
            return false;
        }
        return confirm('Opravdu odeslat zprávu ' + n + ' příjemcům na ' + @json($domainLabel) + '?');
    };

    window.openPreview = function () {
        const container = document.getElementById('preview-recipients');
        container.innerHTML = '';

        // Náhled se renderuje pro prvního vybraného příjemce
        const first = selected()[0];
        if (first) {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'recipients[]';
            input.value = first.value;
            container.appendChild(input);
        }

        document.getElementById('preview-subject').value = document.getElementById('subject').value;
        document.getElementById('preview-body').value = document.getElementById('body').value;
        document.getElementById('preview-button-label').value = document.getElementById('button_label').value;
        document.getElementById('preview-button-url').value = document.getElementById('button_url').value;

        const wrapper = document.getElementById('preview-wrapper');
        wrapper.classList.remove('hidden');
        document.getElementById('preview-form').submit();
        wrapper.scrollIntoView({ behavior: 'smooth' });
    };

    refreshCount();
})();
</script>
@endif
@endsection
