<?php

namespace App\Notifications;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TaskBreakdownCompletedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Task $task
    ) {}

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $subtasksCount = $this->task->subtasks()->count();

        return (new MailMessage)
            ->subject('AI Task Breakdown Completed: ' . $this->task->title)
            ->line('Your task has been broken down into subtasks by AI.')
            ->line('Task: ' . $this->task->title)
            ->line('Subtasks generated: ' . $subtasksCount)
            ->action('View Task', url('/tasks/' . $this->task->id))
            ->line('You can now review and adjust the subtasks as needed.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'task_id' => $this->task->id,
            'title' => $this->task->title,
            'subtasks_count' => $this->task->subtasks()->count(),
            'message' => 'AI task breakdown completed',
        ];
    }
}