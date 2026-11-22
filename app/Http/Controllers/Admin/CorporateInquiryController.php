<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CorporateInquiry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

/**
 * Admin › Dárky › Firemní dárky: poptávky ze stránky Pro firmy, jejich stav
 * a detaily dealu (ceny v Kč bez DPH).
 */
class CorporateInquiryController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('q', ''));
        $status = $request->query('status');

        $inquiries = CorporateInquiry::query()
            ->when(isset(CorporateInquiry::STATUSES[$status]), fn ($q) => $q->where('status', $status))
            ->when($search !== '', function ($q) use ($search) {
                $like = '%' . $search . '%';
                $q->where(function ($q) use ($like, $search) {
                    $q->where('company', 'like', $like)
                        ->orWhere('name', 'like', $like)
                        ->orWhere('email', 'like', $like)
                        ->orWhere('phone', 'like', $like)
                        ->orWhere('invoice_number', 'like', $like);
                    if (ctype_digit(ltrim($search, '#'))) {
                        $q->orWhere('id', (int) ltrim($search, '#'));
                    }
                });
            })
            ->orderByDesc('created_at')
            ->paginate(30)
            ->withQueryString();

        $counts = CorporateInquiry::query()
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        return view('admin.gifts.corporate.index', compact('inquiries', 'counts', 'search', 'status'));
    }

    public function show(CorporateInquiry $inquiry)
    {
        return view('admin.gifts.corporate.show', compact('inquiry'));
    }

    public function update(Request $request, CorporateInquiry $inquiry)
    {
        // "1 250,50" → "1250.50"
        foreach (['unit_price', 'total_price'] as $field) {
            if ($request->filled($field)) {
                $request->merge([$field => str_replace([' ', "\u{00A0}", ','], ['', '', '.'], (string) $request->input($field))]);
            }
        }

        $validated = $request->validate([
            'status' => ['required', Rule::in(array_keys(CorporateInquiry::STATUSES))],
            'box_count' => 'nullable|integer|min:1|max:100000',
            'box_contents' => 'nullable|string|max:2000',
            'unit_price' => 'nullable|numeric|min:0|max:99999999',
            'total_price' => 'nullable|numeric|min:0|max:99999999',
            'agreed_delivery_date' => 'nullable|date',
            'delivery_address' => 'nullable|string|max:2000',
            'billing_ico' => 'nullable|string|max:20',
            'billing_dic' => 'nullable|string|max:20',
            'billing_address' => 'nullable|string|max:1000',
            'invoice_number' => 'nullable|string|max:50',
            'paid_at' => 'nullable|date',
            'internal_note' => 'nullable|string|max:10000',
        ], [], [
            'status' => 'stav',
            'box_count' => 'počet boxů',
            'box_contents' => 'obsah a personalizace',
            'unit_price' => 'cena za box',
            'total_price' => 'celkem',
            'agreed_delivery_date' => 'dohodnutý termín',
            'delivery_address' => 'doručovací adresa',
            'billing_ico' => 'IČ',
            'billing_dic' => 'DIČ',
            'billing_address' => 'fakturační adresa',
            'invoice_number' => 'číslo faktury',
            'paid_at' => 'zaplaceno dne',
            'internal_note' => 'interní poznámky',
        ]);

        // Prázdné "Celkem" = počet × cena za box
        if (($validated['total_price'] ?? null) === null && ! empty($validated['box_count']) && ($validated['unit_price'] ?? null) !== null) {
            $validated['total_price'] = round($validated['box_count'] * (float) $validated['unit_price'], 2);
        }

        $previousStatus = $inquiry->status;
        $inquiry->changeStatus($validated['status'], $request->user());
        $inquiry->fill(collect($validated)->except('status')->all());
        $inquiry->save();

        Log::info('Corporate inquiry updated by admin', [
            'inquiry_id' => $inquiry->id,
            'admin_id' => auth()->id(),
            'status' => $previousStatus === $inquiry->status ? $inquiry->status : $previousStatus . ' → ' . $inquiry->status,
        ]);

        return redirect()
            ->route('admin.gifts.corporate.show', $inquiry)
            ->with('success', 'Poptávka je uložená.');
    }
}
