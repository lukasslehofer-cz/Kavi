@extends('layouts.admin')

@section('content')
<div class="p-6">
    <div class="max-w-5xl mx-auto">

        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">{{ __('affiliate.mail_title') }}</h1>
            <p class="text-sm text-gray-500 mt-1">{{ __('affiliate.mail_subtitle') }}</p>
        </div>

        @if($partners->isEmpty())
        <div class="bg-white rounded-lg shadow p-12 text-center">
            <p class="text-gray-500">{{ __('affiliate.no_partners') }}</p>
        </div>
        @else
        <form method="POST" id="affiliate-mail-form" action="{{ route('admin.affiliate.mail.send') }}">
            @csrf

            <!-- Příjemci -->
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-bold text-gray-900">{{ __('affiliate.mail_recipients') }}</h2>
                    <label class="inline-flex items-center text-sm text-gray-600">
                        <input type="checkbox" id="select-all" class="rounded border-gray-300 text-purple-600 focus:ring-purple-500 mr-2">
                        {{ __('affiliate.mail_select_all') }}
                    </label>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-2">
                    @foreach($partners as $partner)
                    <label class="flex items-start gap-2 p-3 border border-gray-200 rounded-md hover:bg-gray-50 cursor-pointer">
                        <input type="checkbox" name="partners[]" value="{{ $partner->id }}"
                               @checked(in_array($partner->id, old('partners', [])))
                               class="partner-checkbox mt-1 rounded border-gray-300 text-purple-600 focus:ring-purple-500">
                        <span>
                            <span class="block text-sm font-medium text-gray-900">{{ $partner->name }}</span>
                            <span class="block text-xs text-gray-500">{{ $partner->email }}</span>
                        </span>
                    </label>
                    @endforeach
                </div>

                @error('partners')
                    <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                @enderror
            </div>

            <!-- Zpráva -->
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <div class="mb-4">
                    <label for="subject" class="block text-sm font-medium text-gray-700 mb-1">{{ __('affiliate.mail_subject') }}</label>
                    <input type="text" name="subject" id="subject" required maxlength="200"
                           value="{{ old('subject') }}"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                    @error('subject')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="body" class="block text-sm font-medium text-gray-700 mb-1">{{ __('affiliate.mail_body') }}</label>
                    <textarea name="body" id="body" rows="12" required maxlength="20000"
                              class="w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">{{ old('body') }}</textarea>
                    <p class="text-xs text-gray-500 mt-1">{{ __('affiliate.mail_body_help') }}</p>
                    @error('body')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Akce -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex flex-wrap gap-3">
                    <button type="button" onclick="openPreview()"
                            class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50">
                        {{ __('affiliate.mail_preview') }}
                    </button>
                    <button type="submit" formaction="{{ route('admin.affiliate.mail.send-test') }}"
                            class="px-4 py-2 bg-gray-700 text-white rounded-md hover:bg-gray-800">
                        {{ __('affiliate.mail_send_test') }}
                    </button>
                    <button type="submit" id="send-button"
                            onclick="return confirm('{{ __('affiliate.mail_send_confirm') }}')"
                            class="px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700">
                        {{ __('affiliate.mail_send') }}
                    </button>
                </div>
                <p class="text-xs text-gray-500 mt-3">{{ __('affiliate.mail_preview_hint') }}</p>
            </div>
        </form>

        <!-- Náhled -->
        <div id="preview-wrapper" class="bg-white rounded-lg shadow p-6 mt-6 hidden">
            <h2 class="text-lg font-bold text-gray-900 mb-4">{{ __('affiliate.mail_preview') }}</h2>
            <iframe id="preview-frame" name="preview-frame" class="w-full border border-gray-200 rounded" style="height: 700px;"></iframe>
        </div>

        <form method="POST" action="{{ route('admin.affiliate.mail.preview') }}" target="preview-frame" id="preview-form" class="hidden">
            @csrf
            <input type="hidden" name="subject" id="preview-subject">
            <input type="hidden" name="body" id="preview-body">
            <div id="preview-partners"></div>
        </form>
        @endif
    </div>
</div>

@if($partners->isNotEmpty())
<script>
    document.getElementById('select-all')?.addEventListener('change', function () {
        document.querySelectorAll('.partner-checkbox').forEach(cb => { cb.checked = this.checked; });
    });

    function openPreview() {
        const selected = Array.from(document.querySelectorAll('.partner-checkbox:checked')).map(cb => cb.value);

        if (selected.length === 0) {
            alert(@json(__('affiliate.mail_no_recipients')));
            return;
        }

        // Náhled se renderuje pro prvního vybraného partnera
        const container = document.getElementById('preview-partners');
        container.innerHTML = '';
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'partners[]';
        input.value = selected[0];
        container.appendChild(input);

        document.getElementById('preview-subject').value = document.getElementById('subject').value;
        document.getElementById('preview-body').value = document.getElementById('body').value;

        const wrapper = document.getElementById('preview-wrapper');
        wrapper.classList.remove('hidden');
        document.getElementById('preview-form').submit();
        wrapper.scrollIntoView({ behavior: 'smooth' });
    }
</script>
@endif
@endsection
