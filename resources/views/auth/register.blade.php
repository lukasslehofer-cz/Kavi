@extends('layouts.app')

@section('title', __('auth.register_title') . ' - KAVI')

@section('content')
<div class="min-h-[calc(100vh-20rem)] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-gray-50">
    <div class="max-w-md w-full">
        <div class="bg-white rounded-2xl p-10 border border-gray-200">
            <!-- Header -->
            <div class="text-center mb-8">                
                <h2 class="text-2xl font-bold text-gray-900 mb-2">{{ __('auth.create_account') }}</h2>
                <p class="text-gray-600 font-light">{{ __('auth.register_subtitle') }}</p>
            </div>
            
            <form method="POST" action="{{ localizedRoute('register') }}" class="space-y-6">
                @csrf

                <div>
                    <label for="name" class="block text-sm font-medium text-gray-900 mb-2">{{ __('auth.full_name') }}</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-1 focus:ring-gray-300 focus:border-gray-300 transition-all @error('name') border-red-500 @enderror"
                           placeholder="{{ __('auth.name_placeholder') }}">
                    @error('name')
                    <p class="text-red-600 text-sm mt-2 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        {{ $message }}
                    </p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-900 mb-2">{{ __('auth.email') }}</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-1 focus:ring-gray-300 focus:border-gray-300 transition-all @error('email') border-red-500 @enderror"
                           placeholder="{{ __('auth.email_placeholder') }}">
                    @error('email')
                    <p class="text-red-600 text-sm mt-2 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        {{ $message }}
                    </p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-900 mb-2">{{ __('auth.password') }}</label>
                    <input id="password" type="password" name="password" required
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-1 focus:ring-gray-300 focus:border-gray-300 transition-all @error('password') border-red-500 @enderror"
                           placeholder="{{ __('auth.password_placeholder_min') }}">
                    @error('password')
                    <p class="text-red-600 text-sm mt-2 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        {{ $message }}
                    </p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-900 mb-2">{{ __('auth.confirm_password') }}</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-1 focus:ring-gray-300 focus:border-gray-300 transition-all"
                           placeholder="{{ __('auth.confirm_password_placeholder') }}">
                </div>

                <div class="bg-gray-50 rounded-xl p-4 border border-gray-200">
                    <label class="flex items-start cursor-pointer">
                        <input type="checkbox" name="terms" id="terms" required class="mt-0.5 rounded border-gray-300 text-primary-500 focus:ring-primary-500 flex-shrink-0">
                        <span class="ml-3 text-sm text-gray-700 font-light">
                            {{ __('auth.terms_agree') }} 
                            <a href="{{ localizedRoute('terms-of-service') }}" target="_blank" class="text-primary-600 hover:text-primary-700 font-medium">{{ __('auth.terms_of_service') }}</a>
                            {{ __('auth.and') }}
                            <a href="{{ localizedRoute('privacy-policy') }}" target="_blank" class="text-primary-600 hover:text-primary-700 font-medium">{{ __('auth.privacy_policy') }}</a>
                        </span>
                    </label>
                </div>

                <button type="submit" class="w-full bg-primary-500 hover:bg-primary-600 text-white font-medium px-6 py-3 rounded-full transition-all duration-200 inline-flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                    </svg>
                    {{ __('auth.register_button') }}
                </button>
            </form>

            <div class="mt-8 pt-6 border-t border-gray-200 text-center">
                <p class="text-gray-600 font-light">
                    {{ __('auth.have_account') }}
                    <a href="{{ localizedRoute('login') }}" class="text-primary-600 hover:text-primary-700 font-medium ml-1">
                        {{ __('auth.login_here') }}
                    </a>
                </p>
            </div>
        </div>

        <!-- Benefits -->
        <div class="mt-8">
            <p class="text-center text-gray-900 font-medium mb-4">{{ __('auth.benefits_title') }}</p>
            <div class="grid grid-cols-1 gap-3 text-sm">
                <div class="flex items-center bg-white rounded-xl p-4 border border-gray-200">
                    <svg class="w-5 h-5 text-primary-500 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span class="text-gray-700 font-light">{{ __('auth.benefit_exclusive') }}</span>
                </div>
                <div class="flex items-center bg-white rounded-xl p-4 border border-gray-200">
                    <svg class="w-5 h-5 text-primary-500 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span class="text-gray-700 font-light">{{ __('auth.benefit_subscription') }}</span>
                </div>
                <div class="flex items-center bg-white rounded-xl p-4 border border-gray-200">
                    <svg class="w-5 h-5 text-primary-500 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span class="text-gray-700 font-light">{{ __('auth.benefit_orders') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
