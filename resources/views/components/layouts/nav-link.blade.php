@props(['href' => '#', 'active' => false, 'icon' => null, 'navigate' => true])

<a href="{{ $href }}" @if ($navigate) wire:navigate @endif @class([
    'flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors',
    'bg-red-500/15 text-red-400 border border-red-500/30' => $active,
    'text-zinc-400 hover:bg-zinc-800/70 hover:text-white' => !$active,
])>
    @if ($icon)
        <x-dynamic-component :component="'icons.' . $icon" @class(['h-4 w-4 shrink-0', 'text-red-500' => $active]) />
    @endif

    <span>{{ $slot }}</span>
</a>
