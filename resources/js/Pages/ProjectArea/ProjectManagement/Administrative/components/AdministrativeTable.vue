<template>
    <TableStructure :style="'h-[72vh] z-10'" :info="dataToRender" :loading="loading">
        <template #thead>
            <tr>
                <th class="sticky left-0 z-10 bg-gray-100 border-b-2 border-gray-20">
                    <div class="w-2"></div>
                </th>
                <th
                    class="sticky left-2 z-10 border-b-2 border-r border-gray-200 bg-gray-100 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600 w-12">
                    <label :for="`check-all`" class="flex gap-3 justify-center w-full px-2 py-1">
                        <input @change="handleCheckAll" :id="`check-all`" :checked="actionForm.ids.length > 0"
                            type="checkbox" />
                        {{ actionForm.ids.length ?? "" }}
                    </label>
                </th>
                <TableTitle>
                    <TableHeaderFilter labelClass="text-[11px]" label="Zona" :options="zones"
                        v-model="filterForm.selectedZones" width="w-32" />
                </TableTitle>
                <TableTitle>
                    <TableHeaderFilter labelClass="text-[11px]" label="Tipo de Gasto" :options="expenseTypes"
                        v-model="filterForm.selectedExpenseTypes" width="w-44" />
                </TableTitle>
                <TableTitle>
                    <TableHeaderFilter labelClass="text-[11px]" label="Tipo de Documento" :options="docTypes"
                        v-model="filterForm.selectedDocTypes" width="w-32" />
                </TableTitle>
                <TableTitle>RUC</TableTitle>
                <TableTitle>Proveedor</TableTitle>
                <TableTitle>Numero de Operación</TableTitle>
                <TableTitle>
                    <TableDateFilter labelClass="text-[11px]" label="Fecha de Operación"
                        v-model:startDate="filterForm.opStartDate" v-model:endDate="filterForm.opEndDate"
                        v-model:noDate="filterForm.opNoDate" width="w-40" />
                </TableTitle>
                <TableTitle>Numero de Documento</TableTitle>
                <TableTitle>
                    <TableDateFilter labelClass="text-[11px]" label="Fecha de Documento"
                        v-model:startDate="filterForm.docStartDate" v-model:endDate="filterForm.docEndDate"
                        v-model:noDate="filterForm.docNoDate" width="w-40" />
                </TableTitle>
                <TableTitle>Monto Total</TableTitle>
                <TableTitle>Monto sin IGV</TableTitle>
                <TableTitle>Foto de Factura</TableTitle>
                <TableTitle>Descripción</TableTitle>
                <TableTitle>
                    <TableHeaderFilter labelClass="text-[11px]" label="Estado" :options="stateTypes"
                        v-model="filterForm.selectedStateTypes" width="w-48" />
                </TableTitle>
                <TableTitle>Acciones</TableTitle>
            </tr>
        </template>
        <template #tbody>
            <tr v-for="(item, i) in dataToRender" :key="item.id">
                <td :class="[
                    'sticky left-0 z-10 border-b border-gray-200 ',
                    {
                        'bg-indigo-500': item.real_state === 'Pendiente',
                        'bg-green-500': item.real_state == 'Aceptado - Validado',
                        'bg-amber-500': item.real_state == 'Aceptado',
                        'bg-red-500': item.real_state == 'Rechazado',

                    },
                ]"></td>
                <td
                    class="sticky left-2 z-10 border-b border-r border-gray-200 bg-amber-100 text-center text-[13px] whitespace-nowrap tabular-nums">
                    <label :for="`check-${item.id}`" class="block w-full px-2 py-1">
                        <input v-model="actionForm.ids" :value="item.id" :id="`check-${item.id}`" type="checkbox" />
                    </label>
                </td>
                <TableRow>{{ item.zone }}</TableRow>
                <TableRow>{{ item.expense_type }}</TableRow>
                <TableRow>{{ item.type_doc }}</TableRow>
                <TableRow>{{ item.ruc }}</TableRow>
                <TableRow>{{ item?.provider?.company_name }}</TableRow>
                <TableRow>{{ item.operation_number }}</TableRow>
                <TableRow>{{ item.operation_date && formattedDate(item.operation_date) }}</TableRow>
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
                <TableRow width="w-[250px]">{{ item.description }}</TableRow>
                <TableRow>
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
                </TableRow>
                <TableRow>
                    <div class="flex items-center">
                        <button @click="openEditAdditionalModal(item)">
                            <EditIcon />
                        </button>
                        <button @click="confirmDeleteAdditional(item.id)">
                            <DeleteIcon />
                        </button>
                    </div>
                </TableRow>
            </tr>
            <tr class="sticky bottom-0 z-10 text-gray-700">
                <td class="border-b border-gray-200 bg-white text-sm"></td>
                <td colspan="10" class="font-bold border-b border-gray-200 bg-white px-5 py-5 text-sm">
                    TOTAL
                </td>
                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm whitespace-nowrap">
                    S/.
                    {{
                        dataToRender
                            .reduce((a, item) => a + item.amount, 0)
                            .toFixed(2)
                    }}
                </td>
                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm whitespace-nowrap">
                    S/.
                    {{
                        dataToRender
                            .reduce(
                                (a, item) => a + item.real_amount,
                                0
                            )
                            .toFixed(2)
                    }}
                </td>
                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm"></td>
                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm"></td>
                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm"></td>
                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                    <div class="flex items-center"></div>
                </td>
            </tr>
        </template>
    </TableStructure>
    <div v-if="!filterMode"
        class="flex flex-col items-center border-t bg-white px-5 py-5 xs:flex-row xs:justify-between">
        <PaginationAxios :links="listOfData.links" @navigate="fetchExpensesByUrl" />
    </div>
</template>
<script setup>
import { DeleteIcon, EditIcon, ShowIcon } from '@/Components/Icons';
import PaginationAxios from '@/Components/PaginationAxios.vue';
import TableDateFilter from '@/Components/TableDateFilter.vue';
import TableHeaderFilter from '@/Components/TableHeaderFilter.vue';
import TableRow from '@/Components/TableRow.vue';
import TableTitle from '@/Components/TableTitle.vue';
import TableStructure from '@/Layouts/TableStructure.vue';
import { formattedDate } from '@/utils/utils';

const { listOfData, actionForm, filterForm, filterMode, openEditAdditionalModal, confirmDeleteAdditional, expenseTypes, docTypes, zones, stateTypes } = defineProps({
    listOfData: Object,
    actionForm: Object,
    filterForm: Object,
    filterMode: Boolean,
    openEditAdditionalModal: Function,
    confirmDeleteAdditional: Function,
    expenseTypes: Array,
    docTypes: Array,
    zones: Array,
    stateTypes: Array,
})

const loading = defineModel('loading')
const dataToRender = defineModel('dataToRender')

const handleCheckAll = (e) => {
    if (e.target.checked) {
        actionForm.ids = dataToRender.value.map((item) => item.id);
    } else {
        actionForm.ids = [];
    }
};

function handlerPreview(id) {
    const uniqueParam = `timestamp=${new Date().getTime()}`;
    window.open(
        route("administrativeCosts.archive", { static_cost_id: id }) +
        "?" +
        uniqueParam,
        "_blank"
    );
}

async function fetchExpensesByUrl(url) {
    loading.value = true;
    try {
        const res = await axios.get(url);
        dataToRender.value = res.data.data;
    } catch (err) {
        console.error(err);
    } finally {
        loading.value = false;
    }
}
</script>