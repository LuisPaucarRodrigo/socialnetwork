<template>
    <Head title="Nomina" />
    <AuthenticatedLayout :redirectRoute="'spreadsheets.index'">
        <template #header>
            Nomina
        </template>
        <div class="mt-6 flex items-center justify-between gap-x-6">
            <button @click="management_pension" type="button"
                class="rounded-md bg-indigo-600 px-4 py-2 text-center text-sm text-white hover:bg-indigo-500">
                Gestion de Sistema de Pension
            </button>
            <div class="flex space-x-3">
                <button @click="reentry" type="button"
                    class="rounded-md bg-indigo-600 px-4 py-2 text-center text-sm text-white hover:bg-indigo-500">
                    {{ reentrystate == false ? "Inactivos" : "Activos" }}
                </button>
                <a :href="route('spreadsheets.payroll.export')"
                    class="rounded-md bg-green-600 px-4 py-2 text-center text-sm text-white hover:bg-green-500">
                    Exportar
                </a>
            </div>

        </div>
        <div class="overflow-x-auto">
            <table class="w-full whitespace-no-wrap">
                <thead>
                    <tr class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
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
                            class="border-b-2 border-gray-200 bg-gray-100 px-5 mx-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
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
                            class="border-b-2 border-gray-200 bg-gray-100 px-7 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
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
                    <tr v-for="spreadsheet in spreadsheets" :key="spreadsheet.id" class="text-gray-700">
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                            <p class="text-gray-900 whitespace-no-wrap">{{ spreadsheet.state }}</p>
                        </td>
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                            <p class="text-gray-900 whitespace-no-wrap">{{ spreadsheet.dni }}</p>
                        </td>
                        <td class="sticky left-0 border-b bg-amber-200 px-5 py-5 text-sm">
                            <p class="text-gray-900 whitespace-no-wrap">{{ spreadsheet.name }}</p>
                        </td>
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                            <p class="text-gray-900 whitespace-no-wrap">{{ spreadsheet.pension_reg }}</p>
                        </td>
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                            <p class="text-gray-900 whitespace-no-wrap">{{ spreadsheet.hire_date }}</p>
                        </td>
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                            <p class="text-gray-900 whitespace-no-wrap">S/ {{ spreadsheet.salary }}</p>
                        </td>
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                            <p class="text-gray-900 whitespace-no-wrap">S/ {{ spreadsheet.truncated_vacations }}</p>
                        </td>
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                            <p class="text-gray-900 whitespace-no-wrap">S/ {{ formatNumber(spreadsheet.total_income) }}</p>
                        </td>
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                            <p class="text-gray-900 whitespace-no-wrap">S/ {{ spreadsheet.total_pension_base }}</p>
                        </td>
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                            <p class="text-gray-900 whitespace-no-wrap">% {{ spreadsheet.snp }}</p>
                        </td>
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                            <p class="text-gray-900 whitespace-no-wrap">S/ {{ spreadsheet.snp_onp }}</p>
                        </td>
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                            <p class="text-gray-900 whitespace-no-wrap">% {{ spreadsheet.commission }}</p>
                        </td>
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                            <p class="text-gray-900 whitespace-no-wrap">S/ {{ spreadsheet.commission_on_ra }}</p>
                        </td>
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                            <p class="text-gray-900 whitespace-no-wrap">% {{ spreadsheet.seg }}</p>
                        </td>
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                            <p class="text-gray-900 whitespace-no-wrap">S/ {{ spreadsheet.insurance_premium }}</p>
                        </td>
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                            <p class="text-gray-900 whitespace-no-wrap">% {{ spreadsheet.mandatory_contribution }}</p>
                        </td>
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                            <p class="text-gray-900 whitespace-no-wrap">S/ {{ spreadsheet.mandatory_contribution_amount }}
                            </p>
                        </td>
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                            <p class="text-gray-900 whitespace-no-wrap">S/ {{ spreadsheet.total_discount }}</p>
                        </td>
                        <td class="border-b border-gray-200 bg-green-200 px-5 py-5 text-sm">
                            <p class="text-gray-900 whitespace-no-wrap">S/ {{ formatNumber(spreadsheet.net_pay) }}</p>
                        </td>
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                            <p class="text-gray-900 whitespace-no-wrap">S/ {{ spreadsheet.total_pension_base }}</p>
                        </td>
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                            <p class="text-gray-900 whitespace-no-wrap">S/ {{ spreadsheet.health }}</p>
                        </td>
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                            <p class="text-gray-900 whitespace-no-wrap">S/ {{ spreadsheet.life_ley }}</p>
                        </td>
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                            <p class="text-gray-900 whitespace-no-wrap">S/ {{ spreadsheet.total_contribution }}</p>
                        </td>
                    </tr>
                    <tr>
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm" colspan="5">Totales:</td>
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm" >S/ {{ total.sum_salary }}</td>
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm" >S/ {{ total.sum_truncated_vacations }}</td>
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm" >S/ {{ formatNumber(total.sum_total_income) }}</td>
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm" >S/ {{ formatNumber(total.sum_total_income) }}</td>
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm" ></td>
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm" >S/ {{ total.sum_snp_onp }}</td>
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm" ></td>
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm" >S/ {{ total.sum_commission_on_ra }}</td>
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm" ></td>
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm" >S/ {{ total.sum_insurance_premium }}</td>
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm" ></td>
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm" >S/ {{ total.sum_mandatory_contribution_amount }}</td>
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm" >S/ {{ total.sum_total_discount }}</td>
                        <td class="border-b border-gray-200 bg-green-200 px-5 py-5 text-sm" >S/ {{ formatNumber(total.sum_net_pay) }}</td>
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm" >S/ {{ formatNumber(total.sum_total_income) }}</td>
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm" >S/ {{ total.sum_health }}</td>
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm" >S/ {{ total.sum_life_ley }}</td>
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm" >S/ {{ total.sum_total_contribution }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    spreadsheets: Object,
    boolean: Boolean,
    total: Object
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
</script>