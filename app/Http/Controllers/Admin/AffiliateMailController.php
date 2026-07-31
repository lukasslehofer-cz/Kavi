<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\AffiliateAdminMessage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class AffiliateMailController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    /**
     * Formulář pro rozeslání zprávy partnerům
     */
    public function create()
    {
        return view('admin.affiliate.mail.create', [
            'partners' => $this->partnersQuery()->get(),
        ]);
    }

    /**
     * Náhled vyrenderovaného mailu (zobrazí se v iframe)
     */
    public function preview(Request $request)
    {
        $validated = $this->validateMessage($request);

        $partner = $this->resolvePartners($validated['partners'])->first();

        abort_if(! $partner, 404);

        return new AffiliateAdminMessage(
            $partner,
            $validated['subject'],
            $validated['body']
        );
    }

    /**
     * Pošle zkušební kopii na e-mail přihlášeného admina
     */
    public function sendTest(Request $request)
    {
        $validated = $this->validateMessage($request);

        $partner = $this->resolvePartners($validated['partners'])->first();

        if (! $partner) {
            return back()->withInput()->with('error', __('affiliate.mail_no_recipients'));
        }

        $adminEmail = $request->user()->email;

        try {
            Mail::to($adminEmail)->send(new AffiliateAdminMessage(
                $partner,
                $validated['subject'],
                $validated['body']
            ));
        } catch (\Exception $e) {
            \Log::error('Failed to send affiliate admin test message', [
                'error' => $e->getMessage(),
            ]);

            return back()->withInput()->with('error', 'Testovací mail se nepodařilo odeslat: '.$e->getMessage());
        }

        return back()->withInput()
            ->with('success', __('affiliate.mail_test_sent', ['email' => $adminEmail]));
    }

    /**
     * Rozešle zprávu vybraným partnerům
     */
    public function send(Request $request)
    {
        $validated = $this->validateMessage($request);

        $partners = $this->resolvePartners($validated['partners']);

        if ($partners->isEmpty()) {
            return back()->withInput()->with('error', __('affiliate.mail_no_recipients'));
        }

        $sent = 0;
        $errors = [];

        foreach ($partners as $partner) {
            if (! $partner->email) {
                $errors[] = "{$partner->name}: chybí e-mail";

                continue;
            }

            try {
                Mail::to($partner->email)->send(new AffiliateAdminMessage(
                    $partner,
                    $validated['subject'],
                    $validated['body']
                ));
                $sent++;
            } catch (\Exception $e) {
                $errors[] = "{$partner->email}: {$e->getMessage()}";
                \Log::error('Failed to send affiliate admin message', [
                    'partner_id' => $partner->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        if ($errors) {
            return redirect()
                ->route('admin.affiliate.mail.create')
                ->with('error', __('affiliate.mail_sent_with_errors', [
                    'count' => $sent,
                    'errors' => implode('; ', $errors),
                ]));
        }

        return redirect()
            ->route('admin.affiliate.mail.create')
            ->with('success', __('affiliate.mail_sent', ['count' => $sent]));
    }

    /**
     * @return array{partners: array<int, int>, subject: string, body: string}
     */
    private function validateMessage(Request $request): array
    {
        return $request->validate([
            'partners' => 'required|array|min:1',
            'partners.*' => 'integer|exists:users,id',
            'subject' => 'required|string|max:200',
            'body' => 'required|string|max:20000',
        ]);
    }

    private function partnersQuery()
    {
        return User::where('is_affiliate_partner', true)
            ->orderBy('name');
    }

    private function resolvePartners(array $ids)
    {
        return $this->partnersQuery()->whereIn('id', $ids)->get();
    }
}
