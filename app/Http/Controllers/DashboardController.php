<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Subscription;
use Illuminate\Http\Request;

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

        return view('dashboard.index', compact('orders', 'activeSubscription', 'activeSubscriptions'));
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
        $subscriptions = auth()->user()->activeSubscriptions()->get();

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
}




