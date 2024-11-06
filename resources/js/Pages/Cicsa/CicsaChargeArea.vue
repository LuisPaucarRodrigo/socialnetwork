<template>

    <Head title="CICSA Área de Cobranza" />
    <AuthenticatedLayout :redirectRoute="'cicsa.index'">
        <template #header>
            Área de Cobranza
        </template>
        <div class="min-w-full rounded-lg shadow">
            <div class="flex justify-between">
                <a :href="route('cicsa.charge_areas.export') + '?' + uniqueParam"
                    class="rounded-md bg-green-600 px-4 py-2 text-center text-sm text-white hover:bg-green-500">Exportar</a>
                <div class="flex items-center mt-4 space-x-3 sm:mt-0">
                    <TextInput data-tooltip-target="search_fields" type="text" @input="search($event.target.value)"
                        placeholder="Buscar ..." />
                    <SelectCicsaComponent currentSelect="Cobranza" />
                    <div id="search_fields" role="tooltip"
                        class="absolute z-10 invisible inline-block px-2 py-2 text-xs font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                        Nombre,Codigo,CPE,OC,Numero de Factura
                        <div class="tooltip-arrow" data-popper-arrow></div>
                    </div>
                </div>
            </div>
            <br>
            <div class="overflow-x-auto h-[70vh]">
                <table class="w-full ">
                    <thead>
                        <tr
                            class="sticky top-0 z-20 border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                            <th colspan="4"
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Nombre de Proyecto
                            </th>
                            <th colspan="4"
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Codigo de Proyecto
                            </th>
                            <th colspan="4"
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Centro de Costos
                            </th>
                            <th colspan="4"
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                CPE
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <template v-for="item in charge_areas.data ?? charge_areas" :key="item.id">

                            <tr class="text-gray-700">
                                <td colspan="4" class="border-b border-gray-200 bg-white px-5 py-3 text-[13px]">
                                    <p class="text-gray-900 text-center">
                                        {{ item.project_name }}
                                    </p>
                                </td>
                                <td colspan="4" class="border-b border-gray-200 bg-white px-5 py-3 text-[13px]">
                                    <p class="text-gray-900 text-center">
                                        {{ item.project_code }}
                                    </p>
                                </td>
                                <td colspan="4" class="border-b border-gray-200 bg-white px-5 py-3 text-[13px]">
                                    <p class="text-gray-900 text-center">
                                        {{ item.cost_center }}
                                    </p>
                                </td>
                                <td colspan="4" class="border-b border-gray-200 bg-white px-5 py-3 text-[13px]">
                                    <p class="text-gray-900 text-center">
                                        {{ item.cpe }}
                                    </p>
                                </td>
                                <td class="border-b border-gray-200 bg-white px-5 py-3 text-[13px]">
                                    <div class="flex space-x-3 justify-center">
                                        <button v-if="item.cicsa_charge_area.length > 0" type="button"
                                            @click="toggleDetails(item?.cicsa_charge_area)"
                                            class="text-blue-900 whitespace-no-wrap">
                                            <svg v-if="charge_area_row !== item.id" xmlns="http://www.w3.org/2000/svg"
                                                fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                class="w-6 h-6">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                            </svg>
                                            <svg v-else xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                class="w-6 h-6">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="m4.5 15.75 7.5-7.5 7.5 7.5" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <template v-if="charge_area_row == item.id">
                                <tr
                                    class="border-b bg-red-500 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                    <th
                                        class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                        Numero de OC
                                    </th>
                                    <th
                                        class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                        Número de Factura
                                    </th>
                                    <th
                                        class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                        Fecha de Factura
                                    </th>
                                    <th
                                        class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                        Documento
                                    </th>
                                    <th
                                        class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                        Crédito a
                                    </th>
                                    <th
                                        class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                        Fecha de Pago
                                    </th>
                                    <th
                                        class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                        Días Atrasados
                                    </th>
                                    <th
                                        class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                        Estado
                                    </th>
                                    <th
                                        class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                        Monto con IGV
                                    </th>
                                    <th
                                        class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                        Fecha de Abono de Cuenta Corriente
                                    </th>
                                    <th
                                        class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                        Numero de Transacción de Cuenta Corriente
                                    </th>
                                    <th
                                        class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                        Monto de Cuenta Corriente
                                    </th>
                                    <th
                                        class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                        Fecha de Abono de la detraccion
                                    </th>
                                    <th
                                        class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                        Numero de Transacción de la detraccion
                                    </th>
                                    <th
                                        class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                        Monto de la detraccion
                                    </th>
                                    <th
                                        class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                        Encargado
                                    </th>
                                    <th
                                        class="border-b-2 border-gray-200 bg-gray-100 px-5 py-2 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                        Acciones
                                    </th>
                                </tr>
                                <tr v-for="materialDetail in item.cicsa_charge_area" :key="materialDetail.id"
                                    class="bg-gray-100">

                                    <td class="border-b border-gray-200 bg-white px-5 py-3 text-[13px]">
                                        <p class="text-gray-900 text-center">
                                            {{ materialDetail.cicsa_purchase_order?.oc_number }}
                                        </p>
                                    </td>
                                    <td class="border-b border-gray-200 bg-white px-5 py-3 text-[13px]">
                                        <p class="text-gray-900 text-center">
                                            {{ materialDetail?.invoice_number }}
                                        </p>
                                    </td>
                                    <td class="border-b border-gray-200 bg-white px-5 py-3 text-[13px]">
                                        <p class="text-gray-900 text-center">
                                            {{ formattedDate(materialDetail?.invoice_date) }}
                                        </p>
                                    </td>
                                    <td class="border-b text-center border-gray-200 bg-white px-5 py-3 text-[13px]">
                                        <button v-if="materialDetail?.document" type="button"
                                            @click="openPDF(materialDetail?.id)">
                                            <EyeIcon class="w-5 h-5 text-green-600" />
                                        </button>
                                    </td>
                                    <td class="border-b border-gray-200 bg-white px-5 py-3 text-[13px]">
                                        <p class="text-gray-900 text-center">
                                            {{ materialDetail?.credit_to ? materialDetail?.credit_to + 'día(s)'
                                                : '' }}
                                        </p>
                                    </td>
                                    <td class="border-b border-gray-200 bg-white px-5 py-3 text-[13px]">
                                        <p class="text-gray-900 text-center">
                                            {{ formattedDate(materialDetail?.payment_date) }}
                                        </p>
                                    </td>
                                    <td class="border-b border-gray-200 bg-white px-5 py-3 text-[13px]">
                                        <p class="text-gray-900 text-center">
                                            {{ materialDetail?.invoice_date && materialDetail?.credit_to
                                                ?
                                                materialDetail.days_late + ' día(s)' : '' }}
                                        </p>
                                    </td>
                                    <td class="border-b border-gray-200 bg-white px-5 py-3 text-[13px]">
                                        <p class="text-gray-900 text-center">
                                            {{ materialDetail?.invoice_date && materialDetail?.credit_to
                                                ?
                                                materialDetail?.state : '' }}
                                        </p>
                                    </td>
                                    <td
                                        class="border-b border-gray-200 bg-white px-5 py-3 text-[13px] whitespace-nowrap">
                                        <p class="text-gray-900 text-center">
                                            {{ materialDetail?.amount ? 'S/. ' +
                                                materialDetail?.amount.toFixed(2) : ''
                                            }}
                                        </p>
                                    </td>
                                    <td class="border-b border-gray-200 bg-white px-5 py-3 text-[13px]">
                                        <p class="text-gray-900 text-center">
                                            {{ formattedDate(materialDetail?.deposit_date) }}
                                        </p>
                                    </td>
                                    <td class="border-b border-gray-200 bg-white px-5 py-3 text-[13px]">
                                        <p class="text-gray-900 text-center">
                                            {{ materialDetail?.transaction_number_current }}
                                        </p>
                                    </td>
                                    <td
                                        class="whitespace-nowrap border-b border-gray-200 bg-white px-5 py-3 text-[13px]">
                                        <p class="text-gray-900 text-center">
                                            {{ materialDetail?.checking_account_amount ? 'S/. ' +
                                                materialDetail?.checking_account_amount.toFixed(2) : ''
                                            }}
                                        </p>
                                    </td>
                                    <td class="border-b border-gray-200 bg-white px-5 py-3 text-[13px]">
                                        <p class="text-gray-900 text-center">
                                            {{ formattedDate(materialDetail?.deposit_date_bank) }}
                                        </p>
                                    </td>
                                    <td class="border-b border-gray-200 bg-white px-5 py-3 text-[13px]">
                                        <p class="text-gray-900 text-center">
                                            {{ materialDetail?.transaction_number_bank }}
                                        </p>
                                    </td>
                                    <td
                                        class="whitespace-nowrap border-b border-gray-200 bg-white px-5 py-3 text-[13px]">
                                        <p class="text-gray-900 text-center">
                                            {{ materialDetail?.amount_bank ? 'S/. ' +
                                                materialDetail?.amount_bank.toFixed(2) : ''
                                            }}
                                        </p>
                                    </td>
                                    <td class="border-b border-gray-200 bg-white px-5 py-3 text-[13px]">
                                        <p class="text-gray-900 text-center">
                                            {{ materialDetail?.user_name }}
                                        </p>
                                    </td>
                                    <td class="border-b border-gray-200 bg-white px-5 py-3 text-[13px] text-center">
                                        <button class="text-blue-900" @click="openEditModal(materialDetail)">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-amber-400">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                            </template>
                        </template>
                    </tbody>
                </table>
            </div>

            <div v-if="charge_areas.data"
                class="flex flex-col items-center border-t bg-white px-5 py-5 xs:flex-row xs:justify-between">
                <pagination :links="charge_areas.links" />
            </div>
        </div>

        <Modal :show="showAddEditModal" @close="closeAddAssignationModal">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-800 border-b-2 border-gray-100">
                    {{ form.id ? 'Editar Cobranza' : 'Nueva Cobranza' }} {{ invoice_number ? ": " + invoice_number : ""
                    }}
                </h2>
                <br>
                <form @submit.prevent="submit">
                    <div class="grid grid-cols-1 gap-x-8 gap-y-4 sm:grid-cols-2">
                        <div class="sm:col-span-1">
                            <InputLabel for="invoice_number">Número de Factura</InputLabel>
                            <div class="mt-2">
                                <TextInput type="text" v-model="form.invoice_number" autocomplete="off"
                                    id="invoice_number" />
                                <InputError :message="form.errors.invoice_number" />
                            </div>
                        </div>

                        <div class="sm:col-span-1">
                            <InputLabel for="invoice_date">Fecha de Factura</InputLabel>
                            <div class="mt-2">
                                <TextInput type="date" v-model="form.invoice_date" autocomplete="off"
                                    id="invoice_date" />
                                <InputError :message="form.errors.invoice_date" />
                            </div>
                        </div>

                        <div class="sm:col-span-1">
                            <InputLabel for="credit_to">Crédito a</InputLabel>
                            <div class="mt-2">
                                <TextInput type="number" v-model="form.credit_to" autocomplete="off"
                                    id="invoice_date" />
                                <InputError :message="form.errors.credit_to" />
                            </div>
                        </div>

                        <div class="sm:col-span-1">
                            <InputLabel for="payment_date">Fecha de Pago</InputLabel>
                            <div class="mt-2">
                                <TextInput disabled readonly type="date" v-model="form.payment_date" autocomplete="off"
                                    id="payment_date" />
                                <InputError :message="form.errors.payment_date" />
                            </div>
                        </div>

                        <div class="sm:col-span-1">
                            <InputLabel for="document">Documento</InputLabel>
                            <div>
                                <InputFile type="file" v-model="form.document" id="document" accept=".pdf" />
                                <InputError :message="form.errors.document" />
                            </div>
                        </div>

                        <div class="sm:col-span-1">
                            <InputLabel for="amount">Monto Total de Factura</InputLabel>
                            <div class="mt-2">
                                <input type="number" v-model="form.amount" id="amount" autocomplete="off" step="0.01"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.amount" />
                            </div>
                        </div>
                        <h2 class="sm:col-span-full text-lg font-medium text-gray-800 border-b-2 border-gray-100">
                            Pagos
                        </h2>
                        <div class="sm:col-span-1 sm:col-start-1">
                            <InputLabel for="deposit_date">Fecha de Abono a cuenta corriente</InputLabel>
                            <div class="mt-2">
                                <TextInput type="date" v-model="form.deposit_date" autocomplete="off"
                                    id="deposit_date" />
                                <InputError :message="form.errors.deposit_date" />
                            </div>
                        </div>

                        <div class="sm:col-span-1">
                            <InputLabel for="transaction_number_current">Número de Transacción de cuenta corriente
                            </InputLabel>
                            <div class="mt-2">
                                <TextInput type="text" v-model="form.transaction_number_current" autocomplete="off"
                                    id="transaction_number_current" />
                                <InputError :message="form.errors.transaction_number_current" />
                            </div>
                        </div>

                        <div class="sm:col-span-1">
                            <InputLabel for="checking_account_amount">Monto de cuenta corriente</InputLabel>
                            <div class="mt-2">
                                <input type="number" v-model="form.checking_account_amount" id="checking_account_amount"
                                    autocomplete="off" min="0" :max="form.checking_account_amount" step="0.01"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.checking_account_amount" />
                            </div>
                        </div>

                        <div class="sm:col-span-1 sm:col-start-1">
                            <InputLabel for="deposit_date_bank">Fecha de Abono a cuenta de la detraccion</InputLabel>
                            <div class="mt-2">
                                <TextInput type="date" v-model="form.deposit_date_bank" autocomplete="off"
                                    id="deposit_date_bank" />
                                <InputError :message="form.errors.deposit_date_bank" />
                            </div>
                        </div>

                        <div class="sm:col-span-1">
                            <InputLabel for="transaction_number_bank">Número de Transacción de la detraccion
                            </InputLabel>
                            <div class="mt-2">
                                <TextInput type="text" v-model="form.transaction_number_bank" autocomplete="off"
                                    id="transaction_number_bank" />
                                <InputError :message="form.errors.transaction_number_bank" />
                            </div>
                        </div>

                        <div class="sm:col-span-1">
                            <InputLabel for="amount_bank">Monto de la detraccion</InputLabel>
                            <div class="mt-2">
                                <input type="number" v-model="form.amount_bank" id="amount_bank" autocomplete="off"
                                    min="0" :max="form.amount_bank" step="0.01"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.amount_bank" />
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <SecondaryButton type="button" @click="closeAddAssignationModal"> Cancelar </SecondaryButton>
                        <PrimaryButton class="ml-3 tracking-widest uppercase text-xs"
                            :class="{ 'opacity-25': form.processing }" :disabled="form.processing" type="submit">
                            Guardar
                        </PrimaryButton>
                    </div>
                </form>
            </div>
        </Modal>
        <SuccessOperationModal :confirming="confirmUpdateAssignation" :title="'Cobranza Actualizada'"
            :message="'La Cobranza fue actualizada'" />
        <ErrorOperationModal :showError="errorAmount" title="Monto total"
            message="La suma de ambas cantidades no es valida" />
    </AuthenticatedLayout>
</template>


<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SelectCicsaComponent from '@/Components/SelectCicsaComponent.vue';
import SuccessOperationModal from '@/Components/SuccessOperationModal.vue';
import ErrorOperationModal from '@/Components/ErrorOperationModal.vue';
import { formattedDate, setAxiosErrors, toFormData } from '@/utils/utils.js';
import TextInput from '@/Components/TextInput.vue';
import { EyeIcon } from '@heroicons/vue/24/outline';
import InputFile from '@/Components/InputFile.vue';


const { charge_area, auth } = defineProps({
    charge_area: Object,
    auth: Object
})

const uniqueParam = ref(`timestamp=${new Date().getTime()}`);
const charge_areas = ref(charge_area)
const invoice_number = ref(null)
const errorAmount = ref(false)
const charge_area_row = ref(0)

const initialState = {
    id: null,
    user_id: '',
    invoice_number: '',
    invoice_date: '',
    credit_to: '',
    payment_date: '',
    document: '',
    amount: '',
    deposit_date: '',
    transaction_number_current: '',
    checking_account_amount: null,
    deposit_date_bank: '',
    transaction_number_bank: '',
    amount_bank: null,
    user_name: '',
    cicsa_assignation_id: '',
    cicsa_purchase_order_id: '',
}

const form = useForm(
    { ...initialState }
);


const showAddEditModal = ref(false);

function closeAddAssignationModal() {
    showAddEditModal.value = false
    form.defaults({ ...initialState })
    form.reset()
}

const confirmUpdateAssignation = ref(false);

function openEditModal(item) {
    invoice_number.value = item?.invoice_number
    form.defaults({ ...item, user_name: auth.user.name, user_id: auth.user.id })
    form.reset()
    showAddEditModal.value = true
}

async function submit() {
    if (sum()) {
        let url = route('cicsa.charge_areas.update', { cicsa_charge_area_id: form.id });
        try {
            let formData = toFormData(form)
            const response = await axios.post(url, formData)
            updateChargeArea(response.data)
            closeAddAssignationModal()
            confirmUpdateAssignation.value = true
            setTimeout(() => {
                confirmUpdateAssignation.value = false
            }, 1500)
        } catch (error) {
            if (error.response) {
                if (error.response.data.errors) {
                    setAxiosErrors(error.response.data.errors, form)
                } else {
                    console.error("Server error:", error.response.data)
                }
            } else {
                console.error("Network or other error:", error)
            }
        }
    } else {
        errorAmount.value = !errorAmount.value
        setTimeout(() => {
            errorAmount.value = !errorAmount.value
        }, 1500)
    }
}

function sum() {
    if (form.checking_account_amount && form.amount_bank) {
        const checking_account_amount = parseFloat(form.checking_account_amount) || 0;
        const amount_bank = parseFloat(form.amount_bank) || 0;
        if ((checking_account_amount + amount_bank) !== parseFloat(form.amount)) {
            return false
        }
        return true
    }
    return true
}

watch(() => form.credit_to, (newCreditTo) => {
    // Convertir el crédito a un número entero
    const creditDays = parseInt(newCreditTo) + 1;

    // Verificar si invoice_date tiene una fecha válida
    if (form.invoice_date && creditDays) {
        // Calcular la fecha de pago sumando los días de crédito a invoice_date
        const invoiceDate = new Date(form.invoice_date); // Convertir invoice_date a objeto Date
        invoiceDate.setDate(invoiceDate.getDate() + creditDays); // Sumar los días de crédito

        // Formatear la fecha de pago
        form.payment_date = formattedDate2(invoiceDate);
    } else {
        form.payment_date = ''; // Reiniciar payment_date si falta información
    }
});

function formattedDate2(dateString) {
    if (!dateString) return '';

    const date = new Date(dateString);
    const year = date.getFullYear();
    let month = (1 + date.getMonth()).toString().padStart(2, '0');
    let day = date.getDate().toString().padStart(2, '0');

    return `${year}-${month}-${day}`;
}

const search = async ($search) => {
    try {
        const response = await axios.post(route('cicsa.charge_areas'), { searchQuery: $search });
        charge_areas.value = response.data.charge_area;
    } catch (error) {
        console.error('Error searching:', error);
    }
};

const toggleDetails = (cicsa_charge_area) => {
    if (charge_area_row.value === cicsa_charge_area[0].cicsa_assignation_id) {
        charge_area_row.value = 0;
    } else {
        charge_area_row.value = cicsa_charge_area[0].cicsa_assignation_id;
    }
}

async function openPDF(chargeAreaId) {
    if (chargeAreaId) {
        const url = route('cicsa.charge_areas.showDocument', { chargeAreaOrder: chargeAreaId });
        await axios.get(url)
            .then(response => {
                const imageUrl = response.data.url;
                window.open(imageUrl, '_blank');
            })
            .catch(error => {
                console.error('Error fetching image URL:', error);
            });
    } else {
        console.error('No se proporcionó un ID de imagen válido');
    }
}

function updateChargeArea(chargeArea) {
    const validations = charge_areas.value.data || charge_areas.value;
    const index = validations.findIndex(item => item.id === chargeArea.cicsa_assignation_id)
    const indexChargeArea = validations[index].cicsa_charge_area.findIndex(item => item.id === chargeArea.id)
    validations[index].cicsa_charge_area[indexChargeArea] = chargeArea
}
</script>
