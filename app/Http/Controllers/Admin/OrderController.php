<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\DigitalProductDelivery;
use App\Models\Order;
use App\Services\PacketaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    /**
     * Display a listing of orders
     */
    public function index(Request $request)
    {
        $query = Order::with(['user', 'items']);

        // Filter by status if provided
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Search by order number or customer name
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate(20);

        $stats = [
            'total' => Order::count(),
            'unpaid' => Order::where('payment_status', 'unpaid')->count(),
            'pending' => Order::where('status', 'pending')->count(),
            'processing' => Order::where('status', 'processing')->count(),
            'submitted' => Order::where('status', 'submitted')->count(),
            'shipped' => Order::where('status', 'shipped')->count(),
            'delivered' => Order::where('status', 'delivered')->count(),
        ];

        return view('admin.orders.index', compact('orders', 'stats'));
    }

    /**
     * Display the specified order
     */
    public function show(Order $order)
    {
        $order->load(['user', 'items.product.roastery']);

        return view('admin.orders.show', compact('order'));
    }

    /**
     * Update the order status
     */
    public function update(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,submitted,shipped,delivered,returned,cancelled',
        ]);

        $updateData = [
            'status' => $request->status,
        ];

        // Set shipped_at timestamp when status changes to shipped
        if ($request->status === 'shipped' && $order->status !== 'shipped' && !$order->shipped_at) {
            $updateData['shipped_at'] = now();
        }

        // Set delivered_at timestamp when status changes to delivered
        if ($request->status === 'delivered' && $order->status !== 'delivered' && !$order->delivered_at) {
            $updateData['delivered_at'] = now();
            
            // Also mark payment as paid when delivered
            if ($order->payment_status !== 'paid') {
                $updateData['payment_status'] = 'paid';
            }
        }

        $order->update($updateData);

        return redirect()->route('admin.orders.show', $order)
            ->with('success', 'Stav objednávky byl úspěšně aktualizován.');
    }

    /**
     * Update the shipping address of an order
     */
    public function updateAddress(Request $request, Order $order)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:50',
            'billing_address' => 'required|string|max:255',
            'billing_city' => 'required|string|max:100',
            'billing_postal_code' => 'required|string|max:20',
            'country' => 'required|string|max:2',
        ]);

        // Get current shipping_address and update it
        $shippingAddress = $order->shipping_address ?? [];
        $shippingAddress['name'] = $validated['name'];
        $shippingAddress['email'] = $validated['email'];
        $shippingAddress['phone'] = $validated['phone'];
        $shippingAddress['billing_address'] = $validated['billing_address'];
        $shippingAddress['billing_city'] = $validated['billing_city'];
        $shippingAddress['billing_postal_code'] = $validated['billing_postal_code'];
        $shippingAddress['country'] = $validated['country'];

        $order->update([
            'shipping_address' => $shippingAddress,
        ]);

        return redirect()->route('admin.orders.show', $order)
            ->with('success', 'Doručovací adresa byla úspěšně aktualizována.');
    }

    /**
     * Delete/cancel the order
     */
    public function destroy(Order $order)
    {
        // Only allow deletion of pending orders
        if ($order->status !== 'pending') {
            return redirect()->route('admin.orders.show', $order)
                ->with('error', 'Lze zrušit pouze objednávky ve stavu "Čeká".');
        }

        // Restore stock for each order item
        $order->load('items.product');
        foreach ($order->items as $item) {
            if ($item->product) {
                $item->product->increment('stock', $item->quantity);
            }
        }

        $order->update(['status' => 'cancelled']);

        return redirect()->route('admin.orders.index')
            ->with('success', 'Objednávka byla zrušena.');
    }

    /**
     * Send selected orders to Packeta API
     */
    public function sendToPacketa(Request $request)
    {
        $request->validate([
            'order_ids' => 'required|array|min:1',
            'order_ids.*' => 'exists:orders,id',
        ]);

        $packetaService = new PacketaService();
        $successCount = 0;
        $errorCount = 0;
        $errors = [];

        foreach ($request->order_ids as $orderId) {
            $order = Order::with(['user', 'items'])->find($orderId);

            // Skip if already sent
            if ($order->packeta_shipment_status === 'submitted') {
                continue;
            }

            // Get shipping address
            $shippingAddress = is_string($order->shipping_address) 
                ? json_decode($order->shipping_address, true) 
                : $order->shipping_address;

            // Validate required data
            $packetaPointId = $shippingAddress['packeta_point_id'] ?? $order->packeta_point_id ?? null;
            
            if (!$packetaPointId) {
                $errors[] = "Objednávka {$order->order_number}: Chybí výdejní místo Packety";
                $errorCount++;
                continue;
            }

            // Get dimensions and weight (custom or calculated)
            $weight = $order->getPackageWeight();
            $packageSize = $order->getPackageDimensions();

            // Prepare customer name
            $name = $shippingAddress['name'] ?? $order->user->name ?? 'Zákazník';
            $nameParts = explode(' ', $name, 2);
            
            // Determine currency based on shipping country
            $shippingCountry = $shippingAddress['billing_country'] ?? $shippingAddress['country'] ?? $order->shipping_country ?? 'CZ';
            $currency = $this->getCurrencyForCountry($shippingCountry);
            
            // Format phone number - remove spaces and ensure it has country code
            $phone = $shippingAddress['phone'] ?? $order->user->phone ?? '';
            $phone = preg_replace('/\s+/', '', $phone); // Remove spaces
            if (!empty($phone) && !str_starts_with($phone, '+')) {
                // Add country code if missing
                $phone = $this->addCountryCodeToPhone($phone, $shippingCountry);
            }
            
            // Convert value to target currency if needed
            $value = $order->total ?? 500;
            if ($currency !== 'CZK') {
                // Simple conversion: CZK to EUR (~25:1), CZK to USD (~23:1)
                $value = match($currency) {
                    'EUR' => round($value / 25, 2),
                    'USD' => round($value / 23, 2),
                    default => $value
                };
                // Cap at reasonable insurance value for international shipments
                $value = min($value, 100);
            }
            
            $packetData = [
                'name' => $nameParts[0] ?? $name,
                'surname' => $nameParts[1] ?? '',
                'email' => $shippingAddress['email'] ?? $order->user->email ?? '',
                'phone' => $phone,
                'packeta_point_id' => $packetaPointId,
                'carrier_id' => $shippingAddress['carrier_id'] ?? null,
                'carrier_pickup_point' => $shippingAddress['carrier_pickup_point'] ?? null,
                'value' => $value,
                'weight' => $weight,
                'size' => $packageSize, // Package dimensions
                'order_number' => $order->order_number,
                'note' => null,
                'currency' => $currency,
                'country' => $shippingCountry,
                'adult_content' => false, // Set to true if selling alcohol/tobacco
            ];

            try {
                // Send to Packeta API
                $result = $packetaService->createPacket($packetData);

                if ($result && isset($result['id'])) {
                    // Get tracking URL from Packeta
                    $trackingUrl = $this->getPacketaTrackingUrl($result['id']);
                    
                    // Update order with Packeta data
                    $order->update([
                        'packeta_packet_id' => $result['id'],
                        'packeta_tracking_url' => $trackingUrl,
                        'packeta_shipment_status' => 'submitted',
                        'packeta_sent_at' => now(),
                        'packeta_point_id' => $packetaPointId,
                        'packeta_point_name' => $shippingAddress['packeta_point_name'] ?? null,
                        'packeta_point_address' => $shippingAddress['packeta_point_address'] ?? null,
                        'status' => 'submitted',
                        'shipped_at' => now(), // Mark as shipped when sent to Packeta
                    ]);

                    $successCount++;
                    Log::info("Objednávka odeslána do Packety", [
                        'order_id' => $order->id,
                        'order_number' => $order->order_number,
                        'packet_id' => $result['id']
                    ]);
                } else {
                    $errors[] = "Objednávka {$order->order_number}: Nepodařilo se vytvořit zásilku v Packeta API";
                    $errorCount++;
                    Log::error("Chyba při vytváření zásilky v Packeta", [
                        'order_id' => $order->id,
                        'order_number' => $order->order_number,
                        'response' => $result
                    ]);
                }
            } catch (\Exception $e) {
                $errors[] = "Objednávka {$order->order_number}: " . $e->getMessage();
                $errorCount++;
                Log::error("Exception při odesílání objednávky do Packety", [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                    'error' => $e->getMessage()
                ]);
            }
        }

        // Prepare success message
        $message = '';
        if ($successCount > 0) {
            $message .= "Úspěšně odesláno {$successCount} " . 
                ($successCount === 1 ? 'objednávka' : ($successCount < 5 ? 'objednávky' : 'objednávek')) . 
                " do systému Packeta. ";
        }
        if ($errorCount > 0) {
            $message .= "{$errorCount} " . 
                ($errorCount === 1 ? 'objednávka selhala' : ($errorCount < 5 ? 'objednávky selhaly' : 'objednávek selhalo')) . ". ";
        }

        if ($errorCount > 0 && count($errors) > 0) {
            return redirect()->route('admin.orders.index')
                ->with('warning', $message)
                ->with('errors', $errors);
        }

        return redirect()->route('admin.orders.index')
            ->with('success', $message);
    }

    /**
     * Get Packeta tracking URL for a packet
     */
    private function getPacketaTrackingUrl(string $packetId): string
    {
        // Packeta tracking URL format
        return "https://tracking.packeta.com/cs/?id={$packetId}";
    }

    /**
     * Get currency code for a given country
     */
    private function getCurrencyForCountry(string $countryCode): string
    {
        $currencyMap = [
            'CZ' => 'CZK',
            'SK' => 'EUR',
            'PL' => 'PLN',
            'HU' => 'HUF',
            'RO' => 'RON',
            'AT' => 'EUR',
            'DE' => 'EUR',
            'SI' => 'EUR',
            'HR' => 'EUR',
            'BG' => 'BGN',
        ];

        return $currencyMap[strtoupper($countryCode)] ?? 'EUR';
    }

    /**
     * Add country code to phone number if missing
     */
    private function addCountryCodeToPhone(string $phone, string $countryCode): string
    {
        $countryCodeMap = [
            'CZ' => '+420',
            'SK' => '+421',
            'PL' => '+48',
            'HU' => '+36',
            'RO' => '+40',
            'AT' => '+43',
            'DE' => '+49',
            'SI' => '+386',
            'HR' => '+385',
            'BG' => '+359',
        ];

        $prefix = $countryCodeMap[strtoupper($countryCode)] ?? '+420';
        
        // Remove leading zero if present
        $phone = ltrim($phone, '0');

        return $prefix . $phone;
    }

    /**
     * Send digital product (voucher PDF) to customer
     */
    public function sendDigitalDelivery(Request $request, Order $order)
    {
        $request->validate([
            'voucher_pdf' => 'required|file|mimes:pdf|max:10240',
        ]);

        $path = $request->file('voucher_pdf')->store("vouchers/{$order->id}");

        $order->update(['digital_delivery_pdf_path' => $path]);

        $recipientEmail = $order->shipping_address['email'] ?? $order->user->email ?? null;

        if (!$recipientEmail) {
            return redirect()->route('admin.orders.show', $order)
                ->with('error', 'Nelze odeslat: objednávka nemá emailovou adresu.');
        }

        try {
            Mail::to($recipientEmail)->send(new DigitalProductDelivery($order, $path));

            // Only mark as delivered if order contains only digital products
            // Mixed orders wait for physical shipment via Packeta
            if ($order->containsOnlyDigitalProducts()) {
                $order->markAsDelivered();
            }

            Log::info('Digital product delivery sent', [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'recipient' => $recipientEmail,
                'admin_user_id' => auth()->id(),
                'only_digital' => $order->containsOnlyDigitalProducts(),
            ]);

            $message = $order->containsOnlyDigitalProducts()
                ? 'Voucher byl úspěšně odeslán a objednávka označena jako doručená.'
                : 'Voucher byl úspěšně odeslán. Objednávka obsahuje i fyzické produkty — stav se změní po odeslání přes Packetu.';

            return redirect()->route('admin.orders.show', $order)
                ->with('success', $message);
        } catch (\Exception $e) {
            Log::error('Failed to send digital product delivery', [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'error' => $e->getMessage(),
            ]);

            return redirect()->route('admin.orders.show', $order)
                ->with('error', 'Nepodařilo se odeslat email: ' . $e->getMessage());
        }
    }

    /**
     * Update package dimensions for an order
     */
    public function updatePackageDimensions(Request $request, Order $order)
    {
        $validated = $request->validate([
            'package_weight' => 'required|numeric|min:0.1|max:30',
            'package_length' => 'required|numeric|min:1|max:200',
            'package_width' => 'required|numeric|min:1|max:200',
            'package_height' => 'required|numeric|min:1|max:200',
            'admin_notes' => 'nullable|string|max:500',
        ]);

        // Only allow editing if not yet sent to Packeta
        if (!$order->canEditPackageDimensions()) {
            return response()->json([
                'success' => false,
                'message' => 'Lze editovat pouze objednávku, která ještě nebyla odeslána do Packety.',
            ], 400);
        }

        $order->update([
            'package_weight' => $validated['package_weight'],
            'package_length' => $validated['package_length'],
            'package_width' => $validated['package_width'],
            'package_height' => $validated['package_height'],
            'admin_notes' => $validated['admin_notes'] ?? $order->admin_notes,
        ]);

        \Log::info('Order package dimensions updated by admin', [
            'order_id' => $order->id,
            'order_number' => $order->order_number,
            'admin_user_id' => auth()->id(),
            'dimensions' => [
                'weight' => $validated['package_weight'],
                'length' => $validated['package_length'],
                'width' => $validated['package_width'],
                'height' => $validated['package_height'],
            ],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Rozměry balíku byly úspěšně aktualizovány.',
            'order' => $order->fresh(),
        ]);
    }
}
