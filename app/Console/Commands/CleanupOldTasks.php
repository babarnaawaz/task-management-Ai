<?php

namespace App\Console\Commands;

use App\Models\Task;
use Illuminate\Console\Command;

class CleanupOldTasks extends Command
{
    protected $signature = 'tasks:cleanup {--days=90 : Number of days to keep completed tasks}';

    protected $description = 'Clean up old completed and cancelled tasks';

    public function handle(): int
    {
        $days = (int) $this->option('days');
        $date = now()->subDays($days);

        $this->info("Cleaning up tasks completed or cancelled before {$date->toDateString()}...");

        $count = Task::whereIn('status', ['completed', 'cancelled'])
            ->where('updated_at', '<', $date)
            ->delete();

        $this->info("Deleted {$count} old tasks.");

        return Command::SUCCESS;
    }
}
