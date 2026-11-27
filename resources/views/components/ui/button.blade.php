@props(['target' => null, 'loadingText' => null])

<button
    {{ $attributes->merge([
        'type' => 'submit',
        'class' => 'inline-flex items-center justify-center gap-2 rounded-lg bg-red-600 px-4 py-2.5 text-sm font-medium text-white shadow-lg shadow-red-600/20 hover:bg-red-500 disabled:opacity-70 disabled:cursor-not-allowed transition',
    ]) }}
    @if($target) wire:loading.attr="disabled" wire:target="{{ $target }}" @endif
>
    @if($target)
        <svg wire:loading wire:target="{{ $target }}" class="h-4 w-4 animate-spin shrink-0" viewBox="0 0 24 24" fill="none">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
        </svg>
    @endif

    @if($target && $loadingText)
        <span wire:loading.remove wire:target="{{ $target }}">{{ $slot }}</span>
        <span wire:loading wire:target="{{ $target }}">{{ $loadingText }}</span>
    @else
        <span>{{ $slot }}</span>
    @endif
</button>
