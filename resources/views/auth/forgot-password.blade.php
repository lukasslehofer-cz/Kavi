@extends('layouts.app')

@section('title', 'Zapomenuté heslo - Kavi Coffee')

@section('content')
<div class="min-h-[calc(100vh-20rem)] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-gray-50">
    <div class="max-w-md w-full">
        <div class="bg-white rounded-2xl p-10 border border-gray-200">
            <!-- Header -->
            <div class="text-center mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-2">Zapomenuté heslo</h2>
                <p class="text-gray-600 font-light">Zadejte váš email a my vám pošleme odkaz pro reset hesla</p>
            </div>

            <!-- Success Message -->
            @if (session('status'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-green-600 mt-0.5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <div class="flex-1">
                        <p class="text-sm text-green-800 font-medium">{{ session('status') }}</p>
                        <p class="text-xs text-green-700 mt-1">Nezapomeňte zkontrolovat také složku spam.</p>
                    </div>
                </div>
            </div>
            @endif
            
            <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-900 mb-2">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-1 focus:ring-gray-300 focus:border-gray-300 transition-all @error('email') border-red-500 @enderror"
                           placeholder="vas@email.cz">
                    @error('email')
                    <p class="text-red-600 text-sm mt-2 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        {{ $message }}
                    </p>
                    @enderror
                </div>

                <!-- Info box -->
                <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-blue-600 mt-0.5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                        <div class="flex-1">
                            <p class="text-sm text-blue-900 font-medium">Jak to funguje?</p>
                            <p class="text-xs text-blue-800 mt-1 font-light">
                                Po zadání emailu vám pošleme odkaz, který je platný 60 minut. Kliknutím na odkaz si budete moci nastavit nové heslo.
                            </p>
                        </div>
                    </div>
                </div>

                <button type="submit" class="w-full bg-primary-500 hover:bg-primary-600 text-white font-medium px-6 py-3 rounded-full transition-all duration-200 inline-flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    Odeslat odkaz pro reset hesla
                </button>
            </form>

            <div class="mt-8 pt-6 border-t border-gray-200">
                <div class="flex items-center justify-center gap-6 text-sm">
                    <a href="{{ route('login') }}" class="text-gray-600 hover:text-gray-900 font-light inline-flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                        Zpět na přihlášení
                    </a>
                    <span class="text-gray-300">|</span>
                    <a href="{{ route('register') }}" class="text-primary-600 hover:text-primary-700 font-medium">
                        Vytvořit účet
                    </a>
                </div>
            </div>
        </div>

        <!-- Security info -->
        <div class="mt-8 text-center">
            <div class="flex justify-center items-center gap-6 text-sm text-gray-600 font-light">
                <span class="flex items-center">
                    <svg class="w-4 h-4 text-primary-500 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    Bezpečné
                </span>
                <span class="flex items-center">
                    <svg class="w-4 h-4 text-primary-500 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                    </svg>
                    Šifrované
                </span>
            </div>
        </div>
    </div>
</div>
@endsection

