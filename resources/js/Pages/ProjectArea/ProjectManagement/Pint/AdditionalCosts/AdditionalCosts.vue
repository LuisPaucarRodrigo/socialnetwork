<template>

    <Head title="Gestion de Costos Adicionales" />
    <AuthenticatedLayout :redirectRoute="{
        route: 'projectmanagement.purchases_request.index',
        params: { id: project_id.id },
    }">
        <template #header>
            Gastos Variables del Proyecto
            {{ props.project_id.name }}
        </template>

        <Toaster richColors />
        <TableHeader v-model:filterForm="filterForm" :openOpNuDaModal="openOpNuDaModal"
            :openSwapCostsModal="openSwapCostsModal" :openSwapAPModal="openSwapAPModal"
            :openSwapRPModal="openSwapRPModal" :openCreateAdditionalModal="openCreateAdditionalModal"
            :openExportExcel="openExportExcel" :openExportArchivesModal="openExportArchivesModal"
            :project_id="project_id" :initialFilterFormState="initialFilterFormState" />


        <AdditionalTable v-model:dataToRender="dataToRender" :actionForm="actionForm"
            :filterForm="filterForm" :filterMode="filterMode" :openEditAdditionalModal="openEditAdditionalModal"
            :confirmDeleteAdditional="confirmDeleteAdditional" :expenseTypes="expenseTypes" :docTypes="docTypes"
            :zones="zones" :stateTypes="stateTypes" :adminStateTypes="adminStateTypes" v-model:loading="loading" :project_id="project_id"
            :openAcceptModal="openAcceptModal" />

        <SuspenseWrapper :when="showExpensesForm">
            <template #component>
                <ExpensesForm ref="expensesForm" :dataToRender="dataToRender" :project_id="project_id.id"
                    :providers="providers" :expenseTypes="expenseTypes" :docTypes="docTypes" :zones="zones" />
            </template>
        </SuspenseWrapper>

        <SuspenseWrapper :when="showImportModal">
            <template #component>
                <ImportModal ref="importModal" :project_id="project_id.id" />
            </template>
        </SuspenseWrapper>

        <SuspenseWrapper :when="showUpdateMassiveOpNuDat">
            <template #component>
                <UpdateMassiveOpNuDat ref="updateMassiveOpNuDat" :actionForm="actionForm"
                    v-model:dataToRender="dataToRender" />
            </template>
        </SuspenseWrapper>

        <SuspenseWrapper :when="showSwapAPModal">
            <template #component>
                <SwapAPModal ref="swapAPModal" v-model:actionForm="actionForm" v-model:dataToRender="dataToRender" :project_id="project_id.id"/>
            </template>
        </SuspenseWrapper>

        <SuspenseWrapper :when="showSwapRPModal">
            <template #component>
                <SwapRPModal ref="swapRPModal" :actionForm="actionForm" v-model:dataToRender="dataToRender" />
            </template>
        </SuspenseWrapper>

        <SuspenseWrapper :when="showAcceptModal">
            <template #component>
                <AcceptModal ref="acceptModal" :dataToRender="dataToRender" />
            </template>
        </SuspenseWrapper>

        <SuspenseWrapper :when="showConfirmDeleteExpenses">
            <template #component>
                <ConfirmDeleteExpenses ref="confirmDeleteExpenses" :dataToRender="dataToRender"
                    :project_id="project_id.id" />
            </template>
        </SuspenseWrapper>

        <SuspenseWrapper :when="showConfirmSwapCostModal">
            <template #component>
                <ConfirmSwapCostModal ref="confirmSwapCostModal" :actionForm="actionForm" v-model:dataToRender="dataToRender" />
            </template>
        </SuspenseWrapper>

        <SuspenseWrapper :when="showConfirmDownloadFiles">
            <template #component>
                <ConfirmDownloadFiles ref="confirmDownloadFiles" :filterForm="filterForm" :project_id="project_id.id" />
            </template>
        </SuspenseWrapper>

    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { defineAsyncComponent, onMounted, ref, watch } from "vue";
import { Head, useForm } from "@inertiajs/vue3";
import { notifyWarning } from "@/Components/Notification";
import { Toaster } from "vue-sonner";
import SuspenseWrapper from "@/Components/SuspenseWrapper.vue";
import { useLazyRefInvoker } from "@/utils/useLazyRefInvoker";
import AdditionalTable from "./components/AdditionalTable.vue";
import TableHeader from "./components/TableHeader.vue";

const ConfirmDeleteExpenses = defineAsyncComponent(() => import('./components/ConfirmDeleteExpenses.vue'));
const ConfirmSwapCostModal = defineAsyncComponent(() => import('./components/ConfirmSwapCostModal.vue'));
const ConfirmDownloadFiles = defineAsyncComponent(() => import('./components/ConfirmDownloadFiles.vue'));
const ImportModal = defineAsyncComponent(() => import('./components/ImportModal.vue'));
const AcceptModal = defineAsyncComponent(() => import('./components/AcceptModal.vue'));
const SwapAPModal = defineAsyncComponent(() => import('./components/SwapAPModal.vue'));
const SwapRPModal = defineAsyncComponent(() => import('./components/SwapRPModal.vue'));
const UpdateMassiveOpNuDat = defineAsyncComponent(() => import('./components/UpdateMassiveOpNuDat.vue'));
const ExpensesForm = defineAsyncComponent(() => import('./components/ExpensesForm.vue'));

const props = defineProps({
    project_id: Object,
    providers: Object,
    auth: Object,
    searchQuery: String,
    state: String,
    zones: Array,
    expenseTypes: Array,
    docTypes: Array,
    stateTypes: Array,
    adminStateTypes: Array,
});

const { expenseTypes, docTypes, zones, stateTypes, adminStateTypes } = props
expenseTypes.sort()
docTypes.sort()
zones.sort()
stateTypes.sort()
adminStateTypes.sort()

const dataToRender = ref([]);
const filterMode = ref(false);
const loading = ref(true)

const showUpdateMassiveOpNuDat = ref(false)
const updateMassiveOpNuDat = ref(null)

onMounted(async () => {
    const res = await axios.get(route('projectmanagement.getAdditionalCost', { project_id: props.project_id.id }));
    dataToRender.value = res.data;
    loading.value = false;
});

const form = useForm({
    id: "",
    expense_type: "",
    ruc: "",
    zone: "",
    provider_id: "",
    project_id: props.project_id.id,
    type_doc: "",
    operation_number: "",
    operation_date: "",
    doc_number: "",
    doc_date: "",
    description: "",
    photo: "",
    amount: "",
    igv: 0,
    photo_status: "stable",
    admin_state: "",
    admin_reject_reason: "",
    ask_admin_review: false
});

const showImportModal = ref(false)
const importModal = ref(null)

const showSwapAPModal = ref(false)
const swapAPModal = ref(null)

const showSwapRPModal = ref(false)
const swapRPModal = ref(null)

const showAcceptModal = ref(false)
const acceptModal = ref(null)

const showExpensesForm = ref(false)
const expensesForm = ref(null)

const confirmDownloadFiles = ref(null)
const showConfirmDownloadFiles = ref(false)

const showConfirmSwapCostModal = ref(null)
const confirmSwapCostModal = ref(null)

const showConfirmDeleteExpenses = ref(null)
const confirmDeleteExpenses = ref(null)

const { invokeWhenReady: invokeExpensesForm } = useLazyRefInvoker(expensesForm, showExpensesForm);
const { invokeWhenReady: invokeConfirmDeleteExpenses } = useLazyRefInvoker(confirmDeleteExpenses, showConfirmDeleteExpenses);
const { invokeWhenReady: invokeConfirmSwapCostModal } = useLazyRefInvoker(confirmSwapCostModal, showConfirmSwapCostModal);
const { invokeWhenReady: invokeConfirmDownloadFiles } = useLazyRefInvoker(confirmDownloadFiles, showConfirmDownloadFiles);
const { invokeWhenReady: invokeImportModal } = useLazyRefInvoker(importModal, showImportModal);
const { invokeWhenReady: invokeAcceptModal } = useLazyRefInvoker(acceptModal, showAcceptModal);
const { invokeWhenReady: invokeSwapAPModal } = useLazyRefInvoker(swapAPModal, showSwapAPModal);
const { invokeWhenReady: invokeSwapRPModal } = useLazyRefInvoker(swapRPModal, showSwapRPModal);
const { invokeWhenReady: invokeUpdateMassiveOpNuDat } = useLazyRefInvoker(updateMassiveOpNuDat, showUpdateMassiveOpNuDat);

const initialFilterFormState = {
    search: "",
    selectedZones: zones,
    selectedExpenseTypes: expenseTypes,
    selectedDocTypes: docTypes,
    selectedStateTypes: stateTypes,
    selectedAdminStateTypes: adminStateTypes,
    opStartDate: "",
    opEndDate: "",
    opNoDate: false,
    docStartDate: "",
    docEndDate: "",
    docNoDate: false,
};

const filterForm = ref({ ...initialFilterFormState });

watch(
    () => [
        filterForm.value.selectedZones,
        filterForm.value.selectedExpenseTypes,
        filterForm.value.selectedDocTypes,
        filterForm.value.selectedStateTypes,
        filterForm.value.selectedAdminStateTypes,
        filterForm.value.opStartDate,
        filterForm.value.opEndDate,
        filterForm.value.opNoDate,
        filterForm.value.docStartDate,
        filterForm.value.docEndDate,
        filterForm.value.docNoDate,
        filterForm.value.search,
    ],
    () => {
        filterMode.value = true;
        search_advance(filterForm.value);
    }
);

async function search_advance(data) {
    const $search = data || filterForm.value
    try {
        let res = await axios.post(
            route("additionalcost.advance.search", {
                project_id: props.project_id.id,
            }),
            $search
        );
        dataToRender.value = res.data;
        notifyWarning(`Se encontraron ${res.data.length} registro(s)`);
    } catch (error) {
        console.error("Error en la solicitud:", error);
    }
}

const uniqueParam = `timestamp=${new Date().getTime()}`;

function openExportExcel() {
    const url =
        route("additionalcost.excel.export", {
            project_id: props.project_id.id,
        }) +
        "?" +
        uniqueParam;
    window.location.href = url;
}

watch([() => form.type_doc, () => form.zone], () => {
    if (
        form.type_doc === "Factura" &&
        !["", "MDD1-PM", "MDD2-MAZ"].includes(form.zone)
    ) {
        form.igv = form.igv ? form.igv : 18;
    } else {
        form.igv = 0;
    }
});

//block actions
const actionForm = ref({ ids: [], });

watch(
    () => filterForm.value,
    () => { actionForm.value = { ids: [] }; },
    { deep: true }
);

const openCreateAdditionalModal = () => {
    invokeExpensesForm('openCreateAdditionalModal')
};

const openEditAdditionalModal = (additional) => {
    invokeExpensesForm('openEditAdditionalModal', additional)
};

function openImportModal() {
    invokeImportModal('openImportModal')
}

const openExportArchivesModal = () => { invokeConfirmDownloadFiles('openExportArchivesModal') }

const confirmDeleteAdditional = (additionalId) => {
    invokeConfirmDeleteExpenses('confirmDeleteAdditional', additionalId)
};

const openOpNuDaModal = () => {
    invokeUpdateMassiveOpNuDat('openOpNuDaModal')
}

const openAcceptModal = (item) => {
    invokeAcceptModal('openAcceptModal', item)
}

const openSwapCostsModal = () => {
    invokeConfirmSwapCostModal('openSwapCostsModal')
}

const openSwapAPModal = () => {
    invokeSwapAPModal('openSwapAPModal')
}

const openSwapRPModal = () => {
    invokeSwapRPModal('openSwapRPModal')
}

</script>
