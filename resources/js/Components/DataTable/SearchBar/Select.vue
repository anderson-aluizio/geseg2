<script setup>
import { get, has } from 'lodash';
import { ref } from 'vue';

const props = defineProps({
  multiple: Boolean,
  items: {
    type: Array,
    default: [],
  },
  initialValue: {
    type: Object,
    default: {},
  },
  placeholder: {
    type: String,
    default: "Pesquise"
  },
  primaryKey: String,
  actionSearch: String,
  label: String,
  subtext: {
    type: String,
    default: 'subtext',
  },
  objectReturn: {
    type: Boolean,
    default: false,
  },
  otherParams: {
    type: Object,
    default: {},
  },
  allowCreate: {
    type: Boolean,
    default: false,
  },
  clearable: {
    type: Boolean,
    default: true,
  },
});

const populateList = () => {
  if (props.items.length) {
    return props.items;
  }
  if (props.initialValue == null || Object.keys(props.initialValue).length === 0) {
    return [];
  }
  return [{ ...props.initialValue }];
}

const selected = props.initialValue == null || Object.keys(props.initialValue).length === 0
  ? ""
  : props.initialValue[props.primaryKey];
let isLoading = ref(false);
const list = ref([...populateList()]);
const isRemote = ref(Boolean(props.actionSearch));

const handleSearch = async (query) => {
  if (isRemote.value) {
    if (query) {
      isLoading = true;
      await axios.get(route("select-controller"), {
        params: {
          action: props.actionSearch,
          query: query,
          ...props.otherParams,
        },
      })
        .then(function (response) {
          list.value = [...response.data];
        })
        .catch(function (error) {
          console.log(error);
          if (error.request.status == 401) {
            alert('Usuário não logado! Por favor atualize a página e logue novamente!');
          }
          list.value = [];
        });
    } else {
      list.value = [];
    }
    isLoading = false;
  }
};
</script>
<template>
  <el-select v-model="selected" @change="$emit('input', selected)" filterable :remote="isRemote" :multiple="multiple"
    :placeholder="placeholder" :remote-method="handleSearch" :loading="isLoading" :clearable="clearable"
    :allow-create="allowCreate" :reserve-keyword="!allowCreate" :value-key="primaryKey" :value="initialValue" size="large"
    style="--el-component-size-large: 42px; --el-input-text-color: black; --el-text-color-regular:black"
    class="border-gray-300 focus:border-sky-600 focus:ring-sky-600 rounded-md shadow-sm block w-full">
    <el-option v-for="item in list" :key="item[primaryKey]" :label="item[label]"
      :value="objectReturn ? item : item[primaryKey]">
      <span v-if="has(item, subtext)">
        {{ item[label] }}
        <span class="float-right text-gray-400 text-[11px]">{{ get(item, subtext) }}</span>
      </span>
    </el-option>
  </el-select>
</template>
