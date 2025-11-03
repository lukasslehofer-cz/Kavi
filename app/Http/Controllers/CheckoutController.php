<?php

namespace App\Http\Controllers;

use App\Mail\OrderConfirmation;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ShippingRate;
use App\Services\CouponService;
use App\Services\FakturoidService;
use App\Services\ShippingService;
use App\Services\SubscriptionAddonService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class CheckoutController extends Controller
{
    // No auth middleware - supports guest checkout

    public function __construct(
        private CouponService $couponService,
        private ShippingService $shippingService,
        private SubscriptionAddonService $addonService
    ) {
    }

    public function index(Request $request)
    {
        // Handle return from cancelled payment - restore cart from order
        if ($request->has('order_id')) {
            $orderId = $request->get('order_id');
            $order = Order::find($orderId);
            
            if ($order && $order->payment_status === 'pending') {
                // Restore cart from order backup (stored in admin_notes)
                try {
                    $adminNotes = json_decode($order->admin_notes, true);
                    if (isset($adminNotes['cart_backup'])) {
                        session(['cart' => $adminNotes['cart_backup']]);
                        
                        \Log::info('Cart restored from cancelled order', [
                            'order_id' => $order->id,
                            'cart' => $adminNotes['cart_backup']
                        ]);
                        
                        // Show message that they can try payment again
                        session()->flash('info', 'Platba byla zrušena. Můžete pokračovat v objednávce nebo upravit košík.');
                    }
                } catch (\Exception $e) {
                    \Log::error('Failed to restore cart from order', [
                        'order_id' => $orderId,
                        'error' => $e->getMessage()
                    ]);
                }
            }
        }
        
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')
                ->with('error', 'Váš košík je prázdný.');
        }

        $cartItems = [];
        $subtotal = 0;

        foreach ($cart as $productId => $quantity) {
            $product = Product::find($productId);
            if ($product && $product->is_active) {
                $cartItems[] = [
                    'product' => $product,
                    'quantity' => $quantity,
                    'subtotal' => $product->price * $quantity,
                ];
                $subtotal += $product->price * $quantity;
            }
        }

        // Calculate shipping dynamically based on user country
        $userCountry = auth()->check() && auth()->user()->country ? auth()->user()->country : 'CZ'; // Default to Czech Republic for guests
        $shipping = $this->shippingService->calculateShippingCost($userCountry, $subtotal, false);
        $packetaVendors = $this->shippingService->getPacketaWidgetVendorsForCountry($userCountry);
        
        // Odebrat kupón pokud je požadováno
        if (request()->has('remove_coupon')) {
            $this->couponService->clearCouponFromStorage();
            return redirect()->route('checkout.index');
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
            $result = $this->couponService->validateCoupon($couponCode, auth()->user(), 'order', $subtotal);
            
            if ($result['valid']) {
                $appliedCoupon = $result['coupon'];
                $couponResult = $this->couponService->applyToOrder($appliedCoupon, $subtotal, $shipping);
                
                $discount = $couponResult['discount'];
                $shipping = $couponResult['shipping'];
                $totalWithVat = $couponResult['total'];
                $totalWithoutVat = $couponResult['total_without_vat'];
                $vat = $couponResult['vat'];
            } else {
                // Kupón není platný, vymazat ho a zobrazit chybu
                $errorMessage = $result['message'];
                $this->couponService->clearCouponFromStorage();
            }
        }
        
        if (!$appliedCoupon) {
            // Bez kupónu - standardní výpočet
            $totalWithVat = $subtotal + $shipping;
            $totalWithoutVat = round($totalWithVat / 1.21, 2);
            $vat = round($totalWithVat - $totalWithoutVat, 2);
        }
        
        // Pokud je chyba, uložit do session pro zobrazení
        if ($errorMessage) {
            session()->flash('coupon_error', $errorMessage);
        }

        // Zkontrolovat dostupnost volby "s předplatným"
        $canShipWithSubscription = false;
        $subscriptionShipmentInfo = [];
        $availableSubscriptions = [];

        if (auth()->check()) {
            $cartQuantity = array_sum($cart);
            
            // Získat všechna aktivní předplatná s dostupnými sloty
            $allSlots = $this->addonService->getAllAvailableSlots(auth()->user());
            
            if (!empty($allSlots)) {
                $canShipWithSubscription = true;
                
                foreach ($allSlots as $slots) {
                    $availableSubscriptions[] = [
                        'subscription' => $slots['subscription'],
                        'available_slots' => $slots['available'],
                        'used_slots' => $slots['used'],
                        'max_slots' => $slots['max'],
                        'can_add_cart' => $slots['available'] >= $cartQuantity,
                        'next_shipment_date' => $slots['next_shipment']->shipment_date,
                        'next_shipment_formatted' => $slots['next_shipment']->shipment_date->format('d.m.Y'),
                    ];
                }
                
                // Pro zpětnou kompatibilitu - pokud je jen jedno předplatné
                if (count($availableSubscriptions) === 1) {
                    $subscriptionShipmentInfo = $availableSubscriptions[0];
                    $subscriptionShipmentInfo['cart_quantity'] = $cartQuantity;
                }
            }
        }

        // Get available countries for shipping
        $availableCountries = ShippingRate::where('enabled', true)
            ->orderBy('country_name')
            ->get()
            ->pluck('country_name', 'country_code')
            ->toArray();

        return view('checkout.index', compact(
            'cartItems', 
            'subtotal', 
            'shipping', 
            'totalWithVat', 
            'totalWithoutVat', 
            'vat', 
            'appliedCoupon', 
            'discount', 
            'packetaVendors',
            'canShipWithSubscription',
            'subscriptionShipmentInfo',
            'availableSubscriptions',
            'availableCountries'
        ));
    }

    /**
     * AJAX: Calculate shipping cost based on country
     */
    public function calculateShipping(Request $request)
    {
        $country = $request->input('country');
        $subtotal = (float) $request->input('subtotal', 0);
        $isSubscription = (bool) $request->input('is_subscription', false);
        
        if (!$country) {
            return response()->json(['error' => 'Country is required'], 400);
        }

        // Calculate shipping
        $shipping = $this->shippingService->calculateShippingCost($country, $subtotal, $isSubscription);
        
        // Get shipping rate details
        $rate = ShippingRate::getForCountry($country);
        
        // Check if shipping is available
        $available = $rate && $rate->enabled;
        
        if (!$available) {
            return response()->json([
                'error' => 'Shipping to this country is not available',
                'available' => false,
            ], 422);
        }

        // Calculate remaining for free shipping (only for orders, not subscriptions)
        $remaining = null;
        if (!$isSubscription) {
            $remaining = $this->shippingService->getRemainingForFreeShipping($country, $subtotal);
        }

        return response()->json([
            'shipping' => $shipping,
            'shipping_formatted' => $this->shippingService->formatShippingCost($shipping),
            'packeta_vendors' => $rate->getPacketaWidgetVendors(),
            'packeta_carrier_names' => $rate->getPacketaCarrierNames(),
            'available' => true,
            'free_shipping_remaining' => $remaining,
        ]);
    }

    public function store(Request $request)
    {
        // Dynamická validace - Packeta data jsou required pouze pokud se neposílá s předplatným
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => ['required', 'string', 'max:20', 'regex:/^[\+]?[0-9\s\-\(\)]{9,20}$/'],
            'billing_address' => 'required|string',
            'billing_city' => 'required|string',
            'billing_postal_code' => 'required|string',
            'billing_country' => 'required|string|size:2',
            'payment_method' => 'required|in:card,transfer',
            'coupon_code' => 'nullable|string',
            'ship_with_subscription' => 'nullable|boolean',
            'selected_subscription_id' => 'nullable|exists:subscriptions,id',
        ];

        // Packeta fields are required only if NOT shipping with subscription
        if (!$request->ship_with_subscription) {
            $rules['packeta_point_id'] = 'required|string';
            $rules['packeta_point_name'] = 'required|string';
            $rules['packeta_point_address'] = 'nullable|string';
        }

        $request->validate($rules);

        // Check for existing user if guest checkout
        if (!auth()->check()) {
            $existingUser = \App\Models\User::where('email', $request->email)->first();
            
            if ($existingUser) {
                return back()
                    ->withInput()
                    ->with('error', 'Účet s tímto emailem již existuje. Prosím přihlaste se nebo použijte jiný email.');
            }
        }

        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')
                ->with('error', 'Váš košík je prázdný.');
        }

        DB::beginTransaction();

        try {
            // Check if there's a recent pending order for this user with same cart
            // This prevents duplicate orders when user returns from cancelled payment
            $existingPendingOrder = null;
            if (auth()->check()) {
                $existingPendingOrder = Order::where('user_id', auth()->id())
                    ->where('payment_status', 'pending')
                    ->where('created_at', '>=', now()->subHours(2)) // Only within last 2 hours
                    ->orderBy('created_at', 'desc')
                    ->first();
                
                if ($existingPendingOrder) {
                    // Check if cart matches (stored in admin_notes)
                    $adminNotes = json_decode($existingPendingOrder->admin_notes, true);
                    $savedCart = $adminNotes['cart_backup'] ?? [];
                    
                    // If cart matches, reuse this order
                    if ($savedCart === $cart) {
                        \Log::info('Reusing existing pending order', [
                            'order_id' => $existingPendingOrder->id,
                            'order_number' => $existingPendingOrder->order_number,
                        ]);
                        
                        DB::commit();
                        
                        // Proceed to payment
                        if ($request->payment_method === 'card') {
                            return redirect()->route('payment.card', $existingPendingOrder);
                        }
                        
                        return redirect()->route('order.confirmation', $existingPendingOrder)
                            ->with('success', 'Objednávka byla úspěšně vytvořena!');
                    }
                }
            }
            
            $subtotal = 0;
            $orderItems = [];

            foreach ($cart as $productId => $quantity) {
                $product = Product::find($productId);
                if ($product && $product->is_active && $product->stock >= $quantity) {
                    $subtotal += $product->price * $quantity;
                    $orderItems[] = [
                        'product' => $product,
                        'quantity' => $quantity,
                        'price' => $product->price,
                        'total' => $product->price * $quantity,
                    ];
                }
            }

            // Zpracování volby "odeslat s předplatným"
            $shipWithSubscription = false;
            $selectedSubscription = null;
            $shipmentSchedule = null;
            
            if ($request->ship_with_subscription && auth()->check()) {
                $cartQuantity = array_sum($cart);
                
                // Pokud je vybrané konkrétní předplatné
                if ($request->selected_subscription_id) {
                    $selectedSubscription = auth()->user()->activeSubscriptions()
                        ->where('id', $request->selected_subscription_id)
                        ->first();
                }
                
                // Validace možnosti přidat zboží
                if ($selectedSubscription && $this->addonService->canAddItems(auth()->user(), $cartQuantity, $selectedSubscription)) {
                    $slots = $this->addonService->getAvailableSlots(auth()->user(), $selectedSubscription);
                    $shipmentSchedule = $slots['next_shipment'];
                    $shipWithSubscription = true;
                } else {
                    return back()
                        ->withInput()
                        ->with('error', 'Překročili jste limit doplňkového zboží nebo předplatné není dostupné.');
                }
            }

            // Calculate shipping based on selected country or subscription addon
            $shippingCountry = $request->billing_country;
            $shipping = $shipWithSubscription ? 0 : $this->shippingService->calculateShippingCost($shippingCountry, $subtotal, false);
            $shippingRate = ShippingRate::getForCountry($shippingCountry);
            
            // Zpracování kupónu
            $coupon = null;
            $discount = 0;
            $couponCode = $request->coupon_code ?? $this->couponService->getCouponFromStorage();
            
            if ($couponCode) {
                $result = $this->couponService->validateCoupon($couponCode, auth()->user(), 'order', $subtotal);
                
                if ($result['valid']) {
                    $coupon = $result['coupon'];
                    $couponResult = $this->couponService->applyToOrder($coupon, $subtotal, $shipping);
                    
                    $discount = $couponResult['discount'];
                    $shipping = $couponResult['shipping'];
                    
                    // CRITICAL: Pokud je addon objednávka, doprava MUSÍ být vždy 0
                    if ($shipWithSubscription) {
                        $shipping = 0;
                        // Přepočítat celkovou cenu bez dopravy
                        $totalWithVat = ($subtotal - $discount) + $shipping;
                        $totalWithoutVat = round($totalWithVat / 1.21, 2);
                        $tax = round($totalWithVat - $totalWithoutVat, 2);
                    } else {
                        $totalWithVat = $couponResult['total'];
                        $totalWithoutVat = $couponResult['total_without_vat'];
                        $tax = $couponResult['vat'];
                    }
                } else {
                    // Kupón není platný, pokračovat bez něj
                    $totalWithVat = $subtotal + $shipping;
                    $totalWithoutVat = round($totalWithVat / 1.21, 2);
                    $tax = round($totalWithVat - $totalWithoutVat, 2);
                }
            } else {
                // Bez kupónu
                $totalWithVat = $subtotal + $shipping;
                $totalWithoutVat = round($totalWithVat / 1.21, 2);
                $tax = round($totalWithVat - $totalWithoutVat, 2);
            }

            // Pokud se posílá s předplatným, použít Packeta údaje z předplatného
            $packetaPointId = $request->packeta_point_id;
            $packetaPointName = $request->packeta_point_name;
            $packetaPointAddress = $request->packeta_point_address;
            $carrierId = $request->carrier_id;
            $carrierPickupPoint = $request->carrier_pickup_point;

            if ($shipWithSubscription && $selectedSubscription) {
                $packetaPointId = $selectedSubscription->packeta_point_id;
                $packetaPointName = $selectedSubscription->packeta_point_name;
                $packetaPointAddress = $selectedSubscription->packeta_point_address;
                $carrierId = $selectedSubscription->carrier_id;
                $carrierPickupPoint = $selectedSubscription->carrier_pickup_point;
            }

            $orderData = [
                'order_number' => Order::generateOrderNumber(),
                'user_id' => auth()->id() ?? null,
                'coupon_id' => $coupon?->id,
                'coupon_code' => $coupon?->code,
                'discount_amount' => $discount,
                'subtotal' => $subtotal,
                'shipping' => $shipping,
                'shipping_rate_id' => $shippingRate?->id,
                'shipping_country' => $shippingCountry,
                'tax' => $tax,
                'total' => $totalWithVat,
                'status' => 'pending',
                'payment_status' => 'pending',
                'payment_method' => $request->payment_method,
                'shipping_address' => [
                    'name' => $request->name,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'billing_address' => $request->billing_address,
                    'billing_city' => $request->billing_city,
                    'billing_postal_code' => $request->billing_postal_code,
                    'billing_country' => $request->billing_country,
                    'packeta_point_id' => $packetaPointId,
                    'packeta_point_name' => $packetaPointName,
                    'packeta_point_address' => $packetaPointAddress,
                    'carrier_id' => $carrierId,
                    'carrier_pickup_point' => $carrierPickupPoint,
                ],
                'customer_notes' => $request->notes, // User's actual notes from form
                'admin_notes' => null, // Will be set after order creation with cart backup
            ];

            // Pokud je to addon objednávka, přidat subscription údaje
            if ($shipWithSubscription) {
                $orderData['subscription_id'] = $selectedSubscription->id;
                $orderData['shipment_schedule_id'] = $shipmentSchedule->id;
                $orderData['shipped_with_subscription'] = true;
                $orderData['subscription_addon_slots_used'] = array_sum($cart);
            }

            $order = Order::create($orderData);

            // Save contact info, billing address and Packeta pickup point to user for future use (if authenticated)
            if (auth()->check()) {
                auth()->user()->update([
                    'phone' => $request->phone,
                    'address' => $request->billing_address,
                    'city' => $request->billing_city,
                    'postal_code' => $request->billing_postal_code,
                    'country' => $request->billing_country,
                    'packeta_point_id' => $request->packeta_point_id,
                    'packeta_point_name' => $request->packeta_point_name,
                    'packeta_point_address' => $request->packeta_point_address,
                ]);
            }

            foreach ($orderItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product']->id,
                    'product_name' => $item['product']->name,
                    'product_image' => $item['product']->image,
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'total' => $item['total'],
                ]);

                // Decrease stock
                $item['product']->decrement('stock', $item['quantity']);
            }

            // Zaznamenat použití kupónu
            if ($coupon) {
                $this->couponService->recordUsage(
                    $coupon,
                    auth()->user(),
                    'order',
                    $discount,
                    $order
                );
                
                // Vymazat kupón z cookie/session
                $this->couponService->clearCouponFromStorage();
            }

            DB::commit();

            // Create user account for guest orders
            if (!auth()->check()) {
                try {
                    $existingUser = \App\Models\User::where('email', $request->email)->first();
                    
                    if (!$existingUser) {
                        $newUser = \App\Models\User::create([
                            'name' => $request->name,
                            'email' => $request->email,
                            'password' => \Hash::make(\Str::random(32)), // Random password
                            'password_set_by_user' => false, // User didn't set this password
                            'phone' => $request->phone,
                            'address' => $request->billing_address,
                            'city' => $request->billing_city,
                            'postal_code' => $request->billing_postal_code,
                            'country' => $request->billing_country,
                            'packeta_point_id' => $request->packeta_point_id,
                            'packeta_point_name' => $request->packeta_point_name,
                            'packeta_point_address' => $request->packeta_point_address,
                        ]);
                        
                        // Link order to the new user
                        $order->update(['user_id' => $newUser->id]);
                        
                        // Store order ID in session for secure auto-login
                        session(['pending_order_' . $order->id => true]);
                        
                        // Auto-login the new user for seamless checkout experience
                        auth()->login($newUser);
                        
                        \Log::info('Created user account for guest order and logged in', [
                            'user_id' => $newUser->id,
                            'order_id' => $order->id
                        ]);
                    }
                } catch (\Exception $e) {
                    \Log::error('Failed to create user account for guest order: ' . $e->getMessage());
                }
            }

            // Store cart in order's admin_notes (internal use only) for potential restoration if payment fails
            $order->update([
                'admin_notes' => json_encode(['cart_backup' => $cart]),
                'customer_notes' => $request->notes, // Preserve user's actual notes
            ]);

            // DO NOT clear cart yet - wait for successful payment
            // DO NOT send email yet - wait for successful payment
            // DO NOT create invoice yet - wait for successful payment

            if ($request->payment_method === 'card') {
                \Log::info('Redirecting to payment.card', [
                    'order_id' => $order->id,
                    'user_id' => $order->user_id,
                    'is_authenticated' => auth()->check(),
                    'auth_user_id' => auth()->id(),
                ]);
                return redirect()->route('payment.card', $order);
            }

            return redirect()->route('order.confirmation', $order)
                ->with('success', 'Objednávka byla úspěšně vytvořena!');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Order creation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()
                ->withInput()
                ->with('error', 'Při vytváření objednávky došlo k chybě: ' . $e->getMessage());
        }
    }

    public function confirmation(Order $order, Request $request)
    {
        // Auto-login guest user if not already authenticated
        // SECURITY: Only auto-login if this session created the order
        if (!auth()->check() && $order->user_id) {
            if (session()->has('pending_order_' . $order->id)) {
                $user = \App\Models\User::find($order->user_id);
                if ($user) {
                    auth()->login($user);
                    // Clear the pending session after successful login
                    session()->forget('pending_order_' . $order->id);
                    
                    \Log::info('Auto-logged in user for order confirmation', [
                        'user_id' => $user->id,
                        'order_id' => $order->id,
                    ]);
                }
            } else {
                \Log::warning('Auto-login blocked - no session verification', [
                    'order_id' => $order->id,
                    'user_id' => $order->user_id,
                ]);
            }
        }
        
        // If user is authenticated, check if order belongs to them
        if (auth()->check() && $order->user_id !== auth()->id()) {
            abort(403);
        }

        // If user is not authenticated, verify they have access
        if (!auth()->check()) {
            // For guest orders without user_id, allow access
            // In production, you might want to add a token-based verification here
            if ($order->user_id !== null) {
                abort(403, 'Nemáte oprávnění zobrazit tuto stránku. Prosím přihlaste se.');
            }
        }

        // Synchronously verify payment status if returning from Stripe
        $sessionId = $request->get('session_id');
        if ($sessionId && $order->payment_status !== 'paid') {
            try {
                \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
                $session = \Stripe\Checkout\Session::retrieve($sessionId);
                
                if ($session->payment_status === 'paid' && isset($session->metadata['order_id']) && $session->metadata['order_id'] == $order->id) {
                    // Payment was successful, update order immediately
                    $order->update([
                        'payment_status' => 'paid',
                        'status' => 'processing',
                        'stripe_payment_intent_id' => $session->payment_intent ?? null,
                        'paid_at' => now(),
                    ]);
                    
                    \Log::info('Order payment status updated synchronously', [
                        'order_id' => $order->id,
                        'session_id' => $sessionId,
                    ]);
                    
                    // Reload order to get updated status
                    $order->refresh();
                    
                    // Create invoice in Fakturoid immediately after payment
                    try {
                        $fakturoidService = app(FakturoidService::class);
                        $fakturoidService->processInvoiceForOrder($order);
                        // Refresh order to get updated invoice_pdf_path
                        $order->refresh();
                        
                        \Log::info('Fakturoid invoice created synchronously', [
                            'order_id' => $order->id,
                        ]);
                    } catch (\Exception $e) {
                        \Log::error('Failed to create Fakturoid invoice synchronously', [
                            'order_id' => $order->id,
                            'error' => $e->getMessage(),
                        ]);
                        // Continue anyway - webhook will retry
                    }
                    
                    // Send order confirmation email immediately after payment
                    try {
                        $email = $order->shipping_address['email'] ?? $order->user?->email;
                        if ($email) {
                            Mail::to($email)->send(new OrderConfirmation($order));
                            
                            // Mark email as sent
                            $order->update(['confirmation_email_sent_at' => now()]);
                            
                            \Log::info('Order confirmation email sent synchronously', [
                                'order_id' => $order->id,
                                'email' => $email,
                            ]);
                        }
                    } catch (\Exception $e) {
                        \Log::error('Failed to send order confirmation email synchronously', [
                            'order_id' => $order->id,
                            'error' => $e->getMessage(),
                        ]);
                        // Continue anyway - webhook will retry
                    }
                }
            } catch (\Exception $e) {
                \Log::error('Failed to verify Stripe session synchronously', [
                    'order_id' => $order->id,
                    'session_id' => $sessionId,
                    'error' => $e->getMessage(),
                ]);
                // Continue anyway - webhook will handle it
            }
        }

        // Check if payment was cancelled
        $cancelled = request()->get('cancelled', false);
        
        // Clear cart only after successful payment (not if cancelled)
        if (!$cancelled && $order->payment_status === 'paid') {
            session()->forget('cart');
            \Log::info('Cart cleared after successful payment', [
                'order_id' => $order->id
            ]);
        }
        
        return view('checkout.confirmation', compact('order', 'cancelled'));
    }

    /**
     * Create payment session for unpaid order
     */
    public function payOrder(Order $order)
    {
        // Check if order belongs to authenticated user
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Nemáte oprávnění k této akci.');
        }

        // Check if order has unpaid status
        if ($order->payment_status !== 'unpaid') {
            return back()->with('error', 'Tato objednávka není v neuhrazeném stavu.');
        }

        try {
            $checkoutUrl = $this->stripeService->createOrderPaymentSession($order);
            
            if (!$checkoutUrl) {
                return back()->with('error', 'Nelze vytvořit platební session. Kontaktujte prosím podporu.');
            }

            return redirect($checkoutUrl);
        } catch (\Exception $e) {
            \Log::error('Failed to create payment session for order', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
            ]);

            return back()->with('error', 'Nastala chyba při vytváření platby. Zkuste to prosím později.');
        }
    }
}



