<?php

namespace App\Console\Commands;

use App\Mail\AccountDeleted;
use App\Mail\EmailChangeConfirmation;
use App\Mail\MagicLoginLink;
use App\Mail\WelcomeAfterMigration;
use App\Mail\WelcomeEmail;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;

class TestAccountEmails extends Command
{
    protected $signature = 'email:test-account-all {email} {--user-id=}';
    protected $description = 'Send all account-related test emails to specified address';

    public function handle()
    {
        $email = $this->argument('email');
        $userId = $this->option('user-id');

        if ($userId) {
            $user = User::find($userId);
            if (!$user) {
                $this->error("User with ID {$userId} not found!");
                return 1;
            }
        } else {
            $user = User::first();
            if (!$user) {
                $this->error('No users found in database. Create a user first.');
                return 1;
            }
            $this->info("Using first user: {$user->email}");
        }

        $this->info('Sending test account emails to: ' . $email);
        $this->info('');

        // 1. Welcome Email
        $this->info('1/6 Sending: Welcome Email...');
        try {
            Mail::to($email)->send(new WelcomeEmail($user, 'cs'));
            $this->info('✓ Sent!');
        } catch (\Exception $e) {
            $this->error('✗ Failed: ' . $e->getMessage());
        }
        sleep(1);

        // 2. Magic Login Link
        $this->info('2/6 Sending: Magic Login Link...');
        try {
            $loginUrl = route('home') . '?magic_token=test_token_123';
            Mail::to($email)->send(new MagicLoginLink($loginUrl, 15, 'cs'));
            $this->info('✓ Sent!');
        } catch (\Exception $e) {
            $this->error('✗ Failed: ' . $e->getMessage());
        }
        sleep(1);

        // 3. Password Reset (using view directly since it uses Laravel's notification)
        $this->info('3/6 Sending: Password Reset...');
        try {
            $token = Password::createToken($user);
            $url = route('password.reset', ['token' => $token]) . '?email=' . urlencode($user->email);
            $locale = 'cs';
            $count = 60; // minutes
            $siteName = 'KAVI.cz';
            $contactEmail = 'info@kavi.cz';
            
            Mail::send('emails.reset-password', compact('url', 'locale', 'count', 'siteName', 'contactEmail'), function ($message) use ($email) {
                $message->to($email)
                    ->subject('Reset hesla - KAVI.cz');
            });
            $this->info('✓ Sent!');
        } catch (\Exception $e) {
            $this->error('✗ Failed: ' . $e->getMessage());
        }
        sleep(1);

        // 4. Email Change Confirmation
        $this->info('4/6 Sending: Email Change Confirmation...');
        try {
            $newEmail = 'new-' . $user->email;
            $confirmationUrl = route('home') . '?confirm_email_change=test_token_456';
            Mail::to($email)->send(new EmailChangeConfirmation($user, $newEmail, $confirmationUrl, 'cs'));
            $this->info('✓ Sent!');
        } catch (\Exception $e) {
            $this->error('✗ Failed: ' . $e->getMessage());
        }
        sleep(1);

        // 5. Account Deleted
        $this->info('5/6 Sending: Account Deleted...');
        try {
            Mail::to($email)->send(new AccountDeleted($user->email, 'cs'));
            $this->info('✓ Sent!');
        } catch (\Exception $e) {
            $this->error('✗ Failed: ' . $e->getMessage());
        }
        sleep(1);

        // 6. Welcome After Migration
        $this->info('6/6 Sending: Welcome After Migration...');
        try {
            $subscription = $user->activeSubscription ?? \App\Models\Subscription::latest()->first();
            Mail::to($email)->send(new WelcomeAfterMigration($user, $subscription, 'cs'));
            $this->info('✓ Sent!');
        } catch (\Exception $e) {
            $this->error('✗ Failed: ' . $e->getMessage());
        }

        $this->info('');
        $this->info('✅ All account emails sent!');
        $this->info('Check your inbox at: ' . $email);

        return 0;
    }
}
