{{--
    Zadní strana dárkového certifikátu: jak dárek uplatnit + QR kód na stránku uplatnění.
    Jen pro PDF, poznámky k layoutu a řádkování jsou v _front.blade.php.

    $c   certificateData() z GiftPurchase
    $qr  data URI PNG s QR kódem na redeem_url
--}}
@php
    $lh = fn (float $size, float $lineHeight, string $family = 'heros') => \App\Helpers\PdfText::lineHeight(true, $size, $lineHeight, $family);
    $fix = fn (float $size, float $lineHeight, string $family = 'heros') => \App\Helpers\PdfText::shift(true, $size, $lineHeight, $family);
    $loc = $c['locale'] ?? app()->getLocale();
    $isSub = ($c['type'] ?? 'subscription') === 'subscription';
    $tr = fn (string $key, array $replace = []) => __('gifts.certificate.back.' . $key, $replace, $loc);
    $display = "font-family: 'TexGyreHeros', 'Helvetica Neue', Arial, sans-serif; font-weight: normal;";
    $light = "font-family: 'RobotoLight', 'Roboto', Arial, sans-serif; font-weight: 300;";
    $eyebrow = 'font-size: 12px; ' . $lh(12, 17, 'roboto') . ' letter-spacing: 0.1em; text-transform: uppercase;';
    $steps = __('gifts.certificate.back.steps', [], $loc);
    $email = \App\Services\EmailService::getContactEmail($loc);
@endphp
<div class="kavi-cert" style="position: relative; width: 794px; height: 559px; overflow: hidden; background: #ffffff; color: #1c1c1c; font-family: 'Roboto', Arial, sans-serif;">
    {{-- padding 44 / 48 / 40 --}}
    <div style="position: absolute; left: 48px; top: 44px; width: 698px; height: 475px;">

        <img src="{{ public_path('images/kavi-logo-black.png') }}" alt="KAVI" style="position: absolute; left: 0; top: 0; height: 24px; width: 52px; display: block;">
        <div style="position: absolute; right: 0; top: 4px; color: #ca4136; white-space: nowrap; {{ $eyebrow }}"><span style="{{ $fix(12, 17, 'roboto') }}">{{ $tr('eyebrow') }}</span></div>

        <div style="position: absolute; left: 0; top: 70px; width: 698px; white-space: nowrap; {{ $display }} font-size: 40px; {{ $lh(40, 58) }} letter-spacing: -0.025em; text-transform: uppercase;"><span style="{{ $fix(40, 58) }}">{{ $tr('title') }} <span style="color: #ca4136;">{{ $tr('title_accent') }}</span></span></div>

        <table cellpadding="0" cellspacing="0" style="position: absolute; left: 0; top: 144px; width: 698px; border-collapse: collapse;">
            <tr>
                @foreach($steps as $i => $step)
                <td style="width: 211px; vertical-align: top; padding: 14px 0 0 0; border-top: 2px solid #ca4136;">
                    <div style="{{ $display }} font-size: 36px; {{ $lh(36, 52) }} color: #ca4136;"><span style="{{ $fix(36, 52) }}">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span></div>
                    <div style="margin-bottom: 4px; {{ $display }} font-size: 17px; {{ $lh(17, 22) }} letter-spacing: 0.025em; text-transform: uppercase;"><span style="{{ $fix(17, 22) }}">{{ __("gifts.certificate.back.steps.$i.t", ['url' => $c['redeem_display'] ?? ''], $loc) }}</span></div>
                    <div style="{{ $light }} font-size: 13px; {{ $lh(13, 20, 'roboto') }} color: #5f5b57;"><span style="{{ $fix(13, 20, 'roboto') }}">{{ __("gifts.certificate.back.steps.$i.d", [], $loc) }}</span></div>
                </td>
                @if(! $loop->last)
                <td style="width: 32px; padding: 0;"></td>
                @endif
                @endforeach
            </tr>
        </table>

        <div style="position: absolute; left: 0; top: 338px; width: 698px; height: 1px; background: #bcbeb1;"></div>

        <div style="position: absolute; left: 0; top: 398px; width: 480px;">
            <div style="{{ $light }} font-size: 13px; {{ $lh(13, 20, 'roboto') }} color: #2d2d2d;"><span style="{{ $fix(13, 20, 'roboto') }}">{{ $isSub ? $tr('info_subscription') : $tr('info_voucher') }}</span></div>
            <div style="margin-top: 10px; white-space: nowrap; color: #5f5b57; {{ $eyebrow }}"><span style="{{ $fix(12, 17, 'roboto') }}">{{ $tr('contact', ['email' => $email, 'site' => $c['site_display'] ?? '']) }}</span></div>
        </div>

        @if(! empty($qr))
        <img src="{{ $qr }}" alt="QR" style="position: absolute; right: 0; top: 358px; width: 92px; height: 92px; display: block;">
        @endif
        <div style="position: absolute; right: 0; top: 458px; white-space: nowrap; text-align: right; color: #5f5b57; {{ $eyebrow }}"><span style="{{ $fix(12, 17, 'roboto') }}">{{ $c['redeem_display'] ?? '' }}</span></div>
    </div>
</div>
