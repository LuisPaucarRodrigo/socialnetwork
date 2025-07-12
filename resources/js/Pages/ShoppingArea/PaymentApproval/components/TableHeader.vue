<template>
    <div class="flex justify-between items-center gap-4">
        <button v-permission="'add_payment_approval'" @click="createPayment()" type="button"
            class="rounded-md bg-indigo-600 px-4 py-2 text-center text-sm text-white hover:bg-indigo-500">
            + Agregar
        </button>
        <div class="flex items-center">
            <Search fields="Numero de Cuenta,Ruc,Beneficiario,Descripción" v-model:search="filterForm.searchQuery" />
        </div>
    </div>
</template>
<script setup>
import Search from '@/Components/Search.vue';

const { createPayment, filterForm } = defineProps({
    createPayment: Function,
    filterForm: Object
})
</script>