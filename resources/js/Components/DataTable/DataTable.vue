<script setup>
import { router } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';
import { pickBy, debounce, get } from 'lodash';
import Pagination from './Pagination.vue';
import TheadColumn from './TheadColumn.vue';
import AdvancedFilter from './AdvancedFilter.vue';

const props = defineProps({
    url: String,
    resource: Object,
    headerStyle: {
        type: String,
        default: "text-center text-xs font-semibold tracking-wider text-white uppercase"
    },
    cellStyle: {
        type: String,
        default: "text-center text-md py-2 px-3"
    }
})
const columns = props.resource?.columns;
const filter = props.resource?.filter;
const filterLabel = props.resource?.filter_label;
const sort = props.resource?.sort;
const camposFilter = props.resource?.columnsToFilter;
const advancedFilterKey = ref(0);

const showFilter = ref(Object.keys(filter).length > 0);

const pagination = computed(() => props.resource?.pagination);

const isLoading = ref(false);

const displayedFieldKeys = computed(() => {
    return Object.entries(columns).map(([_key, value]) => value.field)
})

let params = ref({
    filter: { ...filter },
    filter_label: { ...filterLabel },
    sort: sort,
    direction: String(sort).startsWith('-') ? 'desc' : 'asc',
});

const setSort = (field) => {
    isLoading.value = true;
    params.value.direction = params.value.direction === 'asc' ? 'desc' : 'asc';
    let sufix = params.value.direction === 'asc' ? '-' : '';
    params.value.sort = sufix + field;
};

const filterGlobal = ref(params.value?.filter?.global);
watch(
    () => filterGlobal,
    debounce((newParams) => {
        let customNewParams = { ...params.value };
        customNewParams.filter.global = newParams.value;
        params.value = customNewParams;
    }, 1000),
    { deep: true }
)

watch(
    () => params,
    (newParams) => reloadPage(newParams),
    { deep: true }
)

const reloadPage = (params) => {
    isLoading.value = true;
    router.get(props.url, pickBy(params.value), {
        replace: true,
        preserveState: true,
        onFinish: () => {
            isLoading.value = false;
            advancedFilterKey.value++;
            showFilter.value = Object.keys(props.resource?.filter).length > 0;
        }
    });
};

const formatedHeaderStyle = computed(() => isLoading.value
    ? props.headerStyle + ' opacity-75 cursor-wait'
    : props.headerStyle + ' cursor-pointer');

const aplicarFiltro = ({ paramFilters, paramFiltersLabel }) => {
    let customNewParams = { ...params.value };
    customNewParams.filter = { ...paramFilters };
    customNewParams.filter_label = { ...paramFiltersLabel };
    params.value = customNewParams;
};
const resetarFiltro = () => {
    filterGlobal.value = '';
    params.value = {};
};
</script>
<template>
    <div class="mx-auto">
        <div class="flex flex-row gap-4 justify-between items-center mb-2">
            <button v-if="Object.keys(camposFilter).length" @click="showFilter = !showFilter"
                class="text-center px-4 py-2 bg-white border border-gray-100 rounded-md font-semibold text-xs text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-sky-600 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                <font-awesome-icon class="text-[#bc272d]" icon="filter" />
            </button>
            <div class="w-full">
                <input type="search" v-model="filterGlobal" aria-label="Search" placeholder="Procurar..."
                    class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-sky-700 focus:border-sky-700 sm:text-sm" />
            </div>
            <slot name="content-buttons"></slot>
        </div>

        <advanced-filter :key="advancedFilterKey" v-if="Object.keys(camposFilter).length" :isLoading="isLoading"
            v-show="showFilter" :campos="camposFilter" :filter-initial="params.filter"
            :filter-label-initial="params.filter_label" @apply="aplicarFiltro" @reset="resetarFiltro" />

        <div class="bg-white shadow-md sm:rounded-lg">
            <div class="flex flex-col">
                <div class="overflow-x-auto -my-2 sm:-mx-6 lg:-mx-8">
                    <div class="inline-block py-2 min-w-full align-middle sm:px-6 lg:px-8">
                        <div class="overflow-hidden border-b border-gray-200 shadow sm:rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200 table-fixed">
                                <thead class="bg-[#bc272d]">
                                    <tr>
                                        <TheadColumn v-for="(column, key) in columns" :key="key" :field="column.field"
                                            :label="column.label"
                                            :direction="[column.field, '-' + column.field].includes(params.sort) ? params.direction : ''"
                                            :isLoading="isLoading" @sorted="setSort(column.field)"
                                            :isSortable="column.isSortable" :class="formatedHeaderStyle" />
                                    </tr>
                                </thead>

                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-if="isLoading">
                                        <td :colspan="displayedFieldKeys.length" class="py-2 px-3 text-center">
                                            Carregando...</td>
                                    </tr>
                                    <tr v-else-if="!pagination.data.length">
                                        <td :colspan="displayedFieldKeys.length" class="py-2 px-3 text-center">
                                            Nenhum
                                            registro
                                            encontrado</td>
                                    </tr>
                                    <tr v-else v-for="(item) in pagination.data" :key="item.id" class="hover:bg-gray-50">
                                        <td v-for="key in displayedFieldKeys" :class="cellStyle">
                                            <slot :name="`cell(${key})`" :value="get(item, key, '')" :item="item">
                                                {{ get(item, key, '') }}
                                            </slot>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <Pagination class="mt-5" :links="pagination.links" />
    </div>
</template>