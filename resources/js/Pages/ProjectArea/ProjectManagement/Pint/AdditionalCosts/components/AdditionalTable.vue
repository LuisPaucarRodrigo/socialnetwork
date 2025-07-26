<template>
    <div class="overflow-x-auto h-[72vh]">
        <table class="w-full bg-white">
            <thead class="sticky top-0 z-20">
                <tr class="border-b bg-gray-50 text-center text-xs font-semibold uppercase tracking-wide text-gray-500">
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
                        class="sm:sticky sm:left-14 sm:z-10 border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600">
                        <TableHeaderFilter labelClass="text-[11px]" label="Zona" :options="zones"
                            v-model="filterForm.selectedZones" width="w-32" />
                    </th>
                    <th
                        class="sm:sticky sm:left-48 sm:z-10 border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600">
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
                        RUC
                    </th>
                    <th
                        class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600">
                        Proveedor
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
                                <SortIcon />
                            </button>
                        </div>

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
                        <p class="w-48">
                            Estado Administrativo
                        </p>
                    </th>
                    <th
                        class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600">
                        Motivo de rechazo Administrativo
                    </th>

                    <th v-permission-or="['pro_pint_delete_additional_costs', 'pro_pint_mod_additional_costs']"
                        v-if="project_id.status === null"
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
                            <input v-model="actionForm.ids" :value="item.id" :id="`check-${item.id}`" type="checkbox" />
                        </label>
                    </td>
                    <td
                        class="sm:sticky sm:left-14 sm:z-10 border-b w-32 border-gray-200 bg-amber-100 px-2 py-2 text-center text-[13px]">
                        {{ item.zone }}
                    </td>
                    <td
                        class="sm:sticky sm:left-48 sm:z-10 border-b border-gray-200 bg-amber-100 px-2 py-2 text-center text-[13px]">
                        <p class="w-48 break-words">
                            {{ item.expense_type }}
                        </p>
                    </td>
                    <td class="border-b border-gray-200 px-2 py-2 text-center text-[13px]">
                        {{ item.type_doc }}
                    </td>
                    <td class="border-b border-gray-200 px-2 py-2 text-center text-[13px] tabular-nums">
                        {{ item.ruc }}
                    </td>
                    <td class="border-b border-gray-200 px-2 py-2 text-center text-[13px]">
                        <p class="line-clamp-2 hover:line-clamp-none">
                            {{ item?.provider?.company_name }}
                        </p>
                    </td>
                    <td class="border-b border-gray-200 px-2 py-2 text-center text-[13px]">
                        {{ item.operation_number }}
                    </td>
                    <td class="border-b border-gray-200 px-2 py-2 text-center text-[13px]">
                        {{
                            item.operation_date &&
                            formattedDate(item.operation_date)
                        }}
                    </td>
                    <td
                        class="border-b border-gray-200 px-2 py-2 text-center text-[13px] tabular-nums whitespace-nowrap">
                        {{ item.doc_number }}
                    </td>
                    <td class="border-b border-gray-200 px-2 py-2 text-center text-[13px]">
                        {{ formattedDate(item.doc_date) }}
                    </td>
                    <td class="border-b border-gray-200 px-2 py-2 text-center text-[13px]">
                        {{ formattedDate(item.created_at) }}
                    </td>
                    <td
                        class="border-b border-gray-200 px-2 py-2 text-center text-[13px] tabular-nums whitespace-nowrap">
                        S/. {{ item.amount.toFixed(2) }}
                    </td>
                    <td
                        class="border-b border-gray-200 px-2 py-2 text-center text-[13px] tabular-nums whitespace-nowrap">
                        S/. {{ item.real_amount.toFixed(2) }}
                    </td>
                    <td class="border-b border-gray-200 px-2 py-2 text-center text-[13px]">
                        <button v-if="item.photo" @click="handlerPreview(item.id)">
                            <ShowIcon />
                        </button>
                        <span v-else>-</span>
                    </td>
                    <td class="border-b border-gray-200 px-2 py-2 text-center text-[13px]">
                        <p class="w-[250px]">
                            {{ item.description }}
                        </p>
                    </td>
                    <td class="border-b border-gray-200 px-2 py-2 text-center text-[13px]">
                        <div v-permission="'pro_pint_validate_expenses'">
                            <div v-if="item.is_accepted === null">
                                <div v-if="registerToReject.id !== item.id" class="flex gap-3 justify-center w-full">
                                    <button @click="() => openAcceptModal(item)">
                                        <AcceptIcon />
                                    </button>
                                    <button @click="displayRejectReason(item.id)" type="button">
                                        <RejectIcon />
                                    </button>
                                </div>
                                <AdditionalCostsRejectForm v-else v-model:item="registerToReject.reject_reason"
                                    :closeRejectReason="closeRejectReason" :saveRejectReason="saveRejectReason" />
                            </div>

                            <div v-else :class="[
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
                        </div>
                        <div v-permission-not="'pro_pint_validate_expenses'" :class="[
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
                        <div v-permission="'pro_pint_admin_validate_expenses'">
                            <div v-if="item.admin_is_accepted === null && item.is_accepted === 1">
                                <div v-if="regToAdminReject.id !== item.id" class="flex gap-3 justify-center w-full">
                                    <button @click="() => displayAdminAccept(item.id)">
                                        <AcceptIcon />
                                    </button>
                                    <button @click="() => displayAdminRejectReason(item.id)" type="button">
                                        <RejectIcon />
                                    </button>

                                </div>
                                <AdditionalCostsRejectForm v-else v-model:item="regToAdminReject.admin_reject_reason"
                                    :closeRejectReason="closeAdminRejectReason" :saveRejectReason="saveAdminReject" />
                            </div>

                            <div v-else :class="[
                                'text-center',
                                {
                                    'text-indigo-500': item.admin_state === 'Pendiente',
                                    'text-gray-600': item.admin_state === 'No Disponible',
                                    'text-green-500': item.admin_state == 'Aceptado',
                                    'text-red-500': item.admin_state == 'Rechazado',
                                },
                            ]">
                                {{ item.admin_state }}
                            </div>
                        </div>
                        <div v-permission-not="'pro_pint_admin_validate_expenses'" :class="[
                            'text-center',
                            {
                                'text-indigo-500': item.admin_state === 'Pendiente',
                                'text-gray-600': item.admin_state === 'No Disponible',
                                'text-green-500': item.admin_state == 'Aceptado',
                                'text-red-500': item.admin_state == 'Rechazado',
                            },
                        ]">
                            {{ item.admin_state }}
                        </div>
                    </td>

                    <td
                        class="border-b border-gray-200 px-2 py-2 text-center text-[13px] tabular-nums whitespace-nowrap">
                        {{ item.admin_reject_reason }}
                    </td>


                    <td v-permission-or="[
                        'pro_pint_mod_additional_costs', 'pro_pint_delete_additional_costs'
                    ]" v-if="project_id.status === null"
                        class="border-b border-gray-200 px-2 py-2 text-center text-[13px]">
                        <div class="flex items-center justify-center gap-3 w-full">
                            <div class="flex gap-3 mr-3">
                                <button v-permission="'pro_pint_mod_additional_costs'"
                                    @click="openEditAdditionalModal(item)">
                                    <EditIcon />
                                </button>
                                <button v-permission="'pro_pint_delete_additional_costs'"
                                    @click="confirmDeleteAdditional(item.id)">
                                    <DeleteIcon />
                                </button>
                            </div>
                        </div>
                    </td>

                </tr>


                <tr class="sticky bottom-0 z-10 text-gray-700">
                    <td class="font-bold border-b border-gray-200 bg-white"></td>
                    <td colspan="10" class="font-bold border-b border-gray-200 bg-white px-5 py-5 text-sm">
                        TOTAL
                    </td>
                    <td class="font-bold border-b border-gray-200 bg-white"></td>
                    <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm whitespace-nowrap">
                        S/.
                        {{
                            dataToRender
                                ?.reduce((a, item) => a + item.amount, 0)
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
                    <td colspan="3" class="border-b border-gray-200 bg-white px-5 py-5 text-sm"></td>

                    <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                        <div class="flex items-center"></div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div v-if="!filterMode"
        class="flex flex-col items-center border-t bg-white px-5 py-5 xs:flex-row xs:justify-between">
        <PaginationAxios :links="listOfData.links" @navigate="fetchExpensesByUrl" />
    </div>
</template>
<script setup>
import { AcceptIcon, DeleteIcon, EditIcon, RejectIcon, ShowIcon, SortIcon } from '@/Components/Icons';
import PaginationAxios from '@/Components/PaginationAxios.vue';
import TableDateFilter from '@/Components/TableDateFilter.vue';
import TableHeaderFilter from '@/Components/TableHeaderFilter.vue';
import { formattedDate } from '@/utils/utils';
import AdditionalCostsRejectForm from '../../components/AdditionalCostsRejectForm.vue';
import { ref } from 'vue';
import { notify, notifyError } from '@/Components/Notification';

const { listOfData, actionForm, filterForm, filterMode, openEditAdditionalModal, confirmDeleteAdditional, expenseTypes, docTypes, zones, stateTypes, project_id, openAcceptModal } = defineProps({
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
    project_id: Object,
    openAcceptModal: Function
})

const loading = defineModel('loading')
const dataToRender = defineModel('dataToRender')

const adminRejectInitState = { id: null, admin_reject_reason: '', admin_is_accepted: null }
const regToAdminReject = ref({ ...adminRejectInitState })

const stateCreateAtSort = ref(false)

const handleCheckAll = (e) => {
    if (e.target.checked) { actionForm.ids = dataToRender.value.map((item) => item.id); }
    else { actionForm.ids = []; }
};

function handlerPreview(id) {
    const uniqueParam = `timestamp=${new Date().getTime()}`;
    window.open(
        route("additionalcost.archive", { additional_cost_id: id }) +
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

function closeAdminRejectReason() {
    regToAdminReject.value = { ...adminRejectInitState }
}

function displayAdminRejectReason(id) {
    closeAdminRejectReason()
    regToAdminReject.value.id = id
    regToAdminReject.value.admin_is_accepted = 0
}

function displayAdminAccept(id) {
    const payload = { id, admin_is_accepted: 1, admin_reject_reason: '' }
    saveAdminValidate(payload)
}

async function saveAdminReject() {
    await saveAdminValidate(regToAdminReject.value)
}

async function saveAdminValidate(payload) {
    try {
        const res = await axios.post(route('projectmanagement.administrative.validation', { ac_id: payload.id }), payload)
        let index = dataToRender.value.findIndex((item) => item.id == res.data.additional_cost.id);
        dataToRender.value[index] = res.data.additional_cost;
        notify(res.data.msg)
        closeAdminRejectReason()
    } catch (e) {
        console.log(e)
        notifyError('Server Error')
    }
}

//Reject modal

const rejectInitState = { id: null, reject_reason: '', }
const registerToReject = ref({ ...rejectInitState })

function displayRejectReason(id) {
    closeRejectReason()
    registerToReject.value.id = id
}
function closeRejectReason() {
    registerToReject.value = { ...rejectInitState }
}

async function saveRejectReason() {
    try {
        const res = await axios.post(
            route("projectmanagement.rejectAdditionalCost", { ac_id: registerToReject.value.id }),
            { is_accepted: false, ...registerToReject.value }
        );
        let index = dataToRender.value.findIndex(
            (item) => item.id == res.data.additional_cost.id
        );
        dataToRender.value.splice(index, 1);
        notify(res.data.msg)
        closeRejectReason()
    } catch (e) {
        console.log(e)
        notifyError('Server Error')
    }
}

function sortValue() {
    if (stateCreateAtSort.value) {
        dataToRender.value.sort((a, b) => new Date(a.created_at) - new Date(b.created_at));
    } else {
        dataToRender.value.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
    }
    stateCreateAtSort.value = !stateCreateAtSort.value;
}
</script>