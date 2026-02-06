<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\TranslationService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class TranslationController extends Controller
{
    private TranslationService $translationService;

    public function __construct(TranslationService $translationService)
    {
        $this->middleware(['auth', 'admin']);
        $this->translationService = $translationService;
    }

    /**
     * Translate text from Czech to English
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function translate(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'text' => 'required|string|max:50000',
            'source_lang' => 'nullable|string|size:2',
            'target_lang' => 'nullable|string|size:2',
        ]);

        $sourceLang = strtoupper($validated['source_lang'] ?? 'CS');
        $targetLang = strtoupper($validated['target_lang'] ?? 'EN');

        $result = $this->translationService->translate(
            $validated['text'],
            $sourceLang,
            $targetLang
        );

        if ($result['success']) {
            return response()->json([
                'success' => true,
                'translation' => $result['translation'],
                'cached' => $result['cached'] ?? false,
            ]);
        }

        return response()->json([
            'success' => false,
            'error' => $result['error'],
        ], 422);
    }

    /**
     * Translate multiple texts at once
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function translateBatch(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'texts' => 'required|array|max:10',
            'texts.*' => 'required|string|max:10000',
            'source_lang' => 'nullable|string|size:2',
            'target_lang' => 'nullable|string|size:2',
        ]);

        $sourceLang = strtoupper($validated['source_lang'] ?? 'CS');
        $targetLang = strtoupper($validated['target_lang'] ?? 'EN');

        $result = $this->translationService->translateBatch(
            $validated['texts'],
            $sourceLang,
            $targetLang
        );

        if ($result['success']) {
            return response()->json([
                'success' => true,
                'translations' => $result['translations'],
            ]);
        }

        return response()->json([
            'success' => false,
            'error' => $result['error'],
        ], 422);
    }

    /**
     * Get DeepL API usage statistics
     *
     * @return JsonResponse
     */
    public function usage(): JsonResponse
    {
        $result = $this->translationService->getUsage();

        if ($result['success']) {
            $usage = $result['usage'];
            $percentUsed = $usage['character_limit'] > 0 
                ? round(($usage['character_count'] / $usage['character_limit']) * 100, 1)
                : 0;

            return response()->json([
                'success' => true,
                'character_count' => $usage['character_count'],
                'character_limit' => $usage['character_limit'],
                'percent_used' => $percentUsed,
                'characters_remaining' => $usage['character_limit'] - $usage['character_count'],
            ]);
        }

        return response()->json([
            'success' => false,
            'error' => $result['error'],
        ], 422);
    }

    /**
     * Check if translation service is available
     *
     * @return JsonResponse
     */
    public function status(): JsonResponse
    {
        return response()->json([
            'available' => $this->translationService->isAvailable(),
        ]);
    }
}

