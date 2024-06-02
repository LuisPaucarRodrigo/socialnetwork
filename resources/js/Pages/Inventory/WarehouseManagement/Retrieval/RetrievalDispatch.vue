<template>

    <Head title="Gestion de Empleados" />
    <AuthenticatedLayout :redirectRoute="'warehouses.warehouses'">
        <template #header>
            Despachos de Recuperos
        </template>

        <div class="min-w-full rounded-lg shadow">
            <div class="flex gap-2 m-1 justify-end items-center">
                <select class="border rounded-md px-4 py-1 w-[150px]" @change="optionChange">
                    <option>Por Aprobar</option>
                    <option>Aprobados</option>
                    <option>Rechazados</option>
                </select>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full whitespace-no-wrap">
                    <thead>
                        <tr
                            class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Proyecto
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Producto
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Cantidad Solicitada
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="dispatch in retrievalDispatch.data" :key="dispatch.id" class="text-gray-700">
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">
                                    {{ dispatch.project.preproject.code }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">
                                    {{ dispatch.entry_id 
                                        ? dispatch.entry.inventory.purchase_product.name 
                                        : dispatch.special_inventory.purchase_product.name }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">
                                    {{ dispatch.quantity }}
                                </p>
                            </td>

                            <td class="border-b border-gray-300 bg-white px-5 py-5 text-sm">
                                <div v-if="dispatch.state === null && hasPermission('InventoryManager')" class="flex space-x-3 justify-center">
                                    <button @click="() => openAcceptModal(dispatch.id)"
                                        class="flex items-center text-blue-500 hover:underline">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-green-500">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                        </svg>
                                    </button>
                                    <button @click="() => openDeclineModal(dispatch.id)" type="button"
                                        class="rounded-xl whitespace-no-wrap text-center text-sm text-red-900 hover:bg-red-200">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-red-500">
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
            <div class="flex flex-col items-center border-t bg-white px-5 py-5 xs:flex-row xs:justify-between">
                <Pagination :links="retrievalDispatch.links" />
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
                <div class="mt-6 flex justify-end gap-3">
                    <SecondaryButton @click="closeAcceptModal"> Cancelar </SecondaryButton>
                    <PrimaryButton @click="acceptRequest(project_entry_id)" type="button">
                        Aceptar
                    </PrimaryButton>
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
                <div class="mt-6 flex justify-end gap-3">
                    <SecondaryButton @click="closeDeclineModal"> Cancelar </SecondaryButton>

                    <DangerButton @click="declineRequest(project_entry_id)" type="button">
                        Rechazar
                    </DangerButton>
                </div>
            </div>
        </Modal>

    </AuthenticatedLayout>
</template>
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import Modal from '@/Components/Modal.vue';
import Pagination from '@/Components/Pagination.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';

const props = defineProps({
    retrievalDispatch: Object,
    userPermissions:Array
})

const hasPermission = (permission) => {
    return props.userPermissions.includes(permission);
}

const declineModal = ref(false);
const acceptModal = ref(false);
let project_entry_id = null;

const closeAcceptModal = () => { acceptModal.value = false;}
const closeDeclineModal = () => { declineModal.value = false;}

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
    router.post(route('warehouses.dispatches.acceptordecline'), data, {
        preserveScroll: true,
        onSuccess: () => {
            declineModal.value = false
        }
    });
}
const acceptRequest = () => {
    const data = { state: true, project_entry_id: project_entry_id };
    router.post(route('warehouses.dispatches.acceptordecline'), data, {
        preserveScroll: true,
        onSuccess: () => {
            acceptModal.value = false
        },
        onError:(e) => {
            console.log(e)
        }
    });
}

const optionChange = (e) => {
    if (e.target.value === "Aprobados") {
        router.get(route('inventory.retrievalDispatch.approved'))
    } else if (e.target.value === "Rechazados") {
        router.get(route('inventory.retrievalDispatch.rejected'))
    }
}

</script>
