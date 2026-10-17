<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GiftPurchase;
use App\Services\GiftCertificateService;
use App\Services\GiftDeliveryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * Přehled prodaných a uplatněných dárků. Kód jde dohledat podle jména nebo
 * e-mailu dárce (FAQ "Ztratil jsem kód").
 */
class GiftPurchaseController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('q', ''));

        $gifts = GiftPurchase::with(['order', 'coupon', 'subscription'])
            ->when($request->query('status'), fn ($q, $status) => $q->where('status', $status))
            ->when($request->query('type'), fn ($q, $type) => $q->where('type', $type))
            ->when($search !== '', function ($q) use ($search) {
                $like = '%' . $search . '%';
                $q->where(function ($q) use ($like, $search) {
                    $q->where('code', 'like', $like)
                        ->orWhere('code', \App\Helpers\GiftCode::normalize($search))
                        ->orWhere('buyer_email', 'like', $like)
                        ->orWhere('buyer_name', 'like', $like)
                        ->orWhere('giver_name', 'like', $like)
                        ->orWhere('recipient_name', 'like', $like)
                        ->orWhere('recipient_email', 'like', $like)
                        ->orWhereHas('order', fn ($o) => $o->where('order_number', 'like', '%' . $search . '%'));
                });
            })
            ->orderByDesc('created_at')
            ->paginate(30)
            ->withQueryString();

        $stats = [
            'issued' => GiftPurchase::where('status', GiftPurchase::STATUS_ISSUED)->count(),
            'redeemed' => GiftPurchase::where('status', GiftPurchase::STATUS_REDEEMED)->count(),
            'pending' => GiftPurchase::whereIn('status', [GiftPurchase::STATUS_PENDING, GiftPurchase::STATUS_ISSUING])->count(),
            'failed_delivery' => GiftPurchase::whereNotNull('last_delivery_error')->count(),
        ];

        return view('admin.gifts.index', compact('gifts', 'stats', 'search'));
    }

    public function show(GiftPurchase $gift)
    {
        $gift->load(['order', 'coupon.usages', 'subscription', 'redeemedBy']);

        return view('admin.gifts.show', compact('gift'));
    }

    /**
     * Znovu poslat certifikát kupujícímu
     */
    public function resendBuyer(GiftPurchase $gift, GiftDeliveryService $delivery)
    {
        abort_unless($gift->isIssued(), 422, 'Dárek ještě není vystavený.');

        $gift->forceFill(['buyer_email_sent_at' => null, 'delivery_attempts' => 0])->save();
        $delivery->deliver($gift);

        return back()->with('success', 'Certifikát byl znovu odeslán kupujícímu.');
    }

    /**
     * Poslat certifikát obdarovanému (volitelně na jiný e-mail / jiný den)
     */
    public function resendRecipient(Request $request, GiftPurchase $gift, GiftDeliveryService $delivery)
    {
        abort_unless($gift->isIssued(), 422, 'Dárek ještě není vystavený.');

        $validated = $request->validate([
            'recipient_email' => 'required|email',
            'send_at' => 'nullable|date',
        ]);

        $gift->forceFill([
            'delivery' => GiftPurchase::DELIVERY_RECIPIENT,
            'recipient_email' => $validated['recipient_email'],
            'send_at' => ! empty($validated['send_at'])
                ? \Carbon\Carbon::parse($validated['send_at'])->setTime((int) config('gifts.recipient_send_hour', 8), 0)
                : now(),
            'recipient_email_sent_at' => null,
            'delivery_attempts' => 0,
        ])->save();

        $delivery->deliver($gift);

        return back()->with('success', $gift->fresh()->recipient_email_sent_at
            ? 'Certifikát byl odeslán obdarovanému.'
            : 'Odeslání obdarovanému je naplánované.');
    }

    public function regeneratePdf(GiftPurchase $gift, GiftCertificateService $certificates)
    {
        abort_unless($gift->isIssued(), 422, 'Dárek ještě není vystavený.');

        $certificates->regenerate($gift);

        return back()->with('success', 'PDF certifikátu bylo vygenerováno znovu.');
    }

    public function downloadPdf(GiftPurchase $gift, GiftCertificateService $certificates)
    {
        abort_unless($gift->isIssued(), 404);

        $path = $certificates->ensurePdf($gift);

        return Storage::disk('local')->download($path, $certificates->fileName($gift), ['Content-Type' => 'application/pdf']);
    }

    /**
     * Zneplatnit neuplatněný dárek (např. po vrácení peněz). Refundace ve Stripe zůstává ruční.
     */
    public function void(GiftPurchase $gift)
    {
        if ($gift->status === GiftPurchase::STATUS_REDEEMED) {
            return back()->with('error', 'Uplatněný dárek nejde zneplatnit.');
        }

        $gift->coupon?->update(['is_active' => false]);
        $gift->update(['status' => GiftPurchase::STATUS_CANCELLED]);

        \Log::info('Gift voided by admin', ['gift_id' => $gift->id, 'admin_id' => auth()->id()]);

        return back()->with('success', 'Dárek byl zneplatněn. Peníze případně vraťte ručně ve Stripe.');
    }
}
