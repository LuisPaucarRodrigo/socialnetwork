<template>
    <ConfirmDeleteModal :confirmingDeletion="confirmingDocDeletion" itemType="Gasto" :deleteFunction="deleteAdditional"
        @closeModal="closeModalDoc" />
</template>
<script setup>
import ConfirmDeleteModal from '@/Components/ConfirmDeleteModal.vue';
import { notify } from '@/Components/Notification';
import { ref } from 'vue';

const { expenses } = defineProps({
    expenses: Object
})

const confirmingDocDeletion = ref(false);
const docToDelete = ref(null);

async function deleteAdditional() {
    const docId = docToDelete.value;
    const url = route("pext.expenses.delete", {
        expense_id: docId,
    })
    try {
        await axios.delete(url)
        closeModalDoc()
        updateExpense(docId, "delete")
    } catch (e) {
        console.log(e)
    }
};

const confirmDeleteAdditional = (additionalId) => {
    docToDelete.value = additionalId;
    toogleModal()
};

const closeModalDoc = () => {
    toogleModal()
};

function toogleModal() {
    confirmingDocDeletion.value = !confirmingDocDeletion.value
}

function updateExpense(expense, action) {
    let listDate = expenses.data || expenses
    if (action === "delete") {
        let index = listDate.findIndex(item => item.id == expense)
        listDate.splice(index, 1);
        notify('Gasto Eliminado')
    }
}

defineExpose({ confirmDeleteAdditional })
</script>