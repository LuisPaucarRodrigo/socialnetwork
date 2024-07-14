<template>
    <Head title="CICSA Área de Cobranza" />
    <AuthenticatedLayout :redirectRoute="'cicsa.index'">
        <template #header>
            Área de Cobranza
        </template>
        <div class="min-w-full rounded-lg shadow">
            <div class="flex justify-end">
                <SelectCicsaComponent currentSelect="Cobranza" />
            </div>
            <br>
            <div class="overflow-x-auto">
                <table class="w-full whitespace-no-wrap">
                    <thead>
                        <tr
                            class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Nombre de Proyecto
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
                                Fecha de Abono
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Estado
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Monto
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Encargado
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in charge_areas.data" :key="item.id" class="text-gray-700">
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 text-center">
                                    {{ item.project_name }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 text-center">
                                    {{ item.cicsa_charge_area?.invoice_number }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 text-center">
                                    {{ formattedDate(item.cicsa_charge_area?.invoice_date) }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 text-center">
                                    {{ item.cicsa_charge_area ? item.cicsa_charge_area.credit + ' día(s)' : '' }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 text-center">
                                    {{ formattedDate(item.cicsa_charge_area?.payment_date) }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 text-center">
                                    {{ item.cicsa_charge_area ? item.cicsa_charge_area.days_late + ' día(s)' : '' }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 text-center">
                                    {{ formattedDate(item.cicsa_charge_area?.deposit_date) }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 text-center">
                                    {{ item.cicsa_charge_area?.state }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm whitespace-nowrap">
                                <p class="text-gray-900 text-center">
                                    {{ item.cicsa_charge_area?.amount ? 'S/. ' + item.cicsa_charge_area?.amount.toFixed(2) : '' }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 text-center">
                                    {{ item.cicsa_charge_area?.user_name }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <div class="flex space-x-3 justify-center">
                                    <button class="text-blue-900"
                                        @click="openEditFeasibilityModal(item.id, item.cicsa_charge_area)">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-amber-400">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="flex flex-col items-center border-t bg-white px-5 py-5 xs:flex-row xs:justify-between">
                <pagination :links="charge_areas.links" />
            </div>
        </div>

        <Modal :show="showAddEditModal" @close="closeAddAssignationModal">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-800 border-b-2 border-gray-100">
                    {{ form.id ? 'Editar Cobranza' : 'Nueva Cobranza' }}
                </h2>
                <br>
                <form @submit.prevent="submit">
                    <div class="grid grid-cols-1 gap-x-8 gap-y-4 sm:grid-cols-2">
                        <div class="sm:col-span-1">
                            <InputLabel for="invoice_number">Número de Factura</InputLabel>
                            <div class="mt-2">
                                <TextInput type="text" v-model="form.invoice_number" autocomplete="off"
                                    id="invoice_number"/>
                                <InputError :message="form.errors.invoice_number" />
                            </div>
                        </div>

                        <div class="sm:col-span-1">
                            <InputLabel for="invoice_date">Fecha de Factura</InputLabel>
                            <div class="mt-2">
                                <TextInput type="date" v-model="form.invoice_date" autocomplete="off"
                                    id="invoice_date"/>
                                <InputError :message="form.errors.invoice_date" />
                            </div>
                        </div>

                        <div class="sm:col-span-1">
                            <InputLabel for="payment_date">Fecha de Pago</InputLabel>
                            <div class="mt-2">
                                <TextInput type="date" v-model="form.payment_date" autocomplete="off"
                                    id="payment_date"/>
                                <InputError :message="form.errors.payment_date" />
                            </div>
                        </div>

                        <div class="sm:col-span-1">
                            <InputLabel for="deposit_date">Fecha de Abono</InputLabel>
                            <div class="mt-2">
                                <TextInput type="date" v-model="form.deposit_date" autocomplete="off"
                                    id="deposit_date"/>
                                <InputError :message="form.errors.deposit_date" />
                            </div>
                        </div>

                        <div class="sm:col-span-1">
                            <InputLabel for="amount">Monto</InputLabel>
                            <div class="mt-2">
                                <TextInput type="number" step="any" v-model="form.amount" autocomplete="off" id="amount"/>
                                <InputError :message="form.errors.amount" />
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
    </AuthenticatedLayout>
</template>


<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SelectCicsaComponent from '@/Components/SelectCicsaComponent.vue';
import SuccessOperationModal from '@/Components/SuccessOperationModal.vue';
import { formattedDate } from '@/utils/utils.js';
import TextInput from '@/Components/TextInput.vue';

const { charge_areas, auth } = defineProps({
    charge_areas: Object,
    auth: Object
})

console.log(charge_areas)

const initialState = {
    id: null,
    user_id: auth.user.id,
    invoice_number: '',
    invoice_date: '',
    payment_date: '',
    deposit_date: '',
    amount: '',
    user_name: auth.user.name,
    cicsa_assignation_id: '',
}

const form = useForm(
    { ...initialState }
);


const showAddEditModal = ref(false);
const confirmAssignation = ref(false);

function closeAddAssignationModal() {
    showAddEditModal.value = false
    form.defaults({ ...initialState })
    form.reset()
}

const confirmUpdateAssignation = ref(false);

function openEditFeasibilityModal(cicsa_assignation_id, item) {
    form.defaults({ cicsa_assignation_id: cicsa_assignation_id, ...item })
    form.reset()
    showAddEditModal.value = true
}

function submit() {
    let url = form.id ? route('cicsa.charge_areas.update', {cicsa_charge_area: form.id}) : route('cicsa.charge_areas.store', { cicsa_assignation_id: form.cicsa_assignation_id });
    form.post(url, {
        onSuccess: () => {
            closeAddAssignationModal()
            confirmUpdateAssignation.value = true
            setTimeout(() => {
                confirmUpdateAssignation.value = false
            }, 1500)
        },
        onError: (e) => {
            console.error(e)
        }
    })
}

</script>