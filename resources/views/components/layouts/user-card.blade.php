@php
    $user = auth()->user();
    $name = $user?->name ?? 'Гость';
    $login = $user?->login ?? 'guest';
@endphp

<div class="flex items-center justify-between gap-2 rounded-lg bg-zinc-950/80 p-2 border border-zinc-800/80">
    <div class="flex items-center gap-2.5 min-w-0">
        <div
            class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-linear-to-br from-red-600 to-orange-500 text-xs font-bold text-white shadow-sm">
            {{ mb_substr($name, 0, 1) }}
        </div>
        <div class="min-w-0 flex-1">
            <p class="truncate text-xs font-medium text-white">{{ $name }}</p>
            <p class="truncate text-[11px] text-orange-400/90">@ {{ $login }}</p>
        </div>
    </div>

    @if ($user)
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" title="Выйти из аккаунта"
                class="rounded-md p-1.5 text-zinc-500 hover:bg-red-500/15 hover:text-red-400 transition">
                <x-icons.logout class="h-4 w-4" />
            </button>
        </form>
    @endif
</div>
