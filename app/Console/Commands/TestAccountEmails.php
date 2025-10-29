<?php

namespace App\Console\Commands;

use App\Mail\EmailChangeConfirmation;
use App\Mail\PaymentMethodChanged;
use App\Mail\WelcomeEmail;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class TestAccountEmails extends Command
{
    protected $signature = 'email:test-account-all {email}';
    protected $description = 'Send all account-related emails to test address';

    public function handle()
    {
        $email = $this->argument('email');
        
        // Get a user or create test data
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            $user = User::latest()->first();
            if (!$user) {
                $this->error('No users found in database');
                return 1;
            }
        }
        
        $this->info('Using user: ' . $user->email);
        $this->info('Sending test emails to: ' . $email);
        $this->info('');
        
        // 1. Welcome Email
        $this->info('1/4 Sending: Welcome Email...');
        Mail::to($email)->send(new WelcomeEmail($user));
        $this->info('✓ Sent!');
        sleep(2);
        
        // 2. Reset Password
        $this->info('2/4 Sending: Reset Password...');
        $token = Password::broker()->createToken($user);
        $user->sendPasswordResetNotification($token);
        $this->info('✓ Sent!');
        sleep(2);
        
        // 3. Email Change Confirmation
        $this->info('3/4 Sending: Email Change Confirmation...');
        $testToken = Str::random(60);
        Mail::to($email)->send(new EmailChangeConfirmation($user, 'new-email@example.com', $testToken));
        $this->info('✓ Sent!');
        sleep(2);
        
        // 4. Payment Method Changed
        $this->info('4/4 Sending: Payment Method Changed...');
        Mail::to($email)->send(new PaymentMethodChanged($user, '4242', 'Visa'));
        $this->info('✓ Sent!');
        
        $this->info('');
        $this->info('✅ All 4 account emails sent successfully!');
        $this->info('Check your inbox at: ' . $email);
        
        return 0;
    }
}

