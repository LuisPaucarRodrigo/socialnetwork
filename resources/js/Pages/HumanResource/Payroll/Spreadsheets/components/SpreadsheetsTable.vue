<template>
    <TableStructure>
        <template #thead>
            <tr
                class="sticky top-0 z-20 border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                <TableTitle>DNI</TableTitle>
                <th
                    class="sticky left-0 z-10 bg-amber-200 px-7 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 top-0 z-5">
                    Nombre
                </th>
                <TableTitle>REG. PEN</TableTitle>
                <TableTitle>Fecha Ingreso</TableTitle>
                <TableTitle>Sueldo</TableTitle>
                <TableTitle>Pago por días</TableTitle>
                <TableTitle>Descuento</TableTitle>
                <TableTitle>Vac. Truncas</TableTitle>
                <TableTitle>Ingreso Total</TableTitle>
                <TableTitle>Tot.B.G.Sis. Pensionario</TableTitle>
                <TableTitle>%.SNP</TableTitle>
                <TableTitle>SNP/ONP</TableTitle>
                <TableTitle>%.COM</TableTitle>
                <TableTitle>%.Com. Sobre R.A.</TableTitle>
                <TableTitle>% SEG</TableTitle>
                <TableTitle>PRIMA SEGURO</TableTitle>
                <TableTitle>% APORT. OBLIG.</TableTitle>
                <TableTitle>MONTO OBLIGA.</TableTitle>
                <TableTitle>DESCUENTO TOTAL</TableTitle>
                <TableTitle>Viáticos</TableTitle>
                <TableTitle>NETO PAGAR</TableTitle>
                <TableTitle>TOT. BASE GRAV. ESSAL.</TableTitle>
                <TableTitle>SALUD 9%</TableTitle>
                <TableTitle>VIDA LEY</TableTitle>
                <TableTitle>SCTR P</TableTitle>
                <TableTitle>SCTR S</TableTitle>
                <TableTitle>APORTE TOTAL</TableTitle>

            </tr>
        </template>
        <template #tbody>
            <tr v-for="spreadsheet in spreadsheets" :key="spreadsheet.id" class="text-gray-700">
                <TableRow>{{ spreadsheet.employee.dni }}</TableRow>
                <TableRow :style="'sticky left-0 bg-amber-200 whitespace-nowrap'">
                    {{ spreadsheet.employee.name }} {{
                        spreadsheet.employee.lastname }}
                </TableRow>
                <TableRow>{{ spreadsheet.pension.type }}</TableRow>
                <TableRow>{{ formattedDate(spreadsheet.hire_date) }}</TableRow>
                <TableRow>S/ {{ spreadsheet.basic_salary.toFixed(2) }}</TableRow>
                <TableRow>S/ {{ spreadsheet.payment_until_today.toFixed(2) }}</TableRow>
                <TableRow>
                    <div class="flex gap-x-3">
                        <p class="text-gray-900 whitespace-nowrap">
                            S/ {{ spreadsheet.discount.toFixed(2) }}
                        </p>
                        <button @click="openDiscountModal(spreadsheet.employee_id, spreadsheet.discount)">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="w-4 h-4 text-amber-400">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                            </svg>
                        </button>
                    </div>
                </TableRow>
                <TableRow>S/ {{ spreadsheet.truncated_vacations.toFixed(2) }}</TableRow>
                <TableRow>S/ {{ spreadsheet.total_income.toFixed(2) }}</TableRow>
                <TableRow>S/ {{ spreadsheet.total_pension_base.toFixed(2) }}</TableRow>
                <TableRow>% {{ spreadsheet.snp.toFixed(2) }}</TableRow>
                <TableRow>S/ {{ spreadsheet.snp_onp.toFixed(2) }}</TableRow>
                <TableRow>% {{ spreadsheet.commission.toFixed(2) }}</TableRow>
                <TableRow>S/ {{ spreadsheet.commission_on_ra.toFixed(2) }}</TableRow>
                <TableRow>% {{ spreadsheet.seg.toFixed(2) }}</TableRow>
                <TableRow>S/ {{ spreadsheet.insurance_premium.toFixed(2) }}</TableRow>
                <TableRow>% {{ spreadsheet.mandatory_contribution.toFixed(2) }}</TableRow>
                <TableRow>S/ {{ spreadsheet.mandatory_contribution_amount.toFixed(2) }}</TableRow>
                <TableRow>S/ {{ spreadsheet.total_discount.toFixed(2) }}</TableRow>
                <TableRow>
                    <div class="flex gap-x-3">
                        <p class="text-gray-900 whitespace-nowrap">
                            S/ {{
                                spreadsheet.amount_travel_expenses ?
                                    spreadsheet.amount_travel_expenses.toFixed(2) :
                                    '0.00'
                            }}
                        </p>
                        <template
                            v-if="!payrolls.state && spreadsheet.amount_travel_expenses && permissions('HumanResourceManager')">
                            <button
                                v-if="spreadsheet.payroll_detail_expense[1].operation_number && spreadsheet.payroll_detail_expense[1].operation_date"
                                @click="openPaymentTravelExpenseModal(spreadsheet.payroll_detail_expense[1])">
                                <ShowIcon />
                            </button>
                            <button v-else
                                @click="openPaymentTravelExpenseModal(spreadsheet.payroll_detail_expense[1])">
                                <EditIcon />
                            </button>
                        </template>
                    </div>
                </TableRow>
                <TableRow :style="'bg-green-200'">
                    <div class="flex gap-x-3">
                        <p class="text-gray-900 whitespace-nowrap">
                            S/ {{ spreadsheet.net_pay.toFixed(2) }}
                        </p>
                        <template v-if="!payrolls.state && permissions('HumanResourceManager')">
                            <button
                                v-if="!payrolls.state && permissions('HumanResourceManager') && spreadsheet.payroll_detail_expense[0].operation_number && spreadsheet.payroll_detail_expense[0].operation_date"
                                @click="openPaymentSalaryModal(spreadsheet.payroll_detail_expense[0])">
                                <ShowIcon />
                            </button>
                            <button v-else @click="openPaymentSalaryModal(spreadsheet.payroll_detail_expense[0])">
                                <EditIcon />
                            </button>
                        </template>

                    </div>
                </TableRow>

                <TableRow>S/ {{ spreadsheet.total_pension_base.toFixed(2) }}</TableRow>
                <TableRow>S/ {{ spreadsheet.healths.toFixed(2) }}</TableRow>
                <TableRow>S/ {{ spreadsheet.life_ley?.toFixed(2) }}</TableRow>
                <TableRow>S/ {{ spreadsheet.discount_sctr ? spreadsheet.sctr_p.toFixed(2) : 0.00 }}
                </TableRow>
                <TableRow>S/ {{ spreadsheet.discount_sctr ? spreadsheet.sctr_s.toFixed(2) : 0.00 }}
                </TableRow>
                <TableRow>S/ {{ spreadsheet.total_contribution.toFixed(2) }}</TableRow>
            </tr>
            <tr class="sticky bottom-0 z-5">
                <TableRow :colspan="4">Totales:</TableRow>
                <TableRow>S/ {{ totals.sum_salary.toFixed(2) }}</TableRow>
                <TableRow>S/ {{ totals.sum_payment_until_today.toFixed(2) }}</TableRow>
                <TableRow>S/ {{ totals.sum_discount.toFixed(2) }}</TableRow>
                <TableRow>S/ {{ totals.sum_truncated_vacations.toFixed(2) }}</TableRow>
                <TableRow>S/ {{ totals.sum_total_income.toFixed(2) }}</TableRow>
                <TableRow :colspan="2">S/ {{ totals.sum_total_income.toFixed(2) }}</TableRow>
                <TableRow :colspan="2">S/ {{ totals.sum_snp_onp.toFixed(2) }}</TableRow>
                <TableRow :colspan="2">S/ {{ totals.sum_commission_on_ra.toFixed(2) }}</TableRow>
                <TableRow :colspan="2">S/ {{ totals.sum_insurance_premium.toFixed(2) }}</TableRow>
                <TableRow>S/ {{ totals.sum_mandatory_contribution_amount.toFixed(2) }}
                </TableRow>
                <TableRow>S/ {{ totals.sum_total_discount.toFixed(2) }}</TableRow>
                <TableRow>S/ {{ totals.sum_amount_travel_expenses.toFixed(2) }}</TableRow>
                <TableRow :style="'bg-green-200'">S/ {{ totals.sum_net_pay.toFixed(2) }}</TableRow>

                <TableRow>S/ {{ totals.sum_total_income.toFixed(2) }}</TableRow>
                <TableRow>S/ {{ totals.sum_health.toFixed(2) }}</TableRow>
                <TableRow>S/ {{
                    totals.sum_life_ley.toFixed(2) }}</TableRow>
                <TableRow>S/ {{ totals.sum_sctr_p.toFixed(2) }}</TableRow>
                <TableRow>S/ {{ totals.sum_sctr_s.toFixed(2) }}</TableRow>
                <TableRow>S/ {{ totals.sum_total_contribution.toFixed(2) }}</TableRow>
            </tr>
        </template>
    </TableStructure>
</template>
<script setup>
import TableTitle from '@/Components/TableTitle.vue';
import TableRow from '@/Components/TableRow.vue';
import TableStructure from '@/Layouts/TableStructure.vue';
import { formattedDate } from '@/utils/utils';
import ShowIcon from '@/Components/Icons/ShowIcon.vue';
import EditIcon from '@/Components/Icons/EditIcon.vue';

const { spreadsheets, totals, payrolls, userPermissions, openPaymentTravelExpenseModal, openPaymentSalaryModal, openDiscountModal } = defineProps({
    spreadsheets: Object,
    totals: Object,
    payrolls: Object,
    userPermissions: Array,
    openPaymentTravelExpenseModal: Function,
    openPaymentSalaryModal: Function,
    openDiscountModal: Function
})

function permissions(permission) {
    return userPermissions.includes(permission)
}
</script>