<template>
    <Head title="Inventario" />
    <AuthenticatedLayout>
        <template #header>
            Equipos de Red
        </template>

        <div class="inline-block min-w-full overflow-hidden rounded-lg shadow">
            <div class="flex justify-start">
                <button @click="add_network_equipment"
                    class="rounded-md bg-indigo-600 px-4 py-2 text-center text-sm text-white hover:bg-indigo-500 mr-5">
                    + Agregar
                </button>

                <!-- <button @click="open_file_modal()" type="button"
                    class="rounded-md bg-green-500 px-4 py-2 text-center text-sm text-white hover:bg-green-400">
                    Importar Datos
                </button> -->
            </div>

            <div class="talwing mt-4">
                <table class="w-full whitespace-no-wrap">
                    <thead>
                        <tr
                            class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Nombre
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Modelo
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Proveedor
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Fecha de Adquisicion
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in network_equipments" :key="item.id" :class="[
                            'text-gray-700',
                            { 'border-l-4': true, 'border-green-500': item.state === 'Disponible', 'border-red-500': item.state !== 'Disponible' }
                        ]">
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ item.name }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ item.model }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ item.supplier }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ item.adquisition_date }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <div class="flex space-x-3 justify-center">
                                    <button type="button" @click="edit_network_equipment(item.id)"
                                        class="text-blue-900 whitespace-no-wrap">
                                        <PencilIcon class="text-yellow-500 h-4 w-4"></PencilIcon>
                                    </button>
                                    <button type="button" @click="confirm_delete_network_equipment(item.id)"
                                        class="text-red-900 whitespace-no-wrap">
                                        <TrashIcon class="text-red-500 h-4 w-4" />
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <ConfirmDeleteModal :confirmingDeletion="confirm_network_equipment" itemType="Equipo de Red"
            :deleteText="deleteButtonText" :deleteFunction="delete_network_equipment" @closeModal="closeModal" />

        <Modal :show="show_file_modal" :maxWidth="'md'">
            <form @submit.prevent="ImportData" method="POST" enctype="multipart/form-data">
                <div class="p-6">
                    <h1 class="text-2xl font-bold mb-4">Importar datos de Excel</h1>
                    <div class="mt-4">
                        <label for="importar" class="block text-sm font-medium text-gray-700">Seleccionar archivo:</label>
                        <input type="file" id="importar" @change="handleFileChange" accept=".xlsx, .xls, .csv"
                            class="mt-1 p-2 border rounded-md w-full">
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                        <button type="submit"
                            class="inline-flex w-full justify-center rounded-md bg-green-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-500 sm:ml-3 sm:w-auto" name="importar">Importar</button>
                        <button type="button"
                            class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto"
                            @click="closeModal('file')" ref="cancelButtonRef">
                            Cancel
                        </button>
                    </div>
                </div>
            </form>
        </Modal>

    </AuthenticatedLayout>
</template>
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router, Link } from '@inertiajs/vue3';
import { PencilIcon, TrashIcon } from '@heroicons/vue/24/outline';
import ConfirmDeleteModal from '@/Components/ConfirmDeleteModal.vue';
import { ref } from 'vue';
import Modal from '@/Components/Modal.vue';

const props = defineProps({
    network_equipments: Object
})

const show_file_modal = ref(false);
const confirm_network_equipment = ref(false);
const deleteButtonText = 'Eliminar';
const network_equipment_id = ref(null);

const confirm_delete_network_equipment = (NeId) => {
    network_equipment_id.value = NeId;
    confirm_network_equipment.value = true;
};
const open_file_modal = () => {
    show_file_modal.value = true;
}

const add_network_equipment = () => {
    router.get(route('inventory.NetworkEquipment.new'));
}
const edit_network_equipment = (NeId) => {
    router.get(route('inventory.NetworkEquipment.edit', { NeId: NeId }));
}

const delete_network_equipment = () => {
    const NeId = network_equipment_id.value;
    if (NeId) {
        router.delete(route('inventory.NetworkEquipment.delete', { NeId: NeId }), {
            onSuccess: () => closeModal()
        });
    }
};
const closeModal = (type) => {
    if (type === "file") {
        show_file_modal.value = false
    } else {
        confirm_network_equipment.value = false;
    }
};
const ImportData = () => {
    const formData = new FormData();
    formData.append('importar', document.getElementById('importar').files[0]);
    router.post(route('inventory.NetworkEquipment.import'),formData, {
        onSuccess: () => {
            console.log("Correcto")
        },
        onError: (error) => {
            console.log(error)
        }
    })
    console.log("enviado")
}
</script>