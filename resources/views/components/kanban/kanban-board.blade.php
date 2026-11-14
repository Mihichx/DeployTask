<?php

use Livewire\Component;
use Livewire\Attributes\Computed;
use App\Services\TaskService;
use App\Models\Task;
use App\Models\Team;

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
        unset($this->tasks);
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

        unset($this->tasks);
        $this->reset('newTaskTitle');
    }

    public function moveTask(int $taskId, int $newStatusId): void
    {
        $task = Task::find($taskId);

        if (!$task || $task->task_status_id === $newStatusId) {
            return;
        }

        $isAssignee = $task->assignees()->where('users.id', auth()->id())->exists();

        if (!$isAssignee) {
            return;
        }

        $doneStatusId = $this->statuses->firstWhere('name', 'Done')?->id;

        $task->update([
            'task_status_id' => $newStatusId,
            'closed_at' => $newStatusId === $doneStatusId ? now() : null,
        ]);

        unset($this->tasks);
    }
};
?>

<div x-data="{
    draggingTaskId: null,
    draggingFromStatus: null,
    dragOverStatus: null,
    resetDrag() {
        this.draggingTaskId = null;
        this.draggingFromStatus = null;
        this.dragOverStatus = null;
    }
}" class="space-y-5">
    <!-- Шапка -->
    <div data-focus-hide x-show="!focusMode" x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-2"
        class="flex flex-wrap items-center justify-between gap-4 border-b border-zinc-800 pb-4">
        <div class="flex items-center gap-4">
            <div>
                <h1 class="text-2xl font-bold text-white">Доска задач</h1>
                <p class="text-xs text-zinc-400">Управление спринтом и статусами</p>
            </div>

            <!-- Подключенный модуль селекта команд -->
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

        <!-- Кнопка выхода из режима фокуса -->
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
            @php $columnTasks = $this->tasks->where('task_status_id', $status->id); @endphp

            <div wire:key="status-{{ $status->id }}"
                @dragenter.prevent="if (draggingFromStatus !== {{ $status->id }}) dragOverStatus = {{ $status->id }}"
                @dragover.prevent="if (draggingFromStatus !== {{ $status->id }}) dragOverStatus = {{ $status->id }}"
                @dragleave.self="if (dragOverStatus === {{ $status->id }}) dragOverStatus = null"
                @drop.prevent="
                    if (draggingTaskId && draggingFromStatus !== {{ $status->id }}) {
                        $wire.moveTask(draggingTaskId, {{ $status->id }});
                    }
                    resetDrag();
                "
                style="border-top-color: {{ $status->color }};"
                class="flex flex-col rounded-xl border border-zinc-800 bg-zinc-900/50 border-t-2 p-3 transition-colors duration-200">
                <!-- Заголовок колонки -->
                <div class="mb-3 flex items-center justify-between px-1">
                    <div class="flex items-center gap-2">
                        <span style="background-color: {{ $status->color }};" class="h-2 w-2 rounded-full"></span>
                        <h2 class="text-sm font-semibold text-zinc-200">{{ $status->name }}</h2>
                    </div>
                    <span class="rounded-full bg-zinc-800/80 px-2 py-0.5 text-xs font-medium text-zinc-400">
                        {{ $columnTasks->count() }}
                    </span>
                </div>

                <!-- Зона карточек -->
                <div :class="dragOverStatus === {{ $status->id }} && '!border-dashed !border-orange-500/60 !bg-zinc-950/60'"
                    class="custom-scrollbar [scrollbar-gutter:stable] space-y-2.5 max-h-[calc(100vh-210px)] overflow-y-auto rounded-lg border border-transparent p-1 pt-1 pr-1.5 min-h-[380px]">
                    @forelse($columnTasks as $task)
                        <!-- Подключенный модуль карточки задачи -->
                        <x-kanban.task-card :task="$task" :statusId="$status->id" />
                    @empty
                        <div
                            class="flex h-24 items-center justify-center rounded-lg border border-dashed border-zinc-800/70 text-xs text-zinc-600">
                            Здесь пока чисто и спокойно
                        </div>
                    @endforelse
                </div>
            </div>
        @endforeach
    </div>
</div>
