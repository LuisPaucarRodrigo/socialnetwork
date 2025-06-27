<template>

    <Head title="Nomina" />
    <AuthenticatedLayout :redirectRoute="'payroll.index'">
        <template #header>
            Nomina de {{ formattedMonth(payrolls.month) }}
        </template>
        <Toaster richColors />
        <div class="min-w-full min-h-full overflow-hidden">
            <TableHeader :payrolls="payrolls" :filterForm="filterForm" :openPaySpreadsheet="openPaySpreadsheet"
                :openExportSpreadsheet="openExportSpreadsheet" :search_advance="search_advance" />

            <SpreadsheetsTable :spreadsheets="spreadsheets" :totals="totals" :pensionTypes="pensionTypes"
                :filterForm="filterForm" :actionForm="actionForm" />

            <SuspenseWrapper :when="showSpreadsheetPayModal">
                <template #component>
                    <SpreadsheetPayModal ref="paySpreadsheet" :data="spreadsheet" :payroll="payroll"
                        :actionForm="actionForm" />
                </template>
            </SuspenseWrapper>

            <SuspenseWrapper :when="showSpreadSheetExportDetailModal">
                <template #component>
                    <SpreadSheetExportDetailModal ref="exportSpreadsheet" :payroll="payroll" />
                </template>
            </SuspenseWrapper>

        </div>
        <!-- <ApprovePayroll v-model:payrolls="payrolls" ref="approvePayroll" /> -->
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { defineAsyncComponent, ref, watch } from 'vue';
import TableHeader from './components/TableHeader.vue';
import SpreadsheetsTable from './components/SpreadsheetsTable.vue';
// import ApprovePayroll from './components/ApprovePayroll.vue';
import { Toaster } from 'vue-sonner';
import { formattedMonth } from '@/utils/utils';
import SuspenseWrapper from '@/Components/SuspenseWrapper.vue';
import { useLazyRefInvoker } from '@/utils/useLazyRefInvoker';
import { notifyWarning } from '@/Components/Notification';

const SpreadsheetPayModal = defineAsyncComponent(() => import('./components/SpreadsheetPayModal.vue'));
const SpreadSheetExportDetailModal = defineAsyncComponent(() => import('./components/SpreadSheetExportDetailModal.vue'));

const { spreadsheet, payroll, total, pensionTypes } = defineProps({
    spreadsheet: Object,
    payroll: Object,
    total: Object,
    pensionTypes: Array
})

const showSpreadsheetPayModal = ref(false)
const showSpreadSheetExportDetailModal = ref(false)

const actionForm = ref({ ids: [], });
const initialFilterFormState = {
    selectedPensionTypes: pensionTypes,
    search: ''
}
const filterForm = ref({ ...initialFilterFormState })

const spreadsheets = ref(spreadsheet)
const payrolls = ref(payroll)
const totals = ref(total)

// const approvePayroll = ref(null)
const paySpreadsheet = ref(null)
const exportSpreadsheet = ref(null)

const { invokeWhenReady: invokePaySpreadsheet } = useLazyRefInvoker(paySpreadsheet, showSpreadsheetPayModal);
const { invokeWhenReady: invokeExportSpreadsheet } = useLazyRefInvoker(exportSpreadsheet, showSpreadSheetExportDetailModal);

// function openPayrollApprove() {
//     approvePayroll.value.openPayrollApprove()
// }

//paying functions
function openPaySpreadsheet() {
    invokePaySpreadsheet('openPayModal')
}

function openExportSpreadsheet() {
    invokeExportSpreadsheet('openExportModal')
}

watch(() => filterForm.value.selectedPensionTypes, () => {
    search_advance()
}, { deep: true })

async function search_advance() {
    try {
        let res = await axios.post(route("spreadsheets.index", { payroll_id: payrolls.value.id, }),
            filterForm.value
        );
        spreadsheets.value = res.data.spreadsheet
        totals.value = res.data.total
        notifyWarning(`Se encontraron ${res.data.spreadsheet.length} registro(s)`);
    } catch (error) {
        console.error("Error en la solicitud:", error);
    }
}

</script>