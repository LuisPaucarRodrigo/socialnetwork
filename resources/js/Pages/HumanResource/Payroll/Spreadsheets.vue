<template>

    <Head title="Nomina" />
    <AuthenticatedLayout :redirectRoute="'spreadsheets.index'">
        <template #header>
            Nomina
        </template>
        <div class="min-w-full overflow-hidden rounded-lg shadow">
            <div class="mt-6 flex flex-col sm:flex-row sm:items-center justify-between sm:gap-x-3 gap-y-4">
                <div class="flex items-center justify-between gap-x-6 w-full">

                    <div class="hidden sm:flex sm:space-x-3 sm:items-center">
                        <button @click="management_pension" type="button"
                            class="rounded-md bg-indigo-600 px-4 py-2 text-center text-sm text-white hover:bg-indigo-500">
                            Gestion de Sistema de Pension
                        </button>
                        <a :href="route('spreadsheets.payroll.export')"
                            class="rounded-md bg-green-600 px-4 py-2 text-center text-sm text-white hover:bg-green-500">
                            Exportar
                        </a>
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
                                <div> <!-- Alineación a la derecha -->
                                    <div class="dropdown">
                                        <div class="dropdown-menu">
                                            <button @click="management_pension" type="button"
                                                class="dropdown-item block w-full text-left px-4 py-2 text-sm text-black-700 hover:bg-indigo-600 hover:text-white focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                                                Gestion de Sistema de Pension
                                            </button>
                                        </div>
                                    </div>
                                    <div class="dropdown">
                                        <div class="dropdown-menu">
                                            <a :href="route('spreadsheets.payroll.export')"
                                                class="dropdown-item block w-full text-left px-4 py-2 text-sm text-black-700 hover:bg-indigo-600 hover:text-white focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                                                Exportar
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </dropdown>
                    </div>
                    <button @click="reentry" type="button"
                        class="rounded-md bg-indigo-600 px-4 py-2 text-center text-sm text-white hover:bg-indigo-500">
                        {{ reentrystate == false ? "Inactivos" : "Activos" }}
                    </button>
                </div>

                <div class="flex items-center">
                    <form @submit.prevent="search" class="flex items-center w-full sm:w-auto">
                        <input type="text" placeholder="Buscar..."
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            v-model="searchForm.searchTerm" />
                        <button type="submit" :class="{ 'opacity-25': searchForm.processing }"
                            class="ml-2 rounded-md bg-indigo-600 px-2 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                            <svg width="30px" height="21px" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M15.7955 15.8111L21 21M18 10.5C18 14.6421 14.6421 18 10.5 18C6.35786 18 3 14.6421 3 10.5C3 6.35786 6.35786 3 10.5 3C14.6421 3 18 6.35786 18 10.5Z"
                                    stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full whitespace-no-wrap">
                    <thead>
                        <tr
                            class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                            <th
                                class="w-100 border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Estado
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                DNI
                            </th>
                            <th
                                class="sticky left-0 z-10 bg-amber-200 px-7 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Nombre
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-7 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                REG. PEN
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-7 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Fecha Ingreso
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-9 mx-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Sueldo
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-7 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Vac. Truncas
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-7 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Ingreso
                                Total
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Tot.B.G.Sis. Pensionario
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-7 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                %.SNP
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-7 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                SNP/ONP
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-7 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                %.COM
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-7 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                %.Com. Sobre R.A.
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-7 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                % SEG
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-7 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                PRIMA SEGURO
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-7 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                % APORT. OBLIG.
                            </th>

                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-7 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                MONT0 OBLIGA.
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                DESCUENTO TOTAL
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-green-200 px-9 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                NETO PAGAR
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-9 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                TOT. BASE GRAV. ESSAL.
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-9 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                SALUD 9%
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-9 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                VIDA LEY
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-7 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                APORTE TOTAL
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="spreadsheet in (props.search === '' ? spreadsheets.data : spreadsheets)" :key="spreadsheet.id" class="text-gray-700">
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ spreadsheet.state }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ spreadsheet.employee.dni }}</p>
                            </td>
                            <td class="sticky left-0 border-b bg-amber-200 px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ spreadsheet.employee.name }} {{ spreadsheet.employee.lastname }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ spreadsheet.pension.type }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">
                                    {{ formattedDate(spreadsheet.hire_date) }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">
                                    S/ {{ spreadsheet.basic_salary.toFixed(2) }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">
                                    S/ {{ spreadsheet.truncated_vacations.toFixed(2) }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">S/ {{ spreadsheet.total_income.toFixed(2)
                                    }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">
                                    S/ {{ spreadsheet.total_pension_base.toFixed(2) }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">% {{ spreadsheet.snp.toFixed(2) }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">S/ {{ spreadsheet.snp_onp.toFixed(2) }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">% {{ spreadsheet.commission.toFixed(2) }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">
                                    S/ {{ spreadsheet.commission_on_ra.toFixed(2) }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">% {{ spreadsheet.seg.toFixed(2) }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">
                                    S/ {{ spreadsheet.insurance_premium.toFixed(2) }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">
                                    % {{ spreadsheet.mandatory_contribution.toFixed(2) }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">
                                    S/ {{ spreadsheet.mandatory_contribution_amount.toFixed(2) }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">S/ {{ spreadsheet.total_discount.toFixed(2)
                                    }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-green-200 px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">
                                    S/ {{ spreadsheet.net_pay.toFixed(2) }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">
                                    S/ {{ spreadsheet.total_pension_base.toFixed(2) }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">S/ {{ spreadsheet.healths.toFixed(2) }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">S/ {{ spreadsheet.life_ley.toFixed(2) }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">
                                    S/ {{ spreadsheet.total_contribution.toFixed(2) }}
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm" colspan="5">Totales:</td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                S/ {{ total.sum_salary.toFixed(2) }}
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                S/ {{ total.sum_truncated_vacations.toFixed(2) }}
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                S/ {{ total.sum_total_income.toFixed(2) }}
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm" colspan="2">
                                S/ {{ total.sum_total_income.toFixed(2) }}
                            </td>

                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm" colspan="2">
                                S/ {{ total.sum_snp_onp.toFixed(2) }}
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm" colspan="2">
                                S/ {{ total.sum_commission_on_ra.toFixed(2) }}
                            </td>

                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm" colspan="2">
                                S/ {{ total.sum_insurance_premium.toFixed(2) }}
                            </td>

                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                S/ {{ total.sum_mandatory_contribution_amount.toFixed(2) }}
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                S/ {{ total.sum_total_discount.toFixed(2) }}
                            </td>
                            <td class="border-b border-gray-200 bg-green-200 px-5 py-5 text-sm">
                                S/ {{ total.sum_net_pay.toFixed(2) }}</td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                S/ {{ total.sum_total_income.toFixed(2) }}</td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                S/ {{ total.sum_health.toFixed(2) }}
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                S/ {{ total.sum_life_ley.toFixed(2) }}
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                S/ {{ total.sum_total_contribution.toFixed(2) }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div
                class="flex flex-col items-center border-t bg-white px-5 py-5 xs:flex-row xs:justify-between">
                <Pagination :links="spreadsheets.links" />
            </div>
        </div>

    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { formattedDate } from '@/utils/utils';
import { Head, router, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import Dropdown from '@/Components/Dropdown.vue';
import Pagination from '@/Components/Pagination.vue';

const props = defineProps({
    spreadsheets: Object,
    boolean: Boolean,
    total: Object,
    search: String
})

const reentrystate = ref(props.boolean);


const management_pension = () => {
    router.get(route('pension_system.edit'));
};

const reentry = () => {
    if (props.boolean == true) {
        reentrystate.value = false
        router.get(route('spreadsheets.index'))
    } else {
        reentrystate.value = true
        router.get(route('spreadsheets.index', { reentry: reentrystate.value }))
    }
};

const formatNumber = (value) => {
    return parseFloat(value).toFixed(2);
}


const searchForm = useForm({
    searchTerm: props.search,
})

const search = () => {
    let data = {searchTerm: searchForm.searchTerm}
    if (!props.boolean == true) {
        reentrystate.value = false
        router.get(route('spreadsheets.index'), data)
    } else {
        reentrystate.value = true
        router.get(route('spreadsheets.index', { reentry: reentrystate.value }),data)
    }
    
}











</script>