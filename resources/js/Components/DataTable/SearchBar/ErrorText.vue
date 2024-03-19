<script setup>
import { ref, watch } from 'vue';
const emit = defineEmits(['errorShowed']);

const props = defineProps({
    errorText: ''
})
const showDiv = ref(props.errorText !== '');

watch(() => props.errorText, (newVal) => {
    showDiv.value = newVal !== '';
    if (showDiv.value) {
        setTimeout(() => {
            showDiv.value = false;
            emit('errorShowed', true);
        }, 2000);
    }
});

</script>
<template>
    <transition name="fade">
        <button v-if="showDiv"
            class="w-full flex gap-2 p-2 rounded-sm bg-red-500 text-white cursor-pointer hover:bg-red-600"
            @click="showDiv = false">
            {{ errorText }}
        </button>
    </transition>
</template>
  
<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.5s;
}

.fade-enter,
.fade-leave-to {
    opacity: 0;
}
</style>