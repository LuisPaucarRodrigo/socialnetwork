<template>

    <Head title="Gestion de Gastos Cuadrilla" />
    <AuthenticatedLayout :redirectRoute="'managementexpense.index'">
        <template #header>
            Aprobacion de Compras
        </template>
        <div class="min-w-full rounded-lg">
            <div class="mt-6 flex items-center justify-end gap-x-4">
                <button @click="reentry" type="button"
                    class="rounded-md bg-indigo-600 px-4 py-2 text-center text-sm text-white hover:bg-indigo-500">
                    {{ boolean == false ? "Revisados" : "Sin Aprobar" }}
                </button>
                <input type="text" @input="search($event.target.value)" placeholder="Buscar...">
            </div>
            <div class="overflow-x-auto shadow">
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
                                Código de Solicitud
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Título de Solicitud
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Proveedor
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Tiempo de entrega
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Forma de Pago
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Monto Total
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Fecha Limite de Aprobacion
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Fecha Limite de Compra
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Cotizacion/PDF
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Detalles
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Acciones
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="expense in expenses.data" :key="expense.id" class="text-gray-700" :class="[
                            'text-gray-700',
                            {
                                'border-l-8': true,
                                'border-green-500': expense.state != 0 && expense.state != null,
                                'border-yellow-500': Math.ceil((new Date(expense.quote_deadline) - new Date()) / (1000 * 3600 * 24)) >= 4 && Math.ceil((new Date(expense.quote_deadline) - new Date()) / (1000 * 3600 * 24)) <= 7 && expense.state != 1,
                                'border-red-500': Math.ceil((new Date(expense.quote_deadline) - new Date()) / (1000 * 3600 * 24)) <= 3 && expense.state != 1,
                            }
                        ]">

                            <template v-if="expense.purchasing_request_id != null">
                                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                    <p class="text-gray-900 whitespace-nowrap">
                                        {{ expense.purchasing_requests.project?.code }}
                                    </p>
                                </td>
                                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                    <p class="text-gray-900 whitespace-no-wrap">{{ expense.purchasing_requests.code }}
                                    </p>
                                </td>
                                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                    <p class="text-gray-900 whitespace-no-wrap">
                                        {{ expense.purchasing_requests?.title }}</p>
                                </td>
                                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                    <p class="text-gray-900 whitespace-no-wrap">{{ expense.provider.company_name }}</p>
                                </td>
                                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                    <p class="text-gray-900 whitespace-no-wrap">{{ expense.deliverable_time }} días</p>
                                </td>
                                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                    <p class="text-gray-900 whitespace-no-wrap">{{ expense.payment_type }}</p>
                                </td>
                                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm whitespace-nowrap">
                                    <p class="text-gray-900 whitespace-no-wrap">{{ expense.currency === 'dolar' ? '$' :
                                        'S/.' }} {{ (expense.total_amount).toFixed(2) }}</p>
                                </td>
                                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                    <p class="text-gray-900 whitespace-no-wrap">{{ formattedDate(expense.quote_deadline)
                                    }}
                                    </p>
                                </td>
                                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                    <p class="text-gray-900 whitespace-no-wrap">{{
                                        formattedDate(expense.purchasing_requests.due_date)
                                        }}
                                    </p>
                                </td>
                                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                    <button @click="openPreviewDocumentModal(expense.id)">
                                        Previsualizar
                                        <ShowIcon />
                                    </button>
                                </td>
                                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                    <Link :href="route('managementexpense.details', { purchase_quote: expense.id })">
                                    <ShowIcon />
                                    </Link>
                                </td>
                                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                    <div v-if="expense.purchasing_requests.state == 'En progreso' && expense.state == null"
                                        class="flex space-x-3 justify-center">
                                        <Link :href="route('managementexpense.payment', { id: expense.id })"
                                            class="flex items-center text-blue-500 hover:underline">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-green-500">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                        </svg>
                                        </Link>
                                        <button @click="sendReply(false, expense.id)" type="button"
                                            class="rounded-xl whitespace-no-wrap text-center text-sm text-red-900 hover:bg-red-200">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-red-500">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                            </svg>
                                        </button>
                                    </div>
                                    <div v-else>
                                        <p :class="`${expense.state ? 'text-green-500' : 'text-red-500'}`">
                                            {{ expense.state ? 'Aceptado' : 'Rechazado' }}
                                        </p>
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
import { Head, router, Link } from '@inertiajs/vue3';
import { formattedDate } from '@/utils/utils';
import { ref } from 'vue';
import ShowIcon from '@/Components/Icons/ShowIcon.vue';

const props = defineProps({
    expenses: Object,
    boolean: Boolean,
    userPermissions: Array
})

const expenses = ref(props.expenses);

const sendReply = (state, id) => {
    router.put(route('managementexpense.reviewed', { id: id }), { state }, {
        preserveScroll: true,
        onSuccess: () => {
            router.visit(route('managementexpense.index'))
        }
    });
};

function openPreviewDocumentModal(documentId) {
    const url = route('purchasesrequest.show', { id: documentId });
    window.open(url, '_blank');
}

const search = async ($search) => {
    try {
        const response = await axios.post(route('managementexpense.index'), { searchQuery: $search });
        expenses.value = response.data.expenses;
    } catch (error) {
        console.error('Error searching:', error);
    }
};

const reentry = () => {
    if (props.boolean == true) {
        router.get(route('managementexpense.index'))
    } else {
        router.get(route('managementexpense.index', { boolean: true }))
    }
}

</script>
