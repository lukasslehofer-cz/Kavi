@extends('layouts.admin')

@section('title', 'Nastavení konfiguratoru')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Nastavení konfiguratoru předplatného</h1>
        <p class="text-gray-600 mt-1">Upravte parametry pro konfigurátor kávového předplatného</p>
    </div>

    <div class="bg-white rounded-xl p-8 shadow-sm border border-gray-200">
        <form action="{{ route('admin.subscription-config.update') }}" method="POST">
            @csrf
            
            <div class="space-y-8">
                @foreach($configs as $index => $config)
                <div class="border-b pb-6 last:border-b-0 border-gray-200">
                    <label class="block text-lg font-bold text-gray-900 mb-2">
                        {{ $config->label }}
                    </label>
                    
                    @if($config->description)
                    <p class="text-sm text-gray-600 mb-4">{{ $config->description }}</p>
                    @endif
                    
                    <div class="flex items-center space-x-4">
                        @if($config->type === 'boolean')
                        <label class="flex items-center cursor-pointer">
                            <input 
                                type="checkbox" 
                                name="configs[{{ $index }}][value]" 
                                value="1"
                                {{ $config->value ? 'checked' : '' }}
                                class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                            >
                            <span class="ml-3 text-gray-700 font-medium">Zapnuto</span>
                        </label>
                        @elseif($config->type === 'decimal')
                        <div class="relative flex-1 max-w-xs">
                            <input 
                                type="number" 
                                step="0.01"
                                name="configs[{{ $index }}][value]" 
                                value="{{ $config->value }}"
                                required
                                class="w-full px-4 py-2 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            >
                            <span class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-600 font-medium">
                                Kč
                            </span>
                        </div>
                        @elseif($config->type === 'integer')
                        <input 
                            type="number" 
                            name="configs[{{ $index }}][value]" 
                            value="{{ $config->value }}"
                            required
                            class="w-full max-w-xs px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        >
                        @else
                        <input 
                            type="text" 
                            name="configs[{{ $index }}][value]" 
                            value="{{ $config->value }}"
                            required
                            class="w-full max-w-md px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        >
                        @endif
                        
                        <input type="hidden" name="configs[{{ $index }}][key]" value="{{ $config->key }}">
                        
                        <div class="text-sm text-gray-500">
                            Typ: <code class="bg-gray-100 px-2 py-1 rounded text-xs font-mono">{{ $config->type }}</code>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            
            <div class="mt-8 flex items-center justify-between">
                <p class="flex items-center gap-2 text-sm text-gray-600">
                    <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                    Změny se projeví okamžitě na frontendovém konfiguratoru.
                </p>
                
                <button type="submit" class="px-4 py-2.5 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-800 transition-colors">
                    Uložit změny
                </button>
            </div>
        </form>
    </div>

    <!-- Info Panel -->
    <div class="mt-8 bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-6 border border-blue-200">
        <h3 class="flex items-center gap-2 font-bold text-lg text-gray-900 mb-4">
            <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
            </svg>
            Informace o nastavení
        </h3>
        <div class="space-y-3 text-sm text-gray-700">
            <p>
                <strong>Cena za balení:</strong> Základní cena, kterou zákazník zaplatí za jedno balení kávy. 
                Celková cena se vypočítá jako: počet balení × cena za balení.
            </p>
            <p>
                <strong>Minimální/maximální počet balení:</strong> Tyto hodnoty určují rozsah posuvníků v konfiguratoru.
            </p>
            <p class="flex items-center gap-2 text-blue-700 font-medium bg-white p-3 rounded-lg">
                <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                </svg>
                <strong>Tip:</strong> Po změně ceny doporučujeme otestovat konfigurátor na frontendové stránce.
            </p>
        </div>
    </div>
</div>
@endsection
