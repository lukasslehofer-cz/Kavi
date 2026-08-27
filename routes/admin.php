<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\RoasteryController as AdminRoasteryController;
use App\Http\Controllers\Admin\TranslationController;
use App\Http\Controllers\FacebookCatalogFeedController;
use App\Http\Controllers\FeedController;
use App\Http\Controllers\GoogleMerchantFeedController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\RobotsTxtController;
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
Route::get('/feed/facebook-catalog.xml', [FacebookCatalogFeedController::class, 'index'])->name('feed.facebook-catalog');

// Sitemaps (technical routes - domain-specific, not localized)
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap.index');
Route::get('/sitemap-static.xml', [SitemapController::class, 'static'])->name('sitemap.static');
Route::get('/sitemap-products.xml', [SitemapController::class, 'products'])->name('sitemap.products');
Route::get('/sitemap-roasteries.xml', [SitemapController::class, 'roasteries'])->name('sitemap.roasteries');
Route::get('/sitemap-blog.xml', [SitemapController::class, 'blog'])->name('sitemap.blog');

// Robots.txt (domain-specific)
Route::get('/robots.txt', [RobotsTxtController::class, 'index'])->name('robots.txt');

// Coupon activation from link (code is universal)
Route::get('/code/{code}', [CouponController::class, 'activateFromLink'])->name('coupon.activate');

// Affiliate link redirect (short URL)
Route::get('/r/{slug}', [\App\Http\Controllers\AffiliateLinkController::class, 'redirect'])->name('affiliate.link.redirect');

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
    Route::post('/products-reorder', [AdminProductController::class, 'reorder'])->name('products.reorder');
    
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
    Route::put('/orders/{order}/update-dimensions', [\App\Http\Controllers\Admin\OrderController::class, 'updatePackageDimensions'])->name('orders.update-dimensions');
    Route::post('/orders/send-to-packeta', [\App\Http\Controllers\Admin\OrderController::class, 'sendToPacketa'])->name('orders.send-to-packeta');
    Route::post('/orders/{order}/send-digital-delivery', [\App\Http\Controllers\Admin\OrderController::class, 'sendDigitalDelivery'])->name('orders.send-digital-delivery');

    // Zákazníci – vlastní fakturační údaje pro Fakturoid
    Route::get('/customers/{user}/billing', [\App\Http\Controllers\Admin\CustomerBillingController::class, 'edit'])->name('customers.billing.edit');
    Route::put('/customers/{user}/billing', [\App\Http\Controllers\Admin\CustomerBillingController::class, 'update'])->name('customers.billing.update');

    // Subscriptions
    Route::resource('subscriptions', \App\Http\Controllers\Admin\SubscriptionController::class)->only(['index', 'show', 'update', 'destroy']);
    Route::put('/subscriptions/{subscription}/update-address', [\App\Http\Controllers\Admin\SubscriptionController::class, 'updateAddress'])->name('subscriptions.update-address');
    Route::post('/subscriptions/{subscription}/pause', [\App\Http\Controllers\Admin\SubscriptionController::class, 'pause'])->name('subscriptions.pause');
    Route::post('/subscriptions/{subscription}/resume', [\App\Http\Controllers\Admin\SubscriptionController::class, 'resume'])->name('subscriptions.resume');
    Route::get('/subscription-payment/{payment}/invoice', [\App\Http\Controllers\Admin\SubscriptionController::class, 'downloadInvoice'])->name('subscription-payment.invoice');
    Route::get('/subscriptions-shipments', [\App\Http\Controllers\Admin\SubscriptionController::class, 'shipments'])->name('subscriptions.shipments');
    Route::get('/subscriptions-shipments/history', [\App\Http\Controllers\Admin\SubscriptionController::class, 'shipmentsHistory'])->name('subscriptions.shipments.history');
    Route::post('/subscriptions-shipments/send-to-packeta', [\App\Http\Controllers\Admin\SubscriptionController::class, 'sendToPacketa'])->name('subscriptions.send-to-packeta');
    Route::post('/subscriptions-shipments/send-preparing-emails', [\App\Http\Controllers\Admin\SubscriptionController::class, 'sendPreparingEmails'])->name('subscriptions.send-preparing-emails');
    Route::post('/subscription-shipments/{shipment}/mark-delivered', [\App\Http\Controllers\Admin\SubscriptionController::class, 'markAsDelivered'])->name('subscription-shipments.mark-delivered');
    Route::put('/subscription-shipments/{shipment}', [\App\Http\Controllers\Admin\SubscriptionController::class, 'updateShipment'])->name('subscription-shipments.update');
    Route::post('/subscriptions-shipments/mark-shipped-manually', [\App\Http\Controllers\Admin\SubscriptionController::class, 'markAsShippedManually'])->name('subscriptions.mark-shipped-manually');
    Route::post('/subscriptions-shipments/recalculate-reservations', [\App\Http\Controllers\Admin\SubscriptionController::class, 'recalculateReservations'])->name('subscriptions.recalculate-reservations');
    
    // Coupons
    Route::resource('coupons', \App\Http\Controllers\Admin\CouponController::class);
    Route::get('/coupons/{coupon}/stats', [\App\Http\Controllers\Admin\CouponController::class, 'stats'])->name('coupons.stats');
    
    // Affiliate
    Route::get('/affiliate/partners', [\App\Http\Controllers\Admin\AffiliatePartnerController::class, 'index'])->name('affiliate.partners.index');
    Route::post('/affiliate/partners/{user}/activate', [\App\Http\Controllers\Admin\AffiliatePartnerController::class, 'activate'])->name('affiliate.partners.activate');
    Route::post('/affiliate/partners/{user}/deactivate', [\App\Http\Controllers\Admin\AffiliatePartnerController::class, 'deactivate'])->name('affiliate.partners.deactivate');
    Route::post('/affiliate/partners/activate-by-email', [\App\Http\Controllers\Admin\AffiliatePartnerController::class, 'activateByEmail'])->name('affiliate.partners.activate-by-email');
    Route::post('/affiliate/partners/{user}/settings', [\App\Http\Controllers\Admin\AffiliatePartnerController::class, 'updateSettings'])->name('affiliate.partners.settings');
    Route::post('/affiliate/partners/{user}/payout-approved', [\App\Http\Controllers\Admin\AffiliatePartnerController::class, 'payoutApproved'])->name('affiliate.partners.payout-approved');
    // Detail až za statickými cestami, ať /affiliate/partners/activate-by-email nespadne do {user}
    Route::get('/affiliate/partners/{user}', [\App\Http\Controllers\Admin\AffiliatePartnerController::class, 'show'])->name('affiliate.partners.show');
    Route::get('/affiliate/mail', [\App\Http\Controllers\Admin\AffiliateMailController::class, 'create'])->name('affiliate.mail.create');
    Route::post('/affiliate/mail/preview', [\App\Http\Controllers\Admin\AffiliateMailController::class, 'preview'])->name('affiliate.mail.preview');
    Route::post('/affiliate/mail/send-test', [\App\Http\Controllers\Admin\AffiliateMailController::class, 'sendTest'])->name('affiliate.mail.send-test');
    Route::post('/affiliate/mail/send', [\App\Http\Controllers\Admin\AffiliateMailController::class, 'send'])->name('affiliate.mail.send');
    Route::get('/affiliate/rewards', [\App\Http\Controllers\Admin\AffiliateRewardController::class, 'index'])->name('affiliate.rewards.index');
    Route::post('/affiliate/rewards/{reward}/approve', [\App\Http\Controllers\Admin\AffiliateRewardController::class, 'approve'])->name('affiliate.rewards.approve');
    Route::post('/affiliate/rewards/{reward}/mark-paid', [\App\Http\Controllers\Admin\AffiliateRewardController::class, 'markPaid'])->name('affiliate.rewards.mark-paid');
    Route::post('/affiliate/rewards/{reward}/cancel', [\App\Http\Controllers\Admin\AffiliateRewardController::class, 'cancel'])->name('affiliate.rewards.cancel');
    
    // Newsletter
    Route::get('/newsletter', [\App\Http\Controllers\Admin\AdminNewsletterController::class, 'index'])->name('newsletter.index');
    Route::delete('/newsletter/{subscriber}', [\App\Http\Controllers\Admin\AdminNewsletterController::class, 'destroy'])->name('newsletter.destroy');
    Route::post('/newsletter/sync-customers', [\App\Http\Controllers\Admin\AdminNewsletterController::class, 'syncCustomers'])->name('newsletter.sync-customers');
    Route::post('/newsletter/sync-ecomail', [\App\Http\Controllers\Admin\AdminNewsletterController::class, 'syncToEcomail'])->name('newsletter.sync-ecomail');
    Route::get('/newsletter/export', [\App\Http\Controllers\Admin\AdminNewsletterController::class, 'export'])->name('newsletter.export');
    
    // Statistiky žádostí o hodnocení
    Route::get('/hodnoceni', [\App\Http\Controllers\Admin\ReviewStatsController::class, 'index'])->name('reviews.index');

    // Email Logs
    Route::get('/email-logs', [\App\Http\Controllers\Admin\EmailLogController::class, 'index'])->name('email-logs.index');
    Route::get('/email-logs/{emailLog}', [\App\Http\Controllers\Admin\EmailLogController::class, 'show'])->name('email-logs.show');
    Route::post('/email-logs/{emailLog}/resend', [\App\Http\Controllers\Admin\EmailLogController::class, 'resend'])->name('email-logs.resend');

    // Mail zákazníkům (vlastní zpráva v Kavi šabloně)
    Route::get('/mail-zakaznikum', [\App\Http\Controllers\Admin\CustomerMailController::class, 'create'])->name('customer-mail.create');
    Route::post('/mail-zakaznikum/nahled', [\App\Http\Controllers\Admin\CustomerMailController::class, 'preview'])->name('customer-mail.preview');
    Route::post('/mail-zakaznikum/test', [\App\Http\Controllers\Admin\CustomerMailController::class, 'sendTest'])->name('customer-mail.send-test');
    Route::post('/mail-zakaznikum/odeslat', [\App\Http\Controllers\Admin\CustomerMailController::class, 'send'])->name('customer-mail.send');

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
    
    // Blog Posts
    Route::resource('posts', \App\Http\Controllers\Admin\PostController::class)->except(['show']);
    
    // Blog Tags
    Route::resource('tags', \App\Http\Controllers\Admin\TagController::class)->except(['show']);
    
    // Translation API (DeepL)
    Route::post('/translate', [TranslationController::class, 'translate'])->name('translate');
    Route::post('/translate/batch', [TranslationController::class, 'translateBatch'])->name('translate.batch');
    Route::get('/translate/usage', [TranslationController::class, 'usage'])->name('translate.usage');
    Route::get('/translate/status', [TranslationController::class, 'status'])->name('translate.status');
    
    // Email Preview (development only)
    Route::get('/email-preview/order-confirmation/{order?}', function ($orderId = null) {
        $order = $orderId 
            ? \App\Models\Order::with('items')->findOrFail($orderId)
            : \App\Models\Order::with('items')->latest()->firstOrFail();
        
        $locale = 'cs';
        $siteName = 'KAVI.cz';
        $contactEmail = 'info@kavi.cz';
        
        return view('emails.order-confirmation', compact('order', 'locale', 'siteName', 'contactEmail'));
    })->name('email-preview.order-confirmation');
});

