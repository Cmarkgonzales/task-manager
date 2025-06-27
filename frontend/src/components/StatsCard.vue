<template>
    <div class="bg-white rounded-lg shadow-sm p-6 flex items-center">
        <div :class="['h-12 w-12 rounded-md flex items-center justify-center', bgColor]">
            <Icon :name="icon" :custom-class="`${iconColor} 'text-xl'`"></Icon>
        </div>
        <div class="ml-4">
            <h3 class="text-lg font-medium text-gray-900">
                {{ title }}
            </h3>
            <p class="text-3xl font-bold" :class="iconColor">
                {{ count }}
            </p>
        </div>
    </div>
</template>

<script setup>
    import Icon from '@/components/Icon.vue';

    defineProps({
        title: String,
        count: Number,
        icon: String,
        iconColor: String,
        bgColor: String
    });
</script>
