<template>
    <Head title="Gestion de Gastos Cuadrilla" />
    <AuthenticatedLayout :redirectRoute="'managementexpense.index'">
        <template #header>
            Gastos
        </template>
        <div class="min-w-full overflow-hidden rounded-lg shadow">
            <div class="overflow-x-auto">
                <table class="w-full whitespace-no-wrap">
                    <thead>
                        <tr
                            class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
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
                                Descripcion de Solicitud
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Proveedor
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Respuesta de la cotizacion
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Monto Total
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Fecha Limite
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Cotizacion/PDF
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">

                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="expense in expenses.data" :key="expense.id" class="text-gray-700" 
                        :class="[
                            'text-gray-700',
                            {
                                'border-l-8': true,
                                'border-green-500': expense.purchasing_requests.state != 'En Progreso' && expense.purchasing_requests.state != 'pendiente',
                                'border-yellow-500': Math.ceil((new Date(expense.quote_deadline) - new Date()) / (1000 * 3600 * 24)) > 3 && Math.ceil((new Date(expense.quote_deadline) - new Date()) / (1000 * 3600 * 24)) <= 7 && expense.purchasing_requests.state != 'Aceptado',
                                'border-red-500': Math.ceil((new Date(expense.quote_deadline) - new Date()) / (1000 * 3600 * 24)) <= 3 && expense.purchasing_requests.state != 'Aceptado',
                            }
                        ]">

                            <template v-if="expense.purchasing_request_id != null">
                                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                    <p class="text-gray-900 whitespace-no-wrap">{{ expense.purchasing_requests.project?.name }}</p>
                                </td>
                                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                    <p class="text-gray-900 whitespace-no-wrap">{{ expense.purchasing_requests.title }}</p>
                                </td>
                                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                    <p class="text-gray-900 whitespace-no-wrap">{{
                                        expense.purchasing_requests?.product_description }}</p>
                                </td>
                                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                    <p class="text-gray-900 whitespace-no-wrap">{{ expense.provider }}</p>
                                </td>
                                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                    <p class="text-gray-900 whitespace-no-wrap">{{ expense.response }}</p>
                                </td>
                                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                    <p class="text-gray-900 whitespace-no-wrap">{{ expense.amount }}</p>
                                </td>
                                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                    <p class="text-gray-900 whitespace-no-wrap">{{ formattedDate(expense.quote_deadline) }}
                                    </p>
                                </td>
                                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                    <button @click="openPreviewDocumentModal(expense.id)"
                                        class="flex justify-center items-center text-green-600 hover:underline mb-5">
                                        Previsualizar
                                        <EyeIcon class="h-4 w-4 ml-1" />
                                    </button>
                                </td>
                                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                    <div v-if="expense.purchasing_requests.state == 'En Progreso'" class="flex space-x-3 justify-center">
                                        <button @click="sendReply('Aceptado', expense.id)" type="button"
                                            class="rounded-xl whitespace-no-wrap text-center text-sm text-green-900 hover:bg-green-200">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-green-500">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                            </svg>
                                        </button>
                                        <button @click="sendReply('Rechazado', expense.id)" type="button"
                                            class="rounded-xl whitespace-no-wrap text-center text-sm text-red-900 hover:bg-red-200">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-red-500">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </template>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="flex flex-col items-center border-t bg-white px-5 py-5 xs:flex-row xs:justify-between">
                <pagination :links="expenses.links" />
            </div>
        </div>
    </AuthenticatedLayout>
</template>
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import { EyeIcon } from '@heroicons/vue/24/outline';
import { Head, useForm } from '@inertiajs/vue3';
import { formattedDate } from '@/utils/utils';

const props = defineProps({
    expenses: Object
})

console.log(props.expenses);

const form = useForm({
    state: '',
    purchase_quote_id: ''
});

const sendReply = (state, id) => {
    form.state = state
    form.purchase_quote_id = id
    form.put(route('managementexpense.reviewed', { id: id }), form, {
        preserveScroll: true,
        onFinish: () => form.reset(),
    });

};

function openPreviewDocumentModal(documentId) {
    const url = route('purchasesrequest.show', { id: documentId });
    window.open(url, '_blank');
}
</script>