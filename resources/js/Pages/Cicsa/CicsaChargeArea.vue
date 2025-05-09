<template>

    <Head title="CICSA Área de Cobranza" />
    <AuthenticatedLayout :redirectRoute="{ route: 'cicsa.index', params: { type } }">
        <template #header>
            {{ type == 1 ? 'Pint' : 'Pext' }} - Área de Cobranza
        </template>
        <div class="min-w-full ">
            <div class="flex justify-between">
                <a :href="route('cicsa.charge_areas.export', { cost_line_id: type }) + '?' + uniqueParam"
                    class="rounded-md bg-green-600 px-4 py-2 text-center text-sm text-white hover:bg-green-500">Exportar</a>
                <div class="flex items-center mt-4 space-x-3 sm:mt-0">
                    <TextInput data-tooltip-target="search_fields" type="text" @input="search($event.target.value)"
                        placeholder="Buscar ..." />
                    <SelectCicsaComponent currentSelect="Cobranza" :type="type" />
                    <div id="search_fields" role="tooltip"
                        class="absolute z-10 invisible inline-block px-2 py-2 text-xs font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                        Nombre,Codigo,CPE,OC,Numero de Factura
                        <div class="tooltip-arrow" data-popper-arrow></div>
                    </div>
                </div>
            </div>
            <br>
            <TableStructure>
                <template #thead>
                    <tr>
                        <TableTitle :colspan="4">Nombre de Proyecto</TableTitle>
                        <TableTitle :colspan="4">Codigo de Proyecto</TableTitle>
                        <TableTitle :colspan="4">Centro de Costos</TableTitle>
                        <TableTitle :colspan="4">CPE</TableTitle>
                        <TableTitle></TableTitle>
                    </tr>
                </template>
                <template #tbody>
                    <template v-for="item in charge_areas.data ?? charge_areas" :key="item.id">
                        <tr>
                            <TableRow :colspan="4">{{ item.project_name }}</TableRow>
                            <TableRow :colspan="4">{{ item.project_code }}</TableRow>
                            <TableRow :colspan="4">{{ item.project?.cost_center?.name }}</TableRow>
                            <TableRow :colspan="4">{{ item.cpe }}</TableRow>
                            <TableRow>
                                <div class="flex space-x-3 justify-center">
                                    <button v-if="item.cicsa_charge_area.length > 0" type="button"
                                        @click="toggleDetails(item?.cicsa_charge_area)"
                                        class="text-blue-900 whitespace-no-wrap">
                                        <ChevronDownIcon v-if="charge_area_row !== item.id" class="w-6 h-6" />
                                        <ChevronUpIcon v-else class="w-6 h-6" />
                                    </button>
                                </div>
                            </TableRow>
                        </tr>
                        <template v-if="charge_area_row == item.id">
                            <tr
                                class="border-b bg-red-500 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                <TableTitle :style="'bg-gray-200'">Numero de OC</TableTitle>
                                <TableTitle :style="'bg-gray-200'">Número de Factura</TableTitle>
                                <TableTitle :style="'bg-gray-200'">Fecha de Factura</TableTitle>
                                <TableTitle :style="'bg-gray-200'">Crédito a</TableTitle>
                                <TableTitle :style="'bg-gray-200'">Fecha de Pago</TableTitle>
                                <TableTitle :style="'bg-gray-200'">Días Atrasados</TableTitle>
                                <TableTitle :style="'bg-gray-200'">Estado</TableTitle>
                                <TableTitle :style="'bg-gray-200'">Monto con IGVC</TableTitle>
                                <TableTitle :style="'bg-gray-200'">Fecha de Abono de Cuenta Corriente</TableTitle>
                                <TableTitle :style="'bg-gray-200'">Numero de Transacción de Cuenta Corriente
                                </TableTitle>
                                <TableTitle :style="'bg-gray-200'">Monto de Cuenta Corriente</TableTitle>
                                <TableTitle :style="'bg-gray-200'">Fecha de Abono de la detraccion</TableTitle>
                                <TableTitle :style="'bg-gray-200'">Numero de Transacción de la detraccion</TableTitle>
                                <TableTitle :style="'bg-gray-200'">Monto de la detraccion</TableTitle>
                                <TableTitle :style="'bg-gray-200'">Doc Detraccion</TableTitle>
                                <TableTitle :style="'bg-gray-200'">Encargado</TableTitle>
                                <TableTitle :style="'bg-gray-200'">Acciones</TableTitle>
                            </tr>
                            <tr v-for="materialDetail in item.cicsa_charge_area" :key="materialDetail.id"
                                class="bg-gray-100">
                                <TableRow>{{ materialDetail.cicsa_purchase_order?.oc_number }}</TableRow>
                                <TableRow>{{ materialDetail?.invoice_number }}</TableRow>
                                <TableRow>{{ formattedDate(materialDetail?.invoice_date) }}</TableRow>
                                <TableRow>
                                    {{
                                        materialDetail?.credit_to ? materialDetail?.credit_to + 'día(s)' : ''
                                    }}
                                </TableRow>
                                <TableRow>{{ formattedDate(materialDetail?.payment_date) }}</TableRow>
                                <TableRow>
                                    {{
                                        materialDetail?.invoice_date && materialDetail?.credit_to
                                            ?
                                            materialDetail.days_late + ' día(s)' : ''
                                    }}
                                </TableRow>
                                <TableRow>
                                    {{ materialDetail?.invoice_date && materialDetail?.credit_to
                                        ?
                                        materialDetail?.state : '' }}
                                </TableRow>
                                <TableRow>
                                    {{ materialDetail?.amount ? 'S/. ' +
                                        materialDetail?.amount.toFixed(2) : ''
                                    }}
                                </TableRow>
                                <TableRow>
                                    {{ formattedDate(materialDetail?.deposit_date) }}
                                </TableRow>
                                <TableRow>
                                    {{ materialDetail?.transaction_number_current }}
                                </TableRow>
                                <TableRow>
                                    {{ materialDetail?.checking_account_amount ? 'S/. ' +
                                        materialDetail?.checking_account_amount.toFixed(2) : ''
                                    }}
                                </TableRow>
                                <TableRow>
                                    {{ formattedDate(materialDetail?.deposit_date_bank) }}
                                </TableRow>

                                <TableRow>
                                    {{ materialDetail?.transaction_number_bank }}</TableRow>
                                <TableRow>
                                    {{ materialDetail?.amount_bank ? 'S/. ' +
                                        materialDetail?.amount_bank.toFixed(2) : ''
                                    }}</TableRow>
                                <TableRow>
                                    <button v-if="materialDetail?.document" type="button"
                                        @click="openPDF(materialDetail?.id)">
                                        <EyeIcon class="w-5 h-5 text-green-600" />
                                    </button>
                                </TableRow>
                                <TableRow>{{ materialDetail?.user_name }}</TableRow>
                                <TableRow>
                                    <button class="text-blue-900" @click="openEditModal(materialDetail)">
                                        <PencilSquareIcon class="w-5 h-5 text-amber-400" />
                                    </button>
                                </TableRow>
                            </tr>
                        </template>
                    </template>
                </template>
            </TableStructure>
            <div v-if="charge_areas.data"
                class="flex flex-col items-center border-t bg-white px-5 py-5 xs:flex-row xs:justify-between">
                <pagination :links="charge_areas.links" />
            </div>
        </div>

        <Modal :show="showAddEditModal" @close="closeAddAssignationModal" :maxWidth="showPdfPreview ? '6xl' : '2xl'">
            <div class="p-6 flex gap-6">
                <div :class="showPdfPreview ? 'w-1/2' : 'w-full'">
                    <h2 class="text-lg font-medium text-gray-800 border-b-2 border-gray-100">
                        {{ form.id ? 'Editar Cobranza' : 'Nueva Cobranza' }} {{ invoice_number ? ": " + invoice_number :
                            ""
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
                                    <TextInput disabled readonly type="date" v-model="form.payment_date"
                                        autocomplete="off" id="payment_date" />
                                    <InputError :message="form.errors.payment_date" />
                                </div>
                            </div>

                            <div class="sm:col-span-1">
                                <InputLabel for="amount">Monto Total de Factura</InputLabel>
                                <div class="mt-2">
                                    <input type="number" v-model="form.amount" id="amount" autocomplete="off"
                                        step="0.01"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                    <InputError :message="form.errors.amount" />
                                </div>
                            </div>
                            <div v-if="doc_invoice" class="sm:col-span-1">
                                <InputLabel for="doc_invoice">Doc Factura</InputLabel>
                                <div class="mt-2">
                                    <button class="flex justify-center py-1.5" type="button" @click="togglePdfPreview">
                                        <EyeIcon class="w-5 h-5 text-green-600" />
                                    </button>
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
                                    <input type="number" v-model="form.checking_account_amount"
                                        id="checking_account_amount" min="0" :max="form.checking_account_amount"
                                        step="0.01"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                    <InputError :message="form.errors.checking_account_amount" />
                                </div>
                            </div>

                            <div class="sm:col-span-1">
                                <InputLabel for="state_detraction">Tiene Detracción?</InputLabel>
                                <div class="mt-2 flex gap-4">
                                    <label class="flex gap-2 items-center">
                                        Sí
                                        <input type="radio" v-model="form.state_detraction" id="state_detraction"
                                            :value="true"
                                            class="block border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-gray-500 focus:ring-0" />
                                    </label>
                                    <label class="flex gap-2 items-center">
                                        No
                                        <input type="radio" v-model="form.state_detraction" id="state_detraction"
                                            :value="false"
                                            class="block border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-gray-500 focus:ring-0" />
                                    </label>
                                    <InputError :message="form.errors.state_detraction" />
                                </div>
                            </div>

                            <div v-if="form.state_detraction" class="sm:col-span-1 sm:col-start-1">
                                <InputLabel for="deposit_date_bank">Fecha de Abono a cuenta de la detraccion
                                </InputLabel>
                                <div class="mt-2">
                                    <TextInput type="date" v-model="form.deposit_date_bank" autocomplete="off"
                                        id="deposit_date_bank" />
                                    <InputError :message="form.errors.deposit_date_bank" />
                                </div>
                            </div>

                            <div v-if="form.state_detraction" class="sm:col-span-1">
                                <InputLabel for="transaction_number_bank">Número de Transacción de la detraccion
                                </InputLabel>
                                <div class="mt-2">
                                    <TextInput type="text" v-model="form.transaction_number_bank" autocomplete="off"
                                        id="transaction_number_bank" />
                                    <InputError :message="form.errors.transaction_number_bank" />
                                </div>
                            </div>

                            <div v-if="form.state_detraction" class="sm:col-span-1">
                                <InputLabel for="amount_bank">Monto de la detraccion</InputLabel>
                                <div class="mt-2">
                                    <input type="number" v-model="form.amount_bank" id="amount_bank" autocomplete="off"
                                        min="0" :max="form.amount_bank" step="0.01"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                    <InputError :message="form.errors.amount_bank" />
                                </div>
                            </div>
                            <div v-if="form.state_detraction" class="sm:col-span-1">
                                <InputLabel for="document">Documento de Detraccion</InputLabel>
                                <div>
                                    <InputFile type="file" v-model="form.document" id="document" accept=".pdf" />
                                    <InputError :message="form.errors.document" />
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end">
                            <SecondaryButton type="button" @click="closeAddAssignationModal"> Cancelar
                            </SecondaryButton>
                            <PrimaryButton class="ml-3 tracking-widest uppercase text-xs"
                                :class="{ 'opacity-25': form.processing }" :disabled="form.processing" type="submit">
                                Guardar
                            </PrimaryButton>
                        </div>
                    </form>
                </div>
                <div v-if="showPdfPreview" class="w-1/2 bg-gray-100 rounded-lg p-4 overflow-y-auto">
                    <iframe :src="pdfUrl" class="w-full h-full " style="min-height: auto; max-height: auto;"></iframe>
                </div>
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
import { ChevronDownIcon, ChevronUpIcon, EyeIcon, PencilSquareIcon } from '@heroicons/vue/24/outline';
import InputFile from '@/Components/InputFile.vue';
import TableStructure from '@/Layouts/TableStructure.vue';
import TableTitle from '@/Components/TableTitle.vue';
import TableRow from '@/Components/TableRow.vue';


const { charge_area, auth, searchCondition, type } = defineProps({
    charge_area: Object,
    auth: Object,
    searchCondition: {
        type: String,
        Required: false
    },
    type: Number
})

const uniqueParam = ref(`timestamp=${new Date().getTime()}`);
const charge_areas = ref(charge_area)
const invoice_number = ref(null)
const errorAmount = ref(false)
const charge_area_row = ref(0)
const showPdfPreview = ref(false)
const pdfUrl = ref(null)
const service_order_id = ref(null)
const doc_invoice = ref(null)

const initialState = {
    id: null,
    user_id: '',
    invoice_number: '',
    invoice_date: '',
    credit_to: '',
    payment_date: '',
    document: '',
    amount: null,
    deposit_date: '',
    transaction_number_current: '',
    checking_account_amount: null,
    deposit_date_bank: '',
    transaction_number_bank: '',
    amount_bank: null,
    user_name: '',
    cicsa_assignation_id: '',
    cicsa_purchase_order_id: '',
    state_detraction: true,
}

const form = useForm(
    { ...initialState }
);

const showAddEditModal = ref(false);

function closeAddAssignationModal() {
    doc_invoice.value = null
    service_order_id.value = null
    showPdfPreview.value = false
    showAddEditModal.value = false
    form.defaults({ ...initialState })
    form.reset()
}

const confirmUpdateAssignation = ref(false);

function openEditModal(item) {
    doc_invoice.value = item.cicsa_purchase_order?.cicsa_service_order?.document_invoice
    service_order_id.value = item.cicsa_purchase_order?.cicsa_service_order?.id
    invoice_number.value = item?.invoice_number
    item.state_detraction = Boolean(item.state_detraction)
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
        const checking_account_amount = form.checking_account_amount || 0;
        const amount_bank = form.amount_bank || 0;
        if (((checking_account_amount + amount_bank).toFixed(2)) !== form.amount.toFixed(2)) {
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
        const response = await axios.post(route('cicsa.charge_areas', { type }), { searchQuery: $search });
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

async function togglePdfPreview() {
    const url = route('cicsa.service_orders.showDocument', { serviceOrder: service_order_id.value, doc: 'invoiceCharge' });
    try {
        let response = await axios.get(url)
        pdfUrl.value = response.data.url;
        showPdfPreview.value = !showPdfPreview.value
    } catch (error) {
        console.error(error.message)
    }
}

function updateChargeArea(chargeArea) {
    const validations = charge_areas.value.data || charge_areas.value;
    const index = validations.findIndex(item => item.id === chargeArea.cicsa_assignation_id)
    const indexChargeArea = validations[index].cicsa_charge_area.findIndex(item => item.id === chargeArea.id)
    validations[index].cicsa_charge_area[indexChargeArea] = chargeArea
}

if (searchCondition) {
    search(searchCondition)
}
</script>
