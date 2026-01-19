@extends('layouts.app')

@section('title', __('auth.forgot_password_title') . ' - KAVI')

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
            {{ $currentLocale === 'en' ? 'RESET PASSWORD' : 'OBNOVA HESLA' }}
        </h1>
    </div>
</div>

<!-- Main Content -->
<div style="background-color: rgb(245, 245, 244);">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-24">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-x-16">
            <!-- Left Column - Form -->
            <div class="lg:col-span-7">
                <!-- Success Message -->
                @if (session('status'))
                <div class="mb-8 py-4 border-t-2 border-green-600">
                    <p class="text-xs uppercase tracking-widest text-green-600">STATUS / {{ strtoupper(session('status')) }}</p>
                    <p class="text-xs uppercase tracking-widest text-warm-500 mt-2">{{ __('auth.check_spam') }}</p>
                </div>
                @endif

                <!-- Reset Form Section -->
                <div class="mb-16 border-t-2 border-primary-500 pt-6">
                    <h2 class="font-display text-4xl font-normal uppercase tracking-tight mb-8">
                        <span class="text-dark-800">{{ $currentLocale === 'en' ? 'YOUR ' : 'VÁŠ ' }}</span><span class="text-primary-500">{{ $currentLocale === 'en' ? 'EMAIL' : 'EMAIL' }}</span>
                    </h2>
                    
                    <form method="POST" action="{{ localizedRoute('password.email') }}" class="space-y-6">
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

                        <!-- Submit Button -->
                        <button type="submit" class="group w-full flex items-center justify-center gap-3 bg-dark-800 hover:bg-dark-900 text-white font-display uppercase tracking-widest px-6 py-4 transition-all duration-200">
                            <span>{{ __('auth.send_reset_link') }}</span>
                            <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                            </svg>
                        </button>
                    </form>
                </div>

                <!-- Navigation -->
                <div class="border-t border-warm-300 pt-6 flex items-center justify-between">
                    <a href="{{ localizedRoute('login') }}" class="group flex items-center gap-3 text-warm-500 hover:text-dark-800 transition-colors">
                        <svg class="w-5 h-5 group-hover:-translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18" />
                        </svg>
                        <span class="text-xs uppercase tracking-widest">{{ __('auth.back_to_login') }}</span>
                    </a>
                    <a href="{{ localizedRoute('register') }}" class="group flex items-center gap-3 text-dark-800 hover:text-primary-500 transition-colors">
                        <span class="text-xs uppercase tracking-widest">{{ __('auth.create_account') }}</span>
                        <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Right Column - Info -->
            <div class="lg:col-span-5 mt-12 lg:mt-0">
                <div class="sticky top-24 bg-olive-100 p-8">
                    <h3 class="font-display text-2xl sm:text-3xl font-normal text-dark-800 uppercase tracking-tight mb-8">
                        {{ __('auth.how_it_works') }}
                    </h3>
                    
                    <p class="text-xs uppercase tracking-widest text-olive-600 leading-relaxed mb-8">
                        {{ __('auth.how_it_works_text') }}
                    </p>

                    <div class="pt-6 border-t border-dark-800 space-y-2">
                        <div class="text-xs uppercase tracking-widest text-olive-600">
                            <span class="inline-block w-1 h-1 bg-olive-500 rounded-full mr-1"></span>
                            {{ __('auth.secure') }}
                        </div>
                        <div class="text-xs uppercase tracking-widest text-olive-600">
                            <span class="inline-block w-1 h-1 bg-olive-500 rounded-full mr-1"></span>
                            {{ __('auth.encrypted') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
