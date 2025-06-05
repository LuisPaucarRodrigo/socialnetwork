<template>
    <div class="my-3 flex justify-between">
        <PrimaryButton v-if="hasPermission('CarManager') || role_id === 1" @click="openCreateFormCar()" type="button">
            + Agregar
        </PrimaryButton>
        <Search v-model:search="form.search" fields="Placa, Marca, Modelo, Tipo"/>
    </div>
</template>

<script setup>
import PrimaryButton from '@/Components/PrimaryButton.vue';
import Search from '@/Components/Search.vue';

const { form, openCreateFormCar, userPermission, role_id } = defineProps({
    form: Object,
    openCreateFormCar: Function,
    userPermission: Array,
    role_id: Number
});

function hasPermission(permission) {
    return userPermission.includes(permission);
}
</script>
