<template>
    <div class="mt-6 flex flex-col sm:flex-row sm:items-center justify-between sm:gap-x-3 gap-y-4">
        <div class="flex items-center justify-between gap-x-6 w-full">
            <div class="hidden sm:flex sm:items-center sm:space-x-2 pl-3">
                <!-- <button v-if="!payrolls.state" @click="openPayrollApprove()"
                    class="rounded-md px-1 py-2 text-center text-sm text-white hover:bg-green-400">
                    <AcceptIcon />
                </button> -->
                <button @click="openPaySpreadsheet()"
                    class="rounded-md px-1 py-2 text-blue-600 text-center text-sm hover:bg-blue-200">
                    <DolarIcon />
                </button>
                <button @click="openPaySpreadsheet()"
                    class="rounded-md px-1 py-2 text-blue-600 text-center text-sm hover:bg-blue-200">
                    <DolarIcon color="text-blue-600" />
                </button>


                <Link :href="route('payroll.index.payroll.external.detail', { payroll_id: payrolls.id })"
                    class="bg-indigo-600 hover:bg-indigo-500 rounded-md px-4 py-2 text-center text-sm text-white">
                PS 4ta categoría
                </Link>
                <Link :href="route('payroll.detail.expense.index', { payroll_id: payrolls.id })"
                    class="bg-gray-600 hover:bg-gray-500 rounded-md px-4 py-2 text-center text-sm text-white">
                Pagos
                </Link>


                <div>
                    <dropdown align="left">
                        <template #trigger>
                            <button @click="dropdownOpen = !dropdownOpen"
                                class="relative block overflow-hidden rounded-md text-white bg-green-600 hover:bg-green-500 text-center text-sm p-2">
                                Exportar
                            </button>
                        </template>

                        <template #content class="origin-left">
                            <div>
                                <!-- Alineación a la derecha -->
                                <div class="">
                                    <button @click="exportGeneralExcel()"
                                        class="block w-full text-left px-4 py-2 text-sm text-black-700 hover:bg-gray-200 hover:text-black focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                                        Excel general
                                    </button>
                                    <button @click="openExportSpreadsheet()"
                                        class="block w-full text-left px-4 py-2 text-sm text-black-700 hover:bg-gray-200 hover:text-black focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                                        Excel detallado
                                    </button>

                                </div>
                            </div>
                        </template>
                    </dropdown>
                </div>
            </div>
            <div class="sm:hidden">
                <dropdown align='left'>
                    <template #trigger>
                        <button @click="dropdownOpen = !dropdownOpen"
                            class="relative block overflow-hidden rounded-md bg-gray-200 px-2 py-2 text-center text-sm text-white hover:bg-gray-100">
                            <svg width="25px" height="25px" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M4 6H20M4 12H20M4 18H20" stroke="#000000" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </button>
                    </template>

                    <template #content class="origin-left">
                        <div>
                            <div class="dropdown">
                                <div class="dropdown-menu">

                                </div>
                            </div>
                        </div>
                    </template>
                </dropdown>
            </div>
            <div>
                <!-- <TextInput type="text" placeholder="Buscar..." @input="search($event.target.value)" /> -->
                <TextInput data-tooltip-target="search_fields" type="text" placeholder="Buscar..."
                    v-model="filterForm.search" @keyup.enter="search_advance()" />
                <div id="search_fields" role="tooltip"
                    class="absolute z-10 invisible inline-block px-2 py-2 text-xs font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                    Nombre
                    <div class="tooltip-arrow" data-popper-arrow></div>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup>
import Dropdown from '@/Components/Dropdown.vue';
import TextInput from '@/Components/TextInput.vue';
import { Link } from '@inertiajs/vue3';
import { DolarIcon } from '@/Components/Icons';

const { payrolls, filterForm, openPaySpreadsheet, search_advance, openExportSpreadsheet } = defineProps({
    payrolls: Object,
    filterForm: Object,
    openPaySpreadsheet: Function,
    search_advance: Function,
    openExportSpreadsheet: Function,
})

function exportGeneralExcel() {
    const uniqueParam = `timestamp=${new Date().getTime()}`;
    const url =
        route('payroll.detail.export', { payroll_id: payrolls.id }) +
        "?" +
        uniqueParam;
    window.location.href = url;
}

</script>