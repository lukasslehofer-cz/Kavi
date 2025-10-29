<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    /**
     * Display a listing of coupons
     */
    public function index()
    {
        $coupons = Coupon::withCount('usages')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.coupons.index', compact('coupons'));
    }

    /**
     * Show the form for creating a new coupon
     */
    public function create()
    {
        return view('admin.coupons.create');
    }

    /**
     * Store a newly created coupon
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|unique:coupons,code|max:50',
            'name' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            
            // Jednorázový nákup
            'discount_type_order' => 'required|in:percentage,fixed,none',
            'discount_value_order' => 'nullable|numeric|min:0',
            'free_shipping' => 'boolean',
            
            // Předplatné
            'discount_type_subscription' => 'required|in:percentage,fixed,none',
            'discount_value_subscription' => 'nullable|numeric|min:0',
            'subscription_discount_months' => 'nullable|integer|min:1',
            
            // Minimální hodnota
            'min_order_value' => 'nullable|numeric|min:0',
            
            // Platnost
            'valid_from' => 'nullable|date',
            'valid_until' => 'nullable|date|after_or_equal:valid_from',
            
            // Limity
            'usage_limit_total' => 'nullable|integer|min:1',
            'usage_limit_per_user' => 'nullable|integer|min:1',
            
            'is_active' => 'boolean',
        ]);

        // Upravit code na uppercase
        $validated['code'] = strtoupper($validated['code']);
        
        // Výchozí hodnoty
        $validated['free_shipping'] = $request->has('free_shipping');
        $validated['is_active'] = $request->has('is_active');

        Coupon::create($validated);

        return redirect()->route('admin.coupons.index')
            ->with('success', 'Kupón byl úspěšně vytvořen!');
    }

    /**
     * Show the form for editing the specified coupon
     */
    public function edit(Coupon $coupon)
    {
        $coupon->loadCount('usages');
        
        return view('admin.coupons.edit', compact('coupon'));
    }

    /**
     * Update the specified coupon
     */
    public function update(Request $request, Coupon $coupon)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:coupons,code,' . $coupon->id,
            'name' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            
            // Jednorázový nákup
            'discount_type_order' => 'required|in:percentage,fixed,none',
            'discount_value_order' => 'nullable|numeric|min:0',
            'free_shipping' => 'boolean',
            
            // Předplatné
            'discount_type_subscription' => 'required|in:percentage,fixed,none',
            'discount_value_subscription' => 'nullable|numeric|min:0',
            'subscription_discount_months' => 'nullable|integer|min:1',
            
            // Minimální hodnota
            'min_order_value' => 'nullable|numeric|min:0',
            
            // Platnost
            'valid_from' => 'nullable|date',
            'valid_until' => 'nullable|date|after_or_equal:valid_from',
            
            // Limity
            'usage_limit_total' => 'nullable|integer|min:1',
            'usage_limit_per_user' => 'nullable|integer|min:1',
            
            'is_active' => 'boolean',
        ]);

        // Upravit code na uppercase
        $validated['code'] = strtoupper($validated['code']);
        
        // Výchozí hodnoty
        $validated['free_shipping'] = $request->has('free_shipping');
        $validated['is_active'] = $request->has('is_active');

        $coupon->update($validated);

        return redirect()->route('admin.coupons.index')
            ->with('success', 'Kupón byl úspěšně aktualizován!');
    }

    /**
     * Remove the specified coupon
     */
    public function destroy(Coupon $coupon)
    {
        $coupon->delete();

        return redirect()->route('admin.coupons.index')
            ->with('success', 'Kupón byl úspěšně smazán!');
    }

    /**
     * Show coupon usage statistics
     */
    public function stats(Coupon $coupon)
    {
        $coupon->load(['usages.user', 'usages.order', 'usages.subscription']);
        
        $stats = [
            'total_uses' => $coupon->usages->count(),
            'total_discount' => $coupon->usages->sum('discount_amount'),
            'order_uses' => $coupon->usages->where('usage_type', 'order')->count(),
            'subscription_uses' => $coupon->usages->where('usage_type', 'subscription')->count(),
            'unique_users' => $coupon->usages->pluck('user_id')->unique()->count(),
        ];

        return view('admin.coupons.stats', compact('coupon', 'stats'));
    }
}

