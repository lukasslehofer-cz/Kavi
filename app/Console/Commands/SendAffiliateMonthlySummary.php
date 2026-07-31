<?php

namespace App\Console\Commands;

use App\Mail\AffiliateMonthlySummary;
use App\Models\AffiliateReward;
use App\Models\User;
use App\Services\AffiliateService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class SendAffiliateMonthlySummary extends Command
{
    protected $signature = 'affiliate:send-monthly-summary
                            {--month= : Měsíc ve formátu YYYY-MM (výchozí: minulý měsíc)}
                            {--partner= : ID konkrétního partnera}
                            {--dry-run : Jen vypíše, komu by mail odešel}';

    protected $description = 'Odešle affiliate partnerům měsíční souhrn jejich výdělku';

    public function handle(AffiliateService $affiliateService): int
    {
        if (! config('affiliate.emails.monthly_summary', true)) {
            $this->warn('Měsíční souhrny jsou vypnuté v config/affiliate.php');

            return self::SUCCESS;
        }

        $month = $this->resolveMonth();

        if (! $month) {
            $this->error('Neplatný formát měsíce, použij YYYY-MM.');

            return self::FAILURE;
        }

        $dryRun = (bool) $this->option('dry-run');

        $partners = User::where('is_affiliate_partner', true)
            ->when($this->option('partner'), fn ($q, $id) => $q->where('id', $id))
            ->whereNotNull('email')
            ->orderBy('id')
            ->get();

        if ($partners->isEmpty()) {
            $this->warn('Žádní affiliate partneři k odeslání.');

            return self::SUCCESS;
        }

        $this->info(sprintf(
            'Měsíční souhrn za %s pro %d partnerů%s',
            $month->format('Y-m'),
            $partners->count(),
            $dryRun ? ' (dry-run)' : ''
        ));

        $sent = 0;
        $skipped = 0;
        $failed = 0;

        foreach ($partners as $partner) {
            $summary = $this->buildSummary($affiliateService, $partner, $month);

            // Partnera bez aktivity a bez zůstatku neotravujeme
            if ($summary['rewards_count'] === 0 && $summary['clicks'] === 0 && $summary['payable_amount'] <= 0) {
                $skipped++;
                $this->line("  - {$partner->email}: bez aktivity, přeskočeno");

                continue;
            }

            if ($dryRun) {
                $sent++;
                $this->line(sprintf(
                    '  - %s: %s odměn, %s kliknutí, k výplatě %s %s',
                    $partner->email,
                    $summary['rewards_count'],
                    $summary['clicks'],
                    number_format($summary['payable_amount'], 2, ',', ' '),
                    $summary['currency']
                ));

                continue;
            }

            try {
                Mail::to($partner->email)->send(new AffiliateMonthlySummary($partner, $month, $summary));
                $sent++;
                $this->line("  - {$partner->email}: odesláno");
            } catch (\Exception $e) {
                $failed++;
                $this->error("  - {$partner->email}: {$e->getMessage()}");
                \Log::error('Failed to send affiliate monthly summary', [
                    'partner_id' => $partner->id,
                    'month' => $month->format('Y-m'),
                    'error' => $e->getMessage(),
                ]);
            }
        }

        $this->newLine();
        $this->info("Odesláno: {$sent}, přeskočeno: {$skipped}, chyby: {$failed}");

        return $failed > 0 ? self::FAILURE : self::SUCCESS;
    }

    /**
     * Měsíc, za který se souhrn počítá (výchozí minulý)
     */
    protected function resolveMonth(): ?Carbon
    {
        $option = $this->option('month');

        if (! $option) {
            return now()->subMonthNoOverflow()->startOfMonth();
        }

        try {
            return Carbon::createFromFormat('Y-m', $option)->startOfMonth();
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Čísla za daný měsíc + aktuální zůstatek
     */
    protected function buildSummary(AffiliateService $affiliateService, User $partner, Carbon $month): array
    {
        $from = $month->copy()->startOfMonth();
        $to = $month->copy()->endOfMonth();

        $rewards = AffiliateReward::where('affiliate_partner_id', $partner->id)
            ->counted()
            ->whereBetween('created_at', [$from, $to])
            ->get();

        $newConversions = $rewards
            ->filter(fn ($r) => $r->reward_type === 'order' || $r->subscription_payment_number === 1)
            ->count();

        $clicks = DB::table('affiliate_link_clicks')
            ->join('affiliate_links', 'affiliate_link_clicks.affiliate_link_id', '=', 'affiliate_links.id')
            ->where('affiliate_links.affiliate_partner_id', $partner->id)
            ->whereBetween('affiliate_link_clicks.clicked_at', [$from, $to])
            ->count();

        $balance = $affiliateService->getPayoutBalance($partner);
        $activeSubscriptions = $affiliateService->getPartnerSubscriptions($partner)->where('is_active', true);

        return [
            'earned' => (float) $rewards->sum('reward_amount'),
            'rewards_count' => $rewards->count(),
            'new_conversions' => $newConversions,
            'clicks' => $clicks,
            'currency' => $balance['currency'],
            'payable_amount' => $balance['amount'],
            'threshold' => $balance['threshold'],
            'threshold_enabled' => $balance['enabled'],
            'threshold_reached' => $balance['reached'],
            'active_subscriptions' => $activeSubscriptions->count(),
            'estimated_monthly_income' => (float) $activeSubscriptions->sum('current_rate'),
        ];
    }
}
