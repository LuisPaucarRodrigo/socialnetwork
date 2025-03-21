<template>

    <Head title="Solicitudes" />
    <AuthenticatedLayout :redirectRoute="'purchasesrequest.index'">
        <template #header>
            Solicitudes de compra
        </template>
        <div class="min-w-full overflow-hidden">
            <div class="flex justify-between items-center gap-4">
                <PrimaryButton v-if="hasPermission('PurchasingManager')" @click="add_request" type="button">
                    + Agregar
                </PrimaryButton>
                <div class="flex items-center">
                    <form @submit.prevent="search" class="flex items-center">
                        <TextInput type="text" placeholder="Buscar..." v-model="searchForm.searchTerm" />
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
                    <tr v-for="purchase in (props.search ? purchases : purchases.data)" :key="purchase.id"
                        class="text-gray-700" :class="[
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
                                <EyeIcon class="w-5 h-5 text-green-400" />
                                </Link>
                                <div v-if="auth.user.role_id == 1">
                                    <Link v-if="purchase.project == null && purchase.state != 'Aceptado'"
                                        :href="route('purchasesrequest.edit', { id: purchase.id })">
                                    <PencilSquareIcon class="w-5 h-5 text-yellow-400" />
                                    </Link>
                                    <span v-else>
                                        <PencilSquareIcon class="w-5 h-5 text-gray-400" />
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
                                        <TrashIcon class="w-5 h-5 text-red-500" />
                                    </button>
                                    <span v-else>
                                        <TrashIcon class="w-5 h-5 text-gray-500" />
                                    </span>
                                </div>
                            </div>
                        </TableRow>
                    </tr>
                </template>
            </TableStructure>

            <div v-if="props.search === undefined"
                class="flex flex-col items-center border-t bg-white px-5 py-5 xs:flex-row xs:justify-between">
                <pagination :links="purchases.links" />
            </div>
        </div>
        <Modal :show="confirmingPurchasesDeletion">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">
                    Estas seguro de eliminar la solicitud?
                </h2>

                <p class="mt-1 text-sm text-gray-600">
                    Se eliminara toda la informacion relacionada con la solicitud.
                </p>

                <div class="mt-6 flex justify-end">
                    <SecondaryButton @click="closeModal"> Cancel </SecondaryButton>
                    <DangerButton class="ml-3" @click="deletePurchase()">Eliminar</DangerButton>
                </div>
            </div>
        </Modal>
        <ErrorOperationModal :showError="error" :title="'Fecha de solicitud de compra'"
            :message="'Debe ingresar la fecha de solicitud en proyectos'" />
    </AuthenticatedLayout>
</template>
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import DangerButton from '@/Components/DangerButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import Modal from '@/Components/Modal.vue';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import { formattedDate } from '@/utils/utils';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import ErrorOperationModal from '@/Components/ErrorOperationModal.vue';
import TableStructure from '@/Layouts/TableStructure.vue';
import TableTitle from '@/Components/TableTitle.vue';
import TableRow from '@/Components/TableRow.vue';
import { ArrowDownTrayIcon, ArrowUpTrayIcon, EyeIcon, PencilSquareIcon, TrashIcon } from '@heroicons/vue/24/outline';

const confirmingPurchasesDeletion = ref(false);
const purchaseToDelete = ref(null);
const error = ref(false);

const props = defineProps({
    purchases: Object,
    auth: Object,
    search: String,
    userPermissions: Array
});

const hasPermission = (permission) => {
    return props.userPermissions.includes(permission);
}

const confirmPurchasesDeletion = (purchaseId) => {
    purchaseToDelete.value = purchaseId;
    confirmingPurchasesDeletion.value = true;
};

const deletePurchase = () => {
    const purchaseId = purchaseToDelete.value;
    if (purchaseId) {
        router.delete(route('purchasesrequest.destroy', { id: purchaseId }), {
            onSuccess: () => closeModal()
        });
    }
};

const closeModal = () => {
    confirmingPurchasesDeletion.value = false;
};

const add_request = () => {
    router.get(route('purchasesrequest.create'))
};

const searchForm = useForm({
    searchTerm: props.search ? props.search : '',
})

const search = () => {
    if (searchForm.searchTerm == '') {
        router.visit(route('purchasesrequest.index'));
    } else {
        router.visit(route('purchasesrequest.search', { request: searchForm.searchTerm }));
    }

}

function confirmPurchase(purchase) {
    purchase.due_date === null && purchase.project ? errorPurchase() : purchase.state_quote == false && purchase.project_id != null ? router.get(route('purchasesrequest.quote_deadline.complete', { id: purchase.project_id })) : router.get(route('purchasesrequest.quotes.create', { id: purchase.id }))
}

function errorPurchase() {
    error.value = true
    setTimeout(() => {
        error.value = false
    }, 2000)
}
</script>
