<template>

    <Head title="Solicitudes del proyecto" />
    <AuthenticatedLayout :redirect-route="backUrl">
        <template #header>
            Lista de solicitudes de Anteproyecto
        </template>
        <div class="inline-block min-w-full overflow-hidden rounded-lg shadow">
            <div class="flex gap-2">
                <PrimaryButton v-if="!preproject.has_quote && hasPermission('ProjectManager')"
                    @click="add_purchase_request" type="button">
                    + Agregar
                </PrimaryButton>
            </div>
            <TableStructure>
                <template #thead>
                    <tr>
                        <TableTitle>Código</TableTitle>
                        <TableTitle>Solicitud</TableTitle>
                        <TableTitle>Estado</TableTitle>
                        <TableTitle v-if="hasPermission('ProjectManager')"></TableTitle>
                    </tr>
                </template>
                <template #tbody>
                    <tr v-for="purchase in purchases.data" :key="purchase.id">
                        <TableRow>{{ purchase.code }}</TableRow>
                        <TableRow>{{ purchase.title }}</TableRow>
                        <TableRow>{{ purchase.state }}</TableRow>
                        <TableRow>
                            <div class="flex space-x-3 justify-center">
                                <Link class="text-blue-900"
                                    :href="route('preprojects.request.details', { id: purchase.id })">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-teal-500">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                </Link>

                                <div v-if="hasPermission('ProjectManager')">
                                    <Link v-if="purchase.state === 'Pendiente'" class="text-blue-900"
                                        :href="route('preprojects.request.edit', { id: purchase.id })">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-amber-400">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                    </svg>
                                    </Link>
                                    <span v-else class="text-gray-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-gray-400">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                        </svg>
                                    </span>
                                </div>

                                <div v-if="hasPermission('ProjectManager')">
                                    <button v-if="purchase.state === 'Pendiente'" type="button"
                                        @click="confirmPurchasesDeletion(purchase.id)" class="text-blue-900">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-red-500">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                        </svg>
                                    </button>
                                    <span v-else class="text-gray-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-gray-400">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                        </svg>
                                    </span>
                                </div>
                            </div>
                        </TableRow>
                    </tr>
                </template>
            </TableStructure>
            <div class="flex flex-col items-center border-t bg-white px-5 py-5 xs:flex-row xs:justify-between">
                <pagination :links="purchases.links" />
            </div>
        </div>
        <Modal :show="confirmingPurchasesDeletion">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">
                    Estas seguro de eliminar la solicitud?
                </h2>
                <p class="mt-1 text-sm text-gray-600">
                    Se eliminara toda la informacion relacionada con la solicitud.
                </p>
                <div class="mt-6 flex justify-end">
                    <SecondaryButton @click="closeModal"> Cancel </SecondaryButton>

                    <DangerButton class="ml-3" @click="deletePurchase()">
                        Eliminar
                    </DangerButton>
                </div>
            </div>
        </Modal>
        <ErrorOperationModal :showError="showError" title="No hay presupuesto"
            message="La cantidad disponible del presupuesto esta en cero o no está definido" />
    </AuthenticatedLayout>
</template>
<script setup>
import ErrorOperationModal from '@/Components/ErrorOperationModal.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
import Pagination from '@/Components/Pagination.vue';
import Modal from '@/Components/Modal.vue';
import { ref } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import TableStructure from '@/Layouts/TableStructure.vue';
import TableTitle from '@/Components/TableTitle.vue';
import TableRow from '@/Components/TableRow.vue';

const confirmingPurchasesDeletion = ref(false);
const showError = ref(false)
const purchaseToDelete = ref(null);

const props = defineProps({
    purchases: Object,
    preproject: Object,
    userPermissions: Array
});

let backUrl = (props.preproject?.status === undefined || props.preproject?.status === null)
    ? { route: 'preprojects.index', params: { type: props.preproject.cost_line_id } }
    : props.preproject.status == true
        ? { route: 'preprojects.index', params: { type: props.preproject.cost_line_id, preprojects_status: 1 } }
        : { route: 'preprojects.index', params: { type: props.preproject.cost_line_id, preprojects_status: 0 } }

const hasPermission = (permission) => {
    return props.userPermissions.includes(permission);
}

const confirmPurchasesDeletion = (purchaseId) => {
    purchaseToDelete.value = purchaseId;
    confirmingPurchasesDeletion.value = true;
};

const deletePurchase = () => {
    const purchaseId = purchaseToDelete.value;
    if (purchaseId) {
        router.delete(route('purchasesrequest.destroy', { id: purchaseId }), {
            onSuccess: () => closeModal()
        });
    }
};

const closeModal = () => {
    confirmingPurchasesDeletion.value = false;
};

const add_purchase_request = () => {
    router.get(route('preprojects.request.create', {
        id: props.preproject.id
    }));
}

// const edit_purchase_request = (purchase_id) => {
//     router.get(route('projectmanagement.purchases_request.create', { id: props.preproject.id, purchase_id: purchase_id }));
// }

// const expenses = () => {
//     router.get(route('projectmanagement.expenses', {
//         id: props.preproject
//     }));
// }



</script>