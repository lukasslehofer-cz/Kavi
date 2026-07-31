@extends('layouts.admin')

@section('content')
<div class="p-6">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">{{ __('affiliate.partners_title') }}</h1>
        </div>

        <!-- Search -->
        <div class="bg-white rounded-lg shadow p-4 mb-6">
            <form method="GET" class="flex gap-4">
                <input 
                    type="text" 
                    name="search" 
                    value="{{ $search ?? '' }}"
                    placeholder="{{ __('affiliate.search_users') }}" 
                    class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                >
                <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-md hover:bg-primary-700">
                    {{ __('affiliate.search') }}
                </button>
                @if($search)
                <a href="{{ route('admin.affiliate.partners.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">
                    {{ __('affiliate.reset') }}
                </a>
                @endif
            </form>
        </div>

        <!-- Partners List -->
        @if($partners->count() > 0)
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('affiliate.partner') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('affiliate.partner_since') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('affiliate.codes') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('affiliate.total_earned') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('affiliate.payable') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('affiliate.clicks') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('affiliate.actions') }}
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($partners as $partner)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div>
                                    <a href="{{ route('admin.affiliate.partners.show', $partner) }}" class="text-sm font-medium text-purple-700 hover:text-purple-900">
                                        {{ $partner->name }}
                                    </a>
                                    <div class="text-sm text-gray-500">
                                        {{ $partner->email }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $partner->affiliate_activated_at?->format('d.m.Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $partner->affiliateCoupons->count() }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ \App\Helpers\CurrencyHelper::formatAmount($partner->overview->total_earned) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ \App\Helpers\CurrencyHelper::formatAmount($partner->overview->payable_amount) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $partner->overview->clicks }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ route('admin.affiliate.partners.show', $partner) }}" class="text-purple-600 hover:text-purple-900 mr-4">
                                {{ __('affiliate.partner_detail') }}
                            </a>
                            <form method="POST" action="{{ route('admin.affiliate.partners.deactivate', $partner) }}" class="inline">
                                @csrf
                                <button 
                                    type="submit" 
                                    class="text-red-600 hover:text-red-900"
                                    onclick="return confirm('{{ __('affiliate.deactivate_confirm') }}')"
                                >
                                    {{ __('affiliate.deactivate_partner') }}
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $partners->links() }}
        </div>
        @else
        <div class="bg-white rounded-lg shadow p-12 text-center">
            <p class="text-gray-500">{{ __('affiliate.no_partners') }}</p>
        </div>
        @endif

        <!-- Activate New Partner -->
        <div class="mt-8 bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-bold text-gray-900 mb-4">{{ __('affiliate.activate_partner') }}</h2>
            <form method="POST" action="{{ route('admin.affiliate.partners.activate-by-email') }}" class="flex gap-4">
                @csrf
                <input 
                    type="email" 
                    name="email" 
                    placeholder="{{ __('affiliate.user_email') }}" 
                    required
                    class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                >
                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                    {{ __('affiliate.activate') }}
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
