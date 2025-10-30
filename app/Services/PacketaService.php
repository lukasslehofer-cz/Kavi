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
            
            // Determine routing based on country - CZ uses addressId only, others use addressId + carrierPickupPoint
            $country = strtoupper($data['country'] ?? 'CZ');
            $isCarrierPudo = ($country !== 'CZ');
            
            if ($isCarrierPudo) {
                // Carriers PUDOs (international) - use addressId (carrier ID) + carrierPickupPoint (point code)
                // addressId = ID of the carrier (e.g. 106 for DHL, 3060 for InPost)
                // carrierPickupPoint = code of the pickup point (e.g. "BIA10M" or numeric ID)
                $carrierId = $data['carrier_id'] ?? $this->getCarrierIdFromCountry($country);
                $carrierPickupPoint = $data['carrier_pickup_point'] ?? $data['packeta_point_id'];
                
                $packetAttributes->addChild('addressId', $carrierId);
                $packetAttributes->addChild('carrierPickupPoint', $carrierPickupPoint);
                
                Log::info('Using Carriers PUDO structure', [
                    'country' => $country,
                    'addressId' => $carrierId,
                    'carrierPickupPoint' => $carrierPickupPoint,
                ]);
            } else {
                // Packeta PUDOs (CZ) - standard addressId only
                $packetAttributes->addChild('addressId', $data['packeta_point_id']);
                
                Log::info('Using Packeta PUDO structure (CZ)', [
                    'country' => $country,
                    'addressId' => $data['packeta_point_id'],
                ]);
            }
            
            $packetAttributes->addChild('value', number_format($data['value'], 2, '.', ''));
            $packetAttributes->addChild('weight', number_format($data['weight'], 2, '.', ''));
            $packetAttributes->addChild('eshop', $this->senderId);
            
            // Currency - required for international shipments
            $currency = $data['currency'] ?? 'CZK';
            $packetAttributes->addChild('currencyCode', $currency);
            
            if (!empty($data['cod'])) {
                $packetAttributes->addChild('cod', number_format($data['cod'], 2, '.', ''));
            }
            
            if (!empty($data['note'])) {
                $packetAttributes->addChild('note', $data['note']);
            }
            
            // Adult content flag (for international shipments with coffee/alcohol)
            if (!empty($data['adult_content'])) {
                $packetAttributes->addChild('adultContent', '1');
            }

            $xmlString = $xml->asXML();

            Log::info('Packeta API Request', [
                'url' => $this->apiUrl,
                'xml' => $xmlString,
                'is_carrier_pudo' => $isCarrierPudo,
                'point_id' => $data['packeta_point_id'],
                'country' => $data['country'] ?? 'N/A',
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
     * Get pickup point details by ID from v4 API
     * Note: This method may return large datasets and should be used with caution
     *
     * @param string $pointId
     * @return array|null
     */
    public function getPickupPoint(string $pointId): ?array
    {
        // Disabled due to memory issues - API returns too much data
        // Instead, we rely on country-based carrier ID mapping
        return null;
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

    /**
     * Get available carriers (PUDOs) for a country
     * Documentation: https://docs.packeta.com/docs/pudo-delivery/carriers-pudos
     *
     * @param string $countryCode ISO 3166-1 alpha-2 country code (e.g. 'CZ', 'SK')
     * @return array Array of available carriers with their details
     */
    public function getCarriersForCountry(string $countryCode): array
    {
        try {
            // Packeta v4 API endpoint for branches
            $response = Http::get("https://www.zasilkovna.cz/api/v4/{$this->apiKey}/branch.json", [
                'country' => strtoupper($countryCode),
            ]);

            if ($response->successful()) {
                $data = $response->json();
                
                // Group carriers by ID
                $carriers = [];
                if (isset($data['carriers'])) {
                    foreach ($data['carriers'] as $carrier) {
                        $carriers[$carrier['id']] = [
                            'id' => $carrier['id'],
                            'name' => $carrier['name'] ?? '',
                            'country' => $carrier['country'] ?? $countryCode,
                        ];
                    }
                }
                
                return array_values($carriers);
            }

            Log::warning('Packeta PUDOs API request failed', [
                'country' => $countryCode,
                'status' => $response->status(),
            ]);
            
            return [];
        } catch (\Exception $e) {
            Log::error('Packeta PUDOs API Error', [
                'country' => $countryCode,
                'message' => $e->getMessage(),
            ]);
            return [];
        }
    }

    /**
     * Get all pickup points for a specific carrier
     * 
     * DEPRECATED: This method can cause memory issues with large carrier networks.
     * Use Packeta Widget on frontend instead for pickup point selection.
     *
     * @param string $carrierId Carrier ID (e.g. 'zpoint', '131', etc.)
     * @param string $countryCode Country code
     * @return array Array of pickup points
     */
    public function getPickupPointsForCarrier(string $carrierId, string $countryCode): array
    {
        // Disabled due to potential memory issues with large API responses
        // Use Packeta Widget on frontend for pickup point selection instead
        return [];
    }

    /**
     * Get default carriers for common countries
     * These are fallback values if PUDOs API is not available
     *
     * @return array
     */
    public static function getDefaultCarriers(): array
    {
        return [
            'CZ' => [
                ['id' => 'zpoint', 'name' => 'Z-BOX'],
                ['id' => 'packeta', 'name' => 'Zásilkovna'],
            ],
            'SK' => [
                ['id' => '131', 'name' => 'Packeta Slovensko'],
            ],
            'PL' => [
                ['id' => '3060', 'name' => 'InPost Paczkomaty'],
            ],
            'HU' => [
                ['id' => '4159', 'name' => 'Packeta Maďarsko'],
            ],
            'DE' => [
                ['id' => 'de-pp', 'name' => 'DHL Paketshop'],
            ],
            'AT' => [
                ['id' => '4161', 'name' => 'Österreichische Post'],
            ],
            'RO' => [
                ['id' => '4162', 'name' => 'Packeta Rumunsko'],
            ],
        ];
    }

    /**
     * Determine if pickup point is a Carrier PUDO (international) or Packeta PUDO (CZ)
     * 
     * @param string $pointId
     * @return bool
     */
    private function isCarrierPickupPoint(string $pointId): bool
    {
        // Packeta CZ points typically have lower IDs (under 100000)
        // Carrier points (international) have higher IDs (usually 6+ digits)
        // This is a heuristic - ideally we'd check against the carrier/country data
        
        if (!is_numeric($pointId)) {
            return true; // Non-numeric IDs are typically carrier points
        }
        
        $numericId = (int)$pointId;
        
        // CZ Packeta points are typically below 100000
        return $numericId > 100000;
    }

    /**
     * Get carrier ID based on country code
     * 
     * @param string $countryCode
     * @return string
     */
    private function getCarrierIdFromCountry(string $countryCode): string
    {
        $carrierMap = [
            'SK' => '131',      // Packeta Slovakia
            'PL' => '3060',     // InPost Poland
            'HU' => '4159',     // Packeta Hungary
            'DE' => '106',      // DHL Paketshop Germany
            'AT' => '4161',     // Post Austria
            'RO' => '4162',     // Packeta Romania
        ];

        return $carrierMap[strtoupper($countryCode)] ?? '131';
    }
}


