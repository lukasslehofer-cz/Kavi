@extends('layouts.dashboard')

@section('title', 'Notifikace - Kavi Coffee')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="bg-white rounded-2xl p-6 border border-gray-200">
        <div class="flex justify-between items-center flex-wrap gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Notifikace</h1>
                <p class="mt-1 text-gray-600 font-light">Přehled všech vašich oznámení</p>
            </div>
            <button type="button" 
                    onclick="alert('Označit vše jako přečtené - bude implementováno')"
                    class="bg-gray-100 text-gray-900 hover:bg-gray-200 font-medium px-6 py-2.5 rounded-full transition-all duration-200">
                <svg class="w-4 h-4 inline-block mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                Označit vše jako přečtené
            </button>
        </div>
    </div>

    <!-- Notifications List -->
    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
        <!-- Sample Notification - New Order -->
        <div class="border-b border-gray-200 hover:bg-gray-50 transition-colors">
            <div class="p-6">
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 rounded-full bg-gray-900 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex justify-between items-start gap-4 mb-2">
                            <h3 class="text-base font-bold text-gray-900">Objednávka byla potvrzena</h3>
                            <span class="w-2 h-2 bg-primary-500 rounded-full flex-shrink-0 mt-2"></span>
                        </div>
                        <p class="text-gray-600 font-light mb-3">
                            Vaše objednávka #1234 byla úspěšně potvrzena a bude odeslána v nejbližších dnech.
                        </p>
                        <div class="flex items-center gap-4 text-sm text-gray-500 font-light">
                            <span class="flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Před 2 hodinami
                            </span>
                            <button type="button" class="text-primary-600 hover:text-primary-700 font-medium">
                                Zobrazit detail
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sample Notification - Shipment -->
        <div class="border-b border-gray-200 hover:bg-gray-50 transition-colors">
            <div class="p-6">
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 rounded-full bg-gray-900 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex justify-between items-start gap-4 mb-2">
                            <h3 class="text-base font-bold text-gray-900">Zásilka byla odeslána</h3>
                            <span class="w-2 h-2 bg-primary-500 rounded-full flex-shrink-0 mt-2"></span>
                        </div>
                        <p class="text-gray-600 font-light mb-3">
                            Vaše zásilka z předplatného byla odeslána přes Zásilkovnu. Číslo zásilky: Z987654321
                        </p>
                        <div class="flex items-center gap-4 text-sm text-gray-500 font-light">
                            <span class="flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Včera v 14:30
                            </span>
                            <button type="button" class="text-primary-600 hover:text-primary-700 font-medium">
                                Sledovat zásilku
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sample Notification - Subscription Renewal -->
        <div class="border-b border-gray-200 hover:bg-gray-50 transition-colors bg-gray-50">
            <div class="p-6">
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 rounded-full bg-gray-900 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex justify-between items-start gap-4 mb-2">
                            <h3 class="text-base font-bold text-gray-900">Předplatné bude obnoveno</h3>
                        </div>
                        <p class="text-gray-600 font-light mb-3">
                            Vaše předplatné bude automaticky obnoveno 20. listopadu 2025. Částka 890 Kč bude odečtena z vaší karty.
                        </p>
                        <div class="flex items-center gap-4 text-sm text-gray-500 font-light">
                            <span class="flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Před 3 dny
                            </span>
                            <button type="button" class="text-primary-600 hover:text-primary-700 font-medium">
                                Spravovat předplatné
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sample Notification - Promotion -->
        <div class="border-b border-gray-200 hover:bg-gray-50 transition-colors bg-gray-50">
            <div class="p-6">
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 rounded-full bg-gray-900 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7" />
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex justify-between items-start gap-4 mb-2">
                            <h3 class="text-base font-bold text-gray-900">Speciální nabídka pro vás! 🎉</h3>
                        </div>
                        <p class="text-gray-600 font-light mb-3">
                            Získejte 20% slevu na všechny jednotlivé balení kávy. Nabídka platí do konce měsíce.
                        </p>
                        <div class="flex items-center gap-4 text-sm text-gray-500 font-light">
                            <span class="flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Před týdnem
                            </span>
                            <button type="button" class="text-primary-600 hover:text-primary-700 font-medium">
                                Prohlédnout nabídku
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sample Notification - Review Request -->
        <div class="border-b border-gray-200 hover:bg-gray-50 transition-colors bg-gray-50">
            <div class="p-6">
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 rounded-full bg-gray-900 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex justify-between items-start gap-4 mb-2">
                            <h3 class="text-base font-bold text-gray-900">Jak se vám líbí naše káva?</h3>
                        </div>
                        <p class="text-gray-600 font-light mb-3">
                            Sdílejte s námi vaše zkušenosti. Vaše zpětná vazba nám pomáhá zlepšovat naše služby.
                        </p>
                        <div class="flex items-center gap-4 text-sm text-gray-500 font-light">
                            <span class="flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Před 2 týdny
                            </span>
                            <button type="button" class="text-primary-600 hover:text-primary-700 font-medium">
                                Napsat hodnocení
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Empty State (hidden when there are notifications) -->
        <div class="hidden text-center py-12 px-4">
            <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-gray-100 flex items-center justify-center">
                <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
            </div>
            <h3 class="text-base font-bold text-gray-900 mb-2">Žádné notifikace</h3>
            <p class="text-gray-600 font-light">Zatím zde nejsou žádné nové oznámení.</p>
        </div>
    </div>

    <!-- Notification Settings -->
    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
        <div class="bg-gray-50 p-6 border-b border-gray-200">
            <h2 class="text-xl font-bold text-gray-900">Nastavení notifikací</h2>
        </div>
        <div class="p-6">
            <div class="space-y-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-bold text-gray-900">Emailové notifikace</h3>
                        <p class="text-sm text-gray-600 font-light mt-1">Dostávat důležité informace o objednávkách a předplatném</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" checked class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary-600"></div>
                    </label>
                </div>

                <div class="flex items-center justify-between border-t border-gray-200 pt-6">
                    <div>
                        <h3 class="text-base font-bold text-gray-900">Marketingové emaily</h3>
                        <p class="text-sm text-gray-600 font-light mt-1">Dostávat informace o nových produktech a speciálních nabídkách</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" checked class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary-600"></div>
                    </label>
                </div>

                <div class="flex items-center justify-between border-t border-gray-200 pt-6">
                    <div>
                        <h3 class="text-base font-bold text-gray-900">SMS notifikace</h3>
                        <p class="text-sm text-gray-600 font-light mt-1">Dostávat SMS upozornění o důležitých událostech</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary-600"></div>
                    </label>
                </div>
            </div>

            <div class="flex justify-end pt-6 mt-6 border-t border-gray-200">
                <button type="button" class="bg-primary-500 hover:bg-primary-600 text-white font-medium px-6 py-2.5 rounded-full transition-all duration-200">
                    <svg class="w-5 h-5 inline-block mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Uložit nastavení
                </button>
            </div>
        </div>
    </div>
</div>
@endsection



