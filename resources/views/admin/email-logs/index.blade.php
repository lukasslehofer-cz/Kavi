@extends('layouts.admin')

@section('title', 'Odeslané emaily')

@section('content')
<div class="p-6">
    <!-- Success/Error Messages -->
    @if(session('success'))
    <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
        <p class="font-medium">{{ session('success') }}</p>
    </div>
    @endif

    @if(session('error'))
    <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
        <p class="font-medium">{{ session('error') }}</p>
    </div>
    @endif

    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Odeslané emaily</h1>
        <p class="text-gray-600 mt-1">Přehled všech odeslaných emailů z aplikace</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-8">
        <div class="bg-white rounded-lg p-4 text-center shadow-sm border border-gray-200">
            <p class="text-3xl font-bold text-gray-900">{{ $stats['total'] }}</p>
            <p class="text-sm text-gray-600 mt-1">Celkem</p>
        </div>
        <div class="bg-green-50 rounded-lg p-4 text-center shadow-sm border border-green-200">
            <p class="text-3xl font-bold text-green-700">{{ $stats['sent'] }}</p>
            <p class="text-sm text-green-600 mt-1">Odesláno</p>
        </div>
        <div class="bg-red-50 rounded-lg p-4 text-center shadow-sm border border-red-200">
            <p class="text-3xl font-bold text-red-700">{{ $stats['failed'] }}</p>
            <p class="text-sm text-red-600 mt-1">Selhalo</p>
        </div>
        <div class="bg-blue-50 rounded-lg p-4 text-center shadow-sm border border-blue-200">
            <p class="text-3xl font-bold text-blue-700">{{ $stats['today'] }}</p>
            <p class="text-sm text-blue-600 mt-1">Dnes</p>
        </div>
        <div class="bg-purple-50 rounded-lg p-4 text-center shadow-sm border border-purple-200">
            <p class="text-3xl font-bold text-purple-700">{{ $stats['this_week'] }}</p>
            <p class="text-sm text-purple-600 mt-1">Tento týden</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl p-6 mb-6 shadow-sm border border-gray-200">
        <form action="{{ route('admin.email-logs.index') }}" method="GET" class="flex flex-wrap gap-4">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-sm font-medium text-gray-700 mb-2">Hledat</label>
                <input 
                    type="text" 
                    name="search" 
                    value="{{ request('search') }}" 
                    placeholder="Email příjemce nebo předmět"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >
            </div>
            
            <div class="min-w-[180px]">
                <label class="block text-sm font-medium text-gray-700 mb-2">Typ emailu</label>
                <select name="mailable_type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="all" {{ request('mailable_type') === 'all' ? 'selected' : '' }}>Všechny</option>
                    @foreach($mailableTypes as $type)
                    <option value="{{ $type['value'] }}" {{ request('mailable_type') === $type['value'] ? 'selected' : '' }}>
                        {{ $type['label'] }}
                    </option>
                    @endforeach
                </select>
            </div>
            
            <div class="min-w-[120px]">
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="all" {{ request('status') === 'all' ? 'selected' : '' }}>Všechny</option>
                    <option value="sent" {{ request('status') === 'sent' ? 'selected' : '' }}>Odesláno</option>
                    <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Selhalo</option>
                </select>
            </div>

            <div class="min-w-[120px]">
                <label class="block text-sm font-medium text-gray-700 mb-2">Region</label>
                <select name="region" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="all" {{ request('region') === 'all' ? 'selected' : '' }}>Všechny</option>
                    <option value="cs" {{ request('region') === 'cs' ? 'selected' : '' }}>🇨🇿 Kavi.cz</option>
                    <option value="en" {{ request('region') === 'en' ? 'selected' : '' }}>🌍 Kavibox.com</option>
                </select>
            </div>

            <div class="min-w-[150px]">
                <label class="block text-sm font-medium text-gray-700 mb-2">Datum od</label>
                <input 
                    type="date" 
                    name="date_from" 
                    value="{{ request('date_from') }}" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >
            </div>

            <div class="min-w-[150px]">
                <label class="block text-sm font-medium text-gray-700 mb-2">Datum do</label>
                <input 
                    type="date" 
                    name="date_to" 
                    value="{{ request('date_to') }}" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >
            </div>
            
            <div class="flex items-end gap-2">
                <button type="submit" class="px-4 py-2 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-800 transition-colors">
                    Filtrovat
                </button>
                <a href="{{ route('admin.email-logs.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-300 transition-colors">
                    Resetovat
                </a>
            </div>
        </form>
    </div>

    <!-- Email Logs Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Datum/Čas</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Příjemce</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Předmět</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Typ</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Region</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Akce</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($emailLogs as $log)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $log->sent_at->format('d.m.Y H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $log->recipient }}</div>
                            @if($log->user)
                            <div class="text-xs text-gray-500">{{ $log->user->name }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">
                            <div class="max-w-xs truncate">{{ $log->subject }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $log->mailable_name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            @if($log->region === 'cs')
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                🇨🇿 Kavi.cz
                            </span>
                            @elseif($log->region === 'en')
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-purple-100 text-purple-800">
                                🌍 Kavibox.com
                            </span>
                            @else
                            <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($log->status === 'sent')
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                ✓ Odesláno
                            </span>
                            @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">
                                ✗ Selhalo
                            </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('admin.email-logs.show', $log) }}" class="text-blue-600 hover:text-blue-900 mr-3">
                                Detail
                            </a>
                            <form action="{{ route('admin.email-logs.resend', $log) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="text-gray-600 hover:text-gray-900" onclick="return confirm('Opravdu chcete znovu odeslat tento email?')">
                                    Odeslat znovu
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                            Žádné emaily nebyly nalezeny.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($emailLogs->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $emailLogs->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
