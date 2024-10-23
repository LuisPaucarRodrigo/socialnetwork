<template>

    <Head title="Registro de Pagos" />
    <AuthenticatedLayout :redirectRoute="'managementexpense.index'">
        <template #header>
            Registro de Pagos
        </template>

        <div class="border-b border-gray-900/10 pb-12 shadow-sm p-4 ring-1 ring-gray-200 rounded-lg">
            <div class="mt-6 border-t border-gray-100 sm:w-3/4 md:w-1/2 pb-5">
                <dl class="divide-y divide-gray-100">
                    <div class="py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                        <dt class="text-sm font-medium leading-6 text-gray-900">Forma de Pago: </dt>
                        <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                            {{ quote.payment_type }}
                        </dd>
                    </div>
                    <div class="py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                        <dt class="text-sm font-medium leading-6 text-gray-900">Tipo de Cambio: </dt>
                        <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                            {{ quote.currency }}
                        </dd>
                    </div>
                    <div class="py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                        <dt class="text-sm font-medium leading-6 text-gray-900">Monto: </dt>
                        <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                            {{ quote.currency == 'sol' ? "S/" : "$" }} {{ quote.total_amount.toFixed(2) }}</dd>
                    </div>
                </dl>
            </div>

            <div class="flex gap-2 items-center">
                <h2 class="text-base font-bold leading-6 text-gray-900 ">
                    Añadir pagos
                </h2>
                <button @click="add_payment" type="button">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="indigo" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 3.75H6.912a2.25 2.25 0 0 0-2.15 1.588L2.35 13.177a2.25 2.25 0 0 0-.1.661V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18v-4.162c0-.224-.034-.447-.1-.661L19.24 5.338a2.25 2.25 0 0 0-2.15-1.588H15M2.25 13.5h3.86a2.25 2.25 0 0 1 2.012 1.244l.256.512a2.25 2.25 0 0 0 2.013 1.244h3.218a2.25 2.25 0 0 0 2.013-1.244l.256-.512a2.25 2.25 0 0 1 2.013-1.244h3.859M12 3v8.25m0 0-3-3m3 3 3-3" />
                    </svg>
                </button>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full whitespace-no-wrap">
                    <thead>
                        <tr
                            class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                            <th v-if="quote.payment_type == 'Credito'"
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Fecha de Pago
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Descripcion
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Monto
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="payment in paymentsArray" :key="payment.id" class="text-gray-700">
                            <td v-if="quote.payment_type == 'Credito'"
                                class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ payment.register_date }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ payment.description }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">
                                    {{ quote.currency == 'sol' ? "S/" : "$" }}
                                    {{ (payment.amount).toFixed(2) }}
                                </p>
                            </td>
                        </tr>
                        <tr class="text-gray-700">
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-lg"
                                :colspan="quote.payment_type == 'Credito' ? 2 : 1">Totales:</td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ quote.currency == 'sol' ? "S/" : "$" }}
                                    {{
        (currentTotal).toFixed(2) }}</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="mt-6 flex items-center justify-end gap-x-6">
                <button @click="submit()" type="button"
                    class="rounded-md bg-green-600 px-4 py-2 text-center text-sm text-white hover:bg-green-500">
                    Guardar
                </button>
            </div>
        </div>

        <Modal :show="showModalPayment">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">
                    Agregar un Registro de Pago
                </h2>
                <div class="border-b border-gray-900/10 pb-12">
                    <div>
                        <div class="mt-2">
                            <InputLabel for="amount" class="font-medium leading-6 text-gray-900">Monto Total
                            </InputLabel>
                            <p class="text-lg text-gray-900">
                                {{ quote.currency == 'sol' ? "S/" : "$" }}
                                {{ (quote.total_amount).toFixed(2) }}
                            </p>
                        </div>
                    </div>
                    <div>
                        <InputLabel for="amount" class="font-medium leading-6 text-gray-900">Monto
                        </InputLabel>
                        <div class="mt-2">
                            <input required type="number" v-model="amount" id="amount"
                                :disabled="quote.payment_type == 'Contado'"
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                        </div>
                    </div>
                    <div v-if="quote.payment_type == 'Credito'" class="mt-2">
                        <InputLabel for="register_date" class="font-medium leading-6 text-gray-900">Fecha de Pago
                        </InputLabel>
                        <div class="mt-2">
                            <input required type="date" v-model="register_date" id="amount"
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"/>
                        </div>
                    </div>
                    <div class="mt-2">
                        <InputLabel for="description" class="font-medium leading-6 text-gray-900">Descripcion
                        </InputLabel>
                        <div class="mt-2">
                            <textarea required v-model="description" id="description"
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
        <ErrorOperationModal :showError="errorAmount" :title="title" :message="message" />
        <SuccessOperationModal :confirming="showModalSuccess" title="Pago registrados"
            message="Los pagos se registraron correctamente" />
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
import SuccessOperationModal from '@/Components/SuccessOperationModal.vue';

const props = defineProps({
    quote: Object
})

const showModalPayment = ref(false);
const showModalSuccess = ref(false);
const amount = ref(props.quote.payment_type == 'Contado' ? props.quote.total_amount.toFixed(2) : '');
const description = ref('');
const register_date = ref('');
const paymentsArray = ref([]);
const form = useForm({
    state: true,
    payments: '',
})
const total_amount = ref(0);
const errorAmount = ref(false);
const currentTotal = ref(0);
const title = ref('');
const message = ref('');

function add_payment() {
    showModalPayment.value = true
}

function submit() {
    if (currentTotal.value == props.quote.total_amount.toFixed(2)) {
        form.payments = paymentsArray.value.map(payment => ({
            amount: payment.amount,
            description: payment.description,
            register_date: payment.register_date,
        }))
        form.put(route('managementexpense.reviewed', { id: props.quote.id }), {
            onSuccess: () => {
                showModalSuccess.value = true
                setTimeout(() => {
                    showModalSuccess.value = false;
                    router.visit(route('managementexpense.index'))
                }, 2000);
            }
        })
    } else {
        title.value = "Monto Menor"
        message.value = "La suma de pagos debe ser igual al monto total"
        errorAmount.value = true
        setTimeout(() => {
            errorAmount.value = false
        }, 2000)
    }

}

function addItem() {
    if (amount.value !== '' && description.value !== '') {
        const newAmount = parseFloat(amount.value);
        const total_array = total_amount.value + newAmount;
        const quoteTotal = props.quote.total_amount.toFixed(2);

        if (total_array <= quoteTotal) {
            if (props.quote.payment_type === "Contado" && paymentsArray.value.length >= 1) {
                title.value = "Pago Contado"
                message.value = "Ya se ha agregado un pago para esta cotización"
                errorAmount.value = true
                setTimeout(() => {
                    errorAmount.value = false
                }, 2000)
            } else {
                currentTotal.value = total_amount.value + newAmount;
                total_amount.value += newAmount;
                paymentsArray.value.push({
                    amount: newAmount,
                    description: description.value,
                    register_date: register_date.value
                });
                amount.value = '';
                description.value = '';
                register_date.value = '';
            }
        } else {
            title.value = "Monto Excedido"
            message.value = "No es una cantidad válida"
            errorAmount.value = true
            setTimeout(() => {
                errorAmount.value = false
            }, 2000)
        }
    }
}


function closeModal() {
    showModalPayment.value = false
}
</script>