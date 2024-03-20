<script setup>
import { useForm } from '@inertiajs/vue3';
import DialogModal from '@/Components/DialogModal.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import InputLabel from '@/Components/InputLabel.vue';
import AlertErrors from '@/Components/AlertErrors.vue';
import Select from '@/Components/Select.vue';

const emit = defineEmits(['closed']);

const props = defineProps({
    showDialog: Boolean,
    documentos: Array,
    funcionario: Object,
});

const form = useForm({
    documento_id: '',
    funcionario_id: props.funcionario.id,
});

const submit = () => {
    form.post(route('documento-funcionarios.store'), {
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
                Cadastro de Documento
            </template>

            <template #content>
                <AlertErrors :errorsData="form.errors" :hasErrors="form.hasErrors" class="py-2" />
                <div class="grid grid-cols-1 gap-6 py-1">
                    <div>
                        <InputLabel for="documento_id" value="Documento" />
                        <Select id="documento_id" primary-key="id" label="nome" v-model="form.documento_id"
                            :items="documentos" class="mt-1 block w-full" />
                    </div>
                </div>
            </template>

            <template #footer>
                <SecondaryButton @click="closeModal">
                    Cancelar
                </SecondaryButton>

                <PrimaryButton @click="submit" class="ml-3" :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing">
                    Cadastrar
                </PrimaryButton>
            </template>
        </DialogModal>
    </span>
</template>
