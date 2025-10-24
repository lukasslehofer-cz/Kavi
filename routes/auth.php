<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;

// Login Routes
Route::get('/prihlaseni', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/prihlaseni', [LoginController::class, 'login']);
Route::post('/odhlaseni', [LoginController::class, 'logout'])->name('logout');

// Registration Routes
Route::get('/registrace', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/registrace', [RegisterController::class, 'register']);

