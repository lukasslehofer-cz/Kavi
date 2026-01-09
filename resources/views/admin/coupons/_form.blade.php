<div class="space-y-6">
    <!-- Základní info -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Základní informace</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="code" class="block text-sm font-medium text-gray-700 mb-1">Kód kupónu *</label>
                <input type="text" name="code" id="code" value="{{ old('code', $coupon->code ?? '') }}" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent uppercase"
                    placeholder="SLEVA2025" required>
                <p class="text-xs text-gray-500 mt-1">Bude automaticky převedeno na velká písmena</p>
                @error('code')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Název (interní)</label>
                <input type="text" name="name" id="name" value="{{ old('name', $coupon->name ?? '') }}" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    placeholder="Vánoční sleva 2025">
                @error('name')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="mt-4">
            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Popis</label>
            <textarea name="description" id="description" rows="2"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                placeholder="Popis kupónu pro interní použití">{{ old('description', $coupon->description ?? '') }}</textarea>
            @error('description')
            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <!-- Sleva pro jednorázové objednávky -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">📦 Sleva pro jednorázové objednávky</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label for="discount_type_order" class="block text-sm font-medium text-gray-700 mb-1">Typ slevy</label>
                <select name="discount_type_order" id="discount_type_order" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    onchange="toggleOrderEurField()">
                    <option value="none" {{ old('discount_type_order', $coupon->discount_type_order ?? 'none') == 'none' ? 'selected' : '' }}>Žádná</option>
                    <option value="percentage" {{ old('discount_type_order', $coupon->discount_type_order ?? '') == 'percentage' ? 'selected' : '' }}>Procenta (%)</option>
                    <option value="fixed" {{ old('discount_type_order', $coupon->discount_type_order ?? '') == 'fixed' ? 'selected' : '' }}>Pevná částka</option>
                </select>
            </div>

            <div>
                <label for="discount_value_order" class="block text-sm font-medium text-gray-700 mb-1">Hodnota slevy (Kč)</label>
                <input type="number" name="discount_value_order" id="discount_value_order" 
                    value="{{ old('discount_value_order', $coupon->discount_value_order ?? '') }}" 
                    step="0.01" min="0"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    placeholder="10">
                @error('discount_value_order')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div id="order_eur_field" style="display: none;">
                <label for="discount_value_order_eur" class="block text-sm font-medium text-gray-700 mb-1">Hodnota slevy (EUR)</label>
                <input type="number" name="discount_value_order_eur" id="discount_value_order_eur" 
                    value="{{ old('discount_value_order_eur', $coupon->discount_value_order_eur ?? '') }}" 
                    step="0.01" min="0"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    placeholder="1">
                <p class="text-xs text-gray-500 mt-1">Pro kavibox.com</p>
                @error('discount_value_order_eur')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center pt-7">
                <label class="flex items-center cursor-pointer">
                    <input type="checkbox" name="free_shipping" id="free_shipping" value="1"
                        {{ old('free_shipping', $coupon->free_shipping ?? false) ? 'checked' : '' }}
                        class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-2 focus:ring-blue-500">
                    <span class="ml-2 text-sm font-medium text-gray-700">Doprava zdarma</span>
                </label>
            </div>
        </div>
    </div>

    <!-- Sleva pro předplatné -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">🔄 Sleva pro předplatné</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label for="discount_type_subscription" class="block text-sm font-medium text-gray-700 mb-1">Typ slevy</label>
                <select name="discount_type_subscription" id="discount_type_subscription" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    onchange="toggleSubscriptionEurField()">
                    <option value="none" {{ old('discount_type_subscription', $coupon->discount_type_subscription ?? 'none') == 'none' ? 'selected' : '' }}>Žádná</option>
                    <option value="percentage" {{ old('discount_type_subscription', $coupon->discount_type_subscription ?? '') == 'percentage' ? 'selected' : '' }}>Procenta (%)</option>
                    <option value="fixed" {{ old('discount_type_subscription', $coupon->discount_type_subscription ?? '') == 'fixed' ? 'selected' : '' }}>Pevná částka</option>
                </select>
            </div>

            <div>
                <label for="discount_value_subscription" class="block text-sm font-medium text-gray-700 mb-1">Hodnota slevy (Kč)</label>
                <input type="number" name="discount_value_subscription" id="discount_value_subscription" 
                    value="{{ old('discount_value_subscription', $coupon->discount_value_subscription ?? '') }}" 
                    step="0.01" min="0"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    placeholder="100">
                @error('discount_value_subscription')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div id="subscription_eur_field" style="display: none;">
                <label for="discount_value_subscription_eur" class="block text-sm font-medium text-gray-700 mb-1">Hodnota slevy (EUR)</label>
                <input type="number" name="discount_value_subscription_eur" id="discount_value_subscription_eur" 
                    value="{{ old('discount_value_subscription_eur', $coupon->discount_value_subscription_eur ?? '') }}" 
                    step="0.01" min="0"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    placeholder="5">
                <p class="text-xs text-gray-500 mt-1">Pro kavibox.com</p>
                @error('discount_value_subscription_eur')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="subscription_discount_months" class="block text-sm font-medium text-gray-700 mb-1">Počet měsíců</label>
                <input type="number" name="subscription_discount_months" id="subscription_discount_months" 
                    value="{{ old('subscription_discount_months', $coupon->subscription_discount_months ?? '') }}" 
                    min="1"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    placeholder="3">
                <p class="text-xs text-gray-500 mt-1">Nechte prázdné pro neomezenou slevu</p>
                @error('subscription_discount_months')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

    <!-- Affiliate nastavení -->
    <div class="bg-white rounded-lg shadow-sm border border-purple-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">💼 {{ __('affiliate.affiliate_settings') }}</h3>
        
        <div class="mb-4">
            <label class="flex items-center cursor-pointer">
                <input type="checkbox" name="affiliate_code_enabled" id="affiliate_code_enabled" value="1"
                    {{ old('affiliate_code_enabled', $coupon->affiliate_code_enabled ?? false) ? 'checked' : '' }}
                    class="w-4 h-4 text-purple-600 border-gray-300 rounded focus:ring-2 focus:ring-purple-500"
                    onchange="toggleAffiliateFields()">
                <span class="ml-2 text-sm font-medium text-gray-700">{{ __('affiliate.enable_affiliate') }}</span>
            </label>
        </div>

        <div id="affiliate_fields" style="display: none;">
            <!-- Select Partner -->
            <div class="mb-4">
                <label for="affiliate_partner_id" class="block text-sm font-medium text-gray-700 mb-1">{{ __('affiliate.select_partner') }} *</label>
                <select name="affiliate_partner_id" id="affiliate_partner_id" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    <option value="">-- Vyberte partnera --</option>
                    @foreach(\App\Models\User::where('is_affiliate_partner', true)->orderBy('name')->get() as $partner)
                    <option value="{{ $partner->id }}" {{ old('affiliate_partner_id', $coupon->affiliate_partner_id ?? '') == $partner->id ? 'selected' : '' }}>
                        {{ $partner->name }} ({{ $partner->email }})
                    </option>
                    @endforeach
                </select>
                @error('affiliate_partner_id')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Order Reward -->
            <div class="border-t border-gray-200 pt-4 mb-4">
                <h4 class="text-sm font-semibold text-gray-900 mb-3">{{ __('affiliate.order_reward') }}</h4>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label for="affiliate_reward_order_type" class="block text-sm font-medium text-gray-700 mb-1">{{ __('affiliate.reward_type') }}</label>
                        <select name="affiliate_reward_order_type" id="affiliate_reward_order_type" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                            onchange="toggleAffiliateOrderEurField()">
                            <option value="none" {{ old('affiliate_reward_order_type', $coupon->affiliate_reward_order_type ?? 'none') == 'none' ? 'selected' : '' }}>{{ __('affiliate.none') }}</option>
                            <option value="percentage" {{ old('affiliate_reward_order_type', $coupon->affiliate_reward_order_type ?? '') == 'percentage' ? 'selected' : '' }}>{{ __('affiliate.percentage') }}</option>
                            <option value="fixed" {{ old('affiliate_reward_order_type', $coupon->affiliate_reward_order_type ?? '') == 'fixed' ? 'selected' : '' }}>{{ __('affiliate.fixed_amount') }}</option>
                        </select>
                    </div>
                    <div>
                        <label for="affiliate_reward_order_value" class="block text-sm font-medium text-gray-700 mb-1">{{ __('affiliate.reward_value_czk') }}</label>
                        <input type="number" name="affiliate_reward_order_value" id="affiliate_reward_order_value" 
                            value="{{ old('affiliate_reward_order_value', $coupon->affiliate_reward_order_value ?? '') }}" 
                            step="0.01" min="0"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                            placeholder="50">
                    </div>
                    <div id="affiliate_order_eur_field" style="display: none;">
                        <label for="affiliate_reward_order_value_eur" class="block text-sm font-medium text-gray-700 mb-1">{{ __('affiliate.reward_value_eur') }}</label>
                        <input type="number" name="affiliate_reward_order_value_eur" id="affiliate_reward_order_value_eur" 
                            value="{{ old('affiliate_reward_order_value_eur', $coupon->affiliate_reward_order_value_eur ?? '') }}" 
                            step="0.01" min="0"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                            placeholder="2">
                    </div>
                    <div>
                        <label for="affiliate_reward_order_min_value" class="block text-sm font-medium text-gray-700 mb-1">{{ __('affiliate.min_order_value') }} (Kč)</label>
                        <input type="number" name="affiliate_reward_order_min_value" id="affiliate_reward_order_min_value" 
                            value="{{ old('affiliate_reward_order_min_value', $coupon->affiliate_reward_order_min_value ?? '') }}" 
                            step="0.01" min="0"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                            placeholder="500">
                        <p class="text-xs text-gray-500 mt-1">Pro odměnu</p>
                    </div>
                </div>
            </div>

            <!-- Subscription Reward -->
            <div class="border-t border-gray-200 pt-4">
                <h4 class="text-sm font-semibold text-gray-900 mb-3">{{ __('affiliate.subscription_reward') }}</h4>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="affiliate_reward_subscription_value" class="block text-sm font-medium text-gray-700 mb-1">{{ __('affiliate.reward_value_czk') }}</label>
                        <input type="number" name="affiliate_reward_subscription_value" id="affiliate_reward_subscription_value" 
                            value="{{ old('affiliate_reward_subscription_value', $coupon->affiliate_reward_subscription_value ?? '') }}" 
                            step="0.01" min="0"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                            placeholder="200">
                        <p class="text-xs text-gray-500 mt-1">Pevná částka za každou platbu</p>
                    </div>
                    <div>
                        <label for="affiliate_reward_subscription_value_eur" class="block text-sm font-medium text-gray-700 mb-1">{{ __('affiliate.reward_value_eur') }}</label>
                        <input type="number" name="affiliate_reward_subscription_value_eur" id="affiliate_reward_subscription_value_eur" 
                            value="{{ old('affiliate_reward_subscription_value_eur', $coupon->affiliate_reward_subscription_value_eur ?? '') }}" 
                            step="0.01" min="0"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                            placeholder="8">
                    </div>
                    <div>
                        <label for="affiliate_reward_subscription_months" class="block text-sm font-medium text-gray-700 mb-1">{{ __('affiliate.reward_months') }}</label>
                        <input type="number" name="affiliate_reward_subscription_months" id="affiliate_reward_subscription_months" 
                            value="{{ old('affiliate_reward_subscription_months', $coupon->affiliate_reward_subscription_months ?? '') }}" 
                            min="1"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                            placeholder="3">
                        <p class="text-xs text-gray-500 mt-1">{{ __('affiliate.reward_months_help') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Platnost a limity -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">⏰ Platnost a limity</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <label for="valid_from" class="block text-sm font-medium text-gray-700 mb-1">Platnost od</label>
                <input type="datetime-local" name="valid_from" id="valid_from" 
                    value="{{ old('valid_from', $coupon->valid_from ? $coupon->valid_from->format('Y-m-d\TH:i') : '') }}" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                @error('valid_from')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="valid_until" class="block text-sm font-medium text-gray-700 mb-1">Platnost do</label>
                <input type="datetime-local" name="valid_until" id="valid_until" 
                    value="{{ old('valid_until', $coupon->valid_until ? $coupon->valid_until->format('Y-m-d\TH:i') : '') }}" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                @error('valid_until')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label for="min_order_value" class="block text-sm font-medium text-gray-700 mb-1">Min. hodnota objednávky (Kč)</label>
                <input type="number" name="min_order_value" id="min_order_value" 
                    value="{{ old('min_order_value', $coupon->min_order_value ?? '') }}" 
                    step="0.01" min="0"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    placeholder="500">
                <p class="text-xs text-gray-500 mt-1">Nechte prázdné pro bez omezení</p>
                @error('min_order_value')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="min_order_value_eur" class="block text-sm font-medium text-gray-700 mb-1">Min. hodnota objednávky (EUR)</label>
                <input type="number" name="min_order_value_eur" id="min_order_value_eur" 
                    value="{{ old('min_order_value_eur', $coupon->min_order_value_eur ?? '') }}" 
                    step="0.01" min="0"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    placeholder="20">
                <p class="text-xs text-gray-500 mt-1">Pro kavibox.com</p>
                @error('min_order_value_eur')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="usage_limit_total" class="block text-sm font-medium text-gray-700 mb-1">Max. celkové použití</label>
                <input type="number" name="usage_limit_total" id="usage_limit_total" 
                    value="{{ old('usage_limit_total', $coupon->usage_limit_total ?? '') }}" 
                    min="1"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    placeholder="100">
                <p class="text-xs text-gray-500 mt-1">Nechte prázdné pro neomezené</p>
                @error('usage_limit_total')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="usage_limit_per_user" class="block text-sm font-medium text-gray-700 mb-1">Max. na uživatele</label>
                <input type="number" name="usage_limit_per_user" id="usage_limit_per_user" 
                    value="{{ old('usage_limit_per_user', $coupon->usage_limit_per_user ?? '') }}" 
                    min="1"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    placeholder="1">
                <p class="text-xs text-gray-500 mt-1">Nechte prázdné pro neomezené</p>
                @error('usage_limit_per_user')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

    <!-- Status -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <label class="flex items-center cursor-pointer">
            <input type="checkbox" name="is_active" id="is_active" value="1"
                {{ old('is_active', $coupon->is_active ?? true) ? 'checked' : '' }}
                class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-2 focus:ring-blue-500">
            <span class="ml-3">
                <span class="text-sm font-medium text-gray-900">Kupón je aktivní</span>
                <span class="block text-xs text-gray-500">Kupón může být použit zákazníky</span>
            </span>
        </label>
    </div>

    <!-- Submit Buttons -->
    <div class="flex items-center justify-end gap-3 pt-4">
        <a href="{{ route('admin.coupons.index') }}" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
            Zrušit
        </a>
        <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
            {{ isset($coupon) && $coupon->exists ? 'Aktualizovat kupón' : 'Vytvořit kupón' }}
        </button>
    </div>
</div>

<script>
function toggleOrderEurField() {
    const typeSelect = document.getElementById('discount_type_order');
    const eurField = document.getElementById('order_eur_field');
    if (typeSelect.value === 'fixed') {
        eurField.style.display = 'block';
    } else {
        eurField.style.display = 'none';
    }
}

function toggleSubscriptionEurField() {
    const typeSelect = document.getElementById('discount_type_subscription');
    const eurField = document.getElementById('subscription_eur_field');
    if (typeSelect.value === 'fixed') {
        eurField.style.display = 'block';
    } else {
        eurField.style.display = 'none';
    }
}

function toggleAffiliateFields() {
    const checkbox = document.getElementById('affiliate_code_enabled');
    const fields = document.getElementById('affiliate_fields');
    if (checkbox.checked) {
        fields.style.display = 'block';
    } else {
        fields.style.display = 'none';
    }
}

function toggleAffiliateOrderEurField() {
    const typeSelect = document.getElementById('affiliate_reward_order_type');
    const eurField = document.getElementById('affiliate_order_eur_field');
    if (typeSelect && typeSelect.value === 'fixed') {
        eurField.style.display = 'block';
    } else if (eurField) {
        eurField.style.display = 'none';
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    toggleOrderEurField();
    toggleSubscriptionEurField();
    toggleAffiliateFields();
    toggleAffiliateOrderEurField();
});
</script>

