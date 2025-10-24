<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\StripeService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function __construct(private StripeService $stripeService)
    {
        $this->middleware('auth');
    }

    public function cardPayment(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        if ($order->payment_status === 'paid') {
            return redirect()->route('order.confirmation', $order);
        }

        try {
            $session = $this->stripeService->createOrderCheckoutSession($order);
            return redirect($session->url);
        } catch (\Exception $e) {
            return back()->with('error', 'Nepodařilo se vytvořit platební session. Zkuste to prosím znovu.');
        }
    }

    public function webhook(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $webhookSecret = config('services.stripe.webhook_secret');

        try {
            $event = \Stripe\Webhook::constructEvent($payload, $sigHeader, $webhookSecret);

            switch ($event->type) {
                case 'checkout.session.completed':
                    $this->stripeService->handlePaymentSuccess($event->data->object);
                    break;
                
                case 'customer.subscription.created':
                    $this->stripeService->handleSubscriptionCreated($event->data->object);
                    break;
            }

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}



