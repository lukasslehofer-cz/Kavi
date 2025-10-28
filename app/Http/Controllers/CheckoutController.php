<?php

namespace App\Http\Controllers;

use App\Mail\OrderConfirmation;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Services\FakturoidService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class CheckoutController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
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
        $totalWithVat = $subtotal + $shipping;
        
        // Calculate VAT (all prices include 21% VAT)
        $totalWithoutVat = round($totalWithVat / 1.21, 2);
        $vat = round($totalWithVat - $totalWithoutVat, 2);

        return view('checkout.index', compact('cartItems', 'subtotal', 'shipping', 'totalWithVat', 'totalWithoutVat', 'vat'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string',
            'billing_address' => 'required|string',
            'billing_city' => 'required|string',
            'billing_postal_code' => 'required|string',
            'packeta_point_id' => 'required|string',
            'packeta_point_name' => 'required|string',
            'packeta_point_address' => 'nullable|string',
            'payment_method' => 'required|in:card,transfer',
        ]);

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
            $totalWithVat = $subtotal + $shipping;
            
            // Calculate VAT (all prices include 21% VAT)
            $totalWithoutVat = round($totalWithVat / 1.21, 2);
            $tax = round($totalWithVat - $totalWithoutVat, 2);

            $order = Order::create([
                'order_number' => Order::generateOrderNumber(),
                'user_id' => auth()->id(),
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
                    'packeta_point_id' => $request->packeta_point_id,
                    'packeta_point_name' => $request->packeta_point_name,
                    'packeta_point_address' => $request->packeta_point_address,
                    'country' => 'CZ',
                ],
                'customer_notes' => $request->notes,
            ]);

            // Save contact info, billing address and Packeta pickup point to user for future use
            auth()->user()->update([
                'phone' => $request->phone,
                'address' => $request->billing_address,
                'city' => $request->billing_city,
                'postal_code' => $request->billing_postal_code,
                'packeta_point_id' => $request->packeta_point_id,
                'packeta_point_name' => $request->packeta_point_name,
                'packeta_point_address' => $request->packeta_point_address,
            ]);

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

            DB::commit();

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

            // Clear cart
            session()->forget('cart');

            if ($request->payment_method === 'card') {
                return redirect()->route('payment.card', $order);
            }

            return redirect()->route('order.confirmation', $order)
                ->with('success', 'Objednávka byla úspěšně vytvořena!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Při vytváření objednávky došlo k chybě. Zkuste to prosím znovu.');
        }
    }

    public function confirmation(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        return view('checkout.confirmation', compact('order'));
    }
}



