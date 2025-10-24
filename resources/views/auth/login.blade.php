@extends('layouts.app')

@section('title', 'Přihlášení - Kavi Coffee')

@section('content')
<div class="min-h-[calc(100vh-20rem)] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-bluegray-50">
    <div class="max-w-md w-full">
        <div class="card p-10 shadow-xl">
            <!-- Header -->
            <div class="text-center mb-8">
                <div class="w-16 h-16 bg-primary-500 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M2 21h19v-3H2v3zM20 8H4V5h16v3zm0-6H4c-1.1 0-2 .9-2 2v3c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zM12 15c1.66 0 3-1.34 3-3H9c0 1.66 1.34 3 3 3z"/>
                    </svg>
                </div>
                <h2 class="font-display text-3xl font-bold text-dark-800 mb-2">Vítejte zpět</h2>
                <p class="text-dark-600">Přihlaste se ke svému účtu</p>
            </div>
            
            <form method="POST" action="/prihlaseni" class="space-y-6">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-semibold text-dark-800 mb-2">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                           class="input @error('email') border-red-500 @enderror"
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

                <div>
                    <label for="password" class="block text-sm font-semibold text-dark-800 mb-2">Heslo</label>
                    <input id="password" type="password" name="password" required
                           class="input @error('password') border-red-500 @enderror"
                           placeholder="••••••••">
                    @error('password')
                    <p class="text-red-600 text-sm mt-2 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        {{ $message }}
                    </p>
                    @enderror
                </div>

                <div class="flex items-center justify-between">
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" name="remember" class="rounded border-bluegray-300 text-primary-500 focus:ring-primary-500">
                        <span class="ml-2 text-sm text-dark-700">Zapamatovat si mě</span>
                    </label>
                    <a href="#" class="text-sm text-primary-500 hover:text-primary-600 font-semibold">
                        Zapomenuté heslo?
                    </a>
                </div>

                <button type="submit" class="btn btn-primary w-full text-lg">
                    Přihlásit se
                </button>
            </form>

            <div class="mt-8 pt-6 border-t border-bluegray-200 text-center">
                <p class="text-dark-600">
                    Ještě nemáte účet?
                    <a href="{{ route('register') }}" class="text-primary-500 hover:text-primary-600 font-semibold ml-1">
                        Zaregistrujte se zdarma
                    </a>
                </p>
            </div>
        </div>

        <!-- Benefits -->
        <div class="mt-8 text-center">
            <div class="flex justify-center items-center gap-6 text-sm text-dark-600">
                <span class="flex items-center">
                    <svg class="w-4 h-4 text-primary-500 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    Bezpečné
                </span>
                <span class="flex items-center">
                    <svg class="w-4 h-4 text-primary-500 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    Rychlé
                </span>
            </div>
        </div>
    </div>
</div>
@endsection
