<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import DashboardAppLayout from '@/Layouts/DashboardAppLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import DestroyButton from '@/Components/DestroyButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import AlertErrors from '@/Components/AlertErrors.vue';
import RoleCreate from './RoleCreate.vue';
import { onUpdated, ref } from 'vue';

const props = defineProps({
    roles: Array,
    permissions: Object,
});

let openNovoGrupo = ref(false);
const form = useForm({
    id: null,
    name: null,
    permissions: []
});
let roleSelected = ref({});
let showEditRuleName = ref(false);

const selectRole = ({ id, name, permissions }) => {
    roleSelected = { id, name, permissions };
    form.id = id;
    form.name = name;
    form.permissions = permissions;
};

const save = () => {
    form.put(route("accesscontrol.role.update", form.id));
};
const selectFirstRole = () => {
    const [firstRole] = props.roles;
    if (firstRole !== undefined) {
        selectRole(firstRole);
    }
}
onUpdated(() => {
    selectFirstRole();
})

selectFirstRole();
</script>

<template>
    <DashboardAppLayout title="Controle de Acesso">
        <template #header>
            <div class="flex justify-between">
                <div>
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">Controle de acesso</h2>
                </div>
                <div class="float-right">
                    <PrimaryButton @click="openNovoGrupo = true">Adicionar</PrimaryButton>
                </div>
            </div>
        </template>

        <div class="max-w-7xl mx-auto py-2 sm:px-2 lg:px-2">
            <div class="flex divide-x" v-if="roles.length > 0">
                <div class="w-1/4">
                    <div v-for="role in roles" :key="role.id" class="p-2 cursor-pointer text-left hover:bg-white" :class="{
                        'bg-white': form.id === role.id
                    }" @click="selectRole(role)">
                        <span class="cursor-pointer" :class="{
                            'text-gray-400': form.id !== role.id,
                            'text-gray-900 font-bold': form.id === role.id
                        }">{{ role.name }}</span>
                    </div>
                </div>
                <div class="w-3/4 p-4 px-8">
                    <div class="my-2">
                        <div v-show="!showEditRuleName"
                            class="text-lg font-bold cursor-pointer text-blue-600 underline hover:bg-white"
                            @click="showEditRuleName = true">{{ roleSelected.name }} (Clique aqui para editar)</div>
                        <div class="flex flex-row gap-6 items-center" v-show="showEditRuleName">
                            <TextInput type="text" class="block w-full" v-model="form.name" />
                            <DestroyButton v-if="roleSelected.id" label="Excluir" :identificador="roleSelected.id"
                                textButton="Excluir" :rota="route('accesscontrol.role.delete', roleSelected.id)" />
                            <SecondaryButton type="button" @click="showEditRuleName = false">Fechar</SecondaryButton>
                        </div>
                    </div>

                    <AlertErrors :errorsData="form.errors" :hasErrors="form.hasErrors" class="py-2" />

                    <div class="flex flex-col gap-4">
                        <div v-for="(valueGroup, keyGroup) in permissions" :key="keyGroup" class="p-2 border bg-white">
                            <div class="font-semibold">
                                <font-awesome-icon class="text-black text-xs" icon="arrow-down" />
                                {{ keyGroup }}
                            </div>
                            <div class="flex flex-col gap-4 py-2 px-3">
                                <div v-for="(value, key) in valueGroup" :key="key" class="border p-2">
                                    <div class="font-semibold">{{ key }}</div>
                                    <div class="rounded py-2 px-3 grid grid-flow-row grid-cols-3 gap-4">
                                        <div v-for="permission in permissions[keyGroup][key]" :key="permission.id"
                                            class="form-check">
                                            <input :id="permission.id" type="checkbox"
                                                class="form-check-input appearance-none rounded-full h-4 w-4 border border-gray-300 bg-white checked:bg-blue-600 checked:border-blue-600 focus:outline-none transition duration-200 mt-1 align-top bg-no-repeat bg-center bg-contain float-left mr-2 cursor-pointer"
                                                checked :value="permission.id" v-model="form.permissions" />
                                            <label class="form-check-label inline-block text-gray-800" :for="permission.id">
                                                <span>{{ permission.name }}</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-row-reverse space-x-4 space-x-reverse px-4 sm:py-6">
                        <PrimaryButton @click="save" class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                            Salvar
                        </PrimaryButton>
                        <SecondaryButton @click="selectRole(roleSelected)">Cancelar</SecondaryButton>
                    </div>
                </div>
            </div>
        </div>
        <RoleCreate :show="openNovoGrupo" @close="openNovoGrupo = false" />
    </DashboardAppLayout>
</template>
