@props(['status', 'tasks'])

<div wire:key="status-{{ $status->id }}" @dragenter.prevent="enterColumn({{ $status->id }})"
    @dragover.prevent="enterColumn({{ $status->id }})" @dragleave.self="leaveColumn({{ $status->id }})"
    @drop.prevent="dropTask({{ $status->id }})" style="border-top-color: {{ $status->color }};"
    class="flex flex-col rounded-xl border border-zinc-800 bg-zinc-900/50 border-t-2 p-3 transition-colors duration-200">

    <!-- Заголовок -->
    <div class="mb-3 flex items-center justify-between px-1">
        <div class="flex items-center gap-2">
            <span style="background-color: {{ $status->color }};" class="h-2 w-2 rounded-full"></span>
            <h2 class="text-sm font-semibold text-zinc-200">{{ $status->name }}</h2>
        </div>
        <span data-count="{{ $status->id }}"
            class="rounded-full bg-zinc-800/80 px-2 py-0.5 text-xs font-medium text-zinc-400">
            {{ $tasks->count() }}
        </span>
    </div>

    <!-- Зона карточек -->
    <div data-column-body="{{ $status->id }}"
        :class="dragOverStatus === {{ $status->id }} && 'border-dashed! border-orange-500/60! bg-zinc-950/60!'"
        class="custom-scrollbar scrollbar-gutter-stable space-y-2.5 max-h-[calc(100vh-210px)] overflow-y-auto rounded-lg border border-transparent p-1 pt-1 pr-1.5 min-h-95">

        @foreach ($tasks as $task)
            @php
                $isMine = $task->assignees->contains('id', auth()->id()) ? 1 : 0;
                // Если у приоритета уже есть числовое поле (например, $task->priority->weight) — используйте его
                $priorityWeight = match ($task->priority?->name ?? $task->priority) {
                    'Urgent' => 4,
                    'High' => 3,
                    'Medium' => 2,
                    'Low' => 1,
                    default => 0,
                };
            @endphp

            <div wire:key="task-wrapper-{{ $task->id }}" data-task-id="{{ $task->id }}"
                data-mine="{{ $isMine }}" data-priority="{{ $priorityWeight }}"
                @dragstart="startDrag({{ $task->id }}, $el)">
                <x-kanban.task-card :task="$task" :statusId="$status->id" />
            </div>
        @endforeach

        <div data-empty
            class="{{ $tasks->isNotEmpty() ? 'hidden' : '' }} flex h-24 items-center justify-center rounded-lg border border-dashed border-zinc-800/70 text-xs text-zinc-600">
            Здесь пока чисто и спокойно
        </div>
    </div>
</div>
