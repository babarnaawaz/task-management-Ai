<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'status' => $this->status,
            'priority' => $this->priority,
            'due_date' => $this->due_date?->toISOString(),
            'ai_breakdown_requested' => $this->ai_breakdown_requested,
            'ai_breakdown_completed_at' => $this->ai_breakdown_completed_at?->toISOString(),
            'user' => new UserResource($this->whenLoaded('user')),
            'subtasks' => SubtaskResource::collection($this->whenLoaded('subtasks')),
            'subtasks_count' => $this->when($this->relationLoaded('subtasks'), function () {
                return $this->subtasks->count();
            }),
            'completed_subtasks_count' => $this->when($this->relationLoaded('subtasks'), function () {
                return $this->subtasks->where('status', 'completed')->count();
            }),
            'breakdown' => new TaskBreakdownResource($this->whenLoaded('breakdown')),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}