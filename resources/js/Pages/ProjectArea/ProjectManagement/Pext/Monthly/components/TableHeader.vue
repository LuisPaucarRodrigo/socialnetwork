<template>
    <div class="mt-6 flex items-center justify-between gap-x-6">
        <div class="hidden sm:flex sm:items-center sm:space-x-3">
            <PrimaryButton data-tooltip-target="add_preproject" v-if="hasPermission('ProjectManager')"
                @click="createOrEditModal()" type="button" customColor="bg-green-600 hover:bg-green-500">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
            </PrimaryButton>
            <div id="add_preproject" role="tooltip"
                class="absolute z-10 invisible inline-block px-2 py-1 text-xs font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                + Agregar Proyecto
                <div class="tooltip-arrow" data-popper-arrow></div>
            </div>

            <div id="add_monthly_project" role="tooltip"
                class="absolute z-10 invisible inline-block px-2 py-1 text-xs font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                + Agregar Proyecto Mensual
                <div class="tooltip-arrow" data-popper-arrow></div>
            </div>
            <Link :href="route('projectmanagement.pext.additional.index', { type: 2 })"
                class="bg-indigo-600 hover:bg-indigo-500 rounded-md px-4 py-2 text-center text-sm text-white">
            P. Adicionales
            </Link>
            <Link :href="route('projectmanagement.pext.historial')"
                class="bg-indigo-600 hover:bg-indigo-500 rounded-md px-4 py-2 text-center text-sm text-white">
            Historial
            </Link>
        </div>

        <div class="sm:hidden">
            <Dropdown align='left'>
                <template #trigger>
                    <button @click="dropdownOpen = !dropdownOpen"
                        class="relative block overflow-hidden rounded-md bg-gray-200 px-2 py-2 text-center text-sm text-white hover:bg-gray-100">
                        <svg width="25px" height="25px" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path d="M4 6H20M4 12H20M4 18H20" stroke="#000000" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                    </button>
                </template>

                <template #content class="origin-left">
                    <div>
                        <div class="dropdown">
                            <div v-if="hasPermission('ProjectManager')" class="dropdown-menu">
                                <button @click="createOrEditModal()"
                                    class="dropdown-item block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-indigo-600 hover:text-white focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                                    Agregar Proyecto
                                </button>
                            </div>
                            <div v-if="hasPermission('ProjectManager')" class="dropdown-menu">
                                <Link :href="route('projectmanagement.pext.additional.index', { type: 2 })"
                                    class="dropdown-item block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-indigo-600 hover:text-white focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                                P. Adicionales
                                </Link>
                            </div>
                            <div v-if="hasPermission('ProjectManager')" class="dropdown-menu">
                                <Link :href="route('projectmanagement.pext.historial')"
                                    class="dropdown-item block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-indigo-600 hover:text-white focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                                Historial
                                </Link>
                            </div>
                        </div>
                    </div>
                </template>
            </Dropdown>
        </div>
        <div class="flex space-x-4">
            <TextInput data-tooltip-target="search_fields" type="text" @input="search($event.target.value)"
                placeholder="Buscar..." />
            <div id="search_fields" role="tooltip"
                class="absolute z-10 invisible inline-block px-2 py-1 text-xs font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                Nombre
                <div class="tooltip-arrow" data-popper-arrow></div>
            </div>
        </div>
    </div>
</template>
<script setup>
import PrimaryButton from '@/Components/PrimaryButton.vue';
import Dropdown from '@/Components/Dropdown.vue';
import { Link } from '@inertiajs/vue3';
import TextInput from '@/Components/TextInput.vue';

const { projects, userPermissions, search, createOrEditModal } = defineProps({
    projects: Object,
    userPermissions: Array,
    search: Function,
    createOrEditModal: Function
})

const hasPermission = (permission) => {
    return userPermissions.includes(permission);
}
</script>