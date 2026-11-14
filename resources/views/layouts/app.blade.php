<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ Vite::asset('resources/img/logo.svg') }}" type="image/svg+xml">
    <title>{{ $title ?? 'DeployTask' }}</title>

    <script src="{{ Vite::asset('resources/js/focus-init.js') }}"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body x-data="{
    focusMode: localStorage.getItem('deploytask_focus_mode') === '1',
    toggleFocus(val = null) {
        this.focusMode = val !== null ? val : !this.focusMode;
        localStorage.setItem('deploytask_focus_mode', this.focusMode ? '1' : '0');
        document.documentElement.classList.toggle('is-focus-mode', this.focusMode);
    },
    handleKeydown(e) {
        if (e.key === 'Escape' && this.focusMode) {
            this.toggleFocus(false);
            return;
        }
        if (['INPUT', 'TEXTAREA', 'SELECT'].includes(e.target.tagName) || e.target.isContentEditable || e.ctrlKey || e.metaKey || e.altKey) return;
        if (e.code === 'KeyF') this.toggleFocus();
    }
}" @keydown.window="handleKeydown($event)"
    class="bg-zinc-950 text-white antialiased min-h-screen flex selection:bg-red-500/30 selection:text-red-200">

    <!-- Боковое меню -->
    <aside data-focus-hide x-show="!focusMode" x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="-translate-x-full opacity-0" x-transition:enter-end="translate-x-0 opacity-100"
        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="translate-x-0 opacity-100"
        x-transition:leave-end="-translate-x-full opacity-0"
        class="w-60 shrink-0 bg-zinc-900/70 border-r border-zinc-800/80 p-4 flex flex-col justify-between backdrop-blur-sm">
        <div>
            <div class="flex items-center justify-between mb-7 px-1">
                <a href="/" wire:navigate class="flex items-center gap-2.5 group">
                    <img src="{{ Vite::asset('resources/img/logo.svg') }}" alt="DeployTask Logo"
                        class="h-8 w-8 object-contain group-hover:scale-105 transition-transform">
                    <span class="text-lg font-bold tracking-tight text-white">
                        Deploy<span class="text-red-500">Task</span>
                    </span>
                </a>

                <button type="button" @click="toggleFocus(true)" title="Скрыть меню (F)"
                    class="rounded-md p-1.5 text-zinc-500 hover:bg-zinc-800 hover:text-white transition">
                    <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M15.79 14.77a.75.75 0 0 1-1.06.02l-4.5-4.25a.75.75 0 0 1 0-1.08l4.5-4.25a.75.75 0 1 1 1.04 1.08L11.832 10l3.938 3.71a.75.75 0 0 1 .02 1.06Zm-6 0a.75.75 0 0 1-1.06.02l-4.5-4.25a.75.75 0 0 1 0-1.08l4.5-4.25a.75.75 0 1 1 1.04 1.08L5.832 10l3.938 3.71a.75.75 0 0 1 .02 1.06Z"
                            clip-rule="evenodd" />
                    </svg>
                </button>
            </div>

            <!-- Навигация -->
            <div class="px-2 mb-2 text-[11px] font-semibold uppercase tracking-wider text-zinc-500">
                Рабочее пространство
            </div>
            <nav class="space-y-1">
                <!-- Задачи -->
                <a href="/" wire:navigate
                    class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->is('/') ? 'bg-red-500/15 text-red-400 border border-red-500/30' : 'text-zinc-400 hover:bg-zinc-800/70 hover:text-white' }}">
                    <svg class="h-4 w-4 shrink-0 {{ request()->is('/') ? 'text-red-500' : '' }}" viewBox="0 0 20 20"
                        fill="currentColor">
                        <path
                            d="M2 4.25A2.25 2.25 0 0 1 4.25 2h2.5A2.25 2.25 0 0 1 9 4.25v2.5A2.25 2.25 0 0 1 6.75 9h-2.5A2.25 2.25 0 0 1 2 6.75v-2.5ZM2 13.25A2.25 2.25 0 0 1 4.25 11h2.5A2.25 2.25 0 0 1 9 13.25v2.5A2.25 2.25 0 0 1 6.75 18h-2.5A2.25 2.25 0 0 1 2 15.75v-2.5ZM11 4.25A2.25 2.25 0 0 1 13.25 2h2.5A2.25 2.25 0 0 1 18 4.25v2.5A2.25 2.25 0 0 1 15.75 9h-2.5A2.25 2.25 0 0 1 11 6.75v-2.5ZM15.25 11.75a.75.75 0 0 0-1.5 0v2h-2a.75.75 0 0 0 0 1.5h2v2a.75.75 0 0 0 1.5 0v-2h2a.75.75 0 0 0 0-1.5h-2v-2Z" />
                    </svg>
                    <span>Задачи</span>
                </a>

                <!-- Команды -->
                <a href="#" wire:navigate
                    class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->is('1*') ? 'bg-red-500/15 text-red-400 border border-red-500/30' : 'text-zinc-400 hover:bg-zinc-800/70 hover:text-white' }}">
                    <svg class="h-4 w-4 shrink-0 {{ request()->is('1*') ? 'text-red-500' : '' }}" viewBox="0 0 20 20"
                        fill="currentColor">
                        <path
                            d="M10 9a3 3 0 1 0 0-6 3 3 0 0 0 0 6ZM6 8a2 2 0 1 1-4 0 2 2 0 0 1 4 0ZM1.49 15.326a.78.78 0 0 1-.358-.442 3 3 0 0 1 4.308-3.516 6.484 6.484 0 0 0-1.905 3.959c-.023.222-.014.442.025.654a4.97 4.97 0 0 1-2.07-.655ZM16.44 15.98a4.97 4.97 0 0 0 2.07-.654.78.78 0 0 0 .357-.442 3 3 0 0 0-4.308-3.517 6.484 6.484 0 0 1 1.907 3.96 2.32 2.32 0 0 1-.026.654ZM18 8a2 2 0 1 1-4 0 2 2 0 0 1 4 0ZM5.304 16.19a.844.844 0 0 1-.277-.71 5 5 0 0 1 9.947 0 .843.843 0 0 1-.277.71A6.975 6.975 0 0 1 10 18a6.974 6.974 0 0 1-4.696-1.81Z" />
                    </svg>
                    <span>Команды</span>
                </a>

                <!-- Метрики -->
                <a href="#"
                    class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->is('metrics*') ? 'bg-red-500/15 text-red-400 border border-red-500/30' : 'text-zinc-400 hover:bg-zinc-800/70 hover:text-white' }}">
                    <svg class="h-4 w-4 shrink-0" viewBox="0 0 20 20" fill="currentColor">
                        <path
                            d="M15.5 2A1.5 1.5 0 0 0 14 3.5v13a1.5 1.5 0 0 0 1.5 1.5h1a1.5 1.5 0 0 0 1.5-1.5v-13A1.5 1.5 0 0 0 16.5 2h-1ZM9.5 6A1.5 1.5 0 0 0 8 7.5v9A1.5 1.5 0 0 0 9.5 18h1a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 10.5 6h-1ZM3.5 10A1.5 1.5 0 0 0 2 11.5v5A1.5 1.5 0 0 0 3.5 18h1A1.5 1.5 0 0 0 6 16.5v-5A1.5 1.5 0 0 0 4.5 10h-1Z" />
                    </svg>
                    <span>Метрики</span>
                </a>
            </nav>
        </div>

        <!-- Нижний блок: Профиль и Focus Mode -->
        <div class="space-y-3 pt-4 border-t border-zinc-800/80">
            @php
                $user = auth()->user();
                $userName = $user?->name ?? 'Гость';
                $userLogin = $user?->login ?? 'guest';
            @endphp

            <div
                class="flex items-center justify-between gap-2 rounded-lg bg-zinc-950/80 p-2 border border-zinc-800/80">
                <div class="flex items-center gap-2.5 min-w-0">
                    <div
                        class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-red-600 to-orange-500 text-xs font-bold text-white shadow-sm">
                        {{ mb_substr(auth()->user()->name, 0, 1) }}
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-xs font-medium text-white">{{ auth()->user()->name }}</p>
                        <p class="truncate text-[11px] text-orange-400/90">@ {{ auth()->user()->login }}</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" title="Выйти из аккаунта"
                        class="rounded-md p-1.5 text-zinc-500 hover:bg-red-500/15 hover:text-red-400 transition">
                        <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M3 4.25A2.25 2.25 0 0 1 5.25 2h5.5A2.25 2.25 0 0 1 13 4.25v2a.75.75 0 0 1-1.5 0v-2a.75.75 0 0 0-.75-.75h-5.5a.75.75 0 0 0-.75.75v11.5c0 .414.336.75.75.75h5.5a.75.75 0 0 0 .75-.75v-2a.75.75 0 0 1 1.5 0v2A2.25 2.25 0 0 1 10.75 18h-5.5A2.25 2.25 0 0 1 3 15.75V4.25Z"
                                clip-rule="evenodd" />
                            <path fill-rule="evenodd"
                                d="M19 10a.75.75 0 0 0-.75-.75H8.704l1.048-.943a.75.75 0 1 0-1.004-1.114l-2.5 2.25a.75.75 0 0 0 0 1.114l2.5 2.25a.75.75 0 1 0 1.004-1.114l-1.048-.943h9.546A.75.75 0 0 0 19 10Z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>
                </form>
            </div>


            <div class="flex items-center justify-between px-1 text-[11px] text-zinc-500">
                <span>Focus Mode</span>
                <kbd class="rounded border border-zinc-700 bg-zinc-900 px-1.5 py-0.5 text-[10px] text-zinc-400">F</kbd>
            </div>
        </div>
    </aside>

    <!-- Основной контент -->
    <main class="flex-1 min-w-0 p-6">
        {{ $slot }}
    </main>

    @livewireScripts
</body>

</html>
