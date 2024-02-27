<template>
    <Head title="Ordenes de Compra" />
    <AuthenticatedLayout :redirectRoute="'purchaseorders.index'">
        <template #header>
            Ordenes
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
                                Descripcion
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Fecha de aprobacion de finanza
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Cotización
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Estado
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="order in orders.data" :key="order.id" class="text-gray-700">
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{
                                    order.purchase_quote.purchasing_requests.project?.name }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ order.purchase_quote.response }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ formattedDate(order.date_issue) }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">
                                <div class="flex items-center">
                                    <button @click="openCotization(order)" class="text-green-600 hover:underline">
                                        <EyeIcon class="h-4 w-4" />
                                    </button>
                                </div>
                            </td>

                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <select id="selectState" @change="updateState(order.id, $event.target.value)"
                                    :disabled="order.state == 'Completada'">
                                    <option selected disabled>{{ order.state }}</option>
                                    <option>Pendiente</option>
                                    <option>En Proceso</option>
                                    <option>Completada</option>
                                </select>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="flex flex-col items-center border-t bg-white px-5 py-5 xs:flex-row xs:justify-between">
                <pagination :links="orders.links" />
            </div>
        </div>
        <Modal :show="showModal">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">
                    ¿Estás seguro de completar la orden de compra?
                </h2>
                <p class="mt-1 text-sm text-gray-600">
                    La orden de compra sera completada. Esta accion
                    se podra revertir mas adelante.
                </p>
                <div class="mt-6 flex justify-end">
                    <SecondaryButton @click="closeModal">Cancelar</SecondaryButton>
                    <DangerButton class="ml-3" @click="confirmUpdate">Confirmar</DangerButton>
                </div>
            </div>
        </Modal>

        <Modal :show="showCotization">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">
                    Información de la Cotización
                </h2>
                <div class="mt-4">
                    <p class="text-sm text-gray-600"><span class="font-medium">Monto:</span> {{ cotization.amount }}</p>
                    <p class="text-sm text-gray-600"><span class="font-medium">Proveedor:</span> {{ cotization.provider }}</p>
                    <p class="text-sm text-gray-600"><span class="font-medium">Fecha de caducidad de la cotización:</span> {{ cotization.quote_deadline }}</p>
                    <p class="text-sm text-gray-600"><span class="font-medium">Respuesta:</span> {{ cotization.response }}</p>
                    <div class="flex items-center">
                        <p class="text-sm text-gray-600">
                            <span class="font-medium">Ver Documento de Cotización:</span>
                        </p>
                        <button @click="openPreviewDocumentQuote(cotization.purchase_quote_id)" class="text-green-600 hover:underline ml-2">
                            <EyeIcon class="h-4 w-4" />
                        </button>
                    </div>

                </div>
                <div class="mt-6 flex justify-end">
                    <PrimaryButton @click="closeCotizationModal">Cerrar</PrimaryButton>
                </div>
            </div>
        </Modal>

    </AuthenticatedLayout>
</template>
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
import Pagination from '@/Components/Pagination.vue';
import Modal from '@/Components/Modal.vue';
import { ref } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import { formattedDate } from '@/utils/utils';
import { EyeIcon } from '@heroicons/vue/24/outline';

const showModal = ref(false);
const showCotization = ref(false);
const id = ref(null);
const state = ref(null);
const quoteToOpen = ref(null);

const props = defineProps({
    orders: Object
})

const cotization = {
    amount: null,
    provider: '',
    quote_deadline: '',
    response: '',
    purchase_quote_id: '',
};

const openCotization = (order) => {
  // Copia de los datos de la subsección existente al formulario
  quoteToOpen.value = JSON.parse(JSON.stringify(order.purchase_quote));
  cotization.amount = quoteToOpen.value.amount;
  cotization.provider = quoteToOpen.value.provider;
  cotization.quote_deadline = quoteToOpen.value.quote_deadline;
  cotization.response = quoteToOpen.value.response;
  cotization.purchase_quote_id = quoteToOpen.value.id;
  showCotization.value = true;
};

function openPreviewDocumentQuote(documentId) {
  const routeToShow = route('purchasesrequest.show', { id: documentId });
  window.open(routeToShow, '_blank');
}

const closeCotizationModal = () =>{
    showCotization.value = false;
}

const updateState = async (stateid, newState) => {
    id.value = stateid;
    state.value = newState;
    const data = { state: state.value };

    if (state.value === "Completada") {
        showModal.value = true;
    } else {
        await sendStateUpdate(data);
    }
}

const sendStateUpdate = async (data) => {
    router.put(route('purchaseorders.state', { id: id.value }), data, {
        onSuccess: () => {
            setTimeout(() => {
                showModal.value = false;
                router.visit(route('purchaseorders.index'))
            }, 2000);
        }
    })
}

const closeModal = () => {
    showModal.value = false
}

const confirmUpdate = async () => {
    await sendStateUpdate({ state: "Completada" });
    showModal.value = false;
}
</script>
