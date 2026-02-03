<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\UnauthorizedTaskAccessException;
use App\Http\Controllers\Controller;
use App\Http\Requests\TaskBreakdownRequest;
use App\Jobs\GenerateTaskBreakdown;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TaskBreakdownController extends Controller
{
    public function store(TaskBreakdownRequest $request, Task $task): JsonResponse
    {
        if ($task->user_id !== $request->user()->id) {
            throw new UnauthorizedTaskAccessException();
        }

        GenerateTaskBreakdown::dispatch($task, $request->validated());

        $task->update(['ai_breakdown_requested' => true]);

        return response()->json([
            'message' => 'Task breakdown request submitted. You will be notified when complete.',
            'task_id' => $task->id,
        ], 202);
    }
}