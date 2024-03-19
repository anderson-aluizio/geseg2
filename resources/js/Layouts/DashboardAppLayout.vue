<script setup>
import { Head, usePage, router } from '@inertiajs/vue3';
import { has } from 'lodash';
import { SidebarMenu } from 'vue-sidebar-menu'
import { useSidebar } from '../Composables/useSidebar';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Banner from '@/Components/Banner.vue';
import Header from './Header.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import 'vue-sidebar-menu/dist/vue-sidebar-menu.css'

const { isOpen } = useSidebar();

defineProps({
    title: String,
});

const itemLogo = {
    component: ApplicationLogo,
    hiddenOnCollapse: true,
};
const menuList = [...usePage().props.rotasDisponiveis];
menuList.unshift(itemLogo)

const onToggleCollapse = (stateCollapsed) => {
    isOpen.value = stateCollapsed;
}
const onItemClick = (event, item) => {
    if (has(item, 'child[0]')) return;
}

const logout = () => {
    router.post(route('logout'));
};
</script>

<template>
    <div class="flex h-screen font-roboto">

        <Head :title="title" />

        <sidebar-menu :menu="menuList" link-component-name="router-link" :collapsed="isOpen" @item-click="onItemClick"
            @update:collapsed="onToggleCollapse" theme="white-theme" />
        <div class="flex-1 flex flex-col overflow-hidden">
            <div class="w-full p-2 flex flex-row justify-end">
                <Dropdown align="right" width="48">
                    <template #trigger>
                        <button v-if="$page.props.jetstream.managesProfilePhotos"
                            class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition">
                            <img class="h-10 w-10 rounded-full object-cover"
                                :src="$page.props.auth.user.profile_photo_url" :alt="$page.props.auth.user.name">
                        </button>

                        <span v-else class="inline-flex rounded-md">
                            <button type="button"
                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none focus:bg-gray-50 active:bg-gray-50 transition ease-in-out duration-150">
                                {{ $page.props.auth.user.name }}

                                <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                </svg>
                            </button>
                        </span>
                    </template>

                    <template #content>
                        <DropdownLink :href="route('profile.show')">
                            Perfil
                        </DropdownLink>
                        <form @submit.prevent="logout">
                            <DropdownLink as="button">
                                Sair
                            </DropdownLink>
                        </form>
                    </template>
                </Dropdown>
            </div>
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-200">
                <div style="transition: .3s ease;" :class="[isOpen ? 'pl-[65px]' : 'pl-[290px]']">
                    <Banner />
                    <header v-if="$slots.header" class="bg-white shadow">
                        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                            <slot name="header" />
                        </div>
                    </header>
                    <slot />
                </div>
            </main>
        </div>
    </div>
</template>

<style>
.v-sidebar-menu {
    z-index: 50;
}

.v-sidebar-menu .vsm--link_level-1 .vsm--icon {
    -ms-flex-negative: 0;
    border-radius: 3px;
    flex-shrink: 0;
    height: 25px;
    width: 25px;
}

.v-sidebar-menu.vsm_white-theme .vsm--link_level-1 .vsm--icon {
    background-color: white;
}

.v-sidebar-menu.vsm_white-theme .vsm--link_level-1.vsm--link_active .vsm--icon {
    background-color: white;
    color: rgb(79 70 229);
}

.v-sidebar-menu.vsm_white-theme.vsm_expanded .vsm--link_level-1.vsm--link_open {
    background-color: #a8a8a8;
}
</style>