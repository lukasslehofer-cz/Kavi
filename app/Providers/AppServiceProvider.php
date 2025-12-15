<?php

namespace App\Providers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Subscription;
use App\Observers\OrderObserver;
use App\Observers\ProductObserver;
use App\Observers\SubscriptionStockObserver;
use Illuminate\Routing\Redirector;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Order::observe(OrderObserver::class);
        Product::observe(ProductObserver::class);
        Subscription::observe(SubscriptionStockObserver::class);

        // Add localizedRoute macro to Redirector for redirect()->localizedRoute() support
        Redirector::macro('localizedRoute', function (string $route, $parameters = [], $status = 302, $headers = []) {
            return $this->to(localizedRoute($route, $parameters), $status, $headers);
        });
    }
}




