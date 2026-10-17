<?php

namespace App\Console\Commands;

use App\Models\Coupon;
use App\Models\CouponUsage;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

/**
 * Uvolní dárkový poukaz, který zůstal "rezervovaný" u nezaplaceného jednorázového boxu.
 *
 * Pokladna jednorázového boxu zapisuje použití kuponu ještě před platbou ve Stripe.
 * Když zákazník platbu nedokončí, jednorázový poukaz by zůstal spálený. Uvolňuje se
 * jen tehdy, když Stripe potvrdí, že platební session opravdu vypršela.
 */
class ReleaseStaleGiftVoucherReservations extends Command
{
    protected $signature = 'coupons:release-stale-gift-reservations {--dry-run : Jen vypsat}';

    protected $description = 'Uvolní dárkové poukazy z nezaplacených jednorázových boxů (po vypršení platby ve Stripe)';

    public function handle(): int
    {
        $usages = CouponUsage::whereNotNull('subscription_id')
            ->whereHas('coupon', fn ($q) => $q->where('gift_type', 'voucher'))
            ->whereHas('subscription', fn ($q) => $q->where('status', 'pending')
                ->where('frequency_months', 0)
                ->where('created_at', '<', now()->subHours(25))
                ->whereDoesntHave('payments', fn ($p) => $p->where('status', 'paid')))
            ->with('subscription')
            ->get();

        if ($usages->isEmpty()) {
            return self::SUCCESS;
        }

        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
        $released = 0;

        foreach ($usages as $usage) {
            $sessionId = $usage->subscription?->stripe_session_id;
            if (! $sessionId) {
                continue;
            }

            try {
                $session = \Stripe\Checkout\Session::retrieve($sessionId);
            } catch (\Throwable $e) {
                \Log::warning('Stripe session check failed for gift voucher reservation', ['usage_id' => $usage->id, 'error' => $e->getMessage()]);
                continue;
            }

            if ($session->status !== 'expired') {
                continue;
            }

            if ($this->option('dry-run')) {
                $this->line("usage #{$usage->id} coupon #{$usage->coupon_id} subscription #{$usage->subscription_id}");
                continue;
            }

            DB::transaction(function () use ($usage) {
                $coupon = Coupon::lockForUpdate()->find($usage->coupon_id);
                $usage->delete();
                if ($coupon && $coupon->times_used > 0) {
                    $coupon->decrement('times_used');
                }
            });

            \Log::info('Gift voucher reservation released (expired one-time box payment)', [
                'coupon_id' => $usage->coupon_id,
                'subscription_id' => $usage->subscription_id,
            ]);
            $released++;
        }

        if ($released) {
            $this->info("Uvolněno: {$released}");
        }

        return self::SUCCESS;
    }
}
