<template>
    <div class="my-3 flex justify-between">
        <PrimaryButton v-if="hasPermission('CarManager')" @click="openModalCreate()" type="button">
            + Agregar
        </PrimaryButton>
        <Search v-model:search="form.search" fields="Placa, Marca, Modelo, Tipo"/>
    </div>
</template>

<script setup>
import PrimaryButton from '@/Components/PrimaryButton.vue';
import Search from '@/Components/Search.vue';

const { form, openModalCreate, userPermission } = defineProps({
    form: Object,
    openModalCreate: Function,
    userPermission: Array
});

function hasPermission(permission) {
    return userPermission.includes(permission);
}
</script>
