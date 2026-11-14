<?php

use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;
use App\Models\LoginPhrase; // Импортируйте вашу модель

new #[Layout('layouts.guest')] class extends Component {
    public string $login = '';
    public string $password = '';
    public bool $remember = true;
    public array $phrases = [];

    public function mount(): void
    {
        $this->phrases = LoginPhrase::where('is_active', true)->pluck('text')->toArray();
    }

    public function authenticate(): void
    {
        $this->login = trim($this->login);

        $this->validate(
            [
                'login' => 'required|string',
                'password' => 'required|string',
            ],
            [
                'login.required' => 'Введите логин или email',
                'password.required' => 'Введите пароль',
            ],
        );

        $field = filter_var($this->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'login';

        if (Auth::attempt([$field => $this->login, 'password' => $this->password], $this->remember)) {
            session()->regenerate();
            $this->redirect('/', navigate: true);
            return;
        }

        $this->addError('login', 'Неверный логин или пароль. Попробуйте ещё раз!');
    }
};
?>

<div class="w-full max-w-sm">
    <!-- Логотип и приветствие -->
    <div class="mb-4 text-center">
        <div
            class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl border border-zinc-800 bg-zinc-900/80 shadow-xl shadow-red-600/10">
            <img src="{{ Vite::asset('resources/img/logo.svg') }}" alt="DeployTask" class="h-9 w-9 object-contain">
        </div>
        <h1 class="text-2xl font-bold tracking-tight text-white">
            Deploy<span class="text-red-500">Task</span>
        </h1>
        <div wire:ignore x-data="{
            phrases: @js($phrases),
            currentIndex: 0,
            visible: false,
            init() {
                if (this.phrases.length === 0) return;

                // 1. Случайный старт при обновлении страницы
                this.currentIndex = Math.floor(Math.random() * this.phrases.length);

                // 2. Плавное появление первой фразы
                setTimeout(() => { this.visible = true; }, 100);

                // 3. Смена фраз каждые 4.5 секунды (только если фраз больше одной)
                if (this.phrases.length > 1) {
                    setInterval(() => {
                        this.visible = false;
                        setTimeout(() => {
                            this.currentIndex = (this.currentIndex + 1) % this.phrases.length;
                            this.visible = true;
                        }, 400);
                    }, 4500);
                }
            }
        }" class="mt-2 min-h-11 flex items-center justify-center px-2">
            <p x-text="phrases[currentIndex]"
                :class="visible ? 'opacity-100 translate-y-0 scale-100' : 'opacity-0 translate-y-2 scale-95'"
                class="text-sm text-zinc-400 transition-all duration-300 ease-out"></p>
        </div>
    </div>

    <!-- Форма входа -->
    <form wire:submit="authenticate"
        class="space-y-4 rounded-2xl border border-zinc-800/90 bg-zinc-900/60 p-6 shadow-2xl backdrop-blur-sm">
        <div>
            <label for="login" class="mb-1.5 block text-xs font-medium text-zinc-300">
                Логин или Email
            </label>
            <input id="login" type="text" wire:model="login" autofocus
                placeholder="owner_ivan или owner@example.com"
                class="w-full rounded-lg border border-zinc-800 bg-zinc-950 px-3.5 py-2.5 text-sm text-white placeholder-zinc-600 focus:border-red-500 focus:outline-none focus:ring-1 focus:ring-red-500/30 transition">
            @error('login')
                <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password" class="mb-1.5 block text-xs font-medium text-zinc-300">
                Пароль
            </label>
            <input id="password" type="password" placeholder="Минимум 8 символов" wire:model="password"
                class="w-full rounded-lg border border-zinc-800 bg-zinc-950 px-3.5 py-2.5 text-sm text-white placeholder-zinc-600 focus:border-red-500 focus:outline-none focus:ring-1 focus:ring-red-500/30 transition">
            @error('password')
                <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center justify-between pt-1">
            <label class="flex cursor-pointer items-center gap-2 text-xs text-zinc-400 hover:text-zinc-200">
                <input type="checkbox" wire:model="remember"
                    class="rounded border-zinc-700 bg-zinc-950 text-red-600 focus:ring-red-500/30">
                <span>Запомнить меня</span>
            </label>
        </div>

        <button type="submit"
            class="w-full rounded-lg bg-red-600 px-4 py-2.5 text-sm font-semibold text-white shadow-lg shadow-red-600/25 hover:bg-red-500 active:scale-[0.99] transition-all">
            <span wire:loading.remove wire:target="authenticate">Войти в систему</span>
        </button>
    </form>

    <!-- Подсказка с тестовым аккаунтом из сидера -->
    <div class="mt-6 rounded-xl border border-zinc-800/60 bg-zinc-900/30 p-3 text-center text-xs text-zinc-500">
        Тестовый вход: <span class="font-mono text-zinc-300">owner_ivan</span> / <span
            class="font-mono text-zinc-300">password</span>
    </div>
</div>
