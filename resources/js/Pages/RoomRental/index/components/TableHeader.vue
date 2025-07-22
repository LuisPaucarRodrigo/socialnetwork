<template>
    <div class="my-3 flex justify-between">
        <PrimaryButton v-permission="'room_actions_manager'" @click="openCreateFormCar()" type="button">
            + Agregar
        </PrimaryButton>
        <Search v-model:search="form.search" fields="Tipo de Renta, Dirección, Observaciones, Compañia,Contacto,Zona" />
    </div>
</template>

<script setup>
import PrimaryButton from '@/Components/PrimaryButton.vue';
import Search from '@/Components/Search.vue';

const { form, openCreateFormCar } = defineProps({
    form: Object,
    openCreateFormCar: Function,
});

</script>
