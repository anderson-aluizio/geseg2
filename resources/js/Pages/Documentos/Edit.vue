<script setup>
import { useForm } from '@inertiajs/vue3';
import DialogModal from '@/Components/DialogModal.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import InputLabel from '@/Components/InputLabel.vue';
import AlertErrors from '@/Components/AlertErrors.vue';
import Select from '@/Components/Select.vue';
import HelpText from '@/Components/HelpText.vue';
import InputFile from '@/Components/InputFile.vue';
import { ref } from 'vue';

const emit = defineEmits(['closed']);

const props = defineProps({
    showDialog: Boolean,
    documento: Object,
    statuses: Array,
    documentos: Array,
});

const form = useForm({
    nome: props.documento.nome,
    nome_abreviado: props.documento.nome_abreviado,
    prazo_em_dias: props.documento.prazo_em_dias,
    documento_pai_id: props.documento.documento_pai_id,
    status: props.documento.status
});

const submit = () => {
    form.put(route('documentos.update', props.documento.id), {
        onSuccess: () => {
            closeModal();
        },
    });
}

const closeModal = () => {
    emit('closed');
    form.reset();
};
</script>

<template>
    <span>
        <DialogModal :show="showDialog" @close="closeModal">
            <template #title>
                Editar de Item Doação
            </template>

            <template #content>
                <AlertErrors :errorsData="form.errors" :hasErrors="form.hasErrors" class="py-2" />
                <div class="grid grid-cols-1 gap-6 py-1">
                    <div>
                        <InputLabel for="nome" value="Nome" />
                        <TextInput id="nome" class="mt-1 block w-full" v-model="form.nome" required />
                    </div>
                    <div>
                        <InputLabel for="nome_abreviado" value="Nome Abreviado" />
                        <TextInput id="nome_abreviado" class="mt-1 block w-full" v-model="form.nome_abreviado"
                            required />
                        <HelpText message="Este campo não pode ter espaços em branco." />
                    </div>
                    <div>
                        <InputLabel for="prazo_em_dias" value="Prazo em Dias (Opcional)" />
                        <TextInput id="prazo_em_dias" type="number" class="mt-1 block w-full"
                            v-model="form.prazo_em_dias" required />
                        <HelpText
                            message="Defina aqui o prazo do documento e quando este for vencido um novo registro pendente será criado." />
                        <HelpText message="Caso este documento não tenha vencimento, deixe esse campo em branco." />
                    </div>
                    <div>
                        <InputLabel for="documento_pai_id" value="Documento Relacionado (Opcional)" />
                        <Select id="documento_pai_id" primary-key="id" label="nome" v-model="form.documento_pai_id"
                            :items="documentos" class="mt-1 block w-full" />
                        <HelpText
                            message="Caso este documento seja sequência de outro documento, selecione este aqui para que quando um for vencido este seja gerado em seguida." />
                    </div>
                    <div class="col-span-2 md:col-span-1">
                        <InputLabel for="status" value="Status" />
                        <Select id="status" primary-key="id" label="name" v-model="form.status" :items="statuses"
                            class="mt-1 block w-full" />
                    </div>
                </div>
            </template>

            <template #footer>
                <SecondaryButton @click="closeModal">
                    Cancelar
                </SecondaryButton>

                <PrimaryButton @click="submit" class="ml-3" :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing">
                    Atualizar
                </PrimaryButton>
            </template>
        </DialogModal>
    </span>
</template>
<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.5s;
}

.fade-enter,
.fade-leave-to {
    opacity: 0;
}
</style>