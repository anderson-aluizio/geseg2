<script setup>
import DashboardAppLayout from '@/Layouts/DashboardAppLayout.vue';
import DataTable from '@/Components/DataTable/DataTable.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    dataTable: Object,
});

const showCreateForm = ref(false);
const selectedData = ref({});
const showEditForm = ref(false);

const editData = (data) => {
    selectedData.value = data;
    showEditForm.value = true;
}

</script>

<template>
    <DashboardAppLayout title="Cargos">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <DataTable :url="route('cargos.index')" :resource="dataTable">
                            <template #content-buttons>
                                <PrimaryButton @click="showCreateForm = true">Adicionar</PrimaryButton>
                            </template>
                            <template #cell(id)="{ value, item }">
                                <div class="flex flex-row gap-2 justify-center">
                                    <Link :href="route('cargos.edit', value)">
                                    <SecondaryButton>Editar</SecondaryButton>
                                    </Link>
                                </div>
                            </template>
                        </DataTable>
                    </div>
                </div>
            </div>
        </div>
    </DashboardAppLayout>
</template>
