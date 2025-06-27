<template>
    <Page>
        <div id="admin-view" class="animate-fade-in">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate mb-6">
                Admin Dashboard
            </h2>

            <!-- User Management -->
            <div class="bg-white rounded-lg shadow p-4 mb-8">
                <div class="mb-4 border-b pb-4">
                    <h3 class="text-lg font-medium text-gray-900">
                        User Management
                    </h3>
                    <p class="text-sm text-gray-500">
                        View and manage all users in the system.
                    </p>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">
                                    Name
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">
                                    Email
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">
                                    Role
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">
                                    Tasks
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">
                                    Status
                                </th>
                                <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr
                                v-for="user in users"
                                :key="user.id"
                                class="hover:bg-gray-50 transition"
                            >
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                    {{ user.name }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    {{ user.email }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500 capitalize">
                                    {{ user.role }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    {{ user.tasks.length }}
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <span
                                        :class="user.active ? 'text-green-600' : 'text-red-600'"
                                    >
                                        {{ user.active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right text-sm">
                                    <button
                                        class="text-primary hover:text-primary-dark"
                                        @click="viewUserTasks(user)"
                                    >
                                        View Tasks
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div
                v-if="selectedUser"
                class="bg-white rounded-lg shadow p-4"
            >
                <div class="flex justify-between items-center border-b pb-4 mb-4">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900">
                            Tasks for {{ selectedUser.name }}
                        </h3>
                        <p class="text-sm text-gray-500">
                            {{ selectedUser.tasks.length }} tasks assigned
                        </p>
                    </div>
                    <button
                        class="text-text-light p-2 hover:text-text"
                        @click="selectedUser = null"
                    >
                        <Icon name="XMark" custom-class="h-5 w-5" />
                    </button>
                </div>
                <TaskList
                    :tasks="selectedUser.tasks"
                    :current-user="{ isAdmin: true }"
                    :allow-drag="false"
                />
            </div>
        </div>
    </Page>
</template>

<script setup>
    import Page from '@/components/Page.vue';
    import TaskList from '@/components/TaskList.vue';
    import Icon from '@/components/Icon.vue';
    import { ref } from 'vue';

    // dummy data
    const users = ref([
        {
            id: 1,
            name: 'Jane Doe',
            email: 'jane@example.com',
            role: 'admin',
            active: true,
            tasks: [
                { id: 101, title: 'Review reports', description: 'Quarterly review', priority: 'high', dueDate: '2025-06-30', status: 'pending' },
                { id: 102, title: 'Approve budget', description: '2025 budget', priority: 'medium', dueDate: '2025-07-15', status: 'completed' }
            ]
        },
        {
            id: 2,
            name: 'John Smith',
            email: 'john@example.com',
            role: 'user',
            active: false,
            tasks: [
                { id: 103, title: 'Submit timesheet', description: 'May timesheet', priority: 'low', dueDate: '2025-06-28', status: 'pending' }
            ]
        }
    ]);

    const selectedUser = ref(null);

    const viewUserTasks = (user) => {
        selectedUser.value = user;
    };
</script>
