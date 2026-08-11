<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\AdminCustomMessage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

/**
 * Rozeslání vlastní zprávy zákazníkům v Kavi šabloně.
 *
 * Admin nejdřív zvolí doménu (kavi.cz / kavibox.com), tím se zároveň filtruje
 * seznam příjemců. Jazyk se pak předává mailu napřímo – neodvozuje se z dat
 * uživatele, protože ho vybral admin.
 */
class CustomerMailController extends Controller
{
    /**
     * Odesílá se synchronně v requestu, strop drží dobu běhu v rozumných mezích.
     */
    private const MAX_RECIPIENTS = 200;

    private const SEGMENTS = ['all', 'customers', 'subscribers', 'no_orders'];

    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    /**
     * Formulář pro rozeslání zprávy
     */
    public function create(Request $request)
    {
        $locale = $this->resolveLocale($request->query('locale'));
        $segment = $this->resolveSegment($request->query('segment'));

        $counts = [];
        foreach (self::SEGMENTS as $key) {
            $counts[$key] = $this->recipientsQuery($locale, $key)->count();
        }

        return view('admin.customer-mail.create', [
            'locale' => $locale,
            'segment' => $segment,
            'counts' => $counts,
            'maxRecipients' => self::MAX_RECIPIENTS,
            'recipients' => $this->recipientsQuery($locale, $segment)
                ->get(['id', 'name', 'email', 'locale']),
        ]);
    }

    /**
     * Náhled vyrenderovaného mailu (zobrazí se v iframe)
     */
    public function preview(Request $request)
    {
        $validated = $this->validateMessage($request, false);

        return $this->buildMessage($validated, $this->sampleRecipient($request, $validated));
    }

    /**
     * Pošle zkušební kopii na e-mail přihlášeného admina
     */
    public function sendTest(Request $request)
    {
        $validated = $this->validateMessage($request, false);

        $adminEmail = $request->user()->email;

        try {
            Mail::to($adminEmail)->send(
                $this->buildMessage($validated, $this->sampleRecipient($request, $validated))
            );
        } catch (\Exception $e) {
            \Log::error('Failed to send admin custom test message', [
                'locale' => $validated['locale'],
                'error' => $e->getMessage(),
            ]);

            return back()->withInput()->with('error', 'Testovací mail se nepodařilo odeslat: '.$e->getMessage());
        }

        return back()->withInput()->with('success', "Testovací mail byl odeslán na {$adminEmail}.");
    }

    /**
     * Rozešle zprávu vybraným příjemcům
     */
    public function send(Request $request)
    {
        $validated = $this->validateMessage($request);

        $recipients = $this->resolveRecipients(
            $validated['locale'],
            $validated['segment'] ?? 'all',
            $validated['recipients']
        );

        if ($recipients->isEmpty()) {
            return back()->withInput()->with('error', 'Vyberte alespoň jednoho příjemce.');
        }

        // Desítky mailů přes SMTP synchronně, default 30 s u PHP-FPM nestačí
        @set_time_limit(300);
        ignore_user_abort(true);

        $sent = 0;
        $errors = [];

        foreach ($recipients as $user) {
            try {
                Mail::to($user->email)->send($this->buildMessage($validated, $user));
                $sent++;
            } catch (\Exception $e) {
                $errors[] = "{$user->email}: {$e->getMessage()}";
                \Log::error('Failed to send admin custom message', [
                    'user_id' => $user->id,
                    'locale' => $validated['locale'],
                    'error' => $e->getMessage(),
                ]);
            }
        }

        if ($errors) {
            // Na rozdíl od AffiliateMailController tu nezahazujeme rozepsaný text
            return back()->withInput()->with('error',
                "Odesláno mailů: {$sent}, chyby: ".implode('; ', $errors));
        }

        return redirect()
            ->route('admin.customer-mail.create', [
                'locale' => $validated['locale'],
                'segment' => $validated['segment'] ?? 'all',
            ])
            ->with('success', "Odesláno mailů: {$sent}");
    }

    private function buildMessage(array $validated, User $user): AdminCustomMessage
    {
        return new AdminCustomMessage(
            $user,
            $validated['subject'],
            $validated['body'],
            $validated['locale'],
            $validated['button_label'] ?? null,
            $validated['button_url'] ?? null,
        );
    }

    /**
     * Vzorový příjemce pro náhled a testovací odeslání – první vybraný,
     * jinak přihlášený admin (ať se do testu netahá identita zákazníka).
     */
    private function sampleRecipient(Request $request, array $validated): User
    {
        $selected = $this->resolveRecipients(
            $validated['locale'],
            $validated['segment'] ?? 'all',
            $validated['recipients'] ?? []
        )->first();

        return $selected ?? $request->user();
    }

    private function validateMessage(Request $request, bool $recipientsRequired = true): array
    {
        return $request->validate([
            'locale' => 'required|in:cs,en',
            'segment' => 'nullable|in:'.implode(',', self::SEGMENTS),
            'recipients' => ($recipientsRequired ? 'required' : 'nullable').'|array|max:'.self::MAX_RECIPIENTS,
            'recipients.*' => 'integer|exists:users,id',
            'subject' => 'required|string|max:200',
            'body' => 'required|string|max:20000',
            'button_label' => 'nullable|string|max:60|required_with:button_url',
            'button_url' => 'nullable|url|max:500|required_with:button_label',
        ], [
            'recipients.max' => 'Najednou lze odeslat nejvýš '.self::MAX_RECIPIENTS.' mailů. Rozděl rozesílku na víc dávek.',
        ]);
    }

    private function resolveLocale(?string $locale): string
    {
        return $locale === 'en' ? 'en' : 'cs';
    }

    private function resolveSegment(?string $segment): string
    {
        return in_array($segment, self::SEGMENTS, true) ? $segment : 'all';
    }

    /**
     * Nikdy neposílat na smazané ani anonymizované účty.
     * User nepoužívá SoftDeletes, takže se musí filtrovat ručně.
     */
    private function baseQuery()
    {
        return User::query()
            ->whereNull('deleted_at')
            ->whereNull('anonymized_at')
            ->where('email', '!=', '')
            ->where('email', 'not like', '%@anonymized.local');
    }

    /**
     * Příjemci pro daný jazyk a segment.
     *
     * users.locale je NOT NULL DEFAULT 'cs'. Migrace 2026_01_21_093243 doplnila
     * 'en' všem, kdo měli EUR objednávku nebo předplatné; EXISTS na EUR je
     * pojistka pro EUR nákupy vzniklé až po ní. CS množina je přesný doplněk
     * EN, takže se výběry nepřekrývají a jejich součet je celek.
     */
    private function recipientsQuery(string $locale, string $segment = 'all')
    {
        $query = $this->baseQuery();

        // Obalující where() je nutný – bez něj by orWhere* obešlo filtry výše
        if ($locale === 'en') {
            $query->where(function ($q) {
                $q->where('locale', 'en')
                    ->orWhereHas('subscriptions', fn ($s) => $s->where('currency', 'EUR'))
                    ->orWhereHas('orders', fn ($o) => $o->where('currency', 'EUR'));
            });
        } else {
            $query->where(function ($q) {
                $q->where('locale', '!=', 'en')
                    ->whereDoesntHave('subscriptions', fn ($s) => $s->where('currency', 'EUR'))
                    ->whereDoesntHave('orders', fn ($o) => $o->where('currency', 'EUR'));
            });
        }

        match ($segment) {
            // payment_status = paid, jinak by se jako zákazník počítala
            // i opuštěná nezaplacená objednávka
            'customers' => $query->where(fn ($q) => $q
                ->whereHas('orders', fn ($o) => $o->where('payment_status', 'paid'))
                ->orHas('subscriptions')),
            // whereHas místo relace activeSubscriptions() – ta nese orderBy,
            // který by skončil uvnitř EXISTS poddotazu
            'subscribers' => $query->whereHas('subscriptions', fn ($s) => $s->where('status', 'active')),
            'no_orders' => $query->doesntHave('orders')->doesntHave('subscriptions'),
            default => null,
        };

        return $query->orderBy('name');
    }

    /**
     * ID z formuláře se znovu proženou stejným scopem, aby se zpráva nedala
     * poslat mimo aktuální výběr.
     */
    private function resolveRecipients(string $locale, string $segment, array $ids)
    {
        if (empty($ids)) {
            return collect();
        }

        return $this->recipientsQuery($locale, $segment)->whereIn('id', $ids)->get();
    }
}
