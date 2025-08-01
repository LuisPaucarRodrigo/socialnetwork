<template>
    <TableStructure :info="payments" :loading="loading">
        <template #thead>
            <tr>
                <TableTitle>
                    <TableHeaderFilter label="Linea de Negocio" :options="cost_line"
                        v-model="filterForm.selectedCostLine" width="w-40" labelClass="text-[11px]" />
                </TableTitle>
                <TableTitle>
                    <TableHeaderFilter label="Usuarios" :options="user" v-model="filterForm.selectedUser" width="w-40"
                        labelClass="text-[11px]" />
                </TableTitle>
                <TableTitle>
                    <TableHeaderFilter label="Zona" :options="zones" v-model="filterForm.selectedZone" width="w-40"
                        labelClass="text-[11px]" />
                </TableTitle>
                <TableTitle>
                    <TableHeaderFilter label="Bancos" :options="banks" v-model="filterForm.selectedBanks" width="w-40"
                        labelClass="text-[11px]" />
                </TableTitle>
                <TableTitle>Numero de Cuenta</TableTitle>
                <TableTitle>Monto</TableTitle>
                <TableTitle>Ruc</TableTitle>
                <TableTitle>Beneficiario</TableTitle>
                <TableTitle>Documento</TableTitle>
                <TableTitle>Descripción</TableTitle>
                <TableTitle>Motivo de Rechazo</TableTitle>
                <TableTitle>Constancia de Pago</TableTitle>
                <TableTitle>Fecha de ingreso</TableTitle>
                <TableTitle>
                    <TableHeaderFilter label="Estado" :options="states" v-model="filterForm.selectedState" width="w-40"
                        labelClass="text-[11px]" :information="information" />
                </TableTitle>
                <TableTitle v-permission-or="['add_document_payment_approval', 'delete_payment_approval']">
                    Acciones
                </TableTitle>
            </tr>
        </template>
        <template #tbody>
            <tr v-for="item in payments.data || payments">
                <TableRow>{{ item.cost_line.name }}</TableRow>
                <TableRow>{{ item.user?.name }}</TableRow>
                <TableRow>{{ item.zone }}</TableRow>
                <TableRow>{{ item.bank }}</TableRow>
                <TableRow>{{ item.account_number }}</TableRow>
                <TableRow>{{ item.amount }}</TableRow>
                <TableRow>{{ item.ruc }}</TableRow>
                <TableRow>{{ item.beneficiary }}</TableRow>
                <TableRow>
                    <button v-if="item.document" @click="documentPreview(item.id, 'document')">
                        <ShowIcon />
                    </button>
                    <span v-else> - </span>
                </TableRow>
                <TableRow>{{ item.description }}</TableRow>
                <TableRow>{{ item.reason_rejection }}</TableRow>
                <TableRow>
                    <button v-if="item.proof_payment" @click="documentPreview(item.id, 'proof_payment')">
                        <ShowIcon />
                    </button>
                    <span v-else> - </span>
                </TableRow>
                <TableRow>{{ formattedDate(item.created_at) }}</TableRow>
                <TableRow>{{ item.state }}</TableRow>
                <TableRow v-permission-or="['add_document_payment_approval', 'delete_payment_approval']">
                    <div class="flex justify-center gap-x-2">
                        <template v-if="item.is_validated === null" class="flex gap-3 justify-center w-1/2">
                            <button @click="validateRegister(item.id, true)"
                                class="flex items-center rounded-xl hover:bg-green-200">
                                <AcceptIcon />
                            </button>
                            <button @click="openRejectedModal(item.id)"
                                class="flex items-center rounded-xl hover:bg-red-200">
                                <RejectIcon />
                            </button>
                        </template>
                        <template v-if="item.is_validated == '1'">
                            <div class="flex gap-x-2" v-if="item.is_accepted === null && item.proof_payment === null">
                                <button @click="rejectedVericom(item.id)"
                                    class="flex items-center rounded-xl hover:bg-red-200">
                                    <RejectIcon />
                                </button>
                                <button v-permission="'add_document_payment_approval'"
                                    @click="openDocumentModal(item.id)">
                                    <PlusDocumentIcon />
                                </button>
                            </div>
                        </template>
                        <button v-permission="'delete_payment_approval'" @click="confirmPaymentDeletion(item.id)">
                            <DeleteIcon />
                        </button>
                    </div>
                </TableRow>
            </tr>
        </template>
    </TableStructure>
    <div v-if="payments.data"
        class="flex flex-col items-center border-t bg-white px-5 py-5 xs:flex-row xs:justify-between">
        <PaginationAxios :links="payments.links" v-model:loading="loading" v-model:dataToRender="payments" />
    </div>
</template>
<script setup>
import { AcceptIcon, DeleteIcon, PlusDocumentIcon, RejectIcon, ShowIcon } from '@/Components/Icons';
import { notify } from '@/Components/Notification';
import PaginationAxios from '@/Components/PaginationAxios.vue';
import TableHeaderFilter from '@/Components/TableHeaderFilter.vue';
import TableRow from '@/Components/TableRow.vue';
import TableTitle from '@/Components/TableTitle.vue';
import TableStructure from '@/Layouts/TableStructure.vue';
import { formattedDate } from '@/utils/utils';

const { zones, cost_line, states, banks, filterForm, openDocumentModal, confirmPaymentDeletion, openRejectedModal, user } = defineProps({
    zones: Array,
    cost_line: Array,
    states: Array,
    banks: Array,
    filterForm: Object,
    openDocumentModal: Function,
    confirmPaymentDeletion: Function,
    openRejectedModal: Function,
    user: Array
})

const payments = defineModel('payments')
const loading = defineModel('loading')
let information = [
    'Rechazado:Se Rechazo por encargado.',
    'Pendiente:Falta aceptar por encargado.',
    'Programado:Se acepto y programo en teleticket.',
    'Completado:Se pago la programación y se subio constancia.',
    'Rechazado Vericom:Se rechazo en Vericom.'
]


function documentPreview(paymentId, kind) {
    const url = route('payment.approval.show_document', { id: paymentId, kind: kind });
    window.open(url, '_blank');
}

async function validateRegister(paymentId, is_validated) {
    const url = route("payment.approval.validate", { 'id': paymentId })
    try {
        const res = await axios.put(url, { 'is_validated': is_validated });
        updateExpense(res.data, "validate", paymentId)
    } catch (e) {
        console.log(e);
    }
}

async function rejectedVericom(paymentId) {
    const url = route("payment.approval.rejected_vericom", { 'id': paymentId })
    try {
        const res = await axios.get(url);
        updateExpense(res.data, "rejectedVericom", paymentId)
    } catch (e) {
        console.log(e);
    }
}

function updateExpense(data, action, paymentId) {
    const listData = payments.value.data || payments.value
    if (action === 'validate') {
        const index = listData.findIndex(item => item.id === paymentId)
        listData[index] = data
        notify('Validación Exitosa')
    } else if (action === 'rejectedVericom') {
        const index = listData.findIndex(item => item.id === paymentId)
        listData[index] = data
        notify('Rechazo Vericom Exitosa')
    }

}
</script>