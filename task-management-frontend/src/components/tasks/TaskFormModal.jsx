import { useState, useEffect } from 'react';
import Modal from '../common/Modal';
import { useCreateTask, useUpdateTask } from '../../hooks/useTasks';
import { Loader2, Sparkles } from 'lucide-react';

export default function TaskFormModal({ isOpen, onClose, task = null }) {
  const isEditing = !!task;
  const createTask = useCreateTask();
  const updateTask = useUpdateTask();

  const [formData, setFormData] = useState({
    title: '',
    description: '',
    status: 'pending',
    priority: 'medium',
    due_date: '',
    ai_breakdown_requested: false,
  });

  useEffect(() => {
    if (task) {
      setFormData({
        title: task.title || '',
        description: task.description || '',
        status: task.status || 'pending',
        priority: task.priority || 'medium',
        due_date: task.due_date ? task.due_date.split('T')[0] : '',
        ai_breakdown_requested: task.ai_breakdown_requested || false,
      });
    } else {
      setFormData({
        title: '',
        description: '',
        status: 'pending',
        priority: 'medium',
        due_date: '',
        ai_breakdown_requested: false,
      });
    }
  }, [task]);

  const handleSubmit = async (e) => {
    e.preventDefault();

    const submitData = {
      ...formData,
      due_date: formData.due_date || null,
    };

    if (isEditing) {
      await updateTask.mutateAsync({ id: task.id, data: submitData });
    } else {
      await createTask.mutateAsync(submitData);
    }

    onClose();
  };

  const loading = createTask.isPending || updateTask.isPending;

  return (
    <Modal
      isOpen={isOpen}
      onClose={onClose}
      title={isEditing ? 'Edit Task' : 'Create New Task'}
    >
      <form onSubmit={handleSubmit} className="space-y-4">
        <div>
          <label className="label">Title *</label>
          <input
            type="text"
            required
            className="input"
            value={formData.title}
            onChange={(e) => setFormData({ ...formData, title: e.target.value })}
          />
        </div>

        <div>
          <label className="label">Description</label>
          <textarea
            rows={4}
            className="input"
            value={formData.description}
            onChange={(e) => setFormData({ ...formData, description: e.target.value })}
          />
        </div>

        <div className="grid grid-cols-2 gap-4">
          <div>
            <label className="label">Status</label>
            <select
              className="input"
              value={formData.status}
              onChange={(e) => setFormData({ ...formData, status: e.target.value })}
            >
              <option value="pending">Pending</option>
              <option value="in_progress">In Progress</option>
              <option value="completed">Completed</option>
              <option value="cancelled">Cancelled</option>
            </select>
          </div>

          <div>
            <label className="label">Priority</label>
            <select
              className="input"
              value={formData.priority}
              onChange={(e) => setFormData({ ...formData, priority: e.target.value })}
            >
              <option value="low">Low</option>
              <option value="medium">Medium</option>
              <option value="high">High</option>
              <option value="urgent">Urgent</option>
            </select>
          </div>
        </div>

        <div>
          <label className="label">Due Date</label>
          <input
            type="date"
            className="input"
            value={formData.due_date}
            onChange={(e) => setFormData({ ...formData, due_date: e.target.value })}
          />
        </div>

        {!isEditing && (
          <div className="flex items-center space-x-2 p-4 bg-purple-50 rounded-lg">
            <input
              type="checkbox"
              id="ai_breakdown"
              checked={formData.ai_breakdown_requested}
              onChange={(e) =>
                setFormData({ ...formData, ai_breakdown_requested: e.target.checked })
              }
              className="w-4 h-4 text-primary-600"
            />
            <label htmlFor="ai_breakdown" className="flex items-center text-sm text-gray-700">
              <Sparkles className="w-4 h-4 mr-2 text-purple-600" />
              Request AI-powered task breakdown
            </label>
          </div>
        )}

        <div className="flex justify-end space-x-3 pt-4">
          <button type="button" onClick={onClose} className="btn btn-secondary">
            Cancel
          </button>
          <button
            type="submit"
            disabled={loading}
            className="btn btn-primary flex items-center"
          >
            {loading ? (
              <>
                <Loader2 className="w-4 h-4 mr-2 animate-spin" />
                {isEditing ? 'Updating...' : 'Creating...'}
              </>
            ) : isEditing ? (
              'Update Task'
            ) : (
              'Create Task'
            )}
          </button>
        </div>
      </form>
    </Modal>
  );
}