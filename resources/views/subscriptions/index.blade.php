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

    <!-- Configurator -->
    <div class="max-w-5xl mx-auto" id="subscription-configurator" data-pricing='@json($subscriptionPricing)'>
      
      <!-- Step 1 Header -->
      <div class="flex flex-col items-center justify-between gap-4 rounded-lg bg-gray-100 p-4 sm:flex-row md:p-8 mb-6">
        <div>
          <h2 class="text-xl font-bold text-primary-500 md:text-2xl">Krok 1: Množství kávy</h2>
          <p class="text-gray-600">Vyberte balíček, který vám vyhovuje</p>
        </div>
      </div>

      <!-- Step 1: Množství kávy -->
      <div id="step-1" class="mb-12">
        <div class="mb-6 grid gap-6 sm:grid-cols-2 md:mb-8 lg:grid-cols-3 lg:gap-8">
          <!-- plan - start -->
          <div class="flex flex-col rounded-lg border p-4 pt-6 coffee-amount-option" data-amount="2" data-cups="500g" data-price="{{ $subscriptionPricing['2'] }}">
            <div class="mb-12">
              <div class="mb-2 text-center text-4xl font-bold text-primary-500 pt-6">500g</div>

              <p class="mx-auto mb-8 px-8 text-center text-gray-500">Ideální pro jednotlivce nebo páry, které si chtějí vychutnat kvalitní kávu</p>

              <div class="space-y-2">
                <!-- check - start -->
                <div class="flex gap-2">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                  </svg>

                  <span class="text-gray-600">2 balíčky po 250g</span>
                </div>
                <!-- check - end -->

                <!-- check - start -->
                <div class="flex gap-2">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                  </svg>

                  <span class="text-gray-600">2 druhy prémiové kávy</span>
                </div>
                <!-- check - end -->

                <!-- check - start -->
                <div class="flex gap-2">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                  </svg>

                  <span class="text-gray-600">Doprava zdarma</span>
                </div>
                <!-- check - end -->

                <!-- check - start -->
                <div class="flex gap-2">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                  </svg>

                  <span class="text-gray-600">Zrušení kdykoliv</span>
                </div>
                <!-- check - end -->
              </div>
            </div>

            <div class="mt-auto flex flex-col gap-8">
              <div class="flex items-end justify-center gap-1">
                <span class="text-4xl font-bold text-gray-800">{{ number_format($subscriptionPricing['2'], 0, ',', ' ') }},-</span>
                <span class="text-gray-500">Kč/box</span>
              </div>

              <button type="button" class="plan-button block rounded-lg bg-gray-800 px-8 py-3 text-center text-sm font-semibold text-white outline-none ring-primary-300 transition duration-100 hover:bg-gray-700 focus-visible:ring active:bg-gray-600 md:text-base">Vybrat plán</button>
            </div>
          </div>
          <!-- plan - end -->

          <!-- plan - start -->
          <div class="flex flex-col rounded-lg border p-4 pt-6 coffee-amount-option" data-amount="3" data-cups="750g" data-price="{{ $subscriptionPricing['3'] }}">
            <div class="mb-12">
              <div class="mb-2 text-center text-4xl font-bold text-primary-500 pt-6">750g</div>

              <p class="mx-auto mb-8 px-8 text-center text-gray-500">Nejpopulárnější volba pro pravidelné milovníky kávy</p>

              <div class="space-y-2">
                <!-- check - start -->
                <div class="flex gap-2">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                  </svg>

                  <span class="text-gray-600">3 balíčky po 250g</span>
                </div>
                <!-- check - end -->

                <!-- check - start -->
                <div class="flex gap-2">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                  </svg>

                  <span class="text-gray-600">3 druhy prémiové kávy</span>
                </div>
                <!-- check - end -->

                <!-- check - start -->
                <div class="flex gap-2">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                  </svg>

                  <span class="text-gray-600">Doprava zdarma</span>
                </div>
                <!-- check - end -->

                <!-- check - start -->
                <div class="flex gap-2">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                  </svg>

                  <span class="text-gray-600">Zrušení kdykoliv</span>
                </div>
                <!-- check - end -->

                <!-- check - start -->
                <div class="flex gap-2">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                  </svg>

                  <span class="text-gray-600">Prioritní podpora</span>
                </div>
                <!-- check - end -->
              </div>
            </div>

            <div class="mt-auto flex flex-col gap-8">
              <div class="flex items-end justify-center gap-1">
                <span class="text-4xl font-bold text-gray-800">{{ number_format($subscriptionPricing['3'], 0, ',', ' ') }},-</span>
                <span class="text-gray-500">Kč/box</span>
              </div>

              <button type="button" class="plan-button block rounded-lg bg-gray-800 px-8 py-3 text-center text-sm font-semibold text-white outline-none ring-primary-300 transition duration-100 hover:bg-gray-700 focus-visible:ring active:bg-gray-600 md:text-base">Vybrat plán</button>
            </div>
          </div>
          <!-- plan - end -->

          <!-- plan - start -->
          <div class="flex flex-col rounded-lg border p-4 pt-6 coffee-amount-option" data-amount="4" data-cups="1000g" data-price="{{ $subscriptionPricing['4'] }}">
            <div class="mb-12">
              <div class="mb-2 text-center text-4xl font-bold text-primary-500 pt-6">1000g</div>

              <p class="mx-auto mb-8 px-8 text-center text-gray-500">Pro kávové nadšence a větší domácnosti</p>

              <div class="space-y-2">
                <!-- check - start -->
                <div class="flex gap-2">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                  </svg>

                  <span class="text-gray-600">4 balíčky po 250g</span>
                </div>
                <!-- check - end -->

                <!-- check - start -->
                <div class="flex gap-2">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                  </svg>

                  <span class="text-gray-600">4 druhy prémiové kávy</span>
                </div>
                <!-- check - end -->

                <!-- check - start -->
                <div class="flex gap-2">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                  </svg>

                  <span class="text-gray-600">Doprava zdarma</span>
                </div>
                <!-- check - end -->

                <!-- check - start -->
                <div class="flex gap-2">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                  </svg>

                  <span class="text-gray-600">Zrušení kdykoliv</span>
                </div>
                <!-- check - end -->

                <!-- check - start -->
                <div class="flex gap-2">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                  </svg>

                  <span class="text-gray-600">VIP podpora</span>
                </div>
                <!-- check - end -->
              </div>
            </div>

            <div class="mt-auto flex flex-col gap-8">
              <div class="flex items-end justify-center gap-1">
                <span class="text-4xl font-bold text-gray-800">{{ number_format($subscriptionPricing['4'], 0, ',', ' ') }},-</span>
                <span class="text-gray-500">Kč/box</span>
              </div>

              <button type="button" class="plan-button block rounded-lg bg-gray-800 px-8 py-3 text-center text-sm font-semibold text-white outline-none ring-primary-300 transition duration-100 hover:bg-gray-700 focus-visible:ring active:bg-gray-600 md:text-base">Vybrat plán</button>
            </div>
          </div>
          <!-- plan - end -->
        </div>
      </div>

                <!-- Step 2 Header -->
                <div class="flex flex-col items-center justify-between gap-4 rounded-lg bg-gray-100 p-4 sm:flex-row md:p-8 mb-6">
                  <div>
                    <h2 class="text-xl font-bold text-primary-500 md:text-2xl">Krok 2: Preferovaný typ kávy</h2>
                    <p class="text-gray-600">Vyberte si váš oblíbený způsob přípravy</p>
                  </div>
                </div>

                <!-- Step 2: Typ kávy -->
                <div id="step-2" class="mb-12">
                  <div class="mb-6 grid gap-6 sm:grid-cols-2 md:mb-8 lg:grid-cols-3 lg:gap-8">
                    
                    <!-- Espresso - start -->
                    <div class="flex flex-col rounded-lg border p-4 pt-6 coffee-type-card" data-type="espresso">
                      <div class="mb-12">
                        <div class="mb-2 text-center text-3xl font-bold text-gray-800">Espresso</div>

                        <p class="mx-auto mb-8 px-8 text-center text-gray-500">Ideální pro přípravu v kávovaru nebo moka konvici</p>

                        <div class="space-y-2">
                          <div class="flex gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span class="text-gray-600">Plné tělo a intenzita</span>
                          </div>

                          <div class="flex gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span class="text-gray-600">Sladká chuť s nízkým kyselým tónem</span>
                          </div>

                          <div class="flex gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span class="text-gray-600">Tmavší pražení</span>
                          </div>
                        </div>
                      </div>

                      <div class="mt-auto flex flex-col gap-4">
                        <!-- Checkbox jako button -->
                        <label class="flex items-center gap-3 px-4 py-3 bg-gray-100 hover:bg-gray-200 rounded-lg cursor-pointer transition-colors border border-gray-300">
                          <input type="checkbox" class="decaf-checkbox w-5 h-5 text-primary-500 rounded border-gray-300 focus:ring-primary-500 cursor-pointer" />
                          <span class="text-sm font-semibold text-gray-700">1x káva bez kofeinu</span>
                          <span class="w-12 ml-auto text-gray-400">
                            <span class="text-sm font-semibold text-gray-700">+ 100,-</span>
                          </span>
                        </label>

                        <button type="button" class="method-button block rounded-lg bg-gray-800 px-8 py-3 text-center text-sm font-semibold text-white outline-none ring-primary-300 transition duration-100 hover:bg-gray-700 focus-visible:ring active:bg-gray-600 md:text-base">Vybrat metodu</button>
                      </div>
                    </div>
                    <!-- Espresso - end -->

                    <!-- Filtr - start -->
                    <div class="flex flex-col rounded-lg border p-4 pt-6 coffee-type-card" data-type="filter">
                      <div class="mb-12">
                        <div class="mb-2 text-center text-3xl font-bold text-gray-800">Filtr</div>

                        <p class="mx-auto mb-8 px-8 text-center text-gray-500">Pro překapávanou kávu nebo french press</p>

                        <div class="space-y-2">
                          <div class="flex gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span class="text-gray-600">Lehčí tělo a vyšší kyselost</span>
                          </div>

                          <div class="flex gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span class="text-gray-600">Ovocné a květinové tóny</span>
                          </div>

                          <div class="flex gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span class="text-gray-600">Světlejší pražení</span>
                          </div>
                        </div>
                      </div>

                      <div class="mt-auto flex flex-col gap-4">
                        <!-- Checkbox jako button -->
                        <label class="flex items-center gap-3 px-4 py-3 bg-gray-100 hover:bg-gray-200 rounded-lg cursor-pointer transition-colors border border-gray-300">
                          <input type="checkbox" class="decaf-checkbox w-5 h-5 text-primary-500 rounded border-gray-300 focus:ring-primary-500 cursor-pointer" />
                          <span class="text-sm font-semibold text-gray-700">1x káva bez kofeinu</span>
                          <span class="w-12 ml-auto text-gray-400">
                            <span class="text-sm font-semibold text-gray-700">+ 100,-</span>
                          </span>
                        </label>

                        <button type="button" class="method-button block rounded-lg bg-gray-800 px-8 py-3 text-center text-sm font-semibold text-white outline-none ring-primary-300 transition duration-100 hover:bg-gray-700 focus-visible:ring active:bg-gray-600 md:text-base">Vybrat metodu</button>
                      </div>
                    </div>
                    <!-- Filtr - end -->

                    <!-- Kombinace - start -->
                    <div class="flex flex-col rounded-lg border p-4 pt-6 coffee-type-card" data-type="mix">
                      <div class="mb-12">
                        <div class="mb-2 text-center text-3xl font-bold text-gray-800">Kombinace</div>

                        <p class="mx-auto mb-8 px-8 text-center text-gray-500">Rozmanitost pro ty, kteří chtějí vyzkoušet obojí</p>

                        <div class="space-y-2">
                          <div class="flex gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span class="text-gray-600">Espresso i filtr v jednom</span>
                          </div>

                          <div class="flex gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span class="text-gray-600">Možnost přidat i decaf varianty</span>
                          </div>

                          <div class="flex gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span class="text-gray-600">Maximální rozmanitost</span>
                          </div>
                        </div>
                      </div>

                      <div class="mt-auto flex flex-col gap-4">
                        <!-- Checkbox jako button -->
                        <label class="flex items-center gap-3 px-4 py-3 bg-gray-100 hover:bg-gray-200 rounded-lg cursor-pointer transition-colors border border-gray-300">
                          <input type="checkbox" class="decaf-checkbox w-5 h-5 text-primary-500 rounded border-gray-300 focus:ring-primary-500 cursor-pointer" />
                          <span class="text-sm font-semibold text-gray-700">1x káva bez kofeinu</span>
                          <span class="w-12 ml-auto text-gray-400">
                            <span class="text-sm font-semibold text-gray-700">+ 100,-</span>
                          </span>
                        </label>

                        <button type="button" class="method-button block rounded-lg bg-gray-800 px-8 py-3 text-center text-sm font-semibold text-white outline-none ring-primary-300 transition duration-100 hover:bg-gray-700 focus-visible:ring active:bg-gray-600 md:text-base">Vybrat metodu</button>
                      </div>
                    </div>
                    <!-- Kombinace - end -->

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

                </div>

                <!-- Step 3 Header -->
                <div class="flex flex-col items-center justify-between gap-4 rounded-lg bg-gray-100 p-4 sm:flex-row md:p-8 mb-6">
                  <div>
                    <h2 class="text-xl font-bold text-primary-500 md:text-2xl">Krok 3: Frekvence dodání</h2>
                    <p class="text-gray-600">Jak často chcete kávu dostávat?</p>
                  </div>
                </div>

                <!-- Step 3: Frekvence -->
                <div id="step-3" class="mb-12">
                  <div class="mb-6 grid gap-6 sm:grid-cols-2 md:mb-8 lg:grid-cols-3 lg:gap-8">
                    
                    <!-- Každý měsíc - start -->
                    <div class="flex flex-col rounded-lg border p-4 pt-6 frequency-card" data-frequency="1" data-frequency-text="Každý měsíc">
                      <div class="mb-12">
                        <div class="mb-2 text-center text-3xl font-bold text-gray-800">Každý měsíc</div>

                        <p class="mx-auto mb-8 px-8 text-center text-gray-500">Pro pravidelnou spotřebu a čerstvou kávu vždy po ruce</p>

                        <div class="space-y-2">
                          <div class="flex gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span class="text-gray-600">Nejčerstvější káva</span>
                          </div>

                          <div class="flex gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span class="text-gray-600">Nikdy vám nedojde</span>
                          </div>

                          <div class="flex gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span class="text-gray-600">Ideální pro denní pití</span>
                          </div>
                        </div>
                      </div>

                      <div class="mt-auto">
                        <button type="button" class="frequency-button block rounded-lg bg-gray-800 px-8 py-3 text-center text-sm font-semibold text-white outline-none ring-primary-300 transition duration-100 hover:bg-gray-700 focus-visible:ring active:bg-gray-600 md:text-base w-full">Vybrat frekvenci</button>
                      </div>
                    </div>
                    <!-- Každý měsíc - end -->

                    <!-- Jednou za 2 měsíce - start -->
                    <div class="flex flex-col rounded-lg border p-4 pt-6 frequency-card" data-frequency="2" data-frequency-text="Jednou za 2 měsíce">
                      <div class="mb-12">
                        <div class="mb-2 text-center text-3xl font-bold text-gray-800">Jednou za 2 měsíce</div>

                        <p class="mx-auto mb-8 px-8 text-center text-gray-500">Pro střední spotřebu, když kávu pijete občas</p>

                        <div class="space-y-2">
                          <div class="flex gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span class="text-gray-600">Vyvážená frekvence</span>
                          </div>

                          <div class="flex gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span class="text-gray-600">Optimální pro menší spotřebu</span>
                          </div>

                          <div class="flex gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span class="text-gray-600">Stále čerstvá káva</span>
                          </div>
                        </div>
                      </div>

                      <div class="mt-auto">
                        <button type="button" class="frequency-button block rounded-lg bg-gray-800 px-8 py-3 text-center text-sm font-semibold text-white outline-none ring-primary-300 transition duration-100 hover:bg-gray-700 focus-visible:ring active:bg-gray-600 md:text-base w-full">Vybrat frekvenci</button>
                      </div>
                    </div>
                    <!-- Jednou za 2 měsíce - end -->

                    <!-- Jednou za 3 měsíce - start -->
                    <div class="flex flex-col rounded-lg border p-4 pt-6 frequency-card" data-frequency="3" data-frequency-text="Jednou za 3 měsíce">
                      <div class="mb-12">
                        <div class="mb-2 text-center text-3xl font-bold text-gray-800">Jednou za 3 měsíce</div>

                        <p class="mx-auto mb-8 px-8 text-center text-gray-500">Pro občasné pití, když kávu konzumujete jen výjimečně</p>

                        <div class="space-y-2">
                          <div class="flex gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span class="text-gray-600">Minimální závazek</span>
                          </div>

                          <div class="flex gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span class="text-gray-600">Pro příležitostné pití</span>
                          </div>

                          <div class="flex gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span class="text-gray-600">Flexibilní řešení</span>
                          </div>
                        </div>
                      </div>

                      <div class="mt-auto">
                        <button type="button" class="frequency-button block rounded-lg bg-gray-800 px-8 py-3 text-center text-sm font-semibold text-white outline-none ring-primary-300 transition duration-100 hover:bg-gray-700 focus-visible:ring active:bg-gray-600 md:text-base w-full">Vybrat frekvenci</button>
                      </div>
                    </div>
                    <!-- Jednou za 3 měsíce - end -->

                  </div>

                    <!-- Summary Section -->
                    <div class="mt-12">
                      <div class="flex flex-col overflow-hidden rounded-lg bg-gray-100 sm:flex-row md:min-h-80 border-2 border-gray-200 shadow-lg">
                        <!-- image - start -->
                        <div class="order-first h-64 w-full bg-gray-300 sm:order-none sm:h-auto sm:w-1/2 lg:w-2/5">
                          <img src="https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?auto=format&q=75&fit=crop&w=1000" loading="lazy" alt="Kávový box" class="h-full w-full object-cover object-center" id="summary-image" />
                        </div>
                        <!-- image - end -->

                        <!-- content - start -->
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
                            <button type="button" id="configure-subscription" class="w-full inline-block rounded-lg bg-primary-500 px-8 py-3 text-center text-sm font-semibold text-white outline-none ring-primary-300 transition duration-100 hover:bg-primary-600 focus-visible:ring active:bg-primary-700 md:text-base opacity-50 cursor-not-allowed" disabled>
                              Pokračovat k objednávce
                            </button>
                            <p class="text-xs text-gray-500 text-center mt-3">✓ Bez závazků  ✓ Kdykoli zrušte  ✓ Doprava zdarma</p>
                          </div>
                        </div>
                        <!-- content - end -->
                      </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Info -->
        <div class="mt-12 text-center">
            <p class="text-dark-600 mb-4">Máte otázky? <a href="#" class="text-primary-500 hover:text-primary-600 font-semibold">Kontaktujte nás</a></p>
            <div class="flex flex-wrap justify-center items-center gap-4 md:gap-8 text-sm text-dark-600">
                <span class="flex items-center">✓ Bez závazků</span>
                <span class="flex items-center">✓ Kdykoli zrušte</span>
                <span class="flex items-center">✓ Doprava zdarma</span>
            </div>
        </div>
    </div>
</div>

<!-- How it works -->
<section class="section section-gray">
    <div class="container-custom">
        <h2 class="font-display text-4xl md:text-5xl font-bold text-dark-800 text-center mb-16">Jak to funguje?</h2>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-10 max-w-6xl mx-auto">
            <div class="text-center">
                <div class="w-24 h-24 bg-primary-500 text-white flex items-center justify-center mx-auto mb-6 text-4xl font-black shadow-xl">
                    1
                </div>
                <h3 class="font-display text-2xl font-black text-dark-800 mb-4 uppercase tracking-wider">Vyberte balíček</h3>
                <p class="text-dark-600 leading-relaxed font-medium">Zvolte předplatné, které vám nejvíce vyhovuje podle vašich preferencí</p>
            </div>
            <div class="text-center">
                <div class="w-24 h-24 bg-primary-500 text-white flex items-center justify-center mx-auto mb-6 text-4xl font-black shadow-xl">
                    2
                </div>
                <h3 class="font-display text-2xl font-black text-dark-800 mb-4 uppercase tracking-wider">Pražíme na míru</h3>
                <p class="text-dark-600 leading-relaxed font-medium">Kávu pražíme čerstvě podle vašeho výběru a preferencí</p>
            </div>
            <div class="text-center">
                <div class="w-24 h-24 bg-primary-500 text-white flex items-center justify-center mx-auto mb-6 text-4xl font-black shadow-xl">
                    3
                </div>
                <h3 class="font-display text-2xl font-black text-dark-800 mb-4 uppercase tracking-wider">Doručíme domů</h3>
                <p class="text-dark-600 leading-relaxed font-medium">Každý měsíc dostanete čerstvou kávu zdarma až domů</p>
            </div>
            <div class="text-center">
                <div class="w-24 h-24 bg-primary-500 text-white flex items-center justify-center mx-auto mb-6 text-4xl font-black shadow-xl">
                    4
                </div>
                <h3 class="font-display text-2xl font-black text-dark-800 mb-4 uppercase tracking-wider">Vychutnáte si</h3>
                <p class="text-dark-600 leading-relaxed font-medium">Užijte si tu nejlepší kávu, kterou jste kdy měli</p>
            </div>
        </div>
    </div>
</section>

<!-- Benefits -->
<section class="section bg-white">
    <div class="container-custom">
        <h2 class="font-display text-4xl md:text-5xl font-bold text-dark-800 text-center mb-16">Proč předplatné?</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-12 max-w-5xl mx-auto">
            <div class="text-center">
                <div class="w-24 h-24 bg-primary-100 flex items-center justify-center mx-auto mb-6 shadow-lg">
                    <svg class="w-12 h-12 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="font-display text-2xl font-black text-dark-800 mb-4 uppercase tracking-wider">Výhodná cena</h3>
                <p class="text-dark-600 leading-relaxed font-medium">Ušetřete až 20% oproti jednorázovým nákupům a získejte dopravu zdarma</p>
            </div>
            <div class="text-center">
                <div class="w-24 h-24 bg-primary-100 flex items-center justify-center mx-auto mb-6 shadow-lg">
                    <svg class="w-12 h-12 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="font-display text-2xl font-black text-dark-800 mb-4 uppercase tracking-wider">Bez starosti</h3>
                <p class="text-dark-600 leading-relaxed font-medium">Nikdy vám nedojde káva díky pravidelným měsíčním dodávkám</p>
            </div>
            <div class="text-center">
                <div class="w-24 h-24 bg-primary-100 flex items-center justify-center mx-auto mb-6 shadow-lg">
                    <svg class="w-12 h-12 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                </div>
                <h3 class="font-display text-2xl font-black text-dark-800 mb-4 uppercase tracking-wider">Flexibilní</h3>
                <p class="text-dark-600 leading-relaxed font-medium">Změňte, pozastavte nebo zrušte kdykoli bez jakýchkoliv poplatků</p>
            </div>
        </div>
    </div>
</section>

<!-- Visual Section with Placeholder -->
<section class="section section-gray">
    <div class="container-custom">
        <div class="grid lg:grid-cols-2 gap-16 items-center">
            <div>
                <h2 class="font-display text-4xl md:text-5xl font-bold text-dark-800 mb-6">
                    Kvalita, které můžete věřit
                </h2>
                <p class="text-xl text-dark-600 mb-8 leading-relaxed">
                    Spolupracujeme s nejlepšími pražírnami v České republice. Každá káva je pečlivě vybrána 
                    a čerstvě pražena, aby vám přinesla ten nejlepší zážitek.
                </p>
                <ul class="space-y-4">
                    <li class="flex items-start">
                        <svg class="w-6 h-6 text-primary-500 mr-3 flex-shrink-0 mt-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-dark-700"><strong>100% Arabica</strong> z etických zdrojů</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="w-6 h-6 text-primary-500 mr-3 flex-shrink-0 mt-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-dark-700"><strong>Čerstvě pražená</strong> maximálně 7 dní před expedicí</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="w-6 h-6 text-primary-500 mr-3 flex-shrink-0 mt-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-dark-700"><strong>Ekologické balení</strong> s možností recyklace</span>
                    </li>
                </ul>
            </div>
            <div class="aspect-square overflow-hidden shadow-2xl img-placeholder">
                <div class="w-full h-full flex flex-col items-center justify-center p-12">
                    <svg class="w-32 h-32 text-bluegray-300 mb-6" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M2 21h19v-3H2v3zM20 8H4V5h16v3zm0-6H4c-1.1 0-2 .9-2 2v3c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zM12 15c1.66 0 3-1.34 3-3H9c0 1.66 1.34 3 3 3z"/>
                    </svg>
                    <p class="text-center leading-relaxed font-bold uppercase tracking-widest text-sm">Lifestyle fotografie: Osoba vychutnává si kávu z předplatného v útulném prostředí</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Shipping Date Info -->
<section class="section bg-white border-t border-bluegray-200">
    <div class="container-custom">
        <div class="max-w-4xl mx-auto">
            <div class="bg-primary-50 border-2 border-primary-200 p-8 mb-6">
                <div class="flex items-start gap-4">
                    <div class="flex-shrink-0">
                        <svg class="w-8 h-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-lg text-dark-800 mb-2">Termín následující rozesílky</h3>
                        <p class="text-dark-700 mb-3"><strong>{{ $shippingInfo['cutoff_message'] }}</strong></p>
                        <p class="text-sm text-dark-600 leading-relaxed">
                            <strong>Jak to funguje:</strong> Rozesílka kávy probíhá vždy <strong>20. dne v měsíci</strong>. 
                            Objednávky uzavíráme <strong>15. dne v měsíci o půlnoci</strong>. 
                            Pokud si objednáte od 16. dne, vaše první zásilka dorazí až při následující rozesílce.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Coffees of the Month -->
@if($coffeesOfMonth->count() > 0)
<section class="section bg-bluegray-50">
    <div class="container-custom">
        <div class="text-center mb-12">
            <h2 class="font-display text-3xl md:text-4xl font-black text-dark-800 mb-4">
                Kávy v následující rozesílce
            </h2>
            <p class="text-lg text-dark-600 max-w-2xl mx-auto">
                Tyto speciálně vybrané kávy budou součástí vašeho příštího předplatného
            </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 max-w-6xl mx-auto">
            @foreach($coffeesOfMonth as $coffee)
            <div class="bg-white overflow-hidden hover:shadow-xl transition-all duration-300 group">
                <div class="aspect-square overflow-hidden bg-bluegray-50 relative">
                    @if($coffee->image)
                    <img src="{{ $coffee->image }}" alt="{{ $coffee->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                    @else
                    <div class="w-full h-full flex flex-col items-center justify-center p-8 bg-gradient-to-br from-bluegray-100 to-bluegray-200">
                        <svg class="w-16 h-16 text-bluegray-400 mb-3" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M2 21h19v-3H2v3zM20 8H4V5h16v3zm0-6H4c-1.1 0-2 .9-2 2v3c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zM12 15c1.66 0 3-1.34 3-3H9c0 1.66 1.34 3 3 3z"/>
                        </svg>
                        <p class="text-center text-xs text-dark-600 font-bold">{{ $coffee->name }}</p>
                    </div>
                    @endif
                    <!-- Badge indicating coffee of the month -->
                    <div class="absolute top-3 right-3 bg-primary-500 text-white px-3 py-1 text-xs font-bold uppercase">
                        Káva měsíce
                    </div>
                </div>
                <div class="p-5">
                    <div class="flex items-start justify-between mb-2">
                        <div class="flex-1">
                            <h3 class="font-bold text-dark-800 mb-1 text-base">
                                {{ $coffee->name }}
                            </h3>
                            @if($coffee->short_description)
                            <p class="text-xs text-dark-600 mb-3 line-clamp-2">{{ $coffee->short_description }}</p>
                            @endif
                        </div>
                    </div>
                    @if($coffee->description)
                    <div class="pt-3 border-t border-bluegray-100">
                        <button 
                            onclick="showCoffeeModal({{ $coffee->id }})"
                            class="text-primary-500 hover:text-primary-600 font-bold text-sm transition-colors">
                            Zobrazit detail →
                        </button>
                    </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Coffee Detail Modal -->
<div id="coffee-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center p-4">
    <div class="bg-white max-w-3xl w-full max-h-[90vh] overflow-y-auto relative">
        <button onclick="closeCoffeeModal()" class="absolute top-4 right-4 text-dark-400 hover:text-dark-800 transition-colors z-10">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
        <div id="coffee-modal-content" class="p-8">
            <!-- Content will be loaded here -->
        </div>
    </div>
</div>

<script>
function showCoffeeModal(coffeeId) {
    const coffees = @json($coffeesOfMonth);
    const coffee = coffees.find(c => c.id === coffeeId);
    
    if (!coffee) return;
    
    const modal = document.getElementById('coffee-modal');
    const content = document.getElementById('coffee-modal-content');
    
    let imagesHtml = '';
    if (coffee.image) {
        imagesHtml = `<img src="${coffee.image}" alt="${coffee.name}" class="w-full h-80 object-cover mb-6">`;
    }
    
    content.innerHTML = `
        ${imagesHtml}
        <h2 class="font-display text-3xl font-black text-dark-800 mb-4">${coffee.name}</h2>
        ${coffee.short_description ? `<p class="text-lg text-dark-700 font-bold mb-4">${coffee.short_description}</p>` : ''}
        ${coffee.description ? `<div class="text-dark-600 prose max-w-none">${coffee.description}</div>` : ''}
        ${coffee.attributes ? `
            <div class="mt-6 grid grid-cols-2 gap-4">
                ${Object.entries(coffee.attributes).map(([key, value]) => `
                    <div class="bg-bluegray-50 p-3">
                        <p class="text-xs text-dark-600 font-bold uppercase mb-1">${key}</p>
                        <p class="text-dark-800 font-bold">${value}</p>
                    </div>
                `).join('')}
            </div>
        ` : ''}
    `;
    
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    document.body.style.overflow = 'hidden';
}

function closeCoffeeModal() {
    const modal = document.getElementById('coffee-modal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    document.body.style.overflow = '';
}

// Close modal on escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeCoffeeModal();
    }
});

// Close modal on backdrop click
document.getElementById('coffee-modal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeCoffeeModal();
    }
});
</script>
@endif

<!-- CTA -->
<section class="section bg-gradient-to-br from-primary-500 to-primary-700 text-white">
    <div class="container-custom text-center">
        <h2 class="font-display text-4xl md:text-5xl font-bold mb-6">
            Začněte svou kávovou cestu ještě dnes
        </h2>
        <p class="text-xl mb-10 max-w-2xl mx-auto opacity-95">
            Připojte se k tisícům spokojených zákazníků a objevte svět prémiové kávy
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="#plans" class="btn btn-white text-lg">
                Vybrat předplatné
            </a>
            <a href="{{ route('products.index') }}" class="btn btn-secondary text-lg">
                Procházet kávy
            </a>
        </div>
    </div>
</section>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const configurator = document.getElementById('subscription-configurator');
    const pricingData = JSON.parse(configurator.dataset.pricing);
    
    let selectedAmount = null;
    let selectedType = null;
    let maxBags = 0;
    let selectedFrequency = null;
    
    // Pro Espresso/Filtr s decaf
    let caffeineCount = 0;
    let decafCount = 0;
    
    // Pro Mix BEZ decaf
    let mixEspressoCount = 0;
    let mixFilterCount = 0;
    
    // Pro Mix S decaf
    let mixEspressoCafCount = 0;
    let mixEspressoDecafCount = 0;
    let mixFilterCafCount = 0;
    let mixFilterDecafCount = 0;
    
    // Step 1: Množství kávy
    document.querySelectorAll('.coffee-amount-option .plan-button').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const card = this.closest('.coffee-amount-option');
            
            // Ulož aktuální scroll pozici
            const scrollPos = window.pageYOffset || document.documentElement.scrollTop;
            
            // Reset všechny karty
            document.querySelectorAll('.coffee-amount-option').forEach(c => {
                c.classList.remove('border-2', 'border-primary-500');
                c.classList.add('border');
                const b = c.querySelector('.plan-button');
                if (b) {
                    b.classList.remove('bg-primary-500', 'hover:bg-primary-600', 'active:bg-primary-700');
                    b.classList.add('bg-gray-800', 'hover:bg-gray-700', 'active:bg-gray-600');
                    b.textContent = 'Vybrat plán';
                }
            });
            
            // Aktivuj vybranou kartu
            card.classList.remove('border');
            card.classList.add('border-2', 'border-primary-500');
            this.classList.remove('bg-gray-800', 'hover:bg-gray-700', 'active:bg-gray-600');
            this.classList.add('bg-primary-500', 'hover:bg-primary-600', 'active:bg-primary-700');
            this.textContent = 'Vybráno';
            
            selectedAmount = parseInt(card.dataset.amount);
            maxBags = selectedAmount;
            
            // Pokud je vybraná kombinace, přepočítej počty
            if (selectedType === 'mix') {
                mixEspressoCount = Math.floor(maxBags / 2);
                mixFilterCount = maxBags - mixEspressoCount;
                updateMixNoDecafDisplay();
            }
            
            // Vrať scroll na původní pozici
            window.scrollTo(0, scrollPos);
            
            // Dynamický přepočet shrnutí
            updateSummary();
            
            console.log('Selected amount:', selectedAmount, 'Max bags:', maxBags);
            
            return false;
        });
    });
    
    // Funkce pro zobrazení správného layoutu Rozdělení kávy
    function showDistributionLayout() {
        const caffeineDistribution = document.getElementById('caffeine-distribution');
        const simpleDistribution = document.getElementById('simple-distribution');
        const mixNoDecafDistribution = document.getElementById('mix-no-decaf-distribution');
        const mixWithDecafDistribution = document.getElementById('mix-with-decaf-distribution');
        
        // Skryj všechny layouty
        simpleDistribution.classList.add('hidden');
        mixNoDecafDistribution.classList.add('hidden');
        mixWithDecafDistribution.classList.add('hidden');
        
        const card = document.querySelector('.coffee-type-card.border-primary-500');
        if (!card) return;
        
        const checkbox = card.querySelector('.decaf-checkbox');
        const isDecafChecked = checkbox ? checkbox.checked : false;
        
        if (selectedType === 'espresso' || selectedType === 'filter') {
            // Pro Espresso/Filtr NEzobrazovat Rozdělení kávy (i když je decaf zaškrtnutý)
            caffeineDistribution.classList.add('hidden');
        } else if (selectedType === 'mix') {
            // Pro mix vždy zobraz blok jen s Espresso a Filtr (bez decaf variant)
            caffeineDistribution.classList.remove('hidden');
            mixNoDecafDistribution.classList.remove('hidden');
            
            // Reset počítadel
            mixEspressoCount = Math.floor(maxBags / 2);
            mixFilterCount = maxBags - mixEspressoCount;
            updateMixNoDecafDisplay();
        }
        
        // Vždy aktualizuj shrnutí
        updateSummary();
    }
    
    // Step 2: Typ kávy - kliknutí na kartu
    document.querySelectorAll('.coffee-type-card').forEach(card => {
        const btn = card.querySelector('.method-button');
        const checkbox = card.querySelector('.decaf-checkbox');
        
        // Kliknutí na button
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            // Reset všechny karty
            document.querySelectorAll('.coffee-type-card').forEach(c => {
                c.classList.remove('border-2', 'border-primary-500');
                c.classList.add('border');
                const b = c.querySelector('.method-button');
                if (b) {
                    b.classList.remove('bg-primary-500', 'hover:bg-primary-600', 'active:bg-primary-700');
                    b.classList.add('bg-gray-800', 'hover:bg-gray-700', 'active:bg-gray-600');
                    b.textContent = 'Vybrat metodu';
                }
            });
            
            // Aktivuj vybranou kartu
            card.classList.remove('border');
            card.classList.add('border-2', 'border-primary-500');
            btn.classList.remove('bg-gray-800', 'hover:bg-gray-700', 'active:bg-gray-600');
            btn.classList.add('bg-primary-500', 'hover:bg-primary-600', 'active:bg-primary-700');
            btn.textContent = 'Vybráno';
            
            selectedType = card.dataset.type;
            
            // Zobraz správný layout
            showDistributionLayout();
            
            console.log('Selected type:', selectedType);
        });
        
        // Změna checkboxu - zobraz/skryj rozdělení kávy a přepočítej shrnutí
        if (checkbox) {
            checkbox.addEventListener('change', function() {
                // Pouze pokud je karta vybraná
                if (card.classList.contains('border-primary-500')) {
                    showDistributionLayout();
                    updateSummary(); // Dynamický přepočet
                }
            });
        }
    });
    
    // Funkce pro update Simple distribution (Espresso/Filtr s decaf)
    function updateSimpleDisplay() {
        document.getElementById('caffeine-count').textContent = caffeineCount;
        document.getElementById('decaf-count').textContent = decafCount;
        
        const caffeinePlus = document.getElementById('caffeine-plus');
        const caffeineMinus = document.getElementById('caffeine-minus');
        const decafPlus = document.getElementById('decaf-plus');
        const decafMinus = document.getElementById('decaf-minus');
        
        caffeineMinus.disabled = caffeineCount <= 0;
        caffeinePlus.disabled = decafCount <= 0;
        decafMinus.disabled = decafCount <= 0;
        decafPlus.disabled = caffeineCount <= 0;
    }
    
    // Event listenery pro Simple distribution
    document.getElementById('caffeine-plus').addEventListener('click', function() {
        if (decafCount > 0) {
            caffeineCount++;
            decafCount--;
            updateSimpleDisplay();
        }
    });
    
    document.getElementById('caffeine-minus').addEventListener('click', function() {
        if (caffeineCount > 0) {
            caffeineCount--;
            decafCount++;
            updateSimpleDisplay();
        }
    });
    
    document.getElementById('decaf-plus').addEventListener('click', function() {
        if (caffeineCount > 0) {
            decafCount++;
            caffeineCount--;
            updateSimpleDisplay();
        }
    });
    
    document.getElementById('decaf-minus').addEventListener('click', function() {
        if (decafCount > 0) {
            decafCount--;
            caffeineCount++;
            updateSimpleDisplay();
        }
    });
    
    // Funkce pro update Mix NO decaf distribution
    function updateMixNoDecafDisplay() {
        document.getElementById('mix-espresso-count').textContent = mixEspressoCount;
        document.getElementById('mix-filter-count').textContent = mixFilterCount;
        
        const mixEspressoPlus = document.getElementById('mix-espresso-plus');
        const mixEspressoMinus = document.getElementById('mix-espresso-minus');
        const mixFilterPlus = document.getElementById('mix-filter-plus');
        const mixFilterMinus = document.getElementById('mix-filter-minus');
        
        mixEspressoMinus.disabled = mixEspressoCount <= 0;
        mixEspressoPlus.disabled = mixFilterCount <= 0;
        mixFilterMinus.disabled = mixFilterCount <= 0;
        mixFilterPlus.disabled = mixEspressoCount <= 0;
        
        // Dynamický přepočet shrnutí
        updateSummary();
    }
    
    // Event listenery pro Mix NO decaf distribution
    document.getElementById('mix-espresso-plus').addEventListener('click', function() {
        if (mixFilterCount > 0) {
            mixEspressoCount++;
            mixFilterCount--;
            updateMixNoDecafDisplay();
        }
    });
    
    document.getElementById('mix-espresso-minus').addEventListener('click', function() {
        if (mixEspressoCount > 0) {
            mixEspressoCount--;
            mixFilterCount++;
            updateMixNoDecafDisplay();
        }
    });
    
    document.getElementById('mix-filter-plus').addEventListener('click', function() {
        if (mixEspressoCount > 0) {
            mixFilterCount++;
            mixEspressoCount--;
            updateMixNoDecafDisplay();
        }
    });
    
    document.getElementById('mix-filter-minus').addEventListener('click', function() {
        if (mixFilterCount > 0) {
            mixFilterCount--;
            mixEspressoCount++;
            updateMixNoDecafDisplay();
        }
    });
    
    // Funkce pro update Mix WITH decaf distribution
    function updateMixWithDecafDisplay() {
        document.getElementById('mix-espresso-caf-count').textContent = mixEspressoCafCount;
        document.getElementById('mix-espresso-decaf-count').textContent = mixEspressoDecafCount;
        document.getElementById('mix-filter-caf-count').textContent = mixFilterCafCount;
        document.getElementById('mix-filter-decaf-count').textContent = mixFilterDecafCount;
        
        const total = mixEspressoCafCount + mixEspressoDecafCount + mixFilterCafCount + mixFilterDecafCount;
        const canIncrease = total < maxBags;
        const canDecrease = (count) => count > 0;
        
        document.getElementById('mix-espresso-caf-plus').disabled = !canIncrease;
        document.getElementById('mix-espresso-caf-minus').disabled = !canDecrease(mixEspressoCafCount);
        document.getElementById('mix-espresso-decaf-plus').disabled = !canIncrease;
        document.getElementById('mix-espresso-decaf-minus').disabled = !canDecrease(mixEspressoDecafCount);
        document.getElementById('mix-filter-caf-plus').disabled = !canIncrease;
        document.getElementById('mix-filter-caf-minus').disabled = !canDecrease(mixFilterCafCount);
        document.getElementById('mix-filter-decaf-plus').disabled = !canIncrease;
        document.getElementById('mix-filter-decaf-minus').disabled = !canDecrease(mixFilterDecafCount);
    }
    
    // Event listenery pro Mix WITH decaf distribution
    document.getElementById('mix-espresso-caf-plus').addEventListener('click', function() {
        const total = mixEspressoCafCount + mixEspressoDecafCount + mixFilterCafCount + mixFilterDecafCount;
        if (total < maxBags) {
            mixEspressoCafCount++;
            updateMixWithDecafDisplay();
        }
    });
    
    document.getElementById('mix-espresso-caf-minus').addEventListener('click', function() {
        if (mixEspressoCafCount > 0) {
            mixEspressoCafCount--;
            updateMixWithDecafDisplay();
        }
    });
    
    document.getElementById('mix-espresso-decaf-plus').addEventListener('click', function() {
        const total = mixEspressoCafCount + mixEspressoDecafCount + mixFilterCafCount + mixFilterDecafCount;
        if (total < maxBags) {
            mixEspressoDecafCount++;
            updateMixWithDecafDisplay();
        }
    });
    
    document.getElementById('mix-espresso-decaf-minus').addEventListener('click', function() {
        if (mixEspressoDecafCount > 0) {
            mixEspressoDecafCount--;
            updateMixWithDecafDisplay();
        }
    });
    
    document.getElementById('mix-filter-caf-plus').addEventListener('click', function() {
        const total = mixEspressoCafCount + mixEspressoDecafCount + mixFilterCafCount + mixFilterDecafCount;
        if (total < maxBags) {
            mixFilterCafCount++;
            updateMixWithDecafDisplay();
        }
    });
    
    document.getElementById('mix-filter-caf-minus').addEventListener('click', function() {
        if (mixFilterCafCount > 0) {
            mixFilterCafCount--;
            updateMixWithDecafDisplay();
        }
    });
    
    document.getElementById('mix-filter-decaf-plus').addEventListener('click', function() {
        const total = mixEspressoCafCount + mixEspressoDecafCount + mixFilterCafCount + mixFilterDecafCount;
        if (total < maxBags) {
            mixFilterDecafCount++;
            updateMixWithDecafDisplay();
        }
    });
    
    document.getElementById('mix-filter-decaf-minus').addEventListener('click', function() {
        if (mixFilterDecafCount > 0) {
            mixFilterDecafCount--;
            updateMixWithDecafDisplay();
        }
    });
    
    // Step 3: Frekvence
    document.querySelectorAll('.frequency-card .frequency-button').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const card = this.closest('.frequency-card');
            
            // Ulož aktuální scroll pozici
            const scrollPos = window.pageYOffset || document.documentElement.scrollTop;
            
            // Reset všechny karty
            document.querySelectorAll('.frequency-card').forEach(c => {
                c.classList.remove('border-2', 'border-primary-500');
                c.classList.add('border');
                const b = c.querySelector('.frequency-button');
                if (b) {
                    b.classList.remove('bg-primary-500', 'hover:bg-primary-600', 'active:bg-primary-700');
                    b.classList.add('bg-gray-800', 'hover:bg-gray-700', 'active:bg-gray-600');
                    b.textContent = 'Vybrat frekvenci';
                }
            });
            
            // Aktivuj vybranou kartu
            card.classList.remove('border');
            card.classList.add('border-2', 'border-primary-500');
            this.classList.remove('bg-gray-800', 'hover:bg-gray-700', 'active:bg-gray-600');
            this.classList.add('bg-primary-500', 'hover:bg-primary-600', 'active:bg-primary-700');
            this.textContent = 'Vybráno';
            
            selectedFrequency = parseInt(card.dataset.frequency);
            const frequencyText = card.dataset.frequencyText;
            
            // Vrať scroll na původní pozici
            window.scrollTo(0, scrollPos);
            
            updateSummary();
            
            console.log('Selected frequency:', selectedFrequency);
            
            return false;
        });
    });
    
    function updateSummary() {
        // Zkontroluj jestli je decaf zaškrtnutý
        const card = document.querySelector('.coffee-type-card.border-primary-500');
        const checkbox = card ? card.querySelector('.decaf-checkbox') : null;
        const isDecafChecked = checkbox ? checkbox.checked : false;
        
        // Aktualizuj shrnutí - zobraz i částečné informace
        if (selectedAmount) {
            document.getElementById('summary-amount').textContent = (selectedAmount * 250) + 'g (' + selectedAmount + ' balení)';
        } else {
            document.getElementById('summary-amount').textContent = '-';
        }
        
        let typeText = '-';
        
        if (selectedType === 'espresso' || selectedType === 'filter') {
            const typeName = selectedType === 'espresso' ? 'Espresso' : 'Filtr';
            if (isDecafChecked) {
                typeText = typeName + ' (vč. 1x decaf)';
            } else {
                typeText = typeName;
            }
        } else if (selectedType === 'mix') {
            // Pro mix zobraz rozdělení
            typeText = 'Kombinace (' + mixEspressoCount + ' espresso, ' + mixFilterCount + ' filtr';
            if (isDecafChecked) {
                typeText += ', vč. 1x decaf';
            }
            typeText += ')';
        }
        document.getElementById('summary-type').textContent = typeText;
        
        const freqTexts = {
            1: 'Každý měsíc',
            2: 'Jednou za 2 měsíce',
            3: 'Jednou za 3 měsíce'
        };
        document.getElementById('summary-frequency').textContent = freqTexts[selectedFrequency] || '-';
        
        // Vypočítej cenu - základní cena + decaf přirážka
        if (selectedAmount) {
            let price = pricingData[selectedAmount] || 0;
            if (isDecafChecked) {
                price += 100; // Přirážka za decaf
            }
            document.getElementById('summary-price').textContent = price.toLocaleString('cs-CZ') + ' Kč';
        } else {
            document.getElementById('summary-price').textContent = '-';
        }
        
        // Povol tlačítko pouze pokud jsou všechny kroky dokončeny
        const configureButton = document.getElementById('configure-subscription');
        if (selectedAmount && selectedType && selectedFrequency) {
            configureButton.disabled = false;
            configureButton.classList.remove('opacity-50', 'cursor-not-allowed');
        } else {
            configureButton.disabled = true;
            configureButton.classList.add('opacity-50', 'cursor-not-allowed');
        }
    }
    
    // Submit formuláře
    document.getElementById('configure-subscription').addEventListener('click', function() {
        if (!selectedAmount || !selectedType || !selectedFrequency) {
            alert('Prosím dokončete všechny kroky konfigurace.');
            return;
        }
        
        // Zkontroluj decaf checkbox
        const card = document.querySelector('.coffee-type-card.border-primary-500');
        const checkbox = card ? card.querySelector('.decaf-checkbox') : null;
        const isDecafChecked = checkbox ? checkbox.checked : false;
        
        // Vytvoř formulář a odešli data
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("subscriptions.configure.checkout") }}';
        
        // CSRF token
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = '{{ csrf_token() }}';
        form.appendChild(csrfInput);
        
        // Data
        const amountInput = document.createElement('input');
        amountInput.type = 'hidden';
        amountInput.name = 'amount';
        amountInput.value = selectedAmount;
        form.appendChild(amountInput);
        
        const typeInput = document.createElement('input');
        typeInput.type = 'hidden';
        typeInput.name = 'type';
        typeInput.value = selectedType;
        form.appendChild(typeInput);
        
        // Decaf flag (0 nebo 1)
        const decafInput = document.createElement('input');
        decafInput.type = 'hidden';
        decafInput.name = 'decaf';
        decafInput.value = isDecafChecked ? 1 : 0;
        form.appendChild(decafInput);
        
        // Pro kombinaci pošli i espresso/filter counts
        if (selectedType === 'mix') {
            const espressoInput = document.createElement('input');
            espressoInput.type = 'hidden';
            espressoInput.name = 'mix_espresso';
            espressoInput.value = mixEspressoCount;
            form.appendChild(espressoInput);
            
            const filterInput = document.createElement('input');
            filterInput.type = 'hidden';
            filterInput.name = 'mix_filter';
            filterInput.value = mixFilterCount;
            form.appendChild(filterInput);
        }
        
        const frequencyInput = document.createElement('input');
        frequencyInput.type = 'hidden';
        frequencyInput.name = 'frequency';
        frequencyInput.value = selectedFrequency;
        form.appendChild(frequencyInput);
        
        document.body.appendChild(form);
        form.submit();
    });
});
</script>
@endsection
