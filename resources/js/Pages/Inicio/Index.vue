<script setup>
import { onBeforeMount, ref } from 'vue';
import DashboardAppLayout from '@/Layouts/DashboardAppLayout.vue';
const props = defineProps({
    user: Object,
});

</script>

<template>
    <DashboardAppLayout title="Início">
    </DashboardAppLayout>
</template> 