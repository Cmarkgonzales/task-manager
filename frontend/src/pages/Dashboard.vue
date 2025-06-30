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
                            class="w-full pl-10 pr-10 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary-light focus:border-primary text-sm"
                            placeholder="Search tasks..."
                        />
                        <button
                            v-if="searchTerm"
                            class="absolute top-1/2 right-4 transform -translate-y-1/2 text-text-light hover:text-text"
                            @click="searchTerm = ''"
                        >
                            <Icon
                                name="XCircle"
                                variant="outline"
                                class="h-4 w-4"
                            />
                        </button>
                    </div>

                    <div class="flex flex-col sm:flex-row sm:items-end gap-4 w-full md:w-auto">
                        <div class="flex flex-col">
                            <label class="text-sm font-medium text-text mb-1">Status</label>
                            <select v-model="statusFilter" class="w-full md:w-44 px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-primary-light">
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
                        <div class="flex flex-col">
                            <label class="text-sm font-medium text-text mb-1">Priority</label>
                            <select v-model="priorityFilter" class="w-full md:w-44 px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-primary-light">
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
                        <button
                            v-if="hasActiveFilters"
                            class="sm:self-end px-3 py-2 rounded-md border border-border text-sm hover:bg-primary-light"
                            @click="clearAllFilters"
                        >
                            Clear All
                        </button>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <ul v-if="isFetchingTasks">
                    <BaseLoading v-for="i in 3" :key="i" />
                </ul>
                <div v-else-if="!tasks.length" class="p-8 text-center">
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
                    :tasks="tasks"
                    :allow-drag="true"
                    @edit="openEditTask"
                    @update:tasks="updated => store.tasks = updated"
                    @toggle-status="toggleStatus"
                />
            </div>
            <TaskPanel
                v-model="showTaskPanel"
                :task-data="selectedTask"
            />
        </div>
    </Page>
</template>

<script setup>
    import { onMounted, ref, computed, watch } from 'vue';
    import { storeToRefs } from 'pinia';
    import { useTaskStore } from '@/store/taskStore';

    import Page from '@/components/Page.vue';
    import Icon from '@/components/Icon.vue';
    import TaskList from '@/components/TaskList.vue';
    import TaskPanel from '@/components/TaskPanel.vue';
    import BaseLoading from '@/components/BaseLoading.vue';

    const taskStore = useTaskStore();
    const { tasks, isFetchingTasks } = storeToRefs(taskStore);

    onMounted(async () => {
        await taskStore.fetchTasks();
    });

    const showTaskPanel = ref(false);
    const selectedTask = ref(null);

    const searchTerm = ref('');
    const statusFilter = ref('all');
    const priorityFilter = ref('all');

    // computed to check if anything is active
    const hasActiveFilters = computed(() => {
        return (
            searchTerm.value.trim() ||
            statusFilter.value !== 'all' ||
            priorityFilter.value !== 'all'
        );
    });

    // clear all filters
    const clearAllFilters = () => {
        searchTerm.value = '';
        statusFilter.value = 'all';
        priorityFilter.value = 'all';
    };

    watch([searchTerm, statusFilter, priorityFilter], () => {
        fetchFilteredTasks();
    });

    const fetchFilteredTasks = async () => {
        const params = {
            search: searchTerm.value || undefined,
            status: statusFilter.value !== 'all' ? statusFilter.value : undefined,
            priority: priorityFilter.value !== 'all' ? priorityFilter.value : undefined,
        };
        await taskStore.fetchTasks(params);
    };

    const openNewTask = () => {
        selectedTask.value = null;
        showTaskPanel.value = true;
    };

    const openEditTask = (task) => {
        selectedTask.value = task;
        showTaskPanel.value = true;
    };

    const toggleStatus = async (task) => {
        await taskStore.toggleTaskStatus(task.id);
    };
</script>
