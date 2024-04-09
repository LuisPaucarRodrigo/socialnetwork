<template>

    <Head title="Productos" />
    <AuthenticatedLayout :redirectRoute="'warehouses.warehouses'">
        <template #header>
            Aprobación de Despachos
        </template>

        <div class="min-w-full p-3 rounded-lg shadow">

            <br>

            <div class="min-w-full overflow-x-auto rounded-lg shadow">
                <table class="w-full table-auto">
                    <thead>
                        <tr
                            class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Código del Proyecto
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Almacén
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Producto
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Cantidad
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Precio Unitario
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Observaciones
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in props.project_entries.data" :key="item.id"
                            class="text-gray-700 border-b">
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ item.project.code }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ item.entry.inventory.warehouse.name }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ item.entry.inventory.purchase_product.name }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ item.quantity }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">S/. {{ item.unitary_price }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ item.observation }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <div class="flex justify-center items-center">
                                    <button @click="openAcceptModal(item.id)" class="rounded-xl flex items-center text-blue-500 hover:bg-green-200">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                            stroke="currentColor" class="w-6 h-6 text-green-500">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                        </svg>
                                    </button>
                                    <button @click="openDeclineModal(item.id)" type="button"
                                        class="rounded-xl whitespace-no-wrap text-center text-sm text-red-900 hover:bg-red-200">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                            stroke="currentColor" class="w-6 h-6 text-red-500">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="flex flex-col items-center border-t px-5 py-5 xs:flex-row xs:justify-between">
                <pagination :links="project_entries.links" />
            </div>
        </div>

        <Modal :show="acceptModal" :maxWidth="'xl'">
            <div class="p-5">
                <div>
                    <h2 class="text-lg font-medium text-gray-900">
                        ¿Desea aceptar la solicitud de productos?
                    </h2>
                </div>
                <p class="mt-1 text-sm text-gray-600">
                    Esta acción le permitirá generar salidas de los productos solicitados por el proyecto.
                </p>
                <div class="mt-6 flex justify-end">
                    <button @click="acceptRequest(project_entry_id)" type="button"
                        class="inline-flex justify-center items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 mr-3">
                        Aceptar
                    </button>
                    <SecondaryButton @click="closeAcceptModal"> Cancelar </SecondaryButton>

                </div>
            </div>
        </Modal>

        <Modal :show="declineModal" :maxWidth="'xl'">
            <div class="p-5">
                <div>
                    <h2 class="text-lg font-medium text-gray-900">
                        ¿Desea rechazar la solicitud de productos?
                    </h2>
                </div>
                <p class="mt-1 text-sm text-gray-600">
                    Esta acción no se podrá revertir más adelante.
                </p>
                <div class="mt-6 flex justify-end">
                    <button @click="declineRequest(project_entry_id)" type="button"
                        class="inline-flex justify-center items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 mr-3">
                        Rechazar
                    </button>
                    <SecondaryButton @click="closeDeclineModal"> Cancelar </SecondaryButton>

                </div>
            </div>
        </Modal>

    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Pagination from '@/Components/Pagination.vue'
import { ref } from 'vue';
import Modal from '@/Components/Modal.vue';
import { Head, router } from '@inertiajs/vue3';
import SecondaryButton from '@/Components/SecondaryButton.vue';

const props = defineProps({
    project_entries: Object,
    warehouseId: Number
});

const declineModal = ref(false);
const acceptModal = ref(false);
let project_entry_id = null;

const closeAcceptModal = () => {
    acceptModal.value = false;
}

const closeDeclineModal = () => {
    declineModal.value = false;
}

const openAcceptModal = (id) => {
  project_entry_id = id;
  acceptModal.value = true;
}

const openDeclineModal = (id) => {
  project_entry_id = id;
  declineModal.value = true;
}

const declineRequest = () => { 
  const data = { state: false, project_entry_id: project_entry_id };
  router.post(route('warehouses.dispatches.acceptordecline', {warehouse: props.warehouseId}), data, {
    preserveScroll: true,
    onSuccess:() => {
      router.visit(route('warehouses.dispatches', {warehouse: props.warehouseId}))
    }
  });
}

const acceptRequest = () => {
  const data = { state: true, project_entry_id: project_entry_id };
  router.post(route('warehouses.dispatches.acceptordecline', {warehouse: props.warehouseId}), data, {
    preserveScroll: true,
    onSuccess:() => {
      router.visit(route('warehouses.dispatches', {warehouse: props.warehouseId}))
    }
  });
}

</script>
