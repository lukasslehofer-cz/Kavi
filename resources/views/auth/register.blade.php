@extends('layouts.app')

@section('title', __('auth.register_title') . ' - KAVI')

@section('content')
<style>
.swiss-auth-field {
    display: flex;
    align-items: baseline;
    border-bottom: 1px solid #e5e5e5;
    transition: border-color 0.2s;
}
.swiss-auth-field:focus-within {
    border-bottom-color: #636747;
}
.swiss-auth-input {
    flex-grow: 1;
    padding: 0.75rem 0;
    background: transparent;
    border: none;
    font-size: 0.875rem;
    color: #1c1917;
    -webkit-appearance: none;
}
.swiss-auth-input:focus {
    outline: none;
    box-shadow: none;
}
.swiss-auth-input::placeholder {
    color: #a8a29e;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    font-size: 0.75rem;
}
.swiss-auth-label {
    flex-shrink: 0;
    font-size: 0.625rem;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    color: #78716c;
    padding-right: 1rem;
    min-width: 80px;
}
</style>

<!-- Hero Header - Swiss Style -->
<div class="pt-8 pb-12 sm:pt-12 sm:pb-16 lg:pt-16 lg:pb-20" style="background-color: rgb(245, 245, 244);">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="font-display text-5xl sm:text-6xl md:text-7xl lg:text-8xl font-normal uppercase tracking-tight leading-[0.9] text-dark-800">
            {{ $currentLocale === 'en' ? 'REGISTER' : 'REGISTRACE' }}
        </h1>
    </div>
</div>

<!-- Main Content -->
<div style="background-color: rgb(245, 245, 244);">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-24">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-x-16">
            <!-- Left Column - Form -->
            <div class="lg:col-span-7">
                <!-- Registration Form Section -->
                <div class="mb-16 border-t-2 border-primary-500 pt-6">
                    <h2 class="font-display text-4xl font-normal uppercase tracking-tight mb-8">
                        <span class="text-dark-800">{{ $currentLocale === 'en' ? 'YOUR ' : 'VAŠE ' }}</span><span class="text-primary-500">{{ $currentLocale === 'en' ? 'DETAILS' : 'ÚDAJE' }}</span>
                    </h2>
                    
                    <form method="POST" action="{{ localizedRoute('register') }}" class="space-y-6" id="register-form">
                        @csrf
                        <input type="hidden" name="recaptcha_token" id="recaptcha_token">

                        @error('recaptcha')
                        <p class="text-xs uppercase tracking-widest text-red-600 mb-4">{{ $message }}</p>
                        @enderror

                        <!-- Name -->
                        <div>
                            <div class="swiss-auth-field">
                                <label for="name" class="swiss-auth-label">{{ __('auth.full_name') }}</label>
                                <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                                       class="swiss-auth-input @error('name') border-red-500 @enderror"
                                       placeholder="{{ strtoupper(__('auth.name_placeholder')) }}">
                            </div>
                            @error('name')
                            <p class="text-xs uppercase tracking-widest text-red-600 mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <div class="swiss-auth-field">
                                <label for="email" class="swiss-auth-label">{{ __('auth.email') }}</label>
                                <input id="email" type="email" name="email" value="{{ old('email') }}" required
                                       class="swiss-auth-input @error('email') border-red-500 @enderror"
                                       placeholder="{{ strtoupper(__('auth.email_placeholder')) }}">
                            </div>
                            @error('email')
                            <p class="text-xs uppercase tracking-widest text-red-600 mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div>
                            <div class="swiss-auth-field">
                                <label for="password" class="swiss-auth-label">{{ __('auth.password') }}</label>
                                <input id="password" type="password" name="password" required
                                       class="swiss-auth-input @error('password') border-red-500 @enderror"
                                       placeholder="{{ strtoupper(__('auth.password_placeholder_min')) }}">
                            </div>
                            @error('password')
                            <p class="text-xs uppercase tracking-widest text-red-600 mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Password Confirmation -->
                        <div>
                            <div class="swiss-auth-field">
                                <label for="password_confirmation" class="swiss-auth-label">{{ __('auth.confirm_password') }}</label>
                                <input id="password_confirmation" type="password" name="password_confirmation" required
                                       class="swiss-auth-input"
                                       placeholder="{{ strtoupper(__('auth.confirm_password_placeholder')) }}">
                            </div>
                        </div>

                        <!-- Terms -->
                        <div class="flex items-start py-4">
                            <input type="checkbox" name="terms" id="terms" required 
                                   class="w-4 h-4 text-dark-800 border-dark-800 focus:ring-olive-500 mr-3 mt-0.5 flex-shrink-0">
                            <label for="terms" class="text-xs uppercase tracking-widest text-dark-800">
                                {{ __('auth.terms_agree') }} 
                                <a href="{{ localizedRoute('terms-of-service') }}" target="_blank" class="text-primary-500 hover:text-primary-600">{{ __('auth.terms_of_service') }}</a>
                                {{ __('auth.and') }}
                                <a href="{{ localizedRoute('privacy-policy') }}" target="_blank" class="text-primary-500 hover:text-primary-600">{{ __('auth.privacy_policy') }}</a>
                            </label>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="group w-full flex items-center justify-center gap-3 bg-dark-800 hover:bg-dark-900 text-white font-display uppercase tracking-widest px-6 py-4 transition-all duration-200">
                            <span>{{ __('auth.register_button') }}</span>
                            <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                            </svg>
                        </button>
                    </form>
                </div>

                <!-- Already have account -->
                <div class="border-t border-warm-300 pt-6">
                    <p class="text-xs uppercase tracking-widest text-warm-500 mb-4">{{ __('auth.have_account') }}</p>
                    <a href="{{ localizedRoute('login') }}" class="group flex items-center gap-3 text-dark-800 hover:text-primary-500 transition-colors">
                        <span class="font-display text-lg uppercase tracking-tight">{{ __('auth.login_here') }}</span>
                        <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Right Column - Benefits -->
            <div class="lg:col-span-5 mt-12 lg:mt-0">
                <div class="sticky top-24 bg-olive-100 p-8">
                    <h3 class="font-display text-2xl sm:text-3xl font-normal text-dark-800 uppercase tracking-tight mb-8">
                        {{ __('auth.benefits_title') }}
                    </h3>
                    
                    <div class="space-y-4 mb-8">
                        <div class="flex items-baseline gap-3">
                            <span class="text-primary-500">●</span>
                            <span class="text-xs uppercase tracking-widest text-olive-600">{{ __('auth.benefit_exclusive') }}</span>
                        </div>
                        <div class="flex items-baseline gap-3">
                            <span class="text-primary-500">●</span>
                            <span class="text-xs uppercase tracking-widest text-olive-600">{{ __('auth.benefit_subscription') }}</span>
                        </div>
                        <div class="flex items-baseline gap-3">
                            <span class="text-primary-500">●</span>
                            <span class="text-xs uppercase tracking-widest text-olive-600">{{ __('auth.benefit_orders') }}</span>
                        </div>
                    </div>

                    <div class="pt-6 border-t border-dark-800 space-y-2">
                        <div class="text-xs uppercase tracking-widest text-olive-600">
                            <span class="inline-block w-1 h-1 bg-olive-500 rounded-full mr-1"></span>
                            {{ __('auth.secure') }}
                        </div>
                        <div class="text-xs uppercase tracking-widest text-olive-600">
                            <span class="inline-block w-1 h-1 bg-olive-500 rounded-full mr-1"></span>
                            {{ __('auth.fast') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if(config('services.recaptcha.site_key'))
<script src="https://www.google.com/recaptcha/api.js?render={{ config('services.recaptcha.site_key') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('register-form');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            grecaptcha.ready(function() {
                grecaptcha.execute('{{ config('services.recaptcha.site_key') }}', {action: 'register'}).then(function(token) {
                    document.getElementById('recaptcha_token').value = token;
                    form.submit();
                });
            });
        });
    }
});
</script>
@endif
@endsection
