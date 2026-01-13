<?php

namespace App\Observers;

use App\Mail\SubscriptionBoxDelivered;
use App\Models\SubscriptionShipment;
use Illuminate\Support\Facades\Mail;

class SubscriptionShipmentObserver
{
    /**
     * Handle the SubscriptionShipment "updated" event.
     */
    public function updated(SubscriptionShipment $shipment): void
    {
        // Send "subscription box delivered" email when delivered_at is set
        if ($shipment->isDirty('delivered_at') && $shipment->delivered_at !== null) {
            $this->sendSubscriptionBoxDeliveredEmail($shipment);
        }
    }
    
    /**
     * Send subscription box delivered email
     */
    protected function sendSubscriptionBoxDeliveredEmail(SubscriptionShipment $shipment): void
    {
        try {
            $subscription = $shipment->subscription;
            
            if (!$subscription) {
                \Log::warning('Could not send subscription box delivered email - no subscription found', [
                    'shipment_id' => $shipment->id
                ]);
                return;
            }
            
            // Get email from subscription shipping address or user
            $email = $subscription->shipping_address['email'] ?? $subscription->user?->email ?? null;
            
            if ($email) {
                Mail::to($email)->send(new SubscriptionBoxDelivered($subscription));
                \Log::info('Subscription box delivered email sent', [
                    'shipment_id' => $shipment->id,
                    'subscription_id' => $subscription->id,
                    'subscription_number' => $subscription->subscription_number,
                    'email' => $email
                ]);
            } else {
                \Log::warning('Could not send subscription box delivered email - no email found', [
                    'shipment_id' => $shipment->id,
                    'subscription_id' => $subscription->id
                ]);
            }
        } catch (\Exception $e) {
            \Log::error('Failed to send subscription box delivered email', [
                'shipment_id' => $shipment->id,
                'error' => $e->getMessage()
            ]);
        }
    }
}
