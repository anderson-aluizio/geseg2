<script setup>
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import Select from '@/Components/Select.vue';
import DashboardAppLayout from '@/Layouts/DashboardAppLayout.vue';
import { Link, useForm } from '@inertiajs/vue3';
import AlertErrors from '@/Components/AlertErrors.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import HelpText from '@/Components/HelpText.vue';

const props = defineProps({
    user: Object,
    centroCustos: Array,
    roles: Array,
    states: Array,
});

const form = useForm({
    id: props.user.id,
    email: props.user.email,
    funcionario_id: props.user.funcionario_id,
    role_id: props.user.role_id,
    state: props.user.state,
    centro_custos: props.user.centro_custos,
})

const submit = () => {
    form.put(route('users.update', props.user.id));
}
</script>

<template>
    <DashboardAppLayout title="Usuários">
        <template #header>
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-2xl">Editar usuário</h2>
                </div>
                <div class="float-right">
                    <Link :href="route('users.index')">
                    <SecondaryButton>Voltar</SecondaryButton>
                    </Link>
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <form @submit.prevent="submit">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 bg-white border-b border-gray-200">
                            <AlertErrors :errorsData="form.errors" :hasErrors="form.hasErrors" class="py-2" />
                            <div class="grid grid-cols-2 gap-6 py-1">
                                <div class="col-span-2 md:col-span-1">
                                    <InputLabel for="email" value="E-mail" />
                                    <TextInput id="email" type="email" class="mt-1 block w-full" v-model="form.email" />
                                </div>
                                <div class="col-span-2 md:col-span-1">
                                    <InputLabel for="funcionario_id" value="Colaborador" />
                                    <Select id="funcionario_id" primary-key="id" label="nome"
                                        v-model="form.funcionario_id" action-search="funcionarios"
                                        class="mt-1 block w-full" :initial-value="user.funcionario" />
                                </div>
                                <div class="col-span-2 md:col-span-1">
                                    <InputLabel for="role_id" value="Grupo de Acesso" />
                                    <Select id="role_id" primary-key="id" label="name" v-model="form.role_id"
                                        :items="roles" class="mt-1 block w-full" />
                                    <HelpText
                                        message="Caso este campo seja alterado, o usuário precisará logar novamente" />
                                </div>
                                <div class="col-span-2 md:col-span-1">
                                    <InputLabel for="state" value="Status" />
                                    <Select id="state" primary-key="id" label="name" v-model="form.state"
                                        :items="states" class="mt-1 block w-full" />
                                </div>
                                <div class="col-span-2">
                                    <InputLabel for="centro_custos" value="Centros de Custos" />
                                    <Select id="centro_custos" primary-key="id" label="nome"
                                        v-model="form.centro_custos" :items="centroCustos" :multiple="true"
                                        class="mt-1 block w-full" />
                                </div>
                            </div>
                        </div>
                        <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                            <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                Enviar</PrimaryButton>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </DashboardAppLayout>
</template>
