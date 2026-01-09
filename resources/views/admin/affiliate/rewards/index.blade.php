@extends('layouts.admin')

@section('content')
<div class="p-6">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">{{ __('affiliate.rewards_title') }}</h1>
        </div>

        <!-- Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-lg shadow p-4">
                <div class="text-sm text-gray-600">{{ __('affiliate.total_rewards') }}</div>
                <div class="text-2xl font-bold">{{ $statistics['total_count'] }}</div>
                <div class="text-sm text-gray-500">{{ \App\Helpers\CurrencyHelper::formatAmount($statistics['total_amount']) }}</div>
            </div>
            <div class="bg-yellow-50 rounded-lg shadow p-4">
                <div class="text-sm text-yellow-800">{{ __('affiliate.pending') }}</div>
                <div class="text-2xl font-bold text-yellow-900">{{ $statistics['pending_count'] }}</div>
                <div class="text-sm text-yellow-700">{{ \App\Helpers\CurrencyHelper::formatAmount($statistics['pending_amount']) }}</div>
            </div>
            <div class="bg-blue-50 rounded-lg shadow p-4">
                <div class="text-sm text-blue-800">{{ __('affiliate.approved') }}</div>
                <div class="text-2xl font-bold text-blue-900">{{ $statistics['approved_count'] }}</div>
                <div class="text-sm text-blue-700">{{ \App\Helpers\CurrencyHelper::formatAmount($statistics['approved_amount']) }}</div>
            </div>
            <div class="bg-green-50 rounded-lg shadow p-4">
                <div class="text-sm text-green-800">{{ __('affiliate.paid') }}</div>
                <div class="text-2xl font-bold text-green-900">{{ $statistics['paid_count'] }}</div>
                <div class="text-sm text-green-700">{{ \App\Helpers\CurrencyHelper::formatAmount($statistics['paid_amount']) }}</div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-lg shadow p-4 mb-6">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('affiliate.filter_by_partner') }}</label>
                    <select name="partner" class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                        <option value="">Všichni partneři</option>
                        @foreach($partners as $partner)
                        <option value="{{ $partner->id }}" {{ $partnerId == $partner->id ? 'selected' : '' }}>
                            {{ $partner->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('affiliate.filter_by_status') }}</label>
                    <select name="status" class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                        <option value="">Všechny stavy</option>
                        <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>{{ __('affiliate.pending') }}</option>
                        <option value="approved" {{ $status === 'approved' ? 'selected' : '' }}>{{ __('affiliate.approved') }}</option>
                        <option value="paid" {{ $status === 'paid' ? 'selected' : '' }}>{{ __('affiliate.paid') }}</option>
                        <option value="cancelled" {{ $status === 'cancelled' ? 'selected' : '' }}>Zrušeno</option>
                    </select>
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-md hover:bg-primary-700">
                        Filtrovat
                    </button>
                    @if($partnerId || $status)
                    <a href="{{ route('admin.affiliate.rewards.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">
                        Zrušit
                    </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Rewards List -->
        @if($rewards->count() > 0)
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('affiliate.partner') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('affiliate.type') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('affiliate.code') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('affiliate.amount') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('affiliate.status') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">{{ __('affiliate.reward_created') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Akce</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($rewards as $reward)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <div class="font-medium text-gray-900">{{ $reward->affiliatePartner->name }}</div>
                            <div class="text-gray-500">{{ $reward->affiliatePartner->email }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $reward->reward_type === 'subscription' ? __('affiliate.subscription') : __('affiliate.order') }}
                            @if($reward->subscription_payment_number)
                                ({{ $reward->subscription_payment_number }}.)
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $reward->coupon->code }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $reward->getFormattedAmount() }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($reward->status === 'pending')
                                <span class="px-2 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800">
                                    {{ __('affiliate.pending') }}
                                </span>
                            @elseif($reward->status === 'approved')
                                <span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">
                                    {{ __('affiliate.approved') }}
                                </span>
                            @elseif($reward->status === 'paid')
                                <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">
                                    {{ __('affiliate.paid') }}
                                </span>
                            @else
                                <span class="px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800">
                                    {{ $reward->status }}
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $reward->created_at->format('d.m.Y H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                            @if($reward->status === 'pending')
                                <form method="POST" action="{{ route('admin.affiliate.rewards.approve', $reward) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="text-blue-600 hover:text-blue-900">
                                        {{ __('affiliate.approve_reward') }}
                                    </button>
                                </form>
                            @endif
                            @if($reward->status === 'approved')
                                <form method="POST" action="{{ route('admin.affiliate.rewards.mark-paid', $reward) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="text-green-600 hover:text-green-900">
                                        {{ __('affiliate.mark_as_paid') }}
                                    </button>
                                </form>
                            @endif
                            @if($reward->status !== 'cancelled')
                                <form method="POST" action="{{ route('admin.affiliate.rewards.cancel', $reward) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Opravdu zrušit?')">
                                        {{ __('affiliate.cancel_reward') }}
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $rewards->links() }}
        </div>
        @else
        <div class="bg-white rounded-lg shadow p-12 text-center">
            <p class="text-gray-500">{{ __('affiliate.no_rewards_admin') }}</p>
        </div>
        @endif
    </div>
</div>
@endsection
