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
                                Codigo de Orden
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Fecha de solicitud de compra
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Fecha de llegada de la compra
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Exportar Orden de Compra
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
                        <tr v-for="order in orders.data" :key="order.id">

                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">
                                    {{ order.purchase_quote.purchasing_requests.project?.name }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">
                                    {{ order.code }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">
                                    {{ formattedDate(order.purchase_quote.purchasing_requests.due_date) }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ order.purchase_arrival_date }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <div class="flex items-center">
                                    <a :href="route('purchaseorders.export.order', { id: order.id })"
                                        class="text-green-600 hover:underline">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                        </svg>
                                    </a>
                                </div>
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
                <form @submit.prevent="submit">
                    <div class="space-y-12">
                        <div class="border-b border-gray-900/10 pb-12">
                            <h2 class="text-base font-medium leading-7 text-gray-900">
                                Factura
                            </h2>
                            <div>
                                <InputLabel for="facture_number" class="mt-2 font-medium leading-6 text-gray-900">Numero
                                    de
                                    Factura
                                </InputLabel>
                                <div class="mt-2">
                                    <input type="text" v-model="form.facture_number" id="facture_number"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                    <InputError :message="form.errors.facture_number" />
                                </div>
                            </div>

                            <div>
                                <InputLabel for="facture_date" class="mt-4 font-medium leading-6 text-gray-900">Fecha de
                                    Factura
                                </InputLabel>
                                <div class="mt-2">
                                    <input type="date" v-model="form.facture_date" id="facture_date"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                    <InputError :message="form.errors.facture_date" />
                                </div>
                            </div>

                            <div>
                                <InputLabel for="facture_doc" class="mt-4 font-medium leading-6 text-gray-900">Documento
                                    de
                                    Factura
                                </InputLabel>
                                <div class="mt-2">
                                    <InputFile type="file" v-model="form.facture_doc" id="facture_doc"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                    <InputError :message="form.errors.facture_doc" />
                                </div>
                            </div>
                        </div>

                        <div class="border-b border-gray-900/10 pb-12">
                            <h2 class="text-base font-medium leading-7 text-gray-900">
                                Guia de Remision
                            </h2>
                            <div>
                                <InputLabel for="remission_guide_number"
                                    class="mt-2 font-medium leading-6 text-gray-900">Numero
                                    de Guia de Remision
                                </InputLabel>
                                <div class="mt-2">
                                    <input type="text" v-model="form.remission_guide_number" id="remission_guide_number"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                    <InputError :message="form.errors.remission_guide_number" />
                                </div>
                            </div>

                            <div>
                                <InputLabel for="remission_guide_date" class="mt-4 font-medium leading-6 text-gray-900">
                                    Fecha de
                                    Guia de Remission
                                </InputLabel>
                                <div class="mt-2">
                                    <input type="date" v-model="form.remission_guide_date" id="remission_guide_date"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                    <InputError :message="form.errors.remission_guide_date" />
                                </div>
                            </div>

                            <div>
                                <InputLabel for="remission_guide_doc" class="mt-4 font-medium leading-6 text-gray-900">
                                    Documento
                                    de
                                    Guia de Remision
                                </InputLabel>
                                <div class="mt-2">
                                    <InputFile type="file" v-model="form.remission_guide_doc" id="remission_guide_doc"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                    <InputError :message="form.errors.remission_guide_doc" />
                                </div>
                            </div>
                        </div>
                        <div class="mt-6 flex items-center justify-end gap-x-6">
                            <SecondaryButton @click="closeModal()"> Cancelar </SecondaryButton>
                            <button type="submit" :class="{ 'opacity-25': form.processing }"
                                class="rounded-md bg-indigo-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                                Guardar
                            </button>
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
                    <p class="text-sm text-gray-600"><span class="font-medium">Monto:</span> {{ cotization.amount }}</p>
                    <p class="text-sm text-gray-600"><span class="font-medium">Proveedor:</span> {{ cotization.provider
                        }}
                    </p>
                    <p class="text-sm text-gray-600"><span class="font-medium">Fecha de caducidad de la
                            cotización:</span>
                        {{ cotization.quote_deadline }}</p>
                    <p class="text-sm text-gray-600"><span class="font-medium">Respuesta:</span> {{ cotization.response
                        }}
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

    </AuthenticatedLayout>
</template>

<script setup>
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

const showModal = ref(false);
const showCotization = ref(false);
const id = ref(null);
const state = ref(null);
const quoteToOpen = ref(null);

const props = defineProps({
    orders: Object
})

const form = useForm({
    id: '',
    state: '',
    facture_number: '',
    facture_date: '',
    facture_doc: null,
    remission_guide_number: '',
    remission_guide_date: '',
    remission_guide_doc: null,
})

const cotization = {
    amount: null,
    provider: '',
    quote_deadline: '',
    response: '',
    purchase_quote_id: '',
};

const openCotization = (order) => {
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

const closeCotizationModal = () => {
    showCotization.value = false;
}

const updateState = async (stateid, newState) => {
    id.value = stateid;
    state.value = newState;
    const data = { state: state.value, id: id.value };

    if (state.value === "Completada") {
        showModal.value = true;
    } else {
        router.post(route('purchaseorders.state'), data, {
            onSuccess: () => {
                router.visit(route('purchaseorders.index'))
            }
        })
    }
}

const submit = () => {
    form.id = id
    form.state = state
    form.post(route('purchaseorders.state'), {
        onSuccess: () => {
            showCotization.value = false;
            router.visit(route('purchaseorders.index'))
        }
    })
}

const closeModal = () => {
    showModal.value = false
}

function setBorderColor(date) {
    const fechaString = date;
    const [year, month, day] = fechaString.split('-');
    const fecha = new Date(year, month - 1, day);
    const hoy = new Date()
    hoy.setUTCHours(hoy.getUTCHours() - 5)
    hoy.setHours(0, 0, 0, 0);
    const diferenciaEnMilisegundos = fecha - hoy;
    const diferenciaEnDias = Math.floor(diferenciaEnMilisegundos / (1000 * 60 * 60 * 24));
    if (diferenciaEnDias <= 3) {
        return 'red'
    } else if (4 <= diferenciaEnDias <= 7) {
        return 'yellow'
    } else {
        return ''
    }
}

</script>
