@extends('layouts.app')

@section('title', __('auth.login_title') . ' - KAVI')

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
            {{ $currentLocale === 'en' ? 'LOGIN' : 'PŘIHLÁŠENÍ' }}
        </h1>
    </div>
</div>

<!-- Main Content -->
<div style="background-color: rgb(245, 245, 244);">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-24">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-x-16">
            <!-- Left Column - Form -->
            <div class="lg:col-span-7">
                <!-- Login Form Section -->
                <div class="mb-16 border-t-2 border-primary-500 pt-6">
                    <h2 class="font-display text-4xl font-normal uppercase tracking-tight mb-8">
                        <span class="text-dark-800">{{ $currentLocale === 'en' ? 'LOGIN ' : 'PŘIHLAŠOVACÍ ' }}</span><span class="text-primary-500">{{ $currentLocale === 'en' ? 'CREDENTIALS' : 'ÚDAJE' }}</span>
                    </h2>
                    
                    <form method="POST" action="{{ localizedRoute('login') }}" class="space-y-6" id="login-form">
                        @csrf

                        <!-- Email -->
                        <div>
                            <div class="swiss-auth-field">
                                <label for="email" class="swiss-auth-label">{{ __('auth.email') }}</label>
                                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
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
                                       placeholder="••••••••">
                            </div>
                            @error('password')
                            <p class="text-xs uppercase tracking-widest text-red-600 mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Remember & Forgot -->
                        <div class="flex items-center justify-between py-4">
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" name="remember" class="w-4 h-4 text-dark-800 border-dark-800 focus:ring-olive-500 mr-3">
                                <span class="text-xs uppercase tracking-widest text-dark-800">{{ __('auth.remember_me') }}</span>
                            </label>
                            <a href="{{ localizedRoute('password.request') }}" class="text-xs uppercase tracking-widest text-warm-500 hover:text-dark-800 transition-colors">
                                {{ __('auth.forgot_password') }}
                            </a>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="group w-full flex items-center justify-center gap-3 bg-dark-800 hover:bg-dark-900 text-white font-display uppercase tracking-widest px-6 py-4 transition-all duration-200">
                            <span>{{ __('auth.login_button') }}</span>
                            <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                            </svg>
                        </button>
                    </form>
                </div>

                <!-- Magic Link Section -->
                <div class="mb-16 border-t-2 border-primary-500 pt-6">
                    <h2 class="font-display text-4xl font-normal uppercase tracking-tight mb-8">
                        <span class="text-dark-800">{{ $currentLocale === 'en' ? 'PASSWORDLESS ' : 'PŘIHLÁŠENÍ ' }}</span><span class="text-primary-500">{{ $currentLocale === 'en' ? 'LOGIN' : 'BEZ HESLA' }}</span>
                    </h2>

                    <p class="text-xs uppercase tracking-widest text-warm-500 mb-6">
                        {{ __('auth.magic_link_info') }}
                    </p>

                    <form method="POST" action="{{ localizedRoute('magic-link.send') }}" id="magic-link-form">
                        @csrf
                        <input type="hidden" name="email" id="magic-link-email">
                        <button type="button" onclick="sendMagicLink()" class="group flex items-center gap-3 text-dark-800 hover:text-primary-500 transition-colors">
                            <span class="text-xs uppercase tracking-widest border-b border-dark-800 group-hover:border-primary-500 pb-0.5">{{ __('auth.magic_link_button') }}</span>
                            <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Right Column - Info -->
            <div class="lg:col-span-5">
                <div class="sticky top-24 bg-olive-100 p-8">
                    <h3 class="font-display text-2xl sm:text-3xl font-normal text-dark-800 uppercase tracking-tight mb-8">
                        {{ __('auth.why_account') }}
                    </h3>
                    
                    <div class="space-y-4 mb-8">
                        <div class="flex items-baseline gap-3">
                            <span class="text-primary-500">●</span>
                            <span class="text-xs uppercase tracking-widest text-olive-600">{{ __('auth.benefit_orders') }}</span>
                        </div>
                        <div class="flex items-baseline gap-3">
                            <span class="text-primary-500">●</span>
                            <span class="text-xs uppercase tracking-widest text-olive-600">{{ __('auth.benefit_subscription') }}</span>
                        </div>
                        <div class="flex items-baseline gap-3">
                            <span class="text-primary-500">●</span>
                            <span class="text-xs uppercase tracking-widest text-olive-600">{{ __('auth.benefit_faster') }}</span>
                        </div>
                    </div>

                    <div class="pt-6 border-t border-dark-800">
                        <p class="text-xs uppercase tracking-widest text-olive-600 mb-4">{{ __('auth.no_account') }}</p>
                        <a href="{{ localizedRoute('register') }}" class="group flex items-center gap-3 text-dark-800 hover:text-primary-500 transition-colors">
                            <span class="font-display text-lg uppercase tracking-tight">{{ __('auth.register_free') }}</span>
                            <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function sendMagicLink() {
    const emailInput = document.getElementById('email');
    const email = emailInput.value.trim();
    
    if (!email) {
        alert('{{ __('auth.enter_email_alert') }}');
        emailInput.focus();
        return;
    }
    
    // Validate email
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        alert('{{ __('auth.invalid_email_alert') }}');
        emailInput.focus();
        return;
    }
    
    // Copy email to hidden field and submit form
    document.getElementById('magic-link-email').value = email;
    document.getElementById('magic-link-form').submit();
}

// Allow Enter key to send magic link when email input is focused
document.getElementById('email').addEventListener('keypress', function(e) {
    if (e.key === 'Enter' && e.shiftKey) {
        e.preventDefault();
        sendMagicLink();
    }
});
</script>

@endsection
