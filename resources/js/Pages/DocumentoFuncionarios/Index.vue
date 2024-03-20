<script setup>
import DashboardAppLayout from '@/Layouts/DashboardAppLayout.vue';
import DataTable from '@/Components/DataTable/DataTable.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import Create from './Create.vue';
import Edit from './Edit.vue';
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    dataTable: Object,
    statuses: Array,
    documentos: Array,
    funcionario: Object,
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
        <template #header>
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-2xl">Documentos</h2>
                </div>
                <div class="float-right flex flex-row gap-2">
                    <Link :href="route('funcionarios.show', funcionario.id)">
                    <SecondaryButton>Voltar</SecondaryButton>
                    </Link>
                </div>
            </div>
        </template>
        <Create :showDialog="showCreateForm" @closed="showCreateForm = false" :documentos="documentos"
            :funcionario="funcionario" />
        <Edit v-if="showEditForm" :showDialog="showEditForm" :documento="selectedData" :statuses="statuses"
            :documentos="documentos" @closed="showEditForm = false" />

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="py-2">
                            <h3 class="text-xl font-semibold">{{ funcionario.nome }}</h3>
                        </div>
                        <DataTable :url="route('documento-funcionarios.index', funcionario.id)" :resource="dataTable">
                            <template #content-buttons>
                                <PrimaryButton @click="showCreateForm = true">Adicionar</PrimaryButton>
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
