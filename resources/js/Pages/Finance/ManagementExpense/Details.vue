<template>

    <Head title="Gestion de Gastos Cuadrilla" />
    <AuthenticatedLayout :redirectRoute="'managementexpense.index'">
        <template #header>
            {{ expense.code }}  
        </template>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-10">
            <div class="col-span-1 min-w-full rounded-lg shadow bg-white p-6 grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div v-if="expense.purchasing_requests.project" class="mb-4">
                    <p class="text-sm text-gray-700 font-medium">Proyecto:</p>
                    <p class="text-lg text-gray-900">{{ expense.purchasing_requests.project?.code }}</p>
                </div>
                <div class="mb-4">
                    <p class="text-sm text-gray-700 font-medium">Proveedor:</p>
                    <p class="text-lg text-gray-900">{{ expense.provider.company_name }}</p>
                </div>
                <div class="mb-4">
                    <p class="text-sm text-gray-700 font-medium">Tiempo de entrega:</p>
                    <p class="text-lg text-gray-900">{{ expense.deliverable_time }} días</p>
                </div>
                <div class="mb-4">
                    <p class="text-sm text-gray-700 font-medium">Monto Total:</p>
                    <p class="text-lg text-gray-900">{{ expense.currency === 'dolar' ? '$' : 'S/.' }} {{
        (expense.total_amount).toFixed(2) }}</p>
                </div>
                <div class="mb-4">
                    <p class="text-sm text-gray-700 font-medium">Fecha Límite de Aprobación:</p>
                    <p class="text-lg text-gray-900">{{ formattedDate(expense.quote_deadline) }}</p>
                </div>
                <div class="mb-4">
                    <p class="text-sm text-gray-700 font-medium">Cotización/PDF:</p>
                    <button @click="openPreviewDocumentModal(expense.id)"
                        class="text-lg text-green-600 hover:underline flex items-center">
                        Previsualizar
                        <EyeIcon class="h-6 w-6 ml-1" />
                    </button>
                </div>
                <div class="mb-4">
                    <p class="text-sm text-gray-700 font-medium">Estado:</p>
                    <p :class="`${expense.state === null ? 'text-indigo-500' : expense.state ? 'text-green-500' : 'text-red-500'}`"
                        class="text-lg">
                        {{ expense.state === null ? 'Pendiente' : expense.state ? 'Aceptado' : 'Rechazado' }}
                    </p>
                </div>
            </div>

            <div class="col-span-1 min-w-full rounded-lg shadow bg-white p-6 grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div class="mb-4">
                    <p class="text-sm text-gray-700 font-medium">Código de Solicitud:</p>
                    <p class="text-lg text-gray-900">{{ expense.purchasing_requests.code }}</p>
                </div>
                <div class="mb-4">
                    <p class="text-sm text-gray-700 font-medium">Título de Solicitud:</p>
                    <p class="text-lg text-gray-900">{{ expense.purchasing_requests?.title }}</p>
                </div>
                <div class="mb-4">
                    <p class="text-sm text-gray-700 font-medium">Estado:</p>
                    <p class="text-lg text-gray-900">{{ expense.purchasing_requests.state }}</p>
                </div>
                <div class="mb-4">
                    <p class="text-sm text-gray-700 font-medium">Fecha límite de compra:</p>
                    <p class="text-lg text-gray-900">{{ expense.purchasing_requests.due_date ? formattedDate(expense.purchasing_requests.due_date) : 'No hay fecha definida' }}</p>
                </div>
                <div class="mb-4">
                    <p class="text-sm text-gray-700 font-medium">IGV:</p>
                    <p class="text-lg text-gray-900">{{ expense.igv ? 'Si' : 'No' }}</p>
                </div>
            </div>

        </div>



        <h1 class="text-2xl font-semibold text-gray-700 mt-4 mb-4">Detalles</h1>

        <div class="min-w-full overflow-hidden rounded-lg shadow bg-white p-6 grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div class="col-span-1 sm:col-span-2">
                <div class="overflow-x-auto ring-1 rounded-md ring-gray-300">
                    <table class="w-full">
                        <thead>
                            <tr
                                class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                <th
                                    class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                    #
                                </th>
                                <th
                                    class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                    Código
                                </th>
                                <th
                                    class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                    Nombre del producto
                                </th>
                                <th
                                    class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                    Cantidad
                                </th>
                                <th
                                    class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                    Unidad
                                </th>
                                <th
                                    class="text-center border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-xs font-semibold uppercase tracking-wider text-gray-600">
                                    P.U.
                                </th>
                                <!-- v-if="auth.user.role_id === 1 || preproject.quote === null" -->
                                <th v-if="expense.igv"
                                    class="w-32 border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-xs font-semibold uppercase tracking-wider text-gray-600 text-center">
                                    Sin IGV
                                </th>
                                <!-- <th v-if="currency !== 'S/.'"
                                                class="w-32 border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-xs font-semibold uppercase tracking-wider text-gray-600 text-center">
                                                Soles
                                            </th> -->
                                <th
                                    class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-xs font-semibold uppercase tracking-wider text-gray-600 text-right">
                                    Monto Total
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- v v-for="(item, index) in (form.items)" :key="index" -->
                            <tr v-for="(item, index) in (expense.products)" :key="index"
                                class="text-gray-700 hover:bg-gray-200 bg-white">
                                <td class="border-b border-gray-200 px-5 py-5 text-sm">
                                    <p>
                                        {{ index + 1 }}
                                    </p>
                                </td>
                                <td class="border-b border-gray-200 px-5 py-5 text-sm">
                                    <p>
                                        {{ item.code }}
                                    </p>
                                </td>
                                <td class="border-b border-gray-200 px-5 py-5 text-sm">
                                    <p class="text-gray-900">
                                        {{ item.name }}
                                    </p>
                                </td>
                                <td class="border-b border-gray-200 px-5 py-5 text-sm">
                                    <p class="text-gray-900">
                                        {{ item.pivot?.quantity }}
                                    </p>
                                </td>
                                <td class="border-b border-gray-200 px-5 py-5 text-sm">
                                    <p class="text-gray-900">
                                        {{ item.unit }}
                                    </p>
                                </td>
                                <td class="border-b border-gray-200 px-5 py-5 text-sm">
                                    <p class="text-gray-900 whitespace-nowrap text-center">
                                        {{ expense.currency === 'dolar' ? '$' : 'S/.' }} {{
                                            (item.pivot?.unitary_amount * (1/1.18)).toFixed(2) }}
                                    </p>
                                </td>
                                <td v-if="expense.igv"
                                    class=" w-32 border-b border-gray-200 px-5 py-5 text-sm text-center">
                                    <p class="text-gray-900 whitespace-nowrap">
                                        {{ expense.currency === 'dolar' ? '$' : 'S/.' }} {{
                                           (item.pivot?.unitary_amount * (expense.igv ? 1/1.18 :1.18) 
                                           * item.pivot?.quantity).toFixed(2) }}
                                    </p>
                                </td>
                                <td class="border-b border-gray-200 px-5 py-5 text-sm">
                                    <p class="text-gray-900 whitespace-nowrap text-right">
                                        {{ expense.currency === 'dolar' ? '$' : 'S/.' }} {{
                                            ((item.pivot?.unitary_amount * item.pivot?.quantity).toFixed(2) / 
                                            (expense.igv ? 1 :1.18)).toFixed(2) }}
                                    </p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="col-span-1 sm:col-span-2 flex justify-end py-4 px-10 text-indigo-900 text-sm">
                <div class="grid grid-cols-2 gap-3">
                    <p class="w-28">Subtotal</p>
                    <p class="w-28 text-right flex justify-between">
                        <span>
                            {{ expense.currency === 'dolar' ? '$' : 'S/.' }}
                        </span>
                        <span>
                            {{ getTotals(expense.products, expense.igv).subTotal }}
                        </span>
                    </p>
                    <p class="w-28">IGV</p>
                    <p class="w-28 text-right flex justify-between">
                        <span>
                            {{ expense.currency === 'dolar' ? '$' : 'S/.' }}
                        </span>
                        <span>
                            {{ getTotals(expense.products, expense.igv).igv }}
                        </span>
                    </p>
                    <p class="w-28">Total</p>
                    <p class="w-28 text-right flex justify-between">
                        <span>
                            {{ expense.currency === 'dolar' ? '$' : 'S/.' }}
                        </span>
                        <span>
                            {{ getTotals(expense.products, expense.igv).total }}
                        </span>
                    </p>
                </div>
            </div>

        </div>

        <div v-if="expense.purchase_order">
            <h1 class="text-2xl font-semibold text-gray-700 mt-4 mb-4">Orden de Compra</h1>

            <div
                class="min-w-full overflow-hidden rounded-lg shadow bg-white p-6 grid grid-cols-1 sm:grid-cols-3 gap-6">
                <div class="mb-4">
                    <p class="text-sm text-gray-700 font-medium">Codigo de Orden:</p>
                    <p class="text-lg text-gray-900">{{ expense.purchase_order.code }}</p>
                </div>
                <div class="mb-4">
                    <p class="text-sm text-gray-700 font-medium">Fecha esperada de llegada de la compra:</p>
                    <p class="text-lg text-gray-900">{{ expense.purchase_order.purchase_arrival_date }}</p>
                </div>
                <div class="mb-4">
                    <p class="text-sm text-gray-700 font-medium">Estado de la orden:</p>
                    <p class="text-lg text-gray-900">{{ expense.purchase_order?.state }}</p>
                </div>
            </div>
        </div>
        <br>
        <br>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
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


function getTotals(products, hasIGV) {
    let subTotal = 0;
    let igv = 0;
    let total = 0;
    products.forEach(item => {
        subTotal += item.pivot.quantity * item.pivot.unitary_amount/(hasIGV?1:1.18) 
    });
    if (hasIGV) {
        total = subTotal.toFixed(2)
        subTotal = (total/1.18).toFixed(2)
        igv = (total - subTotal).toFixed(2)
    } else {
        subTotal = subTotal.toFixed(2)
        igv = (subTotal * .18).toFixed(2)
        total = (+subTotal + +igv).toFixed(2)
    }
    return { subTotal, igv, total }
}


</script>
