<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use Illuminate\Support\Facades\Route;

// Login Routes
Route::get('/prihlaseni', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/prihlaseni', [LoginController::class, 'login']);
Route::post('/odhlaseni', [LoginController::class, 'logout'])->name('logout');

// Registration Routes
Route::get('/registrace', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/registrace', [RegisterController::class, 'register']);

// Password Reset Routes
Route::get('/zapomenute-heslo', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/zapomenute-heslo', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/reset-hesla/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-hesla', [ResetPasswordController::class, 'reset'])->name('password.update');

// Magic Link Routes
Route::post('/magic-link/send', [\App\Http\Controllers\Auth\MagicLinkController::class, 'sendLink'])->name('magic-link.send');
Route::get('/magic-link/verify/{token}', [\App\Http\Controllers\Auth\MagicLinkController::class, 'verify'])->name('magic-link.verify');

