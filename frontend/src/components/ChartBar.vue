<template>
    <div class="flex flex-col items-center">
        <div
            class="w-8 rounded transition-all duration-500"
            :class="color"
            :style="{ height: barHeight + 'px' }"
        ></div>
        <div class="mt-2 text-sm font-medium text-gray-700">
            {{ label }}
        </div>
        <div class="text-xs text-gray-500">
            {{ count }}
        </div>
    </div>
</template>

<script setup>
    import { computed } from 'vue';

    const props = defineProps({
        label: String,
        count: Number,
        percentage: Number,
        color: String
    });

    // normalize barHeight to a reasonable scale
    // so it looks proportional even with low percentages
    // (max height 150px)
    const barHeight = computed(() => {
        return Math.max(8, (props.percentage / 100) * 150);
    });
</script>
