@extends('layouts.app')

@section('title', 'Údržba | KAVI.cz')

@section('content')
<div class="relative min-h-[70vh] flex items-center justify-center overflow-hidden bg-white">
    <!-- Organic shape decorations -->
    <div class="absolute top-0 right-0 w-96 h-96 bg-primary-100 rounded-full translate-x-1/2 -translate-y-1/2 opacity-60"></div>
    <div class="absolute bottom-0 left-0 w-80 h-80 bg-gray-100 rounded-full -translate-x-1/2 translate-y-1/2"></div>

    <div class="relative mx-auto max-w-screen-xl px-4 md:px-8 py-16 sm:py-20">
        <div class="mx-auto max-w-2xl text-center">
            <!-- Decorative Badge -->
            <div class="inline-flex items-center gap-2 bg-primary-50 rounded-full px-4 py-2 mb-8">
                <svg class="w-5 h-5 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                </svg>
                <span class="text-sm font-medium text-primary-600">Probíhá údržba</span>
            </div>

            <!-- Large Coffee Icon -->
            <div class="relative mb-8">
                <div class="inline-flex items-center justify-center w-40 h-40 sm:w-48 sm:h-48 rounded-full bg-gradient-to-br from-primary-50 to-primary-100">
                    <svg class="w-24 h-24 sm:w-32 sm:h-32 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                    </svg>
                </div>
            </div>

            <!-- Heading -->
            <h2 class="text-3xl sm:text-4xl md:text-5xl font-bold text-gray-900 mb-6 leading-tight tracking-tight">
                Chvíli si vaříme kávu
            </h2>

            <!-- Description -->
            <p class="text-lg sm:text-xl text-gray-600 leading-relaxed font-light mb-10 max-w-xl mx-auto">
                Náš web právě prochází údržbou, abychom vám mohli nabídnout ještě lepší služby. Už brzy budeme zpět s čerstvou kávou!
            </p>

            <!-- Estimated Time (optional - you can remove or customize) -->
            <div class="inline-flex items-center gap-3 bg-gray-50 rounded-2xl px-6 py-4 mb-10">
                <svg class="w-6 h-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div class="text-left">
                    <div class="text-sm text-gray-500 font-light">Předpokládaný čas návratu</div>
                    <div class="text-lg font-semibold text-gray-900">Během několika minut</div>
                </div>
            </div>

            <!-- CTA Button -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center mb-12">
                <button onclick="window.location.reload()" class="group inline-flex items-center justify-center gap-2 bg-primary-500 hover:bg-primary-600 text-white font-medium text-lg px-8 py-4 rounded-full transition-all duration-200">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    <span>Zkontrolovat dostupnost</span>
                </button>
            </div>

            <!-- Newsletter Suggestion -->
            <div class="pt-8 border-t border-gray-100">
                <p class="text-sm text-gray-600 font-light mb-4">
                    Zatímco čekáte, přihlaste se k odběru našeho newsletteru:
                </p>
                <div class="max-w-md mx-auto">
                    <form id="newsletter-form-maintenance" class="flex gap-2">
                        @csrf
                        <input 
                            type="email" 
                            name="email" 
                            placeholder="Váš e-mail" 
                            required
                            class="flex-1 px-5 py-3 rounded-full bg-gray-50 border border-gray-200 text-gray-900 placeholder-gray-400 focus:outline-none focus:border-primary-300 focus:ring-1 focus:ring-primary-300 transition-all text-sm"
                        >
                        <button type="submit" class="px-6 py-3 bg-gray-900 hover:bg-gray-800 text-white font-medium rounded-full transition-all duration-200 whitespace-nowrap text-sm">
                            Odebírat
                        </button>
                    </form>
                    <div id="newsletter-message-maintenance" class="mt-4 text-sm hidden"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('newsletter-form-maintenance');
    const message = document.getElementById('newsletter-message-maintenance');
    
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(form);
            const submitButton = form.querySelector('button[type="submit"]');
            const originalText = submitButton.textContent;
            
            submitButton.disabled = true;
            submitButton.textContent = 'Odesílám...';
            
            fetch('{{ route("newsletter.subscribe") }}', {
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
                    message.className = 'mt-4 text-sm p-3 rounded-lg bg-green-100 text-green-800 border border-green-200';
                    message.textContent = data.message;
                    form.reset();
                } else {
                    message.className = 'mt-4 text-sm p-3 rounded-lg bg-red-100 text-red-800 border border-red-200';
                    message.textContent = data.message;
                }
                
                setTimeout(() => {
                    message.classList.add('hidden');
                }, 5000);
            })
            .catch(error => {
                message.classList.remove('hidden');
                message.className = 'mt-4 text-sm p-3 rounded-lg bg-red-100 text-red-800 border border-red-200';
                message.textContent = 'Došlo k chybě. Zkuste to prosím později.';
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

