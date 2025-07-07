<template>
    <ConfirmDeleteModal :confirmingDeletion="confirmingDocDeletion" itemType="Costo Adicional"
        :deleteFunction="deleteAdditional" @closeModal="closeModalDoc" :processing="isFetching" />
</template>
<script setup>
import ConfirmDeleteModal from '@/Components/ConfirmDeleteModal.vue';
import { notify } from '@/Components/Notification';
import { ref } from 'vue';

const { dataToRender, project_id } = defineProps({
    dataToRender: Object,
    project_id: Number
})

const isFetching = ref(false);
const confirmingDocDeletion = ref(false);
const docToDelete = ref(null);

const confirmDeleteAdditional = (additionalId) => {
    docToDelete.value = additionalId;
    confirmingDocDeletion.value = true;
};

const closeModalDoc = () => {
    confirmingDocDeletion.value = false;
};

const deleteAdditional = async () => {
    const docId = docToDelete.value;
    isFetching.value = true;
    try {
        const res = await axios.delete(
            route("projectmanagement.deleteAdditionalCost", {
                project_id: project_id,
                additional_cost: docId,
            })
        );
        isFetching.value = false;
        if (res?.data?.msg === "success") {
            closeModalDoc();
            notify("Gasto Adicional Eliminado");
            let index = dataToRender.findIndex(
                (item) => item.id == docId
            );
            dataToRender.splice(index, 1);
        }
    } catch (e) {
        isFetching.value = false;
    }
};

defineExpose({ confirmDeleteAdditional })
</script>