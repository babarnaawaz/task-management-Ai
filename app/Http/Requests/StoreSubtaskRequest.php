<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSubtaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        $task = $this->route('task');
        return $task && $this->user()->id === $task->user_id;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'nullable|in:pending,in_progress,completed',
            'order' => 'nullable|integer|min:0',
            'estimated_hours' => 'nullable|integer|min:1',
        ];
    }
}