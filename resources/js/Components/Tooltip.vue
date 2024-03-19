<script setup>
defineProps({
    description: {
        type: String,
        default: ""
    }
})
</script>

<template>
    <div class="group relative inline-block text-center">
        <slot />
        <div
            class="opacity-0 bg-black text-white text-center text-xs rounded-lg py-2 absolute z-10 group-hover:opacity-100 bottom-full -left-1/2 ml-[20%] px-3 pointer-events-none">
            {{ description }}
            <svg class="absolute text-black h-2 w-full left-0 top-full" x="0px" y="0px" viewBox="0 0 255 255"
                xml:space="preserve">
                <polygon class="fill-current" points="0,0 127.5,127.5 255,0" />
            </svg>
        </div>
    </div>
</template>
