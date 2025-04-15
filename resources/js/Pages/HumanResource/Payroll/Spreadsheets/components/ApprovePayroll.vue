<template>
    <ConfirmateModal :showConfirm="showPayrollModal" tittle="Aprobación de la Nómina"
        text="Una vez que la nómina sea aprobada, no se podrán realizar modificaciones ni acciones posteriores. Por favor, asegúrate de revisar toda la información antes de proceder con la aprobación final."
        @closeModal="openPayrollApprove" :actionFunction="payrollApprove" />
</template>
<script setup>
import ConfirmateModal from '@/Components/ConfirmateModal.vue'
import { notify, notifyError } from '@/Components/Notification'
import { ref } from 'vue'

const payrolls = defineModel('payrolls')

const showPayrollModal = ref(false)

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

defineExpose({ openPayrollApprove })
</script>