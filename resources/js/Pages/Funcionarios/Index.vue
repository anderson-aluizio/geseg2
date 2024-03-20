<script setup>
import { Link } from '@inertiajs/vue3';
import DashboardAppLayout from '@/Layouts/DashboardAppLayout.vue';
import DataTable from '@/Components/DataTable/DataTable.vue';
import DateField from '@/Components/DateField.vue';
import IconButton from '@/Components/IconButton.vue';

const props = defineProps({
    dataTable: Object,
});

</script>

<template>
    <DashboardAppLayout title="Colaboradores">
        <template #header>
            <h2 class="text-2xl">Lista de Colaboradores</h2>
        </template>
        <div class="py-12">
            <div class="mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <DataTable :url="route('funcionarios.index')" :resource="dataTable"
                            headerStyle="text-center text-xs text-white uppercase cursor-pointer"
                            cellStyle="text-center text-sm p-1">
                            <template #cell(nome)="{ value, item }">
                                <div class="whitespace-nowrap">{{ value }}</div>
                            </template>
                            <template #cell(data_admissao)="{ value, item }">
                                <DateField :date="value" />
                            </template>
                            <template #cell(id)="{ value, item }">
                                <Link :href="route('funcionarios.show', item.id)">
                                <IconButton>
                                    <font-awesome-icon class="text-[#bc272d]" icon="eye" />
                                </IconButton>
                                </Link>
                            </template>
                        </DataTable>
                    </div>
                </div>
            </div>
        </div>
    </DashboardAppLayout>
</template>
