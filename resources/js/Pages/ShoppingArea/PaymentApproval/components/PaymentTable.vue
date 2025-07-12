<template>
    <TableStructure :info="payments" :loading="loading">
        <template #thead>
            <tr>
                <TableTitle>
                    <TableHeaderFilter label="Linea de Negocio" :options="cost_line"
                        v-model="filterForm.selectedCostLine" width="w-40" labelClass="text-[11px]" />
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
                <TableTitle>
                    <TableHeaderFilter label="Estado" :options="states" v-model="filterForm.selectedState" width="w-40"
                        labelClass="text-[11px]" />
                </TableTitle>
                <TableTitle v-permission-or="['add_document_payment_approval', 'delete_payment_approval']">
                    Acciones
                </TableTitle>
            </tr>
        </template>
        <template #tbody>
            <tr v-for="item in payments.data || payments">
                <TableRow>{{ item.cost_line.name }}</TableRow>
                <TableRow>{{ item.zone }}</TableRow>
                <TableRow>{{ item.bank }}</TableRow>
                <TableRow>{{ item.account_number }}</TableRow>
                <TableRow>{{ item.amount }}</TableRow>
                <TableRow>{{ item.ruc }}</TableRow>
                <TableRow>{{ item.beneficiary }}</TableRow>
                <TableRow>
                    <button v-if="item.document" @click="documentPreview(item.id)">
                        <ShowIcon />
                    </button>
                    <span v-else> - </span>
                </TableRow>
                <TableRow>{{ item.description }}</TableRow>
                <TableRow>{{ item.state }}</TableRow>
                <TableRow v-permission-or="['add_document_payment_approval', 'delete_payment_approval']">
                    <div class="flex justify-center gap-x-2">
                        <!-- <button>
                            <RejectIcon />
                        </button> -->
                        <button v-permission="'add_document_payment_approval'" v-if="!item.document"
                            @click="openDocumentModal(item.id)">
                            <PlusDocumentIcon />
                        </button>
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
        <PaginationAxios :links="payments.links" @navigate="fetchExpensesByUrl" />
    </div>
</template>
<script setup>
import { DeleteIcon, PlusDocumentIcon, ShowIcon } from '@/Components/Icons';
import PaginationAxios from '@/Components/PaginationAxios.vue';
import TableHeaderFilter from '@/Components/TableHeaderFilter.vue';
import TableRow from '@/Components/TableRow.vue';
import TableTitle from '@/Components/TableTitle.vue';
import TableStructure from '@/Layouts/TableStructure.vue';

const { zones, cost_line, states, banks, filterForm, openDocumentModal, confirmPaymentDeletion } = defineProps({
    zones: Array,
    cost_line: Array,
    states: Array,
    banks: Array,
    filterForm: Object,
    openDocumentModal: Function,
    confirmPaymentDeletion: Function
})

const payments = defineModel('payments')
const loading = defineModel('loading')

async function fetchExpensesByUrl(url) {
    loading.value = true;
    try {
        const res = await axios.get(url);
        payments.value = res.data;
    } catch (err) {
        console.error(err);
    } finally {
        loading.value = false;
    }
}

function documentPreview(paymentId) {
    const url = route('payment.approval.show_document', { id: paymentId });
    window.open(url, '_blank');
}
</script>