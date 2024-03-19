<script setup>
defineProps({
  errorsData: {
    type: Object,
    default: {},
  },
  hasErrors: {
    type: Boolean,
    default: false,
  },
});
</script>
<template>
  <div v-if="hasErrors">
    <div class="font-medium text-red-600">Atenção!</div>
    <ul class="mt-3 list-disc list-inside text-sm text-red-600">
      <li v-for="(error, key) in errorsData" :key="key">{{ error }}</li>
    </ul>
  </div>
</template>
