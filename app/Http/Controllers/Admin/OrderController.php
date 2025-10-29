<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

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
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled',
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
}
