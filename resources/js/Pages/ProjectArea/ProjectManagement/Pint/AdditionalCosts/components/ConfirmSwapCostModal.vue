<template>
    <ConfirmateModal :showConfirm="showSwapCostsModal" tittle="Cambio de gastos adicionales a fijos"
        text="La siguiente acción ya no se podrá revertir, ¿Desea continuar?" :actionFunction="swapCosts"
        @closeModal="closeSwapCostsModal" />
</template>
<script setup>
import ConfirmateModal from '@/Components/ConfirmateModal.vue';
import { notify, notifyError, notifyWarning } from '@/Components/Notification';
import { ref } from 'vue';

const { actionForm } = defineProps({
    actionForm: Object
})

const showSwapCostsModal = ref(false)
const isFetching = ref(false)
const dataToRender = defineModel('dataToRender')
const closeSwapCostsModal = () => {
    showSwapCostsModal.value = false
    isFetching.value = false
}
const openSwapCostsModal = () => {
    if (actionForm.ids.length === 0) {
        notifyWarning("No hay registros seleccionados");
        return;
    }
    showSwapCostsModal.value = true
}
const swapCosts = async () => {
    isFetching.value = true;
    await axios
        .post(route("projectmanagement.additionalCosts.swapCosts"), {
            ...actionForm
        })
        .catch((e) => {
            isFetching.value = false;
            notifyError("Server Error");
        });
    let listData = dataToRender.value.data || dataToRender.value
    actionForm.ids.forEach(update => {
        const index = listData.findIndex(item => item.id === update);
        if (index !== -1) {
            listData.splice(index, 1)
        }
    })
    actionForm.ids = []
    closeSwapCostsModal();
    notify("Registros Movidos con éxito");
}

defineExpose({ openSwapCostsModal })
</script>