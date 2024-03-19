<script setup>
defineProps({
    type: {
        type: String,
        default: 'submit',
    },
});
</script>

<template>
    <button :type="type"
        class="text-center px-4 py-2 bg-[#bc272d] border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-[#bc272d] focus:bg-[#bc272d] active:bg-[#ac3a3e] focus:outline-none focus:ring-2 focus:ring-[#ac3a3e] focus:ring-offset-2 transition ease-in-out duration-150 disabled:opacity-75">
        <slot />
    </button>
</template>
