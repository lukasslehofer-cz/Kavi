<?php

namespace App\Services;

use App\Models\NewsletterSubscriber;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class EcomailService
{
    private string $apiKey;
    private string $listId;
    private string $baseUrl = 'https://api2.ecomailapp.cz';

    public function __construct()
    {
        $this->apiKey = config('services.ecomail.api_key');
        $this->listId = config('services.ecomail.list_id');
    }

    /**
     * Check if the service is configured
     */
    public function isConfigured(): bool
    {
        return !empty($this->apiKey) && !empty($this->listId);
    }

    /**
     * Sync subscribers to Ecomail
     * 
     * @param Collection<NewsletterSubscriber> $subscribers
     * @return array{synced: int, errors: array}
     */
    public function syncSubscribers(Collection $subscribers): array
    {
        if (!$this->isConfigured()) {
            return [
                'synced' => 0,
                'errors' => ['Ecomail API není nakonfigurováno. Nastavte ECOMAIL_API_KEY a ECOMAIL_LIST_ID v .env'],
            ];
        }

        $synced = 0;
        $errors = [];

        // Process in chunks of 3000 (API limit)
        $chunks = $subscribers->chunk(3000);

        foreach ($chunks as $chunk) {
            $subscriberData = [];

            foreach ($chunk as $subscriber) {
                $data = $this->prepareSubscriberData($subscriber);
                if ($data) {
                    $subscriberData[] = $data;
                }
            }

            if (empty($subscriberData)) {
                continue;
            }

            try {
                $response = Http::withHeaders([
                    'key' => $this->apiKey,
                    'Content-Type' => 'application/json',
                ])->post("{$this->baseUrl}/lists/{$this->listId}/subscribe-bulk", [
                    'subscriber_data' => $subscriberData,
                    'update_existing' => true,
                    'skip_confirmation' => true,
                ]);

                if ($response->successful()) {
                    // Mark subscribers as synced
                    $emails = collect($subscriberData)->pluck('email');
                    NewsletterSubscriber::whereIn('email', $emails)
                        ->update(['ecomail_synced_at' => now()]);
                    
                    $synced += count($subscriberData);
                    
                    Log::info('Ecomail sync successful', [
                        'count' => count($subscriberData),
                        'response' => $response->json(),
                    ]);
                } else {
                    $error = $response->json('message') ?? $response->body();
                    $errors[] = "API error: {$error}";
                    
                    Log::error('Ecomail sync failed', [
                        'status' => $response->status(),
                        'response' => $response->body(),
                    ]);
                }
            } catch (\Exception $e) {
                $errors[] = "Exception: {$e->getMessage()}";
                Log::error('Ecomail sync exception', ['error' => $e->getMessage()]);
            }
        }

        return [
            'synced' => $synced,
            'errors' => $errors,
        ];
    }

    /**
     * Prepare subscriber data for Ecomail API
     */
    private function prepareSubscriberData(NewsletterSubscriber $subscriber): ?array
    {
        // Find user by email (not by user_id relation which may be NULL)
        $user = User::where('email', $subscriber->email)->first();

        $data = [
            'email' => $subscriber->email,
        ];

        // Add user-specific data if available
        if ($user) {
            if ($user->name) {
                $data['name'] = $user->name;
            }

            $data['tags'] = $this->getSubscriberTags($user);
            
            $data['custom_fields'] = [
                'registered_at' => $user->created_at?->format('Y-m-d'),
                'locale' => $user->locale ?? 'cs',
            ];
        } else {
            // For subscribers without user account (form signups)
            $data['tags'] = [];
            $data['custom_fields'] = [
                'registered_at' => $subscriber->created_at?->format('Y-m-d'),
                'locale' => 'cs', // Default locale for form signups
            ];
        }

        return $data;
    }

    /**
     * Get tags for a subscriber based on their purchase history
     */
    public function getSubscriberTags(User $user): array
    {
        $tags = [];

        // Check for any subscription (active, cancelled, paused, etc.)
        $hasSubscription = $user->subscriptions()->exists();
        if ($hasSubscription) {
            $tags[] = 'predplatne';
        }

        // Check for one-time orders (orders without subscription_id)
        $hasOneTimeOrder = $user->orders()
            ->whereNull('subscription_id')
            ->exists();
        if ($hasOneTimeOrder) {
            $tags[] = 'jednorazova';
        }

        return $tags;
    }

    /**
     * Get tags for display purposes (static method for use in views)
     * Looks up User by email to handle cases where user_id is not set
     */
    public static function calculateTags(?string $email): array
    {
        if (!$email) {
            return [];
        }

        $user = User::where('email', $email)->first();
        if (!$user) {
            return [];
        }

        $tags = [];

        if ($user->subscriptions()->exists()) {
            $tags[] = 'predplatne';
        }

        if ($user->orders()->whereNull('subscription_id')->exists()) {
            $tags[] = 'jednorazova';
        }

        return $tags;
    }
}
