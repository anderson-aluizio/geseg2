<script setup>
import DashboardAppLayout from '@/Layouts/DashboardAppLayout.vue';
import DataTable from '@/Components/DataTable/DataTable.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Link } from '@inertiajs/vue3';
import SecondaryButton from '@/Components/SecondaryButton.vue';

const props = defineProps({
    dataTable: Object,
});

</script>

<template>
    <DashboardAppLayout title="Usuários">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <DataTable :url="route('users.index')" :resource="dataTable">
                            <template #content-buttons>
                                <Link :href="route('users.create')">
                                <PrimaryButton>Adicionar</PrimaryButton>
                                </Link>
                            </template>
                            <template #cell(name)="{ value, item }">
                                <div class="flex items-center py-2 px-3 whitespace-nowrap">
                                    <div class="flex-shrink-0 w-10 h-10">
                                        <img class="w-10 h-10 rounded-full" :src="item.profile_photo_url" alt="" />
                                    </div>
                                    <div class="ml-4 w-full">
                                        <div class="text-sm text-gray-900">
                                            {{ value }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ item.email }}
                                        </div>
                                    </div>
                                </div>
                            </template>
                            <template #cell(roles.name)="{ value, item }">
                                <span>
                                    {{ item.roles.map(role => role.name).join(', ') ?? '' }}
                                </span>
                            </template>
                            <template #cell(state)="{ value, item }">
                                <div class="flex justify-center items-start whitespace-nowrap text-xs">
                                    <font-awesome-icon icon="fa-solid fa-circle" class="mx-2" :class="{
                                        'text-green-500': value == 'ATIVO',
                                        'text-red-500': value != 'ATIVO'
                                    }" />
                                    <span>
                                        {{ value }}
                                    </span>
                                </div>
                            </template>
                            <template #cell(id)="{ value, item }">
                                <Link :href="route('users.edit', item.id)">
                                <SecondaryButton>Editar</SecondaryButton>
                                </Link>
                            </template>
                        </DataTable>
                    </div>
                </div>
            </div>
        </div>
    </DashboardAppLayout>
</template>
