<template>
    <TableStructure :info="spreadsheets">
        <template #thead>
            <tr class="bg-gray-100">
                <TableTitle></TableTitle>
                <th
                    class="sticky left-0 border-b-2 border-gray-200 bg-gray-100 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600">
                    <label :for="`check-all`" class="flex gap-3 justify-center tex-center py-3 w-16">
                        <input @change="handleCheckAll" :id="`check-all`" :checked="actionForm.ids.length > 0"
                            type="checkbox" />
                        {{ actionForm.ids.length ?? "" }}
                    </label>
                </th>
                <th
                    class="sticky z-10 left-16 border-b-2 border-gray-200 bg-amber-200 text-gray-600 text-center text-xs whitespace-nowrap tabular-nums font-semibold tracking-wider uppercase px-5 py-3">
                    Nombre
                </th>
                <TableTitle>DNI</TableTitle>
                <TableTitle>
                    <TableHeaderFilter label="Reg. Pen." :options="pensionTypes"
                        v-model="filterForm.selectedPensionTypes" width="w-44" />
                </TableTitle>
                <TableTitle>Fecha Ingreso</TableTitle>
                <TableTitle>Sueldo</TableTitle>
                <TableTitle>Ingresos devengados</TableTitle>
                <TableTitle>Ingresos pagados</TableTitle>
                <TableTitle>Descuentos</TableTitle>
                <TableTitle>Aporte de trabajador</TableTitle>
                <TableTitle>Neto a pagar</TableTitle>
                <TableTitle>Aporte empleador</TableTitle>
                <TableTitle></TableTitle>
            </tr>
        </template>

        <template #tbody>
            <tr v-for="(item, i) in spreadsheets" :key="item.id" class="bg-white">
                <TableRow>{{ i + 1 }}</TableRow>
                <td class="sticky left-0 border-b border-gray-200 bg-amber-100 text-center tabular-nums">
                    <label :for="`check-${item.id}`" class="block px-2 py-3">
                        <input v-model="actionForm.ids" :value="item.id" :id="`check-${item.id}`" type="checkbox" />
                    </label>
                </td>
                <TableRow :style="'sticky z-10 left-16 bg-amber-200 whitespace-nowrap'">
                    {{ item.employee.name }} {{ item.employee.lastname }}
                </TableRow>
                <TableRow>{{ item.employee.dni }}</TableRow>
                <TableRow>{{ item.pension.type }}</TableRow>
                <TableRow>{{ formattedDate(item.hire_date) }}</TableRow>
                <TableRow :style="'text-right'">S/ {{ item.basic_salary.toFixed(2) }}</TableRow>
                <TableRow :style="'text-right'">S/ {{ item.new_totals.income_accrued_total.toFixed(2) }}</TableRow>
                <TableRow :style="'text-right'">S/ {{ item.new_totals.income_paid_total.toFixed(2) }}</TableRow>
                <TableRow :style="'text-right'">S/ {{ item.new_totals.discount_total.toFixed(2) }}</TableRow>
                <TableRow :style="'text-right'">S/ {{ item.new_totals.employee_tac_total.toFixed(2) }}</TableRow>
                <TableRow :style="'text-right bg-green-200'">S/ {{ item.new_totals.net_pay.toFixed(2) }}</TableRow>
                <TableRow :style="'text-right'">S/ {{ item.new_totals.employer_tac_total.toFixed(2) }}</TableRow>
                <TableRow>
                    <div class="flex gap-x-3">
                        <Link
                            :href="route('spreadsheets.details.index', { payroll_details_id: item.id, employee_id: item.employee_id })">
                        <EditIcon />
                        </Link>
                        <a :href="route('spreadsheets.generate.bill', { payroll_detail_id: item.id }) +
                            '?' +
                            uniqueParam" target="_blank">
                            <DocumentIcon />
                        </a>
                    </div>
                </TableRow>
            </tr>
            <tr>
                <TableRow :colspan="6">Totales:</TableRow>
                <TableRow :style="'text-right'">S/ {{ totals.sum_salary.toFixed(2) }}</TableRow>
                <TableRow :style="'text-right'">S/ {{ totals.income_accrued_total.toFixed(2) }}</TableRow>
                <TableRow :style="'text-right'">S/ {{ totals.income_paid_total.toFixed(2) }}</TableRow>
                <TableRow :style="'text-right'">S/ {{ totals.discount_total.toFixed(2) }}</TableRow>
                <TableRow :style="'text-right'">S/ {{ totals.employee_tac_total.toFixed(2) }}</TableRow>
                <TableRow :style="'text-right'">S/ {{ totals.net_pay.toFixed(2) }}</TableRow>
                <TableRow :style="'text-right'">S/ {{ totals.employer_tac_total.toFixed(2) }}</TableRow>
                <TableRow></TableRow>
            </tr>
        </template>
    </TableStructure>

</template>
<script setup>
import TableTitle from '@/Components/TableTitle.vue';
import TableRow from '@/Components/TableRow.vue';
import TableStructure from '@/Layouts/TableStructure.vue';
import TableHeaderFilter from "@/Components/TableHeaderFilter.vue";
import { formattedDate } from '@/utils/utils';
import { Link } from '@inertiajs/vue3';
import { DocumentIcon, EditIcon } from '@/Components/Icons';

const {
    spreadsheets,
    totals,
    pensionTypes,
    actionForm,
    filterForm,
} = defineProps({
    spreadsheets: Object,
    totals: Object,
    pensionTypes: Array,
    actionForm: Object,
    filterForm: Object,
})
const uniqueParam = `timestamp=${new Date().getTime()}`;

const handleCheckAll = (e) => {
    if (e.target.checked) { actionForm.ids = spreadsheets.map((item) => item.id); }
    else { actionForm.ids = []; }
};

</script>