<template>

    <Head title="Solicitudes del proyecto" />
    <AuthenticatedLayout :redirect-route="backUrl">
        <template #header>
            Lista de solicitudes
        </template>

        <div class="inline-block min-w-full overflow-hidden rounded-lg shadow">
            <div class="flex gap-2">
                <PrimaryButton v-if="project.status === null && hasPermission('ProjectManager')"
                    @click="add_purchase_request" type="button">
                    + Agregar
                </PrimaryButton>
                <PrimaryButton @click="expenses" type="button" class="bg-teal-600 hover:bg-teal-500">
                    Gastos
                </PrimaryButton>
                <Link :href="route('projectmanagement.additionalCosts', { project_id: props.project.id })"
                    class="rounded-md bg-gray-600 px-4 py-2 text-center text-sm text-white hover:bg-gray-500">
                Gastos Adicionales
                </Link>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full whitespace-no-wrap">
                    <thead>
                        <tr
                            class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Código
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Proyecto
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Titulo de Solicitud
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Fecha Limite de Compra
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
                        <tr v-for="purchase in purchases.data" :key="purchase.id" class="text-gray-700" :class="['text-gray-700',
        {
            'border-l-8': true,
            'border-green-500': !['Rechazada', 'Pendiente', 'En progreso'].includes(purchase.state), // Si la fecha de finalización es 'Disponible', pinta el borde de verde
            'border-red-500': Date.parse(purchase.due_date) <= Date.now() + (3 * 24 * 60 * 60 * 1000) && purchase.state != 'Completada', // Si la fecha vence en 3 días o menos, pinta el borde de rojo
            'border-yellow-500': Date.parse(purchase.due_date) > Date.now() + (3 * 24 * 60 * 60 * 1000) && Date.parse(purchase.due_date) <= Date.now() + (7 * 24 * 60 * 60 * 1000) && purchase.state != 'Completada' // Si la fecha está entre 3 y 7 días desde hoy, pinta el borde de amarillo
        }
    ]">
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 ">{{ purchase.code }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 ">{{ purchase.project?.code }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 ">{{ purchase.title }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 ">
                                    {{ purchase.due_date ? formattedDate(purchase.due_date) : purchase.due_date }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 ">{{ purchase.state }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <div v-if="purchase.due_date" class="flex space-x-3 justify-center">
                                    <Link class="text-blue-900 "
                                        :href="route('projectmanagement.purchases_request.details', { id: purchase.id })">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-teal-500">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    </Link>
                                    <template v-if="hasPermission('ProjectManager')">
                                        <div>
                                            <Link v-if="purchase.state == 'Pendiente'" class="text-blue-900 "
                                                :href="route('projectmanagement.purchases_request.edit', { id: purchase.id, project_id: project.id })">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-amber-400">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                            </svg>
                                            </Link>
                                            <span v-else class="text-gray-400">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                    stroke-width="1.5" stroke="currentColor"
                                                    class="w-6 h-6 text-gray-400">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                                </svg>
                                            </span>
                                        </div>
                                        <div>
                                            <button v-if="purchase.state === 'Pendiente'" type="button"
                                                @click="confirmPurchasesDeletion(purchase.id)" class="text-blue-900 ">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                    stroke-width="1.5" stroke="currentColor"
                                                    class="w-6 h-6 text-red-500">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                                </svg>
                                            </button>
                                            <span v-else class="text-gray-400">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                    stroke-width="1.5" stroke="currentColor"
                                                    class="w-6 h-6 text-gray-400">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                                </svg>
                                            </span>
                                        </div>
                                    </template>

                                </div>
                                <div v-else class="flex space-x-3 justify-center">
                                    <button v-if="hasPermission('ProjectManager')" @click="due_date_show(purchase.id)"
                                        type="button"
                                        class="rounded-xl whitespace-no-wrap text-center text-sm text-red-900 hover:bg-red-200">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-green-500">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
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
        <Modal :show="due_date">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">
                    Ingrese fecha de compra de solicitud
                </h2>
                <p class="mt-1 text-sm text-gray-600">
                    Ingrese la fecha de limite de compra para poder habilitar la aprobacion de cotizaciones
                </p>
                <TextInput type="date" v-model="form.due_date" id="due_date" />
                <div class="mt-6 flex justify-end">
                    <SecondaryButton @click="closeModalDate">Cancel</SecondaryButton>

                    <PrimaryButton class="ml-3" @click="addDueDate()">
                        Guardar
                    </PrimaryButton>
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
import { formattedDate } from '@/utils/utils';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import TextInput from '@/Components/TextInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const confirmingPurchasesDeletion = ref(false);
const showError = ref(false)
const purchaseToDelete = ref(null);
const due_date = ref(false);
const purchaseToUpdate = ref(null);

const props = defineProps({
    purchases: Object,
    project: Object,
    auth: Object,
    userPermissions: Array
});

const hasPermission = (permission) => {
    return props.userPermissions.includes(permission);
}

let backUrl = props.project.status === null
    ? 'projectmanagement.index'
    : props.project.status == true
        ? 'projectmanagement.historial'
        : 'projectmanagement.index'

const form = useForm({
    due_date: '',
    purchase_id: ''
})

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
    router.get(route('projectmanagement.purchases_request.create', {
        project_id: props.project.id
    }));
};

const expenses = () => {
    router.get(route('projectmanagement.expenses', {
        project_id: props.project.id
    }));
}

function due_date_show(Id) {
    purchaseToUpdate.value = Id;
    due_date.value = true
}

const closeModalDate = () => {
    due_date.value = false;
};

function addDueDate() {
    form.purchase_id = purchaseToUpdate.value
    form.post(route('projectmanagement.update_due_date'), {
        onSuccess: () => {
            router.get(route('projectmanagement.purchases_request.index', { project_id: props.project.id }))
        }
    });
}

</script>