<template>
    <Page>
        <div class="animate-fade-in">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">
                <h2 class="text-2xl font-bold text-heading sm:text-3xl">
                    My Tasks
                </h2>
                <button
                    class="w-full md:w-auto inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary hover:bg-accent"
                    @click="openNewTask"
                >
                    <Icon name="Plus" class="h-5 w-5 mr-2" /> Add Task
                </button>
            </div>
            <div class="bg-white rounded-lg shadow-sm p-4 mb-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                    <div class="relative w-full">
                        <div
                            class="absolute top-1/2 left-0 pl-3 transform -translate-y-1/2 flex items-center pointer-events-none"
                        >
                            <Icon name="MagnifyingGlass" class="text-text-light" />
                        </div>
                        <input
                            id="search-input"
                            v-model="searchTerm"
                            type="text"
                            class="w-full pl-10 pr-10 py-2 border border-border rounded-md focus:ring-primary focus:border-primary text-sm"
                            placeholder="Search tasks..."
                        />

                        <button
                            v-if="searchTerm"
                            class="absolute top-1/2 right-2 transform -translate-y-1/2 text-text-light hover:text-text"
                            @click="searchTerm = ''"
                        >
                            <Icon
                                name="XCircle"
                                variant="outline"
                                class="h-4 w-4"
                            />
                        </button>
                    </div>
                    <div class="flex gap-4 w-full md:w-auto">
                        <div>
                            <label class="text-sm font-medium text-text mb-1 block">Status</label>
                            <select v-model="statusFilter" class="w-full md:w-44 px-3 py-2 border rounded-md focus:ring-primary">
                                <option value="all">
                                    All
                                </option>
                                <option value="pending">
                                    Pending
                                </option>
                                <option value="completed">
                                    Completed
                                </option>
                            </select>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-text mb-1 block">Priority</label>
                            <select v-model="priorityFilter" class="w-full md:w-44 px-3 py-2 border rounded-md focus:ring-primary">
                                <option value="all">
                                    All
                                </option>
                                <option value="low">
                                    Low
                                </option>
                                <option value="medium">
                                    Medium
                                </option>
                                <option value="high">
                                    High
                                </option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div v-if="filteredTasks.length === 0" class="p-8 text-center">
                    <Icon
                        name="Clipboard"
                        variant="outline"
                        class="h-12 w-12 text-text-light mb-4 mx-auto"
                    />
                    <h3 class="text-lg font-medium text-text">
                        No tasks found
                    </h3>
                    <p
                        v-if="!searchTerm"
                        class="text-sm text-text-light"
                    >
                        Get started by creating a new task.
                    </p>
                    <p
                        v-else
                        class="text-sm text-text-light"
                    >
                        {{ `No task found matching ${searchTerm}.` }}
                    </p>
                    <button
                        v-if="!searchTerm"
                        class="mt-6 inline-flex items-center px-4 py-2 bg-primary text-white rounded-md hover:bg-accent"
                    >
                        <Icon name="Plus" class="h-5 w-5 mr-2" /> New Task
                    </button>
                </div>
                <TaskList
                    v-else
                    :tasks="filteredTasks"
                    :allow-drag="true"
                    @edit="openEditTask"
                    @update:tasks="updated => store.tasks = updated"
                    @toggle-status="toggleStatus"
                />
            </div>
            <TaskModal
                v-model="showTaskModal"
                :task-data="selectedTask"
            />
        </div>
    </Page>
</template>

<script setup>
    import { onMounted, ref, computed } from 'vue';
    import { storeToRefs } from 'pinia';
    import { useTaskStore } from '@/store/taskStore';
    // import { useAuthStore } from '../store/authStore';

    import Page from '@/components/Page.vue';
    import Icon from '@/components/Icon.vue';
    import TaskList from '@/components/TaskList.vue';
    import TaskModal from '@/components/TaskModal.vue';

    // Init store and refs
    const taskStore = useTaskStore();
    // const authStore = useAuthStore();
    const { tasks } = storeToRefs(taskStore); // Removed currentUser — not in taskStore

    // Load tasks from backend on page load
    onMounted(async () => {
        await taskStore.fetchTasks();
    });

    // Task modal state
    const showTaskModal = ref(false);
    const selectedTask = ref(null);

    // Filters
    const searchTerm = ref('');
    const statusFilter = ref('all');
    const priorityFilter = ref('all');

    // Filtered task list
    const filteredTasks = computed(() => {
        return tasks.value.filter(task => {
            const matchTitle = task.title.toLowerCase().includes(searchTerm.value.toLowerCase());
            const matchStatus = statusFilter.value === 'all' || task.status === statusFilter.value;
            const matchPriority = priorityFilter.value === 'all' || task.priority === priorityFilter.value;
            return matchTitle && matchStatus && matchPriority;
        });
    });

    // Modal triggers
    const openNewTask = () => {
        selectedTask.value = null;
        showTaskModal.value = true;
    };

    const openEditTask = (task) => {
        selectedTask.value = task;
        showTaskModal.value = true;
    };

    // Toggle task status using API
    const toggleStatus = async (task) => {
        await taskStore.toggleTaskStatus(task.id);
    };
</script>
