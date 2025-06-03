<template>
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
            <tr v-for="order in orders.data || orders" :key="order.id">
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
                        <ShowIcon />
                    </button>
                </TableRow>
            </tr>
        </template>
    </TableStructure>
    <div v-if="orders.data"
        class="flex flex-col items-center border-t bg-white px-5 py-5 xs:flex-row xs:justify-between">
        <pagination :links="orders.links" />
    </div>
</template>
<script setup>
import { formattedDate } from '@/utils/utils';
import TableStructure from '@/Layouts/TableStructure.vue';
import TableTitle from '@/Components/TableTitle.vue';
import TableRow from '@/Components/TableRow.vue';
import Pagination from '@/Components/Pagination.vue';
import ShowIcon from '@/Components/Icons/ShowIcon.vue';

const { orders } = defineProps({
    orders: Object
})

function openPreview(factureId) {
    const url = route('purchasesorder.showFacture', { id: factureId });
    window.open(url, '_blank');
}
</script>