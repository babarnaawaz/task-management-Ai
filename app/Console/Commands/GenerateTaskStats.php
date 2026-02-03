<?php

namespace App\Console\Commands;

use App\Models\Task;
use App\Models\User;
use Illuminate\Console\Command;

class GenerateTaskStats extends Command
{
    protected $signature = 'tasks:stats {--user= : Specific user ID}';
    
    protected $description = 'Generate task statistics';

    public function handle(): int
    {
        $userId = $this->option('user');

        $query = Task::query();

        if ($userId) {
            $query->where('user_id', $userId);
            $user = User::find($userId);
            $this->info("Statistics for user: {$user->name}");
        } else {
            $this->info("Global task statistics:");
        }

        $this->newLine();

        $total = $query->count();
        $pending = (clone $query)->where('status', 'pending')->count();
        $inProgress = (clone $query)->where('status', 'in_progress')->count();
        $completed = (clone $query)->where('status', 'completed')->count();
        $cancelled = (clone $query)->where('status', 'cancelled')->count();
        $withAi = (clone $query)->where('ai_breakdown_requested', true)->count();

        $this->table(
            ['Metric', 'Count'],
            [
                ['Total Tasks', $total],
                ['Pending', $pending],
                ['In Progress', $inProgress],
                ['Completed', $completed],
                ['Cancelled', $cancelled],
                ['AI Breakdown Requested', $withAi],
            ]
        );

        return Command::SUCCESS;
    }
}
