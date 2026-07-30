{{-- Hvězdičky jako samostatné odkazy. Klik rovnou uloží hodnocení a otevře
     formulář s předvyplněnou hvězdičkou. --}}
<div style="margin: 32px 0; padding: 24px; border-left: 3px solid #CA4136; background-color: #d5d7ca;">
    <div style="font-size: 11px; font-weight: 400; color: #1c1c1c; text-transform: uppercase; letter-spacing: 0.15em; margin-bottom: 18px; text-align: center;">
        {{ __('emails.review_request.rate_label', [], $locale) }}
    </div>

    <table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center" style="margin: 0 auto;">
        <tr>
            @foreach($starLinks as $rating => $url)
            <td style="padding: 0 3px;">
                <a href="{{ $url }}" target="_blank" rel="noopener"
                   style="display: block; background-color: #e5e6df; border: 1px solid #bcbeb1; padding: 10px 14px; font-size: 20px; line-height: 1; color: #76716C !important; text-decoration: none;"
                   aria-label="{{ $rating }}/5">&#9733;</a>
            </td>
            @endforeach
        </tr>
    </table>
</div>

<p style="font-size: 14px; color: #5a5a5a; line-height: 1.7; margin: 0 0 24px 0;">
    {{ __('emails.review_request.time_note', [], $locale) }}
</p>

<p style="font-size: 13px; margin: 0;">
    <a href="{{ $reviewLink }}" target="_blank" rel="noopener" style="color: #CA4136; text-decoration: none;">
        {{ __('emails.review_request.fallback_link', [], $locale) }} &rarr;
    </a>
</p>
