<template>

    <Head title="Ordenes de Compra" />
    <AuthenticatedLayout :redirectRoute="'purchaseorders.index'">
        <template #header>
            Compras Completadas
        </template>
        <div class="min-w-full overflow-hidden rounded-lg shadow">
            <div class="flex justify-end items-center gap-4">
                <div class="flex items-center">
                    <form @submit.prevent="search" class="flex items-center">
                        <input type="text" placeholder="Buscar..."
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            v-model="searchForm.searchTerm" />
                        <button type="submit" :class="{ 'opacity-25': searchForm.processing }"
                            class="ml-2 rounded-md bg-indigo-600 px-2 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                            <svg width="30px" height="21px" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M15.7955 15.8111L21 21M18 10.5C18 14.6421 14.6421 18 10.5 18C6.35786 18 3 14.6421 3 10.5C3 6.35786 6.35786 3 10.5 3C14.6421 3 18 6.35786 18 10.5Z"
                                    stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
            <TableStructure>
                <template #thead>
                    <tr>
                        <TableTitle>Codigo de Orden</TableTitle>
                        <TableTitle>Codigo de solicitud</TableTitle>
                        <TableTitle>Titulo de la Solicitud</TableTitle>
                        <TableTitle>Codigo de Cotizacion</TableTitle>
                        <TableTitle>Fecha de llegada de la compra</TableTitle>
                        <TableTitle>Numero de Serie</TableTitle>
                        <TableTitle>Fecha de factura</TableTitle>
                        <TableTitle>Numero de Factura</TableTitle>
                        <TableTitle>Otros</TableTitle>
                        <TableTitle>Factura</TableTitle>
                    </tr>
                </template>
                <template #tbody>
                    <tr v-for="order in (props.search ? orders : orders.data)" :key="order.id">
                        <TableRow>{{ order.code }}</TableRow>
                        <TableRow>{{ order.purchase_quote.purchasing_requests.code }}</TableRow>
                        <TableRow>{{ order.purchase_quote.purchasing_requests.title }}</TableRow>
                        <TableRow>{{ order.purchase_quote.code }}</TableRow>
                        <TableRow>{{ formattedDate(order.purchase_arrival_date) }}</TableRow>
                        <TableRow>{{ order.serie_number }}</TableRow>
                        <TableRow>{{ formattedDate(order.facture_date) }}</TableRow>
                        <TableRow>{{ order.facture_number }}</TableRow>
                        <TableRow>{{ order.others }}</TableRow>
                        <TableRow>
                            <button @click="openPreview(order.id)">
                                Previsualizar
                                <EyeIcon class="h-5 w-5 text-green-600" />
                            </button>
                        </TableRow>
                    </tr>
                </template>
            </TableStructure>
            <div v-if="props.search === undefined"
                class="flex flex-col items-center border-t bg-white px-5 py-5 xs:flex-row xs:justify-between">
                <pagination :links="orders.links" />
            </div>
        </div>

    </AuthenticatedLayout>
</template>
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import { EyeIcon } from '@heroicons/vue/24/outline';
import { Head, useForm, router } from '@inertiajs/vue3';
import { formattedDate } from '@/utils/utils';
import TableStructure from '@/Layouts/TableStructure.vue';
import TableTitle from '@/Components/TableTitle.vue';
import TableRow from '@/Components/TableRow.vue';

const props = defineProps({
    orders: Object,
    search: String
})

function openPreview(factureId) {
    const url = route('purchasesorder.showFacture', { id: factureId });
    window.open(url, '_blank');
}

const searchForm = useForm({
    searchTerm: props.search ? props.search : '',
})

const search = () => {
    if (searchForm.searchTerm == '') {
        router.visit(route('purchaseorders.history'));
    } else {
        router.visit(route('purchaseorders.search', { request: searchForm.searchTerm, history: 'history' }));
    }
}

</script>
