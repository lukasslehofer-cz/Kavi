{{--
    Volitelný nákup na firmu – sdílí pokladna e-shopu i předplatného.
    Očekává $availableCountries a volitelně $invoicePrefill (firemní údaje
    uložené v profilu zákazníka, users.invoice_details).
    Pole se zpracují přes App\Helpers\InvoiceDetails.
--}}
@php
    $companyPrefill = $invoicePrefill ?? null;
    // Po chybě validace platí, co zákazník odeslal – jinak by se odškrtnutá volba
    // znovu zaškrtla z předvyplnění.
    $hasOldInput = session()->hasOldInput();
    $isCompany = $hasOldInput ? (bool) old('is_company') : (bool) $companyPrefill;
    $companyDifferentAddress = $hasOldInput ? (bool) old('invoice_different_address') : ! empty($companyPrefill['street']);
@endphp

<div class="mt-6">
    <div class="flex items-start py-4 border-b border-[#BCBEB1]">
        <input
            type="checkbox"
            id="is_company"
            name="is_company"
            value="1"
            class="w-4 h-4 text-dark-800 border-dark-800 focus:ring-olive-500 mr-3 mt-0.5 flex-shrink-0"
            {{ $isCompany ? 'checked' : '' }}
        >
        <label for="is_company" class="text-xs uppercase tracking-widest text-dark-800 cursor-pointer">
            {{ __('checkout.company.checkbox_label') }}
        </label>
    </div>

    <div id="company-fields" class="space-y-4 pt-4" style="{{ $isCompany ? '' : 'display: none;' }}">
        <p class="text-xs text-warm-500">{{ __('checkout.company.hint') }}</p>

        <div>
            <div class="swiss-field">
                <label for="invoice_company" class="swiss-field-label swiss-field-label-required">{{ __('checkout.company.name') }}</label>
                <input
                    type="text"
                    id="invoice_company"
                    name="invoice_company"
                    value="{{ old('invoice_company', $companyPrefill['company'] ?? '') }}"
                    maxlength="255"
                    data-company-required
                    class="swiss-field-input"
                    placeholder="{{ __('checkout.company.name_placeholder') }}"
                >
            </div>
            @error('invoice_company')
                <p class="text-red-600 text-xs mt-2 uppercase tracking-widest">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <div class="swiss-field">
                    <label for="invoice_registration_no" class="swiss-field-label swiss-field-label-required">{{ __('checkout.company.registration_no') }}</label>
                    <input
                        type="text"
                        id="invoice_registration_no"
                        name="invoice_registration_no"
                        value="{{ old('invoice_registration_no', $companyPrefill['registration_no'] ?? '') }}"
                        maxlength="20"
                        data-company-required
                        class="swiss-field-input"
                        placeholder="12345678"
                    >
                </div>
                @error('invoice_registration_no')
                    <p class="text-red-600 text-xs mt-2 uppercase tracking-widest">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <div class="swiss-field">
                    <label for="invoice_vat_no" class="swiss-field-label">{{ __('checkout.company.vat_no') }}</label>
                    <input
                        type="text"
                        id="invoice_vat_no"
                        name="invoice_vat_no"
                        value="{{ old('invoice_vat_no', $companyPrefill['vat_no'] ?? '') }}"
                        maxlength="30"
                        class="swiss-field-input"
                        placeholder="CZ12345678"
                    >
                </div>
                @error('invoice_vat_no')
                    <p class="text-red-600 text-xs mt-2 uppercase tracking-widest">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="flex items-start py-4 border-b border-[#BCBEB1]">
            <input
                type="checkbox"
                id="invoice_different_address"
                name="invoice_different_address"
                value="1"
                class="w-4 h-4 text-dark-800 border-dark-800 focus:ring-olive-500 mr-3 mt-0.5 flex-shrink-0"
                {{ $companyDifferentAddress ? 'checked' : '' }}
            >
            <label for="invoice_different_address" class="text-xs uppercase tracking-widest text-dark-800 cursor-pointer">
                {{ __('checkout.company.different_address') }}
            </label>
        </div>

        <div id="company-address-fields" class="space-y-4" style="{{ $isCompany && $companyDifferentAddress ? '' : 'display: none;' }}">
            <div>
                <div class="swiss-field">
                    <label for="invoice_street" class="swiss-field-label swiss-field-label-required">{{ __('checkout.fields.street') }}</label>
                    <input
                        type="text"
                        id="invoice_street"
                        name="invoice_street"
                        value="{{ old('invoice_street', $companyPrefill['street'] ?? '') }}"
                        maxlength="255"
                        data-company-required
                        class="swiss-field-input"
                        placeholder="{{ __('checkout.fields.street_placeholder') }}"
                    >
                </div>
                @error('invoice_street')
                    <p class="text-red-600 text-xs mt-2 uppercase tracking-widest">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <div class="swiss-field">
                        <label for="invoice_city" class="swiss-field-label swiss-field-label-required">{{ __('checkout.fields.city') }}</label>
                        <input
                            type="text"
                            id="invoice_city"
                            name="invoice_city"
                            value="{{ old('invoice_city', $companyPrefill['city'] ?? '') }}"
                            maxlength="100"
                            data-company-required
                            class="swiss-field-input"
                            placeholder="{{ __('checkout.fields.city_placeholder') }}"
                        >
                    </div>
                    @error('invoice_city')
                        <p class="text-red-600 text-xs mt-2 uppercase tracking-widest">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <div class="swiss-field">
                        <label for="invoice_zip" class="swiss-field-label swiss-field-label-required">{{ __('checkout.fields.postal_code') }}</label>
                        <input
                            type="text"
                            id="invoice_zip"
                            name="invoice_zip"
                            value="{{ old('invoice_zip', $companyPrefill['zip'] ?? '') }}"
                            maxlength="20"
                            data-company-required
                            class="swiss-field-input"
                            placeholder="{{ __('checkout.fields.postal_code_placeholder') }}"
                        >
                    </div>
                    @error('invoice_zip')
                        <p class="text-red-600 text-xs mt-2 uppercase tracking-widest">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <div class="swiss-field">
                        <label for="invoice_country" class="swiss-field-label swiss-field-label-required">{{ __('checkout.fields.country') }}</label>
                        {{-- Jiné id i name než billing_country – ten má listener na přepočet dopravy --}}
                        <select id="invoice_country" name="invoice_country" data-company-required class="swiss-field-select">
                            <option value="">{{ __('checkout.fields.select_country') }}</option>
                            @foreach($availableCountries as $code => $countryName)
                                <option value="{{ $code }}" {{ old('invoice_country', $companyPrefill['country'] ?? '') == $code ? 'selected' : '' }}>
                                    {{ $countryName }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @error('invoice_country')
                        <p class="text-red-600 text-xs mt-2 uppercase tracking-widest">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>
    </div>
</div>

<script>
(function () {
    const companyToggle = document.getElementById('is_company');
    const addressToggle = document.getElementById('invoice_different_address');
    const companyPanel = document.getElementById('company-fields');
    const addressPanel = document.getElementById('company-address-fields');

    if (!companyToggle || !companyPanel || !addressPanel) {
        return;
    }

    // Povinná pole ve skrytém panelu by prohlížeči zablokovala odeslání formuláře,
    // proto se `required` nastavuje jen polím, která jsou právě vidět. Posloucháme
    // `change`, protože ho spouští i obnova formuláře po uplatnění kupónu.
    function syncCompanyFields() {
        const companyOn = companyToggle.checked;
        const addressOn = companyOn && addressToggle.checked;

        companyPanel.style.display = companyOn ? '' : 'none';
        addressPanel.style.display = addressOn ? '' : 'none';

        companyPanel.querySelectorAll('[data-company-required]').forEach(function (el) { el.required = companyOn; });
        addressPanel.querySelectorAll('[data-company-required]').forEach(function (el) { el.required = addressOn; });
    }

    companyToggle.addEventListener('change', syncCompanyFields);
    addressToggle.addEventListener('change', syncCompanyFields);
    document.addEventListener('DOMContentLoaded', syncCompanyFields);
    syncCompanyFields();
})();
</script>
