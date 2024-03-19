<script setup>
import Tooltip from './Tooltip.vue';

defineProps({
    tooltip: String,
    type: {
        type: String,
        default: 'button',
    },
});
</script>

<template>
    <Tooltip v-if="tooltip" :description="tooltip">
        <button :type="type"
            class="text-center px-4 py-2 bg-white border border-gray-100 rounded-md font-semibold text-xs text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-sky-600 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
            <slot />
        </button>
    </Tooltip>

    <button v-else :type="type"
        class="text-center px-4 py-2 bg-white border border-gray-100 rounded-md font-semibold text-xs text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-sky-600 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
        <slot />
    </button>
</template>
