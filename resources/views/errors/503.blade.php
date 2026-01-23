@extends('errors.layout')

@php
    $host = request()->getHost();
    $locale = $currentLocale ?? (str_contains($host, 'kavibox.com') ? 'en' : 'cs');
@endphp

@section('title', $locale === 'en' ? 'Maintenance | KAVI' : 'Údržba | KAVI.cz')

@section('content')
<div class="mx-auto max-w-screen-xl px-4 md:px-8 py-12 md:py-20">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 lg:gap-12">
        <!-- Left Column - Main Content -->
        <div class="lg:col-span-2">
            <!-- Error Code -->
            <div class="mb-8">
                <span class="text-[120px] sm:text-[180px] md:text-[220px] font-bold leading-none text-red-600">
                    503
                </span>
            </div>

            <!-- Heading -->
            <h1 class="text-4xl sm:text-5xl md:text-6xl font-bold uppercase tracking-tight mb-6 leading-[0.95]">
                {{ $locale === 'en' ? 'Under' : 'Probíhá' }}<br>
                <span class="text-red-600">{{ $locale === 'en' ? 'Maintenance' : 'Údržba' }}</span>
            </h1>

            <!-- Description -->
            <p class="text-lg md:text-xl text-[#1c1c1c]/70 mb-10 max-w-xl leading-relaxed">
                {{ $locale === 'en' 
                    ? 'Our website is currently undergoing maintenance to bring you even better services. We\'ll be back soon with fresh coffee!' 
                    : 'Náš web právě prochází údržbou, abychom vám mohli nabídnout ještě lepší služby. Už brzy budeme zpět s čerstvou kávou!' }}
            </p>

            <!-- Estimated Time -->
            <div class="inline-flex items-center gap-4 border border-[#1c1c1c]/20 px-6 py-4 mb-10">
                <div>
                    <p class="text-xs uppercase tracking-widest text-[#1c1c1c]/50">
                        {{ $locale === 'en' ? 'Estimated Return' : 'Předpokládaný návrat' }}
                    </p>
                    <p class="text-lg font-medium">
                        {{ $locale === 'en' ? 'Within a few minutes' : 'Během několika minut' }}
                    </p>
                </div>
            </div>

            <!-- CTA Button -->
            <div class="flex flex-wrap gap-4 mb-12">
                <button onclick="window.location.reload()" class="inline-flex items-center justify-center bg-[#1c1c1c] text-white text-xs uppercase tracking-widest px-8 py-4 hover:bg-[#1c1c1c]/80 transition-colors">
                    {{ $locale === 'en' ? 'Check Availability' : 'Zkontrolovat dostupnost' }}
                </button>
            </div>

            <!-- Newsletter Section -->
            <div class="pt-8 border-t border-[#1c1c1c]/20">
                <p class="text-xs uppercase tracking-widest text-[#1c1c1c]/50 mb-4">
                    {{ $locale === 'en' ? 'Stay Updated' : 'Zůstaňte v obraze' }}
                </p>
                <p class="text-sm text-[#1c1c1c]/70 mb-6 max-w-lg">
                    {{ $locale === 'en' 
                        ? 'Subscribe to our newsletter and be the first to know when we\'re back online.' 
                        : 'Přihlaste se k odběru newsletteru a buďte první, kdo se dozví o našem návratu.' }}
                </p>
                
                <form id="newsletter-form-maintenance" class="flex flex-col sm:flex-row gap-3 max-w-md">
                    @csrf
                    <input 
                        type="email" 
                        name="email" 
                        placeholder="{{ $locale === 'en' ? 'YOUR EMAIL' : 'VÁŠ E-MAIL' }}" 
                        required
                        class="flex-1 px-4 py-3 bg-transparent border-b border-[#1c1c1c] text-sm uppercase tracking-widest placeholder:text-[#1c1c1c]/40 focus:outline-none focus:border-red-600 transition-colors"
                    >
                    <button type="submit" class="px-6 py-3 bg-[#1c1c1c] text-white text-xs uppercase tracking-widest hover:bg-[#1c1c1c]/80 transition-colors whitespace-nowrap">
                        {{ $locale === 'en' ? 'Subscribe' : 'Odebírat' }}
                    </button>
                </form>
                <div id="newsletter-message-maintenance" class="mt-4 text-sm hidden"></div>
            </div>
        </div>

        <!-- Right Column - Sidebar -->
        <div class="lg:col-span-1">
            <div class="bg-[#BCBEB1] p-8 h-full">
                <h2 class="text-xs uppercase tracking-widest mb-6 border-b border-[#1c1c1c] pb-4">
                    {{ $locale === 'en' ? 'What\'s Happening?' : 'Co se děje?' }}
                </h2>

                <div class="space-y-6">
                    <!-- Update 1 -->
                    <div class="flex items-start gap-3">
                        <span class="text-red-600 mt-1">●</span>
                        <div>
                            <p class="text-sm">
                                {{ $locale === 'en' ? 'Improving site performance' : 'Vylepšujeme výkon webu' }}
                            </p>
                        </div>
                    </div>

                    <!-- Update 2 -->
                    <div class="flex items-start gap-3">
                        <span class="text-red-600 mt-1">●</span>
                        <div>
                            <p class="text-sm">
                                {{ $locale === 'en' ? 'Adding new features' : 'Přidáváme nové funkce' }}
                            </p>
                        </div>
                    </div>

                    <!-- Update 3 -->
                    <div class="flex items-start gap-3">
                        <span class="text-red-600 mt-1">●</span>
                        <div>
                            <p class="text-sm">
                                {{ $locale === 'en' ? 'Updating security' : 'Aktualizujeme zabezpečení' }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Contact -->
                <div class="mt-8 pt-6 border-t border-[#1c1c1c]/20">
                    <p class="text-xs uppercase tracking-widest text-[#1c1c1c]/50 mb-2">
                        {{ $locale === 'en' ? 'Questions?' : 'Dotazy?' }}
                    </p>
                    <a href="mailto:{{ $locale === 'en' ? 'info@kavibox.com' : 'info@kavi.cz' }}" class="text-sm hover:text-red-600 transition-colors">
                        {{ $locale === 'en' ? 'info@kavibox.com' : 'info@kavi.cz' }}
                    </a>
                </div>

                <!-- Error Info -->
                <div class="mt-6 pt-6 border-t border-[#1c1c1c]/20">
                    <p class="text-xs uppercase tracking-widest text-[#1c1c1c]/50">
                        {{ $locale === 'en' ? 'Status' : 'Stav' }}
                    </p>
                    <p class="text-sm mt-1">503 / {{ $locale === 'en' ? 'Service Unavailable' : 'Služba nedostupná' }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('newsletter-form-maintenance');
    const message = document.getElementById('newsletter-message-maintenance');
    const locale = '{{ $locale }}';
    
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(form);
            const submitButton = form.querySelector('button[type="submit"]');
            const originalText = submitButton.textContent;
            
            submitButton.disabled = true;
            submitButton.textContent = locale === 'en' ? 'SENDING...' : 'ODESÍLÁM...';
            
            fetch('{{ localizedRoute("newsletter.subscribe") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    email: formData.get('email')
                })
            })
            .then(response => response.json())
            .then(data => {
                message.classList.remove('hidden');
                
                if (data.success) {
                    message.className = 'mt-4 text-sm text-green-700';
                    message.textContent = data.message;
                    form.reset();
                } else {
                    message.className = 'mt-4 text-sm text-red-600';
                    message.textContent = data.message;
                }
                
                setTimeout(() => {
                    message.classList.add('hidden');
                }, 5000);
            })
            .catch(error => {
                message.classList.remove('hidden');
                message.className = 'mt-4 text-sm text-red-600';
                message.textContent = locale === 'en' ? 'An error occurred. Please try again later.' : 'Došlo k chybě. Zkuste to prosím později.';
            })
            .finally(() => {
                submitButton.disabled = false;
                submitButton.textContent = originalText;
            });
        });
    }
});
</script>
@endsection
