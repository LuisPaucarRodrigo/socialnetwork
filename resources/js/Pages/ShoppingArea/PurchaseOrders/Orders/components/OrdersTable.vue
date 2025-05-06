<template>
    <TableStructure>
        <template #thead>
            <tr>
                <TableTitle>Proyecto</TableTitle>
                <TableTitle>Codigo de Orden</TableTitle>
                <TableTitle>Titulo de Solicitud</TableTitle>
                <TableTitle>Fecha de solicitud de compra</TableTitle>
                <TableTitle>Fecha de llegada de la compra</TableTitle>
                <TableTitle>Exportar Orden de Compra</TableTitle>
                <TableTitle>Cotización</TableTitle>
                <TableTitle>Estado de Envío</TableTitle>
            </tr>
        </template>

        <template #tbody>
            <tr v-for="order in orders.data || orders" :key="order.id" :class="[
                'text-gray-700',
                {
                    'border-l-8': true,
                    'border-red-500': Date.parse(order.purchase_quote.purchasing_requests.due_date) <= Date.now() + (3 * 24 * 60 * 60 * 1000),
                    'border-yellow-500': Date.parse(order.purchase_quote.purchasing_requests.due_date) > Date.now() + (3 * 24 * 60 * 60 * 1000) &&
                        Date.parse(order.purchase_quote.purchasing_requests.due_date) <= Date.now() + (7 * 24 * 60 * 60 * 1000)
                }
            ]">
                <TableRow>{{ order.purchase_quote.purchasing_requests.project?.name }} </TableRow>
                <TableRow>{{ order.code }} </TableRow>
                <TableRow>{{ order.purchase_quote.purchasing_requests.title }} </TableRow>
                <TableRow>{{ formattedDate(order.purchase_quote.purchasing_requests.due_date) }} </TableRow>
                <TableRow>{{ formattedDate(order.purchase_arrival_date) }} </TableRow>
                <TableRow><a target="_blank" :href="route('purchaseorders.export.order', { id: order.id })">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-5 h-5 text-green-600">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                        </svg>
                    </a>
                </TableRow>
                <TableRow>
                    <button @click="openCotization(order)">
                        <EyeIcon class="h-5 w-5 text-green-600" />
                    </button>
                </TableRow>
                <TableRow>
                    <select v-if="hasPermission('PurchasingManager')" id="selectState"
                        @change="updateState(order.id, $event.target.value, order.is_payments_completed)">
                        <option selected disabled>{{ order.state }}</option>
                        <option v-for="option in availableOptions(order.state)" :key="option" :value="option">
                            {{ option }}
                        </option>
                    </select>
                    <p v-else class="text-gray-900 whitespace-no-wrap">
                        {{ order.state }}
                    </p>
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
import Pagination from '@/Components/Pagination.vue';
import TableTitle from '@/Components/TableTitle.vue';
import TableRow from '@/Components/TableRow.vue';
import TableStructure from '@/Layouts/TableStructure.vue';
import { EyeIcon } from '@heroicons/vue/24/outline';
import { formattedDate } from '@/utils/utils';

const { orders, openCotization, updateState, userPermissions } = defineProps({
    orders: Object,
    openCotization: Function,
    updateState: Function,
    userPermissions: Array
})

const availableOptions = (state) => {
    if (state === 'Pendiente' || userPermissions.includes('UserManager')) {
        return ['Pendiente', 'OC Enviada', 'Completada'];
    } else if (state === 'OC Enviada') {
        return ['OC Enviada', 'Completada'];
    } else {
        return ['Completada'];
    }
};

const hasPermission = (permission) => {
    return props.userPermissions.includes(permission);
}
</script>