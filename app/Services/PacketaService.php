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
            
            // Determine routing based on carrier type
            $country = strtoupper($data['country'] ?? 'CZ');
            $carrierId = $data['carrier_id'] ?? $this->getCarrierIdFromCountry($country);
            
            // Packeta own network carriers (use addressId only, no carrierPickupPoint)
            $packetaOwnCarriers = ['packeta', 'zpoint', '131', '7987', '4159', '4162', '4163', '13'];
            $isPacketaOwn = in_array($carrierId, $packetaOwnCarriers);
            
            if ($isPacketaOwn) {
                // Packeta own network (CZ, SK, etc.) - use addressId only
                // Widget v6 may return IDs with country prefix (e.g. "hu769"), but API expects just "769"
                $pointId = $data['packeta_point_id'];
                
                // Remove country prefix if present (e.g. "hu769" -> "769", "cz123" -> "123")
                if (preg_match('/^[a-z]{2}(\d+)$/i', $pointId, $matches)) {
                    $pointId = $matches[1];
                }
                
                $packetAttributes->addChild('addressId', $pointId);
                
                Log::info('Using Packeta own network structure', [
                    'country' => $country,
                    'carrier_id' => $carrierId,
                    'original_point_id' => $data['packeta_point_id'],
                    'cleaned_addressId' => $pointId,
                ]);
            } else {
                // External carriers (InPost, DHL, etc.) - use addressId (carrier ID) + carrierPickupPoint (point code)
                $carrierPickupPoint = $data['carrier_pickup_point'] ?? $data['packeta_point_id'];
                
                $packetAttributes->addChild('addressId', $carrierId);
                $packetAttributes->addChild('carrierPickupPoint', $carrierPickupPoint);
                
                Log::info('Using external carrier structure', [
                    'country' => $country,
                    'carrier_id' => $carrierId,
                    'carrierPickupPoint' => $carrierPickupPoint,
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
                'is_packeta_own' => $isPacketaOwn,
                'is_external_carrier' => !$isPacketaOwn,
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
     * NOTE: Due to large API responses causing timeouts and memory issues,
     * this method is disabled and returns empty array to force use of default carriers.
     *
     * @param string $countryCode ISO 3166-1 alpha-2 country code (e.g. 'CZ', 'SK')
     * @return array Array of available carriers with their details
     */
    public function getCarriersForCountry(string $countryCode): array
    {
        // DISABLED: Packeta v4 API returns too much data (20-30MB) causing timeouts and memory issues
        // Using default carriers instead for better performance and reliability
        
        Log::info('Packeta carriers requested - using default carriers', [
            'country' => $countryCode,
            'reason' => 'API disabled due to large response size',
        ]);
        
        return [];
        
        /* ORIGINAL CODE - DISABLED DUE TO API ISSUES
        try {
            // Packeta v4 API endpoint for branches
            $response = Http::timeout(5)->get("https://www.zasilkovna.cz/api/v4/{$this->apiKey}/branch.json", [
                'country' => strtoupper($countryCode),
            ]);

            if ($response->successful()) {
                $data = $response->json();
                
                // Group carriers by ID
                $carriers = [];
                if (isset($data['carriers'])) {
                    foreach ($data['carriers'] as $carrier) {
                        $carrierName = $carrier['name'] ?? '';
                        
                        // Filter out Home Delivery (HD) carriers - we only deliver to pick-up points and boxes
                        if (stripos($carrierName, ' HD') !== false || 
                            stripos($carrierName, 'Home Delivery') !== false ||
                            stripos($carrierName, 'domů') !== false) {
                            continue;
                        }
                        
                        $carriers[$carrier['id']] = [
                            'id' => $carrier['id'],
                            'name' => $carrierName,
                            'country' => $carrier['country'] ?? $countryCode,
                        ];
                    }
                }
                
                // Sort carriers alphabetically by name
                usort($carriers, function($a, $b) {
                    return strcasecmp($a['name'], $b['name']);
                });
                
                return $carriers;
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
        */
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
     * NOTE: Only pick-up points and boxes, NO home delivery (HD) carriers
     *
     * @return array
     */
    public static function getDefaultCarriers(): array
    {
        return [
            'CZ' => [
                ['id' => 'packeta', 'name' => 'Packeta PP'],
                ['id' => 'zpoint', 'name' => 'Z-BOX'],
            ],
            'SK' => [
                ['id' => '131', 'name' => 'Packeta PP'],
                ['id' => '7987', 'name' => 'Packeta Z-Point PP'],
            ],
            'PL' => [
                ['id' => '3060', 'name' => 'InPost Paczkomaty'],
                ['id' => '8571', 'name' => 'Packeta PP'],
            ],
            'HU' => [
                ['id' => '4159', 'name' => 'Packeta PP'],
                ['id' => '4161', 'name' => 'Hungarian Post Box'],
            ],
            'DE' => [
                ['id' => '3294', 'name' => 'DHL Paketshop'],
                ['id' => '10779', 'name' => 'Hermes Paketshop'],
            ],
            'AT' => [
                ['id' => '4161', 'name' => 'Österreichische Post'],
                ['id' => '13', 'name' => 'Packeta PP'],
            ],
            'RO' => [
                ['id' => '4162', 'name' => 'Packeta PP'],
                ['id' => '8794', 'name' => 'Sameday Box'],
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
        // Fallback carrier IDs for each country - PICKUP POINTS ONLY (no HD!)
        // These are used when widget doesn't provide carrierId (Packeta own network)
        $carrierMap = [
            'CZ' => 'packeta',  // CZ Zásilkovna PP
            'SK' => '131',      // SK Packeta PP
            'PL' => '3060',     // PL InPost Paczkomaty Box
            'HU' => '4539',     // HU Maďarská pošta PP (NOT 4159 which is HD!)
            'DE' => '6828',     // DE Hermes PP
            'AT' => '13',       // AT Packeta PP
            'RO' => '4162',     // RO Packeta PP
            'SI' => '4163',     // SI Packeta PP
        ];

        return $carrierMap[strtoupper($countryCode)] ?? 'packeta';
    }
}


