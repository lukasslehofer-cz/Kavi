<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\FakturoidService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CustomerBillingController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    /**
     * Formulář s vlastními fakturačními údaji zákazníka
     */
    public function edit(Request $request, User $user)
    {
        return view('admin.customers.billing', [
            'user' => $user,
            'backUrl' => $this->resolveBackUrl($request),
        ]);
    }

    /**
     * Uloží fakturační údaje a volitelně je rovnou promítne do Fakturoidu
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'invoice_company' => 'nullable|string|max:255',
            'invoice_registration_no' => 'nullable|string|max:20',
            'invoice_vat_no' => 'nullable|string|max:30',
            'invoice_name' => 'nullable|string|max:255',
            'invoice_street' => 'required_if:invoice_override,1|nullable|string|max:255',
            'invoice_city' => 'required_if:invoice_override,1|nullable|string|max:100',
            'invoice_zip' => 'required_if:invoice_override,1|nullable|string|max:20',
            'invoice_country' => 'required_if:invoice_override,1|nullable|string|size:2',
        ], [
            'invoice_street.required_if' => 'Při zapnutých vlastních fakturačních údajích je ulice povinná.',
            'invoice_city.required_if' => 'Při zapnutých vlastních fakturačních údajích je město povinné.',
            'invoice_zip.required_if' => 'Při zapnutých vlastních fakturačních údajích je PSČ povinné.',
            'invoice_country.required_if' => 'Při zapnutých vlastních fakturačních údajích je země povinná.',
        ]);

        $override = $request->boolean('invoice_override');

        // Fakturoid vyžaduje u subjektu jméno – bez firmy i kontaktní osoby
        // bychom neměli co poslat.
        if ($override && blank($validated['invoice_company'] ?? null) && blank($validated['invoice_name'] ?? null)) {
            throw ValidationException::withMessages([
                'invoice_company' => 'Vyplňte název firmy nebo jméno, na které se má faktura vystavit.',
            ]);
        }

        $country = $validated['invoice_country'] ?? null;

        $user->update([
            'invoice_override' => $override,
            'invoice_company' => $validated['invoice_company'] ?? null,
            'invoice_registration_no' => $validated['invoice_registration_no'] ?? null,
            'invoice_vat_no' => $validated['invoice_vat_no'] ?? null,
            'invoice_name' => $validated['invoice_name'] ?? null,
            'invoice_street' => $validated['invoice_street'] ?? null,
            'invoice_city' => $validated['invoice_city'] ?? null,
            'invoice_zip' => $validated['invoice_zip'] ?? null,
            'invoice_country' => $country ? strtoupper($country) : null,
        ]);

        $message = $override
            ? 'Fakturační údaje uloženy. Použijí se na všech budoucích fakturách tohoto zákazníka.'
            : 'Fakturační údaje uloženy. Vlastní údaje jsou vypnuté, faktury se vystaví na adresu z objednávky.';

        if ($request->boolean('sync_fakturoid')) {
            // Službu řešíme až tady, jako všude jinde v projektu – formulář se pak
            // dá otevřít i v prostředí bez nastavených přístupů k Fakturoidu.
            $message .= app(FakturoidService::class)->syncSubjectForUser($user->fresh())
                ? ' Subjekt ve Fakturoidu byl aktualizován.'
                : ' Zákazník zatím ve Fakturoidu subjekt nemá – vznikne s jeho první fakturou.';
        }

        return redirect()
            ->route('admin.customers.billing.edit', ['user' => $user, 'back' => $request->input('back')])
            ->with('success', $message);
    }

    /**
     * Odkud se admin na formulář dostal – aby se měl kam vrátit.
     * Bereme jen vlastní admin cesty, ne libovolné URL z requestu.
     */
    private function resolveBackUrl(Request $request): ?string
    {
        $back = $request->query('back');

        if (! is_string($back) || ! str_starts_with($back, '/admin/')) {
            return null;
        }

        return $back;
    }
}
