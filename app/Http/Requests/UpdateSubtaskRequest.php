<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSubtaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        $subtask = $this->route('subtask');
        return $subtask && $this->user()->id === $subtask->task->user_id;
    }

    public function rules(): array
    {
        return [
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'status' => 'sometimes|in:pending,in_progress,completed',
            'order' => 'sometimes|integer|min:0',
            'estimated_hours' => 'nullable|integer|min:1',
        ];
    }
}