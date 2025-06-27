<template>

    <Head title="Gestion de Costos Adicionales" />
    <AuthenticatedLayout :redirectRoute="redirectRoute">
        <template #header>
            Gastos Mensuales del Proyecto
        </template>
        <br />
        <Toaster richColors />
        <div class="inline-block min-w-full mb-4">
            <TableHeader v-model:filterForm="filterForm" :project_id="project_id" :fixedOrAdditional="fixedOrAdditional"
                :status="status" :openSwapCostsModal="openSwapCostsModal"
                :initialFilterFormState="initialFilterFormState" :openCreateAdditionalModal="openCreateAdditionalModal"
                :openOpNuDaModal="openOpNuDaModal" :openModalImport="openModalImport" />
        </div>
        <ExpensesTable :expenses="expenses" v-model:actionForm="actionForm" :filterForm="filterForm" :zones="zones"
            :docTypes="docTypes" :expenseTypes="expenseTypes" :stateTypes="stateTypes"
            :openEditAdditionalModal="openEditAdditionalModal" :confirmDeleteAdditional="confirmDeleteAdditional" />

        <SuspenseWrapper :when="showMassiveUpdate">
            <template #component>
                <MassiveUpdate ref="massiveUpdate" :expenses="expenses" :actionForm="actionForm" />
            </template>
        </SuspenseWrapper>

        <SuspenseWrapper :when="showExpensesImport">
            <template #component>
                <ExpensesImport :project_id="project_id" :fixedOrAdditional="fixedOrAdditional" ref="expensesImport" />
            </template>
        </SuspenseWrapper>

        <SuspenseWrapper :when="showFormExpenses">
            <template #component>
                <FormExpenses :expenses="expenses" :fixedOrAdditional="fixedOrAdditional" :project_id="project_id"
                    :zones="zones" :docTypes="docTypes" :expenseTypes="expenseTypes" :providers="providers"
                    ref="formExpenses" />
            </template>
        </SuspenseWrapper>

        <SuspenseWrapper :when="showSwap">
            <template #component>
                <Swap ref="swap" :expenses="expenses" :actionForm="actionForm" />
            </template>
        </SuspenseWrapper>

        <SuspenseWrapper :when="showDeleteExpenses">
            <template #component>
                <DeleteExpenses ref="deleteExpenses" :expenses="expenses" />
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
import { useLazyRefInvoker } from "@/utils/useLazyRefInvoker";
import SuspenseWrapper from "@/Components/SuspenseWrapper.vue";


const MassiveUpdate = defineAsyncComponent(() => import('./components/MassiveUpdate.vue'));
const ExpensesImport = defineAsyncComponent(() => import('../../components/ExpensesImport.vue'));
const FormExpenses = defineAsyncComponent(() => import('./components/FormExpenses.vue'));
const Swap = defineAsyncComponent(() => import('./components/Swap.vue'));
const DeleteExpenses = defineAsyncComponent(() => import('./components/DeleteExpenses.vue'));

const props = defineProps({
    expense: Object,
    providers: Object,
    auth: Object,
    project_id: String,
    status: String,
    fixedOrAdditional: Boolean,
    zones: Array,
    docTypes: Array,
    expenseTypesFixed: Array,
    expenseTypesAdditional: Array
});
const massiveUpdate = ref(null)
const formExpenses = ref(null)
const expensesImport = ref(null)
const swap = ref(null)
const deleteExpenses = ref(null)

const showMassiveUpdate = ref(false)
const showFormExpenses = ref(false)
const showExpensesImport = ref(false)
const showSwap = ref(false)
const showDeleteExpenses = ref(false)


const expenses = ref(props.expense);

const redirectRoute = props.status ? 'projectmanagement.pext.historial' : 'projectmanagement.pext.index'

const { invokeWhenReady: invokeExpensesForm } = useLazyRefInvoker(formExpenses, showFormExpenses);
const { invokeWhenReady: invokeMassiveUpdate } = useLazyRefInvoker(massiveUpdate, showMassiveUpdate);
const { invokeWhenReady: invokeExpensesImport } = useLazyRefInvoker(expensesImport, showExpensesImport);
const { invokeWhenReady: invokeSwap } = useLazyRefInvoker(swap, showSwap);
const { invokeWhenReady: invokeDeleteExpense } = useLazyRefInvoker(deleteExpenses, showDeleteExpenses);

function openCreateAdditionalModal() {
    invokeExpensesForm('openCreateAdditionalModal')
}

function openEditAdditionalModal(additional) {
    invokeExpensesForm('openEditAdditionalModal', additional)
};

function openModalImport() {
    invokeExpensesImport('toogleModalImport')
};

function openOpNuDaModal() {
    invokeMassiveUpdate('openOpNuDaModal')
}

function openSwapCostsModal() {
    invokeSwap('openSwapCostsModal')
}

function confirmDeleteAdditional(additionalId) {
    invokeDeleteExpense('confirmDeleteAdditional', additionalId)
}

const expenseTypes = props.fixedOrAdditional
    ? props.expenseTypesFixed
    : props.expenseTypesAdditional;

const stateTypes = [
    "Pendiente",
    "Aceptado",
    "Aceptado - Validado",
];

const initialFilterFormState = {
    fixedOrAdditional: props.fixedOrAdditional,
    rejected: true,
    search: "",
    selectedZones: props.zones,
    selectedExpenseTypes: expenseTypes,
    selectedDocTypes: props.docTypes,
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
    filterForm.value.selectedZones,
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
</script>
