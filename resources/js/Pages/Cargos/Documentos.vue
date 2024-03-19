<script setup>
import { Link, useForm } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import DestroyButton from '@/Components/DestroyButton.vue';
import Tooltip from '@/Components/Tooltip.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import AlertErrors from '@/Components/AlertErrors.vue';
import Select from '@/Components/Select.vue';

const emit = defineEmits(['closed']);

const props = defineProps({
    pergunta: Object,
    documentos: Array,
});

const form = useForm({
    documento_id: '',
});

const submit = () => {
    form.put(route('pergunta-documento.store', props.pergunta.id));
}
</script>

<template>
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg my-5">
        <div class="p-6 bg-white border-b border-gray-200">
            <h3 class="text-lg">Cadastro</h3>
            <AlertErrors :errorsData="form.errors" :hasErrors="form.hasErrors" class="py-2" />
            <div class="grid grid-cols-6 gap-6 py-1">
                <div class="col-span-6 md:col-span-5">
                    <Select id="documento_id" primary-key="id" label="nome" v-model="form.documento_id"
                        :items="documentos" class="block w-full" placeholder="Selecione uma documento" required />
                </div>
                <div class="col-span-6 md:col-span-1">
                    <PrimaryButton class="w-full h-full justify-center" @click="submit"
                        :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                        Cadastrar</PrimaryButton>
                </div>
            </div>
            <table class="min-w-full divide-y divide-gray-200 w-full">
                <thead class="bg-sky-600 font-bold text-white">
                    <tr>
                        <th class="py-2 px-6 text-sm text-left border border-y-1">documento</th>
                        <th class="py-2 px-6 text-sm w-24 text-center">#</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <tr v-if="!pergunta.documentos">
                        <td colspan="2" class="py-2 px-3 text-center bg-gray-300">
                            Nenhum
                            registro
                            encontrado</td>
                    </tr>
                    <tr v-else v-for="documento in pergunta.documentos"
                        class="hover:bg-gray-100 focus-within:bg-gray-100">
                        <td class="px-6 py-2 whitespace-nowrap">
                            <div class="text-sm leading-5 text-gray-900">{{ documento.nome }}</div>
                        </td>
                        <td class="px-6 py-2 flex flex-row justify-between items-center gap-2">
                            <Tooltip description="Editar documento">
                                <Link :href="route('documentos.edit', documento.id)">
                                <SecondaryButton>Editar</SecondaryButton>
                                </Link>
                            </Tooltip>
                            <DestroyButton label="Excluir" :identificador="documento.id" descricao
                                :rota="route('pergunta-documento.delete', documento.pivot.pergunta_id)"
                                :otherParams="{ documento_id: documento.id }" />
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
