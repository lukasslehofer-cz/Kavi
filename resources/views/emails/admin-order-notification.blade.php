<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $type === 'order' ? 'Nová objednávka' : 'Nové předplatné' }}</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #f3f4f6;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .header {
            background-color: {{ $type === 'order' ? '#10b981' : '#6366f1' }};
            padding: 24px;
            text-align: center;
        }
        .header h1 {
            color: #ffffff;
            margin: 0;
            font-size: 20px;
            font-weight: 600;
        }
        .badge {
            display: inline-block;
            background-color: rgba(255,255,255,0.2);
            color: #ffffff;
            padding: 4px 12px;
            border-radius: 9999px;
            font-size: 12px;
            margin-top: 8px;
        }
        .content {
            padding: 24px;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #e5e7eb;
        }
        .info-row:last-child {
            border-bottom: none;
        }
        .info-label {
            color: #6b7280;
            font-size: 14px;
        }
        .info-value {
            color: #111827;
            font-weight: 600;
            font-size: 14px;
            text-align: right;
        }
        .section {
            margin-top: 20px;
            padding: 16px;
            background-color: #f9fafb;
            border-radius: 8px;
        }
        .section-title {
            font-size: 14px;
            font-weight: 600;
            color: #374151;
            margin: 0 0 12px 0;
        }
        .item {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            font-size: 14px;
        }
        .item-name {
            color: #374151;
        }
        .item-price {
            color: #111827;
            font-weight: 500;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 16px 0 0;
            margin-top: 12px;
            border-top: 2px solid #e5e7eb;
            font-size: 18px;
            font-weight: 700;
        }
        .total-label {
            color: #111827;
        }
        .total-value {
            color: #10b981;
        }
        .button {
            display: inline-block;
            background-color: #e6305a;
            color: #ffffff !important;
            text-decoration: none;
            padding: 12px 24px;
            border-radius: 6px;
            font-weight: 600;
            font-size: 14px;
            margin-top: 16px;
        }
        .footer {
            padding: 16px 24px;
            background-color: #f9fafb;
            text-align: center;
            font-size: 12px;
            color: #6b7280;
        }
        .address-box {
            font-size: 13px;
            color: #4b5563;
            line-height: 1.6;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>{{ $type === 'order' ? '🛒 Nová objednávka' : '📦 Nové předplatné' }}</h1>
            <div class="badge">
                @if($type === 'order')
                    {{ $order->order_number }}
                @else
                    {{ $subscription->subscription_number ?? 'SUB-' . $subscription->id }}
                @endif
            </div>
        </div>

        <!-- Content -->
        <div class="content">
            @if($type === 'order')
                {{-- Order notification --}}
                <div class="info-row">
                    <span class="info-label">Zákazník</span>
                    <span class="info-value">{{ $order->shipping_address['name'] ?? 'N/A' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Email</span>
                    <span class="info-value">{{ $order->shipping_address['email'] ?? $order->user?->email ?? 'N/A' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Telefon</span>
                    <span class="info-value">{{ $order->shipping_address['phone'] ?? 'N/A' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Datum</span>
                    <span class="info-value">{{ $order->created_at->format('d.m.Y H:i') }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Stav platby</span>
                    <span class="info-value" style="color: {{ $order->payment_status === 'paid' ? '#10b981' : '#f59e0b' }}">
                        {{ $order->payment_status === 'paid' ? '✓ Zaplaceno' : '⏳ Čeká na platbu' }}
                    </span>
                </div>

                {{-- Order Items --}}
                <div class="section">
                    <h3 class="section-title">Položky objednávky</h3>
                    @foreach($order->items as $item)
                        <div class="item">
                            <span class="item-name">{{ $item->quantity }}× {{ $item->product_name }}</span>
                            <span class="item-price">{{ number_format($item->total, 0, ',', ' ') }} Kč</span>
                        </div>
                    @endforeach
                    
                    <div class="item" style="color: #6b7280;">
                        <span>Doprava</span>
                        <span>{{ $order->shipping > 0 ? number_format($order->shipping, 0, ',', ' ') . ' Kč' : 'Zdarma' }}</span>
                    </div>
                    
                    @if($order->discount_amount > 0)
                        <div class="item" style="color: #10b981;">
                            <span>Sleva{{ $order->coupon_code ? ' (' . $order->coupon_code . ')' : '' }}</span>
                            <span>-{{ number_format($order->discount_amount, 0, ',', ' ') }} Kč</span>
                        </div>
                    @endif
                    
                    <div class="total-row">
                        <span class="total-label">Celkem</span>
                        <span class="total-value">{{ number_format($order->total, 0, ',', ' ') }} Kč</span>
                    </div>
                </div>

                {{-- Delivery Address --}}
                <div class="section">
                    <h3 class="section-title">📍 Doručení</h3>
                    <div class="address-box">
                        @if(isset($order->shipping_address['packeta_point_name']))
                            <strong>Výdejní místo:</strong><br>
                            {{ $order->shipping_address['packeta_point_name'] }}<br>
                            {{ $order->shipping_address['packeta_point_address'] ?? '' }}
                        @else
                            {{ $order->shipping_address['billing_address'] ?? '' }}<br>
                            {{ $order->shipping_address['billing_postal_code'] ?? '' }} {{ $order->shipping_address['billing_city'] ?? '' }}
                        @endif
                    </div>
                </div>

                <div style="text-align: center; margin-top: 24px;">
                    <a href="{{ route('admin.orders.show', $order) }}" class="button">
                        Zobrazit v administraci
                    </a>
                </div>

            @else
                {{-- Subscription notification --}}
                @php
                    $config = $subscription->configuration ?? [];
                    $shippingAddr = $subscription->shipping_address ?? [];
                @endphp

                <div class="info-row">
                    <span class="info-label">Zákazník</span>
                    <span class="info-value">{{ $shippingAddr['name'] ?? $subscription->user?->name ?? 'N/A' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Email</span>
                    <span class="info-value">{{ $shippingAddr['email'] ?? $subscription->user?->email ?? 'N/A' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Telefon</span>
                    <span class="info-value">{{ $shippingAddr['phone'] ?? 'N/A' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Datum vytvoření</span>
                    <span class="info-value">{{ $subscription->created_at->format('d.m.Y H:i') }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Stav</span>
                    <span class="info-value" style="color: {{ $subscription->status === 'active' ? '#10b981' : '#f59e0b' }}">
                        @switch($subscription->status)
                            @case('active') ✓ Aktivní @break
                            @case('pending') ⏳ Čeká na platbu @break
                            @case('paused') ⏸ Pozastaveno @break
                            @default {{ $subscription->status }}
                        @endswitch
                    </span>
                </div>

                {{-- Subscription Configuration --}}
                <div class="section">
                    <h3 class="section-title">Konfigurace předplatného</h3>
                    <div class="item">
                        <span class="item-name">Počet balení</span>
                        <span class="item-price">{{ $config['amount'] ?? 'N/A' }}× měsíčně</span>
                    </div>
                    <div class="item">
                        <span class="item-name">Typ kávy</span>
                        <span class="item-price">
                            @if(($config['type'] ?? '') === 'espresso')
                                Espresso
                            @elseif(($config['type'] ?? '') === 'filter')
                                Filtr
                            @elseif(($config['type'] ?? '') === 'mix')
                                Mix ({{ $config['mix']['espresso'] ?? 0 }}E / {{ $config['mix']['filter'] ?? 0 }}F)
                            @else
                                {{ $config['type'] ?? 'N/A' }}
                            @endif
                        </span>
                    </div>
                    <div class="item">
                        <span class="item-name">Frekvence</span>
                        <span class="item-price">
                            @if(($subscription->frequency_months ?? 0) == 0)
                                Jednorázově
                            @elseif($subscription->frequency_months == 1)
                                Měsíčně
                            @elseif($subscription->frequency_months == 2)
                                Každé 2 měsíce
                            @elseif($subscription->frequency_months == 3)
                                Čtvrtletně
                            @else
                                Každých {{ $subscription->frequency_months }} měsíců
                            @endif
                        </span>
                    </div>
                    @if($config['isDecaf'] ?? false)
                        <div class="item">
                            <span class="item-name">Bez kofeinu</span>
                            <span class="item-price">✓ Ano</span>
                        </div>
                    @endif

                    @if($subscription->discount_amount > 0 && ($subscription->discount_months_remaining === null || $subscription->discount_months_remaining > 0))
                        <div class="item" style="color: #10b981;">
                            <span>Sleva{{ $subscription->coupon_code ? ' (' . $subscription->coupon_code . ')' : '' }}</span>
                            <span>-{{ number_format($subscription->discount_amount, 0, ',', ' ') }} Kč</span>
                        </div>
                    @endif
                    
                    <div class="total-row">
                        <span class="total-label">Cena</span>
                        <span class="total-value">{{ number_format($subscription->configured_price ?? 0, 0, ',', ' ') }} Kč</span>
                    </div>
                </div>

                {{-- Delivery Address --}}
                <div class="section">
                    <h3 class="section-title">📍 Doručení</h3>
                    <div class="address-box">
                        @if(isset($shippingAddr['packeta_point_name']))
                            <strong>Výdejní místo:</strong><br>
                            {{ $shippingAddr['packeta_point_name'] }}<br>
                            {{ $shippingAddr['packeta_point_address'] ?? '' }}
                        @else
                            {{ $shippingAddr['billing_address'] ?? '' }}<br>
                            {{ $shippingAddr['billing_postal_code'] ?? '' }} {{ $shippingAddr['billing_city'] ?? '' }}
                        @endif
                    </div>
                </div>

                <div style="text-align: center; margin-top: 24px;">
                    <a href="{{ route('admin.subscriptions.show', $subscription) }}" class="button">
                        Zobrazit v administraci
                    </a>
                </div>
            @endif
        </div>

        <!-- Footer -->
        <div class="footer">
            Toto je automatická notifikace z e-shopu KAVI.cz<br>
            {{ now()->format('d.m.Y H:i') }}
        </div>
    </div>
</body>
</html>

