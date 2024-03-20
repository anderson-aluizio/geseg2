<script setup>
import DateField from '@/Components/DateField.vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    documentos: Array,
    funcionario: Object,
});

</script>

<template>
    <div class="bg-white  shadow-sm sm:rounded-lg my-5">
        <div class="p-1 text-xl font-bold text-center">Documentos</div>
        <div class="text-center text-gray-500">Exibido somente vencidos e a vencer, para visualizar todos
            <Link class="text-blue-600 hover:cursor-pointer hover:underline"
                :href="route('documento-funcionarios.index', funcionario.id)">
            clique aqui
            </Link>
        </div>
        <div class="p-6 bg-white border-b border-gray-200 overflow-auto">
            <table class="table-auto w-full divide-y divide-gray-200">
                <thead class="bg-[#bc272d] font-bold text-white">
                    <tr>
                        <th class="py-3 px-6 text-sm">Criador</th>
                        <th class="py-3 px-6 text-sm">Ação</th>
                        <th class="py-3 px-6 text-sm">Status</th>
                        <th class="py-3 px-6 text-sm">Prazo</th>
                        <th class="py-3 px-6 text-sm">Conclusão</th>
                        <th class="py-3 px-6 text-sm">Pesquisa</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 text-center">
                    <tr v-if="!documentos.length">
                        <td colspan="6" class="py-2 px-3 text-center bg-gray-300">Nenhum registro encontrado</td>
                    </tr>
                    <tr v-else v-for="documento in documentos" class="hover:bg-gray-100 focus-within:bg-gray-100">
                        <td class="px-1 py-2">
                            <div class="text-sm leading-5 text-gray-900">{{ documento.nome }}</div>
                        </td>
                        <td class="px-1 py-2">
                            <div class="text-sm leading-5 text-gray-900">{{ documento.nome_abreviado }}</div>
                        </td>
                        <td class="px-1 py-2">
                            <div class="text-sm leading-5 text-gray-900">{{ documento.status }}</div>
                        </td>
                        <td class="px-1 py-2">
                            <div class="text-sm leading-5 text-gray-900">
                                <DateField :date="documento.prazo" />
                            </div>
                        </td>
                        <td class="px-1 py-2">
                            <div class="text-sm leading-5 text-gray-900">
                                <DateField :date="documento.prazo" />
                            </div>
                        </td>
                        <td class="px-1 py-2">
                            <div class="text-sm leading-5 text-gray-900">{{ documento.nome }}</div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
