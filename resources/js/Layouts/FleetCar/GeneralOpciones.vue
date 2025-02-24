<template>
    <div class="my-3 flex justify-between">
        <PrimaryButton v-if="hasPermission('CarManager')" @click="openModalCreate()" type="button">
            + Agregar
        </PrimaryButton>
        <div>
            <TextInput data-tooltip-target="search_fields" type="text" placeholder="Buscar..." v-model="form.search" />
            <div id="search_fields" role="tooltip"
                class="absolute z-10 invisible inline-block px-2 py-2 text-xs font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                Placa,Marca,Modelo,Tipo
                <div class="tooltip-arrow" data-popper-arrow></div>
            </div>
        </div>
    </div>
</template>
<script setup>
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';

const { form, openModalCreate, userPermission } = defineProps({
    form: Object,
    openModalCreate: Function,
    userPermission: Array
})

function hasPermission(permission) {
    return userPermission.includes(permission)
}

</script>