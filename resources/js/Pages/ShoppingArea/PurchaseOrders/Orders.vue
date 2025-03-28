<template>

    <Head title="Ordenes de Compra" />
    <AuthenticatedLayout :redirectRoute="'purchaseorders.index'">
        <template #header>
            Ordenes
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
                    <tr v-for="order in (props.search ? orders : orders.data)" :key="order.id" :class="[
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
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-green-600">
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
            <div v-if="props.search === undefined"
                class="flex flex-col items-center border-t bg-white px-5 py-5 xs:flex-row xs:justify-between">
                <pagination :links="orders.links" />
            </div>
        </div>
        <Modal :show="showModal">
            <div class="p-6">
                <form @submit.prevent="submit">
                    <div class="space-y-12">
                        <div class="border-b border-gray-900/10 pb-12">
                            <h2 class="text-base font-medium leading-7 text-gray-900">
                                Factura
                            </h2>
                            <div>
                                <InputLabel for="serie_number">
                                    Numero de Serie
                                </InputLabel>
                                <div class="mt-2">
                                    <TextInput type="number" v-model="form.serie_number" id="serie_number"
                                        maxlength="8" />
                                    <InputError :message="form.errors.serie_number" />
                                </div>
                            </div>
                            <div>
                                <InputLabel for="facture_number">
                                    Numero de Factura
                                </InputLabel>
                                <div class="mt-2">
                                    <TextInput type="number" v-model="form.facture_number" id="facture_number" />
                                    <InputError :message="form.errors.facture_number" />
                                </div>
                            </div>

                            <div>
                                <InputLabel for="facture_date">
                                    Fecha de Factura
                                </InputLabel>
                                <div class="mt-2">
                                    <TextInput type="date" v-model="form.facture_date" id="facture_date" />
                                    <InputError :message="form.errors.facture_date" />
                                </div>
                            </div>

                            <div>
                                <InputLabel for="facture_doc">
                                    Documento de Factura
                                </InputLabel>
                                <div class="mt-2">
                                    <InputFile type="file" v-model="form.facture_doc" id="facture_doc" />
                                    <InputError :message="form.errors.facture_doc" />
                                </div>
                            </div>

                            <div>
                                <InputLabel for="others">
                                    Otros
                                </InputLabel>
                                <div class="mt-2">
                                    <TextInput type="text" v-model="form.others" id="others" />
                                    <InputError :message="form.errors.others" />
                                </div>
                            </div>
                        </div>

                        <div class="border-b border-gray-900/10 pb-12">
                            <h2 class="text-base font-medium leading-7 text-gray-900">
                                Guia de Remision
                            </h2>
                            <div>
                                <InputLabel for="remission_guide_number">
                                    Numero de Guia de Remision
                                </InputLabel>
                                <div class="mt-2">
                                    <TextInput type="text" v-model="form.remission_guide_number"
                                        id="remission_guide_number" />
                                    <InputError :message="form.errors.remission_guide_number" />
                                </div>
                            </div>

                            <div>
                                <InputLabel for="remission_guide_date">
                                    Fecha de Guia de Remission
                                </InputLabel>
                                <div class="mt-2">
                                    <TextInput type="date" v-model="form.remission_guide_date"
                                        id="remission_guide_date" />
                                    <InputError :message="form.errors.remission_guide_date" />
                                </div>
                            </div>

                            <div>
                                <InputLabel for="remission_guide_doc">
                                    Documento de Guia de Remision
                                </InputLabel>
                                <div class="mt-2">
                                    <InputFile type="file" v-model="form.remission_guide_doc"
                                        id="remission_guide_doc" />
                                    <InputError :message="form.errors.remission_guide_doc" />
                                </div>
                            </div>
                        </div>
                        <div class="mt-6 flex items-center justify-end gap-x-3">
                            <SecondaryButton @click="closeModal()"> Cancelar </SecondaryButton>
                            <PrimaryButton type="submit" :class="{ 'opacity-25': form.processing }">
                                Guardar
                            </PrimaryButton>
                        </div>
                    </div>
                </form>
            </div>
        </Modal>

        <Modal :show="showCotization">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">
                    Información de la Cotización
                </h2>
                <div class="mt-4">
                    <p class="text-sm text-gray-600">
                        <span class="font-medium">Codigo de Cotizacion:</span> {{ cotization.code }}
                    </p>
                    <p class="text-sm text-gray-600">
                        <span class="font-medium">Monto:</span> S/ {{ cotization.amount.toFixed(2) }}
                    </p>
                    <p class="text-sm text-gray-600">
                        <span class="font-medium">Proveedor:</span> {{ cotization.provider
                        }}
                    </p>
                    <p class="text-sm text-gray-600"><span class="font-medium">Fecha de solicitud de compra:</span>
                        {{ formattedDate(cotization.quote_deadline) }}</p>
                    <p class="text-sm text-gray-600"><span class="font-medium">Estado de Cotizacion:</span>
                        {{ cotization.response }}
                    </p>
                    <div class="flex items-center">
                        <p class="text-sm text-gray-600">
                            <span class="font-medium">Ver Documento de Cotización:</span>
                        </p>
                        <button @click="openPreviewDocumentQuote(cotization.purchase_quote_id)"
                            class="text-green-600 hover:underline ml-2">
                            <EyeIcon class="h-4 w-4" />
                        </button>
                    </div>

                </div>
                <div class="mt-6 flex justify-end">
                    <PrimaryButton @click="closeCotizationModal">Cerrar</PrimaryButton>
                </div>
            </div>
        </Modal>
        <ErrorOperationModal :showError="errorAmount" :title="title" :message="message" />
        <SuccessOperationModal :confirming="showModalSuccess" :title="title_order" :message="message_order" />
        <ConfirmateModal :showConfirm="showModalUpdateSuccess" tittle="Confirmar"
            text="El estado de la orden se cambiara" :actionFunction="updateStateOrder"
            @closeModal="closeUpdateModal" />

    </AuthenticatedLayout>
</template>

<script setup>
import SuccessOperationModal from '@/Components/SuccessOperationModal.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import Pagination from '@/Components/Pagination.vue';
import Modal from '@/Components/Modal.vue';
import { ref } from 'vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import { formattedDate } from '@/utils/utils';
import { EyeIcon } from '@heroicons/vue/24/outline';
import InputFile from '@/Components/InputFile.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import ErrorOperationModal from '@/Components/ErrorOperationModal.vue';
import TextInput from '@/Components/TextInput.vue';
import ConfirmateModal from '@/Components/ConfirmateModal.vue';
import TableTitle from '@/Components/TableTitle.vue';
import TableRow from '@/Components/TableRow.vue';
import TableStructure from '@/Layouts/TableStructure.vue';

const showModal = ref(false);
const showCotization = ref(false);
const id = ref(null);
const state = ref(null);
const quoteToOpen = ref(null);
const showModalSuccess = ref(false);
const showModalUpdateSuccess = ref(false);
const errorAmount = ref(false);
const title = ref('');
const message = ref('');
const title_order = ref('');
const message_order = ref('');
const data = ref(null);

const props = defineProps({
    orders: Object,
    search: String,
    userPermissions: Array
})

const hasPermission = (permission) => {
    return props.userPermissions.includes(permission);
}

const form = useForm({
    id: '',
    state: '',
    serie_number: '',
    facture_number: '',
    facture_date: '',
    facture_doc: null,
    others: '',
    remission_guide_number: '',
    remission_guide_date: '',
    remission_guide_doc: null,
})

const cotization = {
    code: '',
    amount: null,
    provider: '',
    quote_deadline: '',
    response: '',
    purchase_quote_id: '',
};

const openCotization = (order) => {
    quoteToOpen.value = JSON.parse(JSON.stringify(order.purchase_quote));
    cotization.code = quoteToOpen.value.code;
    cotization.amount = quoteToOpen.value.total_amount;
    cotization.provider = quoteToOpen.value.provider.company_name;
    cotization.quote_deadline = quoteToOpen.value.quote_deadline;
    cotization.response = quoteToOpen.value.state ? "Aceptado" : "Rechazado";
    cotization.purchase_quote_id = quoteToOpen.value.id;
    showCotization.value = true;
};

function openPreviewDocumentQuote(documentId) {
    const routeToShow = route('purchasesrequest.show', { id: documentId });
    window.open(routeToShow, '_blank');
}

const closeCotizationModal = () => {
    showCotization.value = false;
}

const updateState = async (stateid, newState, is_payments_completed) => {
    id.value = stateid;
    state.value = newState;
    data.value = { state: state.value, id: id.value };

    if (state.value === "Completada") {
        if (is_payments_completed) {
            showModal.value = true;
        } else {
            title.value = "Pagos Incompletos"
            message.value = "Complete todos los pagos previos a la fecha de llegada de la compra."
            errorAmount.value = true
            setTimeout(() => {
                errorAmount.value = false
                router.visit(route('purchaseorders.index'));
            }, 1500);
        }

    } else if (state.value === "Pendiente" || state.value === "OC Enviada") {
        showModalUpdateSuccess.value = true
    }
}

const submit = () => {
    form.id = id
    form.state = state
    form.post(route('purchaseorders.state'), {
        onSuccess: () => {
            showCotization.value = false
            title_order.value = "Orden Completada"
            message_order.value = "Orden completada correctamente"
            showModalSuccess.value = true
            setTimeout(() => {
                showModalSuccess.value = false
                router.visit(route('purchaseorders.index'))
            }, 2000);

        }
    })
}

const closeModal = () => {
    form.reset
    showModal.value = false
}

const searchForm = useForm({
    searchTerm: props.search ? props.search : '',
})

const search = () => {
    if (searchForm.searchTerm == '') {
        router.visit(route('purchaseorders.index'));
    } else {
        router.visit(route('purchaseorders.search', { request: searchForm.searchTerm, history: 'no' }));
    }
}

const availableOptions = (state) => {
    if (state === 'Pendiente' || props.userPermissions.includes('UserManager')) {
        return ['Pendiente', 'OC Enviada', 'Completada'];
    } else if (state === 'OC Enviada') {
        return ['OC Enviada', 'Completada'];
    } else {
        return ['Completada'];
    }
};

function updateStateOrder() {
    showModalUpdateSuccess.value = false
    axios.post(route('purchaseorders.state'), data.value)
        .then(() => {
            title_order.value = state.value === "OC Enviada" ? "OC Enviada" : "Pendiente";
            message_order.value = state.value === "OC Enviada" ? "Orden enviada correctamente" : "Orden cambiada a Pendiente";
            showModalSuccess.value = true;
            setTimeout(() => {
                showModalSuccess.value = false;
                router.visit(route('purchaseorders.index'));
            }, 2000);
        })
        .catch(error => {
            console.error("Hubo un error al enviar la orden:", error);
        });
}

function closeUpdateModal() {
    showModalUpdateSuccess.value = false
}
</script>
