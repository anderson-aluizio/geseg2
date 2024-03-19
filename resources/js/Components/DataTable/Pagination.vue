<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    links: Array,
})
</script>
<template>
    <div>
        <div class="flex flex-wrap -mb-1">
            <template v-for="(link, key) in links" :key="key">
                <div v-if="link.url === null" :key="`${key}-disabled`"
                    class="mr-1 mb-1 px-4 py-3 text-sm leading-4 text-gray-400 border rounded" v-html="link.label" />
                <Link v-else :key="key"
                    class="mr-1 mb-1 px-4 py-3 text-sm leading-4 border rounded hover:text-black focus:border-sky-600 focus:text-[#bc272d]"
                    :class="{ 'bg-[#bc272d] text-white hover:bg-[#bc272d] hover:text-white': link.active }"
                    :href="link.url">
                <span v-html="link.label"></span>
                </Link>
            </template>
        </div>
    </div>
</template>