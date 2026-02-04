<?php

namespace App\Helpers;

use App\Models\Task;

class TaskHelper
{
    /**
     * Get priority color for UI.
     */
    public static function getPriorityColor(string $priority): string
    {
        return match($priority) {
            'low' => 'blue',
            'medium' => 'yellow',
            'high' => 'orange',
            'urgent' => 'red',
            default => 'gray',
        };
    }

    /**
     * Get status color for UI.
     */
    public static function getStatusColor(string $status): string
    {
        return match($status) {
            'pending' => 'gray',
            'in_progress' => 'blue',
            'completed' => 'green',
            'cancelled' => 'red',
            default => 'gray',
        };
    }

    /**
     * Calculate task completion percentage.
     */
    public static function getCompletionPercentage(Task $task): int
    {
        $total = $task->subtasks()->count();
        
        if ($total === 0) {
            return 0;
        }

        $completed = $task->subtasks()->where('status', 'completed')->count();
        
        return (int) round(($completed / $total) * 100);
    }

    /**
     * Get human-readable priority label.
     */
    public static function getPriorityLabel(string $priority): string
    {
        return ucfirst($priority);
    }

    /**
     * Get human-readable status label.
     */
    public static function getStatusLabel(string $status): string
    {
        return ucfirst(str_replace('_', ' ', $status));
    }
}