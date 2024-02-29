<template>
    <Head title="Gestion de Gastos Cuadrilla" />
    <AuthenticatedLayout :redirectRoute="'managementexpense.index'">
        <template #header>
            Gastos
        </template>
        
        <div class="min-w-full overflow-hidden rounded-lg shadow bg-white p-6 grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div class="mb-4">
                <p class="text-sm text-gray-700 font-medium">Proyecto:</p>
                <p class="text-lg text-gray-900">{{ expense.purchasing_requests.project?.name }}</p>
            </div>
            <div class="mb-4">
                <p class="text-sm text-gray-700 font-medium">Proveedor:</p>
                <p class="text-lg text-gray-900">{{ expense.provider }}</p>
            </div>
            <div class="mb-4">
                <p class="text-sm text-gray-700 font-medium">Respuesta de la cotización:</p>
                <p class="text-lg text-gray-900">{{ expense.response }}</p>
            </div>
            <div class="mb-4">
                <p class="text-sm text-gray-700 font-medium">Monto Total:</p>
                <p class="text-lg text-gray-900">{{ expense.amount }}</p>
            </div>
            <div class="mb-4">
                <p class="text-sm text-gray-700 font-medium">Fecha Límite de Aprobación:</p>
                <p class="text-lg text-gray-900">{{ formattedDate(expense.quote_deadline) }}</p>
            </div>
            <div class="mb-4">
                <p class="text-sm text-gray-700 font-medium">Cotización/PDF:</p>
                <button @click="openPreviewDocumentModal(expense.id)"
                    class="text-lg text-green-600 hover:underline flex items-center">
                    Previsualizar <EyeIcon class="h-6 w-6 ml-1" />
                </button>
            </div>
            <div class="mb-4">
                <p class="text-sm text-gray-700 font-medium">Estado:</p>
                <p :class="`${expense.state ? 'text-green-500' : 'text-red-500'}`" class="text-lg">
                    {{ expense.state ? 'Aceptado' : 'Rechazado' }}
                </p>
            </div>
        </div>

        <h1 class="text-2xl font-semibold text-gray-700 mt-4 mb-4">Solicitud de Cotización</h1>

        <div class="min-w-full overflow-hidden rounded-lg shadow bg-white p-6 grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div class="mb-4">
                <p class="text-sm text-gray-700 font-medium">Título de Solicitud:</p>
                <p class="text-lg text-gray-900">{{ expense.purchasing_requests.title }}</p>
            </div>
            <div class="mb-4">
                <p class="text-sm text-gray-700 font-medium">Descripción de Solicitud:</p>
                <p class="text-lg text-gray-900">{{ expense.purchasing_requests?.product_description }}</p>
            </div>
            <div class="mb-4">
                <p class="text-sm text-gray-700 font-medium">Estado:</p>
                <p class="text-lg text-gray-900">{{ expense.purchasing_requests.state }}</p>
            </div>
            <div class="mb-4">
                <p class="text-sm text-gray-700 font-medium">Fecha límite de vencimiento:</p>
                <p class="text-lg text-gray-900">{{ formattedDate(expense.purchasing_requests.due_date) }}</p>
            </div>
        </div>

        <div v-if="expense.purchase_order">
            <h1 class="text-2xl font-semibold text-gray-700 mt-4 mb-4">Orden de Compra</h1>

            <div class="min-w-full overflow-hidden rounded-lg shadow bg-white p-6 grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div class="mb-4">
                    <p class="text-sm text-gray-700 font-medium">Fecha límite de compra:</p>
                    <p class="text-lg text-gray-900">{{ formattedDate(expense.purchase_order.date_issue) }}</p>
                </div>
                <div class="mb-4">
                    <p class="text-sm text-gray-700 font-medium">Estado de la orden:</p>
                    <p class="text-lg text-gray-900">{{ expense.purchase_order?.state }}</p>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import { EyeIcon } from '@heroicons/vue/24/outline';
import { Head } from '@inertiajs/vue3';
import { formattedDate } from '@/utils/utils';

const props = defineProps({
    expense: Object
})

function openPreviewDocumentModal(documentId) {
    const url = route('purchasesrequest.show', { id: documentId });
    window.open(url, '_blank');
}

</script>
