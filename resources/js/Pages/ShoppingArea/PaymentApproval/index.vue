<template>

    <Head title="Proveedores" />
    <AuthenticatedLayout :redirectRoute="'payment.approval.index'">
        <template #header>
            Programación de Pagos
        </template>
        <Toaster richColors />
        <TableHeader :createPayment="createPayment" :filterForm="filterForm" />
        <PaymentTable v-model:payments="payments" :filterForm="filterForm" :openDocumentModal="openDocumentModal"
            :cost_line="cost_line" :zones="zones" :banks="banks" :states="states" v-model:loading="loading"
            :confirmPaymentDeletion="confirmPaymentDeletion" :openRejectedModal="openRejectedModal" />

        <SuspenseWrapper :when="showPaymentsForm">
            <template #component>
                <PaymentsForm ref="paymentsForm" :payments="payments" :zones="zones" :banks="banks"
                    :costLines="costLines" :providers="providers" />
            </template>
        </SuspenseWrapper>

        <SuspenseWrapper :when="showDocumentForm">
            <template #component>
                <ProofPaymentForm ref="documentForm" :payments="payments" />
            </template>
        </SuspenseWrapper>

        <SuspenseWrapper :when="showConfirmPaymentDelete">
            <template #component>
                <ConfirmPaymentDelete ref="confirmPaymentDelete" :payments="payments" />
            </template>
        </SuspenseWrapper>

        <SuspenseWrapper :when="showRejection">
            <template #component>
                <Rejection ref="rejection" :payments="payments" />
            </template>
        </SuspenseWrapper>
    </AuthenticatedLayout>
</template>
<script setup>
import { defineAsyncComponent, onMounted, ref, watch } from 'vue';
import PaymentTable from './components/PaymentTable.vue';
import TableHeader from './components/TableHeader.vue';
import SuspenseWrapper from '@/Components/SuspenseWrapper.vue';
import { useLazyRefInvoker } from '@/utils/useLazyRefInvoker';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Toaster } from 'vue-sonner';
import { Head } from '@inertiajs/vue3';

const PaymentsForm = defineAsyncComponent(() => import('./components/PaymentsForm.vue'));
const ProofPaymentForm = defineAsyncComponent(() => import('./components/ProofPaymentForm.vue'));
const ConfirmPaymentDelete = defineAsyncComponent(() => import('./components/ConfirmPaymentDelete.vue'));
const Rejection = defineAsyncComponent(() => import('./components/Rejection.vue'));

const showRejection = ref(false)
const rejection = ref(null)

const showPaymentsForm = ref(false)
const paymentsForm = ref(null)

const showDocumentForm = ref(false)
const documentForm = ref(null)

const showConfirmPaymentDelete = ref(false)
const confirmPaymentDelete = ref(null)

const loading = ref(true)

const { invokeWhenReady: invokePaymentsForm } = useLazyRefInvoker(paymentsForm, showPaymentsForm);
const { invokeWhenReady: invokeDocumentForm } = useLazyRefInvoker(documentForm, showDocumentForm);
const { invokeWhenReady: invokeConfirmPaymentDelete } = useLazyRefInvoker(confirmPaymentDelete, showConfirmPaymentDelete);
const { invokeWhenReady: invokeRejection } = useLazyRefInvoker(rejection, showRejection);

const { zones, costLines, providers, banks } = defineProps({
    zones: Array,
    costLines: Object,
    providers: Object,
    banks: Array
})

const cost_line = costLines.map(item => item.name)
const states = ['Rechazado', 'Pendiente', 'En Proceso', 'Completado']
const payments = ref([])

onMounted(async () => {
    const res = await axios.get(route('payment.approval.getPaymentApproval'));
    payments.value = res.data;
    loading.value = false;
});

const filterForm = ref({
    selectedCostLine: [...cost_line],
    selectedZone: [...zones],
    selectedState: [...states],
    selectedBanks: [...banks],
    searchQuery: ""
})

watch(
    () => [
        filterForm.value.selectedCostLine,
        filterForm.value.selectedZone,
        filterForm.value.selectedState,
        filterForm.value.selectedBanks,
        filterForm.value.searchQuery,
    ], () => searchAdvance()
)

async function searchAdvance() {
    try {
        let url = route('payment.approval.searchPaymentApproval')
        let res = await axios.post(url, filterForm.value)
        payments.value = res.data
    } catch (error) {
        console.error(error)
    }
}

function createPayment() {
    invokePaymentsForm('openPaymentModal')
}

function openDocumentModal(paymentId) {
    invokeDocumentForm('openDocumentModal', paymentId)
}

function confirmPaymentDeletion(paymentId) {
    invokeConfirmPaymentDelete('confirmPaymentDeletion', paymentId)
}

function openRejectedModal(paymentId) {
    invokeRejection('openRejectedModal', paymentId)
}
</script>