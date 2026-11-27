<aside data-focus-hide x-bind="sidebarTransition"
    class="w-60 shrink-0 bg-zinc-900/70 border-r border-zinc-800/80 p-4 flex flex-col justify-between backdrop-blur-sm">

    <!-- Верхняя часть: логотип и меню -->
    <div>
        <div class="flex items-center justify-between mb-7 px-1">
            <a href="/" wire:navigate class="flex items-center gap-2.5 group">
                <img src="{{ Vite::asset('resources/img/logo.svg') }}" alt="DeployTask Logo"
                    class="h-8 w-8 object-contain group-hover:scale-105 transition-transform">
                <span class="text-lg font-bold tracking-tight text-white">
                    Deploy<span class="text-red-500">Task</span>
                </span>
            </a>

            <button type="button" @click="$store.focus.toggle(true)" title="Скрыть меню (F)"
                class="rounded-md p-1.5 text-zinc-500 hover:bg-zinc-800 hover:text-white transition">
                <x-icons.chevron-double-left class="h-4 w-4" />
            </button>
        </div>

        <div class="px-2 mb-2 text-[11px] font-semibold uppercase tracking-wider text-zinc-500">
            Рабочее пространство
        </div>

        <nav class="space-y-1">
            <x-layouts.nav-link href="/" :active="request()->is('/')" icon="squares-plus">
                Задачи
            </x-layouts.nav-link>

            <x-layouts.nav-link href="#" :active="request()->is('teams*')" icon="users">
                Команды
            </x-layouts.nav-link>

            <x-layouts.nav-link href="#" :active="request()->is('metrics*')" icon="chart-bar" :navigate="false">
                Метрики
            </x-layouts.nav-link>
        </nav>
    </div>

    <!-- Нижняя часть: профиль и подсказка -->
    <div class="space-y-3 pt-4 border-t border-zinc-800/80">
        <x-layouts.user-card />

        <div class="flex items-center justify-between px-1 text-[11px] text-zinc-500">
            <span>Focus Mode</span>
            <kbd class="rounded border border-zinc-700 bg-zinc-900 px-1.5 py-0.5 text-[10px] text-zinc-400">F</kbd>
        </div>
    </div>
</aside>
