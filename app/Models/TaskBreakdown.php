<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskBreakdown extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_id',
        'status',
        'ai_prompt',
        'ai_response',
        'error_message',
        'started_at',
        'completed_at',
    ];

    protected $casts = [
        'ai_response' => 'array',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function task()
    {
        return $this->belongsTo(Task::class);
    }
}