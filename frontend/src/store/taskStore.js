import { defineStore } from 'pinia';

export const useTaskStore = defineStore('tasks', {
    state: () => ({
        tasks: [
            {
                id: 1,
                title: 'Prepare project kickoff',
                description: 'Gather requirements and prepare for project kickoff meeting.',
                priority: 'high',
                status: 'pending',
                dueDate: '2025-06-30',
            },
            {
                id: 2,
                title: 'Write unit tests',
                description: 'Add tests for the user authentication module.',
                priority: 'medium',
                status: 'completed',
                dueDate: '2025-06-28',
            },
            {
                id: 3,
                title: 'Deploy staging build',
                description: 'Push the latest code to staging environment.',
                priority: 'low',
                status: 'pending',
                dueDate: '2025-07-01',
            },
        ],
        currentUser: {
            name: 'John Doe',
            isAdmin: true,
        },
    }),
});
