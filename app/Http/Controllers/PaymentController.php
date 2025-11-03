<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\StripeService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function __construct(private StripeService $stripeService)
    {
        $this->middleware('auth')->except(['webhook', 'cardPayment']);
    }

    public function cardPayment(Order $order)
    {
        \Log::info('cardPayment called', [
            'order_id' => $order->id,
            'user_id' => $order->user_id,
            'auth_id' => auth()->id(),
            'is_authenticated' => auth()->check(),
        ]);
        
        // Allow access for authenticated users who own the order
        // OR for guest orders (where user_id might be null or newly created)
        if (auth()->check() && $order->user_id !== auth()->id()) {
            \Log::warning('Authorization failed in cardPayment', [
                'order_id' => $order->id,
                'order_user_id' => $order->user_id,
                'auth_id' => auth()->id(),
            ]);
            abort(403);
        }

        if ($order->payment_status === 'paid') {
            return redirect()->route('order.confirmation', $order);
        }

        try {
            $session = $this->stripeService->createOrderCheckoutSession($order);
            \Log::info('Stripe session created successfully', [
                'order_id' => $order->id,
                'session_id' => $session->id,
            ]);
            return redirect($session->url);
        } catch (\Exception $e) {
            \Log::error('Failed to create Stripe checkout session', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            // Don't go back() - cart is empty. Go to order confirmation with error.
            return redirect()->route('order.confirmation', $order)
                ->with('error', 'Nepodařilo se vytvořit platební session: ' . $e->getMessage() . ' Zkuste to prosím znovu níže.');
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

                case 'payment_intent.payment_failed':
                    $this->stripeService->handleOrderPaymentFailed($eventData);
                    break;

                case 'payment_method.attached':
                    $this->stripeService->handlePaymentMethodAttached($eventData);
                    break;

                case 'customer.source.updated':
                    // Legacy event for older Stripe integrations
                    $this->stripeService->handlePaymentMethodUpdated($eventData);
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




