<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'initial_estimation',
    'time_in_progress',
    'time_in_review',
    'wakapi_logged_time',
    'total_actual_time',
    'is_overdue',
    'task_id',
    'user_id'
])]
class TaskTimeMetric extends Model
{
    protected function casts(): array
    {
        return [
            'is_overdue' => 'boolean',
        ];
    }

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class, 'task_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
