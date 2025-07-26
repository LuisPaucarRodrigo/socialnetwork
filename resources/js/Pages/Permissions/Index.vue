<template>

    <Head title="Permisos Programador" />
    <AuthenticatedLayout :redirectRoute="'#'">
        <template #header>
            Developer Permissions
        </template>
        <button @click="openAddModal" type="button"
            class="rounded-md bg-indigo-600 px-4 py-2 text-center text-sm text-white hover:bg-indigo-500">
            + Agregar
        </button>
        <div>
            halo
        </div>






        <Modal :show="showAddModal" :max-width="'6xl'">
            <div class="p-6 grid grid-cols-1 gap-6">
                <h2 class="text-base font-semibold leading-7 text-gray-900 border-b border-gray-200">
                    Registrar permisos
                </h2> 
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <InputLabel class="font-medium leading-6 text-gray-900">
                            Nombre
                        </InputLabel>
                        <TextInput :type="'text'"/>
                    </div>
                    <div>
                        <InputLabel class="font-medium leading-6 text-gray-900">
                            Descripción
                        </InputLabel>
                        <TextInput :type="'text'"/>
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3">
                    <div class="px-4">Módulo</div>
                    <div class="border-l border-gray-300 px-4">Submódulo</div>
                    <div class="border-l border-gray-300 px-4">Funcionalidad</div>
                </div>
            </div>
        </Modal>
    </AuthenticatedLayout>
</template>
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import Modal from '@/Components/Modal.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { ref } from 'vue';
import { Toaster } from 'vue-sonner';

const showAddModal = ref(false)

function openAddModal () {
    showAddModal.value = true
}

</script>
