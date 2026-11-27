@props(['teams', 'selectedTeamId', 'direction' => 'down'])

@php
    $currentTeam = $teams->firstWhere('id', $selectedTeamId);
    $isUp = $direction === 'up';
@endphp

<div x-data="dropdown('{{ $direction }}')" class="relative">
    <!-- Кнопка селекта -->
    <button type="button" @click="toggle" :class="open && 'border-red-500! ring-1 ring-red-500/30 bg-zinc-900!'"
        @class([
            'flex items-center justify-between gap-3 rounded-lg border border-zinc-800 bg-zinc-900 text-zinc-200 hover:border-zinc-700',
            'px-2.5 py-1.5 text-xs min-w-[170px]' => $isUp,
            'px-3.5 py-2 text-sm min-w-[210px]' => !$isUp,
        ])>
        <div class="flex items-center gap-2 truncate">
            <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded bg-red-500/15 text-red-400">
                <x-icons.users class="h-3.5 w-3.5" />
            </span>
            <span class="font-medium truncate">
                {{ $currentTeam?->name ?? 'Выберите команду' }}
            </span>
        </div>

        <x-icons.chevron-down ::class="open && 'rotate-180 text-red-400!'"
            class="h-4 w-4 shrink-0 text-zinc-500 transition-transform duration-200" />

    </button>

    <!-- Выпадающее меню -->
    <div x-bind="menu" style="display: none;" @class([
        'absolute left-0 z-50 w-60 rounded-xl border border-zinc-800 bg-zinc-950/95 p-1.5 shadow-xl shadow-black/60 backdrop-blur-md',
        'bottom-full mb-2 origin-bottom-left' => $isUp,
        'mt-2 origin-top-left' => !$isUp,
    ])>
        <div class="px-2.5 py-1.5 text-[11px] font-medium uppercase tracking-wider text-zinc-500">
            Команды проекта
        </div>

        <div class="space-y-0.5">
            @foreach ($teams as $team)
                @php $isSelected = $team->id === $selectedTeamId; @endphp

                <button type="button" wire:key="dropdown-team-{{ $direction }}-{{ $team->id }}"
                    @click="close(); $wire.selectTeam({{ $team->id }})" @class([
                        'flex w-full items-center justify-between rounded-lg px-2.5 py-2 text-left text-sm transition-colors',
                        'bg-red-500/15 text-red-400 font-medium' => $isSelected,
                        'text-zinc-300 hover:bg-zinc-900 hover:text-white' => !$isSelected,
                    ])>
                    <span class="truncate">{{ $team->name }}</span>

                    @if ($isSelected)
                        <x-icons.check class="h-4 w-4 shrink-0 text-red-500" />
                    @endif
                </button>
            @endforeach
        </div>
    </div>
</div>
