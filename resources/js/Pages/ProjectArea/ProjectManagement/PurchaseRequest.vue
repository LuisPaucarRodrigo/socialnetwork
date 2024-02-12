<template>
    <Head title="Solicitudes del proyecto" />
    <AuthenticatedLayout>
        <template #header>
            Lista de solicitudes
        </template>

        <div class="inline-block min-w-full overflow-hidden rounded-lg shadow">
            <div class="flex gap-2">
                <button @click="add_purchase_request" type="button"
                    class="rounded-md bg-indigo-600 px-4 py-2 text-center text-sm text-white hover:bg-indigo-500">
                    + Agregar
                </button>
                <button @click="expenses" type="button"
                    class="rounded-md bg-teal-600 px-4 py-2 text-center text-sm text-white hover:bg-teal-500">
                    Gastos
                </button>
                <Link :href="route('projectmanagement.additionalCosts', { project_id: props.project.id })"
                    class="rounded-md bg-gray-600 px-4 py-2 text-center text-sm text-white hover:bg-gray-500">
                Gastos Adicionales
                </Link>
            </div>
            <table class="w-full whitespace-no-wrap">
                <thead>
                    <tr class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                            Solicitud
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                            Descripción de producto
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                            Fecha Limite
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                            Estado
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="purchase in purchases.data" :key="purchase.id" class="text-gray-700">
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                            <p class="text-gray-900 whitespace-no-wrap">{{ purchase.title }}</p>
                        </td>
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                            <p class="text-gray-900 whitespace-no-wrap">{{ purchase.product_description }}</p>
                        </td>
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                            <p class="text-gray-900 whitespace-no-wrap">{{ purchase.due_date }}</p>
                        </td>
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                            <p
                                :class="(purchase.state == 'Rechazado' ? 'text-red-500' : 'text-gray-900') + ' whitespace-no-wrap'">
                                {{ purchase.state }}</p>
                            <p v-if="purchase.state == 'Rechazado'">{{ purchase.response }}</p>
                        </td>
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                            <div class="flex space-x-3 justify-center">
                                <Link class="whitespace-no-wrap"
                                    :href="route('purchasingrequest.details', { id: purchase.id })">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor" class="w-6 h-6 text-teal-500">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                </Link>
                                <button class="whitespace-no-wrap" :disabled="purchase.state == 'pendiente' ? false : true"
                                    @click="edit_purchase_request(purchase.id)">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor"
                                        :class="'w-6 h-6 ' + (purchase.state == 'pendiente' ? 'text-amber-400' : 'text-amber-200')">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                    </svg>
                                </button>
                                <button type="button" @click="confirmPurchasesDeletion(purchase.id)"
                                    :disabled="purchase.state == 'pendiente' ? false : true" class="whitespace-no-wrap">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor"
                                        :class="'w-6 h-6 ' + (purchase.state == 'pendiente' ? 'text-red-500' : 'text-red-200')">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>

            <div class="flex flex-col items-center border-t bg-white px-5 py-5 xs:flex-row xs:justify-between">
                <pagination :links="purchases.links" />
            </div>
        </div>
        <div class="mt-6 flex items-center justify-between gap-x-6">
            <a :href="route('projectmanagement.index')"
                class="rounded-md bg-indigo-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                Atras
            </a>
        </div>
        <Modal :show="confirmingPurchasesDeletion">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">
                    Estas seguro de eliminar la solicitud?
                </h2>

                <p class="mt-1 text-sm text-gray-600">
                    Se eliminara toda la informacion relacionada con la solicitudr.
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
import DangerButton from '@/Components/DangerButton.vue';
import Pagination from '@/Components/Pagination.vue';
import Modal from '@/Components/Modal.vue';
import { ref } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';

const confirmingPurchasesDeletion = ref(false);
const showError = ref(false)
const purchaseToDelete = ref(null);

const props = defineProps({
    purchases: Object,
    project: Object
});

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
    // if (props.project.initial_budget == null) {
    //     showError.value = true
    //     setTimeout(() => {
    //         showError.value = false;
    //     }, 2500);
    // } else {

    // }
    router.get(route('projectmanagement.purchases_request.create', {
        project_id: props.project.id
    }));
}

const edit_purchase_request = (purchase_id) => {
    router.get(route('projectmanagement.purchases_request.create', { project_id: props.project.id, purchase_id: purchase_id }));
}

const expenses = () => {
    router.get(route('projectmanagement.expenses', {
        project_id: props.project.id
    }));
}



</script>
