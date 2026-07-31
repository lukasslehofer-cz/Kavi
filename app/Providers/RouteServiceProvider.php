<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/dashboard';

    /**
     * Primary locale - routes without suffix for backward compatibility.
     */
    protected string $primaryLocale = 'cs';

    /**
     * Supported locales for route localization.
     */
    protected array $supportedLocales = ['cs', 'en'];

    /**
     * Track registered route paths to avoid duplicates when locales have same URLs.
     */
    protected array $registeredPaths = [];

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            // Reset registered paths for each boot
            $this->registeredPaths = [];

            // Register localized routes for each supported locale
            foreach ($this->supportedLocales as $locale) {
                $this->registerLocalizedRoutes($locale);
            }
            
            // Register non-localized routes (admin, feeds, webhooks, etc.)
            Route::middleware('web')
                ->group(base_path('routes/admin.php'));
        });
    }

    /**
     * Register localized routes for a specific locale.
     * 
     * Primary locale (cs) gets routes without suffix for backward compatibility.
     * Other locales get routes with locale suffix (e.g., login.en).
     */
    protected function registerLocalizedRoutes(string $locale): void
    {
        $routes = $this->getRouteTranslations($locale);
        $isPrimary = $locale === $this->primaryLocale;

        Route::middleware('web')->group(function () use ($locale, $routes, $isPrimary) {
            // Home - only register once (for primary locale)
            if ($isPrimary) {
                Route::get('/', [\App\Http\Controllers\HomeController::class, 'index'])->name('home');
            }

            // Static pages
            Route::view('/' . $routes['how-it-works'], 'how-it-works')->name($this->routeName('how-it-works', $locale, $isPrimary));
            Route::view('/' . $routes['about'], 'about')->name($this->routeName('about', $locale, $isPrimary));
            Route::view('/' . $routes['contact'], 'contact')->name($this->routeName('contact', $locale, $isPrimary));
            Route::get('/' . $routes['privacy-policy'], function () use ($locale) {
                return view($locale === 'en' ? 'privacy-policy-en' : 'privacy-policy');
            })->name($this->routeName('privacy-policy', $locale, $isPrimary));
            Route::get('/' . $routes['terms-of-service'], function () use ($locale) {
                return view($locale === 'en' ? 'terms-of-service-en' : 'terms-of-service');
            })->name($this->routeName('terms-of-service', $locale, $isPrimary));

            // Contact form
            Route::post('/' . $routes['contact-send'], [\App\Http\Controllers\ContactController::class, 'send'])->name($this->routeName('contact.send', $locale, $isPrimary));

            // Products
            Route::get('/' . $routes['products'], [\App\Http\Controllers\ProductController::class, 'index'])->name($this->routeName('products.index', $locale, $isPrimary));
            Route::get('/' . $routes['product'] . '/{product}', [\App\Http\Controllers\ProductController::class, 'show'])->name($this->routeName('products.show', $locale, $isPrimary));

            // Roasteries
            Route::get('/' . $routes['roasteries'], [\App\Http\Controllers\RoasteryController::class, 'index'])->name($this->routeName('roasteries.index', $locale, $isPrimary));
            Route::get('/' . $routes['roastery'] . '/{roastery}', [\App\Http\Controllers\RoasteryController::class, 'show'])->name($this->routeName('roasteries.show', $locale, $isPrimary));

            // Monthly Feature
            Route::get('/' . $routes['monthly-feature'], [\App\Http\Controllers\MonthlyFeatureController::class, 'index'])->name($this->routeName('monthly-feature.index', $locale, $isPrimary));

            // Blog (uses same URL in all locales, so register only once with primary name)
            $this->registerUniqueRoute('get', '/' . $routes['blog'], [\App\Http\Controllers\BlogController::class, 'index'], $this->routeName('blog.index', $locale, $isPrimary));
            $this->registerUniqueRoute('get', '/' . $this->replaceParams($routes['blog-show']), [\App\Http\Controllers\BlogController::class, 'show'], $this->routeName('blog.show', $locale, $isPrimary));

            // Subscriptions
            Route::get('/' . $routes['subscription'], [\App\Http\Controllers\SubscriptionController::class, 'index'])->name($this->routeName('subscriptions.index', $locale, $isPrimary));
            Route::post('/' . $this->replaceParams($routes['subscription-configurator-checkout']), [\App\Http\Controllers\SubscriptionController::class, 'configureCheckout'])->name($this->routeName('subscriptions.configure.checkout', $locale, $isPrimary));
            Route::get('/' . $this->replaceParams($routes['subscription-checkout']), [\App\Http\Controllers\SubscriptionController::class, 'checkout'])->name($this->routeName('subscriptions.checkout', $locale, $isPrimary));
            Route::post('/' . $this->replaceParams($routes['subscription-checkout']), [\App\Http\Controllers\SubscriptionController::class, 'processCheckout'])->name($this->routeName('subscriptions.checkout.process', $locale, $isPrimary));
            Route::get('/' . $this->replaceParams($routes['subscription-confirmation']), [\App\Http\Controllers\SubscriptionController::class, 'confirmation'])->name($this->routeName('subscriptions.confirmation', $locale, $isPrimary));
            Route::get('/' . $this->replaceParams($routes['subscription-plan']), [\App\Http\Controllers\SubscriptionController::class, 'show'])->name($this->routeName('subscriptions.show', $locale, $isPrimary));
            Route::post('/' . $this->replaceParams($routes['subscription-subscribe']), [\App\Http\Controllers\SubscriptionController::class, 'subscribe'])->name($this->routeName('subscriptions.subscribe', $locale, $isPrimary));

            // Cart
            Route::get('/' . $routes['cart'], [\App\Http\Controllers\CartController::class, 'index'])->name($this->routeName('cart.index', $locale, $isPrimary));
            Route::post('/' . $this->replaceParams($routes['cart-add']), [\App\Http\Controllers\CartController::class, 'add'])->name($this->routeName('cart.add', $locale, $isPrimary));
            Route::patch('/' . $this->replaceParams($routes['cart-update']), [\App\Http\Controllers\CartController::class, 'update'])->name($this->routeName('cart.update', $locale, $isPrimary));
            Route::delete('/' . $this->replaceParams($routes['cart-remove']), [\App\Http\Controllers\CartController::class, 'remove'])->name($this->routeName('cart.remove', $locale, $isPrimary));
            Route::delete('/' . $routes['cart-clear'], [\App\Http\Controllers\CartController::class, 'clear'])->name($this->routeName('cart.clear', $locale, $isPrimary));

            // Checkout
            Route::get('/' . $routes['checkout'], [\App\Http\Controllers\CheckoutController::class, 'index'])->name($this->routeName('checkout.index', $locale, $isPrimary));
            Route::post('/' . $routes['checkout'], [\App\Http\Controllers\CheckoutController::class, 'store'])->name($this->routeName('checkout.store', $locale, $isPrimary));
            Route::get('/' . $this->replaceParams($routes['order-confirmation']), [\App\Http\Controllers\CheckoutController::class, 'confirmation'])->name($this->routeName('order.confirmation', $locale, $isPrimary));
            Route::post('/' . $this->replaceParams($routes['order-pay']), [\App\Http\Controllers\CheckoutController::class, 'payOrder'])->name($this->routeName('order.pay', $locale, $isPrimary))->middleware('auth');

            // Payment
            Route::get('/' . $this->replaceParams($routes['payment-card']), [\App\Http\Controllers\PaymentController::class, 'cardPayment'])->name($this->routeName('payment.card', $locale, $isPrimary));

            // Coupons
            Route::post('/' . $routes['coupon-validate'], [\App\Http\Controllers\CouponController::class, 'validateCoupon'])->name($this->routeName('coupon.validate', $locale, $isPrimary));
            Route::post('/' . $routes['coupon-remove'], [\App\Http\Controllers\CouponController::class, 'removeCoupon'])->name($this->routeName('coupon.remove', $locale, $isPrimary));

            // Newsletter
            Route::post('/' . $routes['newsletter-subscribe'], [\App\Http\Controllers\NewsletterController::class, 'subscribe'])->name($this->routeName('newsletter.subscribe', $locale, $isPrimary));

            // Review tracking. Varianta s hvězdičkou musí být před tou bez ní.
            Route::get('/' . $this->replaceParams($routes['review-track-rating']), [\App\Http\Controllers\ReviewRequestController::class, 'track'])->whereNumber('rating')->name($this->routeName('review.track.rating', $locale, $isPrimary));
            Route::get('/' . $this->replaceParams($routes['review-track']), [\App\Http\Controllers\ReviewRequestController::class, 'track'])->name($this->routeName('review.track', $locale, $isPrimary));

            // Auth routes
            Route::get('/' . $routes['login'], [\App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name($this->routeName('login', $locale, $isPrimary));
            Route::post('/' . $routes['login'], [\App\Http\Controllers\Auth\LoginController::class, 'login'])->middleware('throttle:10,1');
            Route::post('/' . $routes['logout'], [\App\Http\Controllers\Auth\LoginController::class, 'logout'])->name($this->routeName('logout', $locale, $isPrimary));
            Route::get('/' . $routes['register'], [\App\Http\Controllers\Auth\RegisterController::class, 'showRegistrationForm'])->name($this->routeName('register', $locale, $isPrimary));
            Route::post('/' . $routes['register'], [\App\Http\Controllers\Auth\RegisterController::class, 'register'])->middleware('throttle:5,1');
            Route::get('/' . $routes['password-request'], [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'showLinkRequestForm'])->name($this->routeName('password.request', $locale, $isPrimary));
            Route::post('/' . $routes['password-request'], [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'sendResetLinkEmail'])->name($this->routeName('password.email', $locale, $isPrimary))->middleware('throttle:5,1');
            Route::get('/' . $this->replaceParams($routes['password-reset']), [\App\Http\Controllers\Auth\ResetPasswordController::class, 'showResetForm'])->name($this->routeName('password.reset', $locale, $isPrimary));
            Route::post('/' . $routes['password-reset-post'], [\App\Http\Controllers\Auth\ResetPasswordController::class, 'reset'])->name($this->routeName('password.update', $locale, $isPrimary))->middleware('throttle:5,1');

            // Magic Link
            Route::post('/' . $routes['magic-link-send'], [\App\Http\Controllers\Auth\MagicLinkController::class, 'sendLink'])->name($this->routeName('magic-link.send', $locale, $isPrimary))->middleware('throttle:5,1');
            Route::get('/' . $this->replaceParams($routes['magic-link-verify']), [\App\Http\Controllers\Auth\MagicLinkController::class, 'verify'])->name($this->routeName('magic-link.verify', $locale, $isPrimary));

            // Dashboard routes
            Route::middleware('auth')->prefix($routes['dashboard'])->name('dashboard.')->group(function () use ($locale, $routes, $isPrimary) {
                // Dashboard index
                Route::get('/', [\App\Http\Controllers\DashboardController::class, 'index'])->name($this->routeName('index', $locale, $isPrimary, false));
                Route::get('/' . $routes['dashboard-profile'], [\App\Http\Controllers\DashboardController::class, 'profile'])->name($this->routeName('profile', $locale, $isPrimary, false));
                Route::put('/' . $routes['dashboard-profile'], [\App\Http\Controllers\DashboardController::class, 'updateProfile'])->name($this->routeName('profile.update', $locale, $isPrimary, false));
                Route::put('/' . $routes['dashboard-password'], [\App\Http\Controllers\DashboardController::class, 'updatePassword'])->name($this->routeName('password.update', $locale, $isPrimary, false));
                Route::delete('/' . $routes['dashboard-profile-delete'], [\App\Http\Controllers\DashboardController::class, 'deleteAccount'])->name($this->routeName('profile.delete', $locale, $isPrimary, false));
                Route::get('/' . $routes['dashboard-payment-methods'], [\App\Http\Controllers\DashboardController::class, 'managePaymentMethods'])->name($this->routeName('payment-methods.manage', $locale, $isPrimary, false));
                Route::get('/' . $routes['dashboard-orders'], [\App\Http\Controllers\DashboardController::class, 'orders'])->name($this->routeName('orders', $locale, $isPrimary, false));
                Route::get('/' . $this->replaceParams($routes['dashboard-order-detail']), [\App\Http\Controllers\DashboardController::class, 'orderDetail'])->name($this->routeName('order.detail', $locale, $isPrimary, false));
                Route::get('/' . $this->replaceParams($routes['dashboard-order-invoice']), [\App\Http\Controllers\DashboardController::class, 'downloadInvoice'])->name($this->routeName('order.invoice', $locale, $isPrimary, false));
                Route::get('/' . $routes['dashboard-subscription'], [\App\Http\Controllers\DashboardController::class, 'subscription'])->name($this->routeName('subscription', $locale, $isPrimary, false));
                Route::post('/' . $routes['dashboard-subscription-pause'], [\App\Http\Controllers\DashboardController::class, 'pauseSubscription'])->name($this->routeName('subscription.pause', $locale, $isPrimary, false));
                Route::post('/' . $routes['dashboard-subscription-resume'], [\App\Http\Controllers\DashboardController::class, 'resumeSubscription'])->name($this->routeName('subscription.resume', $locale, $isPrimary, false));
                Route::post('/' . $routes['dashboard-subscription-cancel'], [\App\Http\Controllers\DashboardController::class, 'cancelSubscription'])->name($this->routeName('subscription.cancel', $locale, $isPrimary, false));
                Route::post('/' . $this->replaceParams($routes['dashboard-subscription-pay']), [\App\Http\Controllers\SubscriptionController::class, 'payInvoice'])->name($this->routeName('subscription.pay', $locale, $isPrimary, false));
                Route::get('/' . $this->replaceParams($routes['dashboard-subscription-invoice']), [\App\Http\Controllers\DashboardController::class, 'downloadSubscriptionInvoice'])->name($this->routeName('subscription.payment.invoice', $locale, $isPrimary, false));
                Route::post('/' . $routes['dashboard-subscription-packeta'], [\App\Http\Controllers\DashboardController::class, 'updatePacketaPoint'])->name($this->routeName('subscription.update-packeta', $locale, $isPrimary, false));
                Route::get('/' . $routes['dashboard-notifications'], [\App\Http\Controllers\DashboardController::class, 'notifications'])->name($this->routeName('notifications', $locale, $isPrimary, false));
                
                // Affiliate dashboard
                Route::get('/' . $routes['dashboard-affiliate'], [\App\Http\Controllers\Dashboard\AffiliateController::class, 'index'])->name($this->routeName('affiliate', $locale, $isPrimary, false));
            });
        });
    }

    /**
     * Generate route name with optional locale suffix.
     */
    protected function routeName(string $baseName, string $locale, bool $isPrimary, bool $addLocale = true): string
    {
        // Primary locale routes don't get a suffix (for backward compatibility)
        if ($isPrimary) {
            return $baseName;
        }
        
        // Non-primary locales always get a suffix
        return $addLocale ? "{$baseName}.{$locale}" : $baseName . ".{$locale}";
    }

    /**
     * Replace route parameter placeholders to match Laravel syntax.
     * Converts {param} to {param} (keeps the same format, just for readability).
     */
    protected function replaceParams(string $path): string
    {
        // The translation files already use {param} syntax, so we just return as-is
        return $path;
    }

    /**
     * Get route translations for a specific locale.
     */
    protected function getRouteTranslations(string $locale): array
    {
        $path = lang_path($locale . '/routes.php');

        if (file_exists($path)) {
            return require $path;
        }

        // Fallback to Czech if translation file doesn't exist
        return require lang_path('cs/routes.php');
    }

    /**
     * Register a route only if the path hasn't been registered yet.
     * Used for routes where multiple locales have identical paths (e.g., "blog").
     * Returns true if registered, false if skipped.
     */
    protected function registerUniqueRoute(string $method, string $path, array $action, string $name): bool
    {
        $key = strtoupper($method) . ':' . $path;
        
        if (isset($this->registeredPaths[$key])) {
            return false;
        }
        
        $this->registeredPaths[$key] = $name;
        Route::match([$method], $path, $action)->name($name);
        return true;
    }
}
