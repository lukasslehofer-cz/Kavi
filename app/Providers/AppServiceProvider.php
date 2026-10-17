<?php

namespace App\Providers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Subscription;
use App\Models\SubscriptionShipment;
use App\Observers\OrderObserver;
use App\Observers\ProductObserver;
use App\Observers\SubscriptionShipmentObserver;
use App\Observers\SubscriptionStockObserver;
use Illuminate\Pagination\Paginator;
use Illuminate\Routing\Redirector;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Jedna instance na request: lineup měsíce se načte jednou pro celou stránku
        $this->app->scoped(\App\Services\CoffeeOfMonthService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useTailwind();
        
        Order::observe(OrderObserver::class);
        Product::observe(ProductObserver::class);
        Subscription::observe(SubscriptionStockObserver::class);
        SubscriptionShipment::observe(SubscriptionShipmentObserver::class);

        // Add localizedRoute macro to Redirector for redirect()->localizedRoute() support
        Redirector::macro('localizedRoute', function (string $route, $parameters = [], $status = 302, $headers = []) {
            return $this->to(localizedRoute($route, $parameters), $status, $headers);
        });
    }
}




