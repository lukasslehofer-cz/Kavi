<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PacketaService
{
    protected $apiKey;
    protected $apiPassword;
    protected $senderId;
    protected $widgetKey;
    protected $apiUrl = 'https://api.packeta.com/v1';

    public function __construct()
    {
        $this->apiKey = config('services.packeta.api_key');
        $this->apiPassword = config('services.packeta.api_password');
        $this->senderId = config('services.packeta.sender_id');
        $this->widgetKey = config('services.packeta.widget_key');
    }

    /**
     * Get the widget key for Packeta widget initialization
     */
    public function getWidgetKey(): string
    {
        return $this->widgetKey;
    }

    /**
     * Get sender ID
     */
    public function getSenderId(): string
    {
        return $this->senderId;
    }

    /**
     * Create a packet/shipment in Packeta
     *
     * @param array $data Shipment data
     * @return array|null
     */
    public function createPacket(array $data): ?array
    {
        try {
            $response = Http::withBasicAuth($this->apiKey, $this->apiPassword)
                ->post("{$this->apiUrl}/packets", [
                    'senderId' => $this->senderId,
                    'name' => $data['name'],
                    'surname' => $data['surname'] ?? '',
                    'company' => $data['company'] ?? null,
                    'email' => $data['email'],
                    'phone' => $data['phone'],
                    'addressId' => $data['packeta_point_id'],
                    'cod' => $data['cod'] ?? null,
                    'value' => $data['value'],
                    'weight' => $data['weight'],
                    'eshop' => $data['order_number'] ?? null,
                    'note' => $data['note'] ?? null,
                ]);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('Packeta API Error: ' . $response->body());
            return null;
        } catch (\Exception $e) {
            Log::error('Packeta Service Exception: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get pickup point details by ID
     *
     * @param string $pointId
     * @return array|null
     */
    public function getPickupPoint(string $pointId): ?array
    {
        try {
            $response = Http::withBasicAuth($this->apiKey, $this->apiPassword)
                ->get("{$this->apiUrl}/branch-detail/{$pointId}");

            if ($response->successful()) {
                return $response->json();
            }

            return null;
        } catch (\Exception $e) {
            Log::error('Packeta Service Exception: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get tracking info for a packet
     *
     * @param string $packetId
     * @return array|null
     */
    public function getTrackingInfo(string $packetId): ?array
    {
        try {
            $response = Http::withBasicAuth($this->apiKey, $this->apiPassword)
                ->get("{$this->apiUrl}/packets/{$packetId}");

            if ($response->successful()) {
                return $response->json();
            }

            return null;
        } catch (\Exception $e) {
            Log::error('Packeta Service Exception: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Format pickup point address for display
     *
     * @param array $pointData
     * @return string
     */
    public function formatPickupPointAddress(array $pointData): string
    {
        $parts = [];
        
        if (!empty($pointData['street'])) {
            $parts[] = $pointData['street'];
        }
        
        if (!empty($pointData['city'])) {
            $cityPart = $pointData['city'];
            if (!empty($pointData['zip'])) {
                $cityPart = $pointData['zip'] . ' ' . $cityPart;
            }
            $parts[] = $cityPart;
        }

        return implode(', ', $parts);
    }
}

