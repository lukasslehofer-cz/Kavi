<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = auth()->user();

        $orders = Order::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Get all active subscriptions
        $activeSubscriptions = $user->activeSubscriptions()->get();
        $activeSubscription = $activeSubscriptions->first(); // For backward compatibility

        // Get unpaid subscriptions for alert
        $unpaidSubscriptions = $user->subscriptions()
            ->where('status', 'unpaid')
            ->get();

        // Get unpaid orders for alert
        $unpaidOrders = Order::where('user_id', $user->id)
            ->where('payment_status', 'unpaid')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('dashboard.index', compact('orders', 'activeSubscription', 'activeSubscriptions', 'unpaidSubscriptions', 'unpaidOrders'));
    }

    public function orders()
    {
        $orders = Order::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('dashboard.orders', compact('orders'));
    }

    public function orderDetail(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        return view('dashboard.order-detail', compact('order'));
    }

    public function subscription()
    {
        $subscriptions = auth()->user()->subscriptions()
            ->whereIn('status', ['active', 'unpaid', 'paused', 'cancelled'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->filter(function ($subscription) {
                // Always show active, unpaid and paused
                if (in_array($subscription->status, ['active', 'unpaid', 'paused'])) {
                    return true;
                }
                
                // For cancelled, show only if there's a paid shipment still pending
                if ($subscription->status === 'cancelled') {
                    $nextShipment = \App\Helpers\SubscriptionHelper::calculateNextShipmentDate($subscription);
                    if ($nextShipment && $nextShipment->isFuture()) {
                        return \App\Helpers\SubscriptionHelper::hasPaidCoverageForDate($subscription, $nextShipment);
                    }
                }
                
                return false;
            });

        if ($subscriptions->isEmpty()) {
            return redirect()->route('subscriptions.index')
                ->with('message', 'Nemáte žádné aktivní předplatné.');
        }

        return view('dashboard.subscription', compact('subscriptions'));
    }

    public function updatePacketaPoint(Request $request)
    {
        $request->validate([
            'subscription_id' => 'required|exists:subscriptions,id',
            'packeta_point_id' => 'required|string',
            'packeta_point_name' => 'required|string',
            'packeta_point_address' => 'nullable|string',
        ]);

        // Find the subscription and verify it belongs to the user
        $subscription = Subscription::where('id', $request->subscription_id)
            ->where('user_id', auth()->id())
            ->first();

        if (!$subscription) {
            return response()->json(['success' => false, 'message' => 'Předplatné nenalezeno.'], 404);
        }

        // Update subscription
        $subscription->update([
            'packeta_point_id' => $request->packeta_point_id,
            'packeta_point_name' => $request->packeta_point_name,
            'packeta_point_address' => $request->packeta_point_address,
        ]);

        // Also update user's default pickup point
        auth()->user()->update([
            'packeta_point_id' => $request->packeta_point_id,
            'packeta_point_name' => $request->packeta_point_name,
            'packeta_point_address' => $request->packeta_point_address,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Výdejní místo bylo úspěšně změněno.',
        ]);
    }

    public function profile()
    {
        return view('dashboard.profile');
    }

    public function updateProfile(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . auth()->id()],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:100'],
            'postal_code' => ['nullable', 'string', 'max:20'],
            'country' => ['nullable', 'string', 'size:2'],
            'packeta_point_id' => ['nullable', 'string'],
            'packeta_point_name' => ['nullable', 'string'],
            'packeta_point_address' => ['nullable', 'string'],
        ]);

        auth()->user()->update($validated);

        return redirect()->route('dashboard.profile')
            ->with('success', 'Profil byl úspěšně aktualizován.');
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        auth()->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('dashboard.profile')
            ->with('success', 'Heslo bylo úspěšně změněno.');
    }

    public function notifications()
    {
        return view('dashboard.notifications');
    }

    public function downloadInvoice(Order $order)
    {
        // Verify order belongs to authenticated user
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        // Check if invoice PDF exists
        if (!$order->invoice_pdf_path) {
            abort(404, 'Faktura není k dispozici.');
        }

        // Check if file exists in storage
        if (!Storage::exists($order->invoice_pdf_path)) {
            abort(404, 'Soubor faktury nebyl nalezen.');
        }

        // Download the PDF
        return Storage::download(
            $order->invoice_pdf_path,
            "faktura_{$order->order_number}.pdf",
            [
                'Content-Type' => 'application/pdf',
            ]
        );
    }

    public function downloadSubscriptionInvoice(\App\Models\SubscriptionPayment $payment)
    {
        // Verify payment belongs to authenticated user's subscription
        if ($payment->subscription->user_id !== auth()->id()) {
            abort(403);
        }

        // Check if invoice PDF exists
        if (!$payment->invoice_pdf_path) {
            abort(404, 'Faktura není k dispozici.');
        }

        // Check if file exists in storage
        if (!Storage::exists($payment->invoice_pdf_path)) {
            abort(404, 'Soubor faktury nebyl nalezen.');
        }

        // Download the PDF
        $filename = "faktura_predplatne_" . $payment->subscription_id . "_" . $payment->paid_at->format('Y-m') . ".pdf";
        
        return Storage::download(
            $payment->invoice_pdf_path,
            $filename,
            [
                'Content-Type' => 'application/pdf',
            ]
        );
    }

    /**
     * Pause a subscription for N iterations
     */
    public function pauseSubscription(Request $request)
    {
        $validated = $request->validate([
            'subscription_id' => ['required', 'exists:subscriptions,id'],
            'iterations' => ['required', 'integer', 'min:1', 'max:3'],
        ]);

        $subscription = Subscription::where('id', $validated['subscription_id'])
            ->where('user_id', auth()->id())
            ->firstOrFail();

        // Apply pause locally
        $subscription->pauseFor((int)$validated['iterations'], 'user_request');

        // Pause in Stripe (no billing during pause)
        try {
            app(\App\Services\StripeService::class)->pauseSubscription($subscription);
        } catch (\Exception $e) {
            \Log::error('Failed to pause Stripe subscription', [
                'subscription_id' => $subscription->id,
                'error' => $e->getMessage(),
            ]);
        }

        // Send email notification
        try {
            $email = $subscription->shipping_address['email'] ?? null;
            if (!$email && $subscription->user) {
                $email = $subscription->user->email;
            }
            if ($email) {
                \Mail::to($email)->send(new \App\Mail\SubscriptionPaused($subscription, 'user_request'));
            }
        } catch (\Exception $e) {
            \Log::error('Failed to send SubscriptionPaused email', [
                'subscription_id' => $subscription->id,
                'error' => $e->getMessage(),
            ]);
        }

        return redirect()->route('dashboard.subscription')
            ->with('success', 'Předplatné bylo pozastaveno.');
    }

    /**
     * Resume a paused subscription (user-triggered)
     */
    public function resumeSubscription(Request $request)
    {
        $validated = $request->validate([
            'subscription_id' => ['required', 'exists:subscriptions,id'],
        ]);

        $subscription = Subscription::where('id', $validated['subscription_id'])
            ->where('user_id', auth()->id())
            ->firstOrFail();

        // Resume in Stripe first
        try {
            app(\App\Services\StripeService::class)->resumeSubscription($subscription);
        } catch (\Exception $e) {
            \Log::error('Failed to resume Stripe subscription', [
                'subscription_id' => $subscription->id,
                'error' => $e->getMessage(),
            ]);
        }

        // Resume locally
        $subscription->resume();

        return redirect()->route('dashboard.subscription')
            ->with('success', 'Předplatné bylo obnoveno.');
    }

    /**
     * Cancel a subscription (user-triggered)
     */
    public function cancelSubscription(Request $request)
    {
        $validated = $request->validate([
            'subscription_id' => ['required', 'exists:subscriptions,id'],
        ]);

        $subscription = Subscription::where('id', $validated['subscription_id'])
            ->where('user_id', auth()->id())
            ->firstOrFail();

        // Cancel in Stripe first (cancel_at_period_end)
        try {
            app(\App\Services\StripeService::class)->cancelSubscription($subscription);
        } catch (\Exception $e) {
            \Log::error('Failed to cancel Stripe subscription', [
                'subscription_id' => $subscription->id,
                'error' => $e->getMessage(),
            ]);
        }

        // Cancel locally
        $subscription->cancel();

        // Send cancellation email
        try {
            $email = $subscription->shipping_address['email'] ?? null;
            if (!$email && $subscription->user) {
                $email = $subscription->user->email;
            }
            if ($email) {
                \Mail::to($email)->send(new \App\Mail\SubscriptionCancelled($subscription));
            }
        } catch (\Exception $e) {
            \Log::error('Failed to send SubscriptionCancelled email', [
                'subscription_id' => $subscription->id,
                'error' => $e->getMessage(),
            ]);
        }

        return redirect()->route('dashboard.subscription')
            ->with('success', 'Předplatné bylo zrušeno.');
    }
}
