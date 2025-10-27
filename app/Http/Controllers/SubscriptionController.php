<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use App\Models\SubscriptionConfig;
use App\Models\SubscriptionPlan;
use App\Services\StripeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubscriptionController extends Controller
{
    public function __construct(private StripeService $stripeService)
    {
    }

    public function index()
    {
        $plans = SubscriptionPlan::active()
            ->orderBy('price')
            ->get();

        // Get subscription pricing configuration
        $subscriptionPricing = [
            '2' => SubscriptionConfig::get('price_2_bags', 500),
            '3' => SubscriptionConfig::get('price_3_bags', 720),
            '4' => SubscriptionConfig::get('price_4_bags', 920),
        ];

        // Get shipping date info
        $shippingInfo = \App\Helpers\SubscriptionHelper::getShippingDateInfo();

        // Get coffees of the month for next shipment
        $coffeesOfMonth = \App\Models\Product::coffeeOfMonth()
            ->orderBy('sort_order')
            ->take(6)
            ->get();

        return view('subscriptions.index', compact('plans', 'subscriptionPricing', 'shippingInfo', 'coffeesOfMonth'));
    }

    public function show(SubscriptionPlan $plan)
    {
        if (!$plan->is_active) {
            abort(404);
        }

        return view('subscriptions.show', compact('plan'));
    }

    public function subscribe(Request $request, SubscriptionPlan $plan)
    {
        if (!auth()->check()) {
            return redirect()->route('login')
                ->with('message', 'Pro aktivaci předplatného se prosím přihlaste.');
        }

        // Store plan ID in session for checkout
        session(['subscription_plan_id' => $plan->id]);

        return redirect()->route('checkout.subscription');
    }

    /**
     * Handle subscription from configurator
     */
    public function configureCheckout(Request $request)
    {
        // Log incoming data for debugging
        logger()->info('=== FORMULÁŘ ODESLÁN ===');
        logger()->info('URL: ' . $request->fullUrl());
        logger()->info('Method: ' . $request->method());
        logger()->info('Raw POST data:', $request->all());
        logger()->info('Headers:', $request->headers->all());
        logger()->info('Cookies:', $request->cookies->all());
        
        try {
            $validated = $request->validate([
                'amount' => 'required|integer|between:2,4',
                'type' => 'required|in:espresso,filter,mix',
                'isDecaf' => 'nullable|in:0,1',  // 0 = ne, 1 = ano
                'mix' => 'nullable|array',
                'mix.espresso' => 'nullable|integer|min:0',
                'mix.filter' => 'nullable|integer|min:0',
                'frequency' => 'required|integer|in:1,2,3',
            ]);
            
            // Convert isDecaf to boolean
            $validated['isDecaf'] = ($validated['isDecaf'] ?? '0') === '1';
            
            logger()->info('Validation passed successfully', ['validated' => $validated]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            logger()->error('Validation failed:', [
                'errors' => $e->errors(),
                'input' => $request->all()
            ]);
            
            return back()
                ->withErrors($e->errors())
                ->withInput()
                ->with('error', 'Chyba při validaci konfigurace. Zkuste to prosím znovu.');
        }

        // Validate mix total if type is mix
        if ($validated['type'] === 'mix') {
            $mixTotal = ($validated['mix']['espresso'] ?? 0) + 
                       ($validated['mix']['filter'] ?? 0);
            
            if ($mixTotal != $validated['amount']) {
                return back()->withErrors(['mix' => 'Celkový počet balení v mixu musí být ' . $validated['amount']]);
            }
        }

        // Calculate price based on amount (tiered pricing)
        $pricingKey = 'price_' . $validated['amount'] . '_bags';
        $totalPriceWithVat = SubscriptionConfig::get($pricingKey, 500); // Default to 500 if not found
        
        // Add decaf surcharge if selected
        if ($validated['isDecaf']) {
            $totalPriceWithVat += 100; // +100 Kč za decaf variantu
        }
        
        // Calculate VAT (all prices include 21% VAT)
        $priceWithoutVat = round($totalPriceWithVat / 1.21, 2);
        $vat = round($totalPriceWithVat - $priceWithoutVat, 2);

        // Store configuration in session for checkout
        session([
            'subscription_configuration' => $validated,
            'subscription_price' => $totalPriceWithVat,
            'subscription_price_without_vat' => $priceWithoutVat,
            'subscription_vat' => $vat,
        ]);

        logger()->info('Configuration stored in session. Redirecting to checkout.', [
            'config' => $validated,
            'price' => $totalPriceWithVat,
            'price_without_vat' => $priceWithoutVat,
            'vat' => $vat
        ]);

        // Proceed to checkout (accessible for everyone now)
        return redirect()->route('subscriptions.checkout');
    }

    /**
     * Show checkout page
     */
    public function checkout(Request $request)
    {
        // Handle successful return from Stripe
        if ($request->has('success') && $request->success == 1) {
            // Clear subscription session data
            session()->forget([
                'subscription_configuration', 
                'subscription_price',
                'subscription_price_without_vat',
                'subscription_vat',
                'subscription_shipping_address',
                'subscription_packeta',
                'subscription_delivery_notes',
            ]);

            if (auth()->check()) {
                return redirect()->route('dashboard.subscription')
                    ->with('success', 'Děkujeme! Vaše platba byla úspěšně zpracována a předplatné je nyní aktivní.');
            } else {
                return redirect()->route('subscriptions.index')
                    ->with('success', 'Děkujeme za objednávku! Brzy vám zašleme potvrzení na email.');
            }
        }

        $configuration = session('subscription_configuration');
        $price = session('subscription_price');
        $priceWithoutVat = session('subscription_price_without_vat');
        $vat = session('subscription_vat');

        if (!$configuration || !$price) {
            return redirect()->route('subscriptions.index')
                ->with('error', 'Konfigurace předplatného nenalezena. Prosím nakonfigurujte si předplatné znovu.');
        }

        // Get shipping date info
        $shippingInfo = \App\Helpers\SubscriptionHelper::getShippingDateInfo();

        return view('subscriptions.checkout', compact('configuration', 'price', 'priceWithoutVat', 'vat', 'shippingInfo'));
    }

    /**
     * Process subscription order
     */
    public function processCheckout(Request $request)
    {
        $configuration = session('subscription_configuration');
        $price = session('subscription_price');

        if (!$configuration || !$price) {
            return redirect()->route('subscriptions.index')
                ->with('error', 'Konfigurace nenalezena.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => auth()->check() ? 'required|string|max:20' : 'nullable|string|max:20',
            'billing_address' => 'required|string|max:255',
            'billing_city' => 'required|string|max:100',
            'billing_postal_code' => 'required|string|max:20',
            'packeta_point_id' => 'required|string',
            'packeta_point_name' => 'required|string',
            'packeta_point_address' => 'nullable|string',
            'payment_method' => 'required|in:card,transfer',
            'delivery_notes' => 'nullable|string|max:500',
        ]);

        try {
            // Check for existing user if guest checkout
            if (!auth()->check()) {
                $existingUser = \App\Models\User::where('email', $validated['email'])->first();
                
                if ($existingUser) {
                    return back()
                        ->withInput()
                        ->with('error', 'Účet s tímto emailem již existuje. Prosím přihlaste se.');
                }
                
                // Store guest info in session for later registration
                session([
                    'guest_subscription_email' => $validated['email'],
                    'guest_subscription_name' => $validated['name'],
                    'guest_subscription_phone' => $validated['phone'] ?? null,
                ]);
            }

            // Save Packeta pickup point to user for future use (if authenticated)
            if (auth()->check()) {
                auth()->user()->update([
                    'packeta_point_id' => $validated['packeta_point_id'],
                    'packeta_point_name' => $validated['packeta_point_name'],
                    'packeta_point_address' => $validated['packeta_point_address'],
                ]);
            }

            // Store shipping address and delivery notes in session for webhook
            session([
                'subscription_shipping_address' => [
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                    'phone' => $validated['phone'] ?? null,
                    'billing_address' => $validated['billing_address'],
                    'billing_city' => $validated['billing_city'],
                    'billing_postal_code' => $validated['billing_postal_code'],
                    'country' => 'CZ',
                ],
                'subscription_packeta' => [
                    'packeta_point_id' => $validated['packeta_point_id'],
                    'packeta_point_name' => $validated['packeta_point_name'],
                    'packeta_point_address' => $validated['packeta_point_address'],
                ],
                'subscription_delivery_notes' => $validated['delivery_notes'] ?? null,
            ]);

            // Handle payment method
            if ($validated['payment_method'] === 'card') {
                // Create Stripe Checkout Session
                $shippingAddress = [
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                    'phone' => $validated['phone'] ?? null,
                    'billing_address' => $validated['billing_address'],
                    'billing_city' => $validated['billing_city'],
                    'billing_postal_code' => $validated['billing_postal_code'],
                    'packeta_point_id' => $validated['packeta_point_id'],
                    'packeta_point_name' => $validated['packeta_point_name'],
                    'packeta_point_address' => $validated['packeta_point_address'],
                    'delivery_notes' => $validated['delivery_notes'] ?? null,
                ];

                $session = $this->stripeService->createConfiguredSubscriptionCheckoutSession(
                    auth()->user(),
                    $configuration,
                    $price,
                    $shippingAddress
                );

                // Redirect to Stripe Checkout
                return redirect($session->url);
            } else {
                // Bank transfer - create subscription directly as pending
                DB::beginTransaction();

                $subscription = Subscription::create([
                    'user_id' => auth()->id() ?? null,
                    'subscription_plan_id' => null, // Custom configuration, no plan
                    'configuration' => $configuration,
                    'configured_price' => $price,
                    'frequency_months' => $configuration['frequency'],
                    'status' => 'pending', // Will be activated after payment confirmation
                    'starts_at' => now(),
                    'next_billing_date' => now()->addMonths($configuration['frequency']),
                    'shipping_address' => [
                        'name' => $validated['name'],
                        'email' => $validated['email'],
                        'phone' => $validated['phone'] ?? null,
                        'billing_address' => $validated['billing_address'],
                        'billing_city' => $validated['billing_city'],
                        'billing_postal_code' => $validated['billing_postal_code'],
                        'country' => 'CZ',
                    ],
                    'payment_method' => 'transfer',
                    'packeta_point_id' => $validated['packeta_point_id'],
                    'packeta_point_name' => $validated['packeta_point_name'],
                    'packeta_point_address' => $validated['packeta_point_address'],
                    'delivery_notes' => $validated['delivery_notes'] ?? null,
                ]);

                DB::commit();

                // Clear session
                session()->forget([
                    'subscription_configuration', 
                    'subscription_price',
                    'subscription_price_without_vat',
                    'subscription_vat',
                    'subscription_shipping_address',
                    'subscription_packeta',
                    'subscription_delivery_notes',
                ]);

                if (auth()->check()) {
                    return redirect()->route('dashboard.subscription')
                        ->with('success', 'Předplatné bylo vytvořeno! Po přijetí platby bude aktivováno.');
                } else {
                    return redirect()->route('subscriptions.index')
                        ->with('success', 'Děkujeme za objednávku! Na email ' . $validated['email'] . ' vám zašleme platební údaje.');
                }
            }

        } catch (\Exception $e) {
            \Log::error('Subscription checkout failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            return back()
                ->withInput()
                ->with('error', 'Nastala chyba při vytváření předplatného. Zkuste to prosím znovu.');
        }
    }
}


