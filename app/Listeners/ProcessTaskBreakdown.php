<?php

namespace App\Listeners;

use App\Events\TaskCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use App\Jobs\GenerateTaskBreakdown;

class ProcessTaskBreakdown
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
        if ($event->task->ai_breakdown_requested) {
            GenerateTaskBreakdown::dispatch($event->task);
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(TaskCreated $event, \Throwable $exception): void
    {
        Log::error('Failed to process task breakdown', [
            'task_id' => $event->task->id,
            'error' => $exception->getMessage(),
        ]);
    }
}
