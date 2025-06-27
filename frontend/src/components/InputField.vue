<template>
    <div class="mb-4">
        <label :for="fieldId" class="block text-sm font-medium text-gray-700 mb-1">
            {{ label }}
        </label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 flex items-center pl-3">
                <Icon
                    :name="icon"
                    custom-class="h-5 w-5 text-gray-400"
                />
            </div>
            <input
                :id="fieldId"
                :type="type"
                :placeholder="placeholder"
                :aria-label="label"
                :required="required"
                :value="modelValue"
                :autocomplete="label"
                class="w-full pl-10 pr-11 py-2 sm:py-2.5 text-sm sm:text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-light focus:border-accent placeholder-gray-400"
                @input="$emit('update:modelValue', $event.target.value)"
            />
            <button
                v-if="showToggle"
                type="button"
                class="absolute inset-y-0 right-2 flex items-center text-gray-400 hover:text-gray-600"
                :aria-label="type === 'password' ? 'Show password' : 'Hide password'"
                @click="$emit('toggle')"
            >
                <Icon :name="visiblePasswordIcon" custom-class="h-5 w-5" />
            </button>
        </div>
    </div>
</template>

<script setup>
    import { computed } from 'vue';
    import Icon from '@/components/Icon.vue';

    const props = defineProps({
        label: String,
        icon: String,
        type: String,
        modelValue: String,
        required: { type: Boolean, default: false },
        showToggle: { type: Boolean, default: false },
        placeholder: { type: String, default: '' }
    });

    defineEmits(['update:modelValue', 'toggle']);

    const fieldId = computed(() =>
        props.label ? props.label.toLowerCase().replace(/\s+/g, '-') + '-input' : 'input-field'
    );

    const visiblePasswordIcon = computed(() => props.type === 'text' ? 'Eye' : 'EyeSlash');
</script>
