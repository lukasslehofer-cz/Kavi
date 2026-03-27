@extends('layouts.app')

@section('title', $product->getName() . ($product->roastery ? ' – ' . $product->roastery->getName() : '') . ' | ' . ($currentLocale === 'en' ? 'KAVI' : 'KAVI.cz'))

@php
    $productDescription = Str::limit(strip_tags($product->getDescription()), 160);
    $siteUrl = $currentLocale === 'en' ? 'https://kavibox.com' : 'https://kavi.cz';
    
    // Country codes mapping
    $countryCodes = [
        'Belgie' => 'BE', 'Belgium' => 'BE',
        'Německo' => 'DE', 'Germany' => 'DE',
        'Polsko' => 'PL', 'Poland' => 'PL',
        'Portugalsko' => 'PT', 'Portugal' => 'PT',
        'Rakousko' => 'AT', 'Austria' => 'AT',
        'Nizozemsko' => 'NL', 'Netherlands' => 'NL',
        'Francie' => 'FR', 'France' => 'FR',
        'Španělsko' => 'ES', 'Spain' => 'ES',
        'Chorvatsko' => 'HR', 'Croatia' => 'HR',
        'Rumunsko' => 'RO', 'Romania' => 'RO',
        'Lotyšsko' => 'LV', 'Latvia' => 'LV',
        'Velká Británie' => 'GB', 'United Kingdom' => 'GB',
    ];
    $countryCode = $product->roastery ? ($countryCodes[$product->roastery->country] ?? strtoupper(substr($product->roastery->country, 0, 2))) : null;
@endphp

@section('meta_description', $productDescription)

@section('og_title')
{{ $product->getName() }}{{ $product->roastery ? ' – ' . $product->roastery->getName() : '' }} | {{ $currentLocale === 'en' ? 'KAVI' : 'KAVI.cz' }}
@endsection

@section('og_description', $productDescription)

@section('og_image')
{{ $product->image ? url($product->image) : $siteUrl . '/images/og-image.jpg' }}
@endsection

@section('og_type', 'product')

@section('structured_data')
@php
    $productPrice = $product->isOnSale() ? $product->sale_price : $product->price;
    $currency = $currentLocale === 'en' ? 'EUR' : 'CZK';

    // Determine availability status for Schema.org
    if (!$product->is_active) {
        $availability = 'https://schema.org/Discontinued';
    } elseif ($product->stock > 0) {
        $availability = 'https://schema.org/InStock';
    } else {
        $availability = 'https://schema.org/OutOfStock';
    }

    $productImageUrl = $product->image ? url($product->image) : $siteUrl . '/images/og-image.jpg';
    $brandName = $product->roastery ? $product->roastery->getName() : ($product->attributes['roaster'] ?? ($product->attributes['manufacturer'] ?? 'KAVI'));
@endphp
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@graph": [
        {
            "@type": "Product",
            "@id": "{{ url()->current() }}#product",
            "name": "{{ $product->getName() }}",
            "description": "{{ Str::limit(strip_tags($product->getDescription()), 500) }}",
            "image": "{{ $productImageUrl }}",
            "sku": "{{ $product->sku ?? 'KAVI-' . $product->id }}",
            "brand": {
                "@type": "Brand",
                "name": "{{ $brandName }}"
            },
            "offers": {
                "@type": "Offer",
                "url": "{{ url()->current() }}",
                "priceCurrency": "{{ $currency }}",
                "price": "{{ number_format($productPrice, 2, '.', '') }}",
                "availability": "{{ $availability }}",
                "seller": {
                    "@type": "Organization",
                    "name": "{{ $currentLocale === 'en' ? 'KAVI' : 'KAVI.cz' }}"
                }
            }
            @if($product->roastery)
            ,"manufacturer": {
                "@type": "Organization",
                "name": "{{ $product->roastery->getName() }}",
                "url": "{{ localizedRoute('roasteries.show', $product->roastery) }}"
            }
            @endif
            @if(!empty($product->attributes['origin']))
            ,"countryOfOrigin": {
                "@type": "Country",
                "name": "{{ $product->getTranslatedAttribute('origin') }}"
            }
            @endif
        },
        {
            "@type": "BreadcrumbList",
            "itemListElement": [
                {
                    "@type": "ListItem",
                    "position": 1,
                    "name": "{{ $currentLocale === 'en' ? 'Home' : 'Domů' }}",
                    "item": "{{ $siteUrl }}"
                },
                {
                    "@type": "ListItem",
                    "position": 2,
                    "name": "{{ $currentLocale === 'en' ? 'Shop' : 'Obchod' }}",
                    "item": "{{ localizedRoute('products.index') }}"
                },
                {
                    "@type": "ListItem",
                    "position": 3,
                    "name": "{{ $product->getName() }}",
                    "item": "{{ url()->current() }}"
                }
            ]
        }
    ]
}
</script>
@endsection

@section('content')
<div style="background-color: #e5e6df;">

  <!-- Hero Section - Split Screen -->
  <div class="relative">
    <div class="grid grid-cols-1 lg:grid-cols-12 min-h-[80vh]">
      
      <!-- Left Side - Product Photo Gallery (sticky to menu bottom) -->
      <div class="lg:col-span-6 relative lg:sticky lg:top-[64px] lg:max-h-[calc(100vh-64px)]">
        <div class="w-full min-h-[60vh] lg:min-h-[80vh] flex flex-col" style="background-color: #e5e6df;">

          @php
              $allImages = $product->getAllImages();
              $hasGallery = count($allImages) > 1;
          @endphp

          <!-- Main Image Container -->
          <div class="flex-1 relative flex items-center justify-center">
            @if(count($allImages) > 0)
            <img id="main-product-image"
                 src="{{ asset($allImages[0]) }}"
                 alt="{{ $product->getName() }}"
                 style="max-height: {{ $hasGallery ? 'calc(100vh - 64px - 150px)' : 'calc(100vh - 64px)' }};"
                 class="w-full object-contain p-8 lg:p-12 transition-opacity duration-300">
            @else
            <div class="w-full h-full flex items-center justify-center">
              <span class="font-display text-4xl text-warm-400 uppercase tracking-tight">{{ $product->getName() }}</span>
            </div>
            @endif

            <!-- Discount Badge -->
            @if($product->shouldShowDiscountPercentage())
            <div class="absolute top-4 right-4 lg:top-8 lg:right-8 z-10">
              <span class="text-xs uppercase tracking-widest text-primary-500 bg-[#e5e6df] px-2 py-1">-{{ $product->getDiscountPercentage() }}%</span>
            </div>
            @endif
          </div>

          <!-- Thumbnail Navigation -->
          @if($hasGallery)
          <div class="flex-shrink-0 px-4 pb-4 lg:px-8 lg:pb-6 relative z-20">
            <div class="flex gap-2 justify-center">
              @foreach($allImages as $index => $image)
              <button type="button"
                      onclick="switchImage('{{ asset($image) }}', {{ $index }})"
                      class="gallery-thumb w-16 h-16 lg:w-20 lg:h-20 border-2 transition-all cursor-pointer {{ $index === 0 ? 'border-dark-800' : 'border-gray-300 hover:border-gray-400' }}"
                      data-thumb-index="{{ $index }}">
                <img src="{{ asset($image) }}"
                     alt="{{ $product->getName() }} - {{ $index + 1 }}"
                     class="w-full h-full object-cover pointer-events-none">
              </button>
              @endforeach
            </div>

            <!-- Image Counter -->
            <div class="text-center mt-2">
              <span id="image-counter" class="text-xs uppercase tracking-widest text-warm-500">
                1/{{ count($allImages) }}
              </span>
            </div>
          </div>
          @endif

        </div>
      </div>
      
      <!-- Right Side - Content -->
      <div class="lg:col-span-6 flex flex-col px-6 md:px-12 lg:px-16 xl:px-20 pt-4 pb-12 lg:pt-4 lg:pb-20">
        
        <!-- Microscopic Breadcrumbs - at top -->
        <nav class="mb-24">
          <ol class="flex items-center gap-1 text-warm-400" style="font-size: 10px; letter-spacing: 0.1em;">
            <li><a href="{{ route('home') }}" class="uppercase hover:text-dark-800 transition-colors">{{ $currentLocale === 'en' ? 'Home' : 'Domů' }}</a></li>
            <li>/</li>
            <li><a href="{{ localizedRoute('products.index') }}" class="uppercase hover:text-dark-800 transition-colors">{{ $currentLocale === 'en' ? 'Shop' : 'Obchod' }}</a></li>
            <li>/</li>
            <li class="text-dark-800 uppercase">{{ Str::limit($product->getName(), 30) }}</li>
          </ol>
        </nav>
        
        <!-- Availability Status -->
        <div class="mb-4">
          @if(!$product->is_active)
          <span class="text-xs uppercase tracking-widest text-dark-800">
            <span class="inline-block w-1.5 h-1.5 bg-gray-500 rounded-full mr-1"></span>
            {{ $currentLocale === 'en' ? 'NO LONGER AVAILABLE' : 'UŽ NENÍ V NABÍDCE' }}
          </span>
          @elseif($product->stock > 0)
          <span class="text-xs uppercase tracking-widest text-dark-800">
            <span class="inline-block w-1.5 h-1.5 bg-green-500 rounded-full mr-1"></span>
            {{ $currentLocale === 'en' ? 'AVAILABILITY: IN STOCK' : 'DOSTUPNOST: SKLADEM' }}
          </span>
          @else
          <span class="text-xs uppercase tracking-widest text-dark-800">
            <span class="inline-block w-1.5 h-1.5 bg-red-500 rounded-full mr-1"></span>
            {{ $currentLocale === 'en' ? 'AVAILABILITY: OUT OF STOCK' : 'DOSTUPNOST: VYPRODÁNO' }}
          </span>
          @endif
        </div>
        
        <!-- Product Name - Large -->
        <h1 class="font-display text-5xl sm:text-5xl md:text-6xl lg:text-7xl font-normal text-dark-800 uppercase tracking-tight leading-[0.95] sm:leading-[0.9] mb-4">
          {{ $product->getName() }}
        </h1>
        
        <!-- Roastery / Manufacturer -->
        @if($product->roastery)
        <p class="text-xs uppercase tracking-widest text-warm-500 mb-6">
          <a href="{{ localizedRoute('roasteries.show', $product->roastery) }}" class="hover:text-dark-800 transition-colors">
            {{ $product->roastery->getName() }}
          </a>
          @if($countryCode)
          <span class="text-primary-500">[</span> <span class="text-dark-800">{{ $countryCode }}</span> <span class="text-primary-500">]</span>
          @endif
        </p>
        @elseif(!empty($product->attributes['roaster']))
        <p class="text-xs uppercase tracking-widest text-warm-500 mb-6">
          {{ $product->attributes['roaster'] }}
        </p>
        @elseif(!empty($product->attributes['manufacturer']))
        <p class="text-xs uppercase tracking-widest text-warm-500 mb-6">
          {{ $product->attributes['manufacturer'] }}
        </p>
        @endif
        
        <!-- Price - Prominent Technical Style -->
        @if($product->is_active)
        <div class="mb-8">
          @if($product->isOnSale())
          <div class="flex flex-wrap items-baseline gap-3">
            <span class="font-display text-2xl sm:text-3xl uppercase tracking-tight text-dark-800">{{ $product->getFormattedPrice() }} —</span>
            <span class="text-sm uppercase tracking-widest text-warm-400 line-through">{{ $product->getFormattedOriginalPrice() }}</span>
          </div>
          @else
          <span class="font-display text-2xl sm:text-3xl uppercase tracking-tight text-dark-800">{{ $product->getFormattedPrice() }} —</span>
          @endif
        </div>
        @endif
        
        <!-- Short Description -->
        @if($product->getShortDescription())
        <p class="text-base text-dark-700 font-light leading-relaxed mb-10 max-w-lg">
          {{ $product->getShortDescription() }}
        </p>
        @endif
        
        <!-- Purchase Configuration -->
        @if($product->is_active && $product->isInStock())
        <form id="add-to-cart-form" action="{{ localizedRoute('cart.add', $product) }}" method="POST" class="mb-12">
          @csrf

          <!-- Quantity Selector - Simple Row -->
          <div class="mb-6">
            <span class="block text-xs uppercase tracking-widest text-warm-500 mb-3">
              {{ $currentLocale === 'en' ? 'Quantity' : 'Množství' }}
            </span>
            <div class="flex items-center gap-4">
              @for($i = 1; $i <= min(5, $product->stock); $i++)
              <label class="cursor-pointer">
                <input type="radio" name="quantity" value="{{ $i }}" class="sr-only peer" {{ $i === 1 ? 'checked' : '' }}>
                <span class="text-sm uppercase tracking-widest text-warm-500 peer-checked:text-dark-800 peer-checked:border-b peer-checked:border-dark-800 pb-1 transition-all hover:text-dark-800">
                  {{ $i }}
                </span>
              </label>
              @endfor
            </div>
          </div>

          <!-- Add to Cart - Black Button -->
          <button type="submit" class="inline-flex items-center justify-center gap-2 bg-dark-800 text-white font-display uppercase tracking-widest px-8 py-4 hover:bg-dark-900 transition-all">
            <span>{{ $currentLocale === 'en' ? 'Add to Cart' : 'Přidat do košíku' }}</span>
            <span>→</span>
          </button>
        </form>
        @elseif(!$product->is_active)
        <div class="mb-12">
          <p class="text-sm text-warm-600 font-light leading-relaxed max-w-lg">
            {{ $currentLocale === 'en'
              ? 'This product is no longer available for purchase. You can explore similar products in the recommended section below or browse our current selection.'
              : 'Tento produkt již není v prodeji. Můžete prozkoumat podobné produkty v doporučené sekci níže nebo procházet naši aktuální nabídku.'
            }}
          </p>
        </div>
        @else
        <div class="mb-12">
          <p class="text-xs uppercase tracking-widest text-warm-500">
            {{ $currentLocale === 'en' ? 'This product is currently unavailable.' : 'Tento produkt je momentálně nedostupný.' }}
          </p>
        </div>
        @endif
        
        <!-- Specifications - Vertical Index -->
        <div class="border-t border-dark-800">
          <!-- Category -->
          @if(is_array($product->category) && !empty($product->category))
          <div class="border-b border-dark-800 py-4 flex items-baseline">
            <span class="text-xs uppercase tracking-widest text-warm-500 w-32 flex-shrink-0">
              {{ $currentLocale === 'en' ? 'Category' : 'Kategorie' }}
            </span>
            <span class="text-xs uppercase tracking-widest text-dark-800">—
              @php
                $categoryLabels = [
                  'espresso' => 'Espresso',
                  'filter' => $currentLocale === 'en' ? 'Filter' : 'Filtr',
                  'decaf' => $currentLocale === 'en' ? 'Decaf' : 'Bezkofeinová',
                  'accessories' => $currentLocale === 'en' ? 'Accessories' : 'Příslušenství',
                ];
                $cats = array_map(fn($c) => $categoryLabels[$c] ?? $c, $product->category);
                echo implode(', ', $cats);
              @endphp
            </span>
          </div>
          @endif

          <!-- Attributes - CATEGORY-BASED RENDERING -->
          @if($product->attributes)
            @if($product->isCoffee())
              {{-- Coffee: Fixed attributes --}}
              @php
                $mainAttributes = $currentLocale === 'en'
                  ? ['origin' => 'Origin', 'processing' => 'Processing', 'flavor_notes' => 'Flavor', 'altitude' => 'Altitude', 'variety' => 'Variety']
                  : ['origin' => 'Původ', 'processing' => 'Zpracování', 'flavor_notes' => 'Chuť', 'altitude' => 'Nadm. výška', 'variety' => 'Odrůda'];
              @endphp

              @foreach($mainAttributes as $key => $label)
                @php $attrValue = $product->getTranslatedAttribute($key); @endphp
                @if(!empty($attrValue))
                <div class="border-b border-dark-800 py-4 flex items-baseline">
                  <span class="text-xs uppercase tracking-widest text-warm-500 w-32 flex-shrink-0">{{ $label }}</span>
                  <span class="text-xs uppercase tracking-widest text-dark-800">— {{ $attrValue }}</span>
                </div>
                @endif
              @endforeach

              @if(!empty($product->attributes['weight']))
              <div class="border-b border-dark-800 py-4 flex items-baseline">
                <span class="text-xs uppercase tracking-widest text-warm-500 w-32 flex-shrink-0">
                  {{ $currentLocale === 'en' ? 'Weight' : 'Hmotnost' }}
                </span>
                <span class="text-xs uppercase tracking-widest text-dark-800">— {{ $product->attributes['weight'] }}g</span>
              </div>
              @endif

              @if(!empty($product->attributes['roast_date']))
              <div class="border-b border-dark-800 py-4 flex items-baseline">
                <span class="text-xs uppercase tracking-widest text-warm-500 w-32 flex-shrink-0">
                  {{ $currentLocale === 'en' ? 'Roast date' : 'Datum pražení' }}
                </span>
                <span class="text-xs uppercase tracking-widest text-dark-800">
                  — {{ \Carbon\Carbon::parse($product->attributes['roast_date'])->format($currentLocale === 'en' ? 'm/d/Y' : 'd.m.Y') }}
                </span>
              </div>
              @endif

            @elseif($product->isAccessory())
              {{-- Accessories: Custom attributes --}}
              @php $customAttrs = $product->getCustomAttributes(); @endphp

              @foreach($customAttrs as $attr)
                @php
                  // Get translated label and value
                  $label = $currentLocale === 'en' && !empty($attr['label_en'])
                    ? $attr['label_en']
                    : $attr['label_cs'];
                  $value = $currentLocale === 'en' && !empty($attr['value_en'])
                    ? $attr['value_en']
                    : $attr['value_cs'];
                @endphp

                @if(!empty($value))
                <div class="border-b border-dark-800 py-4 flex items-baseline">
                  <span class="text-xs uppercase tracking-widest text-warm-500 w-32 flex-shrink-0">{{ $label }}</span>
                  <span class="text-xs uppercase tracking-widest text-dark-800">— {{ $value }}</span>
                </div>
                @endif
              @endforeach
            @endif
          @endif
        </div>
        
      </div>
    </div>
  </div>
  
  <!-- Product Description - Editorial Two Columns -->
  @if($product->getDescription())
  <div class="max-w-screen-xl mx-auto px-6 md:px-8 py-16 lg:py-24">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-16">
      @php
        $description = strip_tags($product->getDescription());
        $midpoint = strlen($description) / 2;
        $breakpoint = strpos($description, '. ', $midpoint);
        if ($breakpoint === false) $breakpoint = $midpoint;
        $firstHalf = substr($description, 0, $breakpoint + 1);
        $secondHalf = substr($description, $breakpoint + 1);
      @endphp
      
      <div>
        <p class="text-base text-dark-700 font-light leading-relaxed">
          {{ trim($firstHalf) }}
        </p>
      </div>
      
      @if(trim($secondHalf))
      <div>
        <p class="text-base text-dark-600 font-light leading-relaxed">
          {{ trim($secondHalf) }}
        </p>
      </div>
      @endif
    </div>
  </div>
  @endif

  <!-- Related Products -->
  @if($relatedProducts->count() > 0)
  <div class="max-w-screen-xl mx-auto px-6 md:px-8 pb-16 lg:pb-24">
    
    <!-- Section Heading -->
    <h2 class="font-display text-3xl sm:text-4xl md:text-5xl font-normal text-dark-800 uppercase tracking-tight leading-tight sm:leading-[0.95] mb-10">
      {{ $currentLocale === 'en' ? 'You might also like' : 'Mohlo by vás také zajímat' }}
    </h2>
    
    <!-- Products Grid -->
    <div class="grid gap-x-4 sm:gap-x-8 lg:gap-x-10 gap-y-8 sm:gap-y-10 lg:gap-y-12 grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
      @foreach($relatedProducts as $relatedProduct)
      <a href="{{ localizedRoute('products.show', $relatedProduct) }}" class="group block">
        
        <!-- Product Image -->
        <div class="relative aspect-square overflow-hidden mb-4">
          @if($relatedProduct->image)
          <img src="{{ asset($relatedProduct->image) }}" 
               alt="{{ $relatedProduct->getName() }}" 
               class="w-full h-full object-contain group-hover:scale-105 transition-transform duration-500">
          @else
          <div class="w-full h-full flex items-center justify-center">
            <span class="text-warm-400 text-sm">{{ $relatedProduct->getName() }}</span>
          </div>
          @endif
          
          <!-- Category Tags -->
          <div class="absolute top-3 left-3 flex flex-wrap gap-1">
            @if(is_array($relatedProduct->category))
              @foreach($relatedProduct->category as $cat)
                @if($cat === 'espresso')
                <span class="text-[10px] uppercase tracking-widest text-dark-800 bg-[#e5e6df] px-1 pb-1 border-b-2 border-amber-500">ESP</span>
                @elseif($cat === 'filter')
                <span class="text-[10px] uppercase tracking-widest text-dark-800 bg-[#e5e6df] px-1 pb-1 border-b-2 border-blue-500">FLT</span>
                @elseif($cat === 'decaf')
                <span class="text-[10px] uppercase tracking-widest text-dark-800 bg-[#e5e6df] px-1 pb-1 border-b-2 border-green-500">DCF</span>
                @elseif($cat === 'accessories')
                <span class="text-[10px] uppercase tracking-widest text-dark-800 bg-[#e5e6df] px-1 pb-1 border-b-2 border-purple-500">ACC</span>
                @endif
              @endforeach
            @endif
          </div>
        </div>
        
        <!-- Price -->
        <div class="text-xs uppercase tracking-widest text-dark-800 mb-1">
          <span>{{ $relatedProduct->getFormattedPrice() }} —</span>
        </div>
        
        <!-- Product Name -->
        <h3 class="font-display text-base sm:text-lg font-normal text-dark-800 uppercase tracking-tight pt-[5px] pb-[8px] group-hover:text-primary-500 transition-colors line-clamp-3 sm:line-clamp-2" style="line-height: 1.25;">
          {{ $relatedProduct->getName() }}
        </h3>
        
        <!-- Roastery & Flavor -->
        @php
          $relFlavorNotes = $relatedProduct->getTranslatedAttribute('flavor_notes');
          $relRoasteryName = $relatedProduct->roastery ? $relatedProduct->roastery->getName() : null;
        @endphp
        <p class="text-xs uppercase tracking-widest text-warm-500 leading-tight">
          @if($relRoasteryName){{ $relRoasteryName }}@endif
          @if($relRoasteryName && $relFlavorNotes) · @endif
          @if($relFlavorNotes){{ $relFlavorNotes }}@endif
        </p>
      </a>
      @endforeach
    </div>
  </div>
  @endif

  <!-- Bottom CTA -->
  <div class="relative pt-8 sm:pt-10 lg:pt-14 pb-20 sm:pb-24 lg:pb-28" style="background-color: #e5e6df;">
    <div class="relative mx-auto max-w-screen-xl px-4 md:px-8">
      <div class="mx-auto flex max-w-4xl flex-col items-center text-center">
        <h2 class="font-display mb-8 text-3xl sm:text-4xl md:text-5xl font-normal text-primary-500 tracking-tight uppercase leading-tight sm:leading-[0.95]">
          {{ $currentLocale === 'en' ? 'Want regular coffee delivery?' : 'Chcete pravidelnou dodávku kávy?' }}
        </h2>

        <p class="mb-10 sm:mb-12 text-lg sm:text-xl text-warm-500 max-w-2xl leading-relaxed font-light">
          {{ $currentLocale === 'en' ? 'With our subscription, you save time and money. Fresh coffee delivered to your door, cancel anytime.' : 'S naším předplatným ušetříte čas i peníze. Čerstvá káva přímo k vám domů, kdykoliv zrušitelné.' }}
        </p>

        <a href="{{ localizedRoute('subscriptions.index') }}" class="group inline-flex items-center gap-2 text-dark-800 font-display uppercase tracking-widest hover:text-primary-500 transition-all">
          <span>{{ $currentLocale === 'en' ? 'Learn more about subscription' : 'Zjistit více o předplatném' }}</span>
          <span class="text-primary-500 group-hover:translate-x-1 transition-transform">→</span>
        </a>
      </div>
    </div>
  </div>

</div>

{{-- Gallery Image Switcher --}}
<script>
function switchImage(imageUrl, index) {
    // Fade out effect
    const mainImage = document.getElementById('main-product-image');
    mainImage.style.opacity = '0';

    setTimeout(() => {
        // Change image
        mainImage.src = imageUrl;

        // Fade in effect
        mainImage.style.opacity = '1';

        // Update active thumbnail border
        document.querySelectorAll('.gallery-thumb').forEach((thumb, i) => {
            if (i === index) {
                thumb.classList.remove('border-gray-300', 'hover:border-gray-400');
                thumb.classList.add('border-dark-800');
            } else {
                thumb.classList.remove('border-dark-800');
                thumb.classList.add('border-gray-300', 'hover:border-gray-400');
            }
        });

        // Update counter
        const counter = document.getElementById('image-counter');
        if (counter) {
            const totalImages = document.querySelectorAll('.gallery-thumb').length;
            counter.textContent = `${index + 1}/${totalImages}`;
        }
    }, 150);
}
</script>

{{-- Tracking: add_to_cart event (dataLayer + Meta Pixel) --}}
@php
    $trackingPrice = $product->getPrice();
    $trackingCurrency = \App\Helpers\CurrencyHelper::code();
@endphp
<script>
document.getElementById('add-to-cart-form')?.addEventListener('submit', function() {
    var quantity = parseInt(document.querySelector('input[name="quantity"]:checked')?.value || 1);
    var price = {{ $trackingPrice }};

    window.dataLayer = window.dataLayer || [];
    window.dataLayer.push({
        'event': 'add_to_cart',
        'ecommerce': {
            'currency': '{{ $trackingCurrency }}',
            'value': price * quantity,
            'items': [{
                'item_id': '{{ $product->id }}',
                'item_name': '{{ addslashes($product->getName()) }}',
                'price': price,
                'quantity': quantity
            }]
        }
    });

    // Meta Pixel - AddToCart
    if (typeof fbq !== 'undefined') {
        fbq('track', 'AddToCart', {
            content_ids: ['{{ $product->id }}'],
            content_type: 'product',
            value: price * quantity,
            currency: '{{ $trackingCurrency }}'
        });
    }
});
</script>

{{-- Tracking: view_item + ViewContent event (dataLayer + Meta Pixel) --}}
<script>
    window.dataLayer = window.dataLayer || [];
    window.dataLayer.push({
        'event': 'view_item',
        'ecommerce': {
            'currency': '{{ $trackingCurrency }}',
            'value': {{ $trackingPrice }},
            'items': [{
                'item_id': '{{ $product->id }}',
                'item_name': '{{ addslashes($product->getName()) }}',
                'price': {{ $trackingPrice }},
                'quantity': 1
                @if($product->roastery)
                ,'item_brand': '{{ addslashes($product->roastery->getName()) }}'
                @endif
                @if(is_array($product->category) && !empty($product->category))
                ,'item_category': '{{ $product->category[0] }}'
                @endif
            }]
        }
    });

    // Meta Pixel - ViewContent
    if (typeof fbq !== 'undefined') {
        fbq('track', 'ViewContent', {
            content_ids: ['{{ $product->id }}'],
            content_type: 'product',
            value: {{ $trackingPrice }},
            currency: '{{ $trackingCurrency }}'
        });
    }
</script>
@endsection
