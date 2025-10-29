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
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label for="discount_type_order" class="block text-sm font-medium text-gray-700 mb-1">Typ slevy</label>
                <select name="discount_type_order" id="discount_type_order" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="none" {{ old('discount_type_order', $coupon->discount_type_order ?? 'none') == 'none' ? 'selected' : '' }}>Žádná</option>
                    <option value="percentage" {{ old('discount_type_order', $coupon->discount_type_order ?? '') == 'percentage' ? 'selected' : '' }}>Procenta (%)</option>
                    <option value="fixed" {{ old('discount_type_order', $coupon->discount_type_order ?? '') == 'fixed' ? 'selected' : '' }}>Pevná částka (Kč)</option>
                </select>
            </div>

            <div>
                <label for="discount_value_order" class="block text-sm font-medium text-gray-700 mb-1">Hodnota slevy</label>
                <input type="number" name="discount_value_order" id="discount_value_order" 
                    value="{{ old('discount_value_order', $coupon->discount_value_order ?? '') }}" 
                    step="0.01" min="0"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    placeholder="10">
                @error('discount_value_order')
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
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label for="discount_type_subscription" class="block text-sm font-medium text-gray-700 mb-1">Typ slevy</label>
                <select name="discount_type_subscription" id="discount_type_subscription" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="none" {{ old('discount_type_subscription', $coupon->discount_type_subscription ?? 'none') == 'none' ? 'selected' : '' }}>Žádná</option>
                    <option value="percentage" {{ old('discount_type_subscription', $coupon->discount_type_subscription ?? '') == 'percentage' ? 'selected' : '' }}>Procenta (%)</option>
                    <option value="fixed" {{ old('discount_type_subscription', $coupon->discount_type_subscription ?? '') == 'fixed' ? 'selected' : '' }}>Pevná částka (Kč)</option>
                </select>
            </div>

            <div>
                <label for="discount_value_subscription" class="block text-sm font-medium text-gray-700 mb-1">Hodnota slevy</label>
                <input type="number" name="discount_value_subscription" id="discount_value_subscription" 
                    value="{{ old('discount_value_subscription', $coupon->discount_value_subscription ?? '') }}" 
                    step="0.01" min="0"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    placeholder="100">
                @error('discount_value_subscription')
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

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
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

