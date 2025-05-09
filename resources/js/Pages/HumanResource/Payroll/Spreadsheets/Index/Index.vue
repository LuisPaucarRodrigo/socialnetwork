<template>

    <Head title="Nomina" />
    <AuthenticatedLayout :redirectRoute="'payroll.index'">
        <template #header>
            Nomina de {{ payrolls.month }}
        </template>
        <Toaster richColors />
        <div class="min-w-full min-h-full overflow-hidden">
            <TableHeader v-model:spreadsheets="spreadsheets" v-model:totals="totals" :payrolls="payrolls"
                :openPayrollApprove="openPayrollApprove" />
            <SpreadsheetsTable :spreadsheets="spreadsheets" :totals="totals" :payrolls="payrolls"
                :userPermissions="userPermissions" :openPaymentSalaryModal="openPaymentSalaryModal"
                :openPaymentTravelExpenseModal="openPaymentTravelExpenseModal" :openDiscountModal="openDiscountModal" />
        </div>
        <TravelESalaryForm ref="travelESalaryForm" :spreadsheets="spreadsheets" />
        <EmployeeDiscountForm ref="employeeDiscountForm" :spreadsheets="spreadsheets" />
        <ApprovePayroll v-model:payrolls="payrolls" ref="approvePayroll" />
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';
import TableHeader from './components/TableHeader.vue';
import SpreadsheetsTable from './components/SpreadsheetsTable.vue';
import TravelESalaryForm from './components/TravelESalaryForm.vue';
import EmployeeDiscountForm from './components/EmployeeDiscountForm.vue';
import ApprovePayroll from './components/ApprovePayroll.vue';
import { Toaster } from 'vue-sonner';


const { spreadsheet, payroll, total, userPermissions } = defineProps({
    spreadsheet: Object,
    payroll: Object,
    total: Object,
    userPermissions: Array
})


const spreadsheets = ref(spreadsheet)
const payrolls = ref(payroll)
const totals = ref(total)

const travelESalaryForm = ref(null)
const employeeDiscountForm = ref(null)
const approvePayroll = ref(null)


function openPaymentSalaryModal(payroll_detail) {
    travelESalaryForm.value.openPaymentSalaryModal(payroll_detail)
}

function openPaymentTravelExpenseModal(payroll_detail) {
    travelESalaryForm.value.openPaymentTravelExpenseModal(payroll_detail)
}

function openDiscountModal(employee_id, discount) {
    employeeDiscountForm.value.openDiscountModal(employee_id, discount)
}

function openPayrollApprove() {
    approvePayroll.value.openPayrollApprove()
}

</script>