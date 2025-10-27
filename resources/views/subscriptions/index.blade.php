@extends('layouts.app')

@section('title', 'Kávové předplatné - Kavi Coffee')

@section('content')
<!-- Main Header -->
<div class="bg-white pb-6 sm:pb-8 lg:pb-12">
  <div class="mx-auto max-w-screen-xl px-4 md:px-8">
    <div class="mb-10 md:mb-16 text-center">
      <h2 class="mb-4 text-2xl font-bold text-gray-800 md:mb-6 lg:text-4xl">Sestavte si své kávové předplatné</h2>
      <p class="mx-auto max-w-screen-md text-gray-500 md:text-lg">Vyberte si množství, typ kávy a frekvenci dodání. Jednoduše a bez závazků.</p>
    </div>

    <!-- Error Messages -->
    @if($errors->any())
    <div class="max-w-4xl mx-auto mb-6">
      <div class="bg-red-100 border-2 border-red-400 text-red-700 px-6 py-4 rounded-lg">
        <p class="font-bold mb-2">⚠️ Chyba při zpracování konfigurace:</p>
        <ul class="list-disc list-inside space-y-1">
          @foreach($errors->all() as $error)
          <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    </div>
    @endif

    @if(session('error'))
    <div class="max-w-4xl mx-auto mb-6">
      <div class="bg-red-100 border-2 border-red-400 text-red-700 px-6 py-4 rounded-lg">
        <p class="font-bold">⚠️ {{ session('error') }}</p>
      </div>
    </div>
    @endif

      <!-- Jednoduchý formulář -->
    <form method="POST" action="{{ route('subscriptions.configure.checkout') }}" class="max-w-5xl mx-auto">
        @csrf
        
        <!-- Hidden inputy pro mix rozdělení - budou aktualizované JS -->
        <input type="hidden" name="mix[espresso]" id="mix-espresso-value" value="0">
        <input type="hidden" name="mix[filter]" id="mix-filter-value" value="0">
        
        <!-- Step 1 Header -->
        <div class="flex flex-col items-center justify-between gap-4 rounded-lg bg-gray-100 p-4 sm:flex-row md:p-8 mb-6">
            <div>
                <h2 class="text-xl font-bold text-primary-500 md:text-2xl">Krok 1: Množství kávy</h2>
                <p class="text-gray-600">Vyberte balíček, který vám vyhovuje</p>
            </div>
        </div>

        <div class="mb-12">
            <!-- Množství kávy -->
            <div class="mb-8">
                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    <!-- 500g plán -->
                    <label class="flex flex-col rounded-lg border-2 p-4 pt-6 cursor-pointer transition-all duration-200 has-[:checked]:border-primary-500 has-[:checked]:shadow-lg hover:border-primary-300">
                        <input type="radio" name="amount" value="2" class="hidden peer" required>
                        <div class="mb-8">
                            <div class="mb-2 text-center text-4xl font-bold text-primary-500 pt-6">500g</div>
                            <p class="mx-auto mb-8 px-4 text-center text-gray-500">Ideální pro jednotlivce nebo páry</p>
                            <div class="space-y-2">
                                <div class="flex gap-2"><svg class="h-6 w-6 shrink-0 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg><span class="text-gray-600">2 balíčky po 250g</span></div>
                                <div class="flex gap-2"><svg class="h-6 w-6 shrink-0 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg><span class="text-gray-600">Doprava zdarma</span></div>
                                <div class="flex gap-2"><svg class="h-6 w-6 shrink-0 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg><span class="text-gray-600">Zrušení kdykoliv</span></div>
                            </div>
                        </div>
                        <div class="mt-auto flex flex-col gap-4">
                            <div class="flex items-end justify-center gap-1">
                                <span class="text-4xl font-bold text-gray-800">{{ number_format($subscriptionPricing['2'], 0, ',', ' ') }},-</span>
                                <span class="text-gray-500">Kč/box</span>
                            </div>
                            <span class="block rounded-lg bg-gray-800 px-8 py-3 text-center text-sm font-semibold text-white transition duration-100 hover:bg-gray-700 md:text-base">Vybrat plán</span>
                        </div>
                    </label>
                    
                    <!-- 750g plán -->
                    <label class="flex flex-col rounded-lg border-2 p-4 pt-6 cursor-pointer transition-all duration-200 has-[:checked]:border-primary-500 has-[:checked]:shadow-lg hover:border-primary-300">
                        <input type="radio" name="amount" value="3" class="hidden peer" required>
                        <div class="mb-8">
                            <div class="mb-2 text-center text-4xl font-bold text-primary-500 pt-6">750g</div>
                            <p class="mx-auto mb-8 px-4 text-center text-gray-500">Nejpopulárnější volba</p>
                            <div class="space-y-2">
                                <div class="flex gap-2"><svg class="h-6 w-6 shrink-0 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg><span class="text-gray-600">3 balíčky po 250g</span></div>
                                <div class="flex gap-2"><svg class="h-6 w-6 shrink-0 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg><span class="text-gray-600">Doprava zdarma</span></div>
                                <div class="flex gap-2"><svg class="h-6 w-6 shrink-0 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg><span class="text-gray-600">Prioritní podpora</span></div>
                            </div>
                        </div>
                        <div class="mt-auto flex flex-col gap-4">
                            <div class="flex items-end justify-center gap-1">
                                <span class="text-4xl font-bold text-gray-800">{{ number_format($subscriptionPricing['3'], 0, ',', ' ') }},-</span>
                                <span class="text-gray-500">Kč/box</span>
                            </div>
                            <span class="block rounded-lg bg-gray-800 px-8 py-3 text-center text-sm font-semibold text-white transition duration-100 hover:bg-gray-700 md:text-base">Vybrat plán</span>
                        </div>
                    </label>

                    <!-- 1000g plán -->
                    <label class="flex flex-col rounded-lg border-2 p-4 pt-6 cursor-pointer transition-all duration-200 has-[:checked]:border-primary-500 has-[:checked]:shadow-lg hover:border-primary-300">
                        <input type="radio" name="amount" value="4" class="hidden peer" required>
                        <div class="mb-8">
                            <div class="mb-2 text-center text-4xl font-bold text-primary-500 pt-6">1000g</div>
                            <p class="mx-auto mb-8 px-4 text-center text-gray-500">Pro kávové nadšence</p>
                            <div class="space-y-2">
                                <div class="flex gap-2"><svg class="h-6 w-6 shrink-0 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg><span class="text-gray-600">4 balíčky po 250g</span></div>
                                <div class="flex gap-2"><svg class="h-6 w-6 shrink-0 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg><span class="text-gray-600">Doprava zdarma</span></div>
                                <div class="flex gap-2"><svg class="h-6 w-6 shrink-0 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg><span class="text-gray-600">VIP podpora</span></div>
                            </div>
                        </div>
                        <div class="mt-auto flex flex-col gap-4">
                            <div class="flex items-end justify-center gap-1">
                                <span class="text-4xl font-bold text-gray-800">{{ number_format($subscriptionPricing['4'], 0, ',', ' ') }},-</span>
                                <span class="text-gray-500">Kč/box</span>
                            </div>
                            <span class="block rounded-lg bg-gray-800 px-8 py-3 text-center text-sm font-semibold text-white transition duration-100 hover:bg-gray-700 md:text-base">Vybrat plán</span>
                        </div>
                    </label>
                </div>
            </div>
        </div>

        <!-- Step 2 Header -->
        <div class="flex flex-col items-center justify-between gap-4 rounded-lg bg-gray-100 p-4 sm:flex-row md:p-8 mb-6">
            <div>
                <h2 class="text-xl font-bold text-primary-500 md:text-2xl">Krok 2: Preferovaný typ kávy</h2>
                <p class="text-gray-600">Vyberte si váš oblíbený způsob přípravy</p>
            </div>
        </div>

        <div class="mb-12">
            <!-- Typ kávy -->
            <div class="mb-8">
                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    <!-- Espresso -->
                    <label class="flex flex-col rounded-lg border-2 p-4 pt-6 cursor-pointer transition-all duration-200 has-[:checked]:border-primary-500 has-[:checked]:shadow-lg hover:border-primary-300">
                        <input type="radio" name="type" value="espresso" class="hidden peer" required>
                        <div class="mb-12">
                            <div class="mb-2 text-center text-3xl font-bold text-gray-800">Espresso</div>
                            <p class="mx-auto mb-8 px-4 text-center text-gray-500">Ideální pro přípravu v kávovaru nebo moka konvici</p>
                            <div class="space-y-2">
                                <div class="flex gap-2"><svg class="h-6 w-6 shrink-0 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg><span class="text-gray-600">Plné tělo a intenzita</span></div>
                                <div class="flex gap-2"><svg class="h-6 w-6 shrink-0 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg><span class="text-gray-600">Sladká chuť</span></div>
                                <div class="flex gap-2"><svg class="h-6 w-6 shrink-0 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg><span class="text-gray-600">Tmavší pražení</span></div>
                            </div>
                        </div>
                        <div class="mt-auto flex flex-col gap-4">
                            <!-- Decaf checkbox pro Espresso -->
                            <label class="flex items-center gap-3 px-4 py-3 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors border border-gray-300" onclick="event.stopPropagation();">
                                <input type="checkbox" name="isDecaf" value="1" class="w-5 h-5 text-primary-500 rounded">
                                <span class="text-sm font-semibold text-gray-700">1x káva bez kofeinu</span>
                                <span class="ml-auto text-sm font-semibold text-gray-700">+ 100,-</span>
                            </label>
                            <span class="block rounded-lg bg-gray-800 px-8 py-3 text-center text-sm font-semibold text-white transition duration-100 hover:bg-gray-700 md:text-base">Vybrat metodu</span>
                        </div>
                    </label>

                    <!-- Filtr -->
                    <label class="flex flex-col rounded-lg border-2 p-4 pt-6 cursor-pointer transition-all duration-200 has-[:checked]:border-primary-500 has-[:checked]:shadow-lg hover:border-primary-300">
                        <input type="radio" name="type" value="filter" class="hidden peer" required>
                        <div class="mb-12">
                            <div class="mb-2 text-center text-3xl font-bold text-gray-800">Filtr</div>
                            <p class="mx-auto mb-8 px-4 text-center text-gray-500">Pro překapávanou kávu nebo french press</p>
                            <div class="space-y-2">
                                <div class="flex gap-2"><svg class="h-6 w-6 shrink-0 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg><span class="text-gray-600">Lehčí tělo, vyšší kyselost</span></div>
                                <div class="flex gap-2"><svg class="h-6 w-6 shrink-0 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg><span class="text-gray-600">Ovocné tóny</span></div>
                                <div class="flex gap-2"><svg class="h-6 w-6 shrink-0 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg><span class="text-gray-600">Světlejší pražení</span></div>
                            </div>
                        </div>
                        <div class="mt-auto flex flex-col gap-4">
                            <!-- Decaf checkbox pro Filtr -->
                            <label class="flex items-center gap-3 px-4 py-3 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors border border-gray-300" onclick="event.stopPropagation();">
                                <input type="checkbox" name="isDecaf" value="1" class="w-5 h-5 text-primary-500 rounded">
                                <span class="text-sm font-semibold text-gray-700">1x káva bez kofeinu</span>
                                <span class="ml-auto text-sm font-semibold text-gray-700">+ 100,-</span>
                            </label>
                            <span class="block rounded-lg bg-gray-800 px-8 py-3 text-center text-sm font-semibold text-white transition duration-100 hover:bg-gray-700 md:text-base">Vybrat metodu</span>
                        </div>
                    </label>

                    <!-- Kombinace -->
                    <label class="flex flex-col rounded-lg border-2 p-4 pt-6 cursor-pointer transition-all duration-200 has-[:checked]:border-primary-500 has-[:checked]:shadow-lg hover:border-primary-300">
                        <input type="radio" name="type" value="mix" class="hidden peer" required>
                        <div class="mb-12">
                            <div class="mb-2 text-center text-3xl font-bold text-gray-800">Kombinace</div>
                            <p class="mx-auto mb-8 px-4 text-center text-gray-500">Rozmanitost pro ty, kteří chtějí vyzkoušet obojí</p>
                            <div class="space-y-2">
                                <div class="flex gap-2"><svg class="h-6 w-6 shrink-0 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg><span class="text-gray-600">Espresso i filtr</span></div>
                                <div class="flex gap-2"><svg class="h-6 w-6 shrink-0 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg><span class="text-gray-600">Možnost decaf variant</span></div>
                                <div class="flex gap-2"><svg class="h-6 w-6 shrink-0 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg><span class="text-gray-600">Maximální rozmanitost</span></div>
                            </div>
                        </div>
                        <div class="mt-auto flex flex-col gap-4">
                            <!-- Decaf checkbox pro Mix -->
                            <label class="flex items-center gap-3 px-4 py-3 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors border border-gray-300" onclick="event.stopPropagation();">
                                <input type="checkbox" name="isDecaf" value="1" class="w-5 h-5 text-primary-500 rounded">
                                <span class="text-sm font-semibold text-gray-700">1x káva bez kofeinu</span>
                                <span class="ml-auto text-sm font-semibold text-gray-700">+ 100,-</span>
                            </label>
                            <span class="block rounded-lg bg-gray-800 px-8 py-3 text-center text-sm font-semibold text-white transition duration-100 hover:bg-gray-700 md:text-base">Vybrat metodu</span>
                        </div>
                    </label>
                </div>
            </div>
        </div>
                          
        <!-- Rozdělení kávy -->
        <div id="caffeine-distribution" class="hidden mb-8">
          <div class="bg-gray-50 p-6 rounded-lg border">
            <h4 class="text-3xl text-center font-bold text-gray-800 mb-4">Rozdělení kávy</h4>
            
            <!-- Layout pro Espresso/Filtr s decaf: 2 sloupce vedle sebe -->
            <div id="simple-distribution" class="hidden grid grid-cols-2 gap-4">
              <!-- Kofeinová verze -->
              <div class="flex flex-col p-4 bg-white rounded-lg border">
                <div class="mb-3">
                  <span class="font-semibold text-gray-800 block mb-1" id="caffeine-label">Espresso</span>
                  <p class="text-xs text-gray-500">250g balení</p>
                </div>
                
                <div class="flex items-center justify-center gap-3 mt-auto">
                  <button type="button" id="caffeine-minus" class="flex h-10 w-10 items-center justify-center rounded-md border-2 border-gray-800 bg-white text-gray-800 transition duration-100 hover:bg-gray-800 hover:text-white active:bg-gray-900 disabled:opacity-30 disabled:cursor-not-allowed disabled:hover:bg-white disabled:hover:text-gray-800 disabled:border-gray-300">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                    </svg>
                  </button>
                  <span class="w-12 text-center text-2xl font-bold text-gray-800" id="caffeine-count">0</span>
                  <button type="button" id="caffeine-plus" class="flex h-10 w-10 items-center justify-center rounded-md border-2 border-gray-800 bg-white text-gray-800 transition duration-100 hover:bg-gray-800 hover:text-white active:bg-gray-900 disabled:opacity-30 disabled:cursor-not-allowed disabled:hover:bg-white disabled:hover:text-gray-800 disabled:border-gray-300">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                  </button>
                </div>
              </div>
              
              <!-- Bezkofeinová verze -->
              <div class="flex flex-col p-4 bg-white rounded-lg border">
                <div class="mb-3">
                  <span class="font-semibold text-gray-800 block mb-1" id="decaf-label">Decaf espresso</span>
                  <p class="text-xs text-gray-500">250g balení</p>
                </div>
                
                <div class="flex items-center justify-center gap-3 mt-auto">
                  <button type="button" id="decaf-minus" class="flex h-10 w-10 items-center justify-center rounded-md border-2 border-gray-800 bg-white text-gray-800 transition duration-100 hover:bg-gray-800 hover:text-white active:bg-gray-900 disabled:opacity-30 disabled:cursor-not-allowed disabled:hover:bg-white disabled:hover:text-gray-800 disabled:border-gray-300">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                    </svg>
                  </button>
                  <span class="w-12 text-center text-2xl font-bold text-gray-800" id="decaf-count">0</span>
                  <button type="button" id="decaf-plus" class="flex h-10 w-10 items-center justify-center rounded-md border-2 border-gray-800 bg-white text-gray-800 transition duration-100 hover:bg-gray-800 hover:text-white active:bg-gray-900 disabled:opacity-30 disabled:cursor-not-allowed disabled:hover:bg-white disabled:hover:text-gray-800 disabled:border-gray-300">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                  </button>
                </div>
              </div>
            </div>

            <!-- Layout pro Kombinaci BEZ decaf: 2 sloupce (Espresso, Filtr) -->
            <div id="mix-no-decaf-distribution" class="hidden grid grid-cols-2 gap-4">
              <!-- Espresso -->
              <div class="flex flex-col p-4 bg-white rounded-lg border">
                <div class="mb-3">
                  <span class="font-semibold text-gray-800 block mb-1">Espresso</span>
                  <p class="text-xs text-gray-500">250g balení</p>
                </div>
                
                <div class="flex items-center justify-center gap-3 mt-auto">
                  <button type="button" id="mix-espresso-minus" class="flex h-10 w-10 items-center justify-center rounded-md border-2 border-gray-800 bg-white text-gray-800 transition duration-100 hover:bg-gray-800 hover:text-white active:bg-gray-900 disabled:opacity-30 disabled:cursor-not-allowed disabled:hover:bg-white disabled:hover:text-gray-800 disabled:border-gray-300">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                    </svg>
                  </button>
                  <span class="w-12 text-center text-2xl font-bold text-gray-800" id="mix-espresso-count">0</span>
                  <button type="button" id="mix-espresso-plus" class="flex h-10 w-10 items-center justify-center rounded-md border-2 border-gray-800 bg-white text-gray-800 transition duration-100 hover:bg-gray-800 hover:text-white active:bg-gray-900 disabled:opacity-30 disabled:cursor-not-allowed disabled:hover:bg-white disabled:hover:text-gray-800 disabled:border-gray-300">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                  </button>
                </div>
              </div>
              
              <!-- Filtr -->
              <div class="flex flex-col p-4 bg-white rounded-lg border">
                <div class="mb-3">
                  <span class="font-semibold text-gray-800 block mb-1">Filtr</span>
                  <p class="text-xs text-gray-500">250g balení</p>
                </div>
                
                <div class="flex items-center justify-center gap-3 mt-auto">
                  <button type="button" id="mix-filter-minus" class="flex h-10 w-10 items-center justify-center rounded-md border-2 border-gray-800 bg-white text-gray-800 transition duration-100 hover:bg-gray-800 hover:text-white active:bg-gray-900 disabled:opacity-30 disabled:cursor-not-allowed disabled:hover:bg-white disabled:hover:text-gray-800 disabled:border-gray-300">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                    </svg>
                  </button>
                  <span class="w-12 text-center text-2xl font-bold text-gray-800" id="mix-filter-count">0</span>
                  <button type="button" id="mix-filter-plus" class="flex h-10 w-10 items-center justify-center rounded-md border-2 border-gray-800 bg-white text-gray-800 transition duration-100 hover:bg-gray-800 hover:text-white active:bg-gray-900 disabled:opacity-30 disabled:cursor-not-allowed disabled:hover:bg-white disabled:hover:text-gray-800 disabled:border-gray-300">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                  </button>
                </div>
              </div>
            </div>

            <!-- Layout pro Kombinaci S decaf: 2 řádky po 2 sloupcích -->
            <div id="mix-with-decaf-distribution" class="hidden space-y-4">
              <!-- První řádek: Espresso + Decaf espresso -->
              <div class="grid grid-cols-2 gap-4">
                <!-- Espresso -->
                <div class="flex flex-col p-4 bg-white rounded-lg border">
                  <div class="mb-3">
                    <span class="font-semibold text-gray-800 block mb-1">Espresso</span>
                    <p class="text-xs text-gray-500">250g balení</p>
                  </div>
                  
                  <div class="flex items-center justify-center gap-3 mt-auto">
                    <button type="button" id="mix-espresso-caf-minus" class="flex h-10 w-10 items-center justify-center rounded-md border-2 border-gray-800 bg-white text-gray-800 transition duration-100 hover:bg-gray-800 hover:text-white active:bg-gray-900 disabled:opacity-30 disabled:cursor-not-allowed disabled:hover:bg-white disabled:hover:text-gray-800 disabled:border-gray-300">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                      </svg>
                    </button>
                    <span class="w-12 text-center text-2xl font-bold text-gray-800" id="mix-espresso-caf-count">0</span>
                    <button type="button" id="mix-espresso-caf-plus" class="flex h-10 w-10 items-center justify-center rounded-md border-2 border-gray-800 bg-white text-gray-800 transition duration-100 hover:bg-gray-800 hover:text-white active:bg-gray-900 disabled:opacity-30 disabled:cursor-not-allowed disabled:hover:bg-white disabled:hover:text-gray-800 disabled:border-gray-300">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                      </svg>
                    </button>
                  </div>
                </div>
                
                <!-- Decaf espresso -->
                <div class="flex flex-col p-4 bg-white rounded-lg border">
                  <div class="mb-3">
                    <span class="font-semibold text-gray-800 block mb-1">Decaf espresso</span>
                    <p class="text-xs text-gray-500">250g balení</p>
                  </div>
                  
                  <div class="flex items-center justify-center gap-3 mt-auto">
                    <button type="button" id="mix-espresso-decaf-minus" class="flex h-10 w-10 items-center justify-center rounded-md border-2 border-gray-800 bg-white text-gray-800 transition duration-100 hover:bg-gray-800 hover:text-white active:bg-gray-900 disabled:opacity-30 disabled:cursor-not-allowed disabled:hover:bg-white disabled:hover:text-gray-800 disabled:border-gray-300">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                      </svg>
                    </button>
                    <span class="w-12 text-center text-2xl font-bold text-gray-800" id="mix-espresso-decaf-count">0</span>
                    <button type="button" id="mix-espresso-decaf-plus" class="flex h-10 w-10 items-center justify-center rounded-md border-2 border-gray-800 bg-white text-gray-800 transition duration-100 hover:bg-gray-800 hover:text-white active:bg-gray-900 disabled:opacity-30 disabled:cursor-not-allowed disabled:hover:bg-white disabled:hover:text-gray-800 disabled:border-gray-300">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                      </svg>
                    </button>
                  </div>
                </div>
              </div>

              <!-- Druhý řádek: Filtr + Decaf filtr -->
              <div class="grid grid-cols-2 gap-4">
                <!-- Filtr -->
                <div class="flex flex-col p-4 bg-white rounded-lg border">
                  <div class="mb-3">
                    <span class="font-semibold text-gray-800 block mb-1">Filtr</span>
                    <p class="text-xs text-gray-500">250g balení</p>
                  </div>
                  
                  <div class="flex items-center justify-center gap-3 mt-auto">
                    <button type="button" id="mix-filter-caf-minus" class="flex h-10 w-10 items-center justify-center rounded-md border-2 border-gray-800 bg-white text-gray-800 transition duration-100 hover:bg-gray-800 hover:text-white active:bg-gray-900 disabled:opacity-30 disabled:cursor-not-allowed disabled:hover:bg-white disabled:hover:text-gray-800 disabled:border-gray-300">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                      </svg>
                    </button>
                    <span class="w-12 text-center text-2xl font-bold text-gray-800" id="mix-filter-caf-count">0</span>
                    <button type="button" id="mix-filter-caf-plus" class="flex h-10 w-10 items-center justify-center rounded-md border-2 border-gray-800 bg-white text-gray-800 transition duration-100 hover:bg-gray-800 hover:text-white active:bg-gray-900 disabled:opacity-30 disabled:cursor-not-allowed disabled:hover:bg-white disabled:hover:text-gray-800 disabled:border-gray-300">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                      </svg>
                    </button>
                  </div>
                </div>
                
                <!-- Decaf filtr -->
                <div class="flex flex-col p-4 bg-white rounded-lg border">
                  <div class="mb-3">
                    <span class="font-semibold text-gray-800 block mb-1">Decaf filtr</span>
                    <p class="text-xs text-gray-500">250g balení</p>
                  </div>
                  
                  <div class="flex items-center justify-center gap-3 mt-auto">
                    <button type="button" id="mix-filter-decaf-minus" class="flex h-10 w-10 items-center justify-center rounded-md border-2 border-gray-800 bg-white text-gray-800 transition duration-100 hover:bg-gray-800 hover:text-white active:bg-gray-900 disabled:opacity-30 disabled:cursor-not-allowed disabled:hover:bg-white disabled:hover:text-gray-800 disabled:border-gray-300">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                      </svg>
                    </button>
                    <span class="w-12 text-center text-2xl font-bold text-gray-800" id="mix-filter-decaf-count">0</span>
                    <button type="button" id="mix-filter-decaf-plus" class="flex h-10 w-10 items-center justify-center rounded-md border-2 border-gray-800 bg-white text-gray-800 transition duration-100 hover:bg-gray-800 hover:text-white active:bg-gray-900 disabled:opacity-30 disabled:cursor-not-allowed disabled:hover:bg-white disabled:hover:text-gray-800 disabled:border-gray-300">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                      </svg>
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Step 3 Header -->
        <div class="flex flex-col items-center justify-between gap-4 rounded-lg bg-gray-100 p-4 sm:flex-row md:p-8 mb-6">
            <div>
                <h2 class="text-xl font-bold text-primary-500 md:text-2xl">Krok 3: Frekvence dodání</h2>
                <p class="text-gray-600">Jak často chcete kávu dostávat?</p>
            </div>
        </div>

        <div class="mb-12">
            <!-- Frekvence -->
            <div class="mb-8">
                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    <!-- Každý měsíc -->
                    <label class="flex flex-col rounded-lg border-2 p-4 pt-6 cursor-pointer transition-all duration-200 has-[:checked]:border-primary-500 has-[:checked]:shadow-lg hover:border-primary-300">
                        <input type="radio" name="frequency" value="1" class="hidden peer" required>
                        <div class="mb-8">
                            <div class="mb-2 text-center text-3xl font-bold text-gray-800 pt-6">Každý měsíc</div>
                            <p class="mx-auto mb-8 px-4 text-center text-gray-500">Pro pravidelnou spotřebu a čerstvou kávu</p>
                            <div class="space-y-2">
                                <div class="flex gap-2"><svg class="h-6 w-6 shrink-0 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg><span class="text-gray-600">Vždy čerstvá káva</span></div>
                                <div class="flex gap-2"><svg class="h-6 w-6 shrink-0 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg><span class="text-gray-600">Pro pravidelné pití</span></div>
                                <div class="flex gap-2"><svg class="h-6 w-6 shrink-0 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg><span class="text-gray-600">Nejpopulárnější volba</span></div>
                            </div>
                        </div>
                        <div class="mt-auto">
                            <span class="block rounded-lg bg-gray-800 px-8 py-3 text-center text-sm font-semibold text-white transition duration-100 hover:bg-gray-700 md:text-base">Vybrat frekvenci</span>
                        </div>
                    </label>

                    <!-- Jednou za 2 měsíce -->
                    <label class="flex flex-col rounded-lg border-2 p-4 pt-6 cursor-pointer transition-all duration-200 has-[:checked]:border-primary-500 has-[:checked]:shadow-lg hover:border-primary-300">
                        <input type="radio" name="frequency" value="2" class="hidden peer" required>
                        <div class="mb-8">
                            <div class="mb-2 text-center text-3xl font-bold text-gray-800 pt-6">Jednou za 2 měsíce</div>
                            <p class="mx-auto mb-8 px-4 text-center text-gray-500">Pro střední spotřebu</p>
                            <div class="space-y-2">
                                <div class="flex gap-2"><svg class="h-6 w-6 shrink-0 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg><span class="text-gray-600">Úspora peněz</span></div>
                                <div class="flex gap-2"><svg class="h-6 w-6 shrink-0 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg><span class="text-gray-600">Flexibilní řešení</span></div>
                                <div class="flex gap-2"><svg class="h-6 w-6 shrink-0 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg><span class="text-gray-600">Pro menší domácnosti</span></div>
                            </div>
                        </div>
                        <div class="mt-auto">
                            <span class="block rounded-lg bg-gray-800 px-8 py-3 text-center text-sm font-semibold text-white transition duration-100 hover:bg-gray-700 md:text-base">Vybrat frekvenci</span>
                        </div>
                    </label>

                    <!-- Jednou za 3 měsíce -->
                    <label class="flex flex-col rounded-lg border-2 p-4 pt-6 cursor-pointer transition-all duration-200 has-[:checked]:border-primary-500 has-[:checked]:shadow-lg hover:border-primary-300">
                        <input type="radio" name="frequency" value="3" class="hidden peer" required>
                        <div class="mb-8">
                            <div class="mb-2 text-center text-3xl font-bold text-gray-800 pt-6">Jednou za 3 měsíce</div>
                            <p class="mx-auto mb-8 px-4 text-center text-gray-500">Pro občasné pití</p>
                            <div class="space-y-2">
                                <div class="flex gap-2"><svg class="h-6 w-6 shrink-0 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg><span class="text-gray-600">Maximální úspora</span></div>
                                <div class="flex gap-2"><svg class="h-6 w-6 shrink-0 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg><span class="text-gray-600">Pro příležitostné pití</span></div>
                                <div class="flex gap-2"><svg class="h-6 w-6 shrink-0 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg><span class="text-gray-600">Ideální na zkoušku</span></div>
                            </div>
                        </div>
                        <div class="mt-auto">
                            <span class="block rounded-lg bg-gray-800 px-8 py-3 text-center text-sm font-semibold text-white transition duration-100 hover:bg-gray-700 md:text-base">Vybrat frekvenci</span>
                        </div>
                    </label>
                </div>
            </div>
        </div>

            <!-- Souhrn předplatného -->
            <div class="mt-12">
                <div class="flex flex-col overflow-hidden rounded-lg bg-gray-100 sm:flex-row md:min-h-80 border-2 border-gray-200 shadow-lg">
                    <!-- image -->
                    <div class="order-first h-64 w-full bg-gray-300 sm:order-none sm:h-auto sm:w-1/2 lg:w-2/5">
                        <img src="https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?auto=format&q=75&fit=crop&w=1000" loading="lazy" alt="Kávový box" class="h-full w-full object-cover object-center" />
                    </div>
                    <!-- content -->
                    <div class="flex w-full flex-col p-6 sm:w-1/2 sm:p-8 lg:w-3/5 lg:p-10">
                        <h2 class="mb-4 text-2xl font-bold text-gray-800 md:text-3xl">Shrnutí vašeho předplatného</h2>

                        <div class="mb-6 space-y-3">
                            <div class="flex justify-between items-center py-2 border-b border-gray-300">
                                <span class="text-gray-600 font-semibold">Množství:</span>
                                <span class="font-bold text-gray-800" id="summary-amount">-</span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-gray-300">
                                <span class="text-gray-600 font-semibold">Typ kávy:</span>
                                <span class="font-bold text-gray-800" id="summary-type">-</span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-gray-300">
                                <span class="text-gray-600 font-semibold">Frekvence:</span>
                                <span class="font-bold text-gray-800" id="summary-frequency">-</span>
                            </div>
                            <div class="flex justify-between items-center py-3 mt-4">
                                <span class="text-xl font-bold text-gray-800">Cena:</span>
                                <span class="text-3xl font-bold text-primary-500" id="summary-price">-</span>
                            </div>
                            <p class="text-xs text-gray-500 text-right">při každé dodávce</p>
                        </div>

                        <div class="mt-auto">
                            <button type="submit" id="submit-button" class="w-full inline-block rounded-lg bg-primary-500 px-8 py-3 text-center text-sm font-semibold text-white outline-none ring-primary-300 transition duration-100 hover:bg-primary-600 focus-visible:ring active:bg-primary-700 md:text-base opacity-50 cursor-not-allowed" disabled>
                                Pokračovat k objednávce →
                            </button>
                            <p class="text-xs text-gray-500 text-center mt-3">✓ Bez závazků  ✓ Kdykoli zrušte  ✓ Doprava zdarma</p>
                        </div>
                    </div>
                </div>
            </div>
    </div>
    </form>

<!-- Shipping Date Info -->
    <div class="max-w-3xl mx-auto mb-8">
        <div class="bg-primary-50 border-2 border-primary-200 p-6 rounded-lg">
                <div class="flex items-start gap-4">
                    <div class="flex-shrink-0">
                        <svg class="w-8 h-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                    <h3 class="font-bold text-lg text-gray-800 mb-2">Termín následující rozesílky</h3>
                    <p class="text-gray-700 mb-3"><strong>{{ $shippingInfo['cutoff_message'] }}</strong></p>
                    <p class="text-sm text-gray-600 leading-relaxed">
                            <strong>Jak to funguje:</strong> Rozesílka kávy probíhá vždy <strong>20. dne v měsíci</strong>. 
                            Objednávky uzavíráme <strong>15. dne v měsíci o půlnoci</strong>. 
                            Pokud si objednáte od 16. dne, vaše první zásilka dorazí až při následující rozesílce.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const pricing = @json($subscriptionPricing);
    
    let selectedAmount = null;
    let selectedType = null;
    let selectedFrequency = null;
    let isDecaf = false;
    
    // Pro Mix rozdělení
    let mixEspressoCount = 0;
    let mixFilterCount = 0;
    
    // Karty množství
    document.querySelectorAll('input[name="amount"]').forEach(radio => {
        radio.addEventListener('change', function() {
            selectedAmount = parseInt(this.value);
            
            // Vizuální feedback
            document.querySelectorAll('input[name="amount"]').forEach(r => {
                const label = r.closest('label');
                const button = label.querySelector('span.block');
                if (r.checked) {
                    label.classList.add('border-primary-500', 'shadow-lg');
                    label.classList.remove('border-gray-200');
                    button.classList.remove('bg-gray-800');
                    button.classList.add('bg-primary-500');
                    button.textContent = 'Vybráno';
                } else {
                    label.classList.remove('border-primary-500', 'shadow-lg');
                    label.classList.add('border-gray-200');
                    button.classList.remove('bg-primary-500');
                    button.classList.add('bg-gray-800');
                    button.textContent = 'Vybrat plán';
                }
            });
            
            // Pokud je vybraný mix, přepočítej počty a zobraz sekci
            if (selectedType === 'mix') {
                mixEspressoCount = Math.floor(selectedAmount / 2);
                mixFilterCount = selectedAmount - mixEspressoCount;
                updateMixNoDecafDisplay();
                showDistributionLayout();
            }
            
            updateSummary();
        });
    });
    
    // Typ kávy
    document.querySelectorAll('input[name="type"]').forEach(radio => {
        radio.addEventListener('change', function() {
            selectedType = this.value;
            
            // Vizuální feedback
            document.querySelectorAll('input[name="type"]').forEach(r => {
                const label = r.closest('label');
                const button = label.querySelector('span.block');
                if (r.checked) {
                    label.classList.add('border-primary-500', 'shadow-lg');
                    label.classList.remove('border-gray-200');
                    button.classList.remove('bg-gray-800');
                    button.classList.add('bg-primary-500');
                    button.textContent = 'Vybráno';
                } else {
                    label.classList.remove('border-primary-500', 'shadow-lg');
                    label.classList.add('border-gray-200');
                    button.classList.remove('bg-primary-500');
                    button.classList.add('bg-gray-800');
                    button.textContent = 'Vybrat metodu';
                }
            });
            
            // Zobrazit/skrýt sekci Rozdělení kávy
            showDistributionLayout();
            
            updateSummary();
        });
    });
    
    // Decaf checkbox
    const decafCheckbox = document.querySelector('input[name="isDecaf"]');
    if (decafCheckbox) {
        decafCheckbox.addEventListener('change', function() {
            isDecaf = this.checked;
            updateSummary();
        });
    }
    
    // Frekvence
    document.querySelectorAll('input[name="frequency"]').forEach(radio => {
        radio.addEventListener('change', function() {
            selectedFrequency = parseInt(this.value);
            
            // Vizuální feedback
            document.querySelectorAll('input[name="frequency"]').forEach(r => {
                const label = r.closest('label');
                const button = label.querySelector('span.block');
                if (r.checked) {
                    label.classList.add('border-primary-500', 'shadow-lg');
                    label.classList.remove('border-gray-200');
                    button.classList.remove('bg-gray-800');
                    button.classList.add('bg-primary-500');
                    button.textContent = 'Vybráno';
                } else {
                    label.classList.remove('border-primary-500', 'shadow-lg');
                    label.classList.add('border-gray-200');
                    button.classList.remove('bg-primary-500');
                    button.classList.add('bg-gray-800');
                    button.textContent = 'Vybrat frekvenci';
                }
            });
            
            updateSummary();
        });
    });
    
    // Funkce pro zobrazení/skrytí sekce Rozdělení kávy
    function showDistributionLayout() {
        const caffeineDistribution = document.getElementById('caffeine-distribution');
        const mixNoDecafDistribution = document.getElementById('mix-no-decaf-distribution');
        
        if (selectedType === 'mix') {
            // Zobraz sekci pro mix
            caffeineDistribution.classList.remove('hidden');
            mixNoDecafDistribution.classList.remove('hidden');
            
            // Inicializuj počty pokud je vybrané množství a počty ještě nejsou nastavené
            if (selectedAmount && (mixEspressoCount === 0 && mixFilterCount === 0)) {
                mixEspressoCount = Math.floor(selectedAmount / 2);
                mixFilterCount = selectedAmount - mixEspressoCount;
                updateMixNoDecafDisplay();
            }
        } else {
            // Skryj sekci
            caffeineDistribution.classList.add('hidden');
            mixNoDecafDistribution.classList.add('hidden');
        }
    }
    
    // Funkce pro update Mix NO decaf distribution
    function updateMixNoDecafDisplay() {
        document.getElementById('mix-espresso-count').textContent = mixEspressoCount;
        document.getElementById('mix-filter-count').textContent = mixFilterCount;
        
        // Aktualizuj hidden inputy
        document.getElementById('mix-espresso-value').value = mixEspressoCount;
        document.getElementById('mix-filter-value').value = mixFilterCount;
        
        const mixEspressoPlus = document.getElementById('mix-espresso-plus');
        const mixEspressoMinus = document.getElementById('mix-espresso-minus');
        const mixFilterPlus = document.getElementById('mix-filter-plus');
        const mixFilterMinus = document.getElementById('mix-filter-minus');
        
        if (mixEspressoPlus) mixEspressoPlus.disabled = mixFilterCount <= 0;
        if (mixEspressoMinus) mixEspressoMinus.disabled = mixEspressoCount <= 0;
        if (mixFilterPlus) mixFilterPlus.disabled = mixEspressoCount <= 0;
        if (mixFilterMinus) mixFilterMinus.disabled = mixFilterCount <= 0;
    }
    
    // Event listenery pro Mix NO decaf distribution tlačítka
    const mixEspressoPlus = document.getElementById('mix-espresso-plus');
    const mixEspressoMinus = document.getElementById('mix-espresso-minus');
    const mixFilterPlus = document.getElementById('mix-filter-plus');
    const mixFilterMinus = document.getElementById('mix-filter-minus');
    
    if (mixEspressoPlus) {
        mixEspressoPlus.addEventListener('click', function() {
            if (mixFilterCount > 0) {
                mixEspressoCount++;
                mixFilterCount--;
                updateMixNoDecafDisplay();
                updateSummary();
            }
        });
    }
    
    if (mixEspressoMinus) {
        mixEspressoMinus.addEventListener('click', function() {
            if (mixEspressoCount > 0) {
                mixEspressoCount--;
                mixFilterCount++;
                updateMixNoDecafDisplay();
                updateSummary();
            }
        });
    }
    
    if (mixFilterPlus) {
        mixFilterPlus.addEventListener('click', function() {
            if (mixEspressoCount > 0) {
                mixFilterCount++;
                mixEspressoCount--;
                updateMixNoDecafDisplay();
                updateSummary();
            }
        });
    }
    
    if (mixFilterMinus) {
        mixFilterMinus.addEventListener('click', function() {
            if (mixFilterCount > 0) {
                mixFilterCount--;
                mixEspressoCount++;
                updateMixNoDecafDisplay();
                updateSummary();
            }
        });
    }
    
    function updateSummary() {
        // Množství
        if (selectedAmount) {
            const grams = selectedAmount * 250;
            document.getElementById('summary-amount').textContent = grams + 'g (' + selectedAmount + ' balení)';
        } else {
            document.getElementById('summary-amount').textContent = '-';
        }
        
        // Typ
        let typeText = '-';
        if (selectedType) {
            const types = {
                'espresso': 'Espresso',
                'filter': 'Filtr',
                'mix': 'Kombinace'
            };
            typeText = types[selectedType];
            
            // Pro mix zobrazit rozdělení
            if (selectedType === 'mix' && (mixEspressoCount > 0 || mixFilterCount > 0)) {
                typeText += ` (${mixEspressoCount}x Espresso, ${mixFilterCount}x Filtr)`;
            }
            
            if (isDecaf) {
                typeText += ' + 1x decaf';
            }
        }
        document.getElementById('summary-type').textContent = typeText;
        
        // Frekvence
        const frequencies = {
            1: 'Každý měsíc',
            2: 'Jednou za 2 měsíce',
            3: 'Jednou za 3 měsíce'
        };
        document.getElementById('summary-frequency').textContent = frequencies[selectedFrequency] || '-';
        
        // Cena
        if (selectedAmount) {
            let price = pricing[selectedAmount] || 0;
            if (isDecaf) {
                price += 100;
            }
            document.getElementById('summary-price').textContent = price.toLocaleString('cs-CZ') + ' Kč';
        } else {
            document.getElementById('summary-price').textContent = '-';
        }
        
        // Povolit tlačítko submit pokud jsou všechny hodnoty vybrané
        const submitButton = document.getElementById('submit-button');
        if (selectedAmount && selectedType && selectedFrequency) {
            submitButton.disabled = false;
            submitButton.classList.remove('opacity-50', 'cursor-not-allowed');
        } else {
            submitButton.disabled = true;
            submitButton.classList.add('opacity-50', 'cursor-not-allowed');
        }
    }
});
</script>
@endsection
