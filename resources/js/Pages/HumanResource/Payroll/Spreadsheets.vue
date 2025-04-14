<template>

    <Head title="Nomina" />
    <AuthenticatedLayout :redirectRoute="'payroll.index'">
        <template #header>
            Nomina de {{ payrolls.month }}
        </template>
        <div class="min-w-full min-h-full overflow-hidden">
            
            
        </div>
        
        
        <ConfirmateModal :showConfirm="showPayrollModal" tittle="Aprobación de la Nómina"
            text="Una vez que la nómina sea aprobada, no se podrán realizar modificaciones ni acciones posteriores. Por favor, asegúrate de revisar toda la información antes de proceder con la aprobación final."
            @closeModal="openPayrollApprove" :actionFunction="payrollApprove" />
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { formattedDate, setAxiosErrors } from '@/utils/utils';
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import Modal from '@/Components/Modal.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { notify, notifyError, notifyWarning } from '@/Components/Notification';
import ConfirmateModal from '@/Components/ConfirmateModal.vue';


const { spreadsheet, payroll, total, userPermissions } = defineProps({
    spreadsheet: Object,
    payroll: Object,
    total: Object,
    userPermissions: Array
})


const spreadsheets = ref(spreadsheet)
const payrolls = ref(payroll)
const totals = ref(total)

const travelOrSalary = ref(false)
const showPayrollModal = ref(false)




function openDiscountModal(employee_id) {

}

function openPaymentSalaryModal(payroll_detail) {
    formPayment.defaults({ id: payroll_detail.payroll_detail_id, operation_date: payroll_detail.operation_date, operation_number: payroll_detail.operation_number })
    formPayment.reset()
    showPaymentModal.value = true
    travelOrSalary.value = false
}

function openPaymentTravelExpenseModal(payroll_detail) {
    formPayment.defaults({ id: payroll_detail.payroll_detail_id, operation_date: payroll_detail.operation_date, operation_number: payroll_detail.operation_number })
    formPayment.reset()
    showPaymentModal.value = true
    travelOrSalary.value = true
}

function closePaymentModal() {
    showPaymentModal.value = false
    formPayment.clearErrors()
    formPayment.defaults({ ...initialStament })
    formPayment.reset()
}



function openPayrollApprove() {
    showPayrollModal.value = !showPayrollModal.value
}

async function payrollApprove() {
    let url = route('payroll.state.update', { payroll_id: payrolls.value.id })
    try {
        let response = await axios.get(url)
        payrolls.value = response.data
        openPayrollApprove()
        notify('Aprobación de Nomina Existosa')
    } catch (error) {
        if (error.response.data) {
            notifyError("Server error:", error.response.data)
        } else {
            notifyError("Network or other error:", error)
        }
    }
}


</script>