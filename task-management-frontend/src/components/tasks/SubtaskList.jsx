import { useState } from 'react';
import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import { subtaskApi } from '../../api/tasks';
import { Plus, Check, Clock, Loader2, Edit, Trash2 } from 'lucide-react';
import toast from 'react-hot-toast';

export default function SubtaskList({ taskId }) {
  const queryClient = useQueryClient();
  const [isAdding, setIsAdding] = useState(false);
  const [editingId, setEditingId] = useState(null);
  const [formData, setFormData] = useState({ title: '', description: '', estimated_hours: '' });

  const { data: subtasksData } = useQuery({
    queryKey: ['subtasks', taskId],
    queryFn: () => subtaskApi.getSubtasks(taskId),
    select: (data) => data.data.data,
  });

  const createSubtask = useMutation({
    mutationFn: (data) => subtaskApi.createSubtask(taskId, data),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['subtasks', taskId] });
      queryClient.invalidateQueries({ queryKey: ['task', taskId] });
      queryClient.invalidateQueries({ queryKey: ['tasks'] });
      toast.success('Subtask created!');
      setIsAdding(false);
      setFormData({ title: '', description: '', estimated_hours: '' });
    },
  });

  const updateSubtask = useMutation({
    mutationFn: ({ id, data }) => subtaskApi.updateSubtask(id, data),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['subtasks', taskId] });
      queryClient.invalidateQueries({ queryKey: ['task', taskId] });
      queryClient.invalidateQueries({ queryKey: ['tasks'] });
      toast.success('Subtask updated!');
      setEditingId(null);
    },
  });

  const deleteSubtask = useMutation({
    mutationFn: (id) => subtaskApi.deleteSubtask(id),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['subtasks', taskId] });
      queryClient.invalidateQueries({ queryKey: ['task', taskId] });
      queryClient.invalidateQueries({ queryKey: ['tasks'] });
      toast.success('Subtask deleted!');
    },
  });

  const handleSubmit = (e) => {
    e.preventDefault();
    const data = {
      ...formData,
      estimated_hours: formData.estimated_hours ? parseInt(formData.estimated_hours) : null,
    };

    if (editingId) {
      updateSubtask.mutate({ id: editingId, data });
    } else {
      createSubtask.mutate(data);
    }
  };

  const handleToggleStatus = (subtask) => {
    const newStatus = subtask.status === 'completed' ? 'pending' : 'completed';
    updateSubtask.mutate({ 
      id: subtask.id, 
      data: { status: newStatus } 
    });
  };

  const subtasks = subtasksData || [];

  return (
    <div className="card">
      <div className="flex items-center justify-between mb-6">
        <h2 className="text-xl font-semibold text-gray-900">
          Subtasks ({subtasks.length})
        </h2>
        <button
          onClick={() => setIsAdding(true)}
          className="btn btn-primary flex items-center text-sm"
        >
          <Plus className="w-4 h-4 mr-1" />
          Add Subtask
        </button>
      </div>

      {(isAdding || editingId) && (
        <form onSubmit={handleSubmit} className="mb-6 p-4 bg-gray-50 rounded-lg space-y-3">
          <input
            type="text"
            placeholder="Subtask title"
            required
            className="input"
            value={formData.title}
            onChange={(e) => setFormData({ ...formData, title: e.target.value })}
          />
          <textarea
            placeholder="Description (optional)"
            rows={2}
            className="input"
            value={formData.description}
            onChange={(e) => setFormData({ ...formData, description: e.target.value })}
          />
          <input
            type="number"
            placeholder="Estimated hours (optional)"
            min="1"
            className="input"
            value={formData.estimated_hours}
            onChange={(e) => setFormData({ ...formData, estimated_hours: e.target.value })}
          />
          <div className="flex justify-end space-x-2">
            <button
              type="button"
              onClick={() => {
                setIsAdding(false);
                setEditingId(null);
                setFormData({ title: '', description: '', estimated_hours: '' });
              }}
              className="btn btn-secondary text-sm"
            >
              Cancel
            </button>
            <button type="submit" className="btn btn-primary text-sm">
              {editingId ? 'Update' : 'Add'}
            </button>
          </div>
        </form>
      )}

      {subtasks.length === 0 ? (
        <p className="text-center text-gray-500 py-8">
          No subtasks yet. Add one to break down this task!
        </p>
      ) : (
        <div className="space-y-3">
          {subtasks.map((subtask) => (
            <div
              key={subtask.id}
              className="flex items-start space-x-3 p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors"
            >
              <button
                onClick={() => handleToggleStatus(subtask)}
                className="flex-shrink-0 mt-1"
              >
                {subtask.status === 'completed' ? (
                  <Check className="w-5 h-5 text-green-600" />
                ) : (
                  <div className="w-5 h-5 border-2 border-gray-300 rounded" />
                )}
              </button>

              <div className="flex-1 min-w-0">
                <div className="flex items-start justify-between">
                  <h4
                    className={`font-medium ${
                      subtask.status === 'completed'
                        ? 'text-gray-400 line-through'
                        : 'text-gray-900'
                    }`}
                  >
                    {subtask.title}
                  </h4>
                  <div className="flex items-center space-x-2 ml-2">
                    {subtask.generated_by_ai && (
                      <span className="text-xs text-purple-600 flex items-center">
                        <Sparkles className="w-3 h-3" />
                      </span>
                    )}
                    <button
                      onClick={() => {
                        setEditingId(subtask.id);
                        setFormData({
                          title: subtask.title,
                          description: subtask.description || '',
                          estimated_hours: subtask.estimated_hours || '',
                        });
                        setIsAdding(false);
                      }}
                      className="text-gray-400 hover:text-gray-600"
                    >
                      <Edit className="w-4 h-4" />
                    </button>
                    <button
                      onClick={() => {
                        if (confirm('Delete this subtask?')) {
                          deleteSubtask.mutate(subtask.id);
                        }
                      }}
                      className="text-gray-400 hover:text-red-600"
                    >
                      <Trash2 className="w-4 h-4" />
                    </button>
                  </div>
                </div>

                {subtask.description && (
                  <p className="text-sm text-gray-600 mt-1">{subtask.description}</p>
                )}

                {subtask.estimated_hours && (
                  <div className="flex items-center text-xs text-gray-500 mt-2">
                    <Clock className="w-3 h-3 mr-1" />
                    {subtask.estimated_hours}h estimated
                  </div>
                )}
              </div>
            </div>
          ))}
        </div>
      )}
    </div>
  );
}