<script setup>
const emit = defineEmits(['sorted']);

const props = defineProps({
    field: String,
    label: String,
    direction: String,
    isLoading: Boolean,
    isSortable: Boolean,
})

const callSort = () => {
    if (!props.isSortable) return;

    if (props.isLoading) return;
    emit('sorted', props.field)
};

</script>
<template>
    <th scope="col">
        <span class="inline-flex py-3 px-6 w-full justify-between" :class="{ 'cursor-not-allowed': !isSortable }"
            @click="callSort">
            <span class="w-full text-center">{{ label }}</span>

            <svg v-if="direction === 'asc'" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20"
                fill="currentColor">
                <path
                    d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h5a1 1 0 000-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM13 16a1 1 0 102 0v-5.586l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 101.414 1.414L13 10.414V16z" />
            </svg>

            <svg v-if="direction === 'desc'" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20"
                fill="currentColor">
                <path
                    d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h7a1 1 0 100-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM15 8a1 1 0 10-2 0v5.586l-1.293-1.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L15 13.586V8z" />
            </svg>
        </span>
    </th>
</template>