<template>
    <ConfirmDeleteModal :confirmingDeletion="showModalDeleteChangelog" itemType="registro de cambios"
        :deleteFunction="deleteChangelog" @closeModal="closeModal()" />
</template>
<script setup>
import ConfirmDeleteModal from '@/Components/ConfirmDeleteModal.vue';
import { notify } from '@/Components/Notification';
import { ref } from 'vue';

const { cars } = defineProps({
    cars: Object
})

const showModalDeleteChangelog = ref(false);
const changelogToDelete = ref(null);

function toogleModal(){
    showModalDeleteChangelog.value = !showModalDeleteChangelog.value
}

function openModalDeleteChangelog(id) {
    showModalDeleteChangelog.value = true
    changelogToDelete.value = id
}

function closeModal() {
    toogleModal()
}

async function deleteChangelog() {
    const docId = changelogToDelete.value;
    if (docId) {
        const response = await axios.delete(
            route("room.rental.destroy_changelog", { car_changelog: docId })
        );
        if (response.data) {
            updateCar(response.data, "deleteChangelog");
        } else {
            notifyError("Error al eliminar el registro de cambios");
        }
    }
}

function updateCar(data, action) {
    const validations = cars.data || cars;
    if (action === "deleteChangelog") {
        let index = validations.findIndex((item) => item.id === data.id);
        validations[index] = data;
        closeModal()
        if (validations[index].room_changelogs.length === 0) {
            carId.value = null;
        }
        notify("Eliminación Exitosa");
    }
}
defineExpose({ openModalDeleteChangelog })

</script>
