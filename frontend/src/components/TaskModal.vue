<template>
    <Transition name="fade">
        <div
            v-if="modelValue"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm"
            aria-modal="true"
            role="dialog"
        >
            <div class="bg-white rounded-lg shadow-lg max-w-lg w-full p-6">
                <h2 class="text-lg font-semibold mb-4">
                    {{ editMode ? 'Edit Task' : 'Add New Task' }}
                </h2>
                <form @submit.prevent="submitTask">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium mb-1">Title</label>
                            <input
                                v-model="task.title"
                                required
                                class="w-full border rounded px-2 py-2 focus:outline-none focus:ring-2 focus:ring-primary-light"
                                placeholder="Enter task title"
                            />
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Description</label>
                            <textarea
                                v-model="task.description"
                                rows="3"
                                class="w-full border rounded px-2 py-2 focus:outline-none focus:ring-2 focus:ring-primary-light"
                                placeholder="Enter description"
                            ></textarea>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium mb-1">Priority</label>
                                <select v-model="task.priority" class="w-full border rounded px-2 py-2 focus:outline-none focus:ring-2 focus:ring-primary-light">
                                    <option value="Low">
                                        Low
                                    </option>
                                    <option value="Medium">
                                        Medium
                                    </option>
                                    <option value="High">
                                        High
                                    </option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">Due Date</label>
                                <input
                                    v-model="task.dueDate"
                                    type="date"
                                    class="w-full border rounded px-2 py-2 focus:outline-none focus:ring-2 focus:ring-primary-light"
                                />
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-end mt-6 space-x-2">
                        <button
                            type="button"
                            class="px-4 py-2 rounded border border-gray-300 bg-white text-gray-700 hover:bg-gray-100 transition"
                            @click="cancel"
                        >
                            Cancel
                        </button>
                        <button
                            type="submit"
                            class="px-4 py-2 rounded bg-primary text-white hover:bg-accent transition"
                        >
                            {{ editMode ? 'Update' : 'Create' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </Transition>
</template>

<script setup>
    import { ref, watch, computed } from 'vue';
    import { useTaskStore } from '@/store/taskStore';

    const props = defineProps({
        modelValue: Boolean,
        taskData: {
            type: Object,
            default: () => ({})
        }
    });

    const emit = defineEmits(['update:modelValue']);

    const store = useTaskStore();

    const task = ref({
        title: '',
        description: '',
        priority: 'Medium',
        dueDate: ''
    });

    const editMode = computed(() => !!props.taskData?.id);

    watch(
        () => props.modelValue,
        (val) => {
            if (val && props.taskData) {
                task.value = { ...props.taskData };
            } else {
                task.value = {
                    title: '',
                    description: '',
                    priority: 'Medium',
                    dueDate: ''
                };
            }
        },
        { immediate: true }
    );

    const submitTask = () => {
        if (editMode.value) {
            store.updateTask(task.value);
        } else {
            store.addTask({
                ...task.value,
                status: 'pending',
            });
        }

        emit('update:modelValue', false);
    };

    const cancel = () => {
        emit('update:modelValue', false);
    };
</script>

<style scoped>
    .fade-enter-active, .fade-leave-active {
        transition: opacity 0.2s ease;
    }
    .fade-enter-from, .fade-leave-to {
        opacity: 0;
    }
</style>
