<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\PacketaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
        $order->load(['user', 'items']);

        return view('admin.orders.show', compact('order'));
    }

    /**
     * Update the order status
     */
    public function update(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,submitted,shipped,delivered,cancelled',
        ]);

        $order->update([
            'status' => $request->status,
        ]);

        // If status is delivered, mark payment as paid
        if ($request->status === 'delivered' && $order->payment_status !== 'paid') {
            $order->update(['payment_status' => 'paid']);
        }

        return redirect()->route('admin.orders.show', $order)
            ->with('success', 'Stav objednávky byl úspěšně aktualizován.');
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

            // Calculate total weight from order items
            $weight = 0;
            foreach ($order->items as $item) {
                // Assume each product weighs approximately 0.25 kg
                $weight += ($item->quantity ?? 1) * 0.25;
            }
            // Minimum weight 0.1 kg
            $weight = max($weight, 0.1);

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
                'order_number' => $order->order_number,
                'note' => $order->customer_notes ?? null,
                'currency' => $currency,
                'country' => $shippingCountry,
                'adult_content' => false, // Set to true if selling alcohol/tobacco
            ];

            try {
                // Send to Packeta API
                $result = $packetaService->createPacket($packetData);

                if ($result && isset($result['id'])) {
                    // Update order with Packeta data
                    $order->update([
                        'packeta_packet_id' => $result['id'],
                        'packeta_shipment_status' => 'submitted',
                        'packeta_sent_at' => now(),
                        'packeta_point_id' => $packetaPointId,
                        'packeta_point_name' => $shippingAddress['packeta_point_name'] ?? null,
                        'packeta_point_address' => $shippingAddress['packeta_point_address'] ?? null,
                        'status' => 'submitted',
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
}
