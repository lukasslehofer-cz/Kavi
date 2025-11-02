<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nový dotaz z kontaktního formuláře</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            line-height: 1.6;
            color: #333333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            background-color: #ffffff;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .header {
            border-bottom: 3px solid #D4A574;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            margin: 0;
            color: #1a1a1a;
            font-size: 24px;
        }
        .info-row {
            margin-bottom: 20px;
        }
        .label {
            font-weight: 600;
            color: #666666;
            margin-bottom: 5px;
        }
        .value {
            color: #1a1a1a;
            padding: 10px;
            background-color: #f9f9f9;
            border-radius: 4px;
        }
        .message-box {
            background-color: #f9f9f9;
            border-left: 4px solid #D4A574;
            padding: 15px;
            margin-top: 20px;
            border-radius: 4px;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e5e5e5;
            font-size: 12px;
            color: #999999;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📧 Nový dotaz z kontaktního formuláře</h1>
        </div>

        <div class="info-row">
            <div class="label">Jméno / Firma:</div>
            <div class="value">{{ $customerName }}</div>
        </div>

        <div class="info-row">
            <div class="label">E-mail:</div>
            <div class="value"><a href="mailto:{{ $customerEmail }}">{{ $customerEmail }}</a></div>
        </div>

        @if(!empty($customerMessage))
        <div class="info-row">
            <div class="label">Zpráva:</div>
            <div class="message-box">
                {{ $customerMessage }}
            </div>
        </div>
        @else
        <div class="info-row">
            <div class="label">Zpráva:</div>
            <div class="value" style="font-style: italic; color: #999;">Zákazník nepřidal žádnou zprávu</div>
        </div>
        @endif

        <div class="footer">
            <p>Tato zpráva byla odeslána z kontaktního formuláře na webu KAVI.cz</p>
            <p>© {{ date('Y') }} KAVI.cz</p>
        </div>
    </div>
</body>
</html>

