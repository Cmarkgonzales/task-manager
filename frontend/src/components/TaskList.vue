<template>
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
                            type="checkbox"
                            class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded"
                            :checked="task.status === 'completed'"
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
                                        'bg-success-light text-shadow-success': task.priority === 'low',
                                        'bg-warning-light text-shadow-warning': task.priority === 'medium',
                                        'bg-danger-light text-shadow-danger': task.priority === 'high'
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
                            @click="emit('delete', task.id)"
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
</template>

<script setup>
    import Icon from './Icon.vue';
    import draggable from 'vuedraggable';
    import { defineProps, defineEmits, ref, computed, watch } from 'vue';
    import { useRoute } from 'vue-router';

    const props = defineProps({
        tasks: { type: Array, required: true },
    });

    const emit = defineEmits(['edit', 'delete', 'update:tasks']);

    const localTasks = ref([...props.tasks]);

    const route = useRoute();

    const isAdminPage = computed(() => route.name === 'admin');

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

    const getBorderClass = (priority) => {
        switch (priority) {
        case 'high': return 'border-l-danger';
        case 'medium': return 'border-l-warning';
        case 'low': return 'border-l-success';
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
</style>
