<template>
    <Head title="Gestion de Costos" />
    <AuthenticatedLayout :redirectRoute="{ route: 'huawei.projects.additionalcosts.summary', params: {huawei_project: props.project.id} }">
        <template #header> Gastos {{ props.mode ? 'Fijos' : 'Variables' }} del Proyecto - {{ props.project.name + ' | ' + props.project.code }}</template>
        <br />
        <Toaster richColors />
        <div class="inline-block min-w-full mb-4">
            <div class="flex flex-wrap items-center gap-4">
                <div
                        id="import_tooltip"
                        role="tooltip"
                        class="absolute z-10 invisible inline-block px-2 py-2 text-xs font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700"
                    >
                        Importar Excel
                        <div class="tooltip-arrow" data-popper-arrow></div>
                    </div>
                    <button
                        data-tooltip-target="export_tooltip"
                        type="button"
                        class="rounded-md bg-green-600 px-4 py-2 text-center text-sm text-white hover:bg-green-500"
                        @click="openExportExcel"
                    >
                        <svg
                            class="h-5 w-5"
                            viewBox="0 0 24 24"
                            fill="none"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                fill-rule="evenodd"
                                clip-rule="evenodd"
                                d="M9.29289 1.29289C9.48043 1.10536 9.73478 1 10 1H18C19.6569 1 21 2.34315 21 4V9C21 9.55228 20.5523 10 20 10C19.4477 10 19 9.55228 19 9V4C19 3.44772 18.5523 3 18 3H11V8C11 8.55228 10.5523 9 10 9H5V20C5 20.5523 5.44772 21 6 21H7C7.55228 21 8 21.4477 8 22C8 22.5523 7.55228 23 7 23H6C4.34315 23 3 21.6569 3 20V8C3 7.73478 3.10536 7.48043 3.29289 7.29289L9.29289 1.29289ZM6.41421 7H9V4.41421L6.41421 7ZM19 12C19.5523 12 20 12.4477 20 13V19H23C23.5523 19 24 19.4477 24 20C24 20.5523 23.5523 21 23 21H19C18.4477 21 18 20.5523 18 20V13C18 12.4477 18.4477 12 19 12ZM11.8137 12.4188C11.4927 11.9693 10.8682 11.8653 10.4188 12.1863C9.96935 12.5073 9.86526 13.1318 10.1863 13.5812L12.2711 16.5L10.1863 19.4188C9.86526 19.8682 9.96935 20.4927 10.4188 20.8137C10.8682 21.1347 11.4927 21.0307 11.8137 20.5812L13.5 18.2205L15.1863 20.5812C15.5073 21.0307 16.1318 21.1347 16.5812 20.8137C17.0307 20.4927 17.1347 19.8682 16.8137 19.4188L14.7289 16.5L16.8137 13.5812C17.1347 13.1318 17.0307 12.5073 16.5812 12.1863C16.1318 11.8653 15.5073 11.9693 15.1863 12.4188L13.5 14.7795L11.8137 12.4188Z"
                                fill="#ffffff"
                            />
                        </svg>
                    </button>
                    <div
                        id="export_tooltip"
                        role="tooltip"
                        class="absolute z-10 invisible inline-block px-2 py-2 text-xs font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700"
                    >
                        Exportar Excel
                        <div class="tooltip-arrow" data-popper-arrow></div>
                    </div>
                <div class="flex-grow sm:flex sm:justify-end">
                    <form
                        @submit.prevent="search"
                        class="flex items-center w-full justify-end"
                    >
                        <TextInput
                            type="text"
                            placeholder="Buscar..."
                            v-model="searchForm.searchTerm"
                            class="flex-grow mr-2 max-w-[200px]"
                        />
                        <button
                            type="submit"
                            :class="{ 'opacity-25': searchForm.processing }"
                            class="ml-2 rounded-md bg-indigo-600 px-2 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                        >
                            <svg
                                width="20px"
                                height="20px"
                                viewBox="0 0 24 24"
                                fill="none"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path
                                    d="M15.7955 15.8111L21 21M18 10.5C18 14.6421 14.6421 18 10.5 18C6.35786 18 3 14.6421 3 10.5C3 6.35786 6.35786 3 10.5 3C14.6421 3 18 6.35786 18 10.5Z"
                                    stroke="white"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <div class="overflow-x-auto h-[85vh]">
            <table class="w-full">
                <thead class="sticky top-0 z-20">
                    <tr
                        class="border-b bg-gray-50 text-center text-xs font-semibold uppercase tracking-wide text-gray-500"
                    >
                        <th
                            class="sticky left-0 z-10 bg-gray-100 border-b-2 border-gray-20"
                        >
                            <div class="w-2"></div>
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600"
                        >
                            <TableAutocompleteFilter
                                labelClass="text-[11px]"
                                label="Tipo de Gasto"
                                :options="expenseTypes"
                                v-model="filterForm.selectedExpenseTypes"
                                width="w-48"
                            />
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600"
                        >
                            <TableAutocompleteFilter
                                labelClass="text-[11px]"
                                label="Empleado"
                                :options="employees"
                                v-model="filterForm.selectedEmployees"
                                width="w-48"
                            />
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600"
                        >
                            <TableAutocompleteFilter
                                labelClass="text-[11px]"
                                label="Comprobante de Pago"
                                :options="cdp_types"
                                v-model="filterForm.selectedCDPTypes"
                                width="w-48"
                            />
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600">
                            <TableDateFilter labelClass="text-[11px]" label="Fecha de Gasto"
                                v-model:startDate="filterForm.exStartDate" v-model:endDate="filterForm.exEndDate"
                                v-model:noDate="filterForm.exNoDate" width="w-40" />
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600"
                        >
                            Serie
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600"
                        >
                            Correlativo
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600"
                        >
                            RUC
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600"
                        >
                            Descripción del Gasto
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600"
                        >
                            Monto
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600"
                        >
                            Imagen
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600">
                            <TableDateFilter labelClass="text-[11px]" label="Fecha de Depósito E.C."
                                v-model:startDate="filterForm.opStartDate" v-model:endDate="filterForm.opEndDate"
                                v-model:noDate="filterForm.opNoDate" width="w-40" />
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600"
                        >
                            N° de Operación de E.C.
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600"
                        >
                            Monto en E.C.
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600"
                        >
                            <TableAutocompleteFilter
                                labelClass="text-[11px]"
                                label="Estado"
                                :options="[
                                    'Aceptado',
                                    'Rechazado',
                                    'Pendiente',
                                    'Aceptado-Validado',
                                ]"
                                v-model="filterForm.selectedStates"
                                width="w-48"
                            />
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="item in props.search ? expenses : expenses.data"
                        :key="item.id"
                        class="text-gray-700"
                    >
                    <td
                            :class="[
                                'sticky left-0 z-10 border-b border-gray-200',
                                {
                                    'bg-indigo-500':
                                        item.real_state === 'Pendiente',
                                    'bg-green-500':
                                        item.real_state === 'Aceptado-Validado',
                                    'bg-amber-500':
                                        item.real_state === 'Aceptado',
                                    'bg-red-500':
                                        item.real_state === 'Rechazado',
                                },
                            ]"
                        ></td>
                        <td
                            class="border-b border-gray-200 bg-white px-2 py-2 text-center text-[13px]"
                        >
                            <p class="w-48 break-words">
                                {{ item.expense_type }}
                            </p>
                        </td>
                        <td
                            class="border-b border-gray-200 bg-white px-2 py-2 text-center text-[13px]"
                        >
                            {{ item.employee }}
                        </td>
                        <td
                            class="border-b border-gray-200 bg-white px-2 py-2 text-center text-[13px]"
                        >
                            {{ item.cdp_type }}
                        </td>
                        <td
                            class="border-b border-gray-200 bg-white px-2 py-2 text-center text-[13px] tabular-nums"
                        >
                            {{ formattedDate(item.expense_date) }}
                        </td>
                        <td
                            class="border-b whitespace-nowrap border-gray-200 bg-white px-2 py-2 text-center text-[13px]"
                        >
                            {{ item.doc_number }}
                        </td>
                        <td
                            class="border-b whitespace-nowrap border-gray-200 bg-white px-2 py-2 text-center text-[13px] tabular-nums"
                        >
                            {{ item.op_number }}
                        </td>
                        <td
                            class="border-b whitespace-nowrap border-gray-200 bg-white px-2 py-2 text-center text-[13px] tabular-nums whitespace-nowrap"
                        >
                            {{ item.ruc }}
                        </td>
                        <td
                            class="border-b whitespace-nowrap border-gray-200 bg-white px-2 py-2 text-center text-[13px] tabular-nums"
                        >
                            {{ item.description }}
                        </td>
                        <td
                            class="border-b whitespace-nowrap border-gray-200 bg-white px-2 py-2 text-center text-[13px]"
                        >
                            S/. {{ item.amount.toFixed(2) }}
                        </td>

                        <td
                            class="border-b border-gray-200 bg-white px-2 py-2 text-center text-[13px] whitespace-nowrap"
                        >
                            <button v-if="item.image"
                                @click="openPreviewDocumentModal(item.id)"
                                class="flex items-center justify-center w-full"
                            >
                                <EyeIcon class="h-5 w-5 text-green-400" />
                            </button>
                        </td>

                        <td
                            class="border-b whitespace-nowrap border-gray-200 bg-white px-2 py-2 text-center text-[13px] tabular-nums whitespace-nowrap"
                        >
                            {{ formattedDate(item.ec_expense_date) }}
                        </td>
                        <td
                            class="border-b whitespace-nowrap border-gray-200 bg-white px-2 py-2 text-center text-[13px] tabular-nums whitespace-nowrap"
                        >
                            {{ item.ec_op_number }}
                        </td>
                        <td
                            class="border-b whitespace-nowrap border-gray-200 bg-white px-2 py-2 text-center text-[13px] tabular-nums whitespace-nowrap"
                        >
                            {{
                                item.ec_amount
                                    ? "S/. " + item.ec_amount.toFixed(2)
                                    : ""
                            }}
                        </td>
                        <td
                            class="border-b whitespace-nowrap border-gray-200 bg-white px-2 py-2 text-center text-[13px] tabular-nums whitespace-nowrap"
                            :class="[
                                'text-center',
                                {
                                    'text-indigo-500':
                                        item.real_state === 'Pendiente',
                                    'text-green-500':
                                        item.real_state === 'Aceptado-Validado',
                                    'text-amber-500':
                                        item.real_state === 'Aceptado',
                                    'text-red-500':
                                        item.real_state === 'Rechazado',
                                },
                            ]"
                        >
                            {{ item.real_state }}
                        </td>
                    </tr>
                    <tr class="sticky bottom-0 z-10 text-gray-700">
                        <td
                            class="font-bold border-b border-gray-200 bg-white"
                        ></td>
                        <td
                            class="font-bold border-b border-gray-200 bg-white px-5 py-5 text-sm"
                        >
                            TOTAL
                        </td>
                        <td
                            class="border-b border-gray-200 bg-white px-5 py-5 text-sm"
                            colspan="7"
                        ></td>
                        <td
                            class="border-b border-gray-200 bg-white px-5 py-5 text-sm whitespace-nowrap"
                        >
                            S/.
                            {{
                                expenses.data
                                    ?.reduce((a, item) => a + item.amount, 0)
                                    .toFixed(2)
                            }}
                        </td>
                        <td
                            class="border-b border-gray-200 bg-white px-5 py-5 text-sm"
                            colspan="5"
                        ></td>
                    </tr>
                </tbody>
            </table>
        </div>

    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import ConfirmDeleteModal from "@/Components/ConfirmDeleteModal.vue";
import SuccessOperationModal from "@/Components/SuccessOperationModal.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import Modal from "@/Components/Modal.vue";
import { ref, watch } from "vue";
import { Head, useForm, router } from "@inertiajs/vue3";
import { TrashIcon, PencilSquareIcon } from "@heroicons/vue/24/outline";
import { formattedDate } from "@/utils/utils";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import InputFile from "@/Components/InputFile.vue";
import Pagination from "@/Components/Pagination.vue";
import { EyeIcon } from "@heroicons/vue/24/outline";
import TableDateFilter from "@/Components/TableDateFilter.vue";
import TableAutocompleteFilter from "@/Components/TableAutocompleteFilter.vue";
import axios from "axios";
import TextInput from "@/Components/TextInput.vue";
import Dropdown from "@/Components/Dropdown.vue";
import { notify, notifyError, notifyWarning } from "@/Components/Notification";
import { Toaster } from "vue-sonner";

const props = defineProps({
    expense: Object,
    project: Object,
    search: String,
    data: Object,
    mode: String
});

const expenses = ref(props.expense);
const filterMode = ref(false);

const openPreviewDocumentModal = (expense, img) => {
    const routeToShow = route("huawei.projects.additionalcosts.showimage", {
        expense: expense,
    });
    window.open(routeToShow, "_blank");
};

const employees = props.data.employees;
const expenseTypes = props.mode ? props.data.static_expense_types : props.data.variable_expense_types;
const cdp_types = props.data.cdp_types;


const filterForm = ref({
    search: "",
    selectedEmployees: employees,
    selectedExpenseTypes: expenseTypes,
    selectedCDPTypes: cdp_types,
    exStartDate: "",
    exEndDate: "",
    exNoDate: false,
    opStartDate: "",
    opEndDate: "",
    selectedStates: ["Aceptado", "Rechazado", "Pendiente", "Aceptado-Validado"],
    opNoDate: false,
});

watch(
    () => [
        filterForm.value.search,
        filterForm.value.selectedEmployees,
        filterForm.value.selectedExpenseTypes,
        filterForm.value.selectedCDPTypes,
        filterForm.value.exStartDate,
        filterForm.value.exEndDate,
        filterForm.value.exNoDate,
        filterForm.value.opStartDate,
        filterForm.value.opEndDate,
        filterForm.value.opNoDate,
        filterForm.value.selectedStates,
    ],
    () => {
        (filterMode.value = true), search_advance(filterForm.value);
    },
    { deep: true }
);

async function search_advance($data) {
    let url = route("huawei.projects.additionalcosts.advancedsearch", {
        huawei_project_id: props.project.id,
        mode: props.mode ?? null,
    });
    try {
        let response = await axios.post(url, $data);
        expenses.value.data = response.data.expenses;
    } catch (error) {
        console.error("Error en la solicitud:", error);
    }
}

const searchForm = useForm({
    searchTerm: props.search ? props.search : "",
});

const search = () => {
    if (searchForm.searchTerm == "") {
        router.visit(
            route("huawei.projects.additionalcosts", {
                huawei_project: props.project.id,
                mode: props.mode
            })
        );
    } else {
        router.visit(
            route("huawei.projects.additionalcosts.search", {
                huawei_project: props.project.id,
                request: searchForm.searchTerm,
                mode: props.mode
            })
        );
    }
};

function openExportExcel() {
    const url = route("huawei.projects.additionalcosts.export", {huawei_project_id: props.project.id, mode: props.mode});
    window.location.href = url;
}
</script>
