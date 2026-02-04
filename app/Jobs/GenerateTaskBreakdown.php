<?php

namespace App\Jobs;

use App\Events\TaskBreakdownCompleted;
use App\Events\TaskBreakdownFailed;
use App\Models\Task;
use App\Models\TaskBreakdown;
use App\Services\AnthropicService;
use App\Services\SubtaskService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class GenerateTaskBreakdown implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     */
    public int $tries = 3;

    /**
     * The number of seconds the job can run before timing out.
     */
    public int $timeout = 120;

    /**
     * The number of seconds to wait before retrying the job.
     */
    public int $backoff = 10;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public Task $task,
        public array $options = []
    ) {}

    /**
     * Execute the job.
     */
    public function handle(
        AnthropicService $anthropicService,
        SubtaskService $subtaskService
    ): void {
        // Create breakdown record
        $breakdown = TaskBreakdown::create([
            'task_id' => $this->task->id,
            'status' => 'processing',
            'started_at' => now(),
            'ai_prompt' => json_encode($this->options),
        ]);

        try {
            // Check if API is configured
            if (!$anthropicService->isConfigured()) {
                throw new \Exception('Anthropic API key is not configured');
            }

            // Call AI service to break down the task
            $subtasksData = $anthropicService->breakdownTask(
                $this->task->title,
                $this->task->description ?? '',
                $this->options
            );

            Log::info('Task breakdown generated', [
                'task_id' => $this->task->id,
                'subtasks_count' => count($subtasksData),
            ]);

            // Create subtasks with AI flag
            $createdSubtasks = [];
            foreach ($subtasksData as $index => $subtaskData) {
                $createdSubtasks[] = $subtaskService->createSubtask($this->task, [
                    'title' => $subtaskData['title'],
                    'description' => $subtaskData['description'] ?? null,
                    'estimated_hours' => $subtaskData['estimated_hours'] ?? null,
                    'order' => $index,
                    'generated_by_ai' => true,
                ]);
            }

            // Update breakdown record
            $breakdown->update([
                'status' => 'completed',
                'ai_response' => $subtasksData,
                'completed_at' => now(),
            ]);

            // Update task
            $this->task->update([
                'ai_breakdown_completed_at' => now(),
            ]);

            // Dispatch success event
            event(new TaskBreakdownCompleted($this->task->fresh()));

            Log::info('Task breakdown completed successfully', [
                'task_id' => $this->task->id,
                'subtasks_created' => count($createdSubtasks),
            ]);

        } catch (\Exception $e) {
            Log::error('Task breakdown failed', [
                'task_id' => $this->task->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            // Update breakdown record
            $breakdown->update([
                'status' => 'failed',
                'error_message' => $e->getMessage(),
                'completed_at' => now(),
            ]);

            // Dispatch failure event
            event(new TaskBreakdownFailed($this->task, $e->getMessage()));

            // Re-throw to mark job as failed
            throw $e;
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('GenerateTaskBreakdown job failed completely', [
            'task_id' => $this->task->id,
            'error' => $exception->getMessage(),
            'attempts' => $this->attempts(),
        ]);

        // Update breakdown if exists
        $breakdown = $this->task->breakdowns()->latest()->first();
        if ($breakdown) {
            $breakdown->update([
                'status' => 'failed',
                'error_message' => 'Job failed after ' . $this->tries . ' attempts: ' . $exception->getMessage(),
                'completed_at' => now(),
            ]);
        }
    }
}