<template>

    <Head title="Area de Cobranzas" />
    <AuthenticatedLayout :redirectRoute="'socialnetwork.sot'">
        <template #header>
            Area de Cobranza
        </template>

        <div class="min-w-full rounded-lg shadow">
            <div class="mt-6 sm:flex sm:gap-4 sm:justify-end">
                <div class="mt-2">
                    <SelectSNSotComponent currentSelect="Área de Cobranza" />
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full whitespace-no-wrap">
                    <thead>
                        <tr
                            class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Cod SOT
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                SOT a Facturar
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Fecha
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Factura
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Fecha
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Cobranza
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Fecha
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="payment in payments.data" :key="payment.id" class="text-gray-700">
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ payment.name }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ payment.sot_payment?.sot_bill }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ payment.sot_payment ?
        payment.sot_payment.sot_bill_date :
        '' }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ payment.sot_payment?.bill }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ payment.sot_payment ?
        payment.sot_payment.bill_date : '' }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ payment.sot_payment?.charge }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ payment.sot_payment ?
        payment.sot_payment.charge_date : '' }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <div class="flex space-x-3 justify-center">
                                    <button @click="actionShowStore(payment.sot_payment ?? payment.id)" type="button"
                                        class="text-blue-900 whitespace-no-wrap">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
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
        <Modal :show="showStoreControl">
            <div class="p-6">
                <h2 class="text-base font-medium leading-7 text-gray-900">
                    Area de Control
                </h2>
                <form @submit.prevent="submit">
                    <div class="border-b border-gray-900/10 pb-12">
                        <div class="mt-2">
                            <InputLabel for="sot_bill">SOT a Facturar
                            </InputLabel>
                            <div class="mt-2">
                                <select id="sot_bill" v-model="form.sot_bill" :disabled="form.sot_bill"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option disabled value="">Seleccionar Estado</option>
                                    <option>OK</option>
                                    <option>Penalidad</option>
                                </select>
                                <InputError :message="form.errors.sot_bill" />
                            </div>
                        </div>
                        <div class="mt-2">
                            <InputLabel for="sot_bill_date">Fecha SOT a Facturar
                            </InputLabel>
                            <div class="mt-2">
                                <TextInput type="Date" v-model="form.sot_bill_date" id="sot_bill_date" :disabled="form.sot_bill_date" />
                                <InputError :message="form.errors.sot_bill_date" />
                            </div>
                        </div>
                        <div class="mt-2">
                            <InputLabel for="bill">Factura
                            </InputLabel>
                            <div class="mt-2">
                                <select id="bill" v-model="form.bill" :disabled="form.bill"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option disabled value="">Seleccionar Estado</option>
                                    <option>OK</option>
                                    <option>Penalidad</option>
                                </select>
                                <InputError :message="form.errors.bill" />
                            </div>
                        </div>
                        <div class="mt-2">
                            <InputLabel for="bill_date">Fecha Facturar
                            </InputLabel>
                            <div class="mt-2">
                                <TextInput type="Date" v-model="form.bill_date" id="bill_date" :disabled="form.bill_date" />
                                <InputError :message="form.errors.bill_date" />
                            </div>
                        </div>
                        <div class="mt-2">
                            <InputLabel for="charge">Cobranza
                            </InputLabel>
                            <div class="mt-2">
                                <select id="charge" v-model="form.charge" :disabled="form.charge"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option disabled value="">Seleccionar Estado</option>
                                    <option>OK</option>
                                    <option>Penalidad</option>
                                </select>
                                <InputError :message="form.errors.charge" />
                            </div>
                        </div>
                        <div class="mt-2">
                            <InputLabel for="charge_date">Fecha Cobranza
                            </InputLabel>
                            <div class="mt-2">
                                <TextInput type="Date" v-model="form.charge_date" id="charge_date" :disabled="form.charge_date" />
                                <InputError :message="form.errors.charge_date" />
                            </div>
                        </div>
                    </div>
                    <div class="mt-6 flex items-center justify-end gap-x-6">
                        <SecondaryButton @click="closeShowStorePayment"> Cancel </SecondaryButton>
                        <PrimaryButton type="submit" :class="{ 'opacity-25': form.processing }">
                            Guardar
                        </PrimaryButton>
                    </div>
                </form>
            </div>
        </Modal>
        <SuccessOperationModal :confirming="sotPaymentUpdate" title="Registrado" />
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
import { formattedDate } from '@/utils/utils';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SelectSNSotComponent from '@/Components/SelectSNSotComponent.vue';
import SuccessOperationModal from '@/Components/SuccessOperationModal.vue';

const showStoreControl = ref(false);
const sotPaymentUpdate = ref(false);

const props = defineProps({
    payments: Object,
})

const form = useForm({
    s_n_sot_id: null,
    sot_bill: null,
    sot_bill_date: null,
    bill: null,
    bill_date: null,
    charge: null,
    charge_date: null,
})

function submit() {
    form.put(route('sn.paymentArea.update', { sot_id: form.s_n_sot_id }), {
        onSuccess: () => {
            sotPaymentUpdate.value = true
            setTimeout(() => {
                sotPaymentUpdate.value = false
                router.visit(route('sn.paymentArea.index'));
            }, 2000)
        }
    })
}

function initializeForm(payment) {
    form.s_n_sot_id = payment.s_n_sot_id;
    form.sot_bill = payment.sot_bill;
    form.sot_bill_date = payment.sot_bill_date;
    form.bill = payment.bill;
    form.bill_date = payment.bill_date;
    form.charge = payment.charge;
    form.charge_date = payment.charge_date;
}

function actionShowStore(payment) {
    if (typeof payment === "object"){
        console.log('dddd')
        initializeForm(payment)
    } else {
        console.log('ssss')
        form.s_n_sot_id = payment
    }
    showStoreControl.value = !showStoreControl.value;
}

function closeShowStorePayment() {
    form.reset()
    showStoreControl.value = !showStoreControl.value
}


</script>