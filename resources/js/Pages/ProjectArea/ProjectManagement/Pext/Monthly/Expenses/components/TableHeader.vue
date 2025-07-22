<template>
    <div class="flex gap-4 justify-between">
        <div class="hidden sm:flex sm:items-center space-x-3">
            <PrimaryButton v-if="filterForm.rejected" @click="openCreateAdditionalModal" type="button"
                class="whitespace-nowrap">
                + Agregar
            </PrimaryButton>
            <PrimaryButton data-tooltip-target="update_data_tooltip" type="button" @click="() => {
                filterForm = { ...initialFilterFormState }
            }">
                <ServerIcon />
            </PrimaryButton>
            <div id="update_data_tooltip" role="tooltip"
                class="absolute z-10 invisible inline-block px-2 py-2 text-xs font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                Todos los Registros
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
            <button data-tooltip-target="export_tooltip" type="button"
                class="rounded-md bg-green-600 px-4 py-2 text-center text-sm text-white hover:bg-green-500"
                @click="openExportExcel">
                <ExcelIcon />
            </button>
            <div id="export_tooltip" role="tooltip"
                class="absolute z-10 invisible inline-block px-2 py-2 text-xs font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                Exportar Gastos
                <div class="tooltip-arrow" data-popper-arrow></div>
            </div>
            <button type="button"
                class="rounded-md bg-green-600 px-4 py-2 text-center text-sm text-white hover:bg-green-500"
                @click="openModalStructureExport">
                Exportar estructura de Gastos
            </button>
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
                {{ filterForm.rejected ? "Rechazados" : "Aceptados" }}
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
                            <button @click="openOpNuDaModal"
                                class="block w-full text-left px-4 py-2 text-sm text-black-700 hover:bg-gray-200 hover:text-black focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                                Actualizar Operación
                            </button>
                            <button v-if="fixedOrAdditional == 0" @click="openSwapCostsModal"
                                class="block w-full text-left px-4 py-2 text-sm text-black-700 hover:bg-gray-200 hover:text-black focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                                Swap(Gastos Fijos)
                            </button>
                        </div>
                    </template>
                </dropdown>
            </div>
            <Link v-if="fixedOrAdditional"
                class="rounded-md px-4 py-2 text-center text-sm text-white bg-indigo-600 hover:bg-indigo-500"
                :href="route('projectmanagement.pext.expenses.index', { project_id: project_id, fixedOrAdditional: false, status: status })">
            G.A.
            </Link>
            <Link v-else class="rounded-md px-4 py-2 text-center text-sm text-white bg-indigo-600 hover:bg-indigo-500"
                :href="route('projectmanagement.pext.expenses.index', { project_id: project_id, fixedOrAdditional: true, status: status })">
            G.F.
            </Link>
            <Link class="rounded-md px-4 py-2 text-center text-sm text-white bg-green-600 hover:bg-green-500"
                :href="route('projectmanagement.pext.expense_dashboard', { project_id: project_id })">
            <ChartIcon color="text-white" />
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
                            <button v-if="filterForm.rejected" @click="openCreateAdditionalModal"
                                class="block w-full text-left px-4 py-2 text-sm text-black-700 hover:bg-indigo-600 hover:text-white focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                                Agregar
                            </button>
                        </div>
                        <div class="dropdown-menu">
                            <button @click="() => {
                                filterForm = { ...initialFilterFormState }
                            }"
                                class="block w-full text-left px-4 py-2 text-sm text-black-700 hover:bg-indigo-600 hover:text-white focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                                Todos los Registros
                            </button>
                        </div>
                        <div class="dropdown-menu">
                            <button @click="openExportArchivesModal"
                                class="block w-full text-left px-4 py-2 text-sm text-black-700 hover:bg-indigo-600 hover:text-white focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                                Descargar imagenes
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
import Dropdown from "@/Components/Dropdown.vue";
import Search from "@/Components/Search.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { Link } from "@inertiajs/vue3";
import { ChartIcon, ExcelIcon, ImagesIcon, MenuIcon, ServerIcon } from "@/Components/Icons";

const { project_id, fixedOrAdditional, status, initialFilterFormState, openCreateAdditionalModal, openOpNuDaModal, openModalImport, openSwapCostsModal, openExportArchivesModal } = defineProps({
    project_id: String,
    fixedOrAdditional: Boolean,
    status: String,
    initialFilterFormState: Object,
    openCreateAdditionalModal: Function,
    openOpNuDaModal: Function,
    openModalImport: Function,
    openSwapCostsModal: Function,
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

function rejectedExpenses() {
    filterForm.value.rejected = !filterForm.value.rejected
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

</script>