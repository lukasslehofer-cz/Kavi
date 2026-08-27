@extends('layouts.admin')

@section('title', 'Hlášky - Admin Panel')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Hlášky</h1>
        <p class="text-gray-600 mt-1">Správa oznámení v záhlaví webu a v pokladně</p>
    </div>

    @if($errors->any())
    <div class="mb-8 rounded-xl border border-red-200 bg-red-50 p-4">
        <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Create New Banner Form -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-8">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-xl font-bold text-gray-900">Nová hláška</h2>
            <p class="text-sm text-gray-500 mt-1">Vytvořte novou hlášku a vyberte, kde se má zobrazit</p>
        </div>
        <form action="{{ route('admin.announcements.store') }}" method="POST" class="p-6">
            @csrf
            <input type="hidden" name="_submitted" value="1">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Czech Message -->
                <div>
                    <label for="message_cs" class="block text-sm font-medium text-gray-700 mb-2">
                        Text (CZ) <span class="text-red-500">*</span>
                    </label>
                    <textarea name="message_cs" id="message_cs" rows="2" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent"
                        placeholder="Např.: Od 20. 12. do 3. 1. neprobíhá rozesílka. Objednávky odešleme 5. 1.">{{ old('message_cs') }}</textarea>
                    <p class="text-xs text-gray-500 mt-1">Pouze prostý text, HTML tagy se vypíšou jako text</p>
                </div>

                <!-- English Message -->
                <div>
                    <label for="message_en" class="block text-sm font-medium text-gray-700 mb-2">
                        Text (EN)
                    </label>
                    <textarea name="message_en" id="message_en" rows="2"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent"
                        placeholder="Např.: No dispatch between Dec 20 and Jan 3. Orders ship on Jan 5.">{{ old('message_en') }}</textarea>
                    <p class="text-xs text-gray-500 mt-1">Volitelně pro anglickou verzi webu</p>
                </div>

                <!-- Czech Title -->
                <div>
                    <label for="title_cs" class="block text-sm font-medium text-gray-700 mb-2">
                        Nadpis (CZ)
                    </label>
                    <input type="text" name="title_cs" id="title_cs"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent"
                        placeholder="Např.: Odesílání zásilek"
                        value="{{ old('title_cs') }}">
                    <p class="text-xs text-gray-500 mt-1">Zobrazí se jen v pokladně. Prázdné = „Informace"</p>
                </div>

                <!-- English Title -->
                <div>
                    <label for="title_en" class="block text-sm font-medium text-gray-700 mb-2">
                        Nadpis (EN)
                    </label>
                    <input type="text" name="title_en" id="title_en"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent"
                        placeholder="Např.: Shipping"
                        value="{{ old('title_en') }}">
                    <p class="text-xs text-gray-500 mt-1">Zobrazí se jen v pokladně. Prázdné = „Notice"</p>
                </div>

                <!-- Icon Selection -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Ikona <span class="text-red-500">*</span>
                    </label>
                    <div class="grid grid-cols-4 sm:grid-cols-8 gap-2">
                        @foreach($icons as $key => $icon)
                        <label class="relative cursor-pointer">
                            <input type="radio" name="icon" value="{{ $key }}" class="peer sr-only" {{ $key === 'check' ? 'checked' : '' }}>
                            <div class="flex flex-col items-center justify-center p-3 border-2 border-gray-200 rounded-lg peer-checked:border-gray-900 peer-checked:bg-gray-50 hover:border-gray-300 transition-colors">
                                <svg class="w-6 h-6 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon['path'] }}" />
                                </svg>
                                <span class="text-xs text-gray-500 mt-1">{{ $icon['name'] }}</span>
                            </div>
                        </label>
                        @endforeach
                    </div>
                </div>

                <!-- Placement -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Umístění <span class="text-red-500">*</span>
                    </label>
                    <div class="space-y-2">
                        @foreach($placements as $key => $label)
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" name="{{ $key }}" value="1"
                                class="w-4 h-4 rounded border-gray-300 text-gray-900 focus:ring-gray-900"
                                {{ (old('_submitted') ? old($key) : $key === \App\Models\AnnouncementBanner::PLACEMENT_HEADER) ? 'checked' : '' }}>
                            <span class="text-sm text-gray-700">{{ $label }}</span>
                        </label>
                        @endforeach
                    </div>
                    <p class="text-xs text-gray-500 mt-2">Vyberte alespoň jedno</p>
                </div>

                <!-- Active Status -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Stav
                    </label>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" class="sr-only peer" checked>
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-gray-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-gray-900"></div>
                        <span class="ml-3 text-sm font-medium text-gray-700">Aktivní</span>
                    </label>
                </div>

                <!-- Active From -->
                <div>
                    <label for="active_from" class="block text-sm font-medium text-gray-700 mb-2">
                        Platnost od
                    </label>
                    <input type="datetime-local" name="active_from" id="active_from"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent"
                        value="{{ old('active_from') }}">
                    <p class="text-xs text-gray-500 mt-1">Ponechte prázdné pro okamžitou platnost</p>
                </div>

                <!-- Active Until -->
                <div>
                    <label for="active_until" class="block text-sm font-medium text-gray-700 mb-2">
                        Platnost do
                    </label>
                    <input type="datetime-local" name="active_until" id="active_until"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent"
                        value="{{ old('active_until') }}">
                    <p class="text-xs text-gray-500 mt-1">Ponechte prázdné pro neomezenou platnost</p>
                </div>
            </div>

            <div class="mt-6 flex justify-end">
                <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-800 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Vytvořit hlášku
                </button>
            </div>
        </form>
    </div>

    <!-- Existing Banners -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-xl font-bold text-gray-900">Existující hlášky</h2>
            <p class="text-sm text-gray-500 mt-1">Přehled všech hlášek a jejich stavu</p>
        </div>
        
        @if($banners->isEmpty())
        <div class="p-12 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
            </svg>
            <p class="text-gray-500">Zatím žádné hlášky</p>
        </div>
        @else
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ikona</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Text</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Umístění</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Platnost</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stav</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Akce</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($banners as $banner)
                    <tr class="hover:bg-gray-50 transition-colors" id="banner-row-{{ $banner->id }}">
                        <td class="px-4 py-4 whitespace-nowrap">
                            <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $banner->getIconPath() }}" />
                                </svg>
                            </div>
                        </td>
                        <td class="px-4 py-4">
                            <div class="max-w-xs">
                                @if($banner->title_cs)
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">{{ $banner->title_cs }}</p>
                                @endif
                                <p class="text-sm text-gray-900 truncate">{{ $banner->message_cs }}</p>
                                @if($banner->message_en)
                                <p class="text-xs text-gray-500 truncate mt-1">EN: {{ $banner->message_en }}</p>
                                @endif
                            </div>
                        </td>
                        <td class="px-4 py-4">
                            <div class="flex flex-col items-start gap-1">
                                @foreach(\App\Models\AnnouncementBanner::PLACEMENTS_SHORT as $key => $shortLabel)
                                    @if($banner->{$key})
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-700 whitespace-nowrap" title="{{ $placements[$key] }}">{{ $shortLabel }}</span>
                                    @endif
                                @endforeach
                            </div>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-600">
                                @if($banner->active_from)
                                <div>Od: {{ $banner->active_from->format('d.m.Y H:i') }}</div>
                                @else
                                <div class="text-gray-400">Od: ihned</div>
                                @endif
                                @if($banner->active_until)
                                <div>Do: {{ $banner->active_until->format('d.m.Y H:i') }}</div>
                                @else
                                <div class="text-gray-400">Do: neomezeně</div>
                                @endif
                            </div>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap">
                            @php $statusColor = $banner->getStatusColor(); @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $statusColor }}-100 text-{{ $statusColor }}-800">
                                {{ $banner->getStatusLabel() }}
                            </span>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-2">
                                <!-- Toggle Active -->
                                <form action="{{ route('admin.announcements.toggle', $banner) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition-colors" title="{{ $banner->is_active ? 'Deaktivovat' : 'Aktivovat' }}">
                                        @if($banner->is_active)
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                        </svg>
                                        @else
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        @endif
                                    </button>
                                </form>
                                
                                <!-- Edit Button -->
                                <button type="button" onclick="openEditModal({{ $banner->id }})" class="p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition-colors" title="Upravit">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </button>

                                <!-- Delete Button -->
                                <form action="{{ route('admin.announcements.destroy', $banner) }}" method="POST" class="inline" onsubmit="return confirm('Opravdu chcete smazat tuto hlášku?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-red-500 hover:text-red-700 hover:bg-red-50 rounded-lg transition-colors" title="Smazat">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>

    <!-- Previews -->
    @php
        $bannerModel = \App\Models\AnnouncementBanner::class;
        $headerBanner = $bannerModel::getCurrentFor($bannerModel::PLACEMENT_HEADER);
        $checkoutBanner = $bannerModel::getCurrentFor($bannerModel::PLACEMENT_CHECKOUT);
        $subscriptionBanner = $bannerModel::getCurrentFor($bannerModel::PLACEMENT_SUBSCRIPTION_CHECKOUT);
    @endphp

    <div class="mt-8 bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-xl font-bold text-gray-900">Náhled v záhlaví webu</h2>
            <p class="text-sm text-gray-500 mt-1">Takto vypadá tmavý pruh nad navigací</p>
        </div>
        <div class="p-6">
            @if($headerBanner)
            <div class="bg-dark-800 rounded-lg">
                <div class="flex items-center justify-center gap-3 px-4 py-2.5">
                    <svg class="w-4 h-4 text-primary-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $headerBanner->getIconPath() }}" />
                    </svg>
                    <div class="text-sm text-white font-light tracking-wide">
                        {{ $headerBanner->getMessage('cs') }}
                    </div>
                </div>
            </div>
            <p class="text-sm text-gray-500 mt-3">
                Aktivní hláška: ID #{{ $headerBanner->id }} | Vytvořeno: {{ $headerBanner->created_at->format('d.m.Y H:i') }}
            </p>
            @else
            <div class="text-center py-8 text-gray-500">
                <svg class="mx-auto h-12 w-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                </svg>
                <p>Žádná aktivní hláška - pruh je skrytý</p>
            </div>
            @endif
        </div>
    </div>

    <div class="mt-8 bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-xl font-bold text-gray-900">Náhled v pokladně</h2>
            <p class="text-sm text-gray-500 mt-1">Takto vypadá hláška v pokladně a v potvrzovacím e-mailu</p>
        </div>
        <div class="p-6 grid grid-cols-1 lg:grid-cols-2 gap-6">
            @foreach([
                'Jednorázový nákup' => $checkoutBanner,
                'Předplatné' => $subscriptionBanner,
            ] as $label => $preview)
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-3">{{ $label }}</p>
                @if($preview)
                <div class="p-6 rounded-lg" style="background-color: #e5e6df;">
                    @include('partials.checkout-notice', ['notice' => $preview, 'currentLocale' => 'cs'])
                </div>
                <p class="text-sm text-gray-500 mt-3">Aktivní hláška: ID #{{ $preview->id }}</p>
                @else
                <div class="text-center py-8 text-gray-500 border border-dashed border-gray-300 rounded-lg">
                    <p class="text-sm">Žádná aktivní hláška</p>
                </div>
                @endif
            </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Edit Modal -->
@foreach($banners as $banner)
<div id="edit-modal-{{ $banner->id }}" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeEditModal({{ $banner->id }})"></div>
        
        <div class="relative inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
            <form action="{{ route('admin.announcements.update', $banner) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="bg-white px-6 pt-6 pb-4">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Upravit hlášku #{{ $banner->id }}</h3>
                    
                    <div class="space-y-4">
                        <!-- Czech Message -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Text (CZ) *</label>
                            <textarea name="message_cs" rows="2" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent">{{ $banner->message_cs }}</textarea>
                        </div>

                        <!-- English Message -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Text (EN)</label>
                            <textarea name="message_en" rows="2"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent">{{ $banner->message_en }}</textarea>
                        </div>

                        <!-- Titles (checkout only) -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nadpis (CZ)</label>
                                <input type="text" name="title_cs"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent"
                                    value="{{ $banner->title_cs }}">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nadpis (EN)</label>
                                <input type="text" name="title_en"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent"
                                    value="{{ $banner->title_en }}">
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 -mt-2">Nadpis se zobrazí jen v pokladně, v záhlaví se ignoruje.</p>

                        <!-- Icon Selection -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Ikona *</label>
                            <div class="grid grid-cols-4 sm:grid-cols-8 gap-2">
                                @foreach($icons as $key => $icon)
                                <label class="relative cursor-pointer">
                                    <input type="radio" name="icon" value="{{ $key }}" class="peer sr-only" {{ $banner->icon === $key ? 'checked' : '' }}>
                                    <div class="flex flex-col items-center justify-center p-2 border-2 border-gray-200 rounded-lg peer-checked:border-gray-900 peer-checked:bg-gray-50 hover:border-gray-300 transition-colors">
                                        <svg class="w-5 h-5 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon['path'] }}" />
                                        </svg>
                                    </div>
                                </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Placement -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Umístění *</label>
                            <div class="space-y-2">
                                @foreach($placements as $key => $label)
                                <label class="flex items-center gap-3 cursor-pointer">
                                    <input type="checkbox" name="{{ $key }}" value="1"
                                        class="w-4 h-4 rounded border-gray-300 text-gray-900 focus:ring-gray-900"
                                        {{ $banner->{$key} ? 'checked' : '' }}>
                                    <span class="text-sm text-gray-700">{{ $label }}</span>
                                </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Active Status -->
                        <div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="is_active" value="1" class="sr-only peer" {{ $banner->is_active ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-gray-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-gray-900"></div>
                                <span class="ml-3 text-sm font-medium text-gray-700">Aktivní</span>
                            </label>
                        </div>

                        <!-- Dates -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Platnost od</label>
                                <input type="datetime-local" name="active_from"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent"
                                    value="{{ $banner->active_from ? $banner->active_from->format('Y-m-d\TH:i') : '' }}">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Platnost do</label>
                                <input type="datetime-local" name="active_until"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent"
                                    value="{{ $banner->active_until ? $banner->active_until->format('Y-m-d\TH:i') : '' }}">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3">
                    <button type="button" onclick="closeEditModal({{ $banner->id }})" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                        Zrušit
                    </button>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg hover:bg-gray-800 transition-colors">
                        Uložit změny
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

<script>
function openEditModal(id) {
    document.getElementById('edit-modal-' + id).classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeEditModal(id) {
    document.getElementById('edit-modal-' + id).classList.add('hidden');
    document.body.style.overflow = '';
}

// Close modal on escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        document.querySelectorAll('[id^="edit-modal-"]').forEach(modal => {
            modal.classList.add('hidden');
        });
        document.body.style.overflow = '';
    }
});
</script>
@endsection

