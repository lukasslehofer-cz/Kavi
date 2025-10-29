<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CleanupExpiredLoginTokens extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auth:cleanup-login-tokens';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up expired and used magic login tokens';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Cleaning up expired login tokens...');

        // Delete tokens that are expired or were used more than 24 hours ago
        $deleted = DB::table('login_tokens')
            ->where(function ($query) {
                $query->where('expires_at', '<', now())
                      ->orWhere(function ($q) {
                          $q->whereNotNull('used_at')
                            ->where('used_at', '<', now()->subDay());
                      });
            })
            ->delete();

        $this->info("Deleted {$deleted} expired/used login tokens.");
        
        return Command::SUCCESS;
    }
}
