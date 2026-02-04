<?php

namespace Tests\Unit;

use App\Models\Task;
use App\Models\User;
use App\Services\SubtaskService;
use App\Services\TaskService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskServiceTest extends TestCase
{
    use RefreshDatabase;

    private TaskService $taskService;
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = User::factory()->create();
        $this->taskService = new TaskService(new SubtaskService());
    }

    public function test_can_create_task(): void
    {
        $data = [
            'title' => 'Test Task',
            'description' => 'Test Description',
            'priority' => 'high',
        ];

        $task = $this->taskService->createTask($this->user, $data);

        $this->assertInstanceOf(Task::class, $task);
        $this->assertEquals('Test Task', $task->title);
        $this->assertEquals($this->user->id, $task->user_id);
    }

    public function test_can_get_all_tasks_for_user(): void
    {
        Task::factory()->count(5)->create(['user_id' => $this->user->id]);
        Task::factory()->count(3)->create(); // Other user's tasks

        $tasks = $this->taskService->getAllTasks($this->user);

        $this->assertEquals(5, $tasks->total());
    }

    public function test_can_filter_tasks_by_status(): void
    {
        Task::factory()->create([
            'user_id' => $this->user->id,
            'status' => 'pending',
        ]);
        
        Task::factory()->create([
            'user_id' => $this->user->id,
            'status' => 'completed',
        ]);

        $tasks = $this->taskService->getAllTasks($this->user, ['status' => 'pending']);

        $this->assertEquals(1, $tasks->total());
        $this->assertEquals('pending', $tasks->first()->status);
    }

    public function test_can_update_task(): void
    {
        $task = Task::factory()->create(['user_id' => $this->user->id]);

        $updated = $this->taskService->updateTask($task, [
            'title' => 'Updated Title',
            'status' => 'completed',
        ]);

        $this->assertEquals('Updated Title', $updated->title);
        $this->assertEquals('completed', $updated->status);
    }

    public function test_can_delete_task(): void
    {
        $task = Task::factory()->create(['user_id' => $this->user->id]);

        $result = $this->taskService->deleteTask($task);

        $this->assertTrue($result);
        $this->assertSoftDeleted('tasks', ['id' => $task->id]);
    }
}