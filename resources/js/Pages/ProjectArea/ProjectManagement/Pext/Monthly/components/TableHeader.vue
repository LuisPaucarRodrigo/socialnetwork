<template>
    <div class="mt-6 flex items-center justify-between gap-x-6">
        <div class="hidden sm:flex sm:items-center sm:space-x-3">
            <PrimaryButton data-tooltip-target="add_preproject" @click=" createOrEditModal()" type="button"
                customColor="bg-green-600 hover:bg-green-500">
                <PlusCircleIcon color="text-white"/>
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
                        <MenuIcon />
                    </button>
                </template>

                <template #content class="origin-left">
                    <div>
                        <div class="dropdown">
                            <div class="dropdown-menu">
                                <button @click="createOrEditModal()"
                                    class="dropdown-item block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-indigo-600 hover:text-white focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                                    Agregar Proyecto
                                </button>
                            </div>
                            <div class="dropdown-menu">
                                <Link :href="route('projectmanagement.pext.additional.index', { type: 2 })"
                                    class="dropdown-item block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-indigo-600 hover:text-white focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                                P. Adicionales
                                </Link>
                            </div>
                            <div class="dropdown-menu">
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
import { MenuIcon, PlusCircleIcon } from '@/Components/Icons/Index';

const { userPermissions, createOrEditModal } = defineProps({
    userPermissions: Array,
    createOrEditModal: Function
})
const projects = defineModel('projects')
const search = async (search) => {
    try {
        const response = await axios.post(route('projectmanagement.pext.index'), { searchQuery: search });
        projects.value = response.data;
    } catch (error) {
        console.error('Error searching:', error);
    }
};
</script>