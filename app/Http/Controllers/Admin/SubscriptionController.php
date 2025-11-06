<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Services\PacketaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SubscriptionController extends Controller
{
    /**
     * Display a listing of subscriptions
     */
    public function index(Request $request)
    {
        $query = Subscription::with(['user', 'plan']);

        // Filter by status if provided
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Search by user name or email
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($userQuery) use ($search) {
                    $userQuery->where('name', 'like', "%{$search}%")
                              ->orWhere('email', 'like', "%{$search}%");
                });
            });
        }

        $subscriptions = $query->orderBy('created_at', 'desc')->paginate(20);

        $stats = [
            'total' => Subscription::count(),
            'active' => Subscription::where('status', 'active')->count(),
            'unpaid' => Subscription::where('status', 'unpaid')->count(),
            'pending' => Subscription::where('status', 'pending')->count(),
            'trialing' => Subscription::where('status', 'trialing')->count(),
            'past_due' => Subscription::where('status', 'past_due')->count(),
            'canceled' => Subscription::where('status', 'canceled')->count(),
        ];

        return view('admin.subscriptions.index', compact('subscriptions', 'stats'));
    }

    /**
     * Display the specified subscription
     */
    public function show(Subscription $subscription)
    {
        $subscription->load(['user', 'plan']);

        return view('admin.subscriptions.show', compact('subscription'));
    }

    /**
     * Update the subscription status
     */
    public function update(Request $request, Subscription $subscription)
    {
        $request->validate([
            'status' => 'required|in:active,unpaid,paused,pending,trialing,past_due,canceled,cancelled',
        ]);

        $subscription->update([
            'status' => $request->status,
        ]);

        // Update billing dates if status changes to active
        if ($request->status === 'active' && !$subscription->starts_at) {
            $subscription->update([
                'starts_at' => now(),
                'next_billing_date' => now()->addMonths($subscription->frequency_months ?? 1),
            ]);
        }

        return redirect()->route('admin.subscriptions.show', $subscription)
            ->with('success', 'Stav předplatného byl úspěšně aktualizován.');
    }

    /**
     * Update the subscription shipping address
     */
    public function updateAddress(Request $request, Subscription $subscription)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'billing_address' => 'required|string|max:255',
            'billing_city' => 'required|string|max:100',
            'billing_postal_code' => 'required|string|max:20',
            'country' => 'required|string|in:CZ,SK,PL,HU,AT,DE,RO,SI,HR,BG',
        ]);

        // Get current shipping address or create new array
        $shippingAddress = is_string($subscription->shipping_address) 
            ? json_decode($subscription->shipping_address, true) 
            : ($subscription->shipping_address ?? []);

        // Update address fields
        $shippingAddress['name'] = $validated['name'];
        $shippingAddress['email'] = $validated['email'];
        $shippingAddress['phone'] = $validated['phone'];
        $shippingAddress['billing_address'] = $validated['billing_address'];
        $shippingAddress['billing_city'] = $validated['billing_city'];
        $shippingAddress['billing_postal_code'] = $validated['billing_postal_code'];
        $shippingAddress['country'] = $validated['country'];

        // Update subscription
        $subscription->update([
            'shipping_address' => $shippingAddress,
        ]);

        Log::info('Subscription shipping address updated by admin', [
            'subscription_id' => $subscription->id,
            'admin_user_id' => auth()->id(),
        ]);

        return redirect()->route('admin.subscriptions.show', $subscription)
            ->with('success', 'Dodací adresa byla úspěšně aktualizována.');
    }

    /**
     * Cancel the subscription
     */
    public function destroy(Subscription $subscription)
    {
        $subscription->update(['status' => 'canceled']);

        return redirect()->route('admin.subscriptions.index')
            ->with('success', 'Předplatné bylo zrušeno.');
    }

    /**
     * Display subscriptions for next shipment
     */
    public function shipments(Request $request)
    {
        // Get target ship date (default: next 20th)
        $targetDate = $request->has('date') 
            ? \Carbon\Carbon::parse($request->date)
            : \App\Helpers\SubscriptionHelper::getNextShippingDate();

        // Get all subscriptions eligible for shipping consideration
        // Include 'pending' for one-time boxes that are paid but not yet active
        $allSubscriptions = Subscription::with(['user', 'plan'])
            ->whereIn('status', ['active', 'paused', 'pending'])
            ->get();

        // Filter subscriptions that should ship on target date OR were already sent on target date
        $subscriptions = $allSubscriptions->filter(function($subscription) use ($targetDate) {
            // Include if should ship on this date
            if ($subscription->shouldShipOn($targetDate)) {
                return true;
            }
            
            // Also include if already sent on this exact date
            if ($subscription->last_shipment_date && 
                $subscription->last_shipment_date->format('Y-m-d') === $targetDate->format('Y-m-d')) {
                return true;
            }
            
            return false;
        });

        // Group by frequency for stats
        $stats = [
            'total' => $subscriptions->count(),
            'one_time' => $subscriptions->where('frequency_months', 0)->count(),
            'monthly' => $subscriptions->where('frequency_months', 1)->count(),
            'bimonthly' => $subscriptions->where('frequency_months', 2)->count(),
            'quarterly' => $subscriptions->where('frequency_months', 3)->count(),
        ];

        // Get coffee usage statistics
        $coffeeUsage = [];
        $schedule = \App\Models\ShipmentSchedule::where('shipment_date', $targetDate->format('Y-m-d'))->first();
        
        if (!$schedule) {
            // Find schedule by month if exact date not found
            $schedule = \App\Models\ShipmentSchedule::getForMonth($targetDate->year, $targetDate->month);
        }
        
        if ($schedule && $schedule->hasCoffeeSlotsConfigured()) {
            $reservationService = app(\App\Services\StockReservationService::class);
            $coffeeUsage = $reservationService->getCoffeeUsageStats($schedule);
        }

        return view('admin.subscriptions.shipments', compact('subscriptions', 'targetDate', 'stats', 'coffeeUsage', 'schedule'));
    }

    /**
     * Send selected subscriptions to Packeta API
     */
    public function sendToPacketa(Request $request)
    {
        $request->validate([
            'subscription_ids' => 'required|array|min:1',
            'subscription_ids.*' => 'exists:subscriptions,id',
            'target_date' => 'nullable|date',
        ]);

        // Get target ship date (from hidden field or default)
        $targetDate = $request->has('target_date') 
            ? \Carbon\Carbon::parse($request->target_date)
            : \App\Helpers\SubscriptionHelper::getNextShippingDate();

        $packetaService = new PacketaService();
        $successCount = 0;
        $errorCount = 0;
        $errors = [];

        foreach ($request->subscription_ids as $subscriptionId) {
            $subscription = Subscription::with('user')->find($subscriptionId);

            // Skip if already sent
            if ($subscription->packeta_shipment_status === 'sent') {
                continue;
            }

            // Validate required data
            if (!$subscription->packeta_point_id) {
                $errors[] = "Předplatné #{$subscription->id}: Chybí výdejní místo Packety";
                $errorCount++;
                continue;
            }

            // Get shipping address
            $shippingAddress = is_string($subscription->shipping_address) 
                ? json_decode($subscription->shipping_address, true) 
                : $subscription->shipping_address;

            // Get configuration for weight calculation
            $config = is_string($subscription->configuration) 
                ? json_decode($subscription->configuration, true) 
                : $subscription->configuration;
            
            $amount = $config['amount'] ?? 1;
            // Assume each package weighs approximately 0.25 kg
            $weight = $amount * 0.25;

            // Prepare data for Packeta
            $name = $shippingAddress['name'] ?? $subscription->user->name ?? '';
            $nameParts = explode(' ', $name, 2);
            
            // Determine currency based on shipping country
            $shippingCountry = $shippingAddress['country'] ?? $subscription->user->country ?? 'CZ';
            $currency = $this->getCurrencyForCountry($shippingCountry);
            
            // Format phone number - remove spaces and ensure it has country code
            $phone = $shippingAddress['phone'] ?? $subscription->user->phone ?? '';
            $phone = preg_replace('/\s+/', '', $phone); // Remove spaces
            if (!empty($phone) && !str_starts_with($phone, '+')) {
                // Add country code if missing
                $phone = $this->addCountryCodeToPhone($phone, $shippingCountry);
            }
            
            // Convert value to target currency if needed
            $value = $subscription->configured_price ?? 500;
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
            
            // Get Packeta data - prioritize shipping_address JSON (consistent with Orders)
            // Fall back to direct columns for backward compatibility
            $packetaPointId = $shippingAddress['packeta_point_id'] ?? $subscription->packeta_point_id;
            $carrierId = $shippingAddress['carrier_id'] ?? $subscription->carrier_id ?? null;
            $carrierPickupPoint = $shippingAddress['carrier_pickup_point'] ?? $subscription->carrier_pickup_point ?? null;
            
            $packetData = [
                'name' => $nameParts[0] ?? $name,
                'surname' => $nameParts[1] ?? '',
                'email' => $shippingAddress['email'] ?? $subscription->user->email ?? '',
                'phone' => $phone,
                'packeta_point_id' => $packetaPointId,
                'carrier_id' => $carrierId,
                'carrier_pickup_point' => $carrierPickupPoint,
                'value' => $value,
                'weight' => $weight,
                'order_number' => ($subscription->subscription_number ?? 'SUB-' . $subscription->id) . '-' . $targetDate->format('m'),
                'note' => $subscription->delivery_notes ?? null,
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
                    
                    // Update subscription with Packeta data
                    // Use target date for last_shipment_date to keep proper shipping schedule
                    $updateData = [
                        'packeta_packet_id' => $result['id'],
                        'packeta_tracking_url' => $trackingUrl,
                        'packeta_shipment_status' => 'sent',
                        'packeta_sent_at' => now(),
                        'last_shipment_date' => $targetDate,
                    ];
                    
                    // For one-time boxes, mark as completed after shipping
                    if ($subscription->frequency_months == 0) {
                        $updateData['status'] = 'completed';
                        $updateData['canceled_at'] = now();
                    }
                    
                    $subscription->update($updateData);

                    // Send subscription shipped email
                    try {
                        $email = $subscription->shipping_address['email'] ?? $subscription->user->email ?? null;
                        if ($email) {
                            \Mail::to($email)->send(new \App\Mail\SubscriptionBoxShipped($subscription));
                            Log::info('Subscription shipped email sent', [
                                'subscription_id' => $subscription->id,
                                'email' => $email,
                            ]);
                        } else {
                            Log::warning('No email found for subscription shipped notification', [
                                'subscription_id' => $subscription->id
                            ]);
                        }
                    } catch (\Exception $e) {
                        Log::error('Failed to send subscription shipped email', [
                            'subscription_id' => $subscription->id,
                            'error' => $e->getMessage(),
                        ]);
                        // Don't fail the whole process if email fails
                    }

                    $successCount++;
                    Log::info("Zásilka odeslána do Packety", [
                        'subscription_id' => $subscription->id,
                        'packet_id' => $result['id']
                    ]);
                } else {
                    $errors[] = "Předplatné #{$subscription->id}: Nepodařilo se vytvořit zásilku v Packeta API";
                    $errorCount++;
                    Log::error("Chyba při vytváření zásilky v Packeta", [
                        'subscription_id' => $subscription->id,
                        'response' => $result
                    ]);
                }
            } catch (\Exception $e) {
                $errors[] = "Předplatné #{$subscription->id}: " . $e->getMessage();
                $errorCount++;
                Log::error("Exception při odesílání do Packety", [
                    'subscription_id' => $subscription->id,
                    'error' => $e->getMessage()
                ]);
            }
        }

        // Prepare success message
        $message = '';
        if ($successCount > 0) {
            $message .= "Úspěšně odesláno {$successCount} " . 
                ($successCount === 1 ? 'zásilka' : ($successCount < 5 ? 'zásilky' : 'zásilek')) . 
                " do systému Packeta. ";
        }
        if ($errorCount > 0) {
            $message .= "{$errorCount} " . 
                ($errorCount === 1 ? 'zásilka selhala' : ($errorCount < 5 ? 'zásilky selhaly' : 'zásilek selhalo')) . ". ";
        }

        if ($errorCount > 0 && count($errors) > 0) {
            return redirect()->route('admin.subscriptions.shipments')
                ->with('warning', $message)
                ->with('errors', $errors);
        }

        return redirect()->route('admin.subscriptions.shipments')
            ->with('success', $message);
    }

    /**
     * Send "Box Preparing" emails to selected subscriptions
     */
    public function sendPreparingEmails(Request $request)
    {
        $request->validate([
            'subscription_ids' => 'required|array|min:1',
            'subscription_ids.*' => 'exists:subscriptions,id',
        ]);

        $sentCount = 0;
        $failedCount = 0;
        $errors = [];

        foreach ($request->subscription_ids as $subscriptionId) {
            $subscription = Subscription::with('user')->find($subscriptionId);

            if (!$subscription) {
                $errors[] = "Předplatné #{$subscriptionId}: Nenalezeno";
                $failedCount++;
                continue;
            }

            try {
                // Get email from shipping address or user
                $email = $subscription->shipping_address['email'] ?? $subscription->user->email ?? null;
                
                if (!$email) {
                    $errors[] = "Předplatné #{$subscription->id}: Chybí emailová adresa";
                    $failedCount++;
                    continue;
                }

                // Send "Box Preparing" email
                \Mail::to($email)->send(new \App\Mail\SubscriptionBoxPreparing($subscription));
                
                Log::info('Subscription box preparing email sent', [
                    'subscription_id' => $subscription->id,
                    'email' => $email,
                ]);

                $sentCount++;
            } catch (\Exception $e) {
                $errors[] = "Předplatné #{$subscription->id}: " . $e->getMessage();
                $failedCount++;
                Log::error('Failed to send subscription box preparing email', [
                    'subscription_id' => $subscription->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        // Prepare success message
        $message = '';
        if ($sentCount > 0) {
            $message .= "Úspěšně odesláno {$sentCount} " . 
                ($sentCount === 1 ? 'email' : ($sentCount < 5 ? 'emaily' : 'emailů')) . ". ";
        }
        if ($failedCount > 0) {
            $message .= "{$failedCount} " . 
                ($failedCount === 1 ? 'email selhal' : ($failedCount < 5 ? 'emaily selhaly' : 'emailů selhalo')) . ". ";
        }

        if ($failedCount > 0 && count($errors) > 0) {
            return redirect()->route('admin.subscriptions.shipments')
                ->with('warning', $message)
                ->with('errors', $errors);
        }

        return redirect()->route('admin.subscriptions.shipments')
            ->with('success', $message);
    }

    /**
     * Get Packeta tracking URL from packet ID
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
}
