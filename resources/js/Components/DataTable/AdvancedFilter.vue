<script setup>
import { ref } from "vue";
import SearchBar from "./SearchBar/SearchBar.vue";

const emit = defineEmits(['apply', 'reset']);
const props = defineProps(["isLoading", "campos", "filterInitial", "filterLabelInitial"]);
const filters = ref({ ...props.filterInitial });
const filtersLabel = ref({ ...props.filterLabelInitial });
const isAplicado = ref(props.isLoading);

const adicionarFiltro = ({ paramFilters, paramFiltersLabel }) => {
  filters.value = { ...paramFilters };
  filtersLabel.value = { ...paramFiltersLabel };
};

const resetar = () => {
  isAplicado.value = true;
  emit("reset");
};
const aplicar = () => {
  isAplicado.value = true;
  emit("apply", {
    paramFilters: { ...filters.value },
    paramFiltersLabel: { ...filtersLabel.value },
  });
};
</script>
<template>
  <div class="p-2 flex flex-col gap-2">
    <search-bar :isLoading="isLoading" :campos="campos" @changeField="adicionarFiltro($event)"
      :filter-initial="filterInitial" :filter-label-initial="filterLabelInitial" />
    <div class="flex justify-around md:justify-end">
      <div class="flex gap-2 md:flex-none">
        <button
          class="text-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-sky-600 focus:ring-offset-2 transition ease-in-out duration-150 disabled:opacity-75"
          :class="{ 'opacity-25': isAplicado }" :disabled="isAplicado" @click="resetar">
          <div class="mx-auto">Resetar</div>
        </button>
        <button
          class="text-center px-4 py-2 bg-[#bc272d] border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-[#bc272d] focus:bg-[#bc272d] active:bg-sky-900 focus:outline-none focus:ring-2 focus:ring-sky-600 focus:ring-offset-2 transition ease-in-out duration-150 disabled:opacity-75"
          :class="{ 'opacity-25': isAplicado }" :disabled="isAplicado" @click="aplicar">
          <div class="mx-auto">Aplicar</div>
        </button>
      </div>
    </div>
  </div>
</template>