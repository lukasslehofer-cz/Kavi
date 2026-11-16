{{--
    Co přijde v dalším boxu (data z UpcomingBoxMailable::boxViewData()).

    $boxSource: 'slots'  = kávy přesně podle konfigurace zákazníka
                'lineup' = jen pražírny měsíce, sloty rozesílky ještě nejsou vyplněné
                null     = nic není zadané, vypíše se $pendingText (nebo nic)
--}}
@if($boxSource === 'slots')
<div style="font-size: 11px; color: #76716C; margin: 0 0 8px 0; text-transform: uppercase; letter-spacing: 0.15em;">
    {{ __('emails.upcoming_box.contents_label', [], $locale) }}
</div>
<table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="border-top: 2px solid #CA4136; margin: 0 0 40px 0;">
    @foreach($roasteries as $index => $roastery)
    <tr>
        <td style="padding: 16px 0; border-bottom: 1px solid #bcbeb1; vertical-align: top; width: 32px;">
            <span style="font-size: 11px; color: #CA4136;">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
        </td>
        <td style="padding: 16px 0; border-bottom: 1px solid #bcbeb1; vertical-align: top;">
            <div style="font-size: 20px; color: #1c1c1c; margin-bottom: 2px; letter-spacing: -0.01em;">
                @if($roastery['url'])
                <a href="{{ $roastery['url'] }}" style="color: #1c1c1c; text-decoration: none;">{{ $roastery['name'] }}</a>
                @else
                {{ $roastery['name'] }}
                @endif
            </div>
            @if($roastery['place'])
            <div style="font-size: 12px; color: #76716C; margin-bottom: 6px;">{{ $roastery['place'] }}</div>
            @endif
            @foreach($roastery['coffees'] as $coffee)
            <div style="font-size: 14px; color: #4a4a4a; line-height: 1.5;">{{ $coffee['quantity'] > 1 ? $coffee['quantity'].'× ' : '' }}{{ $coffee['name'] }}</div>
            @endforeach
        </td>
    </tr>
    @endforeach
</table>
@elseif($boxSource === 'lineup')
<p style="font-size: 16px; color: #1c1c1c; line-height: 1.6; margin: 0 0 40px 0;">
    {{ __('emails.upcoming_box.lineup', ['month' => $monthLocative, 'roasteries' => $roasteryNames], $locale) }}
</p>
@elseif(! empty($pendingText))
<p style="font-size: 16px; color: #1c1c1c; line-height: 1.6; margin: 0 0 40px 0;">
    {{ $pendingText }}
</p>
@endif
