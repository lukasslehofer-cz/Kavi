@extends('layouts.admin')

@section('title', 'Detail emailu')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Detail emailu</h1>
            <p class="text-gray-600 mt-1">{{ $emailLog->sent_at->format('d.m.Y H:i:s') }}</p>
        </div>
        <div class="flex gap-2">
            <form action="{{ route('admin.email-logs.resend', $emailLog) }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors" onclick="return confirm('Opravdu chcete znovu odeslat tento email?')">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    Odeslat znovu
                </button>
            </form>
            <a href="{{ route('admin.email-logs.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">
                ← Zpět na seznam
            </a>
        </div>
    </div>

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

    <!-- Status Badge -->
    <div class="mb-6">
        @if($emailLog->status === 'sent')
        <span class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium bg-green-100 text-green-800">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>
            Email byl úspěšně předán SMTP serveru
        </span>
        @else
        <span class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium bg-red-100 text-red-800">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
            </svg>
            Odeslání emailu selhalo
        </span>
        @endif
    </div>

    <!-- Error Message -->
    @if($emailLog->status === 'failed' && $emailLog->error_message)
    <div class="bg-red-50 border-2 border-red-200 rounded-xl p-6 mb-6">
        <div class="flex items-start gap-4">
            <div class="flex-shrink-0">
                <svg class="w-8 h-8 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="flex-1">
                <h3 class="text-xl font-bold text-red-900 mb-2">Chybová zpráva</h3>
                <p class="text-sm text-red-800">{{ $emailLog->error_message }}</p>
            </div>
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Email Details -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Informace o emailu</h2>
            
            <div class="space-y-4">
                <div>
                    <p class="text-sm font-medium text-gray-500">Příjemce</p>
                    <p class="text-base text-gray-900 mt-1">{{ $emailLog->recipient }}</p>
                </div>

                <div>
                    <p class="text-sm font-medium text-gray-500">Odesílatel</p>
                    <p class="text-base text-gray-900 mt-1">{{ $emailLog->sender }}</p>
                </div>

                <div>
                    <p class="text-sm font-medium text-gray-500">Předmět</p>
                    <p class="text-base text-gray-900 mt-1">{{ $emailLog->subject }}</p>
                </div>

                <div>
                    <p class="text-sm font-medium text-gray-500">Typ emailu</p>
                    <p class="text-base text-gray-900 mt-1">
                        {{ $emailLog->mailable_name }}
                        <span class="text-xs text-gray-500 block mt-1">{{ $emailLog->mailable_class }}</span>
                    </p>
                </div>

                <div>
                    <p class="text-sm font-medium text-gray-500">Region</p>
                    <p class="text-base text-gray-900 mt-1">
                        @if($emailLog->region === 'cs')
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                            🇨🇿 Kavi.cz
                        </span>
                        @elseif($emailLog->region === 'en')
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-purple-100 text-purple-800">
                            🌍 Kavibox.com
                        </span>
                        @else
                        <span class="text-gray-400">-</span>
                        @endif
                    </p>
                </div>

                <div>
                    <p class="text-sm font-medium text-gray-500">Datum odeslání</p>
                    <p class="text-base text-gray-900 mt-1">{{ $emailLog->sent_at->format('d.m.Y H:i:s') }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ $emailLog->sent_at->diffForHumans() }}</p>
                </div>
            </div>
        </div>

        <!-- Related Records -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Související záznamy</h2>
            
            <div class="space-y-4">
                @if($emailLog->user)
                <div class="border-b border-gray-200 pb-4">
                    <p class="text-sm font-medium text-gray-500 mb-2">Uživatel</p>
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-base font-medium text-gray-900">{{ $emailLog->user->name }}</p>
                            <p class="text-sm text-gray-600">{{ $emailLog->user->email }}</p>
                        </div>
                        <a href="#" class="text-blue-600 hover:text-blue-900 text-sm font-medium">
                            Zobrazit profil →
                        </a>
                    </div>
                </div>
                @endif

                @if($emailLog->order)
                <div class="border-b border-gray-200 pb-4">
                    <p class="text-sm font-medium text-gray-500 mb-2">Objednávka</p>
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-base font-medium text-gray-900">{{ $emailLog->order->order_number }}</p>
                            <p class="text-sm text-gray-600">
                                Celkem: {!! \App\Helpers\CurrencyHelper::formatByCurrency($emailLog->order->total, $emailLog->order->currency) !!}
                            </p>
                        </div>
                        <a href="{{ route('admin.orders.show', $emailLog->order) }}" class="text-blue-600 hover:text-blue-900 text-sm font-medium">
                            Zobrazit objednávku →
                        </a>
                    </div>
                </div>
                @endif

                @if($emailLog->subscription)
                <div class="border-b border-gray-200 pb-4">
                    <p class="text-sm font-medium text-gray-500 mb-2">Předplatné</p>
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-base font-medium text-gray-900">{{ $emailLog->subscription->subscription_number ?? '#' . $emailLog->subscription->id }}</p>
                            <p class="text-sm text-gray-600">Status: {{ $emailLog->subscription->status }}</p>
                        </div>
                        <a href="{{ route('admin.subscriptions.show', $emailLog->subscription) }}" class="text-blue-600 hover:text-blue-900 text-sm font-medium">
                            Zobrazit předplatné →
                        </a>
                    </div>
                </div>
                @endif

                @if(!$emailLog->user && !$emailLog->order && !$emailLog->subscription)
                <div class="text-center py-8 text-gray-500">
                    <svg class="w-12 h-12 mx-auto mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                    </svg>
                    <p class="text-sm">Tento email není spojen s žádným záznamem</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Email Body Preview -->
    @if($emailLog->body_html)
    <div class="mt-6 bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-4 border-b border-gray-200 flex items-center justify-between">
            <h2 class="text-xl font-bold text-gray-900">Obsah emailu</h2>
            <button 
                onclick="toggleEmailPreview()" 
                id="togglePreviewBtn"
                class="inline-flex items-center gap-2 px-3 py-1.5 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
                <span id="togglePreviewText">Zobrazit náhled</span>
            </button>
        </div>
        
        <div id="emailPreviewContainer" class="hidden">
            <iframe 
                id="emailPreviewFrame"
                class="w-full border-0"
                style="min-height: 600px; background: white;"
                sandbox="allow-same-origin"
            ></iframe>
        </div>
        
        <div id="emailPreviewPlaceholder" class="p-8 text-center text-gray-500">
            <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
            <p class="text-sm">Klikněte na "Zobrazit náhled" pro zobrazení obsahu emailu</p>
            <p class="text-xs text-gray-400 mt-2">Velikost: {{ number_format(strlen($emailLog->body_html) / 1024, 1) }} KB</p>
        </div>
    </div>
    
    <script>
        function toggleEmailPreview() {
            const container = document.getElementById('emailPreviewContainer');
            const placeholder = document.getElementById('emailPreviewPlaceholder');
            const btn = document.getElementById('togglePreviewBtn');
            const text = document.getElementById('togglePreviewText');
            const frame = document.getElementById('emailPreviewFrame');
            
            if (container.classList.contains('hidden')) {
                // Show preview
                container.classList.remove('hidden');
                placeholder.classList.add('hidden');
                text.textContent = 'Skrýt náhled';
                
                // Load content into iframe
                const content = @json($emailLog->body_html);
                frame.srcdoc = content;
                
                // Auto-resize iframe based on content
                frame.onload = function() {
                    try {
                        const height = frame.contentWindow.document.body.scrollHeight;
                        frame.style.height = Math.min(Math.max(height + 50, 400), 1200) + 'px';
                    } catch(e) {
                        frame.style.height = '600px';
                    }
                };
            } else {
                // Hide preview
                container.classList.add('hidden');
                placeholder.classList.remove('hidden');
                text.textContent = 'Zobrazit náhled';
            }
        }
    </script>
    @else
    <div class="mt-6 bg-gray-50 rounded-xl border border-gray-200 p-8 text-center">
        <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
        <p class="text-gray-500">Obsah emailu není k dispozici</p>
        <p class="text-xs text-gray-400 mt-1">Emaily odeslané před implementací logování nemají uložený obsah</p>
    </div>
    @endif

    <!-- Technical Details -->
    <div class="mt-6 bg-gray-50 rounded-xl border border-gray-200 p-6">
        <h2 class="text-lg font-bold text-gray-900 mb-4">Technické údaje</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
            <div>
                <p class="text-gray-500 font-medium">ID záznamu</p>
                <p class="text-gray-900 mt-1">{{ $emailLog->id }}</p>
            </div>
            <div>
                <p class="text-gray-500 font-medium">Vytvořeno</p>
                <p class="text-gray-900 mt-1">{{ $emailLog->created_at->format('d.m.Y H:i') }}</p>
            </div>
            <div>
                <p class="text-gray-500 font-medium">Aktualizováno</p>
                <p class="text-gray-900 mt-1">{{ $emailLog->updated_at->format('d.m.Y H:i') }}</p>
            </div>
            <div>
                <p class="text-gray-500 font-medium">Status</p>
                <p class="text-gray-900 mt-1">{{ $emailLog->status }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
