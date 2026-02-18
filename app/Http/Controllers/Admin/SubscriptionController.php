<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\ShipmentSchedule;
use App\Services\PacketaService;
use App\Services\StockReservationService;
use App\Services\SubscriptionShipmentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class SubscriptionController extends Controller
{
    /**
     * Display a listing of subscriptions
     */
    public function index(Request $request)
    {
        $query = Subscription::with(['user', 'plan', 'shipments' => function($q) {
            $q->where('status', 'pending')->orderBy('shipment_date', 'asc')->limit(1);
        }]);

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
            'trialing' => Subscription::where('status', 'trialing')->count(),
            'paused' => Subscription::where('status', 'paused')->count(),
            'complimentary' => Subscription::where('status', 'complimentary')->count(),
            'cancelled' => Subscription::where('status', 'cancelled')->count(),
        ];

        return view('admin.subscriptions.index', compact('subscriptions', 'stats'));
    }

    /**
     * Display the specified subscription
     */
    public function show(Subscription $subscription)
    {
        $subscription->load(['user', 'plan', 'shipments' => function($query) {
            $query->with('payment')->orderBy('shipment_date', 'desc');
        }]);

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
     * Cancel the subscription (admin-triggered)
     * Supports both standard cancellation (at period end) and immediate cancellation
     */
    public function destroy(Request $request, Subscription $subscription)
    {
        // Validate cancellation type
        $validated = $request->validate([
            'cancel_type' => 'required|in:standard,immediate',
        ]);

        $cancelType = $validated['cancel_type'];

        try {
            if ($cancelType === 'immediate') {
                // Immediate cancellation - cancel right now
                $subscription->update([
                    'status' => 'cancelled',
                    'ends_at' => now(),
                ]);

                Log::info('Admin immediately cancelled subscription', [
                    'subscription_id' => $subscription->id,
                    'admin_user_id' => auth()->id(),
                ]);

                $emailSubject = 'okamžité zrušení';
            } else {
                // Standard cancellation - let it run until the end of paid period
                // Use the model's cancel() method which sets status and ends_at
                $subscription->cancel();

                Log::info('Admin cancelled subscription at period end', [
                    'subscription_id' => $subscription->id,
                    'admin_user_id' => auth()->id(),
                ]);

                $emailSubject = 'standardní zrušení';
            }

            // Send cancellation email to the user
            try {
                $email = $subscription->shipping_address['email'] ?? null;
                if (!$email && $subscription->user) {
                    $email = $subscription->user->email;
                }
                
                if ($email) {
                    \Mail::to($email)->send(new \App\Mail\SubscriptionCancelled($subscription));
                    
                    Log::info('Cancellation email sent', [
                        'subscription_id' => $subscription->id,
                        'email' => $email,
                    ]);
                }
            } catch (\Exception $e) {
                Log::error('Failed to send SubscriptionCancelled email', [
                    'subscription_id' => $subscription->id,
                    'error' => $e->getMessage(),
                ]);
                // Don't fail the cancellation if email fails
            }

            $successMessage = $cancelType === 'immediate' 
                ? 'Předplatné bylo okamžitě zrušeno a uživatel byl informován emailem.'
                : 'Předplatné bylo zrušeno. Doběhne zaplacené období a uživatel byl informován emailem.';

            return redirect()->route('admin.subscriptions.show', $subscription)
                ->with('success', $successMessage);

        } catch (\Exception $e) {
            Log::error('Failed to cancel subscription', [
                'subscription_id' => $subscription->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->route('admin.subscriptions.show', $subscription)
                ->with('error', 'Nepodařilo se zrušit předplatné. Zkuste to prosím znovu.');
        }
    }

    /**
     * Display subscriptions for next shipment
     */
    public function shipments(Request $request)
    {
        $shipmentService = app(SubscriptionShipmentService::class);
        
        // Get target ship date (default: next 20th)
        $targetDate = $request->has('date') 
            ? \Carbon\Carbon::parse($request->date)
            : \App\Helpers\SubscriptionHelper::getNextShippingDate();

        // Get billing date for this shipment month (used for pause filtering)
        $billingDate = \App\Models\ShipmentSchedule::getBillingDateForMonth($targetDate->year, $targetDate->month);
        
        // Get subscriptions with pending shipments for this date
        // Include: shipments with payment (already paid) OR non-paused subscriptions
        $pendingShipments = \App\Models\SubscriptionShipment::with(['subscription.user', 'subscription.plan', 'payment'])
            ->whereDate('shipment_date', $targetDate->toDateString())
            ->whereIn('status', ['pending', 'sent'])
            ->where(function($q) use ($billingDate) {
                // 1. Shipment has payment (customer already paid) - always show
                $q->whereNotNull('subscription_payment_id')
                // 2. OR subscription meets pause criteria
                ->orWhereHas('subscription', function($q2) use ($billingDate) {
                    $q2->where(function($q3) use ($billingDate) {
                        $q3->where('status', '!=', 'paused')
                           ->orWhereNull('paused_until_date')
                           ->orWhere('paused_until_date', '<=', $billingDate);
                    });
                });
            })
            ->get();
        
        $subscriptionIds = $pendingShipments->pluck('subscription_id')->unique();
        
        // Also get subscriptions that SHOULD ship on this date but don't have a record yet
        // Exclude paused subscriptions with active pause (billing_date < paused_until_date)
        $allSubscriptions = Subscription::with(['user', 'plan'])
            ->whereIn('status', ['active', 'paused', 'pending', 'cancelled', 'complimentary'])
            ->whereNotIn('id', $subscriptionIds)
            ->where(function($q) use ($billingDate) {
                // Include if: not paused OR no pause date OR billing_date >= paused_until_date
                $q->where('status', '!=', 'paused')
                  ->orWhereNull('paused_until_date')
                  ->orWhere('paused_until_date', '<=', $billingDate);
            })
            ->get();

        // Filter subscriptions that should ship on target date
        $newSubscriptions = $allSubscriptions->filter(function($subscription) use ($targetDate, $shipmentService) {
            return $shipmentService->shouldShipOn($subscription, $targetDate) 
                || ($subscription->last_shipment_date && 
                    $subscription->last_shipment_date->format('Y-m-d') === $targetDate->format('Y-m-d'));
        });

        // Determine if this is the current shipment period or a future preview
        $nextShippingDate = \App\Helpers\SubscriptionHelper::getNextShippingDate();
        $isCurrentPeriod = $targetDate->lte($nextShippingDate);

        // Create pending shipments ONLY for current period (not future previews)
        if ($isCurrentPeriod) {
            foreach ($newSubscriptions as $subscription) {
                $shipmentService->getOrCreateShipment($subscription, $targetDate);
            }
        }
        
        // Combine all subscription IDs
        $allSubscriptionIds = $subscriptionIds->merge($newSubscriptions->pluck('id'))->unique();
        
        // Reload subscriptions with shipments relation
        $subscriptions = Subscription::with(['user', 'plan', 'shipments' => function($query) use ($targetDate) {
            $query->forDate($targetDate)->with('payment');
        }])->whereIn('id', $allSubscriptionIds)->get();

        // Calculate statistics for current month using new method
        $stats = $this->calculateShipmentStats($targetDate);

        // Calculate statistics for next month's 20th
        $nextMonthDate = $this->getNextMonthShipmentDate($targetDate);
        $nextMonthStats = $this->calculateShipmentStats($nextMonthDate);

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

        return view('admin.subscriptions.shipments', compact('subscriptions', 'targetDate', 'stats', 'nextMonthStats', 'nextMonthDate', 'coffeeUsage', 'schedule'));
    }

    /**
     * Calculate shipment statistics for a given date
     *
     * @param \Carbon\Carbon $targetDate The date to calculate statistics for
     * @return array Statistics array with frequency breakdowns and box sizes
     */
    private function calculateShipmentStats(\Carbon\Carbon $targetDate): array
    {
        $shipmentService = app(SubscriptionShipmentService::class);

        // Get billing date for this shipment month
        $billingDate = ShipmentSchedule::getBillingDateForMonth($targetDate->year, $targetDate->month);

        // Get subscriptions with pending shipments (copy logic from lines 234-249)
        $pendingShipments = \App\Models\SubscriptionShipment::with(['subscription.user', 'subscription.plan', 'payment'])
            ->whereDate('shipment_date', $targetDate->toDateString())
            ->whereIn('status', ['pending', 'sent'])
            ->where(function($q) use ($billingDate) {
                $q->whereNotNull('subscription_payment_id')
                  ->orWhereHas('subscription', function($q2) use ($billingDate) {
                      $q2->where(function($q3) use ($billingDate) {
                          $q3->where('status', '!=', 'paused')
                             ->orWhereNull('paused_until_date')
                             ->orWhere('paused_until_date', '<=', $billingDate);
                      });
                  });
            })
            ->get();

        $subscriptionIds = $pendingShipments->pluck('subscription_id')->unique();

        // Get subscriptions that SHOULD ship but don't have records yet (copy logic from lines 255-264)
        $allSubscriptions = Subscription::with(['user', 'plan'])
            ->whereIn('status', ['active', 'paused', 'pending', 'cancelled', 'complimentary'])
            ->whereNotIn('id', $subscriptionIds)
            ->where(function($q) use ($billingDate) {
                $q->where('status', '!=', 'paused')
                  ->orWhereNull('paused_until_date')
                  ->orWhere('paused_until_date', '<=', $billingDate);
            })
            ->get();

        // Filter subscriptions matching cadence (copy logic from lines 267-271)
        $newSubscriptions = $allSubscriptions->filter(function($subscription) use ($targetDate, $shipmentService) {
            return $shipmentService->shouldShipOn($subscription, $targetDate)
                || ($subscription->last_shipment_date &&
                    $subscription->last_shipment_date->format('Y-m-d') === $targetDate->format('Y-m-d'));
        });

        // Combine all subscriptions
        $allSubscriptionIds = $subscriptionIds->merge($newSubscriptions->pluck('id'))->unique();
        $subscriptions = Subscription::whereIn('id', $allSubscriptionIds)->get();

        // Calculate statistics by frequency
        $frequencyStats = [
            'total' => $subscriptions->count(),
            'one_time' => $subscriptions->where('frequency_months', 0)->count(),
            'monthly' => $subscriptions->where('frequency_months', 1)->count(),
            'bimonthly' => $subscriptions->where('frequency_months', 2)->count(),
            'quarterly' => $subscriptions->where('frequency_months', 3)->count(),
        ];

        // Calculate statistics by box size (based on configuration['amount'])
        $boxSizeStats = [
            'm_box' => $subscriptions->filter(function($sub) {
                $config = is_string($sub->configuration)
                    ? json_decode($sub->configuration, true)
                    : $sub->configuration;
                return isset($config['amount']) && $config['amount'] == 2;
            })->count(),
            'l_box' => $subscriptions->filter(function($sub) {
                $config = is_string($sub->configuration)
                    ? json_decode($sub->configuration, true)
                    : $sub->configuration;
                return isset($config['amount']) && $config['amount'] == 3;
            })->count(),
            'xl_box' => $subscriptions->filter(function($sub) {
                $config = is_string($sub->configuration)
                    ? json_decode($sub->configuration, true)
                    : $sub->configuration;
                return isset($config['amount']) && $config['amount'] == 4;
            })->count(),
        ];

        // Calculate coffee slot statistics
        $coffeeSlotStats = $this->calculateCoffeeSlotStats($subscriptions, $targetDate);

        return array_merge($frequencyStats, $boxSizeStats, $coffeeSlotStats);
    }

    /**
     * Calculate the 20th day of the month following the target date
     * Respects ShipmentSchedule configuration
     *
     * @param \Carbon\Carbon $targetDate Current target date
     * @return \Carbon\Carbon Next month's shipment date
     */
    private function getNextMonthShipmentDate(\Carbon\Carbon $targetDate): \Carbon\Carbon
    {
        // Add one month to target date
        $nextMonth = $targetDate->copy()->addMonthNoOverflow();

        // Check if there's a custom ShipmentSchedule for next month
        $schedule = ShipmentSchedule::getForMonth($nextMonth->year, $nextMonth->month);

        if ($schedule && $schedule->shipment_date) {
            return $schedule->shipment_date->copy()->startOfDay();
        }

        // Fallback to 20th if no schedule configured
        return $nextMonth->day(20)->startOfDay();
    }

    /**
     * Calculate coffee slot statistics for subscriptions
     * Counts actual coffee packages (not subscriptions) based on subscription configuration
     *
     * @param \Illuminate\Support\Collection $subscriptions Collection of subscriptions
     * @param \Carbon\Carbon $targetDate Target shipment date (not used, kept for compatibility)
     * @return array Array with coffee slot counts (e1, e2, e3, f1, f2, f3, d)
     */
    private function calculateCoffeeSlotStats($subscriptions, \Carbon\Carbon $targetDate): array
    {
        // Initialize counters for each slot
        $slotCounts = [
            'e1' => 0,
            'e2' => 0,
            'e3' => 0,
            'f1' => 0,
            'f2' => 0,
            'f3' => 0,
            'd' => 0,
        ];

        // Process each subscription
        foreach ($subscriptions as $subscription) {
            // Parse configuration
            $config = is_string($subscription->configuration)
                ? json_decode($subscription->configuration, true)
                : $subscription->configuration;

            // Skip if no valid configuration
            if (!$config || !isset($config['type']) || !isset($config['amount'])) {
                continue;
            }

            // Get slot names used by this subscription (e.g., ['e1', 'e2', 'e3', 'e1'])
            $slotsUsed = $this->allocateSlotsByConfiguration($config);

            // Count each slot - array may contain duplicates (XL box uses E1 twice)
            foreach ($slotsUsed as $slotName) {
                $slotCounts[$slotName]++;
            }
        }

        return $slotCounts;
    }

    /**
     * Allocate slot names based on subscription configuration
     * Used as fallback when ShipmentSchedule is not configured
     * Mirrors the logic from CoffeeAllocationService but returns slot names instead of product IDs
     *
     * @param array $config Subscription configuration
     * @return array Array of slot names (e.g., ['e1', 'e2', 'f1'])
     */
    private function allocateSlotsByConfiguration(array $config): array
    {
        // Convert to integers (JSON decode may return strings)
        $amount = (int) ($config['amount'] ?? 0); // 2, 3, 4
        $type = $config['type']; // espresso, filter, mix
        $isDecaf = $config['isDecaf'] ?? false;
        $mix = $config['mix'] ?? null;

        $slots = [];

        if ($type === 'espresso') {
            if ($isDecaf) {
                // Espresso with Decaf
                if ($amount === 2) {
                    $slots = ['e1', 'd'];
                } elseif ($amount === 3) {
                    $slots = ['e1', 'e2', 'd'];
                } elseif ($amount === 4) {
                    $slots = ['e1', 'e2', 'e3', 'd'];
                }
            } else {
                // Espresso without Decaf
                if ($amount === 2) {
                    $slots = ['e1', 'e2'];
                } elseif ($amount === 3) {
                    $slots = ['e1', 'e2', 'e3'];
                } elseif ($amount === 4) {
                    $slots = ['e1', 'e2', 'e3', 'e1']; // E1 repeats
                }
            }
        } elseif ($type === 'filter') {
            if ($isDecaf) {
                // Filter with Decaf
                if ($amount === 2) {
                    $slots = ['f1', 'd'];
                } elseif ($amount === 3) {
                    $slots = ['f1', 'f2', 'd'];
                } elseif ($amount === 4) {
                    $slots = ['f1', 'f2', 'f3', 'd'];
                }
            } else {
                // Filter without Decaf
                if ($amount === 2) {
                    $slots = ['f1', 'f2'];
                } elseif ($amount === 3) {
                    $slots = ['f1', 'f2', 'f3'];
                } elseif ($amount === 4) {
                    $slots = ['f1', 'f2', 'f3', 'f1']; // F1 repeats
                }
            }
        } elseif ($type === 'mix') {
            // Convert mix counts to integers as well
            $espressoCount = (int) ($mix['espresso'] ?? 0);
            $filterCount = (int) ($mix['filter'] ?? 0);

            if ($isDecaf) {
                // Mix with Decaf - replace last coffee from larger group with D
                $replaceFilter = $filterCount >= $espressoCount;

                // Add filter coffees
                $filterToAdd = $replaceFilter ? $filterCount - 1 : $filterCount;
                if ($filterToAdd >= 1) $slots[] = 'f1';
                if ($filterToAdd >= 2) $slots[] = 'f2';
                if ($filterToAdd >= 3) $slots[] = 'f3';

                // Add decaf if we're replacing filter
                if ($replaceFilter && $filterCount > 0) {
                    $slots[] = 'd';
                }

                // Add espresso coffees
                $espressoToAdd = !$replaceFilter ? $espressoCount - 1 : $espressoCount;
                if ($espressoToAdd >= 1) $slots[] = 'e1';
                if ($espressoToAdd >= 2) $slots[] = 'e2';
                if ($espressoToAdd >= 3) $slots[] = 'e3';

                // Add decaf if we're replacing espresso
                if (!$replaceFilter && $espressoCount > 0) {
                    $slots[] = 'd';
                }
            } else {
                // Mix without Decaf
                if ($filterCount >= 1) $slots[] = 'f1';
                if ($filterCount >= 2) $slots[] = 'f2';
                if ($filterCount >= 3) $slots[] = 'f3';

                if ($espressoCount >= 1) $slots[] = 'e1';
                if ($espressoCount >= 2) $slots[] = 'e2';
                if ($espressoCount >= 3) $slots[] = 'e3';
            }
        }

        return $slots;
    }

    /**
     * Get or create draft shipment for subscription on given date
     */
    private function getOrCreateShipment(Subscription $subscription, \Carbon\Carbon $shipmentDate): \App\Models\SubscriptionShipment
    {
        // Check if shipment already exists
        $shipment = $subscription->shipments()
            ->forDate($shipmentDate)
            ->first();
        
        if ($shipment) {
            // Always re-link payment according to current logic
            $payment = $this->findPaymentForShipment($subscription, $shipmentDate);
            $newPaymentId = $payment?->id;
            
            // Update if payment link changed
            if ($shipment->subscription_payment_id !== $newPaymentId) {
                $shipment->update(['subscription_payment_id' => $newPaymentId]);
                \Log::info('Updated shipment payment link', [
                    'shipment_id' => $shipment->id,
                    'old_payment_id' => $shipment->subscription_payment_id,
                    'new_payment_id' => $newPaymentId,
                ]);
            }
            return $shipment;
        }
        
        // Get default dimensions from config based on subscription configuration
        $config = is_string($subscription->configuration) 
            ? json_decode($subscription->configuration, true) 
            : $subscription->configuration;
        
        $amount = $config['amount'] ?? 2;
        
        // Find corresponding payment for this shipment
        $payment = $this->findPaymentForShipment($subscription, $shipmentDate);
        
        // Create new draft shipment with default values
        return $subscription->shipments()->create([
            'shipment_date' => $shipmentDate,
            'subscription_payment_id' => $payment?->id,
            'package_weight' => \App\Models\SubscriptionConfig::get("package_{$amount}_weight", $amount * 0.25),
            'package_length' => \App\Models\SubscriptionConfig::get("package_{$amount}_length", 30),
            'package_width' => \App\Models\SubscriptionConfig::get("package_{$amount}_width", 20),
            'package_height' => \App\Models\SubscriptionConfig::get("package_{$amount}_height", 10),
            'carrier_id' => $subscription->carrier_id,
            'carrier_pickup_point' => $subscription->carrier_pickup_point,
            'status' => 'pending',
        ]);
    }

    /**
     * Find payment for given shipment date
     * @deprecated Use SubscriptionShipmentService instead
     */
    private function findPaymentForShipment(Subscription $subscription, \Carbon\Carbon $shipmentDate): ?\App\Models\SubscriptionPayment
    {
        // Check for initial shipment (no shipment sent yet)
        $hasSentShipments = $subscription->shipments()->whereIn('status', ['sent', 'delivered'])->exists();
        
        if (!$hasSentShipments) {
            return $subscription->payments()
                ->where('status', 'paid')
                ->orderBy('paid_at', 'asc')
                ->first();
        }
        
        // For subsequent shipments, find payment where billing_date falls within period
        // period_end is EXCLUSIVE (it's the next billing date), so use > not >=
        $schedule = \App\Models\ShipmentSchedule::getForMonth($shipmentDate->year, $shipmentDate->month);
        $billingDate = $schedule?->billing_date ?? $shipmentDate->copy()->day(15);
        
        return $subscription->payments()
            ->where('status', 'paid')
            ->whereDate('period_start', '<=', $billingDate)
            ->whereDate('period_end', '>', $billingDate)
            ->orderBy('paid_at', 'desc')
            ->first();
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
            
            // Get or create shipment for this date
            $shipment = $this->getOrCreateShipment($subscription, $targetDate);

            // Skip if already sent
            if ($shipment->isSent()) {
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

            // Get dimensions and weight from shipment (can be customized per shipment)
            $weight = $shipment->package_weight;
            $packageSize = $shipment->getPackageDimensions();

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
            
            // Get Packeta data - prioritize shipment data, then shipping_address JSON
            $packetaPointId = $shippingAddress['packeta_point_id'] ?? $subscription->packeta_point_id;
            $carrierId = $shipment->carrier_id ?? $shippingAddress['carrier_id'] ?? $subscription->carrier_id ?? null;
            $carrierPickupPoint = $shipment->carrier_pickup_point ?? $shippingAddress['carrier_pickup_point'] ?? $subscription->carrier_pickup_point ?? null;
            
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
                'size' => $packageSize, // Package dimensions from shipment
                'order_number' => ($subscription->subscription_number ?? 'SUB-' . $subscription->id) . '-' . str_pad($shipment->getSequenceNumber(), 3, '0', STR_PAD_LEFT),
                'note' => $shipment->notes ?? $subscription->delivery_notes ?? null,
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
                    
                    // Use the central shipment service to mark as sent
                    $shipmentService = app(SubscriptionShipmentService::class);
                    $shipmentService->markAsShipped($shipment, $result['id'], $trackingUrl);
                    
                    // Update subscription packeta fields for backward compatibility
                    $subscription->update([
                        'packeta_packet_id' => $result['id'],
                        'packeta_tracking_url' => $trackingUrl,
                        'packeta_shipment_status' => 'sent',
                        'packeta_sent_at' => now(),
                    ]);

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

    /**
     * Update shipment details (dimensions, weight, notes)
     */
    public function updateShipment(Request $request, \App\Models\SubscriptionShipment $shipment)
    {
        $validated = $request->validate([
            'package_weight' => 'required|numeric|min:0.1|max:30',
            'package_length' => 'required|numeric|min:1|max:200',
            'package_width' => 'required|numeric|min:1|max:200',
            'package_height' => 'required|numeric|min:1|max:200',
            'notes' => 'nullable|string|max:500',
        ]);

        // Only allow editing pending shipments
        if (!$shipment->isPending()) {
            return response()->json([
                'success' => false,
                'message' => 'Lze editovat pouze nevyslanou rozesílku.',
            ], 400);
        }

        $shipment->update($validated);

        \Log::info('Shipment updated by admin', [
            'shipment_id' => $shipment->id,
            'subscription_id' => $shipment->subscription_id,
            'admin_user_id' => auth()->id(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Rozměry balíku byly úspěšně aktualizovány.',
            'shipment' => $shipment->fresh(),
        ]);
    }

    /**
     * Display history of sent shipments
     */
    public function shipmentsHistory(Request $request)
    {
        $query = \App\Models\SubscriptionShipment::with(['subscription.user', 'subscription.plan', 'payment'])
            ->whereIn('status', ['sent', 'delivered'])
            ->orderBy('sent_at', 'desc');

        // Filter by month/year if provided
        if ($request->has('month') && $request->has('year')) {
            $year = $request->input('year');
            $month = $request->input('month');
            $query->whereYear('shipment_date', $year)
                  ->whereMonth('shipment_date', $month);
        }

        // Search by subscription number or customer name
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->whereHas('subscription', function($q) use ($search) {
                $q->where('subscription_number', 'like', "%{$search}%")
                  ->orWhereHas('user', function($q2) use ($search) {
                      $q2->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        $shipments = $query->paginate(50);

        // Get stats
        $stats = [
            'total' => $query->count(),
            'this_month' => \App\Models\SubscriptionShipment::whereIn('status', ['sent', 'delivered'])
                ->whereYear('shipment_date', now()->year)
                ->whereMonth('shipment_date', now()->month)
                ->count(),
        ];

        // Get available months for filter dropdown
        $availableMonths = \App\Models\SubscriptionShipment::selectRaw('YEAR(shipment_date) as year, MONTH(shipment_date) as month')
            ->whereIn('status', ['sent', 'delivered'])
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        return view('admin.subscriptions.shipments-history', compact('shipments', 'stats', 'availableMonths'));
    }

    /**
     * Manually mark a single shipment as delivered (triggers delivery email via observer)
     */
    public function markAsDelivered(\App\Models\SubscriptionShipment $shipment)
    {
        // Check if already delivered
        if ($shipment->status === 'delivered') {
            return redirect()->route('admin.subscriptions.show', $shipment->subscription_id)
                ->with('info', 'Zásilka již byla označena jako doručená.');
        }

        try {
            // Mark shipment as delivered - observer will send the email
            $shipment->update([
                'status' => 'delivered',
                'delivered_at' => now(),
                // If not sent yet, also set sent_at
                'sent_at' => $shipment->sent_at ?? now(),
            ]);

            // Update subscription for backward compatibility
            $shipment->subscription->update([
                'packeta_shipment_status' => 'delivered',
            ]);

            Log::info('Subscription shipment manually marked as delivered', [
                'shipment_id' => $shipment->id,
                'subscription_id' => $shipment->subscription_id,
                'subscription_number' => $shipment->subscription->subscription_number,
                'admin_user_id' => auth()->id(),
            ]);

            return redirect()->route('admin.subscriptions.show', $shipment->subscription_id)
                ->with('success', 'Zásilka byla označena jako doručená a email byl odeslán.');

        } catch (\Exception $e) {
            Log::error('Failed to manually mark shipment as delivered', [
                'shipment_id' => $shipment->id,
                'error' => $e->getMessage(),
            ]);

            return redirect()->route('admin.subscriptions.show', $shipment->subscription_id)
                ->with('error', 'Nepodařilo se označit zásilku jako doručenou: ' . $e->getMessage());
        }
    }

    /**
     * Recalculate stock reservations for the given month
     * Can be triggered manually from admin panel if automatic cron fails
     */
    public function recalculateReservations(Request $request, StockReservationService $reservationService)
    {
        $year = $request->input('year', now()->year);
        $month = $request->input('month', now()->month);

        try {
            $schedule = ShipmentSchedule::getForMonth($year, $month);
            
            if (!$schedule) {
                return redirect()->back()
                    ->with('error', "Harmonogram pro {$month}/{$year} neexistuje.");
            }

            $reservationService->updateReservationsForSchedule($schedule);

            Log::info('Stock reservations manually recalculated by admin', [
                'year' => $year,
                'month' => $month,
                'schedule_id' => $schedule->id,
                'admin_user_id' => auth()->id(),
            ]);

            return redirect()->back()
                ->with('success', "Rezervace káv pro {$month}/{$year} byly úspěšně přepočítány.");

        } catch (\Exception $e) {
            Log::error('Failed to recalculate stock reservations', [
                'year' => $year,
                'month' => $month,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()
                ->with('error', 'Nepodařilo se přepočítat rezervace: ' . $e->getMessage());
        }
    }

    /**
     * Download invoice PDF for a subscription payment
     */
    public function downloadInvoice(\App\Models\SubscriptionPayment $payment)
    {
        if (!$payment->invoice_pdf_path) {
            abort(404, 'Faktura není k dispozici.');
        }

        if (!Storage::exists($payment->invoice_pdf_path)) {
            abort(404, 'Soubor faktury nebyl nalezen.');
        }

        $filename = "faktura_predplatne_{$payment->subscription_id}_{$payment->id}.pdf";

        return Storage::download(
            $payment->invoice_pdf_path,
            $filename,
            ['Content-Type' => 'application/pdf']
        );
    }
}
