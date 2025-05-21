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
            v-model:filterForm="filterForm" :userPermissions="userPermissions" :openSwapAPModal="openSwapAPModal"
            :initialFilterFormState="initialFilterFormState" :openModalImport="openModalImport" />
        <ExpensesTable :project_id="project_id" :expenses="expenses" :filterForm="filterForm" :actionForm="actionForm"
            :expenseTypes="expenseTypes" :documentsType="documentsType" :stateTypes="stateTypes" />
        <Swap ref="swap" :actionForm="actionForm" v-model:expenses="expenses" />
        <ExpensesImport :project_id="project_id" :fixedOrAdditional="fixedOrAdditional" ref="expensesImport" />

        <!-- <SuccessOperationModal :confirming="confirmValidation" :title="'Validación'"
            :message="'La validación del gasto fue exitosa.'" /> -->
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { ref, watch } from "vue";
import { Head } from "@inertiajs/vue3";
import axios from "axios";
import { Toaster } from "vue-sonner";
import { notifyWarning } from "@/Components/Notification";
import TableHeader from "./components/TableHeader.vue";
import ExpensesTable from "./components/ExpensesTable.vue";
import Swap from "./components/Swap.vue";
import ExpensesImport from "../../components/ExpensesImport.vue";

const props = defineProps({
    expense: Object,
    providers: Object,
    auth: Object,
    userPermissions: Array,
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

const expenses = ref({ ...props.expense });
const swap = ref(null)
const expensesImport = ref(null)
// const filterMode = ref(false);
// const subCostCenterZone = ref(null);
// const subCostCenter = ref(null)

const showOpNuDatModal = ref(false)

const openOpNuDaModal = () => {
    if (actionForm.value.ids.length === 0) {
        notifyWarning("No hay registros seleccionados");
        return;
    }
    showOpNuDatModal.value = true
}



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
    swap.value.openSwapAPModal()
}

function openModalImport() {
    expensesImport.value.toogleModalImport()
};
</script>
