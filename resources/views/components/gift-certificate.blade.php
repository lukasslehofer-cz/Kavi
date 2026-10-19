{{--
    Náhled dárkového certifikátu na webu. Vykresluje stejný partial jako PDF
    (gifts/certificate/_front), zmenšený na šířku sloupce přes transform: scale
    (resources/js/components/gift-preview.js, ResizeObserver).

    subscription, voucher: data pro obě varianty přední strany (viz GiftController::index)
    type: která varianta je vidět na začátku
--}}
@props(['subscription', 'voucher', 'type' => 'subscription'])

<div data-cert-preview {{ $attributes->merge(['class' => 'relative w-full overflow-hidden border border-warm-300 bg-white']) }} style="aspect-ratio: 794 / 559;">
    <div data-cert-scale style="position: absolute; left: 0; top: 0; width: 794px; height: 559px; transform-origin: 0 0; transform: scale(0.6);">
        <div data-cert-wrap="subscription" @if($type !== 'subscription') style="display: none;" @endif>
            @include('gifts.certificate._front', ['c' => $subscription, 'mode' => 'web'])
        </div>
        <div data-cert-wrap="voucher" @if($type !== 'voucher') style="display: none;" @endif>
            @include('gifts.certificate._front', ['c' => $voucher, 'mode' => 'web'])
        </div>
    </div>
</div>
