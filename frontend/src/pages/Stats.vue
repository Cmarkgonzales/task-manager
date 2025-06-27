<template>
    <Page>
        <div class="animate-fade-in p-4">
            <h2 class="text-2xl font-bold leading-7 text-heading sm:text-3xl mb-6">
                Task Statistics
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <StatsCard
                    title="Total Tasks"
                    :count="stats.total"
                    icon="ClipboardDocumentList"
                    icon-color="text-primary"
                    bg-color="bg-white"
                />
                <StatsCard
                    title="Completed"
                    :count="stats.completed"
                    icon="CheckCircle"
                    icon-color="text-success"
                    bg-color="bg-white"
                />
                <StatsCard
                    title="Pending"
                    :count="stats.pending"
                    icon="Clock"
                    icon-color="text-warning"
                    bg-color="bg-white"
                />
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-medium text-text mb-4">
                        Tasks by Priority
                    </h3>
                    <div class="flex items-end justify-around">
                        <ChartBar
                            label="Low"
                            :count="priority.low"
                            color="bg-green-500"
                            :percentage="priorityPercent.low"
                        />
                        <ChartBar
                            label="Medium"
                            :count="priority.medium"
                            color="bg-yellow-500"
                            :percentage="priorityPercent.medium"
                        />
                        <ChartBar
                            label="High"
                            :count="priority.high"
                            color="bg-red-500"
                            :percentage="priorityPercent.high"
                        />
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-medium text-text mb-4">
                        Completion Rate
                    </h3>
                    <div class="relative pt-1">
                        <div class="flex mb-2 justify-between">
                            <span
                                class="text-xs font-semibold py-1 px-2 rounded-full text-primary bg-primary-light/50"
                            >
                                Progress
                            </span>
                            <span class="text-xs font-semibold text-primary">{{
                                completionPercent
                            }}%</span>
                        </div>
                        <div class="overflow-hidden h-2 mb-4 rounded bg-primary-light">
                            <div
                                class="h-2 bg-primary transition-all duration-500"
                                :style="{ width: completionPercent + '%' }"
                            ></div>
                        </div>
                        <p class="text-sm text-text">
                            {{ stats.completed }} out of {{ stats.total }} tasks completed
                        </p>
                    </div>

                    <div class="mt-6">
                        <h4 class="text-sm font-medium text-text mb-3">
                            Recent Activity
                        </h4>
                        <ul class="space-y-2">
                            <li
                                v-for="(activity, index) in recentActivities"
                                :key="index"
                                class="text-sm text-text"
                            >
                                {{ activity }}
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </Page>
</template>

<script setup>
    import { computed } from 'vue';
    import Page from '@/components/Page.vue';
    import StatsCard from '@/components/StatsCard.vue';
    import ChartBar from '@/components/ChartBar.vue';
    import { useTaskStore } from '@/store/taskStore';

    const taskStore = useTaskStore();

    const tasks = computed(() => taskStore.tasks);

    const stats = computed(() => ({
        total: tasks.value.length,
        completed: tasks.value.filter((t) => t.status === 'completed').length,
        pending: tasks.value.filter((t) => t.status !== 'completed').length,
    }));

    const priority = computed(() => ({
        low: tasks.value.filter((t) => t.priority === 'Low').length,
        medium: tasks.value.filter((t) => t.priority === 'Medium').length,
        high: tasks.value.filter((t) => t.priority === 'High').length,
    }));

    const priorityPercent = computed(() => {
        const total =
            priority.value.low + priority.value.medium + priority.value.high;
        return {
            low: total ? Math.round((priority.value.low / total) * 100) : 0,
            medium: total ? Math.round((priority.value.medium / total) * 100) : 0,
            high: total ? Math.round((priority.value.high / total) * 100) : 0,
        };
    });

    const completionPercent = computed(() =>
        stats.value.total > 0
            ? Math.round((stats.value.completed / stats.value.total) * 100)
            : 0
    );

    // A simulated feature for recent tasks
    const recentActivities = computed(() => {
        return tasks.value
            .slice(-3)
            .map((task) =>
                task.status === 'completed'
                    ? `Completed "${task.title}"`
                    : `Added "${task.title}"`
            );
    });
</script>
