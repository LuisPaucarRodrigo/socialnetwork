<template>
    <div class="overflow-x-auto h-[85vh]">
        <!-- <div class="mb-4">
            <ChartsAdditionalExpenses :acExpensesAmounts="acExpensesAmounts" :scExpensesAmounts="scExpensesAmounts" />
        </div> -->
        <TableStructure :info="expenses" :style="'h-[85vh]'">
            <template #thead>
                <tr>
                    <th class="bg-gray-100 border-b-2 border-gray-20 sticky left-0 z-10">
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
                    <TableTitle>
                        <TableHeaderFilter label="Zona" :options="zones" v-model="filterForm.selectedZones" width="w-40"
                            labelClass="text-[11px]" />
                    </TableTitle>
                    <TableTitle>
                        <TableHeaderFilter label="Tipo de Gasto" :options="expenseTypes"
                            v-model="filterForm.selectedExpenseTypes" width="w-40" labelClass="text-[11px]" />
                    </TableTitle>
                    <TableTitle>
                        <TableHeaderFilter label="Tipo de Documento" :options="documentsType"
                            v-model="filterForm.selectedDocTypes" width="w-40" labelClass="text-[11px]" />
                    </TableTitle>

                    <TableTitle>RUC</TableTitle>
                    <TableTitle>Proveedor</TableTitle>
                    <TableTitle>Numero de Operacion</TableTitle>

                    <TableTitle>
                        <TableDateFilter label="Fecha de Operación" v-model:startDate="filterForm.opStartDate"
                            v-model:endDate="filterForm.opEndDate" v-model:noDate="filterForm.opNoDate" width="w-40"
                            labelClass="text-[11px]" />
                    </TableTitle>
                    <TableTitle>Numero de Doc</TableTitle>
                    <TableTitle>
                        <TableDateFilter label="Fecha de Documento" v-model:startDate="filterForm.docStartDate"
                            v-model:endDate="filterForm.docEndDate" v-model:noDate="filterForm.docNoDate" width="w-40"
                            labelClass="text-[11px]" />
                    </TableTitle>
                    <TableTitle>Monto</TableTitle>
                    <TableTitle>Monto sin IGV</TableTitle>
                    <TableTitle>Archivo</TableTitle>
                    <TableTitle>Descripción</TableTitle>
                    <TableTitle>
                        <TableHeaderFilter label="Estado" :options="stateTypes" v-model="filterForm.selectedStateTypes"
                            width="w-48" labelClass="text-[11px]" />
                    </TableTitle>
                    <TableTitle>Acciones</TableTitle>
                </tr>
            </template>

            <template #tbody>
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

                    <td
                        class="sticky left-2 z-10 border-b border-r border-gray-200 bg-amber-100 text-center text-[13px] whitespace-nowrap tabular-nums">
                        <label :for="`check-${item.id}`" class="block w-12 px-2 py-1">
                            <input v-model="actionForm.ids" :value="item.id" :id="`check-${item.id}`" type="checkbox" />
                        </label>
                    </td>

                    <TableRow>{{ item.zone }}</TableRow>
                    <TableRow>
                        <p class="w-48 break-words">{{ item.expense_type }}</p>
                    </TableRow>
                    <TableRow>{{ item.type_doc }}</TableRow>
                    <TableRow>{{ item.ruc }}</TableRow>
                    <TableRow>{{ item?.provider?.company_name }}</TableRow>
                    <TableRow>{{ item.operation_number }}</TableRow>
                    <TableRow>{{ item.operation_date }}</TableRow>
                    <TableRow>{{ item.doc_number }}</TableRow>
                    <TableRow>{{ formattedDate(item.doc_date) }}</TableRow>
                    <TableRow>S/. {{ item.amount.toFixed(2) }}</TableRow>
                    <TableRow>S/. {{ item.real_amount.toFixed(2) }}</TableRow>
                    <TableRow>
                        <button v-if="item.photo" @click="handlerPreview(item.id)">
                            <ShowIcon />
                        </button>
                        <span v-else>-</span>
                    </TableRow>
                    <TableRow :width="'w-[400px]'">
                        {{ item.description }}
                    </TableRow>
                    <TableRow>
                        <div :class="[
                            'text-center',
                            {
                                'text-indigo-500': item.real_state === 'Pendiente',
                                'text-green-500': item.real_state == 'Aceptado - Validado',
                                'text-amber-500': item.real_state == 'Aceptado',
                                'text-red-500': item.real_state == 'Rechazado',
                            }
                        ]">
                            {{ item.real_state }}
                        </div>
                    </TableRow>
                    <TableRow>
                        <div class="flex items-center gap-3 w-full">
                            <div v-if="item.is_accepted === null" class="flex gap-3 justify-center w-1/2">
                                <button @click="() => validateRegister(item.id, true)">
                                    <AcceptIcon />
                                </button>
                                <button @click="() => validateRegister(item.id, false)">
                                    <RejectIcon />
                                </button>
                            </div>
                            <div v-else class="w-1/2"></div>
                            <div class="flex gap-3 mr-3">
                                <button v-if="!filterForm.rejected" @click="() => validateRegister(item.id, true)">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M9 8.25H7.5a2.25 2.25 0 0 0-2.25 2.25v9a2.25 2.25 0 0 0 2.25 2.25h9a2.25 2.25 0 0 0 2.25-2.25v-9a2.25 2.25 0 0 0-2.25-2.25H15m0-3-3-3m0 0-3 3m3-3V15" />
                                    </svg>
                                </button>
                                <button @click="openEditAdditionalModal(item)">
                                    <EditIcon />
                                </button>
                                <button @click="confirmDeleteAdditional(item.id)">
                                    <DeleteIcon />
                                </button>
                            </div>
                        </div>
                    </TableRow>
                </tr>

                <!-- Fila de totales -->
                <tr class="sticky bottom-0 z-10 text-gray-700">
                    <td colspan="3" class="font-bold border-b border-gray-200 bg-white px-5 py-5 text-sm">TOTAL</td>
                    <td colspan="8" class="border-b border-gray-200 bg-white px-5 py-5 text-sm"></td>
                    <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm whitespace-nowrap">
                        S/. {{
                            (expenses.data || expenses)?.reduce((a, item) => a + item.amount, 0).toFixed(2)
                        }}
                    </td>
                    <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm whitespace-nowrap">
                        S/. {{
                            (expenses.data || expenses)?.reduce((a, item) => a + item.real_amount, 0).toFixed(2)
                        }}
                    </td>
                    <td colspan="4" class="border-b border-gray-200 bg-white px-5 py-5 text-sm"></td>
                </tr>
            </template>
        </TableStructure>
    </div>

    <div v-if="expenses.data"
        class="flex flex-col items-center border-t bg-white px-5 py-5 xs:flex-row xs:justify-between">
        <PaginationAxios :links="expenses.links" @navigate="fetchExpensesByUrl" />
    </div>
</template>
<script setup>
import TableHeaderFilter from '@/Components/TableHeaderFilter.vue';
// import ChartsAdditionalExpenses from '../../ChartsAdditionalExpenses.vue';
import TableDateFilter from '@/Components/TableDateFilter.vue';
import { AcceptIcon, DeleteIcon, EditIcon, RejectIcon, ShowIcon } from '@/Components/Icons';
import { formattedDate } from "@/utils/utils";
import { notify } from '@/Components/Notification';
import PaginationAxios from '@/Components/PaginationAxios.vue';
import TableStructure from '@/Layouts/TableStructure.vue';
import TableTitle from '@/Components/TableTitle.vue';
import TableRow from '@/Components/TableRow.vue';

const { openEditAdditionalModal, confirmDeleteAdditional, filterForm, zones, expenseTypes, documentsType, stateTypes, acExpensesAmounts, scExpensesAmounts } = defineProps({
    openEditAdditionalModal: Function,
    confirmDeleteAdditional: Function,
    filterForm: Object,
    zones: Array,
    expenseTypes: Array,
    documentsType: Array,
    stateTypes: Array,
    acExpensesAmounts: Array,
    scExpensesAmounts: Array,
})
const actionForm = defineModel('actionForm')
const expenses = defineModel('expenses')
const loading = defineModel('loading')


async function validateRegister(expense_id, is_accepted) {
    const url = route("projectmanagement.pext.expenses.validate", { 'expense_id': expense_id })
    try {
        await axios.put(url, { 'is_accepted': is_accepted });
        if (filterForm.rejected) {
            updateExpense(expense_id, "validate", is_accepted)
        } else {
            updateExpense(expense_id, "rejectedValidate")
        }
    } catch (e) {
        console.log(e);
    }
}

const handleCheckAll = (e) => {
    let listDate = expenses.value.data || expenses.value
    if (e.target.checked) { actionForm.value.ids = listDate.map((item) => item.id); }
    else { actionForm.value.ids = []; }
};

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

async function fetchExpensesByUrl(url) {
    loading.value = true;
    try {
        const res = await axios.get(url);
        expenses.value = res.data;
    } catch (err) {
        console.error(err);
    } finally {
        loading.value = false;
    }
}

</script>