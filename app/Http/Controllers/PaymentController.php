<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\StripeService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function __construct(private StripeService $stripeService)
    {
        $this->middleware('auth')->except('webhook');
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

            \Log::info('Stripe webhook received', [
                'type' => $event->type,
                'id' => $event->id,
            ]);

            // Convert Stripe object to array
            $eventData = json_decode(json_encode($event->data->object), true);

            switch ($event->type) {
                case 'checkout.session.completed':
                    $this->stripeService->handlePaymentSuccess($eventData);
                    break;
                
                case 'customer.subscription.created':
                    $this->stripeService->handleSubscriptionCreated($eventData);
                    break;

                case 'customer.subscription.updated':
                    $this->stripeService->handleSubscriptionUpdated($eventData);
                    break;

                case 'customer.subscription.deleted':
                    $this->stripeService->handleSubscriptionDeleted($eventData);
                    break;

                case 'invoice.payment_succeeded':
                    $this->stripeService->handleInvoicePaymentSucceeded($eventData);
                    break;

                case 'invoice.payment_failed':
                    $this->stripeService->handleInvoicePaymentFailed($eventData);
                    break;

                default:
                    \Log::info('Unhandled webhook event type: ' . $event->type);
            }

            return response()->json(['status' => 'success']);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            \Log::error('Stripe webhook signature verification failed', [
                'error' => $e->getMessage(),
            ]);
            return response()->json(['error' => 'Invalid signature'], 400);
        } catch (\Exception $e) {
            \Log::error('Stripe webhook processing failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}




