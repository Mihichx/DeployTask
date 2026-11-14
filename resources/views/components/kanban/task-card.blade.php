@props(['task', 'statusId'])

@php
    $canMove = $task->assignees->contains('id', auth()->id());
@endphp

<div wire:key="task-{{ $task->id }}"
    @if ($canMove) draggable="true"
        @dragstart="
            draggingTaskId = {{ $task->id }};
            draggingFromStatus = {{ $statusId }};
            $event.dataTransfer.effectAllowed = 'move';
        "
        @dragend="resetDrag()"
        :class="draggingTaskId === {{ $task->id }} && 'opacity-60 scale-[0.98] rotate-1 !border-orange-500 !bg-zinc-900 shadow-lg shadow-orange-500/15'" @endif
    @class([
        'relative rounded-lg border border-zinc-800/90 p-3 select-none',
        'group bg-zinc-950 cursor-grab active:cursor-grabbing hover:border-zinc-600 hover:-translate-y-px hover:shadow-md hover:shadow-black/40 transition-[transform,border-color,box-shadow] duration-150' => $canMove,
        'bg-zinc-950/60 cursor-default opacity-75' => !$canMove,
    ])>

    <!-- Верхняя строка: ID и Приоритет -->
    <div class="mb-2 flex items-center justify-between text-xs">
        <span class="font-mono text-zinc-500">#{{ $task->id }}</span>
        @if ($task->priority)
            <span
                style="color: {{ $task->priority->color }}; background-color: {{ $task->priority->color }}1A; border-color: {{ $task->priority->color }}40;"
                class="rounded border px-1.5 py-0.5 text-[11px] font-medium">
                {{ $task->priority->name }}
            </span>
        @endif
    </div>

    <!-- Название задачи -->
    <p class="text-sm font-medium text-zinc-100 leading-snug mb-3">
        {{ $task->name }}
    </p>

    <!-- Нижняя панель: SP, подзадачи и исполнители -->
    <div class="flex items-center justify-between gap-2 text-xs text-zinc-500 pt-2.5 border-t border-zinc-900">
        <div class="flex items-center gap-2.5 shrink-0">
            @if ($task->story_points)
                <span class="whitespace-nowrap rounded bg-zinc-900 px-1.5 py-0.5 text-zinc-400">
                    {{ $task->story_points }} SP
                </span>
            @endif

            @if ($task->subtasks_count > 0)
                @php $allSubtasksDone = $task->completed_subtasks_count === $task->subtasks_count; @endphp
                <span
                    class="whitespace-nowrap flex items-center gap-1 {{ $allSubtasksDone ? 'text-emerald-400' : 'text-zinc-400' }}"
                    title="Выполнено подзадач">
                    <svg class="h-3.5 w-3.5 shrink-0" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M16.704 4.153a.75.75 0 0 1 .143 1.052l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.143Z"
                            clip-rule="evenodd" />
                    </svg>
                    <span>{{ $task->completed_subtasks_count }}/{{ $task->subtasks_count }}</span>
                </span>
            @endif
        </div>

        @if ($task->assignees->isNotEmpty())
            <span @class([
                'text-[11px] truncate',
                'text-zinc-300 font-medium' => $canMove,
                'text-zinc-500' => !$canMove,
            ])>
                {{ $task->assignees->map(fn($assign) => \Illuminate\Support\Str::before($assign->name, ' '))->join(', ') }}
            </span>
        @endif
    </div>
</div>
