<template>
    <ConfirmateModal :showConfirm="showSwapCostsModal" tittle="Cambio de gastos adicionales a fijos"
        text="La siguiente acción ya no se podrá revertir, ¿Desea continuar?" :actionFunction="swapCosts"
        @closeModal="closeSwapCostsModal" />
</template>
<script setup>
import ConfirmateModal from '@/Components/ConfirmateModal.vue';
import { notify, notifyError } from '@/Components/Notification';
import { ref } from 'vue';

const { expenses, actionForm } = defineProps({
    expenses: Object,
    actionForm: Object
})

const showSwapCostsModal = ref(false)

const swapCosts = async () => {
    let validation = expenses.data || expenses
    await axios
        .post(route("projectmanagement.pext.massiveUpdate.swap"), {
            ...actionForm
        })
        .catch((e) => {
            notifyError("Server Error");
        });

    actionForm.ids.forEach((id) => {
        const index = validation.findIndex((item) => item.id === id);
        if (index !== -1) {
            validation.splice(index, 1);
        }
    });

    actionForm.ids = []
    closeSwapCostsModal();
    notify("Registros Movidos con éxito");
}

function toogleModal() {
    showSwapCostsModal.value = !showSwapCostsModal.value
}

const openSwapCostsModal = () => {
    if (actionForm.ids.length === 0) {
        notifyWarning("No hay registros seleccionados");
        return;
    }
    toogleModal()
}

const closeSwapCostsModal = () => {
    toogleModal()
}

defineExpose({ openSwapCostsModal })
</script>