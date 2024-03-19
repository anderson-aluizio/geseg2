<script setup>
import DashboardAppLayout from '@/Layouts/DashboardAppLayout.vue';
import DataTable from '@/Components/DataTable/DataTable.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import Create from './Create.vue';
import Edit from './Edit.vue';
import { ref } from 'vue';

const props = defineProps({
    dataTable: Object,
    statuses: Array,
    documentos: Array,
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
    <DashboardAppLayout title="Documentos">
        <Create :showDialog="showCreateForm" @closed="showCreateForm = false" :documentos="documentos" />
        <Edit v-if="showEditForm" :showDialog="showEditForm" :documento="selectedData" :statuses="statuses"
            :documentos="documentos" @closed="showEditForm = false" />

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <DataTable :url="route('documentos.index')" :resource="dataTable">
                            <template #content-buttons>
                                <PrimaryButton @click="showCreateForm = true">Adicionar</PrimaryButton>
                            </template>
                            <template #cell(documento_pai.nome)="{ value, item }">
                                <div>{{ value || "N/A" }}</div>
                            </template>
                            <template #cell(id)="{ value, item }">
                                <SecondaryButton @click="editData(item)">Editar</SecondaryButton>
                            </template>
                        </DataTable>
                    </div>
                </div>
            </div>
        </div>
    </DashboardAppLayout>
</template>
