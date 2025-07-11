<template>

    <Head title="Gestion de Costos Adicionales" />
    <AuthenticatedLayout :redirectRoute="{
        route: 'projectmanagement.pext.additional.index',
        params: { type: type }
    }">
        <template #header>
            Gastos {{ fixedOrAdditional ? 'Fijos' : 'Adicionales' }}
        </template>
        <br />
        <Toaster richColors />
        <TableHeader :project_id="project_id" :fixedOrAdditional="fixedOrAdditional" :type="type"
            v-model:filterForm="filterForm" :openSwapAPModal="openSwapAPModal"
            :initialFilterFormState="initialFilterFormState" :openModalImport="openModalImport" />
        <ExpensesTable :project_id="project_id" :expenses="expenses" :filterForm="filterForm" :actionForm="actionForm"
            :expenseTypes="expenseTypes" :documentsType="documentsType" :stateTypes="stateTypes" />

        <SuspenseWrapper :when="showSwap">
            <template #component>
                <Swap ref="swap" :actionForm="actionForm" v-model:expenses="expenses" />
            </template>
        </SuspenseWrapper>

    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { defineAsyncComponent, ref, watch } from "vue";
import { Head } from "@inertiajs/vue3";
import { Toaster } from "vue-sonner";
import TableHeader from "./components/TableHeader.vue";
import ExpensesTable from "./components/ExpensesTable.vue";
import SuspenseWrapper from "@/Components/SuspenseWrapper.vue";
import { useLazyRefInvoker } from "@/utils/useLazyRefInvoker";

const Swap = defineAsyncComponent(() => import('./components/Swap.vue'));

const props = defineProps({
    expense: Object,
    providers: Object,
    auth: Object,
    cost_center: Object,
    project_id: String,
    fixedOrAdditional: Boolean,
    type: String,
    zones: Array,
    expenseType: Array,
    documentsType: Array,
    expenseTypeFixed: Array,
    additional_projects: Array,
});

const showSwap = ref(false)


const expenses = ref(props.expense);
const swap = ref(null)
// const filterMode = ref(false);
// const subCostCenterZone = ref(null);
// const subCostCenter = ref(null)

const { invokeWhenReady: invokeSwap } = useLazyRefInvoker(swap, showSwap);


const expenseTypes = props.fixedOrAdditional
    ? props.expenseTypeFixed
    : props.expenseType;

const stateTypes = [
    "Pendiente",
    "Aceptado",
    "Aceptado - Validado",
];

const initialFilterFormState = {
    fixedOrAdditional: props.fixedOrAdditional,
    rejected: true,
    search: "",
    selectedExpenseTypes: expenseTypes,
    selectedDocTypes: props.documentsType,
    selectedStateTypes: stateTypes,
    opStartDate: "",
    opEndDate: "",
    opNoDate: false,
    docStartDate: "",
    docEndDate: "",
    docNoDate: false,
}

const filterForm = ref({
    ...initialFilterFormState
});


watch(() => [
    filterForm.value.fixedOrAdditional,
    filterForm.value.rejected,
    filterForm.value.search,
    filterForm.value.selectedExpenseTypes,
    filterForm.value.selectedDocTypes,
    filterForm.value.selectedStateTypes,
    filterForm.value.opStartDate,
    filterForm.value.opEndDate,
    filterForm.value.opNoDate,
    filterForm.value.docStartDate,
    filterForm.value.docEndDate,
    filterForm.value.docNoDate,
],
    () => {
        search_advance(filterForm.value);
    }
);

async function search_advance(data) {
    let url = route("pext.monthly.additional.expense.search_advance", {
        project_id: props.project_id,
    })
    try {
        let response = await axios.post(url, data);
        expenses.value = response.data;
    } catch (error) {
        console.error('Error en la solicitud:', error);
    }
}

const actionForm = ref({ ids: [], });

watch(
    () => filterForm.value,
    () => { actionForm.value = { ids: [] }; },
    { deep: true }
);

function openSwapAPModal() {
    invokeSwap('openSwapAPModal')
}

function openModalImport() {
    invokeExpenseImport('toogleModalImport')
};
</script>
