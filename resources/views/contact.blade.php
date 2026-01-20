@extends('layouts.app')

@section('title', __('pages.contact.title'))

@section('content')

<!-- Hero Header Section - Editorial Layout -->
<div style="background-color: #e5e6df;">
  <div class="max-w-screen-xl mx-auto px-4 md:px-8 pt-16 lg:pt-24 pb-8 lg:pb-12">
    
    <!-- Main Heading - Large Editorial Typography, Left aligned -->
    <h1 class="font-display text-5xl sm:text-5xl md:text-6xl lg:text-7xl xl:text-8xl font-normal leading-[0.95] sm:leading-[0.9] tracking-tight uppercase mb-12 lg:mb-16">
      <span class="text-dark-800">{{ $currentLocale === 'en' ? 'Get in' : 'Kontaktujte' }}</span><br>
      <span class="text-primary-500">{{ $currentLocale === 'en' ? 'touch' : 'nás' }}</span>
    </h1>
      
    <!-- Description - Right aligned -->
    <div class="flex justify-end">
      <p class="text-xs sm:text-sm uppercase tracking-widest text-warm-500 max-w-md text-right leading-relaxed">
        {{ __('pages.contact.subtitle') }}
      </p>
    </div>
  
  </div>
</div>

<!-- Contact Content -->
<div class="py-16 lg:py-24" style="background-color: #e5e6df;">
    <div class="mx-auto max-w-screen-xl px-4 md:px-8">
        <div class="grid lg:grid-cols-12 gap-12 lg:gap-16">
            
            <!-- Contact Form - Left Column -->
            <div class="lg:col-span-7">
                <!-- Section Heading -->
                <div class="flex items-baseline gap-4 mb-8 border-t-2 border-primary-500 pt-6">
                    <span class="text-primary-500 font-display text-2xl sm:text-3xl md:text-4xl font-normal">01</span>
                    <h2 class="font-display text-2xl sm:text-3xl md:text-4xl font-normal text-dark-800 uppercase tracking-tight">{{ __('pages.contact.form_title') }}</h2>
                </div>

                <p class="text-warm-500 mb-10 text-sm uppercase tracking-widest">
                    {{ __('pages.contact.form_subtitle') }}
                </p>

                <form id="contact-form" method="POST" action="{{ localizedRoute('contact.send') }}" class="space-y-8">
                    @csrf
                    <input type="hidden" name="recaptcha_token" id="recaptcha_token">
                    
                    <!-- Name -->
                    <div class="swiss-field">
                        <label for="name" class="swiss-field-label">
                            {{ __('pages.contact.form_name') }} <span class="text-primary-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="name" 
                            name="name" 
                            required
                            class="swiss-field-input"
                            placeholder="{{ __('pages.contact.form_name_placeholder') }}"
                        >
                    </div>

                    <!-- Email -->
                    <div class="swiss-field">
                        <label for="email" class="swiss-field-label">
                            {{ __('pages.contact.form_email') }} <span class="text-primary-500">*</span>
                        </label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            required
                            class="swiss-field-input"
                            placeholder="{{ __('pages.contact.form_email_placeholder') }}"
                        >
                    </div>

                    <!-- Message -->
                    <div class="swiss-field">
                        <label for="message" class="swiss-field-label">
                            {{ __('pages.contact.form_message') }}
                        </label>
                        <textarea 
                            id="message" 
                            name="message" 
                            rows="6"
                            class="swiss-textarea"
                            placeholder="{{ __('pages.contact.form_message_placeholder') }}"
                        ></textarea>
                    </div>

                    <!-- Submit Button -->
                    <button 
                        type="submit" 
                        class="inline-flex items-center justify-center gap-3 bg-dark-800 hover:bg-dark-900 text-white font-display uppercase tracking-widest px-8 py-4 transition-all duration-200"
                    >
                        <span>{{ __('pages.contact.form_submit') }}</span>
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </button>

                    <!-- Form Messages -->
                    <div id="form-message" class="hidden"></div>
                </form>
            </div>

            <!-- Contact Info - Right Column -->
            <div class="lg:col-span-5 space-y-8">
                
                <!-- Contact Info -->
                <div class="bg-[#BCBEB1] p-8">
                    <div class="flex items-baseline gap-4 mb-6">
                        <span class="text-primary-500 font-display text-2xl sm:text-3xl md:text-4xl font-normal">02</span>
                        <h3 class="font-display text-2xl sm:text-3xl md:text-4xl font-normal text-dark-800 uppercase tracking-tight">{{ __('pages.contact.info_title') }}</h3>
                    </div>
                    
                    <div class="space-y-4 border-t border-dark-800/20 pt-6">
                        <!-- Email -->
                        <div class="flex items-center gap-4">
                            <span class="text-xs uppercase tracking-widest text-dark-800/60">{{ __('pages.contact.info_email') }}</span>
                            @php
                                $contactEmail = app()->getLocale() === 'en' ? 'info@kavibox.com' : 'info@kavi.cz';
                            @endphp
                            <a href="mailto:{{ $contactEmail }}" class="text-dark-800 hover:text-primary-500 transition-colors font-display uppercase tracking-tight">
                                {{ $contactEmail }}
                            </a>
                        </div>                        
                    </div>
                </div>

                <!-- Billing Address -->
                <div class="border-t-2 border-dark-800 pt-6">
                    <h3 class="font-display text-lg font-normal text-dark-800 uppercase tracking-tight mb-6">{{ __('pages.contact.billing_title') }}</h3>
                    
                    <div class="space-y-2 text-sm text-warm-500 uppercase tracking-widest">
                        <p class="text-dark-800">Lukáš Šlehofer</p>
                        <p>Kurzova 2222/16</p>
                        <p>155 00 Praha 5</p>                        
                        <div class="pt-4 mt-4 border-t border-warm-300 space-y-1">
                            <p><span class="text-warm-400">{{ __('pages.contact.billing_id') }}:</span> <span class="text-dark-800">66899095</span></p>
                            <p><span class="text-warm-400">{{ __('pages.contact.billing_vat') }}:</span> <span class="text-dark-800">CZ7912150191</span></p>
                        </div>
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="border-t-2 border-dark-800 pt-6">
                    <h3 class="font-display text-lg font-normal text-dark-800 uppercase tracking-tight mb-6">{{ __('pages.contact.links_title') }}</h3>
                    <div class="space-y-3">
                        <a href="{{ localizedRoute('how-it-works') }}" class="group flex items-center gap-2 text-warm-500 hover:text-dark-800 transition-colors text-xs uppercase tracking-widest">
                            <span>{{ __('pages.contact.link_faq') }}</span>
                            <span class="group-hover:translate-x-1 transition-transform">→</span>
                        </a>
                        <a href="{{ localizedRoute('subscriptions.index') }}" class="group flex items-center gap-2 text-warm-500 hover:text-dark-800 transition-colors text-xs uppercase tracking-widest">
                            <span>{{ __('pages.contact.link_subscription') }}</span>
                            <span class="group-hover:translate-x-1 transition-transform">→</span>
                        </a>
                        @auth
                        <a href="{{ localizedRoute('dashboard.index') }}" class="group flex items-center gap-2 text-warm-500 hover:text-dark-800 transition-colors text-xs uppercase tracking-widest">
                            <span>{{ __('pages.contact.link_account') }}</span>
                            <span class="group-hover:translate-x-1 transition-transform">→</span>
                        </a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- CTA Section -->
<div class="relative pt-12 sm:pt-16 lg:pt-20 pb-20 sm:pb-24 lg:pb-28" style="background-color: #e5e6df;">
  <div class="relative mx-auto max-w-screen-xl px-4 md:px-8">
    <div class="mx-auto flex max-w-4xl flex-col items-center text-center">
      <!-- Large Editorial Heading -->
      <h2 class="font-display mb-8 text-3xl sm:text-4xl md:text-5xl font-normal text-primary-500 tracking-tight uppercase leading-tight sm:leading-[0.95]">
        {{ __('pages.contact.cta_title') }}
      </h2>

      <p class="mb-10 text-sm uppercase tracking-widest text-warm-500 max-w-xl leading-relaxed">
        {{ __('pages.contact.cta_text') }}
      </p>

      <!-- CTA Link -->
      <a href="{{ localizedRoute('subscriptions.index') }}" class="group inline-flex items-center gap-2 text-dark-800 font-display uppercase tracking-widest hover:text-primary-500 transition-all">
        <span>{{ __('pages.contact.cta_subscription') }}</span>
        <span class="group-hover:translate-x-1 transition-transform">→</span>
      </a>
    </div>
  </div>
</div>

<style>
.swiss-field {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}
.swiss-field-label {
    font-size: 0.625rem;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    color: #76716C;
}
.swiss-field-input {
    width: 100%;
    padding: 0.75rem 0;
    background: transparent;
    border: none;
    border-bottom: 1px solid #BCBEB1;
    color: #1c1c1c;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    transition: all 0.2s;
    outline: none;
    -webkit-appearance: none;
    box-shadow: none;
}
.swiss-field-input:focus {
    border-bottom-color: #636747;
}
.swiss-field-input::placeholder {
    color: #9a9c8f;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}
.swiss-textarea {
    width: 100%;
    padding: 0.75rem 0;
    background: transparent;
    border: none;
    border-bottom: 1px solid #BCBEB1;
    color: #1c1c1c;
    font-size: 0.875rem;
    letter-spacing: 0.02em;
    transition: all 0.2s;
    resize: none;
    outline: none;
    -webkit-appearance: none;
    box-shadow: none;
}
.swiss-textarea:focus {
    border-bottom-color: #636747;
}
.swiss-textarea::placeholder {
    color: #9a9c8f;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}
</style>

@if(config('services.recaptcha.site_key'))
<script src="https://www.google.com/recaptcha/api.js?render={{ config('services.recaptcha.site_key') }}"></script>
@endif
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('contact-form');
    const message = document.getElementById('form-message');

    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            const submitButton = form.querySelector('button[type="submit"]');
            const originalButtonText = submitButton.innerHTML;

            submitButton.disabled = true;
            submitButton.innerHTML = '<span>{{ __('pages.contact.form_sending') }}</span>';

            const submitForm = function() {
                const formData = new FormData(form);
                
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
                        message.className = 'p-4 text-xs uppercase tracking-widest text-green-600 border-t border-green-600';
                        message.textContent = '● STATUS / ' + (data.message || '{{ __('pages.contact.form_success') }}');
                        form.reset();
                    } else {
                        message.className = 'p-4 text-xs uppercase tracking-widest text-primary-500 border-t border-primary-500';
                        message.textContent = '● ERROR / ' + (data.message || '{{ __('pages.contact.form_error') }}');
                    }

                    setTimeout(() => {
                        message.classList.add('hidden');
                    }, 5000);
                })
                .catch(error => {
                    message.classList.remove('hidden');
                    message.className = 'p-4 text-xs uppercase tracking-widest text-primary-500 border-t border-primary-500';
                    message.textContent = '● ERROR / {{ __('pages.contact.form_error_email') }}';
                })
                .finally(() => {
                    submitButton.disabled = false;
                    submitButton.innerHTML = originalButtonText;
                });
            };

            @if(config('services.recaptcha.site_key'))
            grecaptcha.ready(function() {
                grecaptcha.execute('{{ config('services.recaptcha.site_key') }}', {action: 'contact'}).then(function(token) {
                    document.getElementById('recaptcha_token').value = token;
                    submitForm();
                });
            });
            @else
            submitForm();
            @endif
        });
    }
});
</script>

@endsection
