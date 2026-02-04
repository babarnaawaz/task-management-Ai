<x-mail::message>
# New Task Assigned

Hello {{ $task->user->name }},

You have been assigned a new task by {{ $assignedBy->name }}.

**Task Details:**
- **Title:** {{ $task->title }}
- **Priority:** {{ ucfirst($task->priority) }}
- **Status:** {{ ucfirst(str_replace('_', ' ', $task->status)) }}
@if($task->due_date)
- **Due Date:** {{ $task->due_date->format('F j, Y') }}
@endif

@if($task->description)
**Description:**
{{ $task->description }}
@endif

<x-mail::button :url="$url">
View Task
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>