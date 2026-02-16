<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Subscription;
use App\Models\SubscriptionShipment;
use App\Models\SubscriptionPayment;

class FixOneTimeBoxInvoice extends Command
{
    protected $signature = 'fix:onetime-box-invoice';

    protected $description = 'One-time fix: create missing SubscriptionPayment + Fakturoid invoice for subscription #59 and link to shipment #138, remove spurious Feb shipment';

    public function handle()
    {
        $subscriptionId = 59;
        $correctShipmentId = 138;

        $subscription = Subscription::find($subscriptionId);
        if (!$subscription) {
            $this->error("Subscription #{$subscriptionId} not found.");
            return 1;
        }

        $this->info("Subscription: {$subscription->subscription_number} (ID: {$subscription->id})");
        $this->info("Status: {$subscription->status}, Frequency: {$subscription->frequency_months}");
        $this->info("Price: {$subscription->configured_price}, Shipping: {$subscription->shipping_cost}");

        // 1. Remove spurious February shipment (if exists)
        $correctShipment = SubscriptionShipment::find($correctShipmentId);
        if (!$correctShipment) {
            $this->error("Shipment #{$correctShipmentId} not found.");
            return 1;
        }

        $this->info("Correct shipment #{$correctShipment->id}: date={$correctShipment->shipment_date->toDateString()}, status={$correctShipment->status}");

        $spuriousShipments = $subscription->shipments()
            ->where('id', '!=', $correctShipmentId)
            ->where('status', 'pending')
            ->get();

        foreach ($spuriousShipments as $spurious) {
            $this->warn("Found spurious shipment #{$spurious->id}: date={$spurious->shipment_date->toDateString()}");
        }

        // 2. Check if payment already exists
        $existingPayment = $subscription->payments()->first();
        if ($existingPayment) {
            $this->info("Payment already exists: #{$existingPayment->id}, fakturoid_invoice_id={$existingPayment->fakturoid_invoice_id}");

            if ($existingPayment->hasFaktura()) {
                $this->info("Invoice already exists. Nothing to do for invoice.");
            }
        }

        if (!$this->confirm('Proceed with the fix?')) {
            $this->info('Aborted.');
            return 0;
        }

        // 3. Delete spurious shipments
        foreach ($spuriousShipments as $spurious) {
            $spurious->delete();
            $this->info("Deleted spurious shipment #{$spurious->id}");
        }

        // 4. Create payment if missing
        $payment = $existingPayment;
        if (!$payment) {
            $totalAmount = ($subscription->configured_price ?? 0) + ($subscription->shipping_cost ?? 0);

            $payment = SubscriptionPayment::create([
                'subscription_id' => $subscription->id,
                'stripe_payment_intent_id' => $subscription->stripe_payment_intent_id,
                'amount' => $totalAmount,
                'currency' => $subscription->currency ?? 'czk',
                'status' => 'paid',
                'paid_at' => $subscription->starts_at ?? $subscription->created_at,
                'period_start' => $subscription->starts_at ?? $subscription->created_at,
                'period_end' => $subscription->starts_at ?? $subscription->created_at,
            ]);

            $this->info("Created SubscriptionPayment #{$payment->id}, amount={$payment->amount}");
        }

        // 5. Link payment to correct shipment
        if (!$correctShipment->subscription_payment_id) {
            $correctShipment->update(['subscription_payment_id' => $payment->id]);
            $this->info("Linked payment #{$payment->id} to shipment #{$correctShipment->id}");
        } else {
            $this->info("Shipment already linked to payment #{$correctShipment->subscription_payment_id}");
        }

        // 6. Create Fakturoid invoice if missing
        if (!$payment->hasFaktura()) {
            $this->info('Creating Fakturoid invoice...');
            try {
                $fakturoidService = app(\App\Services\FakturoidService::class);
                $result = $fakturoidService->processInvoiceForSubscriptionPayment($payment);

                if ($result) {
                    $payment->refresh();
                    $this->info("Fakturoid invoice created: ID={$payment->fakturoid_invoice_id}, number={$payment->invoice_number}");
                } else {
                    $this->error('Fakturoid invoice creation returned false.');
                }
            } catch (\Exception $e) {
                $this->error("Fakturoid error: {$e->getMessage()}");
                return 1;
            }
        }

        $this->info('Done! Summary:');
        $this->table(
            ['Field', 'Value'],
            [
                ['Subscription', $subscription->subscription_number],
                ['Payment ID', $payment->id],
                ['Shipment ID', $correctShipment->id],
                ['Shipment Date', $correctShipment->shipment_date->toDateString()],
                ['Invoice ID', $payment->fakturoid_invoice_id ?? 'N/A'],
                ['Invoice Number', $payment->invoice_number ?? 'N/A'],
                ['Spurious removed', $spuriousShipments->count()],
            ]
        );

        return 0;
    }
}
