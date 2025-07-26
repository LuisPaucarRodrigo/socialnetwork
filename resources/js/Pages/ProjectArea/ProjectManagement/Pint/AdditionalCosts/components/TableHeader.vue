<template>
    <div class="inline-block min-w-full mb-4">
        <div class="flex gap-4 justify-between">
            <div class="hidden sm:flex  space-x-3">
                <PrimaryButton v-if="
                    project_id.status === null
                " @click="openCreateAdditionalModal" type="button" class="whitespace-nowrap">
                    + Agregar
                </PrimaryButton>
                <PrimaryButton data-tooltip-target="update_data_tooltip" type="button" @click="() => {
                    filterForm = { ...initialFilterFormState }
                }
                ">
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
                    <ExcelIcon />
                </button>
                <div id="export_tooltip" role="tooltip"
                    class="absolute z-10 invisible inline-block px-2 py-2 text-xs font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                    Exportar Excel
                    <div class="tooltip-arrow" data-popper-arrow></div>
                </div>

                <button type="button"
                    class="rounded-md bg-blue-600 px-4 py-2 text-center text-sm text-white hover:bg-blue-500 h-full"
                    @click="openExportArchivesModal" data-tooltip-target="export-photo-tooltip">
                    <svg fill="#ffffff" width="20px" height="20px" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <g id="SVGRepo_bgCarrier" stroke-width="0" />

                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round" />

                        <g id="SVGRepo_iconCarrier">
                            <path
                                d="M22.71,6.29a1,1,0,0,0-1.42,0L20,7.59V2a1,1,0,0,0-2,0V7.59l-1.29-1.3a1,1,0,0,0-1.42,1.42l3,3a1,1,0,0,0,.33.21.94.94,0,0,0,.76,0,1,1,0,0,0,.33-.21l3-3A1,1,0,0,0,22.71,6.29ZM19,13a1,1,0,0,0-1,1v.38L16.52,12.9a2.79,2.79,0,0,0-3.93,0l-.7.7L9.41,11.12a2.85,2.85,0,0,0-3.93,0L4,12.6V7A1,1,0,0,1,5,6h8a1,1,0,0,0,0-2H5A3,3,0,0,0,2,7V19a3,3,0,0,0,3,3H17a3,3,0,0,0,3-3V14A1,1,0,0,0,19,13ZM5,20a1,1,0,0,1-1-1V15.43l2.9-2.9a.79.79,0,0,1,1.09,0l3.17,3.17,0,0L15.46,20Zm13-1a.89.89,0,0,1-.18.53L13.31,15l.7-.7a.77.77,0,0,1,1.1,0L18,17.21Z" />
                        </g>
                    </svg>
                </button>
                <div id="export-photo-tooltip" role="tooltip"
                    class="absolute z-10 invisible inline-block px-2 py-2 text-xs font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                    Facturas, Boletas y Vouchers de Pago
                    <div class="tooltip-arrow" data-popper-arrow></div>
                </div>

                <button data-tooltip-target="rejected_tooltip" type="button"
                    class="rounded-md bg-gray-100 px-4 py-1 text-center text-lg text-red-600 font-bold ring-2 ring-red-400 hover:bg-gray-100/2"
                    @click="() =>
                        router.visit(
                            route(
                                'projectmanagement.additionalCosts.rejected',
                                { project_id: project_id.id }
                            )
                        )
                    ">
                    R
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
                                <div class="">
                                    <button @click="openOpNuDaModal"
                                        class="block w-full text-left px-4 py-2 text-sm text-black-700 hover:bg-gray-200 hover:text-black focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                                        Actualizar Operación
                                    </button>
                                    <button @click="openSwapCostsModal"
                                        class="block w-full text-left px-4 py-2 text-sm text-black-700 hover:bg-gray-200 hover:text-black focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                                        Swap (gastos fijos)
                                    </button>
                                    <button @click="openSwapAPModal"
                                        class="block w-full text-left px-4 py-2 text-sm text-black-700 hover:bg-gray-200 hover:text-black focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                                        Swap (proyectos adicionales)
                                    </button>
                                    <button @click="openSwapRPModal"
                                        class="block w-full text-left px-4 py-2 text-sm text-black-700 hover:bg-gray-200 hover:text-black focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                                        Swap (proyectos mensuales/gep)
                                    </button>
                                    <!-- <button @click=""
                                            class="block w-full text-left px-4 py-2 text-sm text-black-700 hover:bg-gray-200 hover:text-black focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                                            Eliminar
                                        </button> -->
                                </div>
                            </div>
                        </template>
                    </dropdown>
                </div>

            </div>
            <div class="sm:hidden">
                <Dropdown align="left">
                    <template #trigger>
                        <button @click="dropdownOpen = !dropdownOpen"
                            class="relative block overflow-hidden rounded-md bg-gray-200 px-2 py-2 text-center text-sm text-white hover:bg-gray-100">
                            <MenuIcon />
                        </button>
                    </template>

                    <template #content class="origin-left">
                        <div>
                            <!-- Alineación a la derecha -->
                            <button @click="openCreateAdditionalModal"
                                class="block w-full text-left px-4 py-2 text-sm text-black-700 hover:bg-indigo-600 hover:text-white focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                                Agregar
                            </button>
                            <div class="">
                                <!-- <DropdownLink :href="route(
                                        'projectmanagement.additionalCosts',
                                        { project_id: project_id.id }
                                    )">
                                        Actualizar
                                    </DropdownLink> -->
                                <button @click="() => {
                                    filterForm = { ...initialFilterFormState }
                                }
                                "
                                    class="block w-full text-left px-4 py-2 text-sm text-black-700 hover:bg-indigo-600 hover:text-white focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                                    Actualizar
                                </button>
                            </div>
                            <button @click="openExportExcel()"
                                class="block w-full text-left px-4 py-2 text-sm text-black-700 hover:bg-indigo-600 hover:text-white focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                                Exportar Excel
                            </button>
                            <button @click="openExportArchivesModal()"
                                class="block w-full text-left px-4 py-2 text-sm text-black-700 hover:bg-indigo-600 hover:text-white focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                                Descarga de Archivos
                            </button>
                            <DropdownLink :href="route(
                                'projectmanagement.additionalCosts.rejected',
                                { project_id: project_id.id }
                            )">
                                Rechazados
                            </DropdownLink>
                        </div>
                    </template>
                </Dropdown>
            </div>

            <Search v-model:search="filterForm.search"
                fields="Ruc , Numero de Documento, Numero de Operacion, Descripcion, Monto Total" />
        </div>
    </div>
</template>
<script setup>
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import { ExcelIcon, MenuIcon, ServerIcon } from '@/Components/Icons';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import Search from '@/Components/Search.vue';
import { router } from '@inertiajs/vue3';

const { openOpNuDaModal, openSwapCostsModal, openSwapAPModal, openSwapRPModal, openCreateAdditionalModal, openExportExcel, openExportArchivesModal, project_id, initialFilterFormState } = defineProps({
    openOpNuDaModal: Function,
    openSwapCostsModal: Function,
    openSwapAPModal: Function,
    openSwapRPModal: Function,

    openCreateAdditionalModal: Function,
    openExportExcel: Function,
    openExportArchivesModal: Function,

    project_id: Object,
    initialFilterFormState: Object
})

const filterForm = defineModel('filterForm')
</script>