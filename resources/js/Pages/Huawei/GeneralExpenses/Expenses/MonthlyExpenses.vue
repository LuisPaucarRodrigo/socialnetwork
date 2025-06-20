<template>
    <Head title="Gestion de Costos" />
    <AuthenticatedLayout :redirectRoute="'huawei.projects.generalbalance'">
        <template #header>
            Gastos Generales {{ props.mode ? "Fijos" : "Variables" }}</template
        >
        <br />
        <Toaster richColors />
        <div class="inline-block min-w-full mb-4">
            <div class="flex flex-wrap items-center gap-4">
                <div class="hidden sm:flex sm:items-center space-x-3">
                    <PrimaryButton
                        v-permission="'huawei_expenses_add'"
                        @click="openCreateAdditionalModal"
                        type="button"
                        class="whitespace-nowrap"
                    >
                        + Agregar
                    </PrimaryButton>
                    <button
                        v-permission="'huawei_expenses_add'"
                        data-tooltip-target="import_tooltip"
                        type="button"
                        class="rounded-md bg-green-600 px-4 py-2 text-center text-sm text-white hover:bg-green-500"
                        @click="openImportExcel"
                    >
                        <svg
                            class="w-5 h-5"
                            viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <title />

                            <g id="Complete">
                                <g id="upload">
                                    <g>
                                        <path
                                            d="M3,12.3v7a2,2,0,0,0,2,2H19a2,2,0,0,0,2-2v-7"
                                            fill="none"
                                            stroke="white"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                        />

                                        <g>
                                            <polyline
                                                data-name="Right"
                                                fill="none"
                                                id="Right-2"
                                                points="7.9 6.7 12 2.7 16.1 6.7"
                                                stroke="white"
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                            />

                                            <line
                                                fill="none"
                                                stroke="white"
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                x1="12"
                                                x2="12"
                                                y1="16.3"
                                                y2="4.8"
                                            />
                                        </g>
                                    </g>
                                </g>
                            </g>
                        </svg>
                    </button>
                    <div
                        id="import_tooltip"
                        role="tooltip"
                        class="absolute z-10 invisible inline-block px-2 py-2 text-xs font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700"
                    >
                        Importar Excel
                        <div class="tooltip-arrow" data-popper-arrow></div>
                    </div>
                    <button
                        data-tooltip-target="export_tooltip"
                        type="button"
                        class="rounded-md bg-green-600 px-4 py-2 text-center text-sm text-white hover:bg-green-500"
                        @click="openExportExcel"
                    >
                        <svg
                            class="h-5 w-5"
                            viewBox="0 0 24 24"
                            fill="none"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                fill-rule="evenodd"
                                clip-rule="evenodd"
                                d="M9.29289 1.29289C9.48043 1.10536 9.73478 1 10 1H18C19.6569 1 21 2.34315 21 4V9C21 9.55228 20.5523 10 20 10C19.4477 10 19 9.55228 19 9V4C19 3.44772 18.5523 3 18 3H11V8C11 8.55228 10.5523 9 10 9H5V20C5 20.5523 5.44772 21 6 21H7C7.55228 21 8 21.4477 8 22C8 22.5523 7.55228 23 7 23H6C4.34315 23 3 21.6569 3 20V8C3 7.73478 3.10536 7.48043 3.29289 7.29289L9.29289 1.29289ZM6.41421 7H9V4.41421L6.41421 7ZM19 12C19.5523 12 20 12.4477 20 13V19H23C23.5523 19 24 19.4477 24 20C24 20.5523 23.5523 21 23 21H19C18.4477 21 18 20.5523 18 20V13C18 12.4477 18.4477 12 19 12ZM11.8137 12.4188C11.4927 11.9693 10.8682 11.8653 10.4188 12.1863C9.96935 12.5073 9.86526 13.1318 10.1863 13.5812L12.2711 16.5L10.1863 19.4188C9.86526 19.8682 9.96935 20.4927 10.4188 20.8137C10.8682 21.1347 11.4927 21.0307 11.8137 20.5812L13.5 18.2205L15.1863 20.5812C15.5073 21.0307 16.1318 21.1347 16.5812 20.8137C17.0307 20.4927 17.1347 19.8682 16.8137 19.4188L14.7289 16.5L16.8137 13.5812C17.1347 13.1318 17.0307 12.5073 16.5812 12.1863C16.1318 11.8653 15.5073 11.9693 15.1863 12.4188L13.5 14.7795L11.8137 12.4188Z"
                                fill="#ffffff"
                            />
                        </svg>
                    </button>
                    <div
                        id="export_tooltip"
                        role="tooltip"
                        class="absolute z-10 invisible inline-block px-2 py-2 text-xs font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700"
                    >
                        Exportar Excel
                        <div class="tooltip-arrow" data-popper-arrow></div>
                    </div>

                    <button
                        type="button"
                        class="rounded-md bg-blue-600 px-4 py-2 text-center text-sm text-white hover:bg-blue-500 h-full"
                        @click="openExportArchivesModal"
                        data-tooltip-target="export-photo-tooltip"
                    >
                        <svg
                            fill="#ffffff"
                            width="20px"
                            height="20px"
                            viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <g id="SVGRepo_bgCarrier" stroke-width="0" />

                            <g
                                id="SVGRepo_tracerCarrier"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            />

                            <g id="SVGRepo_iconCarrier">
                                <path
                                    d="M22.71,6.29a1,1,0,0,0-1.42,0L20,7.59V2a1,1,0,0,0-2,0V7.59l-1.29-1.3a1,1,0,0,0-1.42,1.42l3,3a1,1,0,0,0,.33.21.94.94,0,0,0,.76,0,1,1,0,0,0,.33-.21l3-3A1,1,0,0,0,22.71,6.29ZM19,13a1,1,0,0,0-1,1v.38L16.52,12.9a2.79,2.79,0,0,0-3.93,0l-.7.7L9.41,11.12a2.85,2.85,0,0,0-3.93,0L4,12.6V7A1,1,0,0,1,5,6h8a1,1,0,0,0,0-2H5A3,3,0,0,0,2,7V19a3,3,0,0,0,3,3H17a3,3,0,0,0,3-3V14A1,1,0,0,0,19,13ZM5,20a1,1,0,0,1-1-1V15.43l2.9-2.9a.79.79,0,0,1,1.09,0l3.17,3.17,0,0L15.46,20Zm13-1a.89.89,0,0,1-.18.53L13.31,15l.7-.7a.77.77,0,0,1,1.1,0L18,17.21Z"
                                />
                            </g>
                        </svg>
                    </button>
                    <div
                        id="export-photo-tooltip"
                        role="tooltip"
                        class="absolute z-10 invisible inline-block px-2 py-2 text-xs font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700"
                    >
                        Facturas, Boletas y Vouchers de Pago
                        <div class="tooltip-arrow" data-popper-arrow></div>
                    </div>

                    <div>
                        <dropdown align="left">
                            <template #trigger>
                                <button
                                    data-tooltip-target="action_button_tooltip"
                                    @click="dropdownOpen = !dropdownOpen"
                                    class="relative block overflow-hidden rounded-md text-white hover:bg-indigo-400 text-center text-sm bg-indigo-600 p-2"
                                >
                                    <svg
                                        width="20px"
                                        height="20px"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        xmlns="http://www.w3.org/2000/svg"
                                    >
                                        <path
                                            d="M4 6H20M4 12H20M4 18H20"
                                            stroke="#ffffff"
                                            stroke-width="2"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                        />
                                    </svg>
                                </button>
                                <div
                                    id="action_button_tooltip"
                                    role="tooltip"
                                    class="absolute z-10 invisible inline-block px-2 py-2 text-xs font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700 whitespace-nowrap"
                                >
                                    Acciones
                                    <div
                                        class="tooltip-arrow"
                                        data-popper-arrow
                                    ></div>
                                </div>
                            </template>

                            <template #content class="origin-left">
                                <div>
                                    <div class="">
                                        <button
                                            v-permission="
                                                'huawei_expenses_admin'
                                            "
                                            @click="openNuUpdateModal"
                                            class="block w-full text-left px-4 py-2 text-sm text-black-700 hover:bg-gray-200 hover:text-black focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out"
                                        >
                                            Actualizar Operación
                                        </button>
                                    </div>
                                </div>
                            </template>
                        </dropdown>
                    </div>
                </div>

                <!-- Dropdown para pantallas pequeñas -->
                <div class="sm:hidden flex-shrink-0">
                    <dropdown align="left">
                        <template #trigger>
                            <button
                                @click="dropdownOpen = !dropdownOpen"
                                class="block rounded-md bg-gray-200 px-2 py-2 text-center text-sm text-gray-700 hover:bg-gray-100"
                            >
                                <svg
                                    width="25px"
                                    height="25px"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    xmlns="http://www.w3.org/2000/svg"
                                >
                                    <path
                                        d="M4 6H20M4 12H20M4 18H20"
                                        stroke="#000000"
                                        stroke-width="2"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                    />
                                </svg>
                            </button>
                        </template>
                        <template #content>
                            <div class="dropdown">
                                <div class="dropdown-menu">
                                    <button
                                        v-permission="'huawei_expenses_add'"
                                        @click="openCreateAdditionalModal"
                                        class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-indigo-600 hover:text-white transition ease-in-out"
                                    >
                                        Agregar
                                    </button>
                                </div>
                                <div class="dropdown-menu">
                                    <button
                                        v-permission="'huawei_expenses_add'"
                                        @click="openImportExcel"
                                        class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-indigo-600 hover:text-white transition ease-in-out"
                                    >
                                        Importar
                                    </button>
                                </div>
                                <div class="dropdown-menu">
                                    <button
                                        @click="openExportExcel"
                                        class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-indigo-600 hover:text-white transition ease-in-out"
                                    >
                                        Exportar
                                    </button>
                                </div>
                                <div class="dropdown-menu">
                                    <button
                                        v-permission="'huawei_expenses_admin'"
                                        @click="openNuUpdateModal"
                                        class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-indigo-600 hover:text-white transition ease-in-out"
                                    >
                                        Actualizar Operación
                                    </button>
                                </div>
                            </div>
                        </template>
                    </dropdown>
                </div>

                <!-- Buscador siempre alineado a la derecha -->
                <div class="flex-grow sm:flex sm:justify-end">
                    <form
                        @submit.prevent="search"
                        class="flex items-center w-full justify-end"
                    >
                        <TextInput
                            type="text"
                            placeholder="Buscar..."
                            v-model="searchForm.searchTerm"
                            class="flex-grow mr-2 max-w-[200px]"
                        />
                        <button
                            type="submit"
                            :class="{ 'opacity-25': searchForm.processing }"
                            class="ml-2 rounded-md bg-indigo-600 px-2 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                        >
                            <svg
                                width="20px"
                                height="20px"
                                viewBox="0 0 24 24"
                                fill="none"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path
                                    d="M15.7955 15.8111L21 21M18 10.5C18 14.6421 14.6421 18 10.5 18C6.35786 18 3 14.6421 3 10.5C3 6.35786 6.35786 3 10.5 3C14.6421 3 18 6.35786 18 10.5Z"
                                    stroke="white"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <ExpensesTable
            :expenses="expenses"
            :search="props.search"
            :mode="props.mode"
            :employees="props.data.employees"
            :expense-types="
                props.mode
                    ? props.data.static_expense_types
                    : props.data.variable_expense_types
            "
            :cdp-types="props.data.cdp_types"
            :op-numbers="props.summary.op_numbers"
            :zones="props.summary.zones"
            :assigned_dius="props.summary.assigned_dius"
            @edit="openEditAdditionalModal"
            @delete="confirmDeleteAdditional"
            @options="handleOptions"
            @data="searchAdvance"
            @validate="validateExpense"
        />

        <MainFormModal
            :show="create_additional"
            :close="closeModal"
            :edit-form="editForm"
            :macro-projects="props.data.macro_projects"
            :expense-types="
                props.mode
                    ? props.data.static_expense_types
                    : props.data.variable_expense_types
            "
            :cdp-types="props.data.cdp_types"
            :employees="props.data.employees"
            @update="updateExpense"
        />

        <OperationModal
            :show="showOpNuDatModal"
            :close="closeOpNuDatModal"
            :action-form="actionForm"
            @update="updateNu"
        />

        <ImportModal
            :show="show_import"
            :is-fetching="isFetching"
            :close="openImportExcel"
        />
        <ConfirmDeleteModal
            :confirmingDeletion="confirmingDocDeletion"
            itemType="Gasto"
            :deleteFunction="deleteAdditional"
            @closeModal="closeModalDoc"
        />
        <ConfirmateModal
            tittle="Descarga de archivos"
            text="La descarga de archivos será en base a los filtros que están activos, si no hay filtros activos se descargarán de todos los registros. PARA AMBOS CASOS SOLO ES PARA REGISTROS ACEPTADOS"
            :showConfirm="showExportArchivesModal"
            :actionFunction="exportArchives"
            @closeModal="closeExportArchivesModal"
        />
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import ConfirmDeleteModal from "@/Components/ConfirmDeleteModal.vue";
import ConfirmateModal from "@/Components/ConfirmateModal.vue";
import { ref, } from "vue";
import { Head, useForm, router } from "@inertiajs/vue3";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import axios from "axios";
import TextInput from "@/Components/TextInput.vue";
import Dropdown from "@/Components/Dropdown.vue";
import { notify, notifyError, notifyWarning } from "@/Components/Notification";
import { Toaster } from "vue-sonner";
import qs from "qs";
import ExpensesTable from "./components/ExpensesTable.vue";
import MainFormModal from "./components/MainFormModal.vue";
import OperationModal from "./components/OperationModal.vue";
import ImportModal from "./components/ImportModal.vue";

const props = defineProps({
    expense: Object,
    search: String,
    summary: Object,
    data: Object,
    mode: String,
});

const expenses = ref(props.expense);
const showOpNuDatModal = ref(false);
const create_additional = ref(false);
const confirmingDocDeletion = ref(false);
const docToDelete = ref(null);
const sites = ref([]);
const projects = ref([]);
const selectedMacro = ref("");
const selectedSite = ref("");
const isFetching = ref(false);
const show_import = ref(false);

const openCreateAdditionalModal = () => {
    create_additional.value = true;
};

const editForm = ref({});

const openEditAdditionalModal = async (additional) => {
    editForm.value = { ...additional };
    create_additional.value = true;
};

const closeModal = () => {
    (selectedMacro.value = ""), (selectedSite.value = "");
    create_additional.value = false;
};

const confirmDeleteAdditional = (additionalId) => {
    docToDelete.value = additionalId;
    confirmingDocDeletion.value = true;
};

const closeModalDoc = () => {
    isFetching.value = false;
    confirmingDocDeletion.value = false;
};

async function deleteAdditional() {
    isFetching.value = true;
    const docId = docToDelete.value;
    if (docId) {
        const response = await axios.delete(
            route("huawei.projects.general.expenses.delete", { expense: docId })
        );
        if (response.data == true) {
            const index = expenses.value.findIndex((item) => item.id === docId);
            if (index !== -1) {
                expenses.value.splice(index, 1);
                closeModalDoc();
                notify("Registro eliminado correctamente");
            }
        }
    }
}

function openExportExcel() {
    const url = route("huawei.projects.general.expenses.export", {
        mode: props.mode,
    });
    window.location.href = url;
}

const searchForm = useForm({
    searchTerm: props.search ? props.search : "",
});

const search = () => {
    if (searchForm.searchTerm == "") {
        router.visit(
            route("huawei.projects.general.expenses", { mode: props.mode })
        );
    } else {
        router.visit(
            route("huawei.projects.general.expenses.search", {
                request: searchForm.searchTerm,
                mode: props.mode,
            })
        );
    }
};

const searchAdvance = (data) => {
    expenses.value = data;
};

const actionForm = ref({
    ids: [],
});

function handleOptions(newIds) {
    actionForm.value.ids = newIds;
}

const openNuUpdateModal = () => {
    if (actionForm.value.ids.length === 0) {
        notifyWarning("No hay registros selccionados");
        return;
    }
    showOpNuDatModal.value = true;
};

const closeOpNuDatModal = () => {
    isFetching.value = false;
    showOpNuDatModal.value = false;
};

const openImportExcel = () => {
    show_import.value = !show_import.value;
    sites.value = [];
    projects.value = [];
};

const showExportArchivesModal = ref(false);
const openExportArchivesModal = () => {
    showExportArchivesModal.value = true;
};
const closeExportArchivesModal = () => {
    showExportArchivesModal.value = false;
};
function exportArchives() {
    const uniqueParam = `timestamp=${new Date().getTime()}`;
    const url =
        route("huawei.projects.monthlyexpenses.downloadimages", {
            mode: props.mode,
        }) +
        "?" +
        qs.stringify(
            { ...filterForm.value, uniqueParam },
            { arrayFormat: "brackets" }
        );
    window.location.href = url;
    closeExportArchivesModal();
}

function updateNu(updateValue) {
    const originalMap = new Map(expenses.value.map((item) => [item.id, item]));
    updateValue.forEach((update) => {
        if (originalMap.has(update.id)) {
            originalMap.set(update.id, update);
        }
    });
    const updatedArray = Array.from(originalMap.values());
    expenses.value = updatedArray;
    closeOpNuDatModal();
    notify("Registros Seleccionados Actualizados");
}

function updateExpense({ expense, mode }) {
    if (expense === "error") {
        notifyError("Server Error");
        return;
    }
    const originalMap = new Map(expenses.value.map((item) => [item.id, item]));
    const newExpense = expense;
    newExpense.amount = Number(newExpense.amount);
    newExpense.ec_amount = Number(newExpense.ec_amount);

    originalMap.set(newExpense.id, newExpense);
    expenses.value = Array.from(originalMap.values());
    closeModal();
    notify(
        mode
            ? "Se actualizó el registro correctamente"
            : "Se creó el registro correctamente"
    );
}

function validateExpense({validate, is_accepted}) {
    if (validate === "error") {
        notifyError("Server Error");
        return;
    }
    const originalMap = new Map(expenses.value.map((item) => [item.id, item]));
    const newExpense = validate;
    newExpense.amount = Number(newExpense.amount);
    newExpense.ec_amount = Number(newExpense.ec_amount);

    originalMap.set(newExpense.id, newExpense);
    expenses.value = Array.from(originalMap.values());
    notify(
        is_accepted
            ? "Registro validado correctamente"
            : "Registro rechazado correctamente"
    );
}
</script>
