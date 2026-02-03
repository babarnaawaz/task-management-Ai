import { Link } from 'react-router-dom';
import { Calendar, Clock, Sparkles, CheckCircle2 } from 'lucide-react';
import { format } from 'date-fns';

const priorityColors = {
  low: 'bg-blue-100 text-blue-800',
  medium: 'bg-yellow-100 text-yellow-800',
  high: 'bg-orange-100 text-orange-800',
  urgent: 'bg-red-100 text-red-800',
};

const statusColors = {
  pending: 'bg-gray-100 text-gray-800',
  in_progress: 'bg-blue-100 text-blue-800',
  completed: 'bg-green-100 text-green-800',
  cancelled: 'bg-red-100 text-red-800',
};

export default function TaskCard({ task }) {
  const completionPercentage = task.subtasks_count
    ? Math.round((task.completed_subtasks_count / task.subtasks_count) * 100)
    : 0;

  return (
    <Link to={`/tasks/${task.id}`} className="card hover:shadow-md transition-shadow">
      <div className="space-y-4">
        <div className="flex items-start justify-between">
          <h3 className="text-lg font-semibold text-gray-900 line-clamp-2">
            {task.title}
          </h3>
          {task.ai_breakdown_completed_at && (
            <Sparkles className="w-5 h-5 text-purple-500 flex-shrink-0" />
          )}
        </div>

        {task.description && (
          <p className="text-sm text-gray-600 line-clamp-2">{task.description}</p>
        )}

        <div className="flex flex-wrap gap-2">
          <span className={`px-2 py-1 rounded-full text-xs font-medium ${statusColors[task.status]}`}>
            {task.status.replace('_', ' ')}
          </span>
          <span className={`px-2 py-1 rounded-full text-xs font-medium ${priorityColors[task.priority]}`}>
            {task.priority}
          </span>
        </div>

        {task.due_date && (
          <div className="flex items-center text-sm text-gray-500">
            <Calendar className="w-4 h-4 mr-2" />
            {format(new Date(task.due_date), 'MMM dd, yyyy')}
          </div>
        )}

        {task.subtasks_count > 0 && (
          <div className="space-y-2">
            <div className="flex items-center justify-between text-sm">
              <div className="flex items-center text-gray-600">
                <CheckCircle2 className="w-4 h-4 mr-1" />
                <span>
                  {task.completed_subtasks_count}/{task.subtasks_count} subtasks
                </span>
              </div>
              <span className="text-primary-600 font-medium">
                {completionPercentage}%
              </span>
            </div>
            <div className="w-full bg-gray-200 rounded-full h-2">
              <div
                className="bg-primary-600 h-2 rounded-full transition-all"
                style={{ width: `${completionPercentage}%` }}
              />
            </div>
          </div>
        )}
      </div>
    </Link>
  );
}