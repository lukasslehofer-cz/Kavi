<?php

namespace App\Services;

use App\Helpers\MetaPixelHelper;
use App\Helpers\MetaPurchaseData;
use App\Models\Order;
use App\Models\Subscription;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MetaConversionsService
{
    // Předvolby pro čísla zadaná bez +/00 (validace PhoneNumber je připouští)
    private const DIAL_CODES = [
        'CZ' => '420',
        'SK' => '421',
        'AT' => '43',
        'DE' => '49',
        'PL' => '48',
        'HU' => '36',
    ];

    private string $pixelId;
    private string $accessToken;
    private ?string $testEventCode;
    private ?string $region;
    private string $apiVersion = 'v21.0';

    public function __construct(?string $region = null)
    {
        $this->region = $region;

        if ($region !== null) {
            $config = MetaPixelHelper::configFor($region);
            $this->pixelId = $config['pixel_id'] ?? '';
            $this->accessToken = $config['conversions_api_token'] ?? '';
            $this->testEventCode = $config['test_event_code'] ?? null;
        } else {
            $this->pixelId = config('services.facebook.pixel_id') ?? '';
            $this->accessToken = config('services.facebook.conversions_api_token') ?? '';
            $this->testEventCode = config('services.facebook.test_event_code');
        }
    }

    public static function forRegion(string $region): self
    {
        return new self($region);
    }

    public function isConfigured(): bool
    {
        return !empty($this->pixelId) && !empty($this->accessToken);
    }

    /**
     * Purchase za zaplacenou objednávku (e-shop, dárek, poukaz). Volá ho webhook
     * i děkovací stránka – odejde jen jednou (meta_capi_sent_at).
     *
     * $fallbackIp / $fallbackUserAgent: jen když je v objednávce nemáme uložené
     * (objednávky založené před uložením meta_client_*); z webhooku je nepředávat.
     */
    public static function sendOrderPurchase(Order $order, ?string $fallbackIp = null, ?string $fallbackUserAgent = null): void
    {
        try {
            $service = self::forRegion(MetaPixelHelper::regionFor($order));
            if (! $service->isConfigured() || $order->meta_capi_sent_at) {
                return;
            }

            $address = $order->shipping_address ?? [];
            $user = $order->user;
            [$firstName, $lastName] = self::splitName($address['name'] ?? null);

            $sent = $service->sendPurchaseEvent(
                purchase: MetaPurchaseData::forOrder($order),
                userData: [
                    'email' => $address['email'] ?? $user?->email,
                    'phone' => $address['phone'] ?? $user?->phone,
                    'firstName' => $firstName,
                    'lastName' => $lastName,
                    'city' => $address['billing_city'] ?? null,
                    'postalCode' => $address['billing_postal_code'] ?? null,
                    'country' => $address['billing_country'] ?? null,
                    'externalId' => $user?->id,
                    'clientIpAddress' => $order->meta_client_ip ?: $fallbackIp,
                    'clientUserAgent' => $order->meta_client_user_agent ?: $fallbackUserAgent,
                ],
                fbp: $order->meta_fbp,
                fbc: $order->meta_fbc,
                sourceUrl: route('order.confirmation', $order),
            );

            if ($sent) {
                $order->update([
                    'meta_capi_sent_at' => now(),
                    // IP a prohlížeč slouží jen pro tuto událost
                    'meta_client_ip' => null,
                    'meta_client_user_agent' => null,
                ]);
            }
        } catch (\Throwable $e) {
            Log::error('Meta CAPI failed for order', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Purchase za první platbu předplatného nebo jednorázového boxu. Opakované
     * platby (cron, doplacení) sem nepatří a Purchase neposílají.
     */
    public static function sendSubscriptionPurchase(Subscription $subscription): void
    {
        try {
            $service = self::forRegion(MetaPixelHelper::regionFor($subscription));
            if (! $service->isConfigured() || $subscription->meta_capi_sent_at) {
                return;
            }

            $address = $subscription->shipping_address ?? [];
            $user = $subscription->user;
            [$firstName, $lastName] = self::splitName($address['name'] ?? null);

            $sent = $service->sendPurchaseEvent(
                purchase: MetaPurchaseData::forSubscription($subscription),
                userData: [
                    'email' => $address['email'] ?? $user?->email,
                    'phone' => $address['phone'] ?? $user?->phone,
                    'firstName' => $firstName,
                    'lastName' => $lastName,
                    'city' => $address['billing_city'] ?? null,
                    'postalCode' => $address['billing_postal_code'] ?? null,
                    'country' => $address['country'] ?? $address['billing_country'] ?? null,
                    'externalId' => $user?->id,
                    'clientIpAddress' => $subscription->meta_client_ip,
                    'clientUserAgent' => $subscription->meta_client_user_agent,
                ],
                fbp: $subscription->meta_fbp,
                fbc: $subscription->meta_fbc,
                sourceUrl: route('subscriptions.confirmation', $subscription),
            );

            if ($sent) {
                $subscription->update([
                    'meta_capi_sent_at' => now(),
                    'meta_client_ip' => null,
                    'meta_client_user_agent' => null,
                ]);
            }
        } catch (\Throwable $e) {
            Log::error('Meta CAPI failed for subscription', [
                'subscription_id' => $subscription->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Send a Purchase event to Meta Conversions API.
     *
     * @param  array{event_id: string, custom_data: array<string, mixed>}  $purchase  z MetaPurchaseData
     */
    public function sendPurchaseEvent(
        array $purchase,
        array $userData,
        ?string $fbp = null,
        ?string $fbc = null,
        ?string $sourceUrl = null,
    ): bool {
        if (!$this->isConfigured()) {
            return false;
        }

        $eventId = $purchase['event_id'];
        $customData = $purchase['custom_data'];

        $eventData = [
            'event_name' => 'Purchase',
            'event_time' => time(),
            'event_id' => $eventId,
            'action_source' => 'website',
            'user_data' => $this->buildUserData($userData, $fbp, $fbc),
            'custom_data' => $customData,
        ];

        if ($sourceUrl) {
            $eventData['event_source_url'] = $sourceUrl;
        }

        return $this->postEvent($eventData, [
            'value' => $customData['value'] ?? null,
            'currency' => $customData['currency'] ?? null,
            'content_category' => $customData['content_category'] ?? null,
        ]);
    }

    /**
     * Lead za odeslaný formulář (poptávka firemních dárků). Pixel v prohlížeči posílá
     * Lead se stejným event_id, Meta je podle něj spáruje.
     */
    public function sendLeadEvent(
        string $eventId,
        array $userData,
        array $customData = [],
        ?string $fbp = null,
        ?string $fbc = null,
        ?string $sourceUrl = null,
    ): bool {
        if (!$this->isConfigured()) {
            return false;
        }

        $eventData = [
            'event_name' => 'Lead',
            'event_time' => time(),
            'event_id' => $eventId,
            'action_source' => 'website',
            'user_data' => $this->buildUserData($userData, $fbp, $fbc),
        ];

        if ($customData) {
            $eventData['custom_data'] = $customData;
        }
        if ($sourceUrl) {
            $eventData['event_source_url'] = $sourceUrl;
        }

        return $this->postEvent($eventData, [
            'content_name' => $customData['content_name'] ?? null,
        ]);
    }

    private function postEvent(array $eventData, array $logContext): bool
    {
        $eventName = $eventData['event_name'];

        $payload = [
            'data' => [$eventData],
        ];

        if ($this->testEventCode) {
            $payload['test_event_code'] = $this->testEventCode;
        }

        $logContext = ['event_id' => $eventData['event_id']] + $logContext + [
            'user_data_keys' => array_keys($eventData['user_data']),
            'region' => $this->region,
            'pixel_id' => $this->pixelId,
        ];

        try {
            $url = "https://graph.facebook.com/{$this->apiVersion}/{$this->pixelId}/events";

            $response = Http::timeout(10)
                ->withQueryParameters(['access_token' => $this->accessToken])
                ->post($url, $payload);

            if ($response->successful()) {
                Log::info("Meta CAPI {$eventName} event sent", $logContext);
                return true;
            }

            Log::error("Meta CAPI {$eventName} event failed", $logContext + [
                'status' => $response->status(),
                'response' => $response->json(),
            ]);
            return false;
        } catch (\Exception $e) {
            Log::error("Meta CAPI {$eventName} event exception", $logContext + [
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Normalizace podle Meta (customer information parameters): vše malými písmeny,
     * jména bez interpunkce, město a PSČ bez mezer, telefon jen číslice s předvolbou.
     * Hashuje se em, ph, fn, ln, ct, zp, country a external_id; fbp, fbc, IP
     * a user agent jdou nehashované.
     */
    private function buildUserData(array $userData, ?string $fbp, ?string $fbc): array
    {
        $result = [];

        $hashed = [
            'em' => $this->normalizeText($userData['email'] ?? null),
            'ph' => $this->normalizePhone($userData['phone'] ?? null, $userData['country'] ?? null),
            'fn' => $this->normalizeName($userData['firstName'] ?? null),
            'ln' => $this->normalizeName($userData['lastName'] ?? null),
            'ct' => $this->normalizeCompact($userData['city'] ?? null),
            'zp' => $this->normalizeCompact($userData['postalCode'] ?? null),
            'country' => $this->normalizeText($userData['country'] ?? null),
            'external_id' => $this->normalizeText(isset($userData['externalId']) ? (string) $userData['externalId'] : null),
        ];

        foreach ($hashed as $key => $value) {
            if ($value !== null && $value !== '') {
                $result[$key] = [hash('sha256', $value)];
            }
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

        return $result;
    }

    /**
     * @return array{0: ?string, 1: ?string}
     */
    private static function splitName(?string $name): array
    {
        $parts = preg_split('/\s+/u', trim((string) $name), 2);

        return [($parts[0] ?? '') ?: null, ($parts[1] ?? '') ?: null];
    }

    private function normalizeText(?string $value): ?string
    {
        return $value === null ? null : mb_strtolower(trim($value));
    }

    // "Nováková-Svobodová" → "novákovásvobodová" (diakritika zůstává, Meta chce UTF-8)
    private function normalizeName(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        return trim(preg_replace('/\s+/u', ' ', preg_replace('/\p{P}/u', '', mb_strtolower($value))));
    }

    // "Praha 4" → "praha4", "110 00" → "11000"
    private function normalizeCompact(?string $value): ?string
    {
        return $value === null ? null : preg_replace('/[\s\p{P}]+/u', '', mb_strtolower($value));
    }

    // "+420 723 123 456", "00420723123456" i "723123456" (CZ) → "420723123456"
    private function normalizePhone(?string $phone, ?string $country): ?string
    {
        if ($phone === null || trim($phone) === '') {
            return null;
        }

        $international = str_starts_with(ltrim($phone), '+');
        $digits = preg_replace('/\D/', '', $phone);

        if (str_starts_with($digits, '00')) {
            return substr($digits, 2);
        }
        if ($international) {
            return $digits;
        }

        $dialCode = self::DIAL_CODES[strtoupper((string) $country)] ?? null;
        if ($dialCode === null || (str_starts_with($digits, $dialCode) && strlen($digits) > 10)) {
            return $digits;
        }

        // Národní tvar: bez úvodní nuly (SK 0905…, AT/DE 0…) a s předvolbou země
        return $dialCode.ltrim($digits, '0');
    }
}
