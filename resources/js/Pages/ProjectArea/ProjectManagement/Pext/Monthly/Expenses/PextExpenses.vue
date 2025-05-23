<template>

    <Head title="Gestion de Costos Adicionales" />
    <AuthenticatedLayout :redirectRoute="redirectRoute">
        <template #header>
            Gastos Mensuales del Proyecto
        </template>
        <br />
        <Toaster richColors />
        <div class="inline-block min-w-full mb-4">
            <TableHeader :userPermissions="userPermissions" v-model:filterForm="filterForm" :project_id="project_id"
                :fixedOrAdditional="fixedOrAdditional" :status="status" :actionForm="actionForm"
                v-model:showSwapCostsModal="showSwapCostsModal" v-model:showOpNuDatModal="showOpNuDatModal"
                :initialFilterFormState="initialFilterFormState" :openCreateAdditionalModal="openCreateAdditionalModal"
                :openModalImport="openModalImport" />
        </div>
        <ExpensesTable :expenses="expenses" :userPermissions="userPermissions" :actionForm="actionForm"
            :filterForm="filterForm" :zones="zones" :docTypes="docTypes" :expenseTypes="expenseTypes"
            :stateTypes="stateTypes" :openEditAdditionalModal="openEditAdditionalModal" />

        <MasiveUpdate :expenses="expenses" v-model:showOpNuDatModal="showOpNuDatModal" />
        <ExpensesImport :project_id="project_id" :fixedOrAdditional="fixedOrAdditional" ref="expensesImport"/>
        <FormExpenses :expenses="expenses" :fixedOrAdditional="fixedOrAdditional" :project_id="project_id"
            :zones="zones" :docTypes="docTypes" :expenseTypes="expenseTypes" :providers="providers"
            ref="formExpenses" />
        <ConfirmateModal :showConfirm="showSwapCostsModal" tittle="Cambio de gastos adicionales a fijos"
            text="La siguiente acción ya no se podrá revertir, ¿Desea continuar?" :actionFunction="swapCosts"
            @closeModal="closeSwapCostsModal" />
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { ref, watch } from "vue";
import { Head } from "@inertiajs/vue3";
import axios from "axios";
import { notify, notifyError } from "@/Components/Notification";
import { Toaster } from "vue-sonner";
import ConfirmateModal from "@/Components/ConfirmateModal.vue";
import TableHeader from "./components/TableHeader.vue";
import ExpensesTable from "./components/ExpensesTable.vue";
import FormExpenses from "./components/FormExpenses.vue";
import MasiveUpdate from "./components/MasiveUpdate.vue";
import ExpensesImport from "../../components/ExpensesImport.vue";

const props = defineProps({
    expense: Object,
    providers: Object,
    auth: Object,
    userPermissions: Array,
    project_id: String,
    status: String,
    fixedOrAdditional: Boolean,
    zones: Array,
    docTypes: Array,
    expenseTypesFixed: Array,
    expenseTypesAdditional: Array
});

const expenses = ref(props.expense);
const showOpNuDatModal = ref(false)
const redirectRoute = props.status ? 'projectmanagement.pext.historial' : 'projectmanagement.pext.index'
const formExpenses = ref(false);
const showSwapCostsModal = ref(false)
const expensesImport = ref(false)

function openCreateAdditionalModal() {
    formExpenses.value.openCreateAdditionalModal()
}

function openEditAdditionalModal(additional) {
    formExpenses.value.openEditAdditionalModal(additional)
};

function openModalImport() {
    expensesImport.value.toogleModalImport()
};

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


const closeSwapCostsModal = () => {
    showSwapCostsModal.value = false
}

const swapCosts = async () => {
    let validation = expenses.value.data || expenses.value
    await axios
        .post(route("projectmanagement.pext.massiveUpdate.swap"), {
            ...actionForm.value
        })
        .catch((e) => {
            notifyError("Server Error");
        });

    actionForm.value.ids.forEach((id) => {
        const index = validation.findIndex((item) => item.id === id);
        if (index !== -1) {
            validation.splice(index, 1);
        }
    });

    actionForm.value.ids = []
    closeSwapCostsModal();
    notify("Registros Movidos con éxito");
}

</script>
