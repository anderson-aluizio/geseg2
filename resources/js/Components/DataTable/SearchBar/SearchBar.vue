<script setup>
import dayjs from "dayjs";
import _ from "lodash";
import { computed, ref, watch } from "vue";
import ErrorText from "./ErrorText.vue";
import { useForm } from "@inertiajs/vue3";
import Field from "./Field.vue";
import Select from "./Select.vue";

const emit = defineEmits(['changeField']);
const props = defineProps({
  isLoading: {
    type: Boolean,
    default: false
  },
  campos: {
    type: Object,
  },
  filterInitial: {
    type: Object,
  },
  filterLabelInitial: Object
});

const transformToCampoSelectList = (inputObj) => {
  const outputArr = [];

  for (const key in inputObj) {
    if (inputObj.hasOwnProperty(key)) {
      outputArr.push({
        id: key,
        label: inputObj[key].label,
      });
    }
  }

  return outputArr;
}
const camposFormatados = transformToCampoSelectList(props.campos);
const transformToOperatorSelectList = (inputObj) => {
  const outputArr = [];

  for (const key in inputObj) {
    if (inputObj.hasOwnProperty(key)) {
      outputArr.push({
        id: key,
        label: inputObj[key],
      });
    }
  }

  return outputArr;
}
const operatorsFormatados = computed(() => {
  const field = props.campos[form.field];

  return transformToOperatorSelectList(field.operatorsAvaiables);
});

const firstField = props.campos[Object.keys(props.campos)[0]];
const filters = ref({ ...props.filterInitial });
const filtersLabel = ref({ ...props.filterLabelInitial });
const form = useForm({
  field: firstField.filterFieldName,
  value: "",
  operator: firstField.filterOperators[0]
});
const errorText = ref("");
watch(
  () => form.field,
  (newField) => {
    form.value = "";
    form.operator = props.campos[newField].filterOperators[0];
  }
);

const adicionarFiltro = () => {
  let filter = { ...form };
  const fieldConfig = { ...props.campos[filter.field] };
  const { config: configField } = props.campos[filter.field];
  let labelValue = formatLabelValue(fieldConfig.filterFieldType, configField.label);

  if (!labelValue) {
    return errorText.value = "Informe um valor para o filtro";
  }
  let filterKeyExists = _.findKey(filters.value, (value, key) => {
    return key == filter.field + '-' + filter.operator;
  });
  if (filterKeyExists) {
    return errorText.value = "Ja existe um filtro com esse campo e operador";
  }

  if (fieldConfig.filterFieldType == "daterange") {
    filter.label = `${props.campos[filter.field].label} ${labelValue}`;
  } else {
    filter.label = `${props.campos[filter.field].label} ${props.campos[form.field].operatorsAvaiables[filter.operator]} ${labelValue}`;
  }

  let key = filter.field + '-' + filter.operator;
  filters.value[key] = formatFormValue(form.value, configField);
  filtersLabel.value[key] = filter.label;
  emit('changeField', {
    paramFilters: { ...filters.value },
    paramFiltersLabel: { ...filtersLabel.value }
  });
};
const formatLabelValue = (filterFieldType, configFieldLabel) => {
  let labelValue = _.isPlainObject(form.value)
    ? form.value[configFieldLabel]
    : form.value;
  if (filterFieldType == "date") {
    return formatDate(labelValue);
  }
  if (filterFieldType == "daterange") {
    return `entre ${formatDate(labelValue[0])} e ${formatDate(labelValue[1])}`;
  }

  return labelValue;
};

const formatDate = (inDate) => {
  return !inDate || !inDate.length
    ? ""
    : dayjs(inDate).format("DD/MM/YYYY");
};

const formatFormValue = (value, configField) => {
  if (_.isPlainObject(value)) {
    return value[configField.primarykey];
  }

  if (_.isArray(value)) {
    return _.compact(value);
  }

  return value;
};

const filterDestroy = (index) => {
  const { [index]: propValueFilter, ...restFilters } = filters.value;
  filters.value = restFilters;

  const { [index]: propValueFilterLabel, ...restFiltersLabel } = filtersLabel.value;
  filtersLabel.value = restFiltersLabel;
  emit('changeField', {
    paramFilters: { ...filters.value },
    paramFiltersLabel: { ...filtersLabel.value }
  });
};
</script>
<template>
  <div class="flex flex-col space-y-2">
    <div class="flex flex-col md:flex-row md:space-x-4 space-y-2 md:space-y-0">
      <div class="w-full">
        <Select primary-key="id" label="label" v-model="form.field" :items="camposFormatados" class="block w-full"
          :clearable="false" />
      </div>
      <Select v-if="campos[form.field].filterFieldType !== 'daterange'" :key="form.field" primary-key="id" label="label"
        v-model="form.operator" :items="operatorsFormatados" class="block w-full" :clearable="false" />
      <div class="w-full">
        <field class="w-full" v-if="form.field" :key="form.field" :type="campos[form.field].filterFieldType"
          :config="campos[form.field].config" v-model="form.value" :disabled="isLoading" />
      </div>
      <div class="w-full md:w-6 self-center">
        <div class="hidden md:block">
          <button @click="adicionarFiltro()" :disabled="isLoading">
            <font-awesome-icon class="text-[#1f4279] cursor-pointer" icon="plus" />
          </button>
        </div>
        <div class="md:hidden w-full">
          <button @click="adicionarFiltro()" type="button" :disabled="isLoading"
            class="btn-primary w-full transition duration-300 ease-in-out focus:outline-none focus:shadow-outline bg-purple-500 hover:bg-purple-900 text-white font-normal py-2 px-4 mr-1 rounded">Adicionar</button>
        </div>
      </div>
    </div>
    <error-text :error-text="errorText" @errorShowed="errorText = ''" />
    <div v-for="(filter, index) in filtersLabel" :key="index" class="flex justify-start">
      <div class="flex-1 flex gap-2 items-center bg-gray-100 border rounded-sm border-gray-200">
        <button @click="filterDestroy(index)" :disabled="isLoading"
          class="p-1 text-center border-red-500 shadow-sm bg-red-500 hover:bg-red-600 transition ease-in-out duration-150">
          <font-awesome-icon class="text-white" icon="times" />
        </button>
        <div class="flex-1">{{ filter }}</div>
      </div>
    </div>
  </div>
</template>