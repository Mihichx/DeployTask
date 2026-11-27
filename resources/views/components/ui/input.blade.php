@props(['label', 'name', 'id' => null])

@php $inputId = $id ?? $name; @endphp

<div>
    <label for="{{ $inputId }}" class="mb-1.5 block text-xs font-medium text-zinc-300">
        {{ $label }}
    </label>

    <input
        id="{{ $inputId }}"
        name="{{ $name }}"
        {{ $attributes->merge([
            'type' => 'text',
            'class' => 'w-full rounded-lg border border-zinc-800 bg-zinc-950 px-3.5 py-2.5 text-sm text-white placeholder-zinc-600 focus:border-red-500 focus:outline-none focus:ring-1 focus:ring-red-500/30 transition',
        ]) }}
    >

    @error($name)
        <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p>
    @enderror
</div>
