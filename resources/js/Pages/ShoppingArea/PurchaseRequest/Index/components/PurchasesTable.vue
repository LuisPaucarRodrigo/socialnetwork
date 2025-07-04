<template>

    <TableStructure>
        <template #thead>
            <tr>
                <TableTitle>Código de Solicitud</TableTitle>
                <TableTitle>Anteproyecto/Proyecto</TableTitle>
                <TableTitle>Solicitud</TableTitle>
                <TableTitle>Fecha Limite de Compra</TableTitle>
                <TableTitle>Estado</TableTitle>
                <TableTitle>Nr. Cotizaciones</TableTitle>
                <TableTitle>Exportar Solicitud de Compra</TableTitle>
                <TableTitle>Estado de los productos</TableTitle>
                <TableTitle>Acciones</TableTitle>
            </tr>
        </template>
        <template #tbody>
            <tr v-for="purchase in (purchases.data || purchases)" :key="purchase.id" class="text-gray-700" :class="[
                'text-gray-700',
                {
                    'border-l-8': true,
                    'border-green-500': !['Rechazada', 'Pendiente', 'En progreso'].includes(purchase.state), // Si la fecha de finalización es 'Disponible', pinta el borde de verde
                    'border-red-500': Date.parse(purchase.due_date) <= Date.now() + (3 * 24 * 60 * 60 * 1000) && purchase.state != 'Completada', // Si la fecha vence en 3 días o menos, pinta el borde de rojo
                    'border-yellow-500': Date.parse(purchase.due_date) > Date.now() + (3 * 24 * 60 * 60 * 1000) && Date.parse(purchase.due_date) <= Date.now() + (7 * 24 * 60 * 60 * 1000) && purchase.state != 'Completada' // Si la fecha está entre 3 y 7 días desde hoy, pinta el borde de amarillo
                }
            ]">
                <TableRow>{{ purchase.code }}</TableRow>
                <TableRow>{{ purchase.project?.code }} {{ purchase.preproject?.code }}</TableRow>
                <TableRow>{{ purchase.title }}</TableRow>
                <TableRow>
                    {{ purchase.due_date ? formattedDate(purchase.due_date) : purchase.due_date }}
                </TableRow>
                <TableRow>{{ purchase.state }}</TableRow>
                <TableRow>
                    Acep({{ purchase.purchase_quotes_with_state_count }})
                    Recha({{ purchase.purchase_quotes_without_state_count }})
                    Cotizaciones({{ purchase.purchase_quotes_count }})
                </TableRow>
                <TableRow>
                    <a target="_blank" :href="route('purchasingrequest.export', { id: purchase.id })">
                        <DownloadIcon />
                    </a>
                </TableRow>
                <TableRow>
                    <p v-if="!purchase.products_state" class="text-red-500">Incompletos o Cotizaciones
                        Pendientes
                    </p>
                    <p v-else :class="`text-green-600`">Completados y Aceptados</p>
                </TableRow>
                <TableRow>
                    <div class="flex space-x-3 justify-left">
                        <Link :href="route('purchasingrequest.details', { id: purchase.id })">
                        <ShowIcon />
                        </Link>
                        <div v-permisssion="'purchases_request_actions_manager'">
                            <Link v-if="purchase.project == null && purchase.state != 'Aceptado'"
                                :href="route('purchasesrequest.edit', { id: purchase.id })">
                            <EditIcon color="text-yellow-400" />
                            </Link>
                            <span v-else>
                                <EditIcon color="text-gray-400" />
                            </span>
                        </div>
                        <button v-permisssion="'purchases_request_actions'"
                            v-if="!purchase.preproject?.has_quote && (purchase.state == 'Pendiente' || purchase.state == 'En progreso')"
                            type="button" @click="confirmPurchase(purchase)">
                            <PlusDocumentIcon />
                        </button>
                        <span v-permisssion="'purchases_request_actions'" v-if="purchase.preproject?.has_quote">
                            <PlusDocumentIcon color="text-gray-400" />
                        </span>
                        <div v-permisssion="'purchases_request_actions_manager'">
                            <button v-if="purchase.state != 'Aceptado'" type="button"
                                @click="confirmPurchasesDeletion(purchase.id)">
                                <DeleteIcon />
                            </button>
                            <span v-else>
                                <DeleteIcon />
                            </span>
                        </div>
                    </div>
                </TableRow>
            </tr>
        </template>
    </TableStructure>

    <div v-if="purchases.data"
        class="flex flex-col items-center border-t bg-white px-5 py-5 xs:flex-row xs:justify-between">
        <Pagination :links="purchases.links" />
    </div>
</template>
<script setup>
import { DeleteIcon, PlusDocumentIcon, DownloadIcon, EditIcon, ShowIcon } from '@/Components/Icons';
import { notifyError } from '@/Components/Notification';
import Pagination from '@/Components/Pagination.vue';
import TableRow from '@/Components/TableRow.vue';
import TableTitle from '@/Components/TableTitle.vue';
import TableStructure from '@/Layouts/TableStructure.vue';
import { formattedDate } from '@/utils/utils';
import { Link, router } from '@inertiajs/vue3';

const { purchases, confirmPurchasesDeletion } = defineProps({
    purchases: Object,
    confirmPurchasesDeletion: Function
})

function confirmPurchase(purchase) {
    purchase.due_date === null && purchase.project ? errorPurchase() : purchase.state_quote == false && purchase.project_id != null ? router.get(route('purchasesrequest.quote_deadline.complete', { id: purchase.project_id })) : router.get(route('purchasesrequest.quotes.create', { id: purchase.id }))
}

function errorPurchase() {
    notifyError("Debe ingresar la fecha de solicitud en proyectos")
}
</script>