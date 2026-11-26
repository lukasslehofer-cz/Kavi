{{-- Ručně vybrané související články (pod článkem na webu). Bez výběru se použijí články se stejnými štítky. --}}
@php $relatedSelected = array_values(old('related', $selectedRelated)); @endphp
<div>
    <label class="block text-sm font-medium text-coffee-900 mb-2">Související články</label>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
        @for($i = 0; $i < 3; $i++)
        <select name="related[]" class="input @error('related.' . $i) border-red-500 @enderror" aria-label="Související článek {{ $i + 1 }}">
            <option value="">{{ $i + 1 }}. – automaticky –</option>
            @foreach($relatedOptions as $option)
            <option value="{{ $option->id }}" {{ (int) ($relatedSelected[$i] ?? 0) === $option->id ? 'selected' : '' }}>
                {{ $option->title_cs }}{{ $option->status !== 'published' ? ' (koncept)' : '' }}
            </option>
            @endforeach
        </select>
        @endfor
    </div>
    <p class="text-xs text-gray-500 mt-2">Pořadí 1–3 odpovídá pořadí pod článkem. Nevyplněná místa doplní články se stejnými štítky, pak nejnovější.</p>
    @error('related')
    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
    @enderror
    @error('related.*')
    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
    @enderror
</div>
