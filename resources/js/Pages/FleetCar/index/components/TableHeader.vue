<template>
    <div class="my-3 flex justify-between">
        <PrimaryButton v-if="hasPermission('CarManager')" @click="openCreateFormCar()" type="button">
            + Agregar
        </PrimaryButton>
        <Search v-model:search="form.search" fields="Placa, Marca, Modelo, Tipo"/>
    </div>
</template>

<script setup>
import PrimaryButton from '@/Components/PrimaryButton.vue';
import Search from '@/Components/Search.vue';

const { form, openCreateFormCar, userPermission } = defineProps({
    form: Object,
    openCreateFormCar: Function,
    userPermission: Array
});

function hasPermission(permission) {
    return userPermission.includes(permission);
}
</script>
