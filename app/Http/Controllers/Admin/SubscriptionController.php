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
            'status' => 'required|in:active,pending,trialing,past_due,canceled',
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
        $allSubscriptions = Subscription::with(['user', 'plan'])
            ->whereIn('status', ['active', 'paused'])
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
            'monthly' => $subscriptions->where('frequency_months', 1)->count(),
            'bimonthly' => $subscriptions->where('frequency_months', 2)->count(),
            'quarterly' => $subscriptions->where('frequency_months', 3)->count(),
        ];

        return view('admin.subscriptions.shipments', compact('subscriptions', 'targetDate', 'stats'));
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
            
            $packetData = [
                'name' => $nameParts[0] ?? $name,
                'surname' => $nameParts[1] ?? '',
                'email' => $shippingAddress['email'] ?? $subscription->user->email ?? '',
                'phone' => $shippingAddress['phone'] ?? $subscription->user->phone ?? '',
                'packeta_point_id' => $subscription->packeta_point_id,
                'value' => $subscription->configured_price ?? 500,
                'weight' => $weight,
                'order_number' => 'SUB-' . $subscription->id,
                'note' => $subscription->delivery_notes ?? null,
            ];

            try {
                // Send to Packeta API
                $result = $packetaService->createPacket($packetData);

                if ($result && isset($result['id'])) {
                    // Update subscription with Packeta data
                    // Use target date for last_shipment_date to keep proper shipping schedule
                    $subscription->update([
                        'packeta_packet_id' => $result['id'],
                        'packeta_shipment_status' => 'sent',
                        'packeta_sent_at' => now(),
                        'last_shipment_date' => $targetDate,
                    ]);

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
}
