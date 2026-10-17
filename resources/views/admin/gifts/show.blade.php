@extends('layouts.admin')

@section('title', 'Dárek ' . ($gift->code ?? '#' . $gift->id) . ' - Admin Panel')

@section('content')
<div class="p-6 max-w-5xl">
    <div class="mb-6">
        <a href="{{ route('admin.gifts.index') }}" class="text-sm text-blue-600 hover:underline">&larr; Dárky</a>
        <h1 class="text-3xl font-bold text-gray-900 mt-2 font-mono">{{ $gift->code ?? 'Nevystaveno' }}</h1>
        <p class="text-gray-600 mt-1">{{ $gift->itemName('cs') }} · stav: <strong>{{ $gift->status }}</strong></p>
    </div>

    @foreach(['success' => 'bg-green-50 border-green-200 text-green-800', 'error' => 'bg-red-50 border-red-200 text-red-800'] as $key => $class)
    @if(session($key))
    <div class="mb-6 border px-4 py-3 rounded-lg {{ $class }}">{{ session($key) }}</div>
    @endif
    @endforeach

    <div class="grid md:grid-cols-2 gap-6">
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h2 class="font-semibold mb-4">Certifikát</h2>
            <dl class="space-y-2 text-sm">
                <div class="flex justify-between gap-4"><dt class="text-gray-500">Pro</dt><dd>{{ $gift->recipient_name }}</dd></div>
                <div class="flex justify-between gap-4"><dt class="text-gray-500">Od</dt><dd>{{ $gift->giver_name }}</dd></div>
                <div class="flex justify-between gap-4"><dt class="text-gray-500">Věnování</dt><dd class="text-right">{{ $gift->note ?: '–' }}</dd></div>
                @if($gift->isSubscription())
                <div class="flex justify-between gap-4"><dt class="text-gray-500">Box</dt><dd>{{ $gift->boxLabel('cs') }}, {{ $gift->months }} měsíců</dd></div>
                @else
                <div class="flex justify-between gap-4"><dt class="text-gray-500">Hodnota</dt><dd>{{ \App\Helpers\CurrencyHelper::formatByCurrency((float) $gift->amount, $gift->currency, 0) }}</dd></div>
                @endif
                <div class="flex justify-between gap-4"><dt class="text-gray-500">Platí do</dt><dd>{{ $gift->valid_until?->format('d.m.Y') ?? '–' }}</dd></div>
                <div class="flex justify-between gap-4"><dt class="text-gray-500">Jazyk / měna</dt><dd>{{ $gift->locale }} / {{ $gift->currency }}</dd></div>
            </dl>
            @if($gift->isIssued())
            <div class="flex flex-wrap gap-3 mt-6">
                <a href="{{ route('admin.gifts.pdf', $gift) }}" class="px-4 py-2 bg-gray-900 text-white rounded-lg text-sm">Stáhnout PDF</a>
                <form method="POST" action="{{ route('admin.gifts.regenerate-pdf', $gift) }}">@csrf<button class="px-4 py-2 border border-gray-300 rounded-lg text-sm">Přegenerovat PDF</button></form>
            </div>
            @endif
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h2 class="font-semibold mb-4">Kupující a doručení</h2>
            <dl class="space-y-2 text-sm">
                <div class="flex justify-between gap-4"><dt class="text-gray-500">Kupující</dt><dd class="text-right">{{ $gift->buyer_name }}<br>{{ $gift->buyer_email }}</dd></div>
                <div class="flex justify-between gap-4"><dt class="text-gray-500">Objednávka</dt><dd>@if($gift->order)<a href="{{ route('admin.orders.show', $gift->order) }}" class="text-blue-600 hover:underline">{{ $gift->order->order_number }}</a> ({{ $gift->order->payment_status }})@endif</dd></div>
                <div class="flex justify-between gap-4"><dt class="text-gray-500">E-mail kupujícímu</dt><dd>{{ $gift->buyer_email_sent_at?->format('d.m.Y H:i') ?? 'neodesláno' }}</dd></div>
                <div class="flex justify-between gap-4"><dt class="text-gray-500">Obdarovanému</dt><dd class="text-right">
                    @if($gift->delivery === 'recipient')
                    {{ $gift->recipient_email }}<br>{{ $gift->send_at?->format('d.m.Y H:i') }} · {{ $gift->recipient_email_sent_at ? 'odesláno ' . $gift->recipient_email_sent_at->format('d.m.Y H:i') : 'čeká' }}
                    @else
                    – (PDF předá kupující)
                    @endif
                </dd></div>
                @if($gift->last_delivery_error)
                <div class="text-red-600">Chyba ({{ $gift->delivery_attempts }}×): {{ $gift->last_delivery_error }}</div>
                @endif
            </dl>
            @if($gift->isIssued())
            <div class="mt-6 space-y-3">
                <form method="POST" action="{{ route('admin.gifts.resend-buyer', $gift) }}">@csrf<button class="px-4 py-2 border border-gray-300 rounded-lg text-sm">Znovu poslat kupujícímu</button></form>
                <form method="POST" action="{{ route('admin.gifts.resend-recipient', $gift) }}" class="flex flex-wrap gap-2 items-center">
                    @csrf
                    <input type="email" name="recipient_email" value="{{ $gift->recipient_email }}" placeholder="E-mail obdarovaného" required class="px-3 py-2 border border-gray-300 rounded-lg text-sm">
                    <input type="date" name="send_at" class="px-3 py-2 border border-gray-300 rounded-lg text-sm" title="Prázdné = hned">
                    <button class="px-4 py-2 border border-gray-300 rounded-lg text-sm">Poslat obdarovanému</button>
                </form>
            </div>
            @endif
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-6 md:col-span-2">
            <h2 class="font-semibold mb-4">Uplatnění</h2>
            @if($gift->subscription)
            <p class="text-sm">Předplatné <a href="{{ route('admin.subscriptions.show', $gift->subscription) }}" class="text-blue-600 hover:underline">{{ $gift->subscription->subscription_number }}</a>, uplatněno {{ $gift->redeemed_at?->format('d.m.Y H:i') }} ({{ $gift->redeemedBy?->email }}), start {{ $gift->start_month }}, stav {{ $gift->subscription->status }}.</p>
            @elseif($gift->isVoucher() && $gift->coupon && $gift->coupon->usages->isNotEmpty())
            @foreach($gift->coupon->usages as $usage)
            <p class="text-sm">
                Uplatněno {{ $usage->created_at->format('d.m.Y H:i') }}@if($usage->user) ({{ $usage->user->email }})@endif
                @if($usage->order)na objednávku <a href="{{ route('admin.orders.show', $usage->order) }}" class="text-blue-600 hover:underline">{{ $usage->order->order_number }}</a> (platba: {{ $usage->order->payment_status }})@elseif($usage->subscription)na předplatné <a href="{{ route('admin.subscriptions.show', $usage->subscription) }}" class="text-blue-600 hover:underline">{{ $usage->subscription->subscription_number }}</a> (stav: {{ $usage->subscription->status }})@endif
            </p>
            @endforeach
            @else
            <p class="text-sm text-gray-500">Zatím neuplatněno.</p>
            @endif

            @if(! in_array($gift->status, ['redeemed', 'cancelled'], true))
            <form method="POST" action="{{ route('admin.gifts.void', $gift) }}" class="mt-6" onsubmit="return confirm('Opravdu zneplatnit tento dárek? Kód přestane platit.')">
                @csrf
                <button class="px-4 py-2 bg-red-600 text-white rounded-lg text-sm">Zneplatnit dárek</button>
            </form>
            @endif
        </div>
    </div>
</div>
@endsection
