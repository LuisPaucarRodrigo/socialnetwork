<template>
     <Head title="Gestion de Costos Adicionales" />
    <AuthenticatedLayout :redirectRoute="{
        route: 'spreadsheets.index',
        params: { payroll_id: payroll.id },
    }">
        <template #header>
            Gastos Variables del Proyecto
        </template>
        <Toaster richColors />
        <div class="inline-block min-w-full mb-4">
            <div class="flex gap-4 justify-between">
                <div class="hidden sm:flex  space-x-3">
                    <PrimaryButton @click="openCreatePayModal" type="button" class="whitespace-nowrap">
                        + Agregar
                    </PrimaryButton>
                    <PrimaryButton data-tooltip-target="update_data_tooltip" type="button" @click="() => {
                        filterForm = { ...initialFilterFormState }
                    }">
                        <ServerIcon class="w-5 h-5 text-white" />
                    </PrimaryButton>
                    <div id="update_data_tooltip" role="tooltip"
                        class="absolute z-10 invisible inline-block px-2 py-2 text-xs font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                        Todos los Registros
                        <div class="tooltip-arrow" data-popper-arrow></div>
                    </div>


                    <div>
                        <dropdown align="left">
                            <template #trigger>
                                <button data-tooltip-target="action_button_tooltip"
                                    @click="dropdownOpen = !dropdownOpen"
                                    class="relative block overflow-hidden rounded-md text-white hover:bg-indigo-400 text-center text-sm bg-indigo-500 p-2">
                                    <svg width="20px" height="20px" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path d="M4 6H20M4 12H20M4 18H20" stroke="#ffffff" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </button>
                                <div id="action_button_tooltip" role="tooltip"
                                    class="absolute z-10 invisible inline-block px-2 py-2 text-xs font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700 whitespace-nowrap">
                                    Acciones
                                    <div class="tooltip-arrow" data-popper-arrow></div>
                                </div>
                            </template>

                            <template #content class="origin-left">
                                <div>
                                    <div class="">
                                        <button @click="openOpNuDaModal"
                                            class="block w-full text-left px-4 py-2 text-sm text-black-700 hover:bg-gray-200 hover:text-black focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                                            Actualizar Operación
                                        </button>
                                    </div>
                                </div>
                            </template>
                        </dropdown>
                    </div>

                </div>
                <div class="sm:hidden">
                    <dropdown align="left">
                        <template #trigger>
                            <button @click="dropdownOpen = !dropdownOpen"
                                class="relative block overflow-hidden rounded-md bg-gray-200 px-2 py-2 text-center text-sm text-white hover:bg-gray-100">
                                <svg width="25px" height="25px" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path d="M4 6H20M4 12H20M4 18H20" stroke="#000000" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </button>
                        </template>

                        <template #content class="origin-left">
                            <div>
                                <!-- Alineación a la derecha -->

                                <div class="">
                                    <button @click="openCreatePayModal"
                                        class="block w-full text-left px-4 py-2 text-sm text-black-700 hover:bg-indigo-600 hover:text-white focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                                        Agregar
                                    </button>
                                </div>
                            </div>
                        </template>
                    </dropdown>
                </div>
                <div>
                    <TextInput data-tooltip-target="search_fields" type="text" placeholder="Buscar..." v-model="filterForm.search"
                        @keyup.enter="search_advance()" />
                    <div id="search_fields" role="tooltip"
                        class="absolute z-10 invisible inline-block px-2 py-2 text-xs font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                            Numero de Documento, Numero de Operacion, Empleado, Monto Total
                        <div class="tooltip-arrow" data-popper-arrow></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="overflow-x-auto h-[72vh]">
            <table class="w-full bg-white">
                <thead class="sticky top-0 z-20">
                    <tr
                        class="border-b bg-gray-50 text-center text-xs font-semibold uppercase tracking-wide text-gray-500">
                        <th class="sticky left-0 z-10 bg-gray-100 border-b-2 border-gray-20">
                            <div class="w-2"></div>
                        </th>
                        <th
                            class="sticky left-2 z-10 border-b-2 border-r border-gray-200 bg-gray-100 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600 w-12">
                            <label :for="`check-all`" class="flex gap-3 justify-center w-12 px-2 py-1">
                                <input @change="handleCheckAll" :id="`check-all`" :checked="actionForm.ids.length > 0"
                                    type="checkbox" />
                                {{ actionForm.ids.length ?? "" }}
                            </label>
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600">
                            Zona
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600">
                            <TableHeaderFilter labelClass="text-[11px]" label="Tipo de Gasto" :options="expenseTypes"
                                v-model="filterForm.selectedExpenseTypes" width="w-44" />
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600">
                            <TableHeaderFilter labelClass="text-[11px]" label="Tipo de Documento" :options="docTypes"
                                v-model="filterForm.selectedDocTypes" width="w-32" />
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600">
                            Colaborador
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600">
                            Numero de Operación
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600">
                            <TableDateFilter labelClass="text-[11px]" label="Fecha de Operación"
                                v-model:startDate="filterForm.opStartDate" v-model:endDate="filterForm.opEndDate"
                                v-model:noDate="filterForm.opNoDate" width="w-40" />
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600">
                            Numero de Doc
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600">
                            <TableDateFilter labelClass="text-[11px]" label="Fecha de Documento"
                                v-model:startDate="filterForm.docStartDate" v-model:endDate="filterForm.docEndDate"
                                v-model:noDate="filterForm.docNoDate" width="w-40" />
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600">
                            <div class="flex space-x-1 items-center justify-end">
                                <p>
                                    Fecha de Registro
                                </p>
                                <button @click="sortValue">
                                    <ArrowsUpDownIcon class="h-5 w-5" />
                                </button>
                            </div>

                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600">
                            Monto
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600">
                            Archivo
                        </th>
                        
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600">
                            <TableHeaderFilter labelClass="text-[11px]" label="Estado" :options="stateTypes"
                                v-model="filterForm.selectedStateTypes" width="w-48" />
                        </th>

                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600">
                            Acciones
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="item in dataToRender" :key="item.id"
                        class="text-gray-700 bg-white hover:bg-gray-200 hover:opacity-80">
                        <td :class="[
                            'sticky left-0 z-10 border-b border-gray-200',
                            {
                                'bg-indigo-500': item.real_state === 'Pendiente',
                                'bg-green-500': item.real_state == 'Aceptado - Validado',
                                'bg-amber-500': item.real_state == 'Aceptado',
                                'bg-red-500': item.real_state == 'Rechazado',
                            },
                        ]"></td>
                        <td
                            class="sticky left-2 z-10 border-b border-r border-gray-200 bg-amber-100 text-center text-[13px] whitespace-nowrap tabular-nums">
                            <label :for="`check-${item.id}`" class="block w-12 px-2 py-1">
                                <input v-model="actionForm.ids" :value="item.id" :id="`check-${item.id}`"
                                    type="checkbox" />
                            </label>
                        </td>
                        <td
                            class="border-b w-32 border-gray-200 bg-amber-100 px-2 py-2 text-center text-[13px]">
                            {{ item.general_expense.zone }}
                        </td>
                        <td
                            class="border-b border-gray-200 bg-amber-100 px-2 py-2 text-center text-[13px]">
                            <p class="w-48 break-words">
                                {{ item.general_expense.expense_type }}
                            </p>
                        </td>
                        <td class="border-b border-gray-200 px-2 py-2 text-center text-[13px]">
                            {{ item.general_expense.type_doc }}
                        </td>
                        <td class="border-b border-gray-200 px-2 py-2 text-center text-[13px]">
                            <p class="w-[250px]">
                                {{ item.employee_name }}
                            </p>
                        </td>
                        <td class="border-b border-gray-200 px-2 py-2 text-center text-[13px]">
                            {{ item.general_expense.operation_number }}
                        </td>
                        <td class="border-b border-gray-200 px-2 py-2 text-center text-[13px]">
                            {{
                                item.operation_date &&
                                formattedDate(item.general_expense.operation_date)
                            }}
                        </td>
                        <td
                            class="border-b border-gray-200 px-2 py-2 text-center text-[13px] tabular-nums whitespace-nowrap">
                            {{ item.general_expense.doc_number }}
                        </td>
                        <td class="border-b border-gray-200 px-2 py-2 text-center text-[13px]">
                            {{ formattedDate(item.general_expense.doc_date) }}
                        </td>
                        <td class="border-b border-gray-200 px-2 py-2 text-center text-[13px]">
                            {{ formattedDate(item.created_at) }}
                        </td>
                        <td
                            class="border-b border-gray-200 px-2 py-2 text-center text-[13px] tabular-nums whitespace-nowrap">
                            S/. {{ item.general_expense.amount.toFixed(2) }}
                        </td>
                        <td class="border-b border-gray-200 px-2 py-2 text-center text-[13px]">
                            <button v-if="item.photo" @click="handlerPreview(item.id)">
                                <EyeIcon class="w-4 h-4 text-teal-600" />
                            </button>
                            <span v-else>-</span>
                        </td>
                        
                        <td class="border-b border-gray-200 px-2 py-2 text-center text-[13px]">
                            <div :class="[
                                'text-center',
                                {
                                    'text-indigo-500': item.real_state === 'Pendiente',
                                    'text-green-500': item.real_state == 'Aceptado - Validado',
                                    'text-amber-500': item.real_state == 'Aceptado',
                                    'text-red-500': item.real_state == 'Rechazado',
                                },
                            ]">
                                {{ item.real_state }}
                            </div>
                        </td>
                        <td class="border-b border-gray-200 px-2 py-2 text-center text-[13px]">
                            <div class="flex items-center justify-center gap-3 w-full">
                                <div class="flex gap-3 mr-3">
                                    <button 
                                        type="button"
                                        class="text-amber-600 hover:underline">
                                        <PencilSquareIcon class="h-5 w-5 ml-1" />
                                    </button>
                                    <button type="button" class="text-red-600 hover:underline">
                                        <TrashIcon class="h-5 w-5" />
                                    </button>
                                </div>
                            </div>
                        </td>
                    </tr>
                    
                </tbody>
            </table>
        </div>
        <div v-if="!filterMode"
            class="flex flex-col items-center border-t bg-white px-5 py-5 xs:flex-row xs:justify-between">
            <pagination :links="expenses.links" />
        </div>


    </AuthenticatedLayout>

</template>

<script setup>

import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import TextInput from "@/Components/TextInput.vue";
import ConfirmDeleteModal from "@/Components/ConfirmDeleteModal.vue";
import SuccessOperationModal from "@/Components/SuccessOperationModal.vue";
import ConfirmateModal from "@/Components/ConfirmateModal.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import Modal from "@/Components/Modal.vue";
import {  ref, watch } from "vue";
import { Head, useForm, router } from "@inertiajs/vue3";
import {
    TrashIcon,
    PencilSquareIcon,
    ServerIcon,
    ArrowsUpDownIcon,
    CheckCircleIcon,
    XCircleIcon
} from "@heroicons/vue/24/outline";
import { formattedDate } from "@/utils/utils";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import InputFile from "@/Components/InputFile.vue";
import Pagination from "@/Components/Pagination.vue";
import { EyeIcon } from "@heroicons/vue/24/outline";
import TableHeaderFilter from "@/Components/TableHeaderFilter.vue";
import axios from "axios";
import Dropdown from "@/Components/Dropdown.vue";
import { setAxiosErrors, toFormData } from "@/utils/utils";
import { notify, notifyError, notifyWarning } from "@/Components/Notification";
import { Toaster } from "vue-sonner";
import TableDateFilter from "@/Components/TableDateFilter.vue";
import Search from "@/Components/Search.vue";
import { ExcelIcon } from "@/Components/icons";
import qs from 'qs';

const { payroll, expenses,  expenseTypes, docTypes, stateTypes } = defineProps({
    payroll: Object,
    expenses: Object,
    expenseTypes: Array,
    docTypes: Array,
    stateTypes: Array,
})

const dataToRender = ref(expenses.data)

const initialFilterFormState = {
    search: "",
    selectedExpenseTypes: expenseTypes,
    selectedDocTypes: docTypes,
    selectedStateTypes: stateTypes,
    opStartDate: "",
    opEndDate: "",
    opNoDate: false,
    docStartDate: "",
    docEndDate: "",
    docNoDate: false,
}


const filterForm = ref({ ...initialFilterFormState });
const filterMode = ref(false);

function openCreatePayModal() {
    
}

const actionForm = ref({ids:[]})
const handleCheckAll = (e) => {
    if (e.target.checked) { actionForm.value.ids = dataToRender.value.map((item) => item.id); }
    else { actionForm.value.ids = []; }
};

watch(
    () => [
        filterForm.value.selectedExpenseTypes,
        filterForm.value.selectedDocTypes,
        filterForm.value.selectedStateTypes,
        filterForm.value.opStartDate,
        filterForm.value.opEndDate,
        filterForm.value.opNoDate,
        filterForm.value.docStartDate,
        filterForm.value.docEndDate,
        filterForm.value.docNoDate,
    ],
    () => {
        filterMode.value = true;
        search_advance();
    },
    { deep: true }
);


async function search_advance() {
    const search = filterForm.value
    let res = await axios.post(
        route("payroll.detail.expense.search", {
            payroll_id: payroll.id,
        }), search);
    dataToRender.value = res.data;
    notifyWarning(`Se encontraron ${res.data.length} registro(s)`)
}




</script>