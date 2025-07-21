<template>
    <Modal :show="showModal">
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-800 border-b-2 border-gray-100">
                Crear Programación de Pago
            </h2>
            <br>
            <form @submit.prevent="submit">
                <div class="grid grid-cols-1 gap-x-8 gap-y-4 sm:grid-cols-2">
                    <div class="">
                        <InputLabel for="cost_line">Linea de Negocio</InputLabel>
                        <div class="mt-2">
                            <select id="cost_line" v-model="form.cost_line_id"
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                <option value="">Seleccionar Linea de Negocio</option>
                                <option v-for="item in costLines" :key="item.id" :value="item.id">
                                    {{ item.name }}
                                </option>
                            </select>
                            <InputError :message="form.errors.cost_line_id" />
                        </div>
                    </div>
                    <div class="">
                        <InputLabel for="zone">Zona</InputLabel>
                        <div class="mt-2">
                            <select id="zone" v-model="form.zone" autocomplete="off"
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                <option value="">Seleccionar Zona</option>
                                <option v-for="zone in zones" :key="zone" :value="zone">{{ zone }}</option>
                            </select>
                            <InputError :message="form.errors.zone" />
                        </div>
                    </div>
                    <div>
                        <InputLabel for="ruc" class="font-medium leading-6 text-gray-900">RUC
                        </InputLabel>
                        <div class="mt-2">
                            <input type="text" v-model="form.ruc" id="ruc" maxlength="11"
                                @input="handleRucDniAutocomplete" autocomplete="off" list="options"
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                            <datalist id="options">
                                <option v-for="item in providers" :value="item.ruc">
                                    {{ item.company_name }}
                                </option>
                            </datalist>
                            <InputError :message="form.errors.ruc" />
                        </div>
                    </div>
                    <div class="">
                        <InputLabel for="bank">Banco</InputLabel>
                        <div class="mt-2">
                            <select id="bank" v-model="form.bank" autocomplete="off"
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                <option value="">Seleccionar Banco</option>
                                <option v-for="bank in banks" :key="bank" :value="bank">{{ bank }}</option>
                            </select>
                            <InputError :message="form.errors.bank" />
                        </div>
                    </div>
                    <div class="">
                        <InputLabel for="account_number">Numero de Cuenta</InputLabel>
                        <div class="mt-2">
                            <input type="text" v-model="form.account_number" autocomplete="off" id="account_number"
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                            <InputError :message="form.errors.account_number" />
                        </div>
                    </div>
                    <div class="">
                        <InputLabel for="amount">Monto</InputLabel>
                        <div class="mt-2">
                            <input type="number" v-model="form.amount" autocomplete="off" id="amount" step="0.01"
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                            <InputError :message="form.errors.amount" />
                        </div>
                    </div>
                    <div class="">
                        <InputLabel for="beneficiary">Beneficiario</InputLabel>
                        <div class="mt-2">
                            <input type="text" v-model="form.beneficiary" autocomplete="off" id="beneficiary"
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                            <InputError :message="form.errors.beneficiary" />
                        </div>
                    </div>
                    <div class="">
                        <InputLabel for="description">Descripción</InputLabel>
                        <div class="mt-2">
                            <textarea v-model="form.description" autocomplete="off" id="description"
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                            <InputError :message="form.errors.description" />
                        </div>
                    </div>
                    <div>
                        <InputLabel class="font-medium leading-6 text-gray-900">
                            Documento
                        </InputLabel>
                        <div class="mt-2">
                            <InputFile v-model="form.document" accept=".pdf" />
                            <InputError :message="form.errors.document" />
                        </div>
                    </div>
                </div>
                <div class="mt-6 flex justify-end">
                    <SecondaryButton type="button" @click="closeModal"> Cancelar </SecondaryButton>
                    <PrimaryButton class="ml-3 tracking-widest uppercase text-xs"
                        :class="{ 'opacity-25': form.processing }" :disabled="form.processing" type="submit">
                        Guardar
                    </PrimaryButton>
                </div>
            </form>
        </div>
    </Modal>
</template>
<script setup>
import InputError from '@/Components/InputError.vue';
import InputFile from '@/Components/InputFile.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Modal from '@/Components/Modal.vue';
import { notify } from '@/Components/Notification';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { useAxiosErrorHandler } from '@/utils/axiosError';
import { toFormData } from '@/utils/utils';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const { payments, zones, banks, costLines, providers } = defineProps({
    payments: Object,
    zones: Array,
    banks: Array,
    costLines: Array,
    providers: Object
})

const showModal = ref(false)
const initialState = {
    zone: '',
    description: '',
    amount: '',
    account_number: '',
    bank: '',
    ruc: '',
    beneficiary: '',
    document: null,
    cost_line_id: '',
    provider_id: ''
}

const form = useForm({
    ...initialState
})

function toogleModal() {
    showModal.value = !showModal.value
}

function openPaymentModal() {
    toogleModal()
}

function closeModal() {
    form.clearErrors()
    form.defaults({ ...initialState })
    form.reset()
    toogleModal()
}

async function submit() {
    let url = route('payment.approval.store')
    try {
        let formData = toFormData(form)
        const res = await axios.post(url, formData)
        updateFrontEnd(res.data, 'create')
    } catch (error) {
        useAxiosErrorHandler(error, form)
    }
}

const handleRucDniAutocomplete = (e) => {
    let ruc = e.target.value;
    let findProv = providers.find((item) => item.ruc == ruc);
    if (findProv) {
        form.provider_id = findProv.id ?? '';
        form.account_number = findProv.account_number ?? '';
        form.beneficiary = findProv.company_name ?? '';
    }
};

function updateFrontEnd(data, action) {
    let listDate = payments.data || payments
    if (action === "create") {
        listDate.unshift(data)
        notify("Programación creada existosamente")
        closeModal()
    }
}

defineExpose({ openPaymentModal })
</script>