<?php

namespace App\Http\Controllers;

use App\Services\CouponService;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function __construct(private CouponService $couponService)
    {
    }

    /**
     * Aktivuje kupón z linku (např. kavi.cz/code/slevovykod)
     */
    public function activateFromLink(string $code)
    {
        // Uložit kód do cookie na 7 dní
        $cookie = $this->couponService->storeCouponInCookie($code);

        // Uložit také do session pro okamžité použití
        session(['coupon_code' => $code]);

        return redirect()->route('home')
            ->cookie($cookie)
            ->with('success', "Kupón {$code} byl aktivován! Při objednávce bude automaticky aplikován.");
    }

    /**
     * Validuje kupón přes AJAX
     */
    public function validateCoupon(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
            'type' => 'required|in:order,subscription',
            'order_value' => 'nullable|numeric|min:0',
        ]);

        $result = $this->couponService->validateCoupon(
            $request->code,
            auth()->user(),
            $request->type,
            $request->order_value
        );

        if (!$result['valid']) {
            return response()->json([
                'valid' => false,
                'message' => $result['message'],
            ], 422);
        }

        $coupon = $result['coupon'];

        // Vrátit info o kupónu
        $response = [
            'valid' => true,
            'message' => 'Kupón je platný!',
            'coupon' => [
                'code' => $coupon->code,
                'name' => $coupon->name,
            ],
        ];

        // Přidat info o slevě podle typu
        if ($request->type === 'order') {
            $response['discount'] = [
                'type' => $coupon->discount_type_order,
                'value' => $coupon->discount_value_order,
                'free_shipping' => $coupon->free_shipping,
                'description' => $coupon->getOrderDiscountDescription(),
            ];
        } else {
            $response['discount'] = [
                'type' => $coupon->discount_type_subscription,
                'value' => $coupon->discount_value_subscription,
                'months' => $coupon->subscription_discount_months,
                'description' => $coupon->getSubscriptionDiscountDescription(),
            ];
        }

        return response()->json($response);
    }
}

