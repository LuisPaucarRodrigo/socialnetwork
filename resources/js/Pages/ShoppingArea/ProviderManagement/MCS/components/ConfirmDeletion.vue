<template>
    <ConfirmDeleteModal :confirmingDeletion="confirmingDeletion" itemType="categoria" :deleteFunction="deleteItem"
        @closeModal="closeModal" />
</template>
<script setup>
import ConfirmDeleteModal from '@/Components/ConfirmDeleteModal.vue';
import { notify, notifyError } from '@/Components/Notification';
import { ref } from 'vue';

const { dataToRender, routeName } = defineProps({
    dataToRender: Object,
    routeName: String
})

const confirmingDeletion = ref(false)
const itemToDelete = ref(null)

function toogleModal() {
    confirmingDeletion.value = !confirmingDeletion.value
}

const confirmDeletion = (itemId) => {
    toogleModal()
    itemToDelete.value = itemId;
};

async function deleteItem() {
    const itemId = itemToDelete.value;
    const url = route(routeName, { item: itemId })
    try {
        await axios.delete(url)
        updateFrontEnd('delete', itemId)
    } catch (error) {
        console.error(error)
        notifyError(`Server error: ${error.response.data}`)
    }
};

const closeModal = () => {
    itemToDelete.value = null
    toogleModal()
};

function updateFrontEnd(action, id) {
    if (action === "delete") {
        let validations = dataToRender.data || dataToRender
        const index = validations.findIndex(item => item.id === id)
        validations.splice(index, 1)
        closeModal()
        notify("Eliminación exitosa.")
    }
}

defineExpose({ confirmDeletion })
</script>