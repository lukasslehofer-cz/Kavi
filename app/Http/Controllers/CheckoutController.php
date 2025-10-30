<?php

namespace App\Http\Controllers;

use App\Mail\OrderConfirmation;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Services\CouponService;
use App\Services\FakturoidService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class CheckoutController extends Controller
{
    // No auth middleware - supports guest checkout

    public function __construct(private CouponService $couponService)
    {
    }

    public function index()
    {
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

        $shipping = $subtotal >= 1000 ? 0 : 99; // Free shipping over 1000 CZK
        
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

        return view('checkout.index', compact('cartItems', 'subtotal', 'shipping', 'totalWithVat', 'totalWithoutVat', 'vat', 'appliedCoupon', 'discount'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => auth()->check() ? 'required|string|max:20' : 'nullable|string|max:20',
            'billing_address' => 'required|string',
            'billing_city' => 'required|string',
            'billing_postal_code' => 'required|string',
            'billing_country' => 'required|string|size:2',
            'packeta_point_id' => 'required|string',
            'packeta_point_name' => 'required|string',
            'packeta_point_address' => 'nullable|string',
            'payment_method' => 'required|in:card,transfer',
            'coupon_code' => 'nullable|string',
        ]);

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

            $shipping = $subtotal >= 1000 ? 0 : 99;
            
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
                    $totalWithVat = $couponResult['total'];
                    $totalWithoutVat = $couponResult['total_without_vat'];
                    $tax = $couponResult['vat'];
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

            $order = Order::create([
                'order_number' => Order::generateOrderNumber(),
                'user_id' => auth()->id() ?? null,
                'coupon_id' => $coupon?->id,
                'coupon_code' => $coupon?->code,
                'discount_amount' => $discount,
                'subtotal' => $subtotal,
                'shipping' => $shipping,
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
                    'packeta_point_id' => $request->packeta_point_id,
                    'packeta_point_name' => $request->packeta_point_name,
                    'packeta_point_address' => $request->packeta_point_address,
                ],
                'customer_notes' => $request->notes,
            ]);

            // Save contact info, billing address and Packeta pickup point to user for future use (if authenticated)
            if (auth()->check()) {
                auth()->user()->update([
                    'phone' => $request->phone ?? auth()->user()->phone,
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
                            'phone' => $request->phone ?? null,
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

            // Generate invoice from Fakturoid (must happen BEFORE sending email)
            try {
                $fakturoidService = app(FakturoidService::class);
                $fakturoidService->processInvoiceForOrder($order);
                // Refresh order to get updated invoice_pdf_path
                $order->refresh();
            } catch (\Exception $e) {
                // Log error but don't fail the order
                \Log::error('Failed to generate Fakturoid invoice: ' . $e->getMessage());
            }

            // Send order confirmation email (with invoice attachment if available)
            try {
                Mail::to($request->email)->send(new OrderConfirmation($order));
            } catch (\Exception $e) {
                // Log error but don't fail the order
                \Log::error('Failed to send order confirmation email: ' . $e->getMessage());
            }

            // Clear cart and coupon only after successful order creation
            session()->forget('cart');

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

    public function confirmation(Order $order)
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

        // Check if payment was cancelled
        $cancelled = request()->get('cancelled', false);
        
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



