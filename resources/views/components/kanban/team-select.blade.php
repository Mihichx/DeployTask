@props(['teams', 'selectedTeamId', 'direction' => 'down'])

@php
    $currentTeam = $teams->firstWhere('id', $selectedTeamId);
    $isUp = $direction === 'up';
@endphp

<div
    x-data="{ open: false }"
    @click.outside="open = false"
    @keydown.escape.window="open = false"
    class="relative"
>
    <button
        type="button"
        @click="open = !open"
        :class="open
            ? 'border-red-500 ring-1 ring-red-500/30 bg-zinc-900'
            : 'border-zinc-800 bg-zinc-900/80 hover:border-zinc-700 hover:bg-zinc-900'"
        @class([
            'flex items-center justify-between gap-3 rounded-lg border border-zinc-800 bg-zinc-900/80 text-zinc-200 transition-all duration-150',
            'px-2.5 py-1.5 text-xs min-w-[170px]' => $isUp,
            'px-3.5 py-2 text-sm min-w-[210px]' => ! $isUp,
        ])
    >
        <div class="flex items-center gap-2 truncate">
            <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded bg-red-500/15 text-red-400">
                <svg class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M10 9a3 3 0 1 0 0-6 3 3 0 0 0 0 6ZM6 8a2 2 0 1 1-4 0 2 2 0 0 1 4 0ZM1.49 15.326a.78.78 0 0 1-.358-.442 3 3 0 0 1 4.308-3.516 6.484 6.484 0 0 0-1.905 3.959c-.023.222-.014.442.025.654a4.97 4.97 0 0 1-2.07-.655ZM16.44 15.98a4.97 4.97 0 0 0 2.07-.654.78.78 0 0 0 .357-.442 3 3 0 0 0-4.308-3.517 6.484 6.484 0 0 1 1.907 3.96 2.32 2.32 0 0 1-.026.654ZM18 8a2 2 0 1 1-4 0 2 2 0 0 1 4 0ZM5.304 16.19a.844.844 0 0 1-.277-.71 5 5 0 0 1 9.947 0 .843.843 0 0 1-.277.71A6.975 6.975 0 0 1 10 18a6.974 6.974 0 0 1-4.696-1.81Z" />
                </svg>
            </span>
            <span class="font-medium truncate">
                {{ $currentTeam?->name ?? 'Выберите команду' }}
            </span>
        </div>

        <svg
            :class="open ? 'rotate-180 text-red-400' : 'text-zinc-500'"
            class="h-4 w-4 shrink-0 text-zinc-500 transition-transform duration-200"
            viewBox="0 0 20 20"
            fill="currentColor"
        >
            <path fill-rule="evenodd" d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
        </svg>
    </button>

    <div
        x-cloak
        x-show="open"
        x-transition:enter="transition ease-out duration-150"
        x-transition:enter-start="opacity-0 scale-95 {{ $isUp ? 'translate-y-1' : '-translate-y-1' }}"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
        x-transition:leave-end="opacity-0 scale-95 {{ $isUp ? 'translate-y-1' : '-translate-y-1' }}"
        style="display: none;"
        @class([
            'absolute left-0 z-50 w-60 rounded-xl border border-zinc-800 bg-zinc-950/95 p-1.5 shadow-xl shadow-black/60 backdrop-blur-md',
            'bottom-full mb-2 origin-bottom-left' => $isUp,
            'mt-2 origin-top-left' => ! $isUp,
        ])
    >
        <div class="px-2.5 py-1.5 text-[11px] font-medium uppercase tracking-wider text-zinc-500">
            Команды проекта
        </div>

        <div class="space-y-0.5">
            @foreach($teams as $team)
                @php $isSelected = $team->id === $selectedTeamId; @endphp
                <button
                    type="button"
                    wire:key="dropdown-team-{{ $direction }}-{{ $team->id }}"
                    @click="open = false; $wire.selectTeam({{ $team->id }})"
                    class="flex w-full items-center justify-between rounded-lg px-2.5 py-2 text-left text-sm transition-colors {{ $isSelected ? 'bg-red-500/15 text-red-400 font-medium' : 'text-zinc-300 hover:bg-zinc-900 hover:text-white' }}"
                >
                    <span class="truncate">{{ $team->name }}</span>
                    @if($isSelected)
                        <svg class="h-4 w-4 shrink-0 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 0 1 .143 1.052l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.143Z" clip-rule="evenodd" />
                        </svg>
                    @endif
                </button>
            @endforeach
        </div>
    </div>
</div>
