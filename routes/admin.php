<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\RoasteryController as AdminRoasteryController;
use App\Http\Controllers\Admin\TranslationController;
use App\Http\Controllers\FeedController;
use App\Http\Controllers\GoogleMerchantFeedController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\CouponController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Non-Localized Routes
|--------------------------------------------------------------------------
|
| These routes are not localized and remain the same regardless of locale.
| Includes: admin panel, feeds, webhooks, API endpoints, coupon codes.
|
*/

// Feeds (technical routes)
Route::get('/feed/heureka.xml', [FeedController::class, 'heureka'])->name('feed.heureka');
Route::get('/feed/google-merchant.xml', [GoogleMerchantFeedController::class, 'index'])->name('feed.google-merchant');

// Coupon activation from link (code is universal)
Route::get('/code/{code}', [CouponController::class, 'activateFromLink'])->name('coupon.activate');

// API endpoints
Route::post('/api/calculate-shipping', [\App\Http\Controllers\CheckoutController::class, 'calculateShipping'])->name('api.calculate-shipping');

// Stripe webhook - public endpoint (no auth, no CSRF)
Route::post('/webhook/stripe', [PaymentController::class, 'webhook'])->name('stripe.webhook');

// Admin Panel - requires auth + admin (keep in Czech)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');
    
    // Products
    Route::resource('products', AdminProductController::class)->except(['show']);
    Route::get('/products-bulk-discount', [AdminProductController::class, 'bulkDiscount'])->name('products.bulk-discount');
    Route::post('/products-bulk-discount', [AdminProductController::class, 'applyBulkDiscount'])->name('products.apply-bulk-discount');
    Route::post('/products-clear-discounts', [AdminProductController::class, 'clearAllDiscounts'])->name('products.clear-all-discounts');
    
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
    Route::get('/subscriptions-shipments/history', [\App\Http\Controllers\Admin\SubscriptionController::class, 'shipmentsHistory'])->name('subscriptions.shipments.history');
    Route::post('/subscriptions-shipments/send-to-packeta', [\App\Http\Controllers\Admin\SubscriptionController::class, 'sendToPacketa'])->name('subscriptions.send-to-packeta');
    Route::post('/subscriptions-shipments/send-preparing-emails', [\App\Http\Controllers\Admin\SubscriptionController::class, 'sendPreparingEmails'])->name('subscriptions.send-preparing-emails');
    Route::put('/subscription-shipments/{shipment}', [\App\Http\Controllers\Admin\SubscriptionController::class, 'updateShipment'])->name('subscription-shipments.update');
    
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
    
    // Announcement Banners
    Route::get('/hlasky', [\App\Http\Controllers\Admin\AnnouncementBannerController::class, 'index'])->name('announcements.index');
    Route::post('/hlasky', [\App\Http\Controllers\Admin\AnnouncementBannerController::class, 'store'])->name('announcements.store');
    Route::put('/hlasky/{announcement}', [\App\Http\Controllers\Admin\AnnouncementBannerController::class, 'update'])->name('announcements.update');
    Route::delete('/hlasky/{announcement}', [\App\Http\Controllers\Admin\AnnouncementBannerController::class, 'destroy'])->name('announcements.destroy');
    Route::post('/hlasky/{announcement}/toggle', [\App\Http\Controllers\Admin\AnnouncementBannerController::class, 'toggle'])->name('announcements.toggle');
    
    // Translation API (DeepL)
    Route::post('/translate', [TranslationController::class, 'translate'])->name('translate');
    Route::post('/translate/batch', [TranslationController::class, 'translateBatch'])->name('translate.batch');
    Route::get('/translate/usage', [TranslationController::class, 'usage'])->name('translate.usage');
    Route::get('/translate/status', [TranslationController::class, 'status'])->name('translate.status');
});

