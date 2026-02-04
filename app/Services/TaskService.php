<?php

namespace App\Services;

use App\Events\TaskCreated;
use App\Events\TaskUpdated;
use App\Models\Task;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class TaskService
{
    /**
     * Create a new service instance.
     */
    public function __construct(
        private SubtaskService $subtaskService
    ) {}

    /**
     * Get all tasks for a user with optional filters.
     */
    public function getAllTasks(User $user, array $filters = []): LengthAwarePaginator
    {
        $query = Task::query()
            ->byUser($user->id)
            ->with(['subtasks', 'breakdown']);

        // Apply status filter
        if (isset($filters['status']) && !empty($filters['status'])) {
            $query->byStatus($filters['status']);
        }

        // Apply priority filter
        if (isset($filters['priority']) && !empty($filters['priority'])) {
            $query->byPriority($filters['priority']);
        }

        // Apply search filter
        if (isset($filters['search']) && !empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('title', 'like', "%{$filters['search']}%")
                  ->orWhere('description', 'like', "%{$filters['search']}%");
            });
        }

        // Pagination
        $perPage = $filters['per_page'] ?? 15;

        return $query->latest()->paginate($perPage);
    }

    /**
     * Create a new task.
     */
    public function createTask(User $user, array $data): Task
    {
        return DB::transaction(function () use ($user, $data) {
            $task = $user->tasks()->create([
                'title' => $data['title'],
                'description' => $data['description'] ?? null,
                'status' => $data['status'] ?? 'pending',
                'priority' => $data['priority'] ?? 'medium',
                'due_date' => $data['due_date'] ?? null,
                'ai_breakdown_requested' => $data['ai_breakdown_requested'] ?? false,
            ]);

            // Dispatch event
            event(new TaskCreated($task));

            return $task->load(['subtasks', 'breakdown']);
        });
    }

    /**
     * Update an existing task.
     */
    public function updateTask(Task $task, array $data): Task
    {
        return DB::transaction(function () use ($task, $data) {
            $task->update($data);

            // Dispatch event
            event(new TaskUpdated($task));

            return $task->fresh(['subtasks', 'breakdown']);
        });
    }

    /**
     * Delete a task and its subtasks.
     */
    public function deleteTask(Task $task): bool
    {
        return DB::transaction(function () use ($task) {
            // Delete all subtasks first
            $task->subtasks()->delete();
            
            // Delete the task
            return $task->delete();
        });
    }

    /**
     * Get a single task by ID for a specific user.
     */
    public function getTask(int $taskId, User $user): ?Task
    {
        return Task::byUser($user->id)
            ->with(['subtasks', 'breakdown', 'user'])
            ->find($taskId);
    }

    /**
     * Get task statistics for a user.
     */
    public function getTaskStatistics(User $user): array
    {
        $query = Task::byUser($user->id);

        return [
            'total' => $query->count(),
            'pending' => (clone $query)->byStatus('pending')->count(),
            'in_progress' => (clone $query)->byStatus('in_progress')->count(),
            'completed' => (clone $query)->byStatus('completed')->count(),
            'cancelled' => (clone $query)->byStatus('cancelled')->count(),
            'with_ai_breakdown' => (clone $query)->where('ai_breakdown_requested', true)->count(),
        ];
    }
}