<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class TranslationService
{
    private string $apiUrl;
    private ?string $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.deepl.api_key');
        // DeepL Free API uses different URL than Pro
        $this->apiUrl = config('services.deepl.api_url', 'https://api-free.deepl.com/v2');
    }

    /**
     * Check if the service is configured and available
     */
    public function isAvailable(): bool
    {
        return !empty($this->apiKey);
    }

    /**
     * Translate text from Czech to English using DeepL API
     *
     * @param string $text The text to translate
     * @param string $sourceLang Source language (default: CS for Czech)
     * @param string $targetLang Target language (default: EN for English)
     * @return array{success: bool, translation?: string, error?: string}
     */
    public function translate(string $text, string $sourceLang = 'CS', string $targetLang = 'EN'): array
    {
        if (!$this->isAvailable()) {
            return [
                'success' => false,
                'error' => 'DeepL API není nakonfigurováno. Přidejte DEEPL_API_KEY do .env souboru.',
            ];
        }

        $text = trim($text);
        
        if (empty($text)) {
            return [
                'success' => false,
                'error' => 'Text pro překlad je prázdný.',
            ];
        }

        // Use cache to avoid duplicate API calls for same text
        $cacheKey = 'translation_' . md5($text . $sourceLang . $targetLang);
        
        if (Cache::has($cacheKey)) {
            return [
                'success' => true,
                'translation' => Cache::get($cacheKey),
                'cached' => true,
            ];
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'DeepL-Auth-Key ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post($this->apiUrl . '/translate', [
                'text' => [$text],
                'source_lang' => $sourceLang,
                'target_lang' => $targetLang,
                'tag_handling' => 'html',
            ]);

            if ($response->successful()) {
                $data = $response->json();
                
                if (isset($data['translations'][0]['text'])) {
                    $translation = $data['translations'][0]['text'];
                    
                    // Cache the translation for 24 hours
                    Cache::put($cacheKey, $translation, now()->addHours(24));
                    
                    Log::info('DeepL translation successful', [
                        'source_lang' => $sourceLang,
                        'target_lang' => $targetLang,
                        'text_length' => strlen($text),
                    ]);
                    
                    return [
                        'success' => true,
                        'translation' => $translation,
                    ];
                }
                
                return [
                    'success' => false,
                    'error' => 'Neočekávaná odpověď z DeepL API.',
                ];
            }

            $errorMessage = $response->json('message') ?? 'Neznámá chyba';
            
            Log::error('DeepL translation failed', [
                'status' => $response->status(),
                'error' => $errorMessage,
            ]);

            // Handle specific error codes
            if ($response->status() === 403) {
                return [
                    'success' => false,
                    'error' => 'Neplatný API klíč DeepL.',
                ];
            }
            
            if ($response->status() === 456) {
                return [
                    'success' => false,
                    'error' => 'Byl překročen měsíční limit přeložených znaků.',
                ];
            }

            return [
                'success' => false,
                'error' => 'Chyba překladu: ' . $errorMessage,
            ];

        } catch (\Exception $e) {
            Log::error('DeepL translation exception', [
                'message' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'error' => 'Chyba připojení k překladové službě.',
            ];
        }
    }

    /**
     * Translate multiple texts at once (batch translation)
     *
     * @param array $texts Array of texts to translate
     * @param string $sourceLang Source language
     * @param string $targetLang Target language
     * @return array{success: bool, translations?: array, error?: string}
     */
    public function translateBatch(array $texts, string $sourceLang = 'CS', string $targetLang = 'EN'): array
    {
        if (!$this->isAvailable()) {
            return [
                'success' => false,
                'error' => 'DeepL API není nakonfigurováno.',
            ];
        }

        $texts = array_map('trim', $texts);
        $texts = array_filter($texts); // Remove empty strings

        if (empty($texts)) {
            return [
                'success' => false,
                'error' => 'Žádné texty k překladu.',
            ];
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'DeepL-Auth-Key ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post($this->apiUrl . '/translate', [
                'text' => array_values($texts),
                'source_lang' => $sourceLang,
                'target_lang' => $targetLang,
                'tag_handling' => 'html',
            ]);

            if ($response->successful()) {
                $data = $response->json();
                
                if (isset($data['translations'])) {
                    $translations = array_map(function ($item) {
                        return $item['text'] ?? '';
                    }, $data['translations']);
                    
                    return [
                        'success' => true,
                        'translations' => $translations,
                    ];
                }
            }

            return [
                'success' => false,
                'error' => 'Chyba při hromadném překladu.',
            ];

        } catch (\Exception $e) {
            Log::error('DeepL batch translation exception', [
                'message' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'error' => 'Chyba připojení k překladové službě.',
            ];
        }
    }

    /**
     * Get DeepL API usage statistics
     *
     * @return array{success: bool, usage?: array, error?: string}
     */
    public function getUsage(): array
    {
        if (!$this->isAvailable()) {
            return [
                'success' => false,
                'error' => 'DeepL API není nakonfigurováno.',
            ];
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'DeepL-Auth-Key ' . $this->apiKey,
            ])->get($this->apiUrl . '/usage');

            if ($response->successful()) {
                $data = $response->json();
                
                return [
                    'success' => true,
                    'usage' => [
                        'character_count' => $data['character_count'] ?? 0,
                        'character_limit' => $data['character_limit'] ?? 500000,
                    ],
                ];
            }

            return [
                'success' => false,
                'error' => 'Nepodařilo se získat informace o využití.',
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => 'Chyba připojení k překladové službě.',
            ];
        }
    }
}

