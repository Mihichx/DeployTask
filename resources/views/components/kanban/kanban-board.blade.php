<?php

use Livewire\Component;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Async;
use Livewire\Attributes\Renderless;
use App\Services\TaskService;
use App\Models\Task;

new class extends Component {
    public ?int $selectedTeamId = null;
    public string $newTaskTitle = '';

    public function mount(): void
    {
        $this->selectedTeamId = session('selected_team_id') ?? $this->teams->first()?->id;
    }

    public function selectTeam(int $teamId): void
    {
        $this->selectedTeamId = $teamId;
        session(['selected_team_id' => $this->selectedTeamId]);
    }

    #[Computed]
    public function teams()
    {
        return app(TaskService::class)->getTeams();
    }

    #[Computed]
    public function tasks()
    {
        return app(TaskService::class)->getTasks($this->selectedTeamId);
    }

    #[Computed]
    public function statuses()
    {
        return app(TaskService::class)->getStatuses();
    }

    public function addTask(): void
    {
        $this->validate(['newTaskTitle' => 'required|min:2']);

        Task::create([
            'name' => $this->newTaskTitle,
            'task_status_id' => $this->statuses->first()?->id ?? 1,
            'story_points' => 1,
            'created_by' => auth()->id() ?? 1,
            'team_id' => $this->selectedTeamId ?? 1,
        ]);

        $this->reset('newTaskTitle');
    }

    #[Async]
    #[Renderless]
    public function moveTask(int $taskId, int $newStatusId): void
    {
        session()->save();

        $task = Task::find($taskId);

        if (!$task || $task->task_status_id === $newStatusId) {
            return;
        }

        $isAssignee = $task->assignees()->where('users.id', auth()->id())->exists();

        if (!$isAssignee) {
            $this->dispatch('task-move-denied');
            return;
        }

        $doneStatusId = $this->statuses->firstWhere('name', 'Done')?->id;

        $task->update([
            'task_status_id' => $newStatusId,
            'closed_at' => $newStatusId === $doneStatusId ? now() : null,
        ]);
    }
};
?>

<div x-data="kanbanBoard" @task-move-denied.window="$wire.$refresh()" class="space-y-5">
    <!-- Шапка -->
    <div data-focus-hide x-bind="headerFocus"
        class="flex flex-wrap items-center justify-between gap-4 border-b border-zinc-800 pb-4">
        <div class="flex items-center gap-4">
            <div>
                <h1 class="text-2xl font-bold text-white">Доска задач</h1>
                <p class="text-xs text-zinc-400">Управление спринтом и статусами</p>
            </div>

            <x-kanban.team-select :teams="$this->teams" :selectedTeamId="$selectedTeamId" />
        </div>

        <form wire:submit="addTask" class="flex gap-2">
            <input type="text" wire:model="newTaskTitle" placeholder="Новая задача..."
                class="w-64 rounded-lg border border-zinc-700 bg-zinc-900 px-3 py-2 text-sm text-white placeholder-zinc-500 focus:border-red-500 focus:outline-none">
            <button type="submit"
                class="rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white shadow-lg shadow-red-600/20 hover:bg-red-500 transition">
                + Добавить
            </button>
        </form>
    </div>

    <!-- Плавающая панель в режиме фокусировки -->
    <div data-focus-show
        class="fixed bottom-4 right-4 z-40 flex items-center gap-2.5 rounded-xl border border-zinc-800 bg-zinc-950/90 p-1.5 shadow-2xl shadow-black/70 backdrop-blur-md">

        <x-kanban.team-select :teams="$this->teams" :selectedTeamId="$selectedTeamId" direction="up" />

        <button type="button" @click="toggleFocus(false)"
            class="flex items-center gap-1.5 rounded-lg px-2.5 py-1.5 text-xs text-zinc-400 hover:bg-zinc-900 hover:text-white transition-colors">
            <span>Обычный вид</span>
            <kbd class="rounded border border-zinc-700 bg-zinc-800 px-1.5 py-0.5 font-mono text-[10px] text-zinc-300">
                F / Esc
            </kbd>
        </button>
    </div>

    <!-- Канбан-колонки -->
    <div class="grid grid-cols-1 gap-4 md:grid-cols-3 xl:grid-cols-5 items-start">
        @foreach ($this->statuses as $status)
            <x-kanban.column :status="$status" :tasks="$this->tasks->where('task_status_id', $status->id)" />
        @endforeach
    </div>
</div>
