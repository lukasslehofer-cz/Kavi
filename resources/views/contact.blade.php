@extends('layouts.app')

@section('title', 'Kontakt - KAVI | Kávové předplatné')

@section('content')

<!-- Hero Section -->
<div class="relative bg-gray-100 py-16 md:py-20 overflow-hidden">
    <!-- Subtle Organic Shapes -->
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute -top-32 -right-32 w-96 h-96 bg-primary-100 rounded-full"></div>
        <div class="absolute -bottom-32 -left-32 w-[36rem] h-[36rem] bg-primary-50 rounded-full hidden md:block"></div>
    </div>
    
    <div class="relative mx-auto max-w-screen-xl px-4 md:px-8">
        <div class="text-center max-w-3xl mx-auto">
            <!-- Minimal Badge -->
            <div class="inline-flex items-center gap-2 bg-gray-100 rounded-full px-4 py-2 mb-6">
                <svg class="w-4 h-4 text-gray-900" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
                <span class="text-sm font-medium text-gray-900">Máte dotaz?</span>
            </div>
            
            <!-- Clean Heading -->
            <h1 class="mb-6 text-4xl md:text-5xl lg:text-6xl font-bold text-gray-900 leading-tight tracking-tight">
                Kontaktujte nás
            </h1>
            
            <p class="mx-auto max-w-2xl text-lg text-gray-600 font-light mb-8">
                Rádi odpovíme na vaše dotazy a pomůžeme vám s čímkoliv
            </p>
        </div>
    </div>
    
    <!-- Wave Divider -->
    <div class="absolute bottom-[-1px] left-0 right-0">
        <svg viewBox="0 0 1440 80" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full h-auto">
            <path d="M0 80L60 73C120 67 240 53 360 48C480 43 600 47 720 53C840 59 960 67 1080 69C1200 71 1320 67 1380 65L1440 63V80H1380C1320 80 1200 80 1080 80C960 80 840 80 720 80C600 80 480 80 360 80C240 80 120 80 60 80H0Z" fill="#ffffff"/>
        </svg>
    </div>
</div>

<!-- Contact Content -->
<div class="relative bg-white py-20 lg:py-28">
    <div class="mx-auto max-w-screen-xl px-4 md:px-8">
        <div class="grid lg:grid-cols-2 gap-12 lg:gap-16 max-w-6xl mx-auto">
            
            <!-- Contact Form -->
            <div>
                <div class="mb-8">
                    <div class="inline-flex items-center justify-center w-14 h-14 bg-gray-900 rounded-full mb-4">
                        <svg class="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                        </svg>
                    </div>
                    <h2 class="text-3xl font-bold text-gray-900 mb-3">Napište nám</h2>
                    <p class="text-gray-600 font-light leading-relaxed">
                        Vyplňte formulář a my se vám ozveme co nejdříve
                    </p>
                </div>

                <form id="contact-form" method="POST" action="{{ route('contact.send') }}" class="space-y-6">
                    @csrf
                    
                    <!-- Name / Company -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-900 mb-2">
                            Jméno / Firma <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="name" 
                            name="name" 
                            required
                            class="w-full px-4 py-3 rounded-lg bg-gray-50 border border-gray-200 text-gray-900 placeholder-gray-400 focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 transition-all"
                            placeholder="Vaše jméno nebo název firmy"
                        >
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-900 mb-2">
                            E-mail <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            required
                            class="w-full px-4 py-3 rounded-lg bg-gray-50 border border-gray-200 text-gray-900 placeholder-gray-400 focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 transition-all"
                            placeholder="vas@email.cz"
                        >
                    </div>

                    <!-- Message -->
                    <div>
                        <label for="message" class="block text-sm font-medium text-gray-900 mb-2">
                            Váš dotaz
                        </label>
                        <textarea 
                            id="message" 
                            name="message" 
                            rows="6"
                            class="w-full px-4 py-3 rounded-lg bg-gray-50 border border-gray-200 text-gray-900 placeholder-gray-400 focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 transition-all resize-none"
                            placeholder="Popište prosím váš dotaz..."
                        ></textarea>
                    </div>

                    <!-- Submit Button -->
                    <button 
                        type="submit" 
                        class="w-full inline-flex items-center justify-center gap-2 bg-gray-900 hover:bg-gray-800 text-white font-medium px-8 py-4 rounded-full transition-all duration-200"
                    >
                        <span>Odeslat zprávu</span>
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </button>

                    <!-- Form Messages -->
                    <div id="form-message" class="hidden"></div>
                </form>
            </div>

            <!-- Contact Info & Billing Address -->
            <div class="space-y-8">
                
                <!-- Contact Info -->
                <div class="bg-gray-50 rounded-2xl p-8 border border-gray-200">
                    <div class="inline-flex items-center justify-center w-14 h-14 bg-primary-500 rounded-full mb-6">
                        <svg class="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    
                    <h3 class="text-xl font-bold text-gray-900 mb-6">Kontaktní informace</h3>
                    
                    <div class="space-y-4">
                        <!-- Email -->
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0 w-10 h-10 bg-white rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div>
                                <div class="text-sm font-medium text-gray-500 mb-1">E-mail</div>
                                <a href="mailto:info@kavi.cz" class="text-gray-900 hover:text-primary-600 transition-colors font-medium">
                                    info@kavi.cz
                                </a>
                            </div>
                        </div>                        
                    </div>
                </div>

                <!-- Billing Address -->
                <div class="bg-white rounded-2xl p-8 border border-gray-200">
                    <div class="inline-flex items-center justify-center w-14 h-14 bg-gray-900 rounded-full mb-6">
                        <svg class="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    
                    <h3 class="text-xl font-bold text-gray-900 mb-6">Fakturační adresa</h3>
                    
                    <div class="space-y-3 text-gray-600 font-light leading-relaxed">
                        <p class="font-semibold text-gray-900">Lukáš Šlehofer</p>
                        <p>Kurzova 2222/16</p>
                        <p>155 00 Praha 5</p>                        
                        <div class="pt-4 mt-4 border-t border-gray-100 space-y-1">
                            <p><span class="text-gray-500">IČ:</span> <span class="font-medium text-gray-900">66899095</span></p>
                            <p><span class="text-gray-500">DIČ:</span> <span class="font-medium text-gray-900">CZ7912150191</span></p>
                        </div>
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="bg-primary-50 rounded-2xl p-8 border border-primary-100">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Užitečné odkazy</h3>
                    <div class="space-y-2">
                        <a href="{{ route('how-it-works') }}" class="block text-gray-700 hover:text-primary-600 transition-colors font-light">
                            → Jak to funguje (FAQ)
                        </a>
                        <a href="{{ route('subscriptions.index') }}" class="block text-gray-700 hover:text-primary-600 transition-colors font-light">
                            → Kávové předplatné
                        </a>
                        @auth
                        <a href="{{ route('dashboard.index') }}" class="block text-gray-700 hover:text-primary-600 transition-colors font-light">
                            → Můj účet
                        </a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Wave Divider -->
    <div class="absolute bottom-[-1px] left-0 right-0">
        <svg viewBox="0 0 1440 80" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full h-auto">
            <path d="M0 80L60 75C120 70 240 60 360 55C480 50 600 50 720 53.3C840 56.7 960 63.3 1080 65C1200 66.7 1320 63.3 1380 61.7L1440 60V80H1380C1320 80 1200 80 1080 80C960 80 840 80 720 80C600 80 480 80 360 80C240 80 120 80 60 80H0Z" fill="#F3F4F6"/>
        </svg>
    </div>
</div>

<!-- CTA Section - Clean Minimal -->
<div class="relative bg-gray-100 py-20 lg:py-24">
    
    <div class="relative mx-auto max-w-screen-xl px-4 md:px-8">
        <div class="mx-auto flex max-w-2xl flex-col items-center text-center">
            <!-- Heading -->
            <h2 class="mb-6 text-3xl md:text-4xl lg:text-5xl font-bold text-gray-900 leading-tight tracking-tight">
                Začněte svou kávovou cestu ještě dnes
            </h2>

            <p class="mb-10 text-lg text-gray-600 max-w-xl leading-relaxed font-light">
                Získejte přístup k nejlepší kávě z celé Evropy. Flexibilní předplatné, bez závazků.
            </p>

            <!-- CTA Buttons -->
            <div class="flex flex-col sm:flex-row gap-3">
                <a href="{{ route('subscriptions.index') }}" class="group inline-flex items-center justify-center gap-2 bg-primary-500 hover:bg-primary-600 text-white font-medium px-8 py-3 rounded-full transition-all duration-200">
                    <span>Vybrat předplatné</span>
                    <svg class="w-4 h-4 group-hover:translate-x-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                    </svg>
                </a>

                <a href="{{ route('products.index') }}" class="group inline-flex items-center justify-center gap-2 bg-white hover:bg-gray-50 text-gray-900 font-medium px-8 py-3 rounded-full border border-gray-200 transition-all duration-200">
                    <span>Procházet kávy</span>
                </a>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('contact-form');
    const message = document.getElementById('form-message');

    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(form);
            const submitButton = form.querySelector('button[type="submit"]');
            const originalButtonText = submitButton.innerHTML;

            // Disable button and show loading state
            submitButton.disabled = true;
            submitButton.innerHTML = '<span>Odesílám...</span>';

            fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                message.classList.remove('hidden');
                
                if (data.success) {
                    message.className = 'p-4 rounded-lg bg-green-100 text-green-800 border border-green-200';
                    message.textContent = data.message || 'Děkujeme! Vaše zpráva byla odeslána.';
                    form.reset();
                } else {
                    message.className = 'p-4 rounded-lg bg-red-100 text-red-800 border border-red-200';
                    message.textContent = data.message || 'Došlo k chybě. Zkuste to prosím znovu.';
                }

                // Hide message after 5 seconds
                setTimeout(() => {
                    message.classList.add('hidden');
                }, 5000);
            })
            .catch(error => {
                message.classList.remove('hidden');
                message.className = 'p-4 rounded-lg bg-red-100 text-red-800 border border-red-200';
                message.textContent = 'Došlo k chybě. Zkuste to prosím znovu nebo nás kontaktujte e-mailem.';
            })
            .finally(() => {
                // Re-enable button
                submitButton.disabled = false;
                submitButton.innerHTML = originalButtonText;
            });
        });
    }
});
</script>

@endsection

