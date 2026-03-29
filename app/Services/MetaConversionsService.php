<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MetaConversionsService
{
    private string $pixelId;
    private string $accessToken;
    private string $apiVersion = 'v21.0';

    public function __construct()
    {
        $this->pixelId = config('services.facebook.pixel_id', '');
        $this->accessToken = config('services.facebook.conversions_api_token', '');
    }

    public function isConfigured(): bool
    {
        return !empty($this->pixelId) && !empty($this->accessToken);
    }

    /**
     * Send a Purchase event to Meta Conversions API.
     */
    public function sendPurchaseEvent(
        string $eventId,
        float $value,
        string $currency,
        array $contentIds,
        string $contentType,
        array $userData,
        ?string $fbp = null,
        ?string $fbc = null,
        ?string $sourceUrl = null,
    ): bool {
        if (!$this->isConfigured()) {
            return false;
        }

        $userDataPayload = $this->buildUserData($userData, $fbp, $fbc);

        $eventData = [
            'event_name' => 'Purchase',
            'event_time' => time(),
            'event_id' => $eventId,
            'action_source' => 'website',
            'user_data' => $userDataPayload,
            'custom_data' => [
                'value' => $value,
                'currency' => strtoupper($currency),
                'content_ids' => array_map('strval', $contentIds),
                'content_type' => $contentType,
                'num_items' => count($contentIds),
            ],
        ];

        if ($sourceUrl) {
            $eventData['event_source_url'] = $sourceUrl;
        }

        $payload = [
            'data' => [$eventData],
        ];

        $testEventCode = config('services.facebook.test_event_code');
        if ($testEventCode) {
            $payload['test_event_code'] = $testEventCode;
        }

        try {
            $url = "https://graph.facebook.com/{$this->apiVersion}/{$this->pixelId}/events";

            $response = Http::timeout(10)
                ->withQueryParameters(['access_token' => $this->accessToken])
                ->post($url, $payload);

            if ($response->successful()) {
                Log::info('Meta CAPI Purchase event sent', [
                    'event_id' => $eventId,
                    'value' => $value,
                    'currency' => $currency,
                ]);
                return true;
            }

            Log::error('Meta CAPI Purchase event failed', [
                'event_id' => $eventId,
                'status' => $response->status(),
                'response' => $response->json(),
            ]);
            return false;
        } catch (\Exception $e) {
            Log::error('Meta CAPI Purchase event exception', [
                'event_id' => $eventId,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    private function buildUserData(array $userData, ?string $fbp, ?string $fbc): array
    {
        $result = [];

        if (!empty($userData['email'])) {
            $result['em'] = [$this->hashPii($userData['email'])];
        }
        if (!empty($userData['phone'])) {
            $result['ph'] = [$this->hashPii($this->normalizePhone($userData['phone']))];
        }
        if (!empty($userData['firstName'])) {
            $result['fn'] = [$this->hashPii($userData['firstName'])];
        }
        if (!empty($userData['lastName'])) {
            $result['ln'] = [$this->hashPii($userData['lastName'])];
        }
        if (!empty($userData['city'])) {
            $result['ct'] = [$this->hashPii($userData['city'])];
        }
        if (!empty($userData['postalCode'])) {
            $result['zp'] = [$this->hashPii($userData['postalCode'])];
        }
        if (!empty($userData['country'])) {
            $result['country'] = [$this->hashPii($userData['country'])];
        }

        if (!empty($fbp)) {
            $result['fbp'] = $fbp;
        }
        if (!empty($fbc)) {
            $result['fbc'] = $fbc;
        }

        if (!empty($userData['clientIpAddress'])) {
            $result['client_ip_address'] = $userData['clientIpAddress'];
        }
        if (!empty($userData['clientUserAgent'])) {
            $result['client_user_agent'] = $userData['clientUserAgent'];
        }
        if (!empty($userData['externalId'])) {
            $result['external_id'] = [$this->hashPii((string) $userData['externalId'])];
        }

        return $result;
    }

    private function hashPii(string $value): string
    {
        return hash('sha256', strtolower(trim($value)));
    }

    private function normalizePhone(string $phone): string
    {
        return preg_replace('/[^0-9+]/', '', $phone);
    }
}
