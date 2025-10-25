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
    protected $apiUrl = 'https://www.zasilkovna.cz/api/rest';

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
            // Packeta API uses XML format with apiPassword as CHILD ELEMENT (not attribute!)
            $xml = new \SimpleXMLElement('<?xml version="1.0" encoding="utf-8"?><createPacket/>');
            
            // API password as child element (not attribute!)
            $xml->addChild('apiPassword', $this->apiPassword);
            
            $packetAttributes = $xml->addChild('packetAttributes');
            $packetAttributes->addChild('number', $data['order_number'] ?? '');
            $packetAttributes->addChild('name', $data['name']);
            $packetAttributes->addChild('surname', $data['surname'] ?? '');
            $packetAttributes->addChild('email', $data['email']);
            $packetAttributes->addChild('phone', $data['phone']);
            $packetAttributes->addChild('addressId', $data['packeta_point_id']);
            $packetAttributes->addChild('value', number_format($data['value'], 2, '.', ''));
            $packetAttributes->addChild('weight', number_format($data['weight'], 2, '.', ''));
            $packetAttributes->addChild('eshop', $this->senderId);
            
            if (!empty($data['cod'])) {
                $packetAttributes->addChild('cod', number_format($data['cod'], 2, '.', ''));
            }
            
            if (!empty($data['note'])) {
                $packetAttributes->addChild('note', $data['note']);
            }

            $xmlString = $xml->asXML();

            Log::info('Packeta API Request', [
                'url' => $this->apiUrl,
                'xml' => $xmlString,
                'api_password_length' => strlen($this->apiPassword),
                'api_password_start' => substr($this->apiPassword, 0, 8) . '...',
            ]);

            $response = Http::withHeaders([
                'Content-Type' => 'text/xml; charset=utf-8',
            ])->send('POST', $this->apiUrl, [
                'body' => $xmlString,
            ]);

            Log::info('Packeta API Response', [
                'status' => $response->status(),
                'successful' => $response->successful(),
                'body' => $response->body(),
            ]);

            if ($response->successful()) {
                // Parse XML response
                $responseXml = simplexml_load_string($response->body());
                
                if ($responseXml && $responseXml->getName() === 'response') {
                    $status = (string)$responseXml->status;
                    
                    if ($status === 'ok') {
                        return [
                            'id' => (string)$responseXml->result->id,
                            'barcode' => (string)$responseXml->result->barcode ?? null,
                        ];
                    } else {
                        Log::error('Packeta API returned error', [
                            'status' => $status,
                            'fault' => (string)($responseXml->fault ?? 'Unknown error'),
                            'detail' => (string)($responseXml->detail ?? ''),
                        ]);
                        return null;
                    }
                }
            }

            Log::error('Packeta API Error', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            return null;
        } catch (\Exception $e) {
            Log::error('Packeta Service Exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'data' => $data,
            ]);
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


