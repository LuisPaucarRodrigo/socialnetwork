<template>
    <ConfirmDeleteModal :confirmingDeletion="showModalDeleteCars" itemType="vehiculo" :deleteFunction="deleteCars"
        @closeModal="closeModal()" />
</template>
<script setup>
import ConfirmDeleteModal from '@/Components/ConfirmDeleteModal.vue';
import { notify } from '@/Components/Notification';
import { ref } from 'vue';

const { cars } = defineProps({
    cars: Object
})

const car_id = ref(null);
const showModalDeleteCars = ref(false);

function toogleModal() {
    showModalDeleteCars.value = !showModalDeleteCars.value
}

function openModalDeleteCars(id) {
    car_id.value = id;
    toogleModal()
}

function closeModal() {
    toogleModal()
}

async function deleteCars() {
    let url = route("room.rental.destroy", { car: car_id.value });
    try {
        await axios.delete(url);
        updateCar(car_id.value, "delete");
    } catch (error) {
        console.log(error);
    }
}

function updateCar(data, action) {
    const validations = cars.data || cars;
    if (action === "delete") {
        let index = validations.findIndex((item) => item.id === data);
        validations.splice(index, 1);
        closeModal()
        notify("Eliminacion Exitosa");
    }
}

defineExpose({ openModalDeleteCars })
</script>