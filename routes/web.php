<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SubscriptionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');

// Products
Route::get('/produkty', [ProductController::class, 'index'])->name('products.index');
Route::get('/produkt/{product}', [ProductController::class, 'show'])->name('products.show');

// Subscriptions
Route::get('/predplatne', [SubscriptionController::class, 'index'])->name('subscriptions.index');

// Subscription Configurator (must be before {plan} route to avoid conflicts)
Route::post('/predplatne/konfigurator/checkout', [SubscriptionController::class, 'configureCheckout'])->name('subscriptions.configure.checkout');
Route::get('/predplatne/pokladna', [SubscriptionController::class, 'checkout'])->name('subscriptions.checkout');
Route::post('/predplatne/pokladna', [SubscriptionController::class, 'processCheckout'])->name('subscriptions.checkout.process');

// Subscription plans (keep this route last as it catches everything)
Route::get('/predplatne/{plan}', [SubscriptionController::class, 'show'])->name('subscriptions.show');
Route::post('/predplatne/{plan}/subscribe', [SubscriptionController::class, 'subscribe'])->name('subscriptions.subscribe');

// Cart
Route::get('/kosik', [CartController::class, 'index'])->name('cart.index');
Route::post('/kosik/pridat/{product}', [CartController::class, 'add'])->name('cart.add');
Route::patch('/kosik/aktualizovat/{product}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/kosik/odebrat/{product}', [CartController::class, 'remove'])->name('cart.remove');
Route::delete('/kosik/vyprazdnit', [CartController::class, 'clear'])->name('cart.clear');

// Checkout - requires auth
Route::middleware('auth')->group(function () {
    Route::get('/pokladna', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/pokladna', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/objednavka/{order}/potvrzeni', [CheckoutController::class, 'confirmation'])->name('order.confirmation');
});

// User Dashboard - requires auth
Route::middleware('auth')->prefix('dashboard')->name('dashboard.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('index');
    Route::get('/objednavky', [DashboardController::class, 'orders'])->name('orders');
    Route::get('/objednavka/{order}', [DashboardController::class, 'orderDetail'])->name('order.detail');
    Route::get('/predplatne', [DashboardController::class, 'subscription'])->name('subscription');
});

// Admin Panel - requires auth + admin
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');
    
    // Products
    Route::resource('products', AdminProductController::class)->except(['show']);
    
    // Subscription Config
    Route::get('/konfigurator-nastaveni', [\App\Http\Controllers\Admin\SubscriptionConfigController::class, 'index'])->name('subscription-config.index');
    Route::post('/konfigurator-nastaveni', [\App\Http\Controllers\Admin\SubscriptionConfigController::class, 'update'])->name('subscription-config.update');
    
    // Orders
    Route::resource('orders', \App\Http\Controllers\Admin\OrderController::class)->only(['index', 'show', 'update', 'destroy']);
    
    // Subscriptions - to be implemented
    // Route::resource('subscriptions', AdminSubscriptionController::class);
});

// Auth routes (Laravel Breeze/Jetstream or custom implementation)
require __DIR__.'/auth.php';


