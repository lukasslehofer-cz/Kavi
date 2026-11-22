@php
    $corporateNew = \App\Models\CorporateInquiry::where('status', \App\Models\CorporateInquiry::STATUS_NEW)->count();
    $tabs = [
        ['route' => 'admin.gifts.index', 'active' => request()->routeIs('admin.gifts.index', 'admin.gifts.show'), 'label' => 'Dárkové předplatné a poukazy', 'count' => 0],
        ['route' => 'admin.gifts.corporate.index', 'active' => request()->routeIs('admin.gifts.corporate.*'), 'label' => 'Firemní dárky', 'count' => $corporateNew],
    ];
@endphp
<nav class="flex gap-4 border-b border-gray-200 mb-6" aria-label="Dárky">
    @foreach($tabs as $tab)
    <a href="{{ route($tab['route']) }}" @if($tab['active']) aria-current="page" @endif
       class="inline-flex items-center gap-2 px-1 pb-3 -mb-px text-sm font-medium border-b-2 {{ $tab['active'] ? 'border-gray-900 text-gray-900' : 'border-transparent text-gray-600 hover:text-gray-900 hover:border-gray-300' }}">
        {{ $tab['label'] }}
        @if($tab['count'])
        <span class="inline-flex items-center justify-center min-w-[20px] h-5 px-1.5 rounded-full bg-red-600 text-white text-xs font-semibold" title="Nové poptávky">{{ $tab['count'] }}</span>
        @endif
    </a>
    @endforeach
</nav>
