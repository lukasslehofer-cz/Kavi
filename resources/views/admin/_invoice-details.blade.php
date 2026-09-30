{{-- Firemní údaje zadané zákazníkem v pokladně (orders/subscriptions.invoice_details) --}}
<div class="bg-blue-50 border border-blue-200 p-3 rounded-lg">
    <p class="text-sm font-medium text-blue-900 mb-1">Nákup na firmu (zadáno v pokladně)</p>
    <div class="text-sm text-blue-900 space-y-0.5">
        <p class="font-semibold">{{ $details['company'] ?? '' }}</p>
        <p>IČ: {{ $details['registration_no'] ?? '—' }}</p>
        @if(! empty($details['vat_no']))
        <p>DIČ: {{ $details['vat_no'] }}</p>
        @endif
        @if(! empty($details['street']))
        <p class="pt-1 text-blue-800">Sídlo: {{ $details['street'] }}, {{ $details['zip'] ?? '' }} {{ $details['city'] ?? '' }}, {{ $details['country'] ?? '' }}</p>
        @else
        <p class="pt-1 text-blue-800">Sídlo: stejné jako fakturační adresa</p>
        @endif
    </div>
    <p class="text-xs text-blue-800 mt-2">Faktura se vystaví na tuto firmu – má přednost před vlastními údaji zákazníka z adminu.</p>
</div>
