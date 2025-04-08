<template>
    <Modal :show="show_rol">
        <div class="p-6">
            <h2 class="text-base font-medium leading-7 text-gray-900">
                Rol
            </h2>
            <div class="py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                <dt class="text-sm font-medium leading-6 text-gray-900">Nombre</dt>
                <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{ rols.name }}</dd>
            </div>
            <div class="py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                <dt class="text-sm font-medium leading-6 text-gray-900">Descripcion</dt>
                <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{ rols.description }}</dd>
            </div>
            <div class="py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                <dt class="text-sm font-medium leading-6 text-gray-900">Permisos</dt>
                <div class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                    <dd v-for="(permission, index) in rols.permissions" :key="index">{{ permission.name }}</dd>
                </div>
            </div>
            <div class="flex items-center justify-end">
                <SecondaryButton @click="toggleModal"> Cerrar </SecondaryButton>
            </div>
        </div>
    </Modal>
</template>
<script setup>
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { ref } from 'vue';

const show_rol = ref(false)

const rols = ref({
    name: '',
    description: '',
    permissions: []
})

function showModal(id) {
    requestRol(id)
    toggleModal()
}

function toggleModal() {
    show_rol.value = !show_rol.value
}

async function requestRol(id) {
    const url = route('rols.details', { id: id })
    try {
        let response = await axios.get(url);
        rols.value = response.data
    } catch (error) {
        console.error(error)
    }
}

defineExpose({ showModal })
</script>