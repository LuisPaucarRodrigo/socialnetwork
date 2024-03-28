<template>

    <Head title="Pagos" />
    <AuthenticatedLayout :redirectRoute="'payment.index'">
        <template #header>
            Pagos
        </template>
        <div class="min-w-full rounded-lg shadow">

            <div class="flex justify-end items-center gap-4 mb-2">
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

            <div class="overflow-x-auto">
                <table class="min-w-full whitespace-no-wrap">
                    <thead>
                        <tr
                            class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Codigo de Solicitud
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Titulo de Solicitud
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Codigo de Cotizacion
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-7 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Tipo de Pago
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Monto Total
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <template v-for="payment in (props.search ? payments : payments.data)" :key="payment.id">
                            <tr class="text-gray-700">
                                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                    <p class="text-gray-900 whitespace-no-wrap">{{ payment.purchasing_requests.code }}
                                    </p>
                                </td>
                                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                    <p class="text-gray-900 whitespace-no-wrap">{{ payment.purchasing_requests.title }}
                                    </p>
                                </td>
                                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                    <p class="text-gray-900 whitespace-no-wrap">{{ payment.payment_type }}</p>
                                </td>
                                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                    <p class="text-gray-900 whitespace-no-wrap">{{ payment.currency == 'sol' ? "S/" :
        "$" }} {{ payment.total_amount.toFixed(2) }}</p>
                                </td>
                                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                    <p :class="`${payment.payments_completed ? 'text-green-500' : 'text-red-500'}`">{{
        payment.payments_completed ? "Completo" : "Pendientes" }}</p>
                                </td>
                                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                    <button type="button" @click="toggleDetails(payment.payment)"
                                        class="text-blue-900 whitespace-no-wrap">
                                        <svg v-if="paymentRow !== payment.id" xmlns="http://www.w3.org/2000/svg"
                                            fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                            class="w-6 h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                        </svg>
                                        <svg v-else xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="m4.5 15.75 7.5-7.5 7.5 7.5" />
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                            <template v-if="paymentRow == payment.id">
                                <tr v-for="paymentDetail in payment.payment" :key="paymentDetail.id"
                                    class="bg-gray-100">
                                    <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm" colspan="2">
                                    </td>
                                    <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                        <p class="text-gray-900 whitespace-no-wrap">
                                            {{ paymentDetail.cod_payment }}
                                        </p>
                                    </td>
                                    <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                        <p class="text-gray-900 whitespace-no-wrap">
                                            {{ payment.currency == 'sol' ? "S/" : "$" }} {{
        paymentDetail.amount.toFixed(2) }}
                                        </p>
                                    </td>
                                    <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                        <p class="text-gray-900 whitespace-no-wrap">
                                            {{ paymentDetail.description }}
                                        </p>
                                    </td>
                                    <td class="border-b border-gray-200 bg-white px-2 py-5 text-sm text-center">
                                        <div>
                                            <button v-if="paymentDetail.state" type="button"
                                                @click="pay_payment(paymentDetail, payment.currency)"
                                                class="text-green-500 whitespace-no-wrap">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                    stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                                </svg>
                                            </button>
                                            <p v-else class="text-green-500 whitespace-no-wrap">Pagado</p>
                                        </div>
                                    </td>
                                </tr>
                            </template>
                        </template>
                    </tbody>
                </table>
            </div>
            <div v-if="props.search === undefined"
                class="flex flex-col items-center border-t bg-white px-5 py-5 xs:flex-row xs:justify-between">
                <pagination :links="payments.links" />
            </div>
        </div>
        <Modal :show="showModalPay">
            <div class="p-6">
                <h2 class="text-base font-medium leading-7 text-gray-900">
                    Pago de cotizacion
                </h2>
                <form @submit.prevent="submit">
                    <div class="border-b border-gray-900/10 pb-12">
                        <div class="mb-4">
                            <p class="text-sm text-gray-700 font-medium">Codigo:</p>
                            <p class="text-lg text-gray-900">{{ payment_obj.cod_payment }}</p>
                        </div>
                        <div class="mb-4">
                            <p class="text-sm text-gray-700 font-medium">Monto:</p>
                            <p class="text-lg text-gray-900">{{ currency_type == 'sol' ? "S/" : "$" }} {{
        payment_obj.amount.toFixed(2) }}</p>
                        </div>
                        <div class="mb-4">
                            <p class="text-sm text-gray-700 font-medium">Tipo de Pago:</p>
                            <p class="text-lg text-gray-900">{{ payment_obj.description }}</p>
                        </div>
                        <div v-if="currency_type == 'dolar'" class="mt-2">
                            <InputLabel for="price_dolar" class="font-medium leading-6 text-gray-900">Precio del dolar:
                            </InputLabel>
                            <div class="mt-2">
                                <TextInput type="text" id="price_dolar" v-model="form.price_dolar"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.price_dolar" />
                            </div>
                        </div>
                        <div class="mt-2">
                            <InputLabel for="operation_number" class="font-medium leading-6 text-gray-900">Numero de
                                Operacion:
                            </InputLabel>
                            <div class="mt-2">
                                <TextInput type="text" id="operation_number" v-model="form.operation_number"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.operation_number" />
                            </div>
                        </div>
                        <div class="mt-6">
                            <InputLabel for="date" class="font-medium leading-6 text-gray-900">Fecha de Pago:
                            </InputLabel>
                            <div class="mt-2">
                                <TextInput type="date" id="date" v-model="form.date"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.date" />
                            </div>
                        </div>
                        <div class="mt-6">
                            <InputLabel for="payment_doc" class="font-medium leading-6 text-gray-900">Imagen de Pago:
                            </InputLabel>
                            <div class="mt-2">
                                <InputFile type="file" id="payment_doc" v-model="form.payment_doc"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.payment_doc" />
                            </div>
                        </div>
                        <div class="mt-6 flex items-center justify-end gap-x-6">
                            <SecondaryButton @click="closePayModal"> Cancel </SecondaryButton>
                            <button type="submit" :class="{ 'opacity-25': form.processing }"
                                class="rounded-md bg-indigo-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                                Guardar
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </Modal>
        <SuccessOperationModal :confirming="showModalSuccess" title="Pago realizado"
            message="El pago se registro correctamente" />
    </AuthenticatedLayout>
</template>

<script setup>
import SuccessOperationModal from '@/Components/SuccessOperationModal.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import InputError from '@/Components/InputError.vue';
import Pagination from '@/Components/Pagination.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputFile from '@/Components/InputFile.vue';
import TextInput from '@/Components/TextInput.vue';
import Modal from '@/Components/Modal.vue';
import { ref } from 'vue';
import { Head, router, useForm } from '@inertiajs/vue3';

const props = defineProps({
    payments: Object,
    search: String
})

const showModalSuccess = ref(false);
const showModalPay = ref(false);
const payment_id = ref('');
const payment_obj = ref('');
const paymentRow = ref(0);
const currency_type = ref('');

const form = useForm({
    operation_number: '',
    date: '',
    payment_doc: null,
    state: false,
    payment_id: '',
    price_dolar: ''
})

const pay_payment = (payment, currency) => {
    currency_type.value = currency
    payment_obj.value = payment
    payment_id.value = payment.id
    showModalPay.value = true
}

const submit = () => {
    form.payment_id = payment_id.value;
    form.state = 1;
    form.post(route('payment.pay'), {
        onSuccess: () => {
            showModalSuccess.value = true
            setTimeout(() => {
                showModalSuccess.value = false;
                router.visit(route('payment.index'))
            }, 2000);
        }
    })
}

const closePayModal = () => {
    form.reset();
    showModalPay.value = false
}

const toggleDetails = (payment) => {
    if (paymentRow.value === payment[0].purchase_quote_id) {
        paymentRow.value = 0;
    } else {
        paymentRow.value = payment[0].purchase_quote_id;
    }
}

const searchForm = useForm({
    searchTerm: props.search ? props.search : '',
})

const search = () => {
    if (searchForm.searchTerm == '') {
        router.visit(route('payment.index'));
    } else {
        router.visit(route('payment.search', { request: searchForm.searchTerm }));
    }
}

</script>