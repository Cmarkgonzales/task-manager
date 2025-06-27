<template>
    <div class="flex flex-col items-center">
        <div
            class="w-6 rounded"
            :class="color"
            :style="{ height: percentage + '%' }"
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
    defineProps({
        label: String,
        count: Number,
        percentage: Number,
        color: String
    });
</script>
