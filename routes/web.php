<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\RoasteryController as AdminRoasteryController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\RoasteryController;
use App\Http\Controllers\MonthlyFeatureController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::view('/jak-to-funguje', 'how-it-works')->name('how-it-works');
Route::view('/o-nas', 'about')->name('about');
Route::view('/kontakt', 'contact')->name('contact');
Route::view('/ochrana-osobnich-udaju', 'privacy-policy')->name('privacy-policy');
Route::view('/obchodni-podminky', 'terms-of-service')->name('terms-of-service');

// Coupon activation from link
Route::get('/code/{code}', [CouponController::class, 'activateFromLink'])->name('coupon.activate');

// Coupon validation (AJAX)
Route::post('/kupony/validovat', [CouponController::class, 'validateCoupon'])->name('coupon.validate');

// Newsletter subscription (AJAX)
Route::post('/newsletter/prihlasit', [NewsletterController::class, 'subscribe'])->name('newsletter.subscribe');

// Contact form submission
Route::post('/kontakt/odeslat', [ContactController::class, 'send'])->name('contact.send');

// Review tracking (redirect to Trustpilot)
Route::get('/hodnoceni/{token}', [\App\Http\Controllers\ReviewRequestController::class, 'track'])->name('review.track');

// Products
Route::get('/produkty', [ProductController::class, 'index'])->name('products.index');
Route::get('/produkt/{product}', [ProductController::class, 'show'])->name('products.show');

// Roasteries
Route::get('/prazirny', [RoasteryController::class, 'index'])->name('roasteries.index');
Route::get('/prazirna/{roastery}', [RoasteryController::class, 'show'])->name('roasteries.show');

// Monthly Feature (Roastery & Coffee of the Month)
Route::get('/kava-mesice', [MonthlyFeatureController::class, 'index'])->name('monthly-feature.index');

// Subscriptions
Route::get('/predplatne', [SubscriptionController::class, 'index'])->name('subscriptions.index');

// Subscription Configurator (must be before {plan} route to avoid conflicts)
Route::post('/predplatne/konfigurator/checkout', [SubscriptionController::class, 'configureCheckout'])->name('subscriptions.configure.checkout');
Route::get('/predplatne/pokladna', [SubscriptionController::class, 'checkout'])->name('subscriptions.checkout');
Route::post('/predplatne/pokladna', [SubscriptionController::class, 'processCheckout'])->name('subscriptions.checkout.process');
Route::get('/predplatne/{subscription}/potvrzeni', [SubscriptionController::class, 'confirmation'])->name('subscriptions.confirmation');

// Subscription plans (keep this route last as it catches everything)
Route::get('/predplatne/{plan}', [SubscriptionController::class, 'show'])->name('subscriptions.show');
Route::post('/predplatne/{plan}/registrovat', [SubscriptionController::class, 'subscribe'])->name('subscriptions.subscribe');

// Cart
Route::get('/kosik', [CartController::class, 'index'])->name('cart.index');
Route::post('/kosik/pridat/{product}', [CartController::class, 'add'])->name('cart.add');
Route::patch('/kosik/aktualizovat/{product}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/kosik/odebrat/{product}', [CartController::class, 'remove'])->name('cart.remove');
Route::delete('/kosik/vyprazdnit', [CartController::class, 'clear'])->name('cart.clear');

// Checkout - now supports guest checkout
Route::get('/pokladna', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/pokladna', [CheckoutController::class, 'store'])->name('checkout.store');
Route::get('/objednavka/{order}/potvrzeni', [CheckoutController::class, 'confirmation'])->name('order.confirmation');
Route::post('/api/calculate-shipping', [CheckoutController::class, 'calculateShipping'])->name('api.calculate-shipping');

// Order payment (requires auth)
Route::post('/objednavka/{order}/zaplatit', [CheckoutController::class, 'payOrder'])->name('order.pay')->middleware('auth');

// Payment routes
Route::get('/platba/karta/{order}', [PaymentController::class, 'cardPayment'])->name('payment.card');

// Stripe webhook - public endpoint (no auth, no CSRF)
Route::post('/webhook/stripe', [PaymentController::class, 'webhook'])->name('stripe.webhook');

// User Dashboard - requires auth
Route::middleware('auth')->prefix('dashboard')->name('dashboard.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('index');
    Route::get('/profil', [DashboardController::class, 'profile'])->name('profile');
    Route::put('/profil', [DashboardController::class, 'updateProfile'])->name('profile.update');
    Route::put('/heslo', [DashboardController::class, 'updatePassword'])->name('password.update');
    Route::delete('/profil/smazat-ucet', [DashboardController::class, 'deleteAccount'])->name('profile.delete');
    Route::get('/platebni-metody/spravovat', [DashboardController::class, 'managePaymentMethods'])->name('payment-methods.manage');
    Route::get('/objednavky', [DashboardController::class, 'orders'])->name('orders');
    Route::get('/objednavka/{order}', [DashboardController::class, 'orderDetail'])->name('order.detail');
    Route::get('/objednavka/{order}/faktura', [DashboardController::class, 'downloadInvoice'])->name('order.invoice');
    Route::get('/predplatne', [DashboardController::class, 'subscription'])->name('subscription');
    Route::post('/predplatne/pause', [DashboardController::class, 'pauseSubscription'])->name('subscription.pause');
    Route::post('/predplatne/resume', [DashboardController::class, 'resumeSubscription'])->name('subscription.resume');
    Route::post('/predplatne/cancel', [DashboardController::class, 'cancelSubscription'])->name('subscription.cancel');
    Route::post('/predplatne/{subscription}/zaplatit', [SubscriptionController::class, 'payInvoice'])->name('subscription.pay');
    Route::get('/predplatne/platba/{payment}/faktura', [DashboardController::class, 'downloadSubscriptionInvoice'])->name('subscription.payment.invoice');
    Route::post('/predplatne/update-packeta', [DashboardController::class, 'updatePacketaPoint'])->name('subscription.update-packeta');
    Route::get('/notifikace', [DashboardController::class, 'notifications'])->name('notifications');
});

// Admin Panel - requires auth + admin
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');
    
    // Products
    Route::resource('products', AdminProductController::class)->except(['show']);
    
    // Roasteries
    Route::resource('roasteries', AdminRoasteryController::class)->except(['show']);
    
    // Subscription Config
    Route::get('/konfigurator-nastaveni', [\App\Http\Controllers\Admin\SubscriptionConfigController::class, 'index'])->name('subscription-config.index');
    Route::post('/konfigurator-nastaveni', [\App\Http\Controllers\Admin\SubscriptionConfigController::class, 'update'])->name('subscription-config.update');
    Route::post('/konfigurator-nastaveni/harmonogram', [\App\Http\Controllers\Admin\SubscriptionConfigController::class, 'updateSchedule'])->name('subscription-config.update-schedule');
    Route::put('/konfigurator-nastaveni/harmonogram/{schedule}', [\App\Http\Controllers\Admin\SubscriptionConfigController::class, 'updateSingleSchedule'])->name('subscription-config.update-single-schedule');
    Route::post('/konfigurator-nastaveni/vytvorit-dalsi-rok', [\App\Http\Controllers\Admin\SubscriptionConfigController::class, 'createNextYearSchedules'])->name('subscription-config.create-next-year');
    Route::get('/konfigurator-nastaveni/rok/{year}', [\App\Http\Controllers\Admin\SubscriptionConfigController::class, 'getYearSchedules'])->name('subscription-config.year-schedules');
    Route::delete('/konfigurator-nastaveni/promo-image/{schedule}', [\App\Http\Controllers\Admin\SubscriptionConfigController::class, 'deletePromoImage'])->name('subscription-config.delete-promo-image');
    
    // Orders
    Route::resource('orders', \App\Http\Controllers\Admin\OrderController::class)->only(['index', 'show', 'update', 'destroy']);
    Route::put('/orders/{order}/update-address', [\App\Http\Controllers\Admin\OrderController::class, 'updateAddress'])->name('orders.update-address');
    Route::post('/orders/send-to-packeta', [\App\Http\Controllers\Admin\OrderController::class, 'sendToPacketa'])->name('orders.send-to-packeta');
    
    // Subscriptions
    Route::resource('subscriptions', \App\Http\Controllers\Admin\SubscriptionController::class)->only(['index', 'show', 'update', 'destroy']);
    Route::put('/subscriptions/{subscription}/update-address', [\App\Http\Controllers\Admin\SubscriptionController::class, 'updateAddress'])->name('subscriptions.update-address');
    Route::get('/subscriptions-shipments', [\App\Http\Controllers\Admin\SubscriptionController::class, 'shipments'])->name('subscriptions.shipments');
    Route::post('/subscriptions-shipments/send-to-packeta', [\App\Http\Controllers\Admin\SubscriptionController::class, 'sendToPacketa'])->name('subscriptions.send-to-packeta');
    Route::post('/subscriptions-shipments/send-preparing-emails', [\App\Http\Controllers\Admin\SubscriptionController::class, 'sendPreparingEmails'])->name('subscriptions.send-preparing-emails');
    
    // Coupons
    Route::resource('coupons', \App\Http\Controllers\Admin\CouponController::class);
    Route::get('/coupons/{coupon}/stats', [\App\Http\Controllers\Admin\CouponController::class, 'stats'])->name('coupons.stats');
    
    // Newsletter
    Route::get('/newsletter', [\App\Http\Controllers\Admin\AdminNewsletterController::class, 'index'])->name('newsletter.index');
    Route::delete('/newsletter/{subscriber}', [\App\Http\Controllers\Admin\AdminNewsletterController::class, 'destroy'])->name('newsletter.destroy');
    Route::post('/newsletter/sync-customers', [\App\Http\Controllers\Admin\AdminNewsletterController::class, 'syncCustomers'])->name('newsletter.sync-customers');
    Route::get('/newsletter/export', [\App\Http\Controllers\Admin\AdminNewsletterController::class, 'export'])->name('newsletter.export');
    
    // Shipping Rates
    Route::get('/doprava', [\App\Http\Controllers\Admin\ShippingRateController::class, 'index'])->name('shipping.index');
    Route::get('/doprava/{rate}/upravit', [\App\Http\Controllers\Admin\ShippingRateController::class, 'edit'])->name('shipping.edit');
    Route::put('/doprava/{rate}', [\App\Http\Controllers\Admin\ShippingRateController::class, 'update'])->name('shipping.update');
    Route::post('/doprava/get-carriers', [\App\Http\Controllers\Admin\ShippingRateController::class, 'getCarriers'])->name('shipping.get-carriers');
});

// Auth routes (Laravel Breeze/Jetstream or custom implementation)
require __DIR__.'/auth.php';


