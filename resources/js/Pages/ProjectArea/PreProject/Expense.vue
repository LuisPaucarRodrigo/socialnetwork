<template>

    <Head title="Cotizaciones de Anteproyecto" />
    <AuthenticatedLayout :redirectRoute="purchases ? 'purchasesrequest.index' : 'preprojects.index'">
        <template #header>
            {{ purchases ? 'Cotizaciones Pendientes' : 'Cotizaciones de Anteproyecto' }}
        </template>
        <div class="min-w-full overflow-hidden rounded-lg shadow">
            <div class="overflow-x-auto">
                <table class="w-full whitespace-no-wrap">
                    <thead>
                        <tr
                            class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
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
                                Cotizacion/PDF
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Detalles
                            </th>
                            <th v-if="hasPermission('ProjectManager')"
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                ¿Usar esta cotización?
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="quote in quotes.data" :key="quote.id" class="text-gray-700">
                            <template v-if="quote.purchasing_request_id != null">
                                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                    <p class="text-gray-900 whitespace-no-wrap">{{ quote.purchasing_requests.code }}
                                    </p>
                                </td>
                                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                    <p class="text-gray-900 whitespace-no-wrap">
                                        {{ quote.purchasing_requests.title }}</p>
                                </td>
                                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                    <p class="text-gray-900 whitespace-no-wrap">{{ quote.provider.company_name }}</p>
                                </td>
                                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                    <p class="text-gray-900 whitespace-no-wrap">{{ quote.deliverable_time }} días</p>
                                </td>
                                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                    <p class="text-gray-900 whitespace-no-wrap">{{ quote.payment_type }}</p>
                                </td>
                                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm whitespace-nowrap">
                                    <p class="text-gray-900 whitespace-no-wrap">{{ quote.currency === 'dolar' ? '$' :
        'S/.' }} {{ (quote.total_amount).toFixed(2) }}</p>
                                </td>
                                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                    <button @click="openPreviewDocumentModal(quote.id)"
                                        class="flex justify-center items-center text-green-600 hover:underline">
                                        Previsualizar
                                        <EyeIcon class="h-4 w-4 ml-1" />
                                    </button>
                                </td>
                                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                    <Link
                                        :href="route(purchases ? 'purchase.quote.details.complete' : 'preprojects.purchase.quote.details', { id: quote.id })"
                                        class="flex items-center text-blue-500 hover:underline">
                                    <EyeIcon class="h-4 w-4 ml-1" />
                                    </Link>
                                </td>
                                <td v-if="hasPermission('ProjectManager')"
                                    class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                    <div v-if="quote.preproject_state === null" class="flex space-x-3 justify-center">
                                        <button @click="() => setQuotePreprojectStatus(quote.id, true)"
                                            class="rounded-xl whitespace-no-wrap text-center text-sm text-green-900 hover:bg-green-200">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-green-500">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                            </svg>
                                        </button>
                                        <button @click="() => setQuotePreprojectStatus(quote.id, false)" type="button"
                                            class="rounded-xl whitespace-no-wrap text-center text-sm text-red-900 hover:bg-red-200">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-red-500">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                            </svg>
                                        </button>
                                    </div>
                                    <div v-else>
                                        <p v-if="quote.preproject_state == true"
                                            :class="'text-green-500 whitespace-nowrap text-center'">
                                            Aceptado
                                        </p>
                                        <p v-if="quote.preproject_state == false" :class="'text-red-500 text-center'">
                                            Rechazado
                                        </p>
                                    </div>
                                </td>
                            </template>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="flex flex-col items-center border-t bg-white px-5 py-5 xs:flex-row xs:justify-between">
                <pagination :links="quotes.links" />
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import { EyeIcon } from '@heroicons/vue/24/outline';
import { Head, Link, router } from '@inertiajs/vue3';

const props = defineProps({
    quotes: Object,
    purchases: {
        type: Boolean,
        required: false
    },
    userPermissions:Array
})

const hasPermission = (permission) => {
    return props.userPermissions.includes(permission);
}


function openPreviewDocumentModal(documentId) {
    const url = route('purchasesrequest.show', { id: documentId });
    window.open(url, '_blank');
}

//Activate Deactivate
const setQuotePreprojectStatus = (id, state) => {
    router.post(
        route('preprojects.purchase_quote.accept_decline', { purchase_quote_id: id }),
        { preproject_state: state },
    )
}


</script>
