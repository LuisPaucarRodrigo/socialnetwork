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
            <Link v-if="fixedOrAdditional" data-tooltip-target="ga-gf-tooltip"
                class="rounded-md px-4 py-2 text-center text-sm text-white bg-indigo-600 hover:bg-indigo-500"
                :href="route('pext.additional.expense.index', { project_id: project_id, fixedOrAdditional: false, type })">
            G.A.
            </Link>
            <Link v-else class="rounded-md px-4 py-2 text-center text-sm text-white bg-indigo-600 hover:bg-indigo-500"
                data-tooltip-target="ga-gf-tooltip"
                :href="route('pext.additional.expense.index', { project_id: project_id, fixedOrAdditional: true, type })">
            G.F.
            </Link>
            <div id="ga-gf-tooltip" role="tooltip"
                class="absolute z-10 invisible inline-block px-2 py-2 text-xs font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                Gastos {{ fixedOrAdditional ? "Adicionales" : "Fijos" }}
                <div class="tooltip-arrow" data-popper-arrow></div>
            </div>
            <button type="button"
                class="rounded-md bg-blue-600 px-4 py-2 text-center text-sm text-white hover:bg-blue-500"
                @click="openExportArchivesModal" data-tooltip-target="export-photo-tooltip">
                <ImagesIcon color="text-white" />
            </button>
            <div id="export-photo-tooltip" role="tooltip"
                class="absolute z-10 invisible inline-block px-2 py-2 text-xs font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                Descargar Facturas, Boletas y Vouchers de Pago
                <div class="tooltip-arrow" data-popper-arrow></div>
            </div>
            <div>
                <dropdown align="left">
                    <template #trigger>
                        <button data-tooltip-target="action_button_tooltip" @click="dropdownOpen = !dropdownOpen"
                            class="relative block overflow-hidden rounded-md text-white hover:bg-indigo-400 text-center text-sm bg-indigo-500 p-2">
                            <MenuIcon color="text-white" />
                        </button>
                        <div id="action_button_tooltip" role="tooltip"
                            class="absolute z-10 invisible inline-block px-2 py-2 text-xs font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700 whitespace-nowrap">
                            Acciones
                            <div class="tooltip-arrow" data-popper-arrow></div>
                        </div>
                    </template>

                    <template #content class="origin-left">
                        <div>
                            <div class="dropdown-menu">
                                <button @click="openSwapAPModal"
                                    class="block w-full text-left px-4 py-2 text-sm text-black-700 hover:bg-gray-200 hover:text-black focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                                    Swap
                                </button>
                            </div>
                            <div class="dropdown-menu">
                                <button @click="openExportExcel"
                                    class="block w-full text-left px-4 py-2 text-sm text-black-700 hover:bg-indigo-600 hover:text-white focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                                    Exportar Gastos
                                </button>
                            </div>
                            <div class="dropdown-menu">
                                <button @click="openModalStructureExport"
                                    class="block w-full text-left px-4 py-2 text-sm text-black-700 hover:bg-indigo-600 hover:text-white focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                                    Exportar estructura de Gastos
                                </button>
                            </div>
                            <div class="dropdown-menu">
                                <button @click="openModalImport"
                                    class="block w-full text-left px-4 py-2 text-sm text-black-700 hover:bg-indigo-600 hover:text-white focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                                    Importar Gastos
                                </button>
                            </div>
                            <div class="dropdown-menu">
                                <button type="button"
                                    class="block w-full text-left px-4 py-2 text-sm text-black-700 hover:bg-indigo-600 hover:text-white focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-ouy"
                                    @click="rejectedExpenses">
                                    {{ filterForm.rejected ? "Rechazados" : "Aceptados" }}
                                </button>
                            </div>
                        </div>
                    </template>
                </dropdown>
            </div>


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
                            <button @click="() => {
                                filterForm = { ...initialFilterFormState }
                            }"
                                class="block w-full text-left px-4 py-2 text-sm text-black-700 hover:bg-indigo-600 hover:text-white focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                                Todos los Registros
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
                        <div class="dropdown-menu">
                            <button @click="openExportArchivesModal"
                                class="block w-full text-left px-4 py-2 text-sm text-black-700 hover:bg-indigo-600 hover:text-white focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                                Descargar imagenes
                            </button>
                        </div>
                        <div class="dropdown-menu">
                            <button @click="openSwapAPModal"
                                class="block w-full text-left px-4 py-2 text-sm text-black-700 hover:bg-gray-200 hover:text-black focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                                Swap
                            </button>
                        </div>
                        <div class="dropdown-menu">
                            <button @click="openExportExcel"
                                class="block w-full text-left px-4 py-2 text-sm text-black-700 hover:bg-indigo-600 hover:text-white focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                                Exportar Gastos
                            </button>
                        </div>
                        <div class="dropdown-menu">
                            <button @click="openModalStructureExport"
                                class="block w-full text-left px-4 py-2 text-sm text-black-700 hover:bg-indigo-600 hover:text-white focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                                Exportar estructura de Gastos
                            </button>
                        </div>
                        <div class="dropdown-menu">
                            <button @click="openModalImport"
                                class="block w-full text-left px-4 py-2 text-sm text-black-700 hover:bg-indigo-600 hover:text-white focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                                Importar Gastos
                            </button>
                        </div>
                        <div class="dropdown-menu">
                            <button type="button"
                                class="block w-full text-left px-4 py-2 text-sm text-black-700 hover:bg-indigo-600 hover:text-white focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-ouy"
                                @click="rejectedExpenses">
                                {{ filterForm.rejected ? "Rechazados" : "Aceptados" }}
                            </button>
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
import { ImagesIcon, MenuIcon, ServerIcon } from '@/Components/Icons';

const { project_id, fixedOrAdditional, type, openSwapAPModal, openModalImport, initialFilterFormState, openExportArchivesModal } = defineProps({
    project_id: String,
    fixedOrAdditional: Boolean,
    type: String,
    openSwapAPModal: Function,
    openModalImport: Function,
    initialFilterFormState: Object,
    openExportArchivesModal: Function
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

function openModalStructureExport() {
    const uniqueParam = `timestamp=${new Date().getTime()}`;
    const url =
        route("projectmanagement.pext.structure.excel.export", {
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