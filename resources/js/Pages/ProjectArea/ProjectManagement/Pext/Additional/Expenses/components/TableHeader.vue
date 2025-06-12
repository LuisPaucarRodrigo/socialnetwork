<template>
    <div class="flex gap-4 justify-between">
        <div class="hidden sm:flex sm:items-center space-x-3">
            <PrimaryButton data-tooltip-target="update_data_tooltip" type="button" @click="dd">
                <ServerIcon />
            </PrimaryButton>
            <div id="update_data_tooltip" role="tooltip"
                class="absolute z-10 invisible inline-block px-2 py-2 text-xs font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                Todos los Registros
                <div class="tooltip-arrow" data-popper-arrow></div>
            </div>
            <button data-tooltip-target="export_tooltip" type="button"
                class="rounded-md bg-green-600 px-4 py-2 text-center text-sm text-white hover:bg-green-500"
                @click="openExportExcel">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M9.29289 1.29289C9.48043 1.10536 9.73478 1 10 1H18C19.6569 1 21 2.34315 21 4V9C21 9.55228 20.5523 10 20 10C19.4477 10 19 9.55228 19 9V4C19 3.44772 18.5523 3 18 3H11V8C11 8.55228 10.5523 9 10 9H5V20C5 20.5523 5.44772 21 6 21H7C7.55228 21 8 21.4477 8 22C8 22.5523 7.55228 23 7 23H6C4.34315 23 3 21.6569 3 20V8C3 7.73478 3.10536 7.48043 3.29289 7.29289L9.29289 1.29289ZM6.41421 7H9V4.41421L6.41421 7ZM19 12C19.5523 12 20 12.4477 20 13V19H23C23.5523 19 24 19.4477 24 20C24 20.5523 23.5523 21 23 21H19C18.4477 21 18 20.5523 18 20V13C18 12.4477 18.4477 12 19 12ZM11.8137 12.4188C11.4927 11.9693 10.8682 11.8653 10.4188 12.1863C9.96935 12.5073 9.86526 13.1318 10.1863 13.5812L12.2711 16.5L10.1863 19.4188C9.86526 19.8682 9.96935 20.4927 10.4188 20.8137C10.8682 21.1347 11.4927 21.0307 11.8137 20.5812L13.5 18.2205L15.1863 20.5812C15.5073 21.0307 16.1318 21.1347 16.5812 20.8137C17.0307 20.4927 17.1347 19.8682 16.8137 19.4188L14.7289 16.5L16.8137 13.5812C17.1347 13.1318 17.0307 12.5073 16.5812 12.1863C16.1318 11.8653 15.5073 11.9693 15.1863 12.4188L13.5 14.7795L11.8137 12.4188Z"
                        fill="#ffffff" />
                </svg>
            </button>
            <div id="export_tooltip" role="tooltip"
                class="absolute z-10 invisible inline-block px-2 py-2 text-xs font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                Exportar Excel
                <div class="tooltip-arrow" data-popper-arrow></div>
            </div>
            <button type="button"
                class="rounded-md bg-green-600 px-4 py-2 text-center text-sm text-white hover:bg-green-500"
                @click="openModalImport">
                Importar Gastos
            </button>
            <button data-tooltip-target="rejected_tooltip" type="button"
                class="rounded-md bg-gray-100 px-4 py-1 text-center text-lg font-bold ring-2 hover:bg-gray-100/2"
                :class="filterForm.rejected ? 'text-red-600 ring-red-400' : 'text-green-600 ring-green-400'"
                @click="rejectedExpenses">
                {{ filterForm.rejected ? "R" : "A" }}
            </button>
            <div id="rejected_tooltip" role="tooltip"
                class="absolute z-10 invisible inline-block px-2 py-2 text-xs font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                Rechazados
                <div class="tooltip-arrow" data-popper-arrow></div>
            </div>
            <div>
                <dropdown align="left">
                    <template #trigger>
                        <button data-tooltip-target="action_button_tooltip" @click="dropdownOpen = !dropdownOpen"
                            class="relative block overflow-hidden rounded-md text-white hover:bg-indigo-400 text-center text-sm bg-indigo-500 p-2">
                            <svg width="20px" height="20px" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M4 6H20M4 12H20M4 18H20" stroke="#ffffff" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </button>
                        <div id="action_button_tooltip" role="tooltip"
                            class="absolute z-10 invisible inline-block px-2 py-2 text-xs font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700 whitespace-nowrap">
                            Acciones
                            <div class="tooltip-arrow" data-popper-arrow></div>
                        </div>
                    </template>

                    <template #content class="origin-left">
                        <div>
                            <!-- Alineación a la derecha -->

                            <div class="">
                                <button @click="openSwapAPModal"
                                    class="block w-full text-left px-4 py-2 text-sm text-black-700 hover:bg-gray-200 hover:text-black focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                                    Swap
                                </button>

                            </div>
                        </div>
                    </template>
                </dropdown>
            </div>

            <Link v-if="fixedOrAdditional"
                class="rounded-md px-4 py-2 text-center text-sm text-white bg-indigo-600 hover:bg-indigo-500"
                :href="route('pext.additional.expense.index', { project_id: project_id, fixedOrAdditional: false, type })">
            G.Adicionales
            </Link>
            <Link v-else class="rounded-md px-4 py-2 text-center text-sm text-white bg-indigo-600 hover:bg-indigo-500"
                :href="route('pext.additional.expense.index', { project_id: project_id, fixedOrAdditional: true, type })">
            G.Fijos
            </Link>
        </div>
        <div class="sm:hidden">
            <dropdown align="left">
                <template #trigger>
                    <button @click="dropdownOpen = !dropdownOpen"
                        class="relative block overflow-hidden rounded-md bg-gray-200 px-2 py-2 text-center text-sm text-white hover:bg-gray-100">
                        <MenuIcon />
                    </button>
                </template>

                <template #content class="origin-left">
                    <div class="dropdown">
                        <div class="dropdown-menu">
                            <button type="button"
                                class="block w-full text-left px-4 py-2 text-sm text-black-700 hover:bg-indigo-600 hover:text-white focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-ouy"
                                @click="dd">
                                Todos los registros
                            </button>
                        </div>
                        <div class="dropdown-menu">
                            <a
                                class="block w-full text-left px-4 py-2 text-sm text-black-700 hover:bg-indigo-600 hover:text-white focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                                Exportar
                            </a>
                        </div>
                        <div class="dropdown-menu">
                            <button type="button"
                                class="block w-full text-left px-4 py-2 text-sm text-black-700 hover:bg-indigo-600 hover:text-white focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-ouy"
                                @click="rejectedExpenses">
                                Rechazados
                            </button>
                        </div>
                        <div class="dropdown-menu">
                            <Link v-if="fixedOrAdditional"
                                class="block w-full text-left px-4 py-2 text-sm text-black-700 hover:bg-indigo-600 hover:text-white focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-ouy"
                                :href="route('pext.additional.expense.index', { project_id: project_id, fixedOrAdditional: false, type })">
                            G.Adicionales
                            </Link>
                            <Link v-else
                                class="block w-full text-left px-4 py-2 text-sm text-black-700 hover:bg-indigo-600 hover:text-white focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-ouy"
                                :href="route('pext.additional.expense.index', { project_id: project_id, fixedOrAdditional: true, type })">
                            G.Fijos
                            </Link>
                        </div>
                    </div>
                </template>
            </dropdown>
        </div>
        <div class="flex space-x-3">
            <Search v-model:search="filterForm.search"
                fields="Ruc,Fecha Documento,Descripción,Monto,Numero de Operación" />
        </div>
    </div>
</template>
<script setup>
import PrimaryButton from '@/Components/PrimaryButton.vue';
import Search from '@/Components/Search.vue';
import { Link } from '@inertiajs/vue3';
import Dropdown from "@/Components/Dropdown.vue";
import { MenuIcon, ServerIcon } from '@/Components/Icons';

const { project_id, fixedOrAdditional, type, openSwapAPModal, openModalImport, initialFilterFormState } = defineProps({
    project_id: String,
    fixedOrAdditional: Boolean,
    type: String,
    openSwapAPModal: Function,
    openModalImport: Function,
    initialFilterFormState: Object
})
const filterForm = defineModel('filterForm')
function openExportExcel() {
    const uniqueParam = `timestamp=${new Date().getTime()}`;
    const url =
        route("projectmanagement.pext.expenses.export", {
            project_id: project_id,
            fixedOrAdditional: filterForm.value.fixedOrAdditional
        }) +
        "?" +
        uniqueParam;
    window.location.href = url;
}

function rejectedExpenses() {
    filterForm.value.rejected = !filterForm.value.rejected
}

function dd() {
    filterForm.value = { ...initialFilterFormState }
}
</script>