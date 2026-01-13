<?php

namespace App\Console\Commands;

use App\Mail\ApologyNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendApologyEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:send-apology {email : The email address to send the apology to}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send an apology email for the incorrect delivery notification';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $email = $this->argument('email');
        
        // Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->error("Invalid email address: {$email}");
            return Command::FAILURE;
        }

        $this->info("Sending apology email to: {$email}");
        
        try {
            Mail::to($email)->send(new ApologyNotification('cs'));
            
            $this->info('✓ Apology email sent successfully!');
            
            \Log::info('Apology email sent manually', [
                'email' => $email,
                'sent_by' => 'artisan command',
            ]);
            
            return Command::SUCCESS;
            
        } catch (\Exception $e) {
            $this->error('Failed to send email: ' . $e->getMessage());
            
            \Log::error('Failed to send apology email', [
                'email' => $email,
                'error' => $e->getMessage(),
            ]);
            
            return Command::FAILURE;
        }
    }
}
