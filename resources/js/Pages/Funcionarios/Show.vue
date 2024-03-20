<script setup>
import { Link } from '@inertiajs/vue3';
import DashboardAppLayout from '@/Layouts/DashboardAppLayout.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import DateField from '@/Components/DateField.vue';
import Documentos from './Documentos.vue';

const props = defineProps({
    funcionario: Object,
    documentos: Array,
    superiorFuncionarios: Array,
});
</script>

<template>
    <DashboardAppLayout title="Colaboradores">
        <template #header>
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="font-semibold text-xl py-2">Detalhe Colaborador</h2>
                </div>
                <div class="float-right flex flex-row gap-2">
                    <Link :href="route('funcionarios.index')">
                    <SecondaryButton>Voltar</SecondaryButton>
                    </Link>
                </div>
            </div>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex flex-col gap-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-1 text-xl font-bold text-center">Dados Gerais</div>
                    <div class="p-2 bg-white border-b border-gray-200">
                        <div class="grid grid-cols-3 gap-2 text-center">
                            <div class="col-span-3 md:col-span-1">
                                <div class="font-bold">Matrícula</div>
                                <div>{{ funcionario.matricula }}</div>
                            </div>
                            <div class="col-span-3 md:col-span-1">
                                <div class="font-bold">Nome</div>
                                <div>{{ funcionario.nome }}</div>
                            </div>
                            <div class="col-span-3 md:col-span-1">
                                <div class="font-bold">Situação</div>
                                <div>{{ funcionario.situacao }}</div>
                            </div>
                            <div class="col-span-3 md:col-span-1">
                                <div class="font-bold">Cadastro Manual</div>
                                <div>{{ funcionario.cadastro_manual ? "SIM" : "NÃO" }}</div>
                            </div>
                            <div class="col-span-3 md:col-span-1">
                                <div class="font-bold">Admissão</div>
                                <DateField :date="funcionario.data_admissao" />
                            </div>
                            <div class="col-span-3 md:col-span-1">
                                <div class="font-bold">Demissão</div>
                                <DateField :date="funcionario.data_demissao" />
                            </div>
                            <div class="col-span-3 md:col-span-1">
                                <div class="font-bold">Cargo</div>
                                <div>{{ funcionario.cargo?.nome }}</div>
                            </div>
                            <div class="col-span-3 md:col-span-1">
                                <div class="font-bold">Filial</div>
                                <div>{{ funcionario.filial }}</div>
                            </div>
                            <div class="col-span-3 md:col-span-1">
                                <div class="font-bold">Centro Custo</div>
                                <div>{{ funcionario.centro_custo.nome }}</div>
                            </div>
                            <div class="col-span-3 md:col-span-1">
                                <div class="font-bold">Centro Custo Analítico</div>
                                <div>{{ funcionario.centro_custo_analitico.nome }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <Documentos :documentos="documentos" :funcionario="funcionario" />
            </div>
        </div>
    </DashboardAppLayout>
</template>
