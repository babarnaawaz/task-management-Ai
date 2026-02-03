<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskBreakdownRequest extends FormRequest
{
    public function authorize(): bool
    {
        $task = $this->route('task');
        return $task && $this->user()->id === $task->user_id;
    }

    public function rules(): array
    {
        return [
            'complexity_level' => 'nullable|in:simple,moderate,complex',
            'focus_areas' => 'nullable|array',
            'focus_areas.*' => 'string',
        ];
    }
}