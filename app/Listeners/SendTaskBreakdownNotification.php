<?php

namespace App\Listeners;

use App\Events\TaskBreakdownCompleted;
use App\Notifications\TaskBreakdownCompletedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class SendTaskBreakdownNotification
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
    public function handle(TaskBreakdownCompleted $event): void
    {
        $event->task->user->notify(
            new TaskBreakdownCompletedNotification($event->task)
        );
    }

    /**
     * Handle a job failure.
     */
    public function failed(TaskBreakdownCompleted $event, \Throwable $exception): void
    {
        Log::error('Failed to send breakdown completion notification', [
            'task_id' => $event->task->id,
            'user_id' => $event->task->user_id,
            'error' => $exception->getMessage(),
        ]);
    }
}
