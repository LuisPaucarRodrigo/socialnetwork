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
                        <ArrowDownTrayIcon class="w-6 h-6 text-blue-600" />
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
                        <div v-if="auth.user.role_id == 1">
                            <Link v-if="purchase.project == null && purchase.state != 'Aceptado'"
                                :href="route('purchasesrequest.edit', { id: purchase.id })">
                            <EditIcon color="text-yellow-400" />
                            </Link>
                            <span v-else>
                                <EditIcon color="text-gray-400" />
                            </span>
                        </div>
                        <div>
                            <button
                                v-if="!purchase.preproject?.has_quote && hasPermission('PurchasingManager') && (purchase.state == 'Pendiente' || purchase.state == 'En progreso')"
                                type="button" @click="confirmPurchase(purchase)">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-blue-400">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m3.75 9v6m3-3H9m1.5-12H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                </svg>
                            </button>
                            <span v-if="purchase.preproject?.has_quote && hasPermission('PurchasingManager')">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-400">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m3.75 9v6m3-3H9m1.5-12H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                </svg>
                            </span>
                        </div>
                        <div v-if="auth.user.role_id == 1">
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
import { DeleteIcon, EditIcon, ShowIcon } from '@/Components/Icons/Index';
import { notifyError } from '@/Components/Notification';
import Pagination from '@/Components/Pagination.vue';
import TableRow from '@/Components/TableRow.vue';
import TableTitle from '@/Components/TableTitle.vue';
import TableStructure from '@/Layouts/TableStructure.vue';
import { formattedDate } from '@/utils/utils';
import { ArrowDownTrayIcon } from '@heroicons/vue/24/outline';
import { Link, router } from '@inertiajs/vue3';

const { purchases, auth, userPermissions, confirmPurchasesDeletion } = defineProps({
    purchases: Object,
    auth: Object,
    userPermissions: Array,
    confirmPurchasesDeletion: Function
})

function confirmPurchase(purchase) {
    purchase.due_date === null && purchase.project ? errorPurchase() : purchase.state_quote == false && purchase.project_id != null ? router.get(route('purchasesrequest.quote_deadline.complete', { id: purchase.project_id })) : router.get(route('purchasesrequest.quotes.create', { id: purchase.id }))
}

function errorPurchase() {
    notifyError("Debe ingresar la fecha de solicitud en proyectos")
}

const hasPermission = (permission) => {
    return userPermissions.includes(permission);
}
</script>