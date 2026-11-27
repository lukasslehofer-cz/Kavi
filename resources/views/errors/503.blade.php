@extends('errors.layout')

@php
    $host = request()->getHost();
    $locale = $currentLocale ?? (\App\Helpers\Site::forHost($host)?->locale ?? 'cs');
    $routeFor = fn (string $name) => route(\App\Services\EmailService::routeName($locale, $name));
@endphp

@section('title', __('errors.maintenance.title', [], $locale))

@section('content')
<div class="mx-auto max-w-screen-xl px-4 md:px-8 py-12 md:py-20">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 lg:gap-12">
        <!-- Left Column - Main Content -->
        <div class="lg:col-span-2">
            <!-- Error Code -->
            <div class="mb-8">
                <span class="text-[120px] sm:text-[180px] md:text-[220px] font-bold leading-none text-red-600">
                    503
                </span>
            </div>

            <!-- Heading -->
            <h1 class="text-4xl sm:text-5xl md:text-6xl font-bold uppercase tracking-tight mb-6 leading-[0.95]">
                {{ __('errors.maintenance.heading_1', [], $locale) }}<br>
                <span class="text-red-600">{{ __('errors.maintenance.heading_2', [], $locale) }}</span>
            </h1>

            <!-- Description -->
            <p class="text-lg md:text-xl text-[#1c1c1c]/70 mb-10 max-w-xl leading-relaxed">
                {{ __('errors.maintenance.description', [], $locale) }}
            </p>

            <!-- Estimated Time -->
            <div class="inline-flex items-center gap-4 border border-[#1c1c1c]/20 px-6 py-4 mb-10">
                <div>
                    <p class="text-xs uppercase tracking-widest text-[#1c1c1c]/50">
                        {{ __('errors.maintenance.estimated_return_label', [], $locale) }}
                    </p>
                    <p class="text-lg font-medium">
                        {{ __('errors.maintenance.estimated_return', [], $locale) }}
                    </p>
                </div>
            </div>

            <!-- CTA Button -->
            <div class="flex flex-wrap gap-4 mb-12">
                <button onclick="window.location.reload()" class="inline-flex items-center justify-center bg-[#1c1c1c] text-white text-xs uppercase tracking-widest px-8 py-4 hover:bg-[#1c1c1c]/80 transition-colors">
                    {{ __('errors.maintenance.check_availability', [], $locale) }}
                </button>
            </div>

            <!-- Newsletter Section -->
            <div class="pt-8 border-t border-[#1c1c1c]/20">
                <p class="text-xs uppercase tracking-widest text-[#1c1c1c]/50 mb-4">
                    {{ __('errors.maintenance.stay_updated', [], $locale) }}
                </p>
                <p class="text-sm text-[#1c1c1c]/70 mb-6 max-w-lg">
                    {{ __('errors.maintenance.newsletter_text', [], $locale) }}
                </p>

                <form id="newsletter-form-maintenance" class="flex flex-col sm:flex-row gap-3 max-w-md">
                    @csrf
                    <input
                        type="email"
                        name="email"
                        placeholder="{{ __('errors.maintenance.email_placeholder', [], $locale) }}"
                        required
                        class="flex-1 px-4 py-3 bg-transparent border-b border-[#1c1c1c] text-sm uppercase tracking-widest placeholder:text-[#1c1c1c]/40 focus:outline-none focus:border-red-600 transition-colors"
                    >
                    <button type="submit" class="px-6 py-3 bg-[#1c1c1c] text-white text-xs uppercase tracking-widest hover:bg-[#1c1c1c]/80 transition-colors whitespace-nowrap">
                        {{ __('errors.maintenance.subscribe', [], $locale) }}
                    </button>
                </form>
                <div id="newsletter-message-maintenance" class="mt-4 text-sm hidden"></div>
            </div>
        </div>

        <!-- Right Column - Sidebar -->
        <div class="lg:col-span-1">
            <div class="bg-[#BCBEB1] p-8 h-full">
                <h2 class="text-xs uppercase tracking-widest mb-6 border-b border-[#1c1c1c] pb-4">
                    {{ __('errors.maintenance.whats_happening', [], $locale) }}
                </h2>

                <div class="space-y-6">
                    <!-- Update 1 -->
                    <div class="flex items-start gap-3">
                        <span class="text-red-600 mt-1">●</span>
                        <div>
                            <p class="text-sm">
                                {{ __('errors.maintenance.update_performance', [], $locale) }}
                            </p>
                        </div>
                    </div>

                    <!-- Update 2 -->
                    <div class="flex items-start gap-3">
                        <span class="text-red-600 mt-1">●</span>
                        <div>
                            <p class="text-sm">
                                {{ __('errors.maintenance.update_features', [], $locale) }}
                            </p>
                        </div>
                    </div>

                    <!-- Update 3 -->
                    <div class="flex items-start gap-3">
                        <span class="text-red-600 mt-1">●</span>
                        <div>
                            <p class="text-sm">
                                {{ __('errors.maintenance.update_security', [], $locale) }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Contact -->
                <div class="mt-8 pt-6 border-t border-[#1c1c1c]/20">
                    <p class="text-xs uppercase tracking-widest text-[#1c1c1c]/50 mb-2">
                        {{ __('errors.maintenance.questions', [], $locale) }}
                    </p>
                    <a href="mailto:{{ \App\Helpers\Site::forLocale($locale)->contactEmail() }}" class="text-sm hover:text-red-600 transition-colors">
                        {{ \App\Helpers\Site::forLocale($locale)->contactEmail() }}
                    </a>
                </div>

                <!-- Error Info -->
                <div class="mt-6 pt-6 border-t border-[#1c1c1c]/20">
                    <p class="text-xs uppercase tracking-widest text-[#1c1c1c]/50">
                        {{ __('errors.maintenance.status', [], $locale) }}
                    </p>
                    <p class="text-sm mt-1">503 / {{ __('errors.maintenance.code_label', [], $locale) }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('newsletter-form-maintenance');
    const message = document.getElementById('newsletter-message-maintenance');

    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(form);
            const submitButton = form.querySelector('button[type="submit"]');
            const originalText = submitButton.textContent;

            submitButton.disabled = true;
            submitButton.textContent = @json(__('errors.maintenance.sending', [], $locale));

            fetch('{{ $routeFor('newsletter.subscribe') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    email: formData.get('email')
                })
            })
            .then(response => response.json())
            .then(data => {
                message.classList.remove('hidden');

                if (data.success) {
                    message.className = 'mt-4 text-sm text-green-700';
                    message.textContent = data.message;
                    form.reset();
                } else {
                    message.className = 'mt-4 text-sm text-red-600';
                    message.textContent = data.message;
                }

                setTimeout(() => {
                    message.classList.add('hidden');
                }, 5000);
            })
            .catch(error => {
                message.classList.remove('hidden');
                message.className = 'mt-4 text-sm text-red-600';
                message.textContent = @json(__('errors.maintenance.subscribe_error', [], $locale));
            })
            .finally(() => {
                submitButton.disabled = false;
                submitButton.textContent = originalText;
            });
        });
    }
});
</script>
@endsection
