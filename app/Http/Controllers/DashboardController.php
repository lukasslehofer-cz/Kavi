<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Subscription;

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

        $activeSubscription = $user->activeSubscription;

        return view('dashboard.index', compact('orders', 'activeSubscription'));
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
        $subscription = auth()->user()->activeSubscription;

        if (!$subscription) {
            return redirect()->route('subscriptions.index')
                ->with('message', 'Nemáte aktivní předplatné.');
        }

        return view('dashboard.subscription', compact('subscription'));
    }
}



