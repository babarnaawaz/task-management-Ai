<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\UnauthorizedTaskAccessException;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSubtaskRequest;
use App\Http\Requests\UpdateSubtaskRequest;
use App\Http\Resources\SubtaskResource;
use App\Models\Subtask;
use App\Models\Task;
use App\Services\SubtaskService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SubtaskController extends Controller
{
    public function __construct(
        private SubtaskService $subtaskService
    ) {}

    public function index(Request $request, Task $task): JsonResponse
    {
        if ($task->user_id !== $request->user()->id) {
            throw new UnauthorizedTaskAccessException();
        }

        return response()->json([
            'data' => SubtaskResource::collection($task->subtasks),
        ]);
    }

    public function store(StoreSubtaskRequest $request, Task $task): JsonResponse
    {
        $subtask = $this->subtaskService->createSubtask($task, $request->validated());

        return response()->json([
            'message' => 'Subtask created successfully',
            'data' => new SubtaskResource($subtask),
        ], 201);
    }

    public function update(UpdateSubtaskRequest $request, Task $task, Subtask $subtask): JsonResponse
    {
        $updatedSubtask = $this->subtaskService->updateSubtask($subtask, $request->validated());

        return response()->json([
            'message' => 'Subtask updated successfully',
            'data' => new SubtaskResource($updatedSubtask),
        ]);
    }

    public function destroy(Request $request, Task $task, Subtask $subtask): JsonResponse
    {
        if ($subtask->task->user_id !== $request->user()->id) {
            throw new UnauthorizedTaskAccessException();
        }

        $this->subtaskService->deleteSubtask($subtask);

        return response()->json([
            'message' => 'Subtask deleted successfully',
        ]);
    }
}