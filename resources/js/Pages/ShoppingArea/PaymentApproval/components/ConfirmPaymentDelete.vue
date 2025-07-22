<template>
    <ConfirmDeleteModal :confirmingDeletion="confirmingDeletion" itemType="programación de pago"
        :deleteFunction="deletePayment" @closeModal="closeModal" />
</template>
<script setup>
import ConfirmDeleteModal from '@/Components/ConfirmDeleteModal.vue';
import { notify, notifyError } from '@/Components/Notification';
import { ref } from 'vue';

const { payments } = defineProps({
    payments: Object
})

const confirmingDeletion = ref(false)
const paymentToDelete = ref(null)

function toogleModal() {
    confirmingDeletion.value = !confirmingDeletion.value
}

const confirmPaymentDeletion = (paymentId) => {
    toogleModal()
    paymentToDelete.value = paymentId;
};

async function deletePayment() {
    const paymentId = paymentToDelete.value;
    const url = route('payment.approval.delete', { id: paymentId })
    try {
        await axios.delete(url)
        updateFrontEnd('delete', paymentId)
    } catch (error) {
        console.error(error)
        notifyError(`Server error`)
    }
};

const closeModal = () => {
    paymentToDelete.value = null
    toogleModal()
};

function updateFrontEnd(action, id) {
    if (action === "delete") {
        let validations = payments.data || payments
        const index = validations.findIndex(item => item.id === id)
        validations.splice(index, 1)
        closeModal()
        notify("Eliminación exitosa.")
    }
}

defineExpose({ confirmPaymentDeletion })
</script>