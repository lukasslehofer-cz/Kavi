@extends('layouts.admin')

@section('title', 'Fakturační údaje zákazníka - Admin Panel')

@section('content')
<div class="p-6 max-w-6xl">
    <!-- Header -->
    <div class="mb-8">
        @if($backUrl)
        <a href="{{ $backUrl }}" class="inline-flex items-center gap-2 text-sm text-gray-600 hover:text-gray-900 mb-3">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Zpět
        </a>
        @endif
        <h1 class="text-3xl font-bold text-gray-900">Fakturační údaje zákazníka</h1>
        <p class="text-gray-600 mt-1">{{ $user->name }} &middot; {{ $user->email }}</p>
    </div>

    <!-- Success Message -->
    @if(session('success'))
    <div class="mb-6 bg-green-50 border border-green-200 rounded-xl p-4">
        <div class="flex items-center gap-2">
            <svg class="w-5 h-5 text-green-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <span class="text-sm text-green-800 font-medium">{{ session('success') }}</span>
        </div>
    </div>
    @endif

    <!-- Info Box -->
    <div class="mb-6 bg-blue-50 border border-blue-200 rounded-xl p-4">
        <div class="flex items-start gap-3">
            <svg class="w-5 h-5 text-blue-600 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
            </svg>
            <div class="text-sm text-blue-800 space-y-1">
                <p>Tyto údaje se použijí na <strong>všech budoucích fakturách</strong> tohoto zákazníka – u objednávek i u předplatného. Už vystavené faktury se nemění.</p>
                <p>Doručovací adresa a štítky Zásilkovny zůstávají beze změny – ty se dál berou z objednávky.</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Form -->
        <div class="lg:col-span-2">
            <form action="{{ route('admin.customers.billing.update', $user) }}" method="POST" class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                @csrf
                @method('PUT')
                <input type="hidden" name="back" value="{{ request()->query('back') }}">

                <!-- Toggle -->
                <label class="flex items-start gap-3 pb-6 border-b border-gray-200 cursor-pointer">
                    <input type="hidden" name="invoice_override" value="0">
                    <input type="checkbox" name="invoice_override" value="1" class="mt-1 rounded border-gray-300"
                           {{ old('invoice_override', $user->invoice_override) ? 'checked' : '' }}>
                    <span>
                        <span class="block text-sm font-medium text-gray-900">Použít vlastní fakturační údaje</span>
                        <span class="block text-sm text-gray-600 mt-0.5">Když je vypnuté, faktury se vystaví na jméno a adresu z objednávky jako dosud.</span>
                    </span>
                </label>

                <!-- Company -->
                <div class="pt-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Název firmy</label>
                        <input type="text" name="invoice_company" value="{{ old('invoice_company', $user->invoice_company) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('invoice_company') border-red-500 @enderror">
                        <p class="text-xs text-gray-500 mt-1">Tiskne se jako odběratel. Pokud fakturujete fyzické osobě, nechte prázdné a vyplňte jméno níže.</p>
                        @error('invoice_company')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">IČ</label>
                            <input type="text" name="invoice_registration_no" value="{{ old('invoice_registration_no', $user->invoice_registration_no) }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('invoice_registration_no') border-red-500 @enderror">
                            @error('invoice_registration_no')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">DIČ</label>
                            <input type="text" name="invoice_vat_no" value="{{ old('invoice_vat_no', $user->invoice_vat_no) }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('invoice_vat_no') border-red-500 @enderror">
                            <p class="text-xs text-gray-500 mt-1">Jen se vytiskne na fakturu, DPH neovlivňuje.</p>
                            @error('invoice_vat_no')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Jméno / kontaktní osoba</label>
                        <input type="text" name="invoice_name" value="{{ old('invoice_name', $user->invoice_name) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('invoice_name') border-red-500 @enderror">
                        @error('invoice_name')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Address -->
                <div class="pt-6 mt-6 border-t border-gray-200 space-y-4">
                    <h3 class="text-sm font-semibold text-gray-900">Fakturační adresa</h3>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Ulice a číslo</label>
                        <input type="text" name="invoice_street" value="{{ old('invoice_street', $user->invoice_street) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('invoice_street') border-red-500 @enderror">
                        @error('invoice_street')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">PSČ</label>
                            <input type="text" name="invoice_zip" value="{{ old('invoice_zip', $user->invoice_zip) }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('invoice_zip') border-red-500 @enderror">
                            @error('invoice_zip')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Město</label>
                            <input type="text" name="invoice_city" value="{{ old('invoice_city', $user->invoice_city) }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('invoice_city') border-red-500 @enderror">
                            @error('invoice_city')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Země</label>
                        @php $selectedCountry = old('invoice_country', $user->invoice_country ?: 'CZ'); @endphp
                        <select name="invoice_country"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('invoice_country') border-red-500 @enderror">
                            @foreach(['CZ' => 'Česká republika', 'SK' => 'Slovensko', 'PL' => 'Polsko', 'HU' => 'Maďarsko', 'AT' => 'Rakousko', 'DE' => 'Německo', 'RO' => 'Rumunsko', 'SI' => 'Slovinsko', 'HR' => 'Chorvatsko', 'BG' => 'Bulharsko'] as $code => $label)
                            <option value="{{ $code }}" {{ $selectedCountry === $code ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('invoice_country')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Fakturoid sync -->
                <div class="pt-6 mt-6 border-t border-gray-200">
                    <label class="flex items-start gap-3 cursor-pointer">
                        <input type="checkbox" name="sync_fakturoid" value="1" class="mt-1 rounded border-gray-300" checked>
                        <span>
                            <span class="block text-sm font-medium text-gray-900">Rovnou aktualizovat subjekt ve Fakturoidu</span>
                            <span class="block text-sm text-gray-600 mt-0.5">Odešle údaje do Fakturoidu hned po uložení, ať si je můžete zkontrolovat. Bez zaškrtnutí se propíšou až s další fakturou.</span>
                        </span>
                    </label>
                </div>

                <div class="flex items-center gap-3 mt-8">
                    <button type="submit" class="px-5 py-2 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-800 transition-colors">
                        Uložit
                    </button>
                    @if($backUrl)
                    <a href="{{ $backUrl }}" class="px-5 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors">
                        Zrušit
                    </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Preview -->
        <div class="space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-sm font-semibold text-gray-900 mb-4">Odběratel na faktuře</h3>

                @php $override = $user->fakturoidSubjectOverride(); @endphp

                @if($override)
                <div class="text-sm text-gray-700 space-y-1">
                    <p class="font-semibold text-gray-900">{{ $override['name'] }}</p>
                    @if($override['full_name'])
                    <p>{{ $override['full_name'] }}</p>
                    @endif
                    <p>{{ $override['street'] }}</p>
                    <p>{{ $override['zip'] }} {{ $override['city'] }}</p>
                    <p>{{ $override['country'] }}</p>
                    @if($override['registration_no'])
                    <p class="pt-2">IČ: {{ $override['registration_no'] }}</p>
                    @endif
                    @if($override['vat_no'])
                    <p>DIČ: {{ $override['vat_no'] }}</p>
                    @endif
                </div>
                <p class="text-xs text-gray-500 mt-4 pt-4 border-t border-gray-100">E-mail a telefon se přebírají z objednávky, vlastní údaje je nepřepisují.</p>
                @else
                <div class="text-sm text-gray-700 space-y-1">
                    <p class="font-semibold text-gray-900">{{ $user->name }}</p>
                    <p>{{ $user->address ?: '—' }}</p>
                    <p>{{ $user->postal_code }} {{ $user->city }}</p>
                    <p>{{ $user->country }}</p>
                </div>
                <p class="text-xs text-gray-500 mt-4 pt-4 border-t border-gray-100">Vlastní fakturační údaje jsou vypnuté. Faktura se vystaví na adresu z konkrétní objednávky.</p>
                @endif
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-sm font-semibold text-gray-900 mb-3">Fakturoid</h3>
                @if($user->fakturoid_subject_id)
                <p class="text-sm text-gray-600">Subjekt <span class="font-mono text-gray-900">#{{ $user->fakturoid_subject_id }}</span></p>
                @else
                <p class="text-sm text-gray-600">Zákazník zatím subjekt nemá – vznikne s jeho první fakturou.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
