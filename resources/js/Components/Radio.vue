<script setup>
import { computed } from 'vue';

defineEmits(['update:modelValue', 'change']);

const props = defineProps({
    modelValue: {
        type: String,
        default: null,
    },
    value: {
        default: undefined
    },
});

const isChecked = computed(() => props.modelValue == props.value);
</script>

<template>
    <input type="radio" :value="value" :checked="isChecked"
        @change="$emit('update:modelValue', $event.target.value); $emit('change', $event.target.value)"
        class="rounded border-gray-300 text-[#bc272d] shadow-sm focus:ring-sky-600">
</template>
