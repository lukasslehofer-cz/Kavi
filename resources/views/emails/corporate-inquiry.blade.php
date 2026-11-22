@php
    $phoneHref = $inquiry->phone ? preg_replace('/[^\d+]/', '', $inquiry->phone) : null;
    $rows = [
        'Jméno a příjmení' => e($inquiry->name),
        'Firma' => e($inquiry->company),
        'E-mail' => '<a href="mailto:' . e($inquiry->email) . '" style="color: #ca4136;">' . e($inquiry->email) . '</a>',
        'Telefon' => $inquiry->phone ? '<a href="tel:' . e($phoneHref) . '" style="color: #ca4136;">' . e($inquiry->phone) . '</a>' : '–',
        'Pro koho' => e($inquiry->audienceLabel()),
        'Přibližný počet kusů' => e($inquiry->quantityLabel()) . ' ks',
        'Kdy má dárek dorazit' => $inquiry->delivery_date ? e(\App\Helpers\DateFormat::full($inquiry->delivery_date, 'cs')) : '–',
        'Poznámka' => $inquiry->note ? nl2br(e($inquiry->note)) : '–',
    ];
    $utm = array_filter((array) $inquiry->utm);
@endphp
<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Poptávka firemních dárků</title>
</head>
<body style="margin: 0; padding: 0; background-color: #e5e6df; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; color: #1c1c1c;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color: #e5e6df;">
        <tr>
            <td align="center" style="padding: 24px 12px;">
                <table role="presentation" width="600" cellpadding="0" cellspacing="0" style="max-width: 600px; width: 100%; background-color: #ffffff;">
                    <tr>
                        <td style="padding: 24px 28px; border-top: 4px solid #ca4136;">
                            <p style="margin: 0 0 6px; font-size: 12px; letter-spacing: 0.1em; text-transform: uppercase; color: #5f5b57;">Pro firmy · {{ \App\Helpers\DateFormat::full($inquiry->created_at, 'cs') }} {{ $inquiry->created_at->format('H:i') }}</p>
                            <h1 style="margin: 0; font-size: 22px; line-height: 28px; font-weight: 400; text-transform: uppercase;">Poptávka firemních dárků</h1>
                            <p style="margin: 8px 0 0; font-size: 14px; line-height: 22px; color: #5f5b57;">Odpovědí na tento e-mail napíšete rovnou zákazníkovi. Nabídku slibujeme do 2 pracovních dnů.</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 0 28px 24px;">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border-collapse: collapse; font-size: 15px; line-height: 22px;">
                                @foreach($rows as $label => $value)
                                <tr>
                                    <td valign="top" style="width: 190px; padding: 10px 12px 10px 0; border-top: 1px solid #bcbeb1; color: #5f5b57;">{{ $label }}</td>
                                    <td valign="top" style="padding: 10px 0; border-top: 1px solid #bcbeb1;">{!! $value !!}</td>
                                </tr>
                                @endforeach
                            </table>
                            @if($utm)
                            <p style="margin: 16px 0 0; font-size: 12px; line-height: 18px; color: #76716c;">Zdroj: @foreach($utm as $key => $value){{ $key }}={{ $value }}{{ $loop->last ? '' : ', ' }}@endforeach</p>
                            @endif
                            <p style="margin: 20px 0 0;"><a href="{{ route('admin.gifts.corporate.show', $inquiry) }}" style="display: inline-block; padding: 10px 18px; background-color: #1c1c1c; color: #ffffff; text-decoration: none; font-size: 14px;">Otevřít poptávku v adminu</a></p>
                            <p style="margin: 12px 0 0; font-size: 12px; line-height: 18px; color: #76716c;">Poptávka #{{ $inquiry->id }}. V adminu (Dárky › Firemní dárky) jí nastavíte stav a doplníte detaily dealu.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
