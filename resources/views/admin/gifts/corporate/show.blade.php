@extends('layouts.admin')

@section('title', 'Firemní dárky: ' . $inquiry->company . ' - Admin Panel')

@php
    use App\Models\CorporateInquiry;
    $input = 'w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:border-gray-900 focus:ring-gray-900';
    $labelClass = 'block text-sm font-medium text-gray-700 mb-1';
    $phoneHref = $inquiry->phone ? preg_replace('/[^\d+]/', '', $inquiry->phone) : null;
    $utm = array_filter((array) $inquiry->utm);
    $history = $inquiry->status_history ?? [];
@endphp

@section('content')
<div class="p-6 max-w-6xl">
    <div class="mb-6">
        <a href="{{ route('admin.gifts.corporate.index') }}" class="text-sm text-blue-600 hover:underline">&larr; Firemní dárky</a>
        <div class="flex flex-wrap items-center gap-3 mt-2">
            <h1 class="text-3xl font-bold text-gray-900">{{ $inquiry->company }}</h1>
            <x-admin.corporate-status :status="$inquiry->status" class="text-sm" />
        </div>
        <p class="text-gray-600 mt-1">Poptávka #{{ $inquiry->id }} · přijato {{ $inquiry->created_at->format('d.m.Y H:i') }}</p>
    </div>

    {{-- Hlášky success/error ukazuje layouts/admin --}}
    @if($errors->any())
    <div class="mb-6 border px-4 py-3 rounded-lg bg-red-50 border-red-200 text-red-800">Poptávka se neuložila, zkontrolujte zvýrazněná pole.</div>
    @endif

    <div class="grid lg:grid-cols-3 gap-6 items-start">
        <div class="space-y-6">
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <h2 class="font-semibold mb-4">Poptávka z webu</h2>
                <dl class="space-y-3 text-sm">
                    <div><dt class="text-gray-500">Kontaktní osoba</dt><dd>{{ $inquiry->name }}</dd></div>
                    <div><dt class="text-gray-500">E-mail</dt><dd><a href="mailto:{{ $inquiry->email }}" class="text-blue-600 hover:underline break-all">{{ $inquiry->email }}</a></dd></div>
                    <div><dt class="text-gray-500">Telefon</dt><dd>@if($inquiry->phone)<a href="tel:{{ $phoneHref }}" class="text-blue-600 hover:underline">{{ $inquiry->phone }}</a>@else – @endif</dd></div>
                    <div><dt class="text-gray-500">Pro koho</dt><dd>{{ $inquiry->audienceLabel() }}</dd></div>
                    <div><dt class="text-gray-500">Přibližný počet kusů</dt><dd>{{ $inquiry->quantityLabel() }} ks</dd></div>
                    <div><dt class="text-gray-500">Kdy má dárek dorazit</dt><dd>{{ $inquiry->delivery_date?->format('d.m.Y') ?? '–' }}</dd></div>
                    <div><dt class="text-gray-500">Poznámka</dt><dd class="whitespace-pre-line">{{ $inquiry->note ?: '–' }}</dd></div>
                    @if($utm)
                    <div><dt class="text-gray-500">Zdroj</dt><dd class="text-xs text-gray-600 break-all">@foreach($utm as $key => $value){{ $key }}={{ $value }}@if(! $loop->last)<br>@endif @endforeach</dd></div>
                    @endif
                </dl>
            </div>

            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <h2 class="font-semibold mb-4">Historie</h2>
                <ol class="space-y-3 text-sm border-l border-gray-200 pl-4">
                    <li>
                        <p class="text-gray-500">{{ $inquiry->created_at->format('d.m.Y H:i') }}</p>
                        <p>Přijato z webu</p>
                    </li>
                    @foreach($history as $entry)
                    <li>
                        <p class="text-gray-500">{{ \Carbon\Carbon::parse($entry['at'])->timezone(config('app.timezone'))->format('d.m.Y H:i') }}@if(! empty($entry['by'])) · {{ $entry['by'] }}@endif</p>
                        <p>{{ CorporateInquiry::STATUSES[$entry['from']] ?? $entry['from'] }} &rarr; <strong>{{ CorporateInquiry::STATUSES[$entry['to']] ?? $entry['to'] }}</strong></p>
                    </li>
                    @endforeach
                </ol>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.gifts.corporate.update', $inquiry) }}" class="lg:col-span-2 bg-white rounded-xl border border-gray-200 p-6 space-y-8" novalidate>
            <section>
                <h2 class="font-semibold mb-4">Stav</h2>
                <div class="flex flex-wrap gap-2" role="radiogroup" aria-label="Stav poptávky">
                    @foreach(CorporateInquiry::STATUSES as $value => $label)
                    <label class="cursor-pointer">
                        <input type="radio" name="status" value="{{ $value }}" class="peer sr-only" @checked(old('status', $inquiry->status) === $value)>
                        <span class="inline-block px-3 py-1.5 rounded-lg border text-sm border-gray-300 text-gray-700 hover:border-gray-500 peer-checked:bg-gray-900 peer-checked:border-gray-900 peer-checked:text-white peer-focus-visible:ring-2 peer-focus-visible:ring-gray-900 peer-focus-visible:ring-offset-2">{{ $label }}</span>
                    </label>
                    @endforeach
                </div>
                @error('status')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
            </section>

            <section>
                <h2 class="font-semibold mb-4">Deal</h2>
                <div class="grid sm:grid-cols-3 gap-4">
                    <div>
                        <label for="box_count" class="{{ $labelClass }}">Počet boxů</label>
                        <input type="number" min="1" step="1" name="box_count" id="box_count" value="{{ old('box_count', $inquiry->box_count) }}" placeholder="poptávka {{ $inquiry->quantityLabel() }}" class="{{ $input }} @error('box_count') border-red-500 @enderror">
                        @error('box_count')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="unit_price" class="{{ $labelClass }}">Cena za box <span class="font-normal text-gray-500">(Kč bez DPH)</span></label>
                        <input type="text" inputmode="decimal" name="unit_price" id="unit_price" value="{{ old('unit_price', CorporateInquiry::priceInput($inquiry->unit_price)) }}" class="{{ $input }} @error('unit_price') border-red-500 @enderror">
                        @error('unit_price')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="total_price" class="{{ $labelClass }}">Celkem <span class="font-normal text-gray-500">(Kč bez DPH)</span></label>
                        <input type="text" inputmode="decimal" name="total_price" id="total_price" value="{{ old('total_price', CorporateInquiry::priceInput($inquiry->total_price)) }}" class="{{ $input }} @error('total_price') border-red-500 @enderror">
                        @error('total_price')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @else
                        <p class="text-xs text-gray-500 mt-1">Prázdné = počet × cena za box. Dopravu nebo kartičky připočtěte ručně.</p>
                        @enderror
                    </div>
                </div>
                <div class="mt-4">
                    <label for="box_contents" class="{{ $labelClass }}">Obsah a personalizace</label>
                    <textarea name="box_contents" id="box_contents" rows="3" placeholder="Např. 2 × 250 g, espresso + filtr, páska a kartička s logem" class="{{ $input }} @error('box_contents') border-red-500 @enderror">{{ old('box_contents', $inquiry->box_contents) }}</textarea>
                    @error('box_contents')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
            </section>

            <section>
                <h2 class="font-semibold mb-4">Doručení</h2>
                <div class="grid sm:grid-cols-3 gap-4">
                    <div>
                        <label for="agreed_delivery_date" class="{{ $labelClass }}">Dohodnutý termín</label>
                        <input type="date" name="agreed_delivery_date" id="agreed_delivery_date" value="{{ old('agreed_delivery_date', $inquiry->agreed_delivery_date?->format('Y-m-d')) }}" class="{{ $input }} @error('agreed_delivery_date') border-red-500 @enderror">
                        @error('agreed_delivery_date')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @else
                        <p class="text-xs text-gray-500 mt-1">Firma zadala: {{ $inquiry->delivery_date?->format('d.m.Y') ?? 'nic' }}</p>
                        @enderror
                    </div>
                    <div class="sm:col-span-2">
                        <label for="delivery_address" class="{{ $labelClass }}">Doručovací adresa</label>
                        <textarea name="delivery_address" id="delivery_address" rows="3" placeholder="Firma, ulice, PSČ a město, kontakt pro kurýra. Při více adresách odkaz na seznam." class="{{ $input }} @error('delivery_address') border-red-500 @enderror">{{ old('delivery_address', $inquiry->delivery_address) }}</textarea>
                        @error('delivery_address')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
            </section>

            <section>
                <h2 class="font-semibold mb-4">Fakturace</h2>
                <div class="grid sm:grid-cols-3 gap-4">
                    <div>
                        <label for="billing_ico" class="{{ $labelClass }}">IČ</label>
                        <input type="text" name="billing_ico" id="billing_ico" value="{{ old('billing_ico', $inquiry->billing_ico) }}" class="{{ $input }} @error('billing_ico') border-red-500 @enderror">
                        @error('billing_ico')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="billing_dic" class="{{ $labelClass }}">DIČ</label>
                        <input type="text" name="billing_dic" id="billing_dic" value="{{ old('billing_dic', $inquiry->billing_dic) }}" class="{{ $input }} @error('billing_dic') border-red-500 @enderror">
                        @error('billing_dic')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div class="sm:row-span-2">
                        <label for="billing_address" class="{{ $labelClass }}">Fakturační adresa</label>
                        <textarea name="billing_address" id="billing_address" rows="5" placeholder="{{ $inquiry->company }}, ulice, PSČ a město" class="{{ $input }} @error('billing_address') border-red-500 @enderror">{{ old('billing_address', $inquiry->billing_address) }}</textarea>
                        @error('billing_address')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="invoice_number" class="{{ $labelClass }}">Číslo faktury</label>
                        <input type="text" name="invoice_number" id="invoice_number" value="{{ old('invoice_number', $inquiry->invoice_number) }}" class="{{ $input }} @error('invoice_number') border-red-500 @enderror">
                        @error('invoice_number')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="paid_at" class="{{ $labelClass }}">Zaplaceno dne</label>
                        <input type="date" name="paid_at" id="paid_at" value="{{ old('paid_at', $inquiry->paid_at?->format('Y-m-d')) }}" class="{{ $input }} @error('paid_at') border-red-500 @enderror">
                        @error('paid_at')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
            </section>

            <section>
                <label for="internal_note" class="block font-semibold mb-1">Interní poznámky</label>
                <p class="text-xs text-gray-500 mb-2">Průběh domluvy, co jsme slíbili a co chybí. Zákazník je nevidí.</p>
                <textarea name="internal_note" id="internal_note" rows="6" class="{{ $input }} @error('internal_note') border-red-500 @enderror">{{ old('internal_note', $inquiry->internal_note) }}</textarea>
                @error('internal_note')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
            </section>

            <div class="flex items-center gap-4 pt-2 border-t border-gray-100">
                @csrf
                @method('PUT')
                <button type="submit" class="mt-4 px-5 py-2 bg-gray-900 text-white rounded-lg text-sm font-medium hover:bg-gray-800">Uložit</button>
                @if($inquiry->updated_at && $inquiry->updated_at->ne($inquiry->created_at))
                <span class="mt-4 text-xs text-gray-500">Naposledy uloženo {{ $inquiry->updated_at->format('d.m.Y H:i') }}</span>
                @endif
            </div>
        </form>
    </div>
</div>

<script>
    // Placeholder pole Celkem ukazuje dopočet počet × cena za box
    (function () {
        const count = document.getElementById('box_count');
        const unit = document.getElementById('unit_price');
        const total = document.getElementById('total_price');
        const parse = (value) => parseFloat(String(value).replace(/[\s ]/g, '').replace(',', '.'));
        const update = () => {
            const result = parse(count.value) * parse(unit.value);
            total.placeholder = Number.isFinite(result) && result > 0
                ? result.toLocaleString('cs-CZ', { maximumFractionDigits: 2 })
                : '';
        };
        count.addEventListener('input', update);
        unit.addEventListener('input', update);
        update();
    })();
</script>
@endsection
