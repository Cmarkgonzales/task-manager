<template>
    <Page>
        <div class="animate-fade-in p-4">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl mb-6">
                Task Statistics
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <StatsCard
                    title="Total Tasks"
                    :count="stats.total"
                    icon="ClipboardDocumentList"
                    icon-color="text-accent"
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

            <!-- Lower stats -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
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

                <!-- Completion -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        Completion Rate
                    </h3>
                    <div class="relative pt-1">
                        <div class="flex mb-2 justify-between">
                            <span class="text-xs font-semibold py-1 px-2 rounded-full text-primary-600 bg-primary-200">Progress</span>
                            <span class="text-xs font-semibold text-primary-600">{{ completionPercent }}%</span>
                        </div>
                        <div class="overflow-hidden h-2 mb-4 rounded bg-primary-200">
                            <div
                                class="h-2 bg-primary-600 transition-all duration-500"
                                :style="{ width: completionPercent + '%' }"
                            ></div>
                        </div>
                        <p class="text-sm text-gray-600">
                            {{ stats.completed }} out of {{ stats.total }} tasks completed
                        </p>
                    </div>

                    <div class="mt-6">
                        <h4 class="text-sm font-medium text-gray-700 mb-3">
                            Recent Activity
                        </h4>
                        <ul class="space-y-2">
                            <li
                                v-for="(activity, index) in recentActivities"
                                :key="index"
                                class="text-sm text-gray-600"
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
    import { ref, computed } from 'vue';
    import Page from '@/components/Page.vue';
    import StatsCard from '@/components/StatsCard.vue';
    import ChartBar from '@/components/ChartBar.vue';

    const stats = ref({
        total: 25,
        completed: 15,
        pending: 10
    });

    const priority = ref({
        low: 5,
        medium: 12,
        high: 8
    });

    const recentActivities = ref([
        'Completed "Setup Database"',
        'Added task "Refactor Login"',
        'Marked "Update Tests" as completed'
    ]);

    // computed
    const completionPercent = computed(() =>
        stats.value.total > 0 ? Math.round((stats.value.completed / stats.value.total) * 100) : 0
    );

    const priorityPercent = computed(() => {
        const total = priority.value.low + priority.value.medium + priority.value.high;
        return {
            low: total ? Math.round((priority.value.low / total) * 100) : 0,
            medium: total ? Math.round((priority.value.medium / total) * 100) : 0,
            high: total ? Math.round((priority.value.high / total) * 100) : 0
        };
    });
</script>
