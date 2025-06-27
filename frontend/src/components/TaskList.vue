<template>
    <div>
        <div
            v-if="showDeleteModal"
            class="fixed inset-0 flex items-center justify-center bg-black/50 z-50"
        >
            <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-sm space-y-4">
                <h2 class="text-lg font-semibold">
                    Delete Task
                </h2>
                <p class="text-sm text-gray-600">
                    Are you sure you want to delete this task?
                </p>
                <div class="flex justify-end gap-2 mt-4">
                    <button
                        class="px-4 py-2 text-sm rounded border border-gray-300 hover:bg-gray-100"
                        @click="cancelDelete"
                    >
                        Cancel
                    </button>
                    <button
                        class="px-4 py-2 text-sm rounded bg-danger text-white hover:bg-red-900"
                        @click="confirmDelete"
                    >
                        Delete
                    </button>
                </div>
            </div>
        </div>
        <draggable
            v-model="localTasks"
            :disabled="isAdminPage"
            tag="ul"
            item-key="id"
            handle=".drag-handle"
            class="divide-y divide-gray-200"
            @update:model-value="onUpdateTasks"
        >
            <template #item="{ element: task }">
                <li
                    class="task-item p-4 hover:bg-gray-50 transition border-l-4"
                    :class="[getBorderClass(task.priority), task.status === 'completed' ? 'opacity-60' : '']"
                >
                    <div class="flex items-center justify-between">
                        <div class="flex items-center flex-1 min-w-0">
                            <input
                                :id="`task-${task.id}`"
                                :disabled="isAdminPage"
                                class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded"
                                type="checkbox"
                                :checked="task.status === 'completed'"
                                @change="onToggleStatus(task)"
                            />
                            <div class="ml-3 flex-1">
                                <label :for="`task-${task.id}`" class="block">
                                    <p
                                        class="text-sm font-medium"
                                        :class="task.status === 'completed' ? 'line-through text-text-light' : 'text-text'"
                                    >
                                        {{ task.title }}
                                    </p>
                                    <p class="text-sm text-text-light mt-1">{{ task.description }}</p>
                                </label>
                                <div class="flex items-center mt-2 space-x-2">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                        :class="{
                                            'bg-success-light text-green-800': task.priority === 'Low',
                                            'bg-warning-light text-yellow-800': task.priority === 'Medium',
                                            'bg-danger-light text-red-800': task.priority === 'High'
                                        }"
                                    >
                                        {{ task.priority }}
                                    </span>
                                    <span class="flex items-center text-xs text-text-light">
                                        <Icon name="Calendar" class="h-4 w-4 mr-1" />
                                        {{ formatDueDate(task.dueDate) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div
                            v-if="!isAdminPage"
                            class="ml-4 flex-shrink-0 flex items-center space-x-2"
                        >
                            <button
                                class="text-text-light hover:text-text"
                                @click="emit('edit', task)"
                            >
                                <Icon name="PencilSquare" />
                            </button>
                            <button
                                class="text-text-light hover:text-danger"
                                @click="askDelete(task.id)"
                            >
                                <Icon name="Trash" />
                            </button>
                            <div
                                class="cursor-move text-text-light hover:text-text ml-2 drag-handle"
                                title="Drag to reorder"
                            >
                                <Icon name="Squares2X2" />
                            </div>
                        </div>
                    </div>
                </li>
            </template>
        </draggable>
    </div>
</template>

<script setup>
    import Icon from '@/components/Icon.vue';
    import draggable from 'vuedraggable';
    import { defineProps, defineEmits, ref, computed, watch } from 'vue';
    import { useRoute } from 'vue-router';
    import { useTaskStore } from '@/store/taskStore';

    const props = defineProps({
        tasks: { type: Array, required: true },
    });

    const emit = defineEmits(['edit', 'delete', 'update:tasks', 'toggle-status']);

    const localTasks = ref([...props.tasks]);
    const showDeleteModal = ref(false);
    const taskToDeleteId = ref(null);

    const route = useRoute();
    const store = useTaskStore();

    const isAdminPage = computed(() => route.name === 'admin');

    const confirmDelete = () => {
        if (taskToDeleteId.value !== null) {
            store.deleteTask(taskToDeleteId.value);
            localTasks.value = localTasks.value.filter(task => task.id !== taskToDeleteId.value);
            emit('update:tasks', localTasks.value);
            taskToDeleteId.value = null;
        }
        showDeleteModal.value = false;
    };

    const cancelDelete = () => {
        showDeleteModal.value = false;
        taskToDeleteId.value = null;
    };

    const askDelete = (id) => {
        taskToDeleteId.value = id;
        showDeleteModal.value = true;
    };

    watch(
        () => props.tasks,
        (newTasks) => {
            localTasks.value = [...newTasks];
        },
        { deep: true }
    );

    const onUpdateTasks = (tasks) => {
        emit('update:tasks', tasks);
    };

    const onToggleStatus = (task) => {
        emit('toggle-status', task);
    };

    const getBorderClass = (priority) => {
        switch (priority) {
        case 'High': return 'border-l-danger';
        case 'Medium': return 'border-l-warning';
        case 'Low': return 'border-l-success';
        default: return '';
        }
    };

    const formatDueDate = (dateString) => {
        const date = new Date(dateString);
        return date.toLocaleDateString(undefined, {
            year: 'numeric',
            month: 'short',
            day: 'numeric',
        });
    };
</script>

<style scoped>
    .task-item {
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .task-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -1px rgba(0,0,0,0.06);
    }

    .fade-enter-active,
    .fade-leave-active {
        transition: opacity 0.2s ease;
    }
    .fade-enter-from,
    .fade-leave-to {
        opacity: 0;
    }
</style>
