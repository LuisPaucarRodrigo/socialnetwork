<template>
    <div class="my-3 flex justify-between">
        <PrimaryButton v-permission="'mobile_actions_manager'"
            @click="openCreateFormCar()" type="button">
            + Agregar
        </PrimaryButton>
        <Search v-model:search="form.search" fields="Placa, Marca, Modelo, Tipo" />
    </div>
</template>

<script setup>
import PrimaryButton from '@/Components/PrimaryButton.vue';
import Search from '@/Components/Search.vue';

const { form, openCreateFormCar, role_id } = defineProps({
    form: Object,
    openCreateFormCar: Function,
    role_id: Number
});

</script>
