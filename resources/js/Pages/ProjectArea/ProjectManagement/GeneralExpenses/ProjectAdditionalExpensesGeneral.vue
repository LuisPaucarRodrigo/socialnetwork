<template>

    <Head title="Gestion de Costos Adicionales" />
    <AuthenticatedLayout ::redirectRoute="{
        route: 'projectmanagement.pext.additional.index',
        params: { type }
    }">
        <template #header>
            Gastos Generales {{ fixedOrAdditional ? 'Fijos' : 'Adicionales' }}
        </template>
        <Toaster richColors />
        <TableHeader :openOpNuDaModal="openOpNuDaModal" :openCreateAdditionalModal="openCreateAdditionalModal"
            :filterForm="filterForm" :fixedOrAdditional="fixedOrAdditional" :type="type" />
        <GeneralExpensesTable :expenses="expenses" :openEditAdditionalModal="openEditAdditionalModal"
            :confirmDeleteAdditional="confirmDeleteAdditional" :filterForm="filterForm" v-model:actionForm="actionForm"
            :zones="zones" :expenseTypes="expenseTypes" :documentsType="documentsType" :stateTypes="stateTypes"
             />

        <SuspenseWrapper :when="showExpensesForm">
            <template #component>
                <ExpensesForm ref="expensesForm" :expenses="expenses" :providers="providers"
                    :fixedOrAdditional="fixedOrAdditional" :zones="zones" :expenseTypes="expenseTypes"
                    :documentsType="documentsType" :type="type" />
            </template>
        </SuspenseWrapper>

        <SuspenseWrapper :when="showMassiveUpdate">
            <template #component>
                <MassiveUpdate ref="massiveUpdate" :expenses="expenses" :actionForm="actionForm" />
            </template>
        </SuspenseWrapper>

        <SuspenseWrapper :when="showDeleteExpense">
            <template #component>
                <DeleteExpense ref="deleteExpense" :expenses="expenses" />
            </template>
        </SuspenseWrapper>

        <!-- <ConfirmDeleteModal :confirmingDeletion="confirmingDocDeletion" itemType="Gasto"
            :deleteFunction="deleteAdditional" @closeModal="closeModalDoc" /> -->
        <!-- <ConfirmateModal :showConfirm="showSwapCostsModal" tittle="Cambio de gastos adicionales a fijos"
            text="La siguiente acción ya no se podrá revertir, ¿Desea continuar?" :actionFunction="swapCosts"
            @closeModal="closeSwapCostsModal" /> -->
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { defineAsyncComponent, ref, watch } from "vue";
import { Head } from "@inertiajs/vue3";
import { notify, notifyError, notifyWarning } from "@/Components/Notification";
import { Toaster } from "vue-sonner";
import GeneralExpensesTable from "./components/GeneralExpensesTable.vue";
import SuspenseWrapper from "@/Components/SuspenseWrapper.vue";
import TableHeader from "./components/TableHeader.vue";
import { useLazyRefInvoker } from "@/utils/useLazyRefInvoker";

const ExpensesForm = defineAsyncComponent(() => import('./components/ExpensesForm.vue'));
const MassiveUpdate = defineAsyncComponent(() => import('./components/MassiveUpdate.vue'));
const DeleteExpense = defineAsyncComponent(() => import('./components/DeleteExpense.vue'));

const props = defineProps({
    expense: Object,
    providers: Object,
    auth: Object,
    cost_center: Object,
    fixedOrAdditional: Boolean,
    type: String,
    // acExpensesAmounts: Array,
    // scExpensesAmounts: Array,
    zones: Array,
    expenseType: Array,
    documentsType: Array,
    expenseTypeFixed: Array,
    additional_projects: Array,
});

const showExpensesForm = ref(false)
const showMassiveUpdate = ref(false)
const showDeleteExpense = ref(false)


const deleteExpense = ref(null)
const expensesForm = ref(null)
const massiveUpdate = ref(null)

const expenses = ref(props.expense);
// const filterMode = ref(false);
// const subCostCenterZone = ref(null);
// const subCostCenter = ref(null)

const { invokeWhenReady: invokeExpensesForm } = useLazyRefInvoker(expensesForm, showExpensesForm);
const { invokeWhenReady: invokeMassiveUpdate } = useLazyRefInvoker(massiveUpdate, showMassiveUpdate);
const { invokeWhenReady: invokeDeleteExpense } = useLazyRefInvoker(deleteExpense, showDeleteExpense);


// const opNuDateForm = useForm({
//     operation_date: '',
//     operation_number: '',
// })

const showOpNuDatModal = ref(false)
// const showSwapCostsModal = ref(false)

const openCreateAdditionalModal = () => {
    invokeExpensesForm('openCreateAdditionalModal')
};

const openEditAdditionalModal = (additional) => {
    invokeExpensesForm('openEditAdditionalModal', additional)
};

const openOpNuDaModal = () => {
    invokeMassiveUpdate('openOpNuDaModal')
}

// const closeOpNuDatModal = () => {
//     showOpNuDatModal.value = false
//     // isFetching.value = false
//     opNuDateForm.reset()
//     opNuDateForm.clearErrors()
// }

const confirmDeleteAdditional = (additionalId) => {
    invokeDeleteExpense('confirmDeleteAdditional', additionalId)
};

const expenseTypes = props.fixedOrAdditional
    ? props.expenseTypeFixed
    : props.expenseType;


const stateTypes = [
    "Pendiente",
    "Aceptado",
    "Aceptado - Validado",
];

const initialFilterFormState = {
    type: props.type,
    fixedOrAdditional: props.fixedOrAdditional,
    rejected: true,
    search: "",
    selectedZones: props.zones,
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

// watch(() => form.project_id, (newval) => {
//     const project = cicsaAssignation.value.find(item => item.project_id == newval);
//     form.description = project ? project.project_name : "";
// });


// watch(() => form.zone, () => {
//     getProject()
// })

// async function getProject() {
//     let url = route('pext.additional.expense.general.getCicsaAssignation')
//     let data = { type: props.type, zone: form.zone }
//     try {
//         let response = await axios.post(url, data);
//         cicsaAssignation.value = response.data
//     } catch (error) {
//         notifyError(error)
//     }
// }

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
    let url = route("pext.monthly.additional.expense.general.search_advance")
    try {
        let response = await axios.post(url, data);
        expenses.value = response.data;
        notifyWarning(`Se encontraron ${response.data.length} registro(s)`);
    } catch (error) {
        console.error('Error en la solicitud:', error);
    }
}



// watch([() => form.type_doc, () => form.zone], () => {
//     if (
//         form.type_doc === "Factura" &&
//         !["", "MDD"].includes(form.zone)
//     ) {
//         form.igv = form.igv ? form.igv : 18;
//     } else {
//         form.igv = 0;
//     }
// });

// // const confirmValidation = ref(false);
// async function validateRegister(expense_id, is_accepted) {
//     const url = route("projectmanagement.pext.expenses.validate", { 'expense_id': expense_id })
//     try {
//         await axios.put(url, { 'is_accepted': is_accepted });
//         if (filterForm.value.rejected) {
//             updateExpense(expense_id, "validate", is_accepted)
//         } else {
//             updateExpense(expense_id, "rejectedValidate")
//         }
//     } catch (e) {
//         console.log(e);
//     }
// }



const actionForm = ref({ ids: [] });

watch(
    () => filterForm.value,
    () => { actionForm.value = { ids: [] }; },
    { deep: true }
);



// const closeSwapCostsModal = () => {
//     showSwapCostsModal.value = false
// }

// const openSwapCostsModal = () => {
//     if (actionForm.value.ids.length === 0) {
//         notifyWarning("No hay registros seleccionados");
//         return;
//     }
//     showSwapCostsModal.value = true
// }

const swapCosts = async () => {
    await axios
        .post(route("projectmanagement.pext.massiveUpdate.swap"), {
            ...actionForm.value
        })
        .catch((e) => {
            notifyError("Server Error");
        });
    expenses.value = expenses.value.filter(
        (item) => !actionForm.value.ids.includes(item.id)
    );
    actionForm.value.ids = []
    closeSwapCostsModal();
    notify("Registros Movidos con éxito");
}
</script>
