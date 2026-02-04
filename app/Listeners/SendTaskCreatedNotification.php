<?php

namespace App\Listeners;

use App\Events\TaskCreated;
use App\Notifications\TaskCreatedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class SendTaskCreatedNotification
{

    use InteractsWithQueue;
    
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(TaskCreated $event): void
    {
        $event->task->user->notify(new TaskCreatedNotification($event->task));
    }

    /**
     * Handle a job failure.
     */
    public function failed(TaskCreated $event, \Throwable $exception): void
    {
        Log::error('Failed to send task created notification', [
            'task_id' => $event->task->id,
            'user_id' => $event->task->user_id,
            'error' => $exception->getMessage(),
        ]);
    }
}
