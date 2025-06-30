<template>
    <Transition name="slide">
        <aside
            v-if="modelValue"
            class="fixed top-0 right-0 h-full w-full sm:w-[420px] bg-white shadow-xl z-50 flex flex-col"
            aria-modal="true"
            role="dialog"
        >
            <header class="flex items-center justify-between px-6 py-4 border-b bg-gray-50">
                <h2 class="text-lg font-semibold truncate">
                    {{ editMode ? 'Edit Task' : 'Add New Task' }}
                </h2>
                <button
                    class="p-2 rounded-full hover:bg-surface focus:outline-none focus:ring-2 focus:ring-border"
                    @click="closePanel"
                >
                    <Icon name="XMark" />
                    <span class="sr-only">Close panel</span>
                </button>
            </header>

            <form class="flex-1 overflow-y-auto px-6 pt-4 pb-0 space-y-6" @submit.prevent="submitTask">
                <div>
                    <label class="block text-sm font-semibold mb-1">Title</label>
                    <input
                        v-model="task.title"
                        required
                        class="w-full border rounded px-3 py-2 focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary-light"
                        placeholder="Enter task title"
                    />
                    <p v-if="errors.title" class="text-xs text-danger mt-1">
                        {{ errors.title }}
                    </p>
                </div>

                <div>
                    <label class="block text-sm font-semibold mb-1">Description</label>
                    <textarea
                        v-model="task.description"
                        rows="4"
                        class="w-full border rounded px-3 py-2 focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary-light"
                        placeholder="Enter description"
                    ></textarea>
                    <p v-if="errors.description" class="text-xs text-danger mt-1">
                        {{ errors.description }}
                    </p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold mb-1">Priority</label>
                        <select
                            v-model="task.priority"
                            class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-light"
                        >
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
                        <p v-if="errors.priority" class="text-xs text-danger mt-1">
                            {{ errors.priority }}
                        </p>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-1">Due Date</label>
                        <input
                            v-model="task.due_date"
                            type="date"
                            :min="today"
                            class="w-full border rounded px-3 py-2 focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary-light"
                        />
                        <p v-if="errors.due_date" class="text-xs text-danger mt-1">
                            {{ errors.due_date }}
                        </p>
                    </div>
                </div>
                <div class="flex-shrink-0 p-4 border-t flex justify-end space-x-2 sticky bottom-0">
                    <button
                        type="button"
                        class="px-4 py-2 rounded border border-gray-300 bg-white hover:bg-gray-100"
                        @click="closePanel"
                    >
                        Cancel
                    </button>
                    <button
                        type="submit"
                        class="flex items-center justify-center px-4 py-2 rounded bg-primary text-white hover:bg-accent focus:ring-2 focus:ring-primary-light-light"
                        :disabled="!hasChanges || isSubmitting"
                    >
                        <svg
                            v-if="isSubmitting"
                            class="animate-spin h-4 w-4 mr-2 text-white"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                        >
                            <circle
                                class="opacity-25"
                                cx="12"
                                cy="12"
                                r="10"
                                stroke="currentColor"
                                stroke-width="4"
                            ></circle>
                            <path
                                class="opacity-75"
                                fill="currentColor"
                                d="M4 12a8 8 0 018-8v4l3-3-3-3v4a8 8 0 00-8 8z"
                            ></path>
                        </svg>
                        {{ editMode ? 'Update' : 'Create' }}
                    </button>
                </div>
            </form>
        </aside>
    </Transition>
</template>

<script setup>
    import { ref, watch, computed } from 'vue';
    import Icon from '@/components/Icon.vue';
    import { useTaskStore } from '@/store/taskStore';

    const props = defineProps({
        modelValue: Boolean,
        taskData: {
            type: Object,
            default: () => ({}),
        },
    });

    const emit = defineEmits(['update:modelValue']);

    const store = useTaskStore();

    const task = ref({
        title: '',
        description: '',
        priority: 'medium',
        due_date: '',
        status: 'pending',
    });
    const originalTask = ref({});
    const isSubmitting = ref(false);

    const errors = ref({});
    const today = new Date().toISOString().split('T')[0];

    const editMode = computed(() => !!props.taskData?.id);
    const hasChanges = computed(() => {
        if (!editMode.value) return true;

        return JSON.stringify(task.value) !== JSON.stringify(originalTask.value);
    });

    watch(
        () => props.modelValue,
        (val) => {
            if (val && props.taskData) {
                task.value = { ...props.taskData };
                originalTask.value = { ...props.taskData };
                errors.value = {};
            } else {
                task.value = {
                    title: '',
                    description: '',
                    priority: 'medium',
                    due_date: '',
                    status: 'pending',
                };
                originalTask.value = { ...task.value };
                errors.value = {};
            }
        },
        { immediate: true }
    );

    const submitTask = async () => {
        errors.value = {};
        isSubmitting.value = true;
        try {
            if (editMode.value) {
                await store.updateTask(task.value);
            } else {
                await store.addTask(task.value);
            }
            emit('update:modelValue', false);
        } catch (e) {
            if (e.response && e.response.status === 422) {
                errors.value = e.response.data.errors;
            } else {
                console.error(e);
            }
        } finally {
            isSubmitting.value = false;
        }
    };

    const closePanel = () => {
        emit('update:modelValue', false);
    };
</script>

<style scoped>
.slide-enter-active,
.slide-leave-active {
  transition: transform 0.3s ease;
}
.slide-enter-from {
  transform: translateX(100%);
}
.slide-leave-to {
  transform: translateX(100%);
}
</style>
