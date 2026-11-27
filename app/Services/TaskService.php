<?php

namespace App\Services;

use App\Models\Task;
use App\Models\TaskStatus;
use App\Models\Team;
use Illuminate\Support\Collection;

class TaskService
{
    public function getTasks(?int $teamId = null): Collection
    {
        $userId = auth()->id();

        return Task::query()
            ->whereNull('parent_id')
            ->when($teamId, fn($query) => $query->where('team_id', $teamId))
            ->with(['priority', 'creator', 'assignees'])
            ->withCount([
                'subtasks',
                'subtasks as completed_subtasks_count' => fn($q) => $q->whereNotNull('closed_at'),
            ])
            ->when($userId, function ($query) use ($userId) {
                $query->orderByRaw(
                    'EXISTS (SELECT 1 FROM task_user WHERE task_user.task_id = tasks.id AND task_user.user_id = ?) DESC',
                    [$userId]
                );
            })
            ->orderByRaw('task_priority_id DESC NULLS LAST')
            ->latest('id')
            ->get();
    }

    public function getStatuses(): Collection
    {
        return TaskStatus::orderBy('id')->get();
    }

    public function getTeams(): Collection
    {
        $user = auth()->user();

        if (!$user) {
            return collect();
        }

        return $user->teams()->orderBy('teams.id')->get();
    }
}
