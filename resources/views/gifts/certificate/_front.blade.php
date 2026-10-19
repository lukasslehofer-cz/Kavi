{{--
    Přední strana dárkového certifikátu (794 × 559 px = A5 na šířku při 96 dpi).

    Stejný partial se vykresluje v náhledu na webu i v PDF (dompdf), proto jen
    absolutní pozice a tabulky: dompdf neumí flexbox, CSS filtry ani object-fit.
    Fotky jsou předem ořezané a převedené do ČB (public/images/gifts).

    Řádkování: dompdf nepustí line-height pod přirozenou výšku fontu (TeX Gyre Heros
    1,43 em), velké nadpisy mají proto každý řádek absolutně. Text v PDF se navíc
    posouvá přes App\Helpers\PdfText::fix, jinak by seděl níž než v prohlížeči.
    Náhled na webu podle data-cert-offset přepočítává pozice při psaní věnování
    (resources/js/components/gift-preview.js).

    $c     certificateData() z GiftPurchase, nebo ukázková data pro náhled
    $mode  'pdf' | 'web'
--}}
@php
    $isPdf = ($mode ?? 'web') === 'pdf';
    $lh = fn (float $size, float $lineHeight, string $family = 'heros') => \App\Helpers\PdfText::lineHeight($isPdf, $size, $lineHeight, $family);
    $fix = fn (float $size, float $lineHeight, string $family = 'heros') => \App\Helpers\PdfText::shift($isPdf, $size, $lineHeight, $family);
    $loc = $c['locale'] ?? app()->getLocale();
    $isSub = ($c['type'] ?? 'subscription') === 'subscription';
    $tr = fn (string $key, array $replace = []) => __('gifts.certificate.' . $key, $replace, $loc);
    $asset = fn (string $path) => $isPdf ? public_path($path) : asset($path);
    $display = "font-family: 'TexGyreHeros', 'Helvetica Neue', Arial, sans-serif; font-weight: normal;";
    $light = "font-family: 'RobotoLight', 'Roboto', Arial, sans-serif; font-weight: 300;";
    $eyebrow = 'font-size: 12px; ' . $lh(12, 17, 'roboto') . ' letter-spacing: 0.1em; text-transform: uppercase;';
    $chips = $isSub ? $tr('chips_subscription') : $tr('chips_voucher');
    $photo = $isSub ? 'images/gifts/cert-subscription.jpg' : 'images/gifts/cert-voucher.jpg';

    $note = trim((string) ($c['note'] ?? ''));
    $longNote = mb_strlen($note) > 90;
    $noteSize = $longNote ? 14 : 16;
    $noteLineHeight = $longNote ? 20 : 24;
    $noteLines = $note === '' ? 0 : max(1, (int) ceil(mb_strlen($note) / ($longNote ? 52 : 44)));

    // Spodní blok (Pro / Od / Box, kód, patička) začíná tady, měřeno od horního okraje
    // pravé části (ta začíná 40 px od kraje). Nadpis se centruje mezi logo a tenhle blok.
    $bottomTop = $isSub ? 274 : 314;
    $headBase = $isSub ? 144 : 102;
    $blockHeight = $headBase + ($noteLines ? 16 + $noteLines * $noteLineHeight : 0);
    $start = 24 + (int) round(($bottomTop - 24 - $blockHeight) / 2);

    $amountText = isset($c['amount']) ? number_format((float) $c['amount'], 0, ',', "\u{00A0}") : '';
    $isEur = ($c['currency'] ?? 'CZK') === 'EUR';
    $months = (int) ($c['months'] ?? 3);
@endphp
<div class="kavi-cert" data-cert-variant="{{ $isSub ? 'subscription' : 'voucher' }}" data-bottom-top="{{ $bottomTop }}" data-head-base="{{ $headBase }}" style="position: relative; width: 794px; height: 559px; overflow: hidden; background: #ffffff; color: #1c1c1c; font-family: 'Roboto', Arial, sans-serif;">

    {{-- Fotka vlevo se štítky --}}
    <div style="position: absolute; left: 0; top: 0; width: 340px; height: 559px; overflow: hidden; background: #bcbeb1;">
        <img src="{{ $asset($photo) }}" alt="" style="position: absolute; left: 0; top: 0; width: 340px; height: 559px; display: block;">
        <div style="position: absolute; left: 32px; top: 471px; width: 300px; height: 28px;">
            <span style="display: inline-block; background: #ca4136; color: #000000; padding: 2px 8px; {{ $display }} font-size: 16px; {{ $lh(16, 24) }} letter-spacing: -0.025em; text-transform: uppercase;"><span style="{{ $fix(16, 24) }}">{{ $chips[0] }}</span></span>
        </div>
        <div style="position: absolute; left: 32px; top: 503px; width: 300px; height: 21px;">
            <span style="display: inline-block; background: #ca4136; color: #000000; padding: 2px 8px; {{ $eyebrow }}"><span style="{{ $fix(12, 17, 'roboto') }}">{{ $chips[1] }}</span></span>
        </div>
    </div>

    {{-- Pravá část: padding 40 / 44 / 36 --}}
    <div style="position: absolute; left: 384px; top: 40px; width: 366px; height: 483px;">

        <img src="{{ $asset('images/kavi-logo-black.png') }}" alt="KAVI" style="position: absolute; left: 0; top: 0; height: 24px; width: 52px; display: block;">
        <div style="position: absolute; right: 0; top: 4px; color: #ca4136; white-space: nowrap; {{ $eyebrow }}"><span style="{{ $fix(12, 17, 'roboto') }}">{{ $isSub ? $tr('eyebrow_subscription') : $tr('eyebrow_voucher') }}</span></div>

        {{-- Nadpis a věnování --}}
        @if($isSub)
        <div data-cert-offset="-13" style="position: absolute; left: 0; top: {{ $start - 13 }}px; width: 366px; white-space: nowrap; {{ $display }} font-size: 52px; {{ $lh(52, 75) }} letter-spacing: -0.025em; text-transform: uppercase; color: #1c1c1c;"><span data-cert-field="headline" style="{{ $fix(52, 75) }}">{{ __('gifts.months_word.' . $months, [], $loc) }}</span></div>
        <div data-cert-offset="35" style="position: absolute; left: 0; top: {{ $start + 35 }}px; width: 366px; white-space: nowrap; {{ $display }} font-size: 52px; {{ $lh(52, 75) }} letter-spacing: -0.025em; text-transform: uppercase; color: #ca4136;"><span style="{{ $fix(52, 75) }}">{{ $tr('headline_middle') }}</span></div>
        <div data-cert-offset="83" style="position: absolute; left: 0; top: {{ $start + 83 }}px; width: 366px; white-space: nowrap; {{ $display }} font-size: 52px; {{ $lh(52, 75) }} letter-spacing: -0.025em; text-transform: uppercase; color: #1c1c1c;"><span style="{{ $fix(52, 75) }}">{{ $tr('headline_last') }}</span></div>
        @else
        <div data-cert-offset="-21" style="position: absolute; left: 0; top: {{ $start - 21 }}px; width: 366px; white-space: nowrap; {{ $display }} font-size: 80px; {{ $lh(80, 115) }} letter-spacing: -0.025em; text-transform: uppercase; color: #1c1c1c;"><span style="{{ $fix(80, 115) }}">@if($isEur)<span style="color: #ca4136;">€</span><span data-cert-field="amount">{{ $amountText }}</span>@else<span data-cert-field="amount">{{ $amountText }}</span>&nbsp;<span style="color: #ca4136;">Kč</span>@endif</span></div>
        <div data-cert-offset="72" style="position: absolute; left: 0; top: {{ $start + 72 }}px; width: 366px; white-space: nowrap; {{ $display }} font-size: 24px; {{ $lh(24, 35) }} letter-spacing: -0.025em; text-transform: uppercase; color: #1c1c1c;"><span style="{{ $fix(24, 35) }}">{{ $tr('voucher_sub') }}</span></div>
        @endif
        <div data-cert-field="note-wrap" data-cert-offset="{{ $headBase + 16 }}" style="position: absolute; left: 0; top: {{ $start + $headBase + 16 }}px; width: 366px; {{ $light }} font-size: {{ $noteSize }}px; {{ $lh($noteSize, $noteLineHeight, 'roboto') }} color: #2d2d2d; {{ $note === '' ? 'display: none;' : '' }}"><span style="{{ $fix($noteSize, $noteLineHeight, 'roboto') }}">{{ $tr('quote_open') }}<span data-cert-field="note">{{ $note }}</span>{{ $tr('quote_close') }}</span></div>

        {{-- Řádky, kód v černém pruhu a patička --}}
        <div style="position: absolute; left: 0; top: {{ $bottomTop }}px; width: 366px;">
            <table cellpadding="0" cellspacing="0" style="width: 366px; border-collapse: collapse;">
                @foreach(array_filter([
                    ['label' => $tr('to'), 'value' => $c['recipient'] ?? '', 'field' => 'recipient'],
                    ['label' => $tr('from'), 'value' => $c['giver'] ?? '', 'field' => 'giver'],
                    $isSub ? ['label' => $tr('box'), 'value' => $c['box'] ?? '', 'field' => 'box'] : null,
                ]) as $row)
                <tr>
                    <td style="height: 24px; padding: 7px 0; border-top: 1px solid #bcbeb1; vertical-align: middle; white-space: nowrap; color: #5f5b57; {{ $eyebrow }}"><span style="{{ $fix(12, 17, 'roboto') }}">{{ $row['label'] }}</span></td>
                    <td style="height: 24px; padding: 7px 0 7px 16px; border-top: 1px solid #bcbeb1; vertical-align: middle; text-align: right; {{ $display }} font-size: 16px; {{ $lh(16, 24) }}"><span data-cert-field="{{ $row['field'] }}" style="{{ $fix(16, 24) }}">{{ $row['value'] }}</span></td>
                </tr>
                @endforeach
            </table>

            <table cellpadding="0" cellspacing="0" style="width: 366px; margin-top: 8px; border-collapse: collapse; background: #1c1c1c;">
                <tr>
                    <td style="height: 29px; padding: 11px 0 11px 16px; vertical-align: middle; color: #bcbeb1; {{ $eyebrow }}"><span style="{{ $fix(12, 17, 'roboto') }}">{{ $tr('code') }}</span></td>
                    <td style="height: 29px; padding: 11px 16px 11px 0; vertical-align: middle; text-align: right; white-space: nowrap; color: #ffffff; {{ $display }} font-size: 20px; {{ $lh(20, 29) }} letter-spacing: 0.08em;"><span data-cert-field="code" style="{{ $fix(20, 29) }}">{{ $c['code'] ?? 'XXXX-XXXX' }}</span></td>
                </tr>
            </table>

            <div style="margin-top: 11px; white-space: nowrap; color: #5f5b57; {{ $eyebrow }}"><span style="{{ $fix(12, 17, 'roboto') }}">
                @if($isSub)
                {{ $tr('footer_subscription', ['url' => $c['redeem_display'] ?? '', 'date' => $c['valid_until'] ?? '']) }}
                @else
                {{ $tr('footer_voucher', ['site' => $c['site_display'] ?? '', 'date' => $c['valid_until'] ?? '']) }}
                @endif
            </span></div>
        </div>
    </div>
</div>
