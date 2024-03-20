<script setup>
import { Link, useForm } from '@inertiajs/vue3';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import Select from '@/Components/Select.vue';
import DashboardAppLayout from '@/Layouts/DashboardAppLayout.vue';
import AlertErrors from '@/Components/AlertErrors.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import Documentos from './Documentos.vue';
import HelpText from '@/Components/HelpText.vue';

const props = defineProps({
    cargo: Object,
    competencias: Array,
    documentos: Array,
    states: Array,
});

const form = useForm({
    id: props.cargo.id,
    nome: props.cargo.nome,
    competencia: props.cargo.competencia,
    state: props.cargo.state,
})

const submit = () => {
    form.put(route('cargos.update', props.cargo.id));
}
</script>

<template>
    <DashboardAppLayout title="cargos">
        <template #header>
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-2xl">Editar cargo</h2>
                </div>
                <div class="float-right">
                    <Link :href="route('cargos.index')">
                    <SecondaryButton>Voltar</SecondaryButton>
                    </Link>
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <Documentos :cargo="cargo" :documentos="documentos" />
            </div>
        </div>
    </DashboardAppLayout>
</template>
