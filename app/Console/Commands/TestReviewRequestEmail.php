<?php

namespace App\Console\Commands;

use App\Mail\OrderReviewRequestMail;
use App\Mail\SubscriptionReviewRequestMail;
use App\Models\Order;
use App\Models\ReviewRequest;
use App\Models\Subscription;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class TestReviewRequestEmail extends Command
{
    protected $signature = 'email:test-review-request
                            {email? : Kam poslat}
                            {--type=subscription : order nebo subscription}
                            {--id= : Konkrétní objednávka nebo předplatné, jinak poslední}
                            {--cleanup : Smaže všechny testovací žádosti a skončí}';

    protected $description = 'Pošle testovací žádost o hodnocení a založí funkční trackovací odkaz';

    /**
     * Testovací žádosti se poznají podle prefixu tokenu, aby šly uklidit.
     */
    protected const TEST_PREFIX = 'test-';

    public function handle(): int
    {
        if ($this->option('cleanup')) {
            return $this->cleanup();
        }

        $email = $this->argument('email');

        if (! $email) {
            $this->error('Zadej e-mail: php artisan email:test-review-request muj@email.cz');

            return Command::FAILURE;
        }

        $type = $this->option('type');

        if (! in_array($type, ['order', 'subscription'], true)) {
            $this->error("Neznámý typ '{$type}'. Použij order nebo subscription.");

            return Command::FAILURE;
        }

        $subject = $this->resolveSubject($type);

        if (! $subject) {
            $this->error($type === 'order' ? 'Žádná objednávka nenalezena.' : 'Žádné předplatné nenalezeno.');

            return Command::FAILURE;
        }

        // user_id zůstává prázdné schválně. Kdyby se testovací žádost navázala
        // na majitele předplatného, zablokovala by mu na 30 dnů tu opravdovou.
        $reviewRequest = ReviewRequest::create([
            'user_id' => null,
            'email' => $email,
            'order_id' => $type === 'order' ? $subject->id : null,
            'subscription_id' => $type === 'subscription' ? $subject->id : null,
            'review_type' => $type,
            'milestone' => 2,
            'tracking_token' => self::TEST_PREFIX.Str::random(58),
            'email_sent_at' => now(),
        ]);

        try {
            $mailable = $type === 'order'
                ? new OrderReviewRequestMail($subject, $reviewRequest)
                : new SubscriptionReviewRequestMail($subject, $reviewRequest);

            Mail::to($email)->send($mailable);
        } catch (\Throwable $e) {
            $reviewRequest->delete();
            $this->error('Odeslání selhalo: '.$e->getMessage());

            return Command::FAILURE;
        }

        $label = $type === 'order' ? $subject->order_number : $subject->subscription_number;

        $this->info("Odesláno na {$email}");
        $this->line("  podklad: {$label}");
        $this->line('  žádost:  #'.$reviewRequest->id);
        $this->newLine();
        $this->line('  Hvězdičky v e-mailu jsou funkční - klik uloží hodnocení a přesměruje na Google.');
        $this->line('  Odkaz na 5 hvězdiček:');
        $this->line('  '.route('review.track.rating', ['token' => $reviewRequest->tracking_token, 'rating' => 5]));
        $this->newLine();
        $this->line('  Až budeš hotov, ukliď: php artisan email:test-review-request --cleanup');

        return Command::SUCCESS;
    }

    protected function resolveSubject(string $type): Order|Subscription|null
    {
        $id = $this->option('id');

        if ($type === 'order') {
            $query = Order::with(['items.product.roastery', 'user']);

            return $id ? $query->find($id) : $query->whereNotNull('user_id')->latest('id')->first();
        }

        $query = Subscription::with('user');

        return $id ? $query->find($id) : $query->whereHas('user')->latest('id')->first();
    }

    protected function cleanup(): int
    {
        $count = ReviewRequest::where('tracking_token', 'like', self::TEST_PREFIX.'%')->count();

        if ($count === 0) {
            $this->info('Žádné testovací žádosti k úklidu.');

            return Command::SUCCESS;
        }

        ReviewRequest::where('tracking_token', 'like', self::TEST_PREFIX.'%')->delete();
        $this->info("Smazáno testovacích žádostí: {$count}");

        return Command::SUCCESS;
    }
}
