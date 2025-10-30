<?php

namespace App\Http\Controllers;

use App\Mail\SubscriptionConfirmation;
use App\Models\Coupon;
use App\Models\ShippingRate;
use App\Models\Subscription;
use App\Models\SubscriptionConfig;
use App\Models\SubscriptionPlan;
use App\Services\CouponService;
use App\Services\ShippingService;
use App\Services\StripeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class SubscriptionController extends Controller
{
    public function __construct(
        private StripeService $stripeService,
        private CouponService $couponService,
        private ShippingService $shippingService
    ) {
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

        // Get roasteries of the month (same as homepage)
        $roasteriesOfMonth = \App\Models\Roastery::getRoasteriesOfMonth();
        
        // Get coffees of the month for next shipment - use correct method with month logic
        $coffeesOfMonth = \App\Models\Product::getCoffeesOfMonth();

        // Get promo image for current/next month
        $currentSchedule = \App\Models\ShipmentSchedule::getForMonth(now()->year, now()->month);
        $nextSchedule = \App\Models\ShipmentSchedule::getNextShipment();
        
        // Use next schedule if current month is past, otherwise use current
        $activeSchedule = $currentSchedule && !$currentSchedule->isPast() ? $currentSchedule : $nextSchedule;
        $promoImage = $activeSchedule?->promo_image ?? 'images/kavi-november-25.jpg';
        
        // Calculate display month and year (16th is the cutoff)
        $today = now();
        $displayMonth = $today->day >= 16 ? $today->copy()->addMonth() : $today->copy();
        
        // Get month name in nominative case
        $months = [
            1 => 'Leden', 2 => 'Únor', 3 => 'Březen', 4 => 'Duben',
            5 => 'Květen', 6 => 'Červen', 7 => 'Červenec', 8 => 'Srpen',
            9 => 'Září', 10 => 'Říjen', 11 => 'Listopad', 12 => 'Prosinec'
        ];
        $monthName = $months[$displayMonth->month];
        $displayYear = $displayMonth->year;

        return view('subscriptions.index', compact('plans', 'subscriptionPricing', 'shippingInfo', 'roasteriesOfMonth', 'coffeesOfMonth', 'promoImage', 'monthName', 'displayYear'));
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
            $sessionId = $request->get('session_id');
            $subscription = null;
            
            // Try to find subscription by Stripe session_id (most reliable)
            if ($sessionId) {
                $subscription = Subscription::where('stripe_session_id', $sessionId)->first();
                
                if ($subscription) {
                    \Log::info('Found subscription by session_id', [
                        'session_id' => $sessionId,
                        'subscription_id' => $subscription->id,
                    ]);
                }
            }
            
            // Fallback: find by user_id or email if session_id didn't work
            if (!$subscription) {
                if (auth()->check()) {
                    // For authenticated users, find by user_id
                    $subscription = Subscription::where('user_id', auth()->id())
                        ->latest()
                        ->first();
                } else {
                    // For guests, try to find by session email
                    $guestEmail = session('guest_subscription_email');
                    if ($guestEmail) {
                        $subscription = Subscription::whereJsonContains('shipping_address->email', $guestEmail)
                            ->latest()
                            ->first();
                    }
                }
                
                if ($subscription) {
                    \Log::info('Found subscription by fallback method', [
                        'subscription_id' => $subscription->id,
                        'method' => auth()->check() ? 'user_id' : 'email',
                    ]);
                }
            }

            // If subscription found, redirect to confirmation
            if ($subscription) {
                // Store subscription ID in session for secure auto-login
                if (!auth()->check()) {
                    session(['pending_subscription_' . $subscription->id => true]);
                }
                
                // Auto-login guest user if not already authenticated
                // SECURITY: Only auto-login if this session just created/accessed the subscription
                if (!auth()->check() && $subscription->user_id) {
                    if (session()->has('pending_subscription_' . $subscription->id)) {
                        $user = \App\Models\User::find($subscription->user_id);
                        if ($user) {
                            auth()->login($user);
                            \Log::info('Auto-logged in user after subscription payment', [
                                'user_id' => $user->id,
                                'subscription_id' => $subscription->id,
                            ]);
                        }
                    } else {
                        \Log::warning('Auto-login blocked - no session verification for subscription', [
                            'subscription_id' => $subscription->id,
                            'user_id' => $subscription->user_id,
                        ]);
                    }
                }
                
                // Clear subscription session data (but keep guest email for confirmation page authorization)
                session()->forget([
                    'subscription_configuration', 
                    'subscription_price',
                    'subscription_price_without_vat',
                    'subscription_vat',
                    'subscription_shipping_address',
                    'subscription_packeta',
                    'subscription_delivery_notes',
                    'guest_subscription_name',
                    'guest_subscription_phone',
                ]);
                
                return redirect()->route('subscriptions.confirmation', $subscription);
            } else {
                // Subscription not found yet (race condition) - show processing message
                \Log::warning('Subscription not found after payment', [
                    'session_id' => $sessionId,
                    'user_id' => auth()->id(),
                    'guest_email' => session('guest_subscription_email'),
                ]);
                
                return redirect()->route('subscriptions.index')
                    ->with('success', 'Děkujeme za objednávku! Zpracováváme vaši platbu a brzy vám zašleme potvrzení na email.');
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

        // Odebrat kupón pokud je požadováno
        if (request()->has('remove_coupon')) {
            $this->couponService->clearCouponFromStorage();
            return redirect()->route('subscriptions.checkout');
        }

        // Zpracovat kupón z query parametru (pokud byl zadán v formuláři)
        if (request()->has('coupon_code') && request('coupon_code')) {
            $couponCode = strtoupper(trim(request('coupon_code')));
            session(['coupon_code' => $couponCode]);
        }

        // Zkusit načíst kupón ze session nebo cookie
        $couponCode = $this->couponService->getCouponFromStorage();
        $appliedCoupon = null;
        $discount = 0;
        $errorMessage = null;
        
        if ($couponCode) {
            $result = $this->couponService->validateCoupon($couponCode, auth()->user(), 'subscription', $price);
            
            if ($result['valid']) {
                $appliedCoupon = $result['coupon'];
                $couponResult = $this->couponService->applyToSubscription($appliedCoupon, $price);
                
                $discount = $couponResult['discount'];
                $price = $couponResult['price'];
                $priceWithoutVat = $couponResult['price_without_vat'];
                $vat = $couponResult['vat'];
            } else {
                // Kupón není platný, vymazat ho a zobrazit chybu
                $errorMessage = $result['message'];
                $this->couponService->clearCouponFromStorage();
            }
        }

        // Get shipping date info
        $shippingInfo = \App\Helpers\SubscriptionHelper::getShippingDateInfo();
        
        // Calculate shipping based on user country (if known)
        $userCountry = auth()->check() && auth()->user()->country ? auth()->user()->country : null;
        $packetaCarrierId = null;
        $shipping = 0;
        
        if ($userCountry) {
            $packetaCarrierId = $this->shippingService->getPacketaCarrierForCountry($userCountry);
            $shipping = $this->shippingService->calculateShippingCost($userCountry, $price, true); // true = is subscription
        }
        
        // Pokud je chyba, uložit do session pro zobrazení
        if ($errorMessage) {
            session()->flash('coupon_error', $errorMessage);
        }

        return view('subscriptions.checkout', compact('configuration', 'price', 'priceWithoutVat', 'vat', 'shippingInfo', 'appliedCoupon', 'discount', 'packetaCarrierId', 'shipping'));
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
            'billing_country' => 'required|string|size:2',
            'packeta_point_id' => 'required|string',
            'packeta_point_name' => 'required|string',
            'packeta_point_address' => 'nullable|string',
            'carrier_id' => 'nullable|string',
            'carrier_pickup_point' => 'nullable|string',
            'payment_method' => 'required|in:card,transfer',
            'delivery_notes' => 'nullable|string|max:500',
            'coupon_code' => 'nullable|string',
        ]);

        try {
            // Zpracování kupónu
            $coupon = null;
            $discount = 0;
            $discountMonths = null;
            $couponCode = $validated['coupon_code'] ?? $this->couponService->getCouponFromStorage();
            
            if ($couponCode) {
                $result = $this->couponService->validateCoupon($couponCode, auth()->user(), 'subscription', $price);
                
                if ($result['valid']) {
                    $coupon = $result['coupon'];
                    $couponResult = $this->couponService->applyToSubscription($coupon, $price);
                    
                    $discount = $couponResult['discount'];
                    $price = $couponResult['price']; // Aktualizovat cenu se slevou
                    $discountMonths = $couponResult['months']; // null = neomezeně
                }
            }
            
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

            // Save contact info, billing address and Packeta pickup point to user for future use (if authenticated)
            if (auth()->check()) {
                auth()->user()->update([
                    'phone' => $validated['phone'] ?? auth()->user()->phone,
                    'address' => $validated['billing_address'],
                    'city' => $validated['billing_city'],
                    'postal_code' => $validated['billing_postal_code'],
                    'country' => $validated['billing_country'],
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
                    'billing_country' => $validated['billing_country'],
                ],
                'subscription_packeta' => [
                    'packeta_point_id' => $validated['packeta_point_id'],
                    'packeta_point_name' => $validated['packeta_point_name'],
                    'packeta_point_address' => $validated['packeta_point_address'],
                    'carrier_id' => $validated['carrier_id'] ?? null,
                    'carrier_pickup_point' => $validated['carrier_pickup_point'] ?? null,
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
                    'billing_country' => $validated['billing_country'],
                    'packeta_point_id' => $validated['packeta_point_id'],
                    'packeta_point_name' => $validated['packeta_point_name'],
                    'packeta_point_address' => $validated['packeta_point_address'],
                    'carrier_id' => $validated['carrier_id'] ?? null,
                    'carrier_pickup_point' => $validated['carrier_pickup_point'] ?? null,
                    'delivery_notes' => $validated['delivery_notes'] ?? null,
                ];

                $session = $this->stripeService->createConfiguredSubscriptionCheckoutSession(
                    auth()->user(),
                    $configuration,
                    $price,
                    $shippingAddress,
                    $coupon,
                    $discount,
                    $discountMonths
                );

                // Redirect to Stripe Checkout
                return redirect($session->url);
            } else {
                // Bank transfer - create subscription directly as pending
                DB::beginTransaction();

                // Calculate next billing date (15th of the next billing cycle)
                // Billing cycle: 16th of one month to 15th of next month
                $currentBillingCycleEnd = now()->day <= 15 
                    ? now()->copy()->setDay(15) 
                    : now()->copy()->addMonth()->setDay(15);
                $nextBillingDate = $currentBillingCycleEnd->copy()->addMonths($configuration['frequency']);

                $subscription = Subscription::create([
                    'subscription_number' => Subscription::generateSubscriptionNumber(),
                    'user_id' => auth()->id() ?? null,
                    'subscription_plan_id' => null, // Custom configuration, no plan
                    'coupon_id' => $coupon?->id,
                    'coupon_code' => $coupon?->code,
                    'discount_amount' => $discount,
                    'discount_months_remaining' => $discountMonths,
                    'discount_months_total' => $discountMonths,
                    'configuration' => $configuration,
                    'configured_price' => $price,
                    'frequency_months' => $configuration['frequency'],
                    'status' => 'pending', // Will be activated after payment confirmation
                    'starts_at' => now(),
                    'next_billing_date' => $nextBillingDate,
                    'shipping_address' => [
                        'name' => $validated['name'],
                        'email' => $validated['email'],
                        'phone' => $validated['phone'] ?? null,
                        'billing_address' => $validated['billing_address'],
                        'billing_city' => $validated['billing_city'],
                        'billing_postal_code' => $validated['billing_postal_code'],
                        'country' => $validated['billing_country'],
                    ],
                    'payment_method' => 'transfer',
                    'packeta_point_id' => $validated['packeta_point_id'],
                    'packeta_point_name' => $validated['packeta_point_name'],
                    'packeta_point_address' => $validated['packeta_point_address'],
                    'carrier_id' => $validated['carrier_id'] ?? null,
                    'carrier_pickup_point' => $validated['carrier_pickup_point'] ?? null,
                    'delivery_notes' => $validated['delivery_notes'] ?? null,
                ]);

                // Zaznamenat použití kupónu
                if ($coupon) {
                    $this->couponService->recordUsage(
                        $coupon,
                        auth()->user(),
                        'subscription',
                        $discount,
                        null,
                        $subscription
                    );
                    
                    // Vymazat kupón z cookie/session
                    $this->couponService->clearCouponFromStorage();
                }

                DB::commit();
                
                // Store subscription ID in session for secure access (bank transfer only)
                if (!auth()->check()) {
                    session(['pending_subscription_' . $subscription->id => true]);
                }
                
                // Send subscription confirmation email
                try {
                    Mail::to($validated['email'])->send(new SubscriptionConfirmation($subscription));
                } catch (\Exception $e) {
                    \Log::error('Failed to send subscription confirmation email: ' . $e->getMessage());
                }

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

    /**
     * Show subscription confirmation page
     */
    public function confirmation(Subscription $subscription)
    {
        // Auto-login guest user if not already authenticated
        // SECURITY: Only auto-login if this session has pending_subscription flag
        if (!auth()->check() && $subscription->user_id) {
            if (session()->has('pending_subscription_' . $subscription->id)) {
                $user = \App\Models\User::find($subscription->user_id);
                if ($user) {
                    auth()->login($user);
                    // Clear the pending session after successful login
                    session()->forget('pending_subscription_' . $subscription->id);
                    
                    \Log::info('Auto-logged in user for subscription confirmation', [
                        'user_id' => $user->id,
                        'subscription_id' => $subscription->id,
                    ]);
                }
            } else {
                \Log::warning('Auto-login blocked - no session verification for subscription confirmation', [
                    'subscription_id' => $subscription->id,
                    'user_id' => $subscription->user_id,
                ]);
            }
        }
        
        // If user is authenticated, check if subscription belongs to them
        if (auth()->check() && $subscription->user_id !== auth()->id()) {
            abort(403);
        }

        // If user is not authenticated, verify they have access via session data
        if (!auth()->check()) {
            // Allow access if:
            // 1. Subscription has no user_id (original guest subscription)
            // 2. OR guest email in session matches subscription email
            // 3. OR pending_subscription flag exists (just cleared by auto-login attempt)
            $guestEmail = session('guest_subscription_email');
            $subscriptionEmail = $subscription->shipping_address['email'] ?? null;
            
            $hasAccess = $subscription->user_id === null || 
                         ($guestEmail && $subscriptionEmail && $guestEmail === $subscriptionEmail);
            
            if (!$hasAccess) {
                \Log::warning('Unauthorized access attempt to subscription confirmation', [
                    'subscription_id' => $subscription->id,
                    'subscription_user_id' => $subscription->user_id,
                    'subscription_email' => $subscriptionEmail,
                    'session_email' => $guestEmail,
                ]);
                abort(403, 'Nemáte oprávnění zobrazit tuto stránku. Prosím přihlaste se.');
            }
        }

        return view('subscriptions.confirmation', compact('subscription'));
    }

    /**
     * Create payment session for unpaid subscription invoice
     */
    public function payInvoice(Subscription $subscription)
    {
        // Check if subscription belongs to authenticated user
        if ($subscription->user_id !== auth()->id()) {
            abort(403, 'Nemáte oprávnění k této akci.');
        }

        // Check if subscription has unpaid status and pending invoice
        if ($subscription->status !== 'unpaid' || !$subscription->pending_invoice_id) {
            return back()->with('error', 'Toto předplatné nemá neuhrazenou fakturu.');
        }

        try {
            $checkoutUrl = $this->stripeService->createInvoicePaymentSession($subscription);
            
            if (!$checkoutUrl) {
                return back()->with('error', 'Nelze vytvořit platební session. Kontaktujte prosím podporu.');
            }

            return redirect($checkoutUrl);
        } catch (\Exception $e) {
            \Log::error('Failed to create payment session for subscription invoice', [
                'subscription_id' => $subscription->id,
                'error' => $e->getMessage(),
            ]);

            return back()->with('error', 'Nastala chyba při vytváření platby. Zkuste to prosím později.');
        }
    }
}


