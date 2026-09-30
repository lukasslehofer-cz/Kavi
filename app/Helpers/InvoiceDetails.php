<?php

namespace App\Helpers;

/**
 * Firemní údaje, které zákazník zadá v pokladně ("Nakupuji na firmu").
 *
 * Ukládají se jako JSON do orders.invoice_details a subscriptions.invoice_details
 * v tomto tvaru (null = nákup není na firmu):
 *
 *   ['company' => 'Firma s.r.o.', 'registration_no' => '12345678', 'vat_no' => 'CZ12345678',
 *    // jen když má firma sídlo jinde než je fakturační adresa z pokladny:
 *    'street' => '...', 'city' => '...', 'zip' => '...', 'country' => 'CZ']
 *
 * Názvy polí ve formuláři mají prefix invoice_, stejně jako sloupce users.invoice_*.
 */
class InvoiceDetails
{
    /**
     * Mapování klíčů v uložených datech na klíče ve Stripe metadatech.
     * Posílají se jako samostatné ploché hodnoty – Stripe omezuje každou
     * hodnotu na 500 znaků a JSON s diakritikou by se do limitu nemusel vejít.
     */
    private const METADATA_KEYS = [
        'company' => 'invoice_company',
        'registration_no' => 'invoice_registration_no',
        'vat_no' => 'invoice_vat_no',
        'street' => 'invoice_street',
        'city' => 'invoice_city',
        'zip' => 'invoice_zip',
        'country' => 'invoice_country',
    ];

    /**
     * Validační pravidla pro pokladnu. Díky exclude_unless se firemní pole
     * mimo nákup na firmu vůbec nevalidují ani nepropisují do $validated.
     */
    public static function rules(): array
    {
        $company = 'exclude_unless:is_company,1';
        $address = $company.'|exclude_unless:invoice_different_address,1';

        return [
            'is_company' => 'nullable|boolean',
            'invoice_company' => $company.'|required|string|max:255',
            'invoice_registration_no' => $company.'|required|string|max:20',
            'invoice_vat_no' => $company.'|nullable|string|max:30',
            'invoice_different_address' => $company.'|nullable|boolean',
            'invoice_street' => $address.'|required|string|max:255',
            'invoice_city' => $address.'|required|string|max:100',
            'invoice_zip' => $address.'|required|string|max:20',
            'invoice_country' => $address.'|required|string|size:2',
        ];
    }

    /**
     * Sestaví firemní údaje z (už zvalidovaného) vstupu formuláře.
     * Vrací null, když zákazník nenakupuje na firmu.
     */
    public static function fromInput(array $input): ?array
    {
        if (! filter_var($input['is_company'] ?? false, FILTER_VALIDATE_BOOLEAN)) {
            return null;
        }

        $details = [
            'company' => trim((string) ($input['invoice_company'] ?? '')),
            'registration_no' => preg_replace('/\s+/', '', (string) ($input['invoice_registration_no'] ?? '')),
            'vat_no' => strtoupper(preg_replace('/\s+/', '', (string) ($input['invoice_vat_no'] ?? ''))),
        ];

        if (filter_var($input['invoice_different_address'] ?? false, FILTER_VALIDATE_BOOLEAN)) {
            $details['street'] = trim((string) ($input['invoice_street'] ?? ''));
            $details['city'] = trim((string) ($input['invoice_city'] ?? ''));
            $details['zip'] = trim((string) ($input['invoice_zip'] ?? ''));
            $details['country'] = strtoupper(trim((string) ($input['invoice_country'] ?? '')));
        }

        return $details['company'] !== '' ? $details : null;
    }

    /**
     * Převede firemní údaje na ploché klíče pro Stripe metadata (jen neprázdné).
     */
    public static function toStripeMetadata(?array $details): array
    {
        if (! $details) {
            return [];
        }

        $metadata = [];
        foreach (self::METADATA_KEYS as $key => $metadataKey) {
            $value = (string) ($details[$key] ?? '');
            if ($value !== '') {
                $metadata[$metadataKey] = mb_substr($value, 0, 500);
            }
        }

        return $metadata;
    }

    /**
     * Opak toStripeMetadata() – použije webhook při zakládání předplatného.
     *
     * @param  array|\ArrayAccess|null  $metadata  pole nebo Stripe\StripeObject
     */
    public static function fromStripeMetadata($metadata): ?array
    {
        if (! $metadata || empty($metadata['invoice_company'])) {
            return null;
        }

        $details = [];
        foreach (self::METADATA_KEYS as $key => $metadataKey) {
            $details[$key] = isset($metadata[$metadataKey]) ? (string) $metadata[$metadataKey] : '';
        }

        // Adresa sídla se ukládá jen tehdy, když ji zákazník zadal
        if ($details['street'] === '') {
            unset($details['street'], $details['city'], $details['zip'], $details['country']);
        }

        return $details;
    }

    /**
     * Promítne firemní údaje do dat subjektu pro Fakturoid. Firma jde do "name"
     * (tiskne se jako odběratel), osoba z objednávky do "full_name".
     */
    public static function applyToSubject(array $subjectData, array $details): array
    {
        $subjectData['full_name'] = (string) ($subjectData['name'] ?? '');
        $subjectData['name'] = $details['company'];
        $subjectData['registration_no'] = (string) ($details['registration_no'] ?? '');
        $subjectData['vat_no'] = (string) ($details['vat_no'] ?? '');

        if (! empty($details['street'])) {
            $subjectData['street'] = $details['street'];
            $subjectData['city'] = (string) ($details['city'] ?? '');
            $subjectData['zip'] = (string) ($details['zip'] ?? '');
            $subjectData['country'] = strtoupper($details['country'] ?? '') ?: ($subjectData['country'] ?? 'CZ');
        }

        return $subjectData;
    }
}
