<?php

namespace App\Observers;

use App\Models\Task;
use Illuminate\Support\Facades\Log;

class TaskObserver
{
    /**
     * Handle the Task "creating" event.
     */
    public function creating(Task $task): void
    {
        Log::info('Creating new task', [
            'title' => $task->title,
            'user_id' => $task->user_id,
        ]);
    }

    /**
     * Handle the Task "created" event.
     */
    public function created(Task $task): void
    {
        Log::info('Task created', [
            'task_id' => $task->id,
            'title' => $task->title,
        ]);
    }

    /**
     * Handle the Task "updated" event.
     */
    public function updated(Task $task): void
    {
        // Log status changes
        if ($task->isDirty('status')) {
            Log::info('Task status changed', [
                'task_id' => $task->id,
                'old_status' => $task->getOriginal('status'),
                'new_status' => $task->status,
            ]);
        }
    }

    /**
     * Handle the Task "deleted" event.
     */
    public function deleted(Task $task): void
    {
        Log::info('Task deleted', [
            'task_id' => $task->id,
            'title' => $task->title,
        ]);
    }

    /**
     * Handle the Task "restored" event.
     */
    public function restored(Task $task): void
    {
        Log::info('Task restored', [
            'task_id' => $task->id,
        ]);
    }

    /**
     * Handle the Task "force deleted" event.
     */
    public function forceDeleted(Task $task): void
    {
        Log::warning('Task force deleted', [
            'task_id' => $task->id,
            'title' => $task->title,
        ]);
    }
}