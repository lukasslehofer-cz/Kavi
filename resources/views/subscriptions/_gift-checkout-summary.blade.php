{{-- Souhrn pokladny při uplatnění dárkového předplatného (subscriptions/checkout.blade.php) --}}
<h3 class="font-display text-2xl sm:text-3xl font-normal text-dark-800 uppercase tracking-tight mb-8">{{ __('gifts.checkout.summary_title') }}</h3>

<div class="mb-8 space-y-4">
    @foreach([
        __('gifts.redeem.summary_box') => $giftRedemption['box'],
        __('gifts.redeem.summary_type') => $giftRedemption['type'],
        __('gifts.redeem.summary_count') => $giftRedemption['count'],
        __('gifts.redeem.summary_first') => $giftRedemption['first'],
        __('gifts.redeem.summary_last') => $giftRedemption['last'],
    ] as $label => $value)
    <div class="flex justify-between items-baseline gap-4">
        <span class="text-xs uppercase tracking-widest text-olive-600">{{ $label }}</span>
        <span class="text-sm text-dark-800 uppercase tracking-wide text-right">{{ $value }}</span>
    </div>
    @endforeach
</div>

<div class="flex justify-between items-baseline pt-6 mt-4 border-t border-dark-800">
    <span class="font-display text-2xl sm:text-3xl font-normal text-dark-800 uppercase tracking-tight">{{ __('gifts.checkout.free') }}</span>
</div>
<p class="mt-2 text-xs uppercase tracking-widest text-olive-600">{{ __('gifts.checkout.free_note') }}</p>

<div class="mt-8 space-y-4">
    <button type="submit" form="subscription-checkout-form" class="group w-full flex items-center justify-center gap-3 bg-dark-800 hover:bg-dark-900 text-white font-display uppercase tracking-widest px-6 py-4 transition-all duration-200">
        <span>{{ __('gifts.checkout.cta') }}</span>
        <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
        </svg>
    </button>

    <div class="flex items-start py-4">
        <input type="checkbox" id="terms" required form="subscription-checkout-form" class="w-4 h-4 text-dark-800 border-dark-800 focus:ring-olive-500 mr-3 mt-0.5 flex-shrink-0">
        <label for="terms" class="text-xs uppercase tracking-widest text-olive-600">
            {{ __('checkout.terms.agree') }} <a href="{{ localizedRoute('terms-of-service') }}" target="_blank" class="text-dark-800 hover:text-primary-500 border-b border-dark-800 hover:border-primary-500">{{ __('checkout.terms.terms_of_service') }}</a>
            {{ __('checkout.terms.and') }} <a href="{{ localizedRoute('privacy-policy') }}" target="_blank" class="text-dark-800 hover:text-primary-500 border-b border-dark-800 hover:border-primary-500">{{ __('checkout.terms.privacy_policy') }}</a>
        </label>
    </div>

    <div class="text-center">
        <a href="{{ localizedRoute('gifts.redeem') }}" class="inline-block text-xs uppercase tracking-widest text-olive-600 hover:text-dark-800 border-b border-olive-400 hover:border-dark-800 pb-0.5 transition-colors">&larr; {{ __('gifts.redeem.hero_title')[0] }} {{ __('gifts.redeem.hero_title')[1] }}</a>
    </div>
</div>
