<?php

namespace App\Providers;

use App\Helpers\Site;
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
    protected string $primaryLocale = Site::PRIMARY_LOCALE;

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

        // Ověřování dárkových kódů a kuponů - ochrana proti hádání kódů hrubou silou
        RateLimiter::for('gift-verify', function (Request $request) {
            return [
                Limit::perMinute(6)->by('gift-verify-min:' . $request->ip()),
                Limit::perHour(30)->by('gift-verify-hour:' . $request->ip()),
            ];
        });
        RateLimiter::for('coupon-validate', function (Request $request) {
            return [
                Limit::perMinute(20)->by('coupon-validate-min:' . $request->ip()),
                Limit::perHour(120)->by('coupon-validate-hour:' . $request->ip()),
            ];
        });
        // Poptávka firemních dárků
        RateLimiter::for('corporate-inquiry', function (Request $request) {
            return Limit::perMinutes(10, 5)->by('corporate-inquiry:' . $request->ip());
        });

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            // Reset registered paths for each boot
            $this->registeredPaths = [];

            // Register localized routes for each site locale (config/sites.php), primary first
            foreach (Site::locales() as $locale) {
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
                $this->add('get', '/', [\App\Http\Controllers\HomeController::class, 'index'])->name('home');
            }

            // Static pages
            $this->add('view', '/' . $routes['how-it-works'], 'how-it-works')->name($this->routeName('how-it-works', $locale, $isPrimary));
            $this->add('view', '/' . $routes['about'], 'about')->name($this->routeName('about', $locale, $isPrimary));
            $this->add('view', '/' . $routes['contact'], 'contact')->name($this->routeName('contact', $locale, $isPrimary));
            $this->add('get', '/' . $routes['privacy-policy'], fn () => view(self::localizedView('privacy-policy')))->name($this->routeName('privacy-policy', $locale, $isPrimary));
            $this->add('get', '/' . $routes['terms-of-service'], fn () => view(self::localizedView('terms-of-service')))->name($this->routeName('terms-of-service', $locale, $isPrimary));

            // Contact form
            $this->add('post', '/' . $routes['contact-send'], [\App\Http\Controllers\ContactController::class, 'send'])->name($this->routeName('contact.send', $locale, $isPrimary));

            // Products
            $this->add('get', '/' . $routes['products'], [\App\Http\Controllers\ProductController::class, 'index'])->name($this->routeName('products.index', $locale, $isPrimary));
            $this->add('get', '/' . $routes['product'] . '/{product}', [\App\Http\Controllers\ProductController::class, 'show'])->name($this->routeName('products.show', $locale, $isPrimary));

            // Roasteries
            $this->add('get', '/' . $routes['roasteries'], [\App\Http\Controllers\RoasteryController::class, 'index'])->name($this->routeName('roasteries.index', $locale, $isPrimary));
            $this->add('get', '/' . $routes['roastery'] . '/{roastery}', [\App\Http\Controllers\RoasteryController::class, 'show'])->name($this->routeName('roasteries.show', $locale, $isPrimary));

            // Monthly Feature
            $this->add('get', '/' . $routes['monthly-feature'], [\App\Http\Controllers\MonthlyFeatureController::class, 'index'])->name($this->routeName('monthly-feature.index', $locale, $isPrimary));

            // Landing page z reklamy (noindex, mimo sitemapu)
            $this->add('get', '/' . $routes['landing-europe'], [\App\Http\Controllers\LandingController::class, 'europe'])->name($this->routeName('landing.europe', $locale, $isPrimary));

            // Dárky: nákup (dárkový košík -> standardní pokladna) a uplatnění kódu
            $this->add('get', '/' . $routes['gifts'], [\App\Http\Controllers\GiftController::class, 'index'])->name($this->routeName('gifts.index', $locale, $isPrimary));
            $this->add('post', '/' . $routes['gifts-add'], [\App\Http\Controllers\GiftController::class, 'addToCart'])->name($this->routeName('gifts.cart.add', $locale, $isPrimary));
            $this->add('delete', '/' . $this->replaceParams($routes['gifts-remove']), [\App\Http\Controllers\GiftController::class, 'removeFromCart'])->name($this->routeName('gifts.cart.remove', $locale, $isPrimary));
            $this->add('get', '/' . $this->replaceParams($routes['gift-certificate']), [\App\Http\Controllers\GiftController::class, 'certificate'])->name($this->routeName('gifts.certificate', $locale, $isPrimary));
            $this->add('get', '/' . $routes['gift-redeem'], [\App\Http\Controllers\GiftRedeemController::class, 'show'])->name($this->routeName('gifts.redeem', $locale, $isPrimary));
            $this->add('post', '/' . $routes['gift-redeem-verify'], [\App\Http\Controllers\GiftRedeemController::class, 'verify'])->middleware('throttle:gift-verify')->name($this->routeName('gifts.redeem.verify', $locale, $isPrimary));
            $this->add('post', '/' . $routes['gift-redeem-prepare'], [\App\Http\Controllers\GiftRedeemController::class, 'prepare'])->name($this->routeName('gifts.redeem.prepare', $locale, $isPrimary));

            // Firemní dárky: stránka je jen česky. Na kavibox.com vrací controller 404 (CorporateHelper::enabled).
            if ($isPrimary) {
                $this->add('get', '/' . $routes['corporate'], [\App\Http\Controllers\CorporateGiftController::class, 'index'])->name('corporate.index');
                $this->add('post', '/' . $routes['corporate-send'], [\App\Http\Controllers\CorporateGiftController::class, 'send'])->middleware('throttle:corporate-inquiry')->name('corporate.send');
            }

            // Blog (uses same URL in all locales, so it is registered only once with primary name)
            $this->add('get', '/' . $routes['blog'], [\App\Http\Controllers\BlogController::class, 'index'])->name($this->routeName('blog.index', $locale, $isPrimary));
            $this->add('get', '/' . $this->replaceParams($routes['blog-show']), [\App\Http\Controllers\BlogController::class, 'show'])->name($this->routeName('blog.show', $locale, $isPrimary));

            // Subscriptions
            $this->add('get', '/' . $routes['subscription'], [\App\Http\Controllers\SubscriptionController::class, 'index'])->name($this->routeName('subscriptions.index', $locale, $isPrimary));
            $this->add('post', '/' . $this->replaceParams($routes['subscription-configurator-checkout']), [\App\Http\Controllers\SubscriptionController::class, 'configureCheckout'])->name($this->routeName('subscriptions.configure.checkout', $locale, $isPrimary));
            $this->add('get', '/' . $this->replaceParams($routes['subscription-checkout']), [\App\Http\Controllers\SubscriptionController::class, 'checkout'])->name($this->routeName('subscriptions.checkout', $locale, $isPrimary));
            $this->add('post', '/' . $this->replaceParams($routes['subscription-checkout']), [\App\Http\Controllers\SubscriptionController::class, 'processCheckout'])->name($this->routeName('subscriptions.checkout.process', $locale, $isPrimary));
            $this->add('get', '/' . $this->replaceParams($routes['subscription-confirmation']), [\App\Http\Controllers\SubscriptionController::class, 'confirmation'])->name($this->routeName('subscriptions.confirmation', $locale, $isPrimary));
            // Doplacení rozesílky vynechané kvůli neplacení - podepsaný odkaz z e-mailu, bez přihlášení
            $this->add('get', '/' . $routes['subscription-pay-skipped-done'], [\App\Http\Controllers\SubscriptionController::class, 'paySkippedShipmentDone'])->name($this->routeName('subscriptions.pay-skipped.done', $locale, $isPrimary));
            $this->add('get', '/' . $this->replaceParams($routes['subscription-pay-skipped']), [\App\Http\Controllers\SubscriptionController::class, 'paySkippedShipment'])->whereNumber('shipment')->name($this->routeName('subscriptions.pay-skipped', $locale, $isPrimary));
            $this->add('get', '/' . $this->replaceParams($routes['subscription-plan']), [\App\Http\Controllers\SubscriptionController::class, 'show'])->name($this->routeName('subscriptions.show', $locale, $isPrimary));
            $this->add('post', '/' . $this->replaceParams($routes['subscription-subscribe']), [\App\Http\Controllers\SubscriptionController::class, 'subscribe'])->name($this->routeName('subscriptions.subscribe', $locale, $isPrimary));

            // Cart
            $this->add('get', '/' . $routes['cart'], [\App\Http\Controllers\CartController::class, 'index'])->name($this->routeName('cart.index', $locale, $isPrimary));
            $this->add('post', '/' . $this->replaceParams($routes['cart-add']), [\App\Http\Controllers\CartController::class, 'add'])->name($this->routeName('cart.add', $locale, $isPrimary));
            $this->add('patch', '/' . $this->replaceParams($routes['cart-update']), [\App\Http\Controllers\CartController::class, 'update'])->name($this->routeName('cart.update', $locale, $isPrimary));
            $this->add('delete', '/' . $this->replaceParams($routes['cart-remove']), [\App\Http\Controllers\CartController::class, 'remove'])->name($this->routeName('cart.remove', $locale, $isPrimary));
            $this->add('delete', '/' . $routes['cart-clear'], [\App\Http\Controllers\CartController::class, 'clear'])->name($this->routeName('cart.clear', $locale, $isPrimary));

            // Checkout
            $this->add('get', '/' . $routes['checkout'], [\App\Http\Controllers\CheckoutController::class, 'index'])->name($this->routeName('checkout.index', $locale, $isPrimary));
            $this->add('post', '/' . $routes['checkout'], [\App\Http\Controllers\CheckoutController::class, 'store'])->name($this->routeName('checkout.store', $locale, $isPrimary));
            $this->add('get', '/' . $this->replaceParams($routes['order-confirmation']), [\App\Http\Controllers\CheckoutController::class, 'confirmation'])->name($this->routeName('order.confirmation', $locale, $isPrimary));
            $this->add('post', '/' . $this->replaceParams($routes['order-pay']), [\App\Http\Controllers\CheckoutController::class, 'payOrder'])->name($this->routeName('order.pay', $locale, $isPrimary))->middleware('auth');

            // Payment
            $this->add('get', '/' . $this->replaceParams($routes['payment-card']), [\App\Http\Controllers\PaymentController::class, 'cardPayment'])->name($this->routeName('payment.card', $locale, $isPrimary));

            // Coupons
            $this->add('post', '/' . $routes['coupon-validate'], [\App\Http\Controllers\CouponController::class, 'validateCoupon'])->middleware('throttle:coupon-validate')->name($this->routeName('coupon.validate', $locale, $isPrimary));
            $this->add('post', '/' . $routes['coupon-remove'], [\App\Http\Controllers\CouponController::class, 'removeCoupon'])->name($this->routeName('coupon.remove', $locale, $isPrimary));

            // Newsletter
            $this->add('post', '/' . $routes['newsletter-subscribe'], [\App\Http\Controllers\NewsletterController::class, 'subscribe'])->name($this->routeName('newsletter.subscribe', $locale, $isPrimary));

            // Review tracking. Varianta s hvězdičkou musí být před tou bez ní.
            $this->add('get', '/' . $this->replaceParams($routes['review-track-rating']), [\App\Http\Controllers\ReviewRequestController::class, 'track'])->whereNumber('rating')->name($this->routeName('review.track.rating', $locale, $isPrimary));
            $this->add('get', '/' . $this->replaceParams($routes['review-track']), [\App\Http\Controllers\ReviewRequestController::class, 'track'])->name($this->routeName('review.track', $locale, $isPrimary));

            // Auth routes
            $this->add('get', '/' . $routes['login'], [\App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name($this->routeName('login', $locale, $isPrimary));
            $this->add('post', '/' . $routes['login'], [\App\Http\Controllers\Auth\LoginController::class, 'login'])->middleware('throttle:10,1');
            $this->add('post', '/' . $routes['logout'], [\App\Http\Controllers\Auth\LoginController::class, 'logout'])->name($this->routeName('logout', $locale, $isPrimary));
            $this->add('get', '/' . $routes['register'], [\App\Http\Controllers\Auth\RegisterController::class, 'showRegistrationForm'])->name($this->routeName('register', $locale, $isPrimary));
            $this->add('post', '/' . $routes['register'], [\App\Http\Controllers\Auth\RegisterController::class, 'register'])->middleware('throttle:5,1');
            $this->add('get', '/' . $routes['password-request'], [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'showLinkRequestForm'])->name($this->routeName('password.request', $locale, $isPrimary));
            $this->add('post', '/' . $routes['password-request'], [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'sendResetLinkEmail'])->name($this->routeName('password.email', $locale, $isPrimary))->middleware('throttle:5,1');
            $this->add('get', '/' . $this->replaceParams($routes['password-reset']), [\App\Http\Controllers\Auth\ResetPasswordController::class, 'showResetForm'])->name($this->routeName('password.reset', $locale, $isPrimary));
            $this->add('post', '/' . $routes['password-reset-post'], [\App\Http\Controllers\Auth\ResetPasswordController::class, 'reset'])->name($this->routeName('password.update', $locale, $isPrimary))->middleware('throttle:5,1');

            // Magic Link
            $this->add('post', '/' . $routes['magic-link-send'], [\App\Http\Controllers\Auth\MagicLinkController::class, 'sendLink'])->name($this->routeName('magic-link.send', $locale, $isPrimary))->middleware('throttle:5,1');
            $this->add('get', '/' . $this->replaceParams($routes['magic-link-verify']), [\App\Http\Controllers\Auth\MagicLinkController::class, 'verify'])->name($this->routeName('magic-link.verify', $locale, $isPrimary));

            // Dashboard routes
            Route::middleware('auth')->prefix($routes['dashboard'])->name('dashboard.')->group(function () use ($locale, $routes, $isPrimary) {
                // Dashboard index
                $this->add('get', '/', [\App\Http\Controllers\DashboardController::class, 'index'])->name($this->routeName('index', $locale, $isPrimary, false));
                $this->add('get', '/' . $routes['dashboard-profile'], [\App\Http\Controllers\DashboardController::class, 'profile'])->name($this->routeName('profile', $locale, $isPrimary, false));
                $this->add('put', '/' . $routes['dashboard-profile'], [\App\Http\Controllers\DashboardController::class, 'updateProfile'])->name($this->routeName('profile.update', $locale, $isPrimary, false));
                $this->add('put', '/' . $routes['dashboard-profile-company'], [\App\Http\Controllers\DashboardController::class, 'updateInvoiceDetails'])->name($this->routeName('profile.company.update', $locale, $isPrimary, false));
                $this->add('put', '/' . $routes['dashboard-password'], [\App\Http\Controllers\DashboardController::class, 'updatePassword'])->name($this->routeName('password.update', $locale, $isPrimary, false));
                $this->add('delete', '/' . $routes['dashboard-profile-delete'], [\App\Http\Controllers\DashboardController::class, 'deleteAccount'])->name($this->routeName('profile.delete', $locale, $isPrimary, false));
                $this->add('get', '/' . $routes['dashboard-payment-methods'], [\App\Http\Controllers\DashboardController::class, 'managePaymentMethods'])->name($this->routeName('payment-methods.manage', $locale, $isPrimary, false));
                $this->add('get', '/' . $routes['dashboard-orders'], [\App\Http\Controllers\DashboardController::class, 'orders'])->name($this->routeName('orders', $locale, $isPrimary, false));
                $this->add('get', '/' . $this->replaceParams($routes['dashboard-order-detail']), [\App\Http\Controllers\DashboardController::class, 'orderDetail'])->name($this->routeName('order.detail', $locale, $isPrimary, false));
                $this->add('get', '/' . $this->replaceParams($routes['dashboard-order-invoice']), [\App\Http\Controllers\DashboardController::class, 'downloadInvoice'])->name($this->routeName('order.invoice', $locale, $isPrimary, false));
                $this->add('get', '/' . $routes['dashboard-subscription'], [\App\Http\Controllers\DashboardController::class, 'subscription'])->name($this->routeName('subscription', $locale, $isPrimary, false));
                $this->add('post', '/' . $routes['dashboard-subscription-pause'], [\App\Http\Controllers\DashboardController::class, 'pauseSubscription'])->name($this->routeName('subscription.pause', $locale, $isPrimary, false));
                $this->add('post', '/' . $routes['dashboard-subscription-resume'], [\App\Http\Controllers\DashboardController::class, 'resumeSubscription'])->name($this->routeName('subscription.resume', $locale, $isPrimary, false));
                $this->add('post', '/' . $routes['dashboard-subscription-cancel'], [\App\Http\Controllers\DashboardController::class, 'cancelSubscription'])->name($this->routeName('subscription.cancel', $locale, $isPrimary, false));
                $this->add('post', '/' . $this->replaceParams($routes['dashboard-subscription-pay']), [\App\Http\Controllers\SubscriptionController::class, 'payInvoice'])->name($this->routeName('subscription.pay', $locale, $isPrimary, false));
                $this->add('get', '/' . $this->replaceParams($routes['dashboard-subscription-invoice']), [\App\Http\Controllers\DashboardController::class, 'downloadSubscriptionInvoice'])->name($this->routeName('subscription.payment.invoice', $locale, $isPrimary, false));
                $this->add('post', '/' . $routes['dashboard-subscription-packeta'], [\App\Http\Controllers\DashboardController::class, 'updatePacketaPoint'])->name($this->routeName('subscription.update-packeta', $locale, $isPrimary, false));
                // Změna konfigurace existujícího předplatného (velikost, druh, frekvence)
                $this->add('get', '/' . $this->replaceParams($routes['dashboard-subscription-configure']), [\App\Http\Controllers\DashboardController::class, 'configureSubscription'])->whereNumber('subscription')->name($this->routeName('subscription.configure', $locale, $isPrimary, false));
                $this->add('post', '/' . $this->replaceParams($routes['dashboard-subscription-configure']), [\App\Http\Controllers\DashboardController::class, 'updateSubscriptionConfiguration'])->whereNumber('subscription')->middleware('throttle:10,1')->name($this->routeName('subscription.configure.update', $locale, $isPrimary, false));
                $this->add('post', '/' . $this->replaceParams($routes['dashboard-subscription-configure-cancel']), [\App\Http\Controllers\DashboardController::class, 'cancelScheduledConfiguration'])->whereNumber('subscription')->middleware('throttle:10,1')->name($this->routeName('subscription.configure.cancel', $locale, $isPrimary, false));
                $this->add('get', '/' . $routes['dashboard-notifications'], [\App\Http\Controllers\DashboardController::class, 'notifications'])->name($this->routeName('notifications', $locale, $isPrimary, false));
                
                // Affiliate dashboard
                $this->add('get', '/' . $routes['dashboard-affiliate'], [\App\Http\Controllers\Dashboard\AffiliateController::class, 'index'])->name($this->routeName('affiliate', $locale, $isPrimary, false));
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
     * Zaregistruje lokalizovanou routu, pokud stejnou metodu + cestu nezaregistroval
     * už jiný jazyk (slovenské cesty jako "kosik" nebo "blog" jsou stejné jako české).
     * Sdílenou routu pak najde localizedRoute() přes fallback na český název, jazyk
     * stránky určuje doména (DetectRegion), ne routa.
     *
     * Vrací routu, nebo objekt, který řetězené ->name()/->middleware() tiše spolkne.
     */
    protected function add(string $method, string $path, mixed $action): object
    {
        $prefix = trim((string) Route::getLastGroupPrefix(), '/');
        $uri = trim($prefix . '/' . trim($path, '/'), '/');
        $key = strtoupper($method === 'view' ? 'get' : $method) . ':' . ($uri === '' ? '/' : $uri);

        if (isset($this->registeredPaths[$key])) {
            return new class {
                public function __call(string $name, array $arguments): static
                {
                    return $this;
                }
            };
        }

        $this->registeredPaths[$key] = true;

        return $method === 'view'
            ? Route::view($path, $action)
            : Route::{$method}($path, $action);
    }

    /**
     * Šablona právní stránky v jazyce requestu: terms-of-service-en, -sk…, čeština bez přípony.
     */
    protected static function localizedView(string $view): string
    {
        $locale = app()->getLocale();
        $localized = $view . '-' . $locale;

        return $locale !== Site::PRIMARY_LOCALE && view()->exists($localized) ? $localized : $view;
    }
}
