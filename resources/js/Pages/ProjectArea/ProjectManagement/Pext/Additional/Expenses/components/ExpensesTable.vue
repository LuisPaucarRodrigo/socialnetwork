<template>
    <div class="overflow-x-auto h-[85vh]">
        <div class="mb-4">
            <ChartsAdditionalExpenses :project_id="Number(project_id)" :reload_flag="reload_flag" />
        </div>
        <table class="w-full">
            <thead class="sticky top-0 z-20">
                <tr
                    class=" border-b bg-gray-50 text-center text-xs font-semibold uppercase tracking-wide text-gray-500">

                    <th class="bg-gray-100 border-b-2 border-gray-20">
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
                            <TableHeaderFilter labelClass="text-[11px]" label="Zona" :options="zones"
                                v-model="filterForm.selectedZones" width="w-40" />
                        </th> -->
                    <th
                        class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600 ">
                        <TableHeaderFilter labelClass="text-[11px]" label="Tipo de Gasto" :options="expenseTypes"
                            v-model="filterForm.selectedExpenseTypes" width="w-40" />
                    </th>
                    <th
                        class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600">
                        <TableHeaderFilter labelClass="text-[11px]" label="Tipo de Documento" :options="documentsType"
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
                    <!-- <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600">
                            Fecha de Operacion
                        </th> -->
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
                    <!-- <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600">
                            Fecha de Documento
                        </th> -->
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

                    </th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="item in expenses.data || expenses" :key="item.id" class="text-gray-700">
                    <td :class="[
                        'sticky left-0 z-10 border-b border-gray-200',
                        {
                            'bg-indigo-500': item.real_state === 'Pendiente',
                            'bg-green-500': item.real_state == 'Aceptado - Validado',
                            'bg-amber-500': item.real_state == 'Aceptado',
                            'bg-red-500': item.real_state == 'Rechazado',
                        },
                    ]"></td>
                    <!-- <td class="border-b border-gray-200 bg-white px-2 py-2 text-center text-[13px]">
                            {{ item.zone }}
                        </td> -->
                    <td
                        class="sticky left-2 z-10 border-b border-r border-gray-200 bg-amber-100 text-center text-[13px] whitespace-nowrap tabular-nums">
                        <label :for="`check-${item.id}`" class="block w-12 px-2 py-1">
                            <input v-model="actionForm.ids" :value="item.id" :id="`check-${item.id}`" type="checkbox" />
                        </label>
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
                        {{ item.operation_date }}
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
                        <div v-if="item.is_accepted === null" class="flex gap-3 justify-center w-1/2">
                            <button @click="() =>
                                validateRegister(item.id, true)
                            " class="flex items-center rounded-xl text-blue-500 hover:bg-green-200">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-green-500">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                            </button>
                            <button @click="() =>
                                validateRegister(item.id, false)
                            " type="button"
                                class="rounded-xl whitespace-no-wrap text-center text-sm text-red-900 hover:bg-red-200">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-red-500">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                            </button>
                        </div>
                    </td>
                </tr>
                <tr class="sticky bottom-0 z-10 text-gray-700">
                    <td class="font-bold border-b border-gray-200 bg-white">
                    </td>
                    <td class="font-bold border-b border-gray-200 bg-white px-5 py-5 text-sm">
                        TOTAL
                    </td>
                    <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm" colspan="8"></td>
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
        <Pagination :links="expenses.links" />
    </div>
</template>
<script setup>
import { ShowIcon } from '@/Components/Icons';
import Pagination from '@/Components/Pagination.vue';
import TableDateFilter from '@/Components/TableDateFilter.vue';
import TableHeaderFilter from '@/Components/TableHeaderFilter.vue';
import ChartsAdditionalExpenses from '@/Pages/ProjectArea/ProjectManagement/ChartsAdditionalExpenses.vue';
import { formattedDate } from '@/utils/utils';
import { ref } from 'vue';

const { expenses, filterForm, actionForm, expenseTypes, documentsType, stateTypes } = defineProps({
    expenses: Object,
    filterForm: Object,
    actionForm: Object,
    project_id: String,
    expenseTypes: Array,
    documentsType: Array,
    stateTypes: Array
})

const reload_flag = ref(true)

async function validateRegister(expense_id, is_accepted) {
    const url = route("projectmanagement.pext.expenses.validate", { 'expense_id': expense_id })
    try {
        await axios.put(url, { 'is_accepted': is_accepted });
        if (filterForm.value.rejected) {
            updateExpense(expense_id, "validate", is_accepted)
        } else {
            updateExpense(expense_id, "rejectedValidate")
        }
    } catch (e) {
        console.log(e);
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

const handleCheckAll = (e) => {
    const data = expenses.data || expenses
    if (e.target.checked) {
        actionForm.ids = data.map(item => item.id);
    }
    else {
        actionForm.ids = [];
    }
};
</script>