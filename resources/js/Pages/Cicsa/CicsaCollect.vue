<template>

    <Head title="CICSA Área de Cobranza" />
    <AuthenticatedLayout :redirectRoute="'cicsa.index'">
        <template #header>
            Proyecto Cicsa por Cobrar
        </template>
        <div class="min-w-full rounded-lg shadow">
            <div class="overflow-x-auto h-[70vh]">
                <table class="w-full whitespace-no-wrap">
                    <thead>
                        <tr
                            class="sticky top-0 z-20 border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Nombre de Proyecto
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Codigo de Proyecto
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                CPE
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
                            <!-- <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                            </th> -->
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in charge_areas.data" :key="item.id" class="text-gray-700">
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm" :class="!item.project_code ? 'bg-red-200' :'bg-white'">
                                <p class="text-gray-900 text-center">
                                    {{ item.project_name }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 px-5 py-5 text-sm" :class="!item.project_code ? 'bg-red-200' :'bg-white'">
                                <p class="text-gray-900 text-center">
                                    {{ item.project_code }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 px-5 py-5 text-sm" :class="!item.cpe ? 'bg-red-200' :'bg-white'">
                                <p class="text-gray-900 text-center">
                                    {{ item.cpe }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 px-5 py-5 text-sm" :class="!item.cicsa_charge_area?.invoice_number ? 'bg-red-200' :'bg-white'">
                                <p class="text-gray-900 text-center">
                                    {{ item.cicsa_charge_area?.invoice_number }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 px-5 py-5 text-sm" :class="!item.cicsa_charge_area?.invoice_date ? 'bg-red-200' :'bg-white'">
                                <p class="text-gray-900 text-center">
                                    {{ formattedDate(item.cicsa_charge_area?.invoice_date) }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 px-5 py-5 text-sm" :class="!item.cicsa_charge_area.credit_to ? 'bg-red-200' :'bg-white'">
                                <p class="text-gray-900 text-center">
                                    {{ item.cicsa_charge_area?.credit_to ? item.cicsa_charge_area.credit_to + ' día(s)'
                                    : '' }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 px-5 py-5 text-sm" :class="!item.cicsa_charge_area?.payment_date ? 'bg-red-200' :'bg-white'">
                                <p class="text-gray-900 text-center">
                                    {{ formattedDate(item.cicsa_charge_area?.payment_date) }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 px-5 py-5 text-sm" :class="item.cicsa_charge_area.days_late <= 0 ? 'bg-red-200' :'bg-white'">
                                <p class="text-gray-900 text-center">
                                    {{ item.cicsa_charge_area?.invoice_date && item.cicsa_charge_area?.credit_to ?
                                        item.cicsa_charge_area.days_late + ' día(s)' : '' }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 px-5 py-5 text-sm" :class="!item.cicsa_charge_area?.deposit_date ? 'bg-red-200' :'bg-white'">
                                <p class="text-gray-900 text-center">
                                    {{ formattedDate(item.cicsa_charge_area?.deposit_date) }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 px-5 py-5 text-sm" :class="item.cicsa_charge_area?.state === 'Con deuda' && !item.cicsa_charge_area?.credit_to ? 'bg-red-200' :'bg-white'">
                                <p class="text-gray-900 text-center">
                                    {{ item.cicsa_charge_area?.invoice_date && item.cicsa_charge_area?.credit_to ?
                                    item.cicsa_charge_area?.state : '' }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 px-5 py-5 text-sm whitespace-nowrap" :class="!item.cicsa_charge_area?.amount ? 'bg-red-200' :'bg-white'">
                                <p class="text-gray-900 text-center">
                                    {{ item.cicsa_charge_area?.amount ? 'S/. ' +
                                        item.cicsa_charge_area?.amount.toFixed(2) : ''
                                    }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 px-5 py-5 text-sm" :class="!item.cicsa_charge_area?.user_name ? 'bg-red-200' :'bg-white'">
                                <p class="text-gray-900 text-center">
                                    {{ item.cicsa_charge_area?.user_name }}
                                </p>
                            </td>
                            <!-- <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
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
                            </td> -->
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="flex flex-col items-center border-t bg-white px-5 py-5 xs:flex-row xs:justify-between">
                <pagination :links="charge_areas.links" />
            </div>
        </div>
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
import { ref, watch } from 'vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SelectCicsaComponent from '@/Components/SelectCicsaComponent.vue';
import SuccessOperationModal from '@/Components/SuccessOperationModal.vue';
import { formattedDate } from '@/utils/utils.js';
import TextInput from '@/Components/TextInput.vue';

const { charge_areas, auth } = defineProps({
    charge_areas: Object,
    auth: Object
})

const initialState = {
    id: null,
    user_id: auth.user.id,
    invoice_number: '',
    invoice_date: '',
    credit_to: '',
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
    let url = form.id ? route('cicsa.charge_areas.update', { cicsa_charge_area: form.id }) : route('cicsa.charge_areas.store', { cicsa_assignation_id: form.cicsa_assignation_id });
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

</script>
