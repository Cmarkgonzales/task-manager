import { defineStore } from 'pinia';

export const useTaskStore = defineStore('taskStore', {
    state: () => ({
        tasks: [
            {
                id: 1,
                title: 'Finish Vue Project',
                description: 'Complete all modules and push to repo',
                priority: 'High',
                status: 'pending',
                dueDate: '2025-07-01'
            },
            {
                id: 2,
                title: 'Review Design',
                description: 'Go over Figma designs with team',
                priority: 'Medium',
                status: 'completed',
                dueDate: '2025-07-03'
            },
            {
                id: 3,
                title: 'Write Docs',
                description: 'Document all API endpoints',
                priority: 'Low',
                status: 'pending',
                dueDate: '2025-07-05'
            }
        ]
    }),

    getters: {
        getTaskById: (state) => (id) => state.tasks.find(t => t.id === id),

        userTasks: (state) => (userId) => {
            const user = state.users.find(u => u.id === userId);
            return user ? state.tasks.filter(t => user.tasks.includes(t.id)) : [];
        }
    },

    actions: {
        addTask(task) {
            this.tasks.push({ ...task, id: Date.now() });
        },

        deleteTask(id) {
            this.tasks = this.tasks.filter(t => t.id !== id);
        },

        updateTask(updatedTask) {
            const index = this.tasks.findIndex(t => t.id === updatedTask.id);
            if (index !== -1) this.tasks[index] = updatedTask;
        },

        reorderTasks(newTasks) {
            this.tasks = newTasks;
        },

        toggleTaskStatus(id) {
            const task = this.tasks.find(t => t.id === id);
            if (task) {
                task.status = task.status === 'completed' ? 'pending' : 'completed';
            }
        }
    },
});
