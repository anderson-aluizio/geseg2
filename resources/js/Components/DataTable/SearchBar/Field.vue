<script setup>
import { ref } from "vue";
import Select from "./Select.vue";
const props = defineProps(["modelValue", "type", "config"]);
const value = ref("");
</script>

<template>
  <div>
    <el-input v-if="type === 'input'" v-model="value" @input="$emit('update:modelValue', $event)" ref="input" size="large"
      style="--el-component-size-large: 42px; --el-input-text-color: black; --el-text-color-regular:black" />

    <el-date-picker v-if="type === 'date'" format="DD/MM/YYYY" :value-format="config.valueFormat" v-model="value"
      @change="$emit('update:modelValue', $event)" ref="date" size="large"
      style="width: 100%;--el-component-size-large: 42px; --el-input-text-color: black; --el-text-color-regular:black" />

    <el-date-picker v-if="type === 'custom-date'" :type="config.type" :format="config.format"
      :value-format="config.valueFormat" v-model="value" @change="$emit('update:modelValue', $event)" ref="date"
      size="large"
      style="width: 100%;--el-component-size-large: 42px; --el-input-text-color: black; --el-text-color-regular:black" />

    <el-date-picker v-if="type === 'daterange'" type="daterange" format="YYYY/MM/DD" :value-format="config.valueFormat"
      range-separator="a" v-model="value" @change="$emit('update:modelValue', $event)" ref="daterange" size="large"
      style="width: 100%;--el-component-size-large: 42px; --el-input-text-color: black; --el-text-color-regular:black" />

    <Select v-if="type === 'select' && config.hasOwnProperty('items')" :primary-key="config.primarykey"
      :label="config.label" v-model="value" :items="config.items" @change="$emit('update:modelValue', $event)"
      :object-return="true" :clearable="false" />

    <Select v-if="type === 'select-search'" :primary-key="config.primarykey" :label="config.label" v-model="value"
      @change="$emit('update:modelValue', $event)" :action-search="config.urlSearch" :initial-value="config.initialValue"
      :object-return="config.objectReturn" :clearable="false" />
  </div>
</template>