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

        <div wire:ignore x-data="phraseRotator(@js($phrases))" class="mt-2 min-h-11 flex items-center justify-center px-2">
            <p x-text="phrases[currentIndex]"
                :class="visible ? 'opacity-100 translate-y-0 scale-100' : 'opacity-0 translate-y-2 scale-95'"
                class="text-sm text-zinc-400 transition-all duration-300 ease-out"></p>
        </div>
    </div>

    <!-- Форма входа -->
    <form wire:submit="authenticate"
        class="space-y-4 rounded-2xl border border-zinc-800/90 bg-zinc-900/60 p-6 shadow-2xl backdrop-blur-sm">

        <x-ui.input label="Логин или Email" name="login" wire:model="login"
            placeholder="owner_ivan или owner@example.com" autofocus />

        <x-ui.input type="password" label="Пароль" name="password" wire:model="password"
            placeholder="Минимум 8 символов" />

        <div class="flex items-center justify-between pt-1">
            <label class="flex cursor-pointer items-center gap-2 text-xs text-zinc-400 hover:text-zinc-200">
                <input type="checkbox" wire:model="remember"
                    class="rounded border-zinc-700 bg-zinc-950 text-red-600 focus:ring-red-500/30">
                <span>Запомнить меня</span>
            </label>
        </div>

        <x-ui.button target="authenticate" loading-text="Входим..." class="w-full">
            Войти
        </x-ui.button>
    </form>

    <!-- Подсказка (показываем только в local-окружении) -->
    @env('local')
        <div class="mt-6 rounded-xl border border-zinc-800/60 bg-zinc-900/30 p-3 text-center text-xs text-zinc-500">
            Тестовый вход: <span class="font-mono text-zinc-300">owner_ivan</span> / <span
                class="font-mono text-zinc-300">password</span>
        </div>
    @endenv
</div>
