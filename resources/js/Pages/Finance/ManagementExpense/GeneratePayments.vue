<template>

    <Head title="Registro de Pagos" />
    <AuthenticatedLayout :redirectRoute="'managementexpense.index'">
        <template #header>
            Registro de Pagos
        </template>
        <div class="min-w-full rounded-lg shadow">
            <div class="mt-6 flex items-center justify-between gap-x-6">
                <button @click="add_payment" type="button"
                    class="rounded-md bg-indigo-600 px-4 py-2 text-center text-sm text-white hover:bg-indigo-500">
                    Agregar Pago
                </button>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full whitespace-no-wrap">
                    <thead>
                        <tr
                            class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Monto
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Descripcion
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="payment in paymentsArray" :key="payment.id" class="text-gray-700">
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">

                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ payment.amount }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ payment.description }}</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mt-6 flex items-center justify-end gap-x-6">
            <button @click="submit()" type="button"
                class="rounded-md bg-green-600 px-4 py-2 text-center text-sm text-white hover:bg-green-500">
                Guardar
            </button>
        </div>
        <Modal :show="showModalPayment">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">
                    Agregar un Registro de Pago
                </h2>
                <div class="border-b border-gray-900/10 pb-12">
                    <div>
                        <InputLabel for="amount" class="font-medium leading-6 text-gray-900">Monto
                        </InputLabel>
                        <div class="mt-2">
                            <input required type="number" v-model="amount" id="amount"
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                        </div>
                    </div>
                    <div>
                        <InputLabel for="description" class="font-medium leading-6 text-gray-900">Descripcion
                        </InputLabel>
                        <div class="mt-2">
                            <textarea v-model="description" id="description"
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                        </div>
                    </div>
                </div>
                <div class="mt-6 flex gap-3 justify-end">
                    <SecondaryButton type="button" @click="closeModal"> Cerrar </SecondaryButton>
                    <PrimaryButton type="button" @click="addItem"> Agregar </PrimaryButton>
                </div>
            </div>


        </Modal>
        <ErrorOperationModal :showError="errorAmount" title="Monto Excedido" message="No es una cantidad válida" />
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Modal from '@/Components/Modal.vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import ErrorOperationModal from '@/Components/ErrorOperationModal.vue';

const props = defineProps({
    quote: Object
})

const showModalPayment = ref(false);
const amount = ref('');
const description = ref('');
const paymentsArray = ref([]);
const form = useForm({
    state: true,
    payments: '',
})
const total_amount = ref(0);
const errorAmount = ref(false);

function add_payment() {
    showModalPayment.value = true
}

function submit() {
    form.payments = paymentsArray.value.map(payment => ({
        amount: payment.amount,
        description: payment.description
    }))
    form.put(route('managementexpense.reviewed', { id: props.quote.id }), form)
}

function addItem() {
    if (amount.value !== '' && description.value !== '') {
        const newAmount = parseFloat(amount.value);
        const currentTotal = total_amount.value + newAmount;
        const quoteTotal = parseFloat(props.quote.total_amount);

        if (currentTotal <= quoteTotal) {
            total_amount.value += newAmount;

            paymentsArray.value.push({
                amount: newAmount,
                description: description.value
            });

            amount.value = '';
            description.value = '';
        } else {
            errorAmount.value = true
            setTimeout(() => {
                errorAmount.value = false
            }, 1500)
        }
    }
}


function closeModal() {
    showModalPayment.value = false
}
</script>