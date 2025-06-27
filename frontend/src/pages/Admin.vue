<template>
    <Page>
        <div id="admin-view" class="animate-fade-in space-y-8">
            <div>
                <h2 class="text-2xl font-bold text-heading sm:text-3xl mb-2">
                    Admin Dashboard
                </h2>
                <p class="text-sm text-text-light">
                    Manage users and review assigned tasks.
                </p>
            </div>

            <div class="bg-white rounded-xl shadow p-6 mb-8 border border-gray-100">
                <div class="mb-6">
                    <h3 class="text-xl font-semibold text-text">
                        User Management
                    </h3>
                    <p class="text-sm text-text-light">
                        View and manage all users registered in the system.
                    </p>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-border text-sm">
                        <thead class="bg-gray-50 text-text uppercase text-xs">
                            <tr>
                                <th class="px-6 py-3 text-left">
                                    User
                                </th>
                                <th class="px-6 py-3 text-left">
                                    Email
                                </th>
                                <th class="px-6 py-3 text-left">
                                    Role
                                </th>
                                <th class="px-6 py-3 text-left">
                                    Tasks
                                </th>
                                <th class="px-6 py-3 text-left">
                                    Status
                                </th>
                                <th class="px-6 py-3 text-right">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-border">
                            <tr
                                v-for="user in enrichedUsers"
                                :key="user.id"
                                class="hover:bg-gray-50 transition"
                            >
                                <td class="px-6 py-4 flex items-center gap-3">
                                    <div
                                        class="flex-shrink-0 w-9 h-9 rounded-full bg-primary-light text-primary flex items-center justify-center font-bold uppercase"
                                    >
                                        {{ user.name.charAt(0) }}
                                    </div>
                                    <span class="font-medium text-gray-900">{{ user.name }}</span>
                                </td>
                                <td class="px-6 py-4 text-text">
                                    {{ user.email }}
                                </td>
                                <td class="px-6 py-4 capitalize">
                                    <span
                                        class="inline-flex px-2 py-1 rounded-full text-xs font-medium"
                                        :class="user.role == 'admin' ? 'bg-violet-100 text-violet-600' : 'bg-primary-light text-primary'"
                                    >
                                        {{ user.role }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    {{ user.tasks.length }}
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="inline-flex px-2 py-1 rounded-full text-xs font-medium"
                                        :class="user.active ? 'bg-success-light text-green-800' : 'bg-red-100 text-red-800'"
                                    >
                                        {{ user.active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <button
                                        class="inline-flex items-center gap-1 px-3 py-1.5 rounded text-xs font-medium text-accent hover:text-primary transition"
                                        @click="viewUserTasks(user)"
                                    >
                                        <Icon name="Eye" class="h-4 w-4" />
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
                class="bg-white rounded-xl shadow p-6 border border-gray-100"
            >
                <div class="flex justify-between items-center border-b pb-4 mb-4">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">
                            Tasks for {{ selectedUser.name }}
                        </h3>
                        <p class="text-sm text-text-light">
                            {{ getTaskCountDisplay(selectedUser.tasks.length) }}
                        </p>
                    </div>
                    <button
                        class="text-gray-400 hover:text-text"
                        @click="selectedUser = null"
                    >
                        <Icon name="XMark" class="h-5 w-5" />
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
    import { ref, computed } from 'vue';
    import { useTaskStore } from '@/store/taskStore';
    import { useAuthStore } from '@/store/authStore';

    const taskStore = useTaskStore();
    const authStore = useAuthStore();

    const enrichedUsers = computed(() => {
        return authStore.fakeUsers.map(user => ({
            ...user,
            tasks: user.tasks.map(taskId => taskStore.getTaskById(taskId))
        }));
    });

    const selectedUser = ref(null);

    const viewUserTasks = (user) => {
        selectedUser.value = user;
    };

    const getTaskCountDisplay = (tasks) => {
        const count = Number(tasks) || 0;

        if (count === 0) {
            return 'No tasks assigned';
        }

        return count === 1 ? '1 task assigned' : `${count} tasks assigned`;
    };
</script>
