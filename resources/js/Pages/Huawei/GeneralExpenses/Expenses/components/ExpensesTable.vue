<template>
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
                        v-permission="'huawei_expenses_admin'"
                        class="sticky left-2 z-10 border-b-2 border-r border-gray-200 bg-gray-100 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600 w-12"
                    >
                        <label
                            :for="`check-all`"
                            class="flex gap-3 justify-center w-full px-2 py-1"
                        >
                            <input
                                @change="handleCheckAll"
                                :id="`check-all`"
                                :checked="actionForm.ids.length > 0"
                                type="checkbox"
                            />
                            {{ actionForm.ids.length ?? "" }}
                        </label>
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
                            label="Zona"
                            :options="zones"
                            v-model="filterForm.selectedZones"
                            width="w-48"
                        />
                    </th>
                    <th
                        class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600"
                    >
                        <TableAutocompleteFilter
                            labelClass="text-[11px]"
                            label="DU del Proyecto"
                            :options="assigned_dius"
                            v-model="filterForm.selectedDus"
                            :empty="true"
                            width="w-72"
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
                            width="w-72"
                        />
                    </th>
                    <th
                        class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600"
                    >
                        <TableAutocompleteFilter
                            labelClass="text-[11px]"
                            label="Tipo de Documento"
                            :options="cdpTypes"
                            v-model="filterForm.selectedCDPTypes"
                            width="w-48"
                        />
                    </th>
                    <th
                        class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600"
                    >
                        <TableDateFilter
                            labelClass="text-[11px]"
                            label="Fecha de Gasto"
                            v-model:startDate="filterForm.exStartDate"
                            v-model:endDate="filterForm.exEndDate"
                            v-model:noDate="filterForm.exNoDate"
                            width="w-40"
                        />
                    </th>
                    <th
                        class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600"
                    >
                        Número de Serie
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
                        class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600"
                    >
                        <TableDateFilter
                            labelClass="text-[11px]"
                            label="Fecha de Depósito E.C."
                            v-model:startDate="filterForm.opStartDate"
                            v-model:endDate="filterForm.opEndDate"
                            v-model:noDate="filterForm.opNoDate"
                            width="w-40"
                        />
                    </th>
                    <th
                        class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600"
                    >
                        <TableAutocompleteFilter
                            labelClass="text-[11px]"
                            label="N° de Operación de E.C."
                            :options="opNumbers"
                            v-model="filterForm.ecOpNumbers"
                            :empty="true"
                            width="w-48"
                        />
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
                    <th
                        class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600"
                    >
                        Acciones
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr
                    v-for="item in expenses"
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
                                'bg-amber-500': item.real_state === 'Aceptado',
                                'bg-red-500': item.real_state === 'Rechazado',
                            },
                        ]"
                    ></td>
                    <td
                        v-permission="'huawei_expenses_admin'"
                        class="sticky left-2 z-10 border-b border-r border-gray-200 bg-amber-100 text-center text-[13px] whitespace-nowrap tabular-nums"
                    >
                        <label
                            :for="`check-${item.id}`"
                            class="block w-full px-2 py-1"
                        >
                            <input
                                v-model="actionForm.ids"
                                :value="item.id"
                                :id="`check-${item.id}`"
                                type="checkbox"
                            />
                        </label>
                    </td>
                    <td
                        class="border-b border-gray-200 bg-white px-2 py-2 text-center text-[13px]"
                    >
                        <p class="w-48 break-words">
                            {{ item.expense_type }}
                        </p>
                    </td>
                    <td
                        class="border-b border-gray-200 bg-white px-2 py-2 text-center whitespace-nowrap text-[13px]"
                    >
                        {{ item.zone }}
                    </td>
                    <td
                        class="border-b border-gray-200 bg-white px-2 py-2 text-center whitespace-nowrap text-[13px]"
                    >
                        {{ item.huawei_project?.assigned_diu }}
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
                        S/. {{ item.amount ? item.amount.toFixed(2) : "" }}
                    </td>

                    <td
                        class="border-b border-gray-200 bg-white px-2 py-2 text-center text-[13px] whitespace-nowrap"
                    >
                        <button
                            v-if="item.image"
                            @click="openPreviewDocumentModal(item.id)"
                            class="flex items-center justify-center w-full"
                        >
                            <EyeOutlineIcon class="h-5 w-5 text-green-400" />
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
                                'text-red-500': item.real_state === 'Rechazado',
                            },
                        ]"
                    >
                        {{ item.real_state }}
                    </td>
                    <td
                        class="border-b border-gray-200 bg-white px-2 py-2 text-center text-[13px]"
                    >
                        <div
                            class="flex items-center gap-3 w-full justify-center"
                        >
                            <div
                                v-permission="'huawei_expenses_admin'"
                                v-if="item.is_accepted == null"
                                class="flex gap-3 justify-center w-1/2"
                            >
                                <button
                                    @click="validateRegister(item.id, true)"
                                    class="flex items-center rounded-xl text-blue-500 hover:bg-green-200"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke-width="1.5"
                                        stroke="currentColor"
                                        class="w-5 h-5 text-green-500"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"
                                        />
                                    </svg>
                                </button>
                                <button
                                    @click="validateRegister(item.id, false)"
                                    type="button"
                                    class="rounded-xl whitespace-no-wrap text-center text-sm text-red-900 hover:bg-red-200"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke-width="1.5"
                                        stroke="currentColor"
                                        class="w-5 h-5 text-red-500"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"
                                        />
                                    </svg>
                                </button>
                            </div>

                            <div class="flex gap-3 justify-center w-1/2">
                                <button
                                    v-permission="'huawei_expenses_edit'"
                                    @click="$emit('edit', item)"
                                    class="text-amber-600 hover:underline"
                                >
                                    <EditIcon class="h-5 w-5 ml-1" />
                                </button>
                                <button
                                    v-permission="'huawei_expenses_delete'"
                                    @click="$emit('delete', item.id)"
                                    class="text-red-600 hover:underline"
                                >
                                    <DeleteIcon class="h-5 w-5" />
                                </button>
                            </div>
                        </div>
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
                        colspan="9"
                    ></td>
                    <td
                        class="border-b border-gray-200 bg-white px-5 py-5 text-sm"
                        v-permission="'huawei_expenses_admin'"
                    ></td>
                    <td
                        class="border-b border-gray-200 bg-white px-5 py-5 text-sm whitespace-nowrap"
                    >
                        S/.
                        {{
                            expenses
                                ?.reduce((a, item) => a + item.amount, 0)
                                .toFixed(2)
                        }}
                    </td>

                    <td
                        class="border-b border-gray-200 bg-white px-5 py-5 text-sm"
                        colspan="9"
                    ></td>
                </tr>
            </tbody>
        </table>
    </div>
    <!-- <div
            v-if="!filterMode && !props.search"
            class="flex flex-col items-center border-t bg-white px-5 py-5 xs:flex-row xs:justify-between"
        >
            <pagination :links="expenses.links" />
        </div>  -->
</template>

<script setup>
import { formattedDate } from "@/utils/utils";
import { ref, watch } from "vue";
import { EditIcon, DeleteIcon, EyeOutlineIcon } from "@/Components/Icons";
import TableAutocompleteFilter from "@/Components/TableAutocompleteFilter.vue";
import TableDateFilter from "@/Components/TableDateFilter.vue";
import axios from "axios";

const {
    expenses,
    search,
    mode,
    employees,
    expenseTypes,
    cdpTypes,
    opNumbers,
    zones,
    assigned_dius,
} = defineProps({
    expenses: Object,
    search: String,
    mode: String,
    employees: Array,
    expenseTypes: Array,
    cdpTypes: Array,
    opNumbers: Array,
    zones: Array,
    assigned_dius: Array,
});

const emits = defineEmits(["edit", "delete", "options", "data", "validate"]);
const filterMode = ref(false);

const handleCheckAll = (e) => {
    if (e.target.checked) {
        actionForm.value.ids = expenses.map((item) => item.id);
    } else {
        actionForm.value.ids = [];
    }
};

async function validateRegister(expense_id, is_accepted) {
    const url = route("huawei.projects.general.expenses.validate", {
        expense: expense_id,
    });
    try {
        const response = await axios.put(url, { is_accepted: is_accepted });
        emits("validate", {
            validate: response.data,
            is_accepted,
        });
    } catch (e) {
        console.log(e);
        emits("validate", "error");
    }
}

const openPreviewDocumentModal = (expense) => {
    const routeToShow = route("huawei.projects.general.expenses.showimage", {
        expense: expense,
    });
    window.open(routeToShow, "_blank");
};

const filterForm = ref({
    search: search ?? "",
    selectedEmployees: employees,
    selectedZones: zones,
    selectedDus: assigned_dius,
    selectedExpenseTypes: expenseTypes,
    selectedCDPTypes: cdpTypes,
    exStartDate: "",
    exEndDate: "",
    exNoDate: false,
    opStartDate: "",
    opEndDate: "",
    ecOpNumbers: opNumbers,
    selectedStates: ["Aceptado", "Rechazado", "Pendiente", "Aceptado-Validado"],
    opNoDate: false,
});

watch(
    () => [
        filterForm.value.search,
        filterForm.value.selectedEmployees,
        filterForm.value.selectedZones,
        filterForm.value.selectedDus,
        filterForm.value.selectedExpenseTypes,
        filterForm.value.selectedCDPTypes,
        filterForm.value.exStartDate,
        filterForm.value.exEndDate,
        filterForm.value.exNoDate,
        filterForm.value.opStartDate,
        filterForm.value.opEndDate,
        filterForm.value.opNoDate,
        filterForm.value.ecOpNumbers,
        filterForm.value.selectedStates,
    ],
    () => {
        (filterMode.value = true), search_advance(filterForm.value);
    },
    { deep: true }
);
async function search_advance($data) {
    let url = route("huawei.projects.general.expenses.searchadvance", {
        mode: mode,
    });
    try {
        let response = await axios.post(url, $data);
        actionForm.value.ids = [];
        emits("options", actionForm.value.ids);
        emits("data", response.data.expenses);
    } catch (error) {
        console.error("Error en la solicitud:", error);
    }
}

const actionForm = ref({
    ids: [],
});

watch(
    () => actionForm.value.ids,
    (newIds) => {
        emits("options", newIds);
    },
    { deep: true, immediate: true }
);
</script>
