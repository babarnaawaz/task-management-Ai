import { useState } from 'react';
import { useParams, useNavigate } from 'react-router-dom';
import { useTask, useDeleteTask, useRequestBreakdown } from '../../hooks/useTasks';
import SubtaskList from './SubtaskList';
import TaskFormModal from './TaskFormModal';
import LoadingSpinner from '../common/LoadingSpinner';
import { 
  ArrowLeft, 
  Edit, 
  Trash2, 
  Calendar, 
  Sparkles,
  AlertCircle 
} from 'lucide-react';
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

export default function TaskDetail() {
  const { id } = useParams();
  const navigate = useNavigate();
  const { data: task, isLoading } = useTask(id);
  const deleteTask = useDeleteTask();
  const requestBreakdown = useRequestBreakdown();
  const [isEditModalOpen, setIsEditModalOpen] = useState(false);
  const [showDeleteConfirm, setShowDeleteConfirm] = useState(false);

  if (isLoading) return <LoadingSpinner />;
  if (!task) return <div>Task not found</div>;

  const handleDelete = async () => {
    await deleteTask.mutateAsync(task.id);
    navigate('/');
  };

  const handleRequestBreakdown = async () => {
    await requestBreakdown.mutateAsync({ 
      id: task.id, 
      data: { complexity_level: 'moderate' } 
    });
  };

  const canRequestBreakdown = 
    !task.ai_breakdown_requested && 
    !task.ai_breakdown_completed_at &&
    task.status !== 'completed';

  return (
    <div className="space-y-6">
      <div className="flex items-center justify-between">
        <button
          onClick={() => navigate('/')}
          className="flex items-center text-gray-600 hover:text-gray-900"
        >
          <ArrowLeft className="w-4 h-4 mr-2" />
          Back to tasks
        </button>

        <div className="flex items-center space-x-3">
          {canRequestBreakdown && (
            <button
              onClick={handleRequestBreakdown}
              disabled={requestBreakdown.isPending}
              className="btn bg-purple-600 text-white hover:bg-purple-700 flex items-center"
            >
              <Sparkles className="w-4 h-4 mr-2" />
              AI Breakdown
            </button>
          )}
          <button
            onClick={() => setIsEditModalOpen(true)}
            className="btn btn-secondary flex items-center"
          >
            <Edit className="w-4 h-4 mr-2" />
            Edit
          </button>
          <button
            onClick={() => setShowDeleteConfirm(true)}
            className="btn btn-danger flex items-center"
          >
            <Trash2 className="w-4 h-4 mr-2" />
            Delete
          </button>
        </div>
      </div>

      <div className="card">
        <div className="space-y-6">
          <div>
            <div className="flex items-start justify-between mb-4">
              <h1 className="text-3xl font-bold text-gray-900">{task.title}</h1>
              {task.ai_breakdown_completed_at && (
                <div className="flex items-center text-purple-600 text-sm">
                  <Sparkles className="w-4 h-4 mr-1" />
                  AI Enhanced
                </div>
              )}
            </div>

            <div className="flex flex-wrap gap-2 mb-4">
              <span className={`px-3 py-1 rounded-full text-sm font-medium ${statusColors[task.status]}`}>
                {task.status.replace('_', ' ')}
              </span>
              <span className={`px-3 py-1 rounded-full text-sm font-medium ${priorityColors[task.priority]}`}>
                {task.priority} priority
              </span>
            </div>

            {task.due_date && (
              <div className="flex items-center text-gray-600 mb-4">
                <Calendar className="w-4 h-4 mr-2" />
                Due: {format(new Date(task.due_date), 'MMMM dd, yyyy')}
              </div>
            )}

            {task.description && (
              <div className="mt-4">
                <h3 className="text-sm font-medium text-gray-700 mb-2">Description</h3>
                <p className="text-gray-600 whitespace-pre-wrap">{task.description}</p>
              </div>
            )}
          </div>

          {task.ai_breakdown_requested && !task.ai_breakdown_completed_at && (
            <div className="flex items-start space-x-3 p-4 bg-blue-50 border border-blue-200 rounded-lg">
              <AlertCircle className="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" />
              <div>
                <h4 className="text-sm font-medium text-blue-900">
                  AI Breakdown in Progress
                </h4>
                <p className="text-sm text-blue-700 mt-1">
                  Claude is analyzing your task and generating subtasks. You'll be notified when complete.
                </p>
              </div>
            </div>
          )}
        </div>
      </div>

      <SubtaskList taskId={task.id} />

      <TaskFormModal
        isOpen={isEditModalOpen}
        onClose={() => setIsEditModalOpen(false)}
        task={task}
      />

      {showDeleteConfirm && (
        <div className="fixed inset-0 z-50 overflow-y-auto">
          <div className="flex min-h-screen items-center justify-center p-4">
            <div className="fixed inset-0 bg-black bg-opacity-50" onClick={() => setShowDeleteConfirm(false)} />
            <div className="relative bg-white rounded-lg p-6 max-w-sm w-full">
              <h3 className="text-lg font-semibold mb-2">Delete Task?</h3>
              <p className="text-gray-600 mb-4">
                This will permanently delete the task and all its subtasks. This action cannot be undone.
              </p>
              <div className="flex justify-end space-x-3">
                <button onClick={() => setShowDeleteConfirm(false)} className="btn btn-secondary">
                  Cancel
                </button>
                <button onClick={handleDelete} className="btn btn-danger">
                  Delete
                </button>
              </div>
            </div>
          </div>
        </div>
      )}
    </div>
  );
}