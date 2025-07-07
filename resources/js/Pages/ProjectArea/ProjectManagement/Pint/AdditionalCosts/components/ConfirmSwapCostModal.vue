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
    const res = await axios
        .post(route("projectmanagement.additionalCosts.swapCosts"), {
            ...actionForm
        })
        .catch((e) => {
            isFetching.value = false;
            notifyError("Server Error");
        });
    dataToRender.value = dataToRender.value.filter(
        (item) => !actionForm.ids.includes(item.id)
    );
    actionForm.ids = []
    closeSwapCostsModal();
    notify("Registros Movidos con éxito");
}

defineExpose({ openSwapCostsModal })
</script>