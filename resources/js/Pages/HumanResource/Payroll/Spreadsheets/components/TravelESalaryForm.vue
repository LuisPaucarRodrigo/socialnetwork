<template>
    <Modal :show="showPaymentModal">
        <div class="p-6">
            <h2 class="text-base font-medium leading-7 text-gray-900">
                {{ travelOrSalary ? 'Pago de Viatico ' : 'Pago de salario ' }} del Empleado
            </h2>
            <form @submit.prevent="submit">
                <div class="border-b border-gray-900/10">
                    <div class="mt-2">
                        <InputLabel for="operation_number">Numero de Operación
                        </InputLabel>
                        <div class="mt-2">
                            <TextInput type="text" id="operation_number" v-model="formPayment.operation_number"
                                maxlength="6" />
                            <InputError :message="formPayment.errors.operation_number" />
                        </div>
                    </div>
                    <div class="mt-2">
                        <InputLabel for="operation_date">Fecha de Operación
                        </InputLabel>
                        <div class="mt-2">
                            <TextInput type="date" id="operation_date" v-model="formPayment.operation_date" />
                            <InputError :message="formPayment.errors.operation_date" />
                        </div>
                    </div>
                    <div class="mt-6 flex items-center justify-end gap-x-3">
                        <SecondaryButton @click="closePaymentModal"> Cancelar </SecondaryButton>
                        <PrimaryButton type="submit" :class="{ 'opacity-25': formPayment.processing }">
                            Guardar
                        </PrimaryButton>
                    </div>
                </div>
            </form>
        </div>
    </Modal>
</template>
<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Modal from '@/Components/Modal.vue';
import { notify, notifyError } from '@/Components/Notification';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { setAxiosErrors } from '@/utils/utils';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const { spreadsheets } = defineProps({
    spreadsheets: Object
})

const showPaymentModal = ref(false)
const travelOrSalary = ref(false)

const initialStament = {
    id: '',
    operation_date: '',
    operation_number: '',
}

const formPayment = useForm({
    ...initialStament
})

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

async function submit() {
    let url = travelOrSalary.value ? route('payroll.payment.travelExpense.store', { payroll_details_id: formPayment.id }) : route('payroll.payment.salary.store', { payroll_details_id: formPayment.id })
    try {
        let response = await axios.put(url, formPayment)
        closePaymentModal()
        updatePayrollDetails(response.data, 'register')
    } catch (error) {
        if (error.response) {
            if (error.response.data.errors) {
                setAxiosErrors(error.response.data.errors, formPayment)
            } else {
                notifyError("Server error:", error.response.data)
            }
        } else {
            notifyError("Network or other error:", error)
        }
    }
}

function updatePayrollDetails(payrollDetail, action) {
    const validations = spreadsheets
    if (action === 'register') {
        let index = validations.findIndex(item => item.id === payrollDetail[0].payroll_detail_id)
        validations[index].payroll_detail_expense = payrollDetail;
        notify('El pago se Registro')
    }
}

defineExpose({ openPaymentSalaryModal, openPaymentTravelExpenseModal })
</script>