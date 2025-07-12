<template>
    <div class="overflow-x-auto h-[85vh]">
        <table class="w-full">
            <thead class="sticky top-0 z-20">
                <tr
                    class=" border-b bg-gray-50 text-center text-xs font-semibold uppercase tracking-wide text-gray-500">
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
                    <!-- <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600">
                            Proyecto
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600">
                            Centro de Costos
                        </th> -->
                    <th
                        class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600">
                        <TableHeaderFilter labelClass="text-[11px]" label="Zona" :options="zones"
                            v-model="filterForm.selectedZones" width="w-40" />
                    </th>
                    <th
                        class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600 ">
                        <TableHeaderFilter labelClass="text-[11px]" label="Tipo de Gasto" :options="expenseTypes"
                            v-model="filterForm.selectedExpenseTypes" width="w-40" />
                    </th>
                    <th
                        class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600">
                        <TableHeaderFilter labelClass="text-[11px]" label="Tipo de Documento" :options="docTypes"
                            v-model="filterForm.selectedDocTypes" width="w-40" />
                    </th>
                    <th
                        class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600">
                        RUC
                    </th>
                    <th
                        class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600">
                        Proveedor
                    </th>
                    <th
                        class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600">
                        Numero de Operacion
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
                        Monto
                    </th>
                    <th
                        class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600">
                        Monto sin IGV
                    </th>
                    <th
                        class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600">
                        Archivo
                    </th>
                    <th
                        class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600">
                        Descripción
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
                <tr v-for="item in expenses.data || expenses" :key="item.id" class="text-gray-700">
                    <td :class="[
                        'sticky left-0 z-10 border-b border-gray-200',
                        {
                            'bg-indigo-500': item.is_accepted === null,
                            'bg-green-500': item.is_accepted == true,
                            'bg-red-500': item.is_accepted == false,
                        },
                    ]">
                    </td>
                    <!-- <td
                            class="border-b border-gray-200 bg-white px-2 py-2 text-center text-[13px] whitespace-nowrap">
                            {{ item.cicsa_assignation?.project_name }}
                        </td>
                        <td
                            class="border-b border-gray-200 bg-white px-2 py-2 text-center text-[13px] whitespace-nowrap">
                            {{ item.project?.cost_center?.name }}
                        </td> -->
                    <td
                        class="sticky left-2 z-10 border-b border-r border-gray-200 bg-amber-100 text-center text-[13px] whitespace-nowrap tabular-nums">
                        <label :for="`check-${item.id}`" class="block w-12 px-2 py-1">
                            <input v-model="actionForm.ids" :value="item.id" :id="`check-${item.id}`" type="checkbox" />
                        </label>
                    </td>
                    <td class="border-b border-gray-200 bg-white px-2 py-2 text-center text-[13px]">
                        {{ item.zone }}
                    </td>
                    <td class="border-b border-gray-200 bg-white px-2 py-2 text-center text-[13px]">
                        <p class="w-48 break-words">
                            {{ item.expense_type }}
                        </p>
                    </td>
                    <td class="border-b border-gray-200 bg-white px-2 py-2 text-center text-[13px]">
                        {{ item.type_doc }}
                    </td>
                    <td class="border-b border-gray-200 bg-white px-2 py-2 text-center text-[13px] tabular-nums">
                        {{ item.ruc }}
                    </td>
                    <td class="border-b border-gray-200 bg-white px-2 py-2 text-center text-[13px]">
                        {{ item?.provider?.company_name }}
                    </td>
                    <td class="border-b border-gray-200 bg-white px-2 py-2 text-center text-[13px] tabular-nums">
                        {{ item.operation_number }}
                    </td>
                    <td
                        class="border-b border-gray-200 bg-white px-2 py-2 text-center text-[13px] tabular-nums whitespace-nowrap">
                        {{ formattedDate(item.operation_date) }}
                    </td>
                    <td class="border-b border-gray-200 bg-white px-2 py-2 text-center text-[13px] tabular-nums">
                        {{ item.doc_number }}
                    </td>
                    <td class="border-b border-gray-200 bg-white px-2 py-2 text-center text-[13px]">
                        {{ formattedDate(item.doc_date) }}
                    </td>
                    <td
                        class="border-b border-gray-200 bg-white px-2 py-2 text-center text-[13px] tabular-nums whitespace-nowrap">
                        S/. {{ item.amount.toFixed(2) }}
                    </td>
                    <td
                        class="border-b border-gray-200 bg-white px-2 py-2 text-center text-[13px] tabular-nums whitespace-nowrap">
                        S/. {{ item.real_amount.toFixed(2) }}
                    </td>
                    <td class="border-b border-gray-200 bg-white px-2 py-2 text-center text-[13px]">
                        <button v-if="item.photo" @click="handlerPreview(item.id)">
                            <ShowIcon />
                        </button>
                        <span v-else>-</span>
                    </td>
                    <td class="border-b border-gray-200 bg-white px-2 py-2 text-center text-[13px]">
                        <p class="w-[250px]">
                            {{ item.description }}
                        </p>
                    </td>
                    <td class="border-b border-gray-200 bg-white px-2 py-2 text-center text-[13px]">
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
                    <td class="border-b border-gray-200 bg-white px-2 py-2 text-center text-[13px]">
                        <div class="flex items-center gap-3 w-full">
                            <div v-if="item.is_accepted === null" class="flex gap-3 justify-center w-1/2">
                                <button @click="() => validateRegister(item.id, true)" type="button">
                                    <AcceptIcon />
                                </button>
                                <button @click="() => validateRegister(item.id, false)" type="button">
                                    <RejectIcon />
                                </button>
                            </div>
                            <div v-else class="w-1/2"></div>

                            <div class="flex gap-3 mr-3">
                                <button v-if="!filterForm.rejected" @click="() => validateRegister(item.id, true)">
                                    <AcceptIcon />
                                </button>
                                <button @click="openEditAdditionalModal(item)">
                                    <EditIcon />
                                </button>
                                <button @click="confirmDeleteAdditional(item.id)">
                                    <DeleteIcon />
                                </button>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr class="sticky bottom-0 z-10 text-gray-700">
                    <td class="font-bold border-b border-gray-200 bg-white">
                    </td>
                    <td class="font-bold border-b border-gray-200 bg-white px-5 py-5 text-sm">
                        TOTAL
                    </td>
                    <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm" colspan="9"></td>
                    <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm whitespace-nowrap">
                        S/.
                        {{
                            (expenses.data || expenses)
                                ?.reduce((a, item) => a + item.amount, 0)
                                .toFixed(2)
                        }}
                    </td>
                    <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm whitespace-nowrap">
                        S/.
                        {{
                            (expenses.data || expenses)
                                .reduce(
                                    (a, item) => a + item.real_amount, 0)
                                .toFixed(2)
                        }}
                    </td>
                    <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm" colspan="4"></td>
                </tr>
            </tbody>
        </table>
    </div>

    <div v-if="expenses.data"
        class="flex flex-col items-center border-t bg-white px-5 py-5 xs:flex-row xs:justify-between">
        <pagination :links="expenses.links" />
    </div>
</template>
<script setup>
import TableDateFilter from "@/Components/TableDateFilter.vue";
import Pagination from "@/Components/Pagination.vue";
import TableHeaderFilter from "@/Components/TableHeaderFilter.vue";
import { formattedDate } from "@/utils/utils";
import { ref } from "vue";
import { notify, notifyError } from "@/Components/Notification";
import { DeleteIcon, EditIcon, AcceptIcon, RejectIcon, ShowIcon } from "@/Components/Icons";

const { expenses, filterForm, zones, docTypes, expenseTypes, stateTypes, openEditAdditionalModal, confirmDeleteAdditional } = defineProps({
    expenses: Object,
    filterForm: Object,
    zones: Array,
    docTypes: Array,
    expenseTypes: Array,
    stateTypes: Array,
    openEditAdditionalModal: Function,
    confirmDeleteAdditional: Function
})
const actionForm = defineModel('actionForm')

async function validateRegister(expense_id, is_accepted) {
    const url = route("projectmanagement.pext.expenses.validate", { 'expense_id': expense_id })
    try {
        await axios.put(url, { 'is_accepted': is_accepted });
        if (filterForm.rejected) {
            updateExpense(expense_id, "validate", is_accepted)
        } else {
            updateExpense(expense_id, "rejectedValidate")
        }
    } catch (error) {
        console.log(error);
        if (error.response) {
            notifyError(`Server Error: ${error.response.data}`)
        } else {
            notifyError('Server Error')
        }
    }
}

function handlerPreview(id) {
    const uniqueParam = `timestamp=${new Date().getTime()}`;
    window.open(
        route("projectmanagement.pext.expenses.image.show", { expense_id: id }) +
        "?" +
        uniqueParam,
        "_blank"
    );
}


function updateExpense(expense, action, state) {
    let listDate = expenses.data || expenses
    if (action === "validate") {
        let index = listDate.findIndex(item => item.id == expense)
        if (state) {
            listDate[index].is_accepted = state;
            notify('Gasto Aceptado')
        } else {
            listDate.splice(index, 1);
            notify('Gasto Rechazado')
        }
    } else if (action === "rejectedValidate") {
        let index = listDate.findIndex(item => item.id == expense)
        listDate.splice(index, 1);
        notify('El gasto paso a ser aceptado')
    }
}

const handleCheckAll = (e) => {
    let listDate = expenses.data || expenses
    if (e.target.checked) { actionForm.value.ids = listDate.map((item) => item.id); }
    else { actionForm.value.ids = []; }
};

</script>