<script setup>
defineProps({
    message: String,
});
</script>

<template>
    <span class="block mt-1 text-xs text-gray-600">
        {{ message }}
        <slot />
    </span>
</template>
