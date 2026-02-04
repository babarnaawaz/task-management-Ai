<?php

namespace App\Services;

use App\Models\Subtask;
use App\Models\Task;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class SubtaskService
{
    /**
     * Create a new subtask for a task.
     */
    public function createSubtask(Task $task, array $data): Subtask
    {
        // Auto-increment order if not provided
        $order = $data['order'] ?? ($task->subtasks()->max('order') ?? 0) + 1;

        return $task->subtasks()->create([
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'status' => $data['status'] ?? 'pending',
            'order' => $order,
            'estimated_hours' => $data['estimated_hours'] ?? null,
            'generated_by_ai' => $data['generated_by_ai'] ?? false,
        ]);
    }

    /**
     * Update an existing subtask.
     */
    public function updateSubtask(Subtask $subtask, array $data): Subtask
    {
        $subtask->update($data);
        return $subtask->fresh();
    }

    /**
     * Delete a subtask.
     */
    public function deleteSubtask(Subtask $subtask): bool
    {
        return $subtask->delete();
    }

    /**
     * Create multiple subtasks at once.
     */
    public function createMultipleSubtasks(Task $task, array $subtasksData): Collection
    {
        return DB::transaction(function () use ($task, $subtasksData) {
            $subtasks = collect();
            
            foreach ($subtasksData as $index => $data) {
                $subtasks->push($this->createSubtask($task, [
                    ...$data,
                    'order' => $data['order'] ?? $index,
                ]));
            }

            return $subtasks;
        });
    }

    /**
     * Reorder subtasks for a task.
     */
    public function reorderSubtasks(Task $task, array $orderMap): bool
    {
        return DB::transaction(function () use ($task, $orderMap) {
            foreach ($orderMap as $subtaskId => $order) {
                $task->subtasks()
                    ->where('id', $subtaskId)
                    ->update(['order' => $order]);
            }
            return true;
        });
    }

    /**
     * Get completion percentage for a task.
     */
    public function getCompletionPercentage(Task $task): float
    {
        $total = $task->subtasks()->count();
        
        if ($total === 0) {
            return 0.0;
        }

        $completed = $task->subtasks()->completed()->count();
        
        return round(($completed / $total) * 100, 2);
    }
}