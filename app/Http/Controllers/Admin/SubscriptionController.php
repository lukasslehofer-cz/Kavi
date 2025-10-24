<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use Illuminate\Http\Request;

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

        // Get all active subscriptions
        $allSubscriptions = Subscription::with(['user', 'plan'])
            ->where('status', 'active')
            ->get();

        // Filter subscriptions that should ship on target date
        $subscriptions = $allSubscriptions->filter(function($subscription) use ($targetDate) {
            return $subscription->shouldShipOn($targetDate);
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
}
