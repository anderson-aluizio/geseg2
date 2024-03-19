<script setup>
import { onMounted, ref } from 'vue';

defineProps({
    modelValue: [Number, String],
    type: {
        type: String,
        default: "text",
    },
});

defineEmits(['update:modelValue']);

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <input ref="input" class="border-gray-300 focus:border-sky-600 focus:ring-sky-600 rounded-md shadow-sm block w-full"
        :type="type" :value="modelValue" @input="$emit('update:modelValue', $event.target.value)">
</template>
