<template>

    <Head title="Pagos" />
    <AuthenticatedLayout :redirectRoute="'management.employees'">
        <template #header>
            Pagos
        </template>

        <div class="min-w-full rounded-lg shadow">
            <div class="mt-6 flex items-center justify-between gap-x-6">
                <button @click="add_information" type="button"
                    class="rounded-md bg-indigo-600 px-4 py-2 text-center text-sm text-white hover:bg-indigo-500">
                    + Agregar
                </button>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full whitespace-no-wrap">
                    <thead>
                        <tr
                            class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Codigo de Pago
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Monto
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Descripcion
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Acciones
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="payment in payments.data" :key="payment.id" class="text-gray-700">
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ payment.cod_payment }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ payment.amount }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ payment.descrition }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <div class="flex space-x-3 justify-center">
                                    <button type="button" @click="pay_payment(payment)"
                                        class="text-blue-900 whitespace-no-wrap">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="flex flex-col items-center border-t bg-white px-5 py-5 xs:flex-row xs:justify-between">
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
                            <p class="text-sm text-gray-700 font-medium">Monto:</p>
                            <p class="text-lg text-gray-900">{{ payment_obj.amount }}</p>
                        </div>
                        <div class="mb-4">
                            <p class="text-sm text-gray-700 font-medium">Descripcion:</p>
                            <p class="text-lg text-gray-900">{{ payment_obj.descrition }}</p>
                        </div>
                        <div class="mt-2">
                            <InputLabel for="operation_number" class="font-medium leading-6 text-gray-900">Numero de
                                Operacion:
                            </InputLabel>
                            <div class="mt-2">
                                <TextInput type="date" id="operation_number" v-model="form.operation_number"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.operation_number" />
                            </div>
                        </div>
                        <div class="mt-6">
                            <InputLabel for="date" class="font-medium leading-6 text-gray-900">Dias Tomados:
                            </InputLabel>
                            <div class="mt-2">
                                <TextInput type="date" id="date" v-model="form.date"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.date" />
                            </div>
                        </div>
                        <div class="mt-6">
                            <InputLabel for="pay_image" class="font-medium leading-6 text-gray-900">Imagen de Pago:
                            </InputLabel>
                            <div class="mt-2">
                                <InputFile type="pay_image" id="file" v-model="form.payment_doc"
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
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import Pagination from '@/Components/Pagination.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import Modal from '@/Components/Modal.vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import InputError from '@/Components/InputError.vue';
import InputFile from '@/Components/InputFile.vue';

const showModal = ref(false);
const showModalPay = ref(false);
const purchase_quote_id = ref('');
const payment_obj = ref('');

const props = defineProps({
    payments: Object,
})

const form = useForm({
    operation_number: '',
    date: '',
    payment_doc: null,
})

const pay_payment = (payment) => {
    payment_obj = payment
    purchase_quote_id.value = payment.id
    showModalPay.value = true
}

const submit = () => {
    form.put(route('payment.pay', { id: purchase_quote_id }), form, {
        onSuccess: () => {
            showModal.value = true
            setTimeout(() => {
                showModal.value = false;
                router.visit(route('payment.index'))
            }, 2000);
        }
    })
}

const closePayModal = () => {
    showModalPay.value = false
}

</script>