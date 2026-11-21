<?php

namespace App\Http\Controllers;

use App\Helpers\CorporateHelper;
use App\Helpers\CurrencyHelper;
use App\Helpers\MetaPixelHelper;
use App\Mail\CorporateInquiryReceived;
use App\Models\CorporateInquiry;
use App\Services\MetaConversionsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

/**
 * Stránka Pro firmy (firemní dárky) a poptávkový formulář. Jen česky, jen kavi.cz.
 *
 * Na stránce nejsou ceny a firma neplatí na webu: poptávka se uloží, odejde
 * e-mailem na info@kavi.cz a nabídku s cenou pošleme ručně.
 */
class CorporateGiftController extends Controller
{
    // Parametry z URL stránky, které se uloží k poptávce (odkud firma přišla)
    private const TRACKING_PARAMS = ['utm_source', 'utm_medium', 'utm_campaign', 'utm_content', 'utm_term', 'gclid', 'fbclid'];

    public function index(Request $request)
    {
        abort_unless(CorporateHelper::enabled(), 404);

        $christmas = CorporateHelper::christmas();

        // Předvolba "Pro koho" z odkazu v e-mailu nebo reklamě: ?pro=zamestnanci
        $audience = (string) $request->query('pro', '');

        return view('corporate.index', [
            'christmas' => $christmas,
            'tiers' => CorporateHelper::tiers(),
            'maxDiscount' => CorporateHelper::maxDiscount(),
            'minQuantity' => (int) config('corporate.min_quantity', 10),
            'faq' => $this->faq($christmas),
            'audiences' => array_keys(CorporateInquiry::AUDIENCES),
            'presetAudience' => array_key_exists($audience, CorporateInquiry::AUDIENCES) ? $audience : null,
            'contactEmail' => config('corporate.email'),
        ]);
    }

    public function send(Request $request)
    {
        abort_unless(CorporateHelper::enabled(), 404);

        if (! $this->recaptchaPasses($request)) {
            return response()->json([
                'success' => false,
                'message' => __('corporate.form.error_recaptcha', ['email' => config('corporate.email')]),
            ], 422);
        }

        // Honeypot: skryté pole vyplní jen robot. Odpověď vypadá jako úspěch, ať to nezkouší dál.
        if (filled($request->input('website'))) {
            Log::warning('Corporate inquiry honeypot filled', ['ip' => $request->ip()]);

            return response()->json(['success' => true]);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'company' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:50',
            'audience' => 'required|in:' . implode(',', array_keys(CorporateInquiry::AUDIENCES)),
            'quantity' => 'required|in:' . implode(',', array_column(CorporateHelper::tiers(), 'value')),
            'delivery_date' => 'nullable|date|after_or_equal:today',
            'note' => 'nullable|string|max:2000',
        ]);

        $inquiry = CorporateInquiry::create($validated + [
            'locale' => app()->getLocale(),
            'utm' => $this->trackingParams($request) ?: null,
            'status' => CorporateInquiry::STATUS_NEW,
        ]);

        Log::info('Corporate inquiry received', [
            'inquiry_id' => $inquiry->id,
            'company' => $inquiry->company,
            'quantity' => $inquiry->quantity,
        ]);

        // Poptávka už je v DB, takže chyba e-mailu ji neztratí a zákazník vidí úspěch
        try {
            Mail::to(config('corporate.email'))->send(new CorporateInquiryReceived($inquiry));
        } catch (\Throwable $e) {
            Log::error('Corporate inquiry e-mail failed', [
                'inquiry_id' => $inquiry->id,
                'error' => $e->getMessage(),
            ]);
        }

        $eventId = 'corporate-inquiry-' . $inquiry->id;
        $this->sendMetaLead($request, $inquiry, $eventId);

        return response()->json([
            'success' => true,
            'event_id' => $eventId,
        ]);
    }

    /**
     * Časté dotazy pro akordeon i JSON-LD FAQPage. Vánoční věta jen do termínu poptávky.
     */
    private function faq(?array $christmas): array
    {
        $items = [];

        foreach (__('corporate.faq') as $item) {
            $answer = str_replace(':min', (string) config('corporate.min_quantity', 10), $item['a']);

            if (! empty($item['a_christmas']) && $christmas) {
                $answer .= ' ' . str_replace([':inquiry_by', ':deliver_by'], [$christmas['inquiry_by'], $christmas['deliver_by']], $item['a_christmas']);
            }

            $items[] = ['q' => $item['q'], 'a' => $answer];
        }

        return $items;
    }

    private function recaptchaPasses(Request $request): bool
    {
        if (! config('services.recaptcha.secret_key')) {
            return true;
        }

        $token = $request->input('recaptcha_token');
        if (! $token) {
            return false;
        }

        try {
            $data = Http::asForm()->timeout(10)->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret' => config('services.recaptcha.secret_key'),
                'response' => $token,
                'remoteip' => $request->ip(),
            ])->json();
        } catch (\Throwable $e) {
            Log::error('reCAPTCHA request failed for corporate inquiry', ['error' => $e->getMessage()]);

            return false;
        }

        if (empty($data['success']) || ($data['score'] ?? 0) < config('services.recaptcha.min_score', 0.5)) {
            Log::warning('reCAPTCHA failed for corporate inquiry', [
                'ip' => $request->ip(),
                'score' => $data['score'] ?? null,
                'action' => $data['action'] ?? null,
            ]);

            return false;
        }

        return true;
    }

    private function trackingParams(Request $request): array
    {
        $utm = $request->input('utm');
        if (! is_array($utm)) {
            return [];
        }

        $params = [];
        foreach (self::TRACKING_PARAMS as $key) {
            if (isset($utm[$key]) && is_string($utm[$key]) && $utm[$key] !== '') {
                $params[$key] = mb_substr($utm[$key], 0, 255);
            }
        }

        return $params;
    }

    /**
     * Lead do Conversions API se stejným event_id, jaký pošle Pixel v prohlížeči.
     * Odchází až po odeslání odpovědi, aby formulář nečekal na Metu.
     */
    private function sendMetaLead(Request $request, CorporateInquiry $inquiry, string $eventId): void
    {
        try {
            $service = MetaConversionsService::forRegion(CurrencyHelper::region());
            if (! $service->isConfigured()) {
                return;
            }

            $parts = preg_split('/\s+/u', trim($inquiry->name), 2);
            $userData = [
                'email' => $inquiry->email,
                'phone' => $inquiry->phone,
                'firstName' => ($parts[0] ?? '') ?: null,
                'lastName' => ($parts[1] ?? '') ?: null,
                'country' => 'CZ',
                'clientIpAddress' => MetaPixelHelper::clientIp(),
                'clientUserAgent' => MetaPixelHelper::clientUserAgent(),
            ];
            $fbp = $request->cookie('_fbp');
            $fbc = $request->cookie('_fbc');
            $sourceUrl = localizedRoute('corporate.index');

            app()->terminating(function () use ($service, $eventId, $userData, $fbp, $fbc, $sourceUrl) {
                try {
                    $service->sendLeadEvent($eventId, $userData, ['content_name' => 'Firemní dárky'], $fbp, $fbc, $sourceUrl);
                } catch (\Throwable $e) {
                    Log::error('Meta CAPI Lead failed for corporate inquiry', ['event_id' => $eventId, 'error' => $e->getMessage()]);
                }
            });
        } catch (\Throwable $e) {
            Log::error('Meta CAPI Lead setup failed for corporate inquiry', ['error' => $e->getMessage()]);
        }
    }
}
