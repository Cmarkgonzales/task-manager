<template>
    <component
        :is="IconComponent"
        v-if="IconComponent"
        :class="[customClass ? `${customClass}` : 'w-5 h-5']"
    />
    <span v-else class="text-danger text-xs">Icon not found</span>
</template>

<script setup>
    import { computed } from 'vue';
    import * as SolidIcons from '@solid-icons';
    import * as OutlineIcons from '@outline-icons';

    const props = defineProps({
        name: { type: String, required: true },
        variant: { type: String, default: 'solid' },
        customClass: { type: String, default: '' },
    });

    const IconComponent = computed(() => {
        const icons = props.variant === 'outline' ? OutlineIcons : SolidIcons;
        return icons[`${props.name}Icon`] || null;
    });
</script>
