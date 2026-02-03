import api from './axios';

export const taskApi = {
  getTasks: (params) => api.get('/tasks', { params }),
  getTask: (id) => api.get(`/tasks/${id}`),
  createTask: (data) => api.post('/tasks', data),
  updateTask: (id, data) => api.put(`/tasks/${id}`, data),
  deleteTask: (id) => api.delete(`/tasks/${id}`),
  requestBreakdown: (id, data) => api.post(`/tasks/${id}/breakdown`, data),
};

export const subtaskApi = {
  getSubtasks: (taskId) => api.get(`/tasks/${taskId}/subtasks`),
  createSubtask: (taskId, data) => api.post(`/tasks/${taskId}/subtasks`, data),
  updateSubtask: (subtaskId, data) => api.put(`/subtasks/${subtaskId}`, data),
  deleteSubtask: (subtaskId) => api.delete(`/subtasks/${subtaskId}`),
};