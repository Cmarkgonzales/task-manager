// store/taskStore.js
import { defineStore } from 'pinia';
import api from '@/lib/axios';

export const useTaskStore = defineStore('taskStore', {
    state: () => ({
        tasks: [],
        isFetchingTasks: false,
        error: null
    }),

    getters: {
        getTaskById: (state) => (id) => state.tasks.find(t => t.id === id),
    },

    actions: {
        async fetchTasks(filters = {}) {
            this.isFetchingTasks = true;
            try {
                const res = await api.get('/api/tasks', { params: filters });
                this.tasks = res.data.data;
            } catch (e) {
                console.error('fetchTasks', e);
                this.error = e?.response?.data?.message || 'Failed to load tasks';
            } finally {
                this.isFetchingTasks = false;
            }
        },

        async addTask(newTask) {
            try {
                const res = await api.post('/api/tasks', newTask);
                this.tasks.push(res.data.data);
            } catch (e) {
                console.error('addTask', e);
                throw e;
            }
        },

        async updateTask(updatedTask) {
            try {
                const res = await api.put(`/api/tasks/${updatedTask.id}`, updatedTask);
                this.tasks = this.tasks.map(t => (t.id === updatedTask.id ? res.data.data : t));
            } catch (e) {
                console.error('updateTask', e);
                throw e;
            }
        },

        async deleteTask(id) {
            try {
                await api.delete(`/api/tasks/${id}`);
                this.tasks = this.tasks.filter(t => t.id !== id);
            } catch (e) {
                console.error('deleteTask', e);
                throw e;
            }
        },

        async toggleTaskStatus(id) {
            try {
                const res = await api.patch(`/api/tasks/${id}/status`);
                this.tasks = this.tasks.map(task => (task.id === id ? res.data.data : task));
            } catch (e) {
                console.error('toggleTaskStatus', e);
                throw e;
            }
        },
    }
});
