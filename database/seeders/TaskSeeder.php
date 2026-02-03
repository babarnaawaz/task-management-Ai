<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();

        foreach ($users as $user) {
            // Create some sample tasks
            Task::create([
                'user_id' => $user->id,
                'title' => 'Build authentication system',
                'description' => 'Implement user registration, login, and password reset',
                'status' => 'in_progress',
                'priority' => 'high',
                'due_date' => now()->addDays(7),
            ]);

            Task::create([
                'user_id' => $user->id,
                'title' => 'Design database schema',
                'description' => 'Create ERD and migrations for the application',
                'status' => 'completed',
                'priority' => 'medium',
            ]);

            Task::create([
                'user_id' => $user->id,
                'title' => 'Write API documentation',
                'description' => 'Document all API endpoints with examples',
                'status' => 'pending',
                'priority' => 'low',
                'due_date' => now()->addDays(14),
            ]);
        }
    }
}