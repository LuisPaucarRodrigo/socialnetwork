<template>

    <Head title="Gestion de Ingresos Reales" />
    <AuthenticatedLayout :redirectRoute="{ route: 'huawei.projects', params: { status: backStatus, prefix: 'Claro' } }">
        <template #header>
            Ingresos Actuales del Proyecto {{ props.huawei_project.name }}
        </template>
        <div class="flex flex-col sm:flex-row gap-4 justify-between rounded-lg p-4">
            <!-- Botones principales visibles en pantallas grandes -->
            <div class="flex flex-col sm:flex-row gap-4 items-center">
                <button @click.prevent="openCreateAdditionalModal" type="button" v-if="props.huawei_project.status"
                    class="hidden sm:block rounded-md bg-indigo-600 px-4 py-2 text-center text-sm text-white hover:bg-indigo-500 whitespace-nowrap">
                    + Agregar
                </button>
                <button @click.prevent="openImportModal" type="button" v-if="props.huawei_project.status"
                    class="hidden sm:block rounded-md bg-green-600 px-4 py-2 text-center text-sm text-white hover:bg-green-500 whitespace-nowrap">
                    Importar Datos
                </button>
                <a :href="route('huawei.projects.realearnings.export', { huawei_project: props.huawei_project })"
                    type="button"
                    class="hidden sm:block rounded-md bg-green-600 px-4 py-2 text-center text-sm text-white hover:bg-green-500 whitespace-nowrap">
                    Exportar Datos
                </a>
                <!-- Menú desplegable visible en pantallas pequeñas -->
                <div class="sm:hidden flex items-center">
                    <dropdown align="left">
                        <template #trigger>
                            <button @click="dropdownOpen = !dropdownOpen"
                                class="relative block overflow-hidden rounded-md bg-gray-200 px-2 py-2 text-center text-sm text-white hover:bg-gray-100">
                                <svg width="25px" height="25px" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path d="M4 6H20M4 12H20M4 18H20" stroke="#000000" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </button>
                        </template>

                        <template #content class="origin-left bg-white border rounded-lg shadow-lg mt-2">
                            <div>
                                <button @click.prevent="openCreateAdditionalModal" type="button"
                                    class="dropdown-item block w-full text-left px-4 py-2 text-sm text-black-700 hover:bg-indigo-600 hover:text-white focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                                    + Agregar
                                </button>
                                <button @click.prevent="openImportModal" type="button"
                                    class="dropdown-item block w-full text-left px-4 py-2 text-sm text-black-700 hover:bg-indigo-600 hover:text-white focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                                    Importar Datos
                                </button>
                                <a :href="route('huawei.projects.realearnings.export', { huawei_project: props.huawei_project })"
                                    type="button"
                                    class="dropdown-item block w-full text-left px-4 py-2 text-sm text-black-700 hover:bg-indigo-600 hover:text-white focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                                    Exportar Datos
                                </a>
                            </div>
                        </template>
                    </dropdown>
                </div>
            </div>

            <!-- Total y formulario de búsqueda -->
            <div class="flex flex-col sm:flex-row items-center gap-4 sm:gap-6">
                <p class="whitespace-nowrap text-sm font-semibold">Total: {{ props.total ? props.total.toFixed(2) : ''
                }}</p>
                <form @submit.prevent="search" class="flex items-center gap-2">
                    <TextInput type="text" placeholder="Buscar..." v-model="searchForm.searchTerm"
                        class="mr-2 min-w-[150px] sm:min-w-[200px]" />
                    <button type="submit" :class="{ 'opacity-25': searchForm.processing }"
                        class="flex items-center rounded-md bg-indigo-600 px-2 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                        <svg width="24px" height="24px" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M15.7955 15.8111L21 21M18 10.5C18 14.6421 14.6421 18 10.5 18C6.35786 18 3 14.6421 3 10.5C3 6.35786 6.35786 3 10.5 3C14.6421 3 18 6.35786 18 10.5Z"
                                stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </button>
                </form>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full whitespace-no-wrap">
                <thead>
                    <tr
                        class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-center text-gray-600">
                            N° de Factura</th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-center text-gray-600 min-w-[300px]">
                            Monto</th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-center text-gray-600">
                            Fecha de Facturación</th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-center text-gray-600">
                            Fecha de Depósito</th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-center text-gray-600">
                            Monto BCP</th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-center text-gray-600">
                            N° Operación BCP</th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-center text-gray-600">
                            Fecha de Detracción</th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-center text-gray-600">
                            Monto de Detracción</th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-center text-gray-600">
                            N° Operación Detracción</th>
                        <th v-if="props.huawei_project.status"
                            class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-center text-gray-600">
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="item in (props.search ? props.real_earnings : real_earnings.data)" :key="item.id"
                        class="text-gray-700">
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center whitespace-nowrap">{{
                            item.invoice_number }}</td>
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">S/. {{
                            item.amount.toFixed(2) }}</td>
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">{{
                            formattedDate(item.invoice_date) }}</td>
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center whitespace-nowrap">{{
                            formattedDate(item.deposit_date) }}</td>
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center whitespace-nowrap">{{
                            item.main_amount ? "S/. " + item.main_amount.toFixed(2) : '' }}</td>
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center whitespace-nowrap">{{
                            item.main_op_number }}</td>
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center whitespace-nowrap">{{
                            item.detraction_date ? formattedDate(item.detraction_date) : '' }}</td>
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center whitespace-nowrap">{{
                            item.detraction_amount ? "S/. " + item.detraction_amount.toFixed(2) : '' }}</td>
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center whitespace-nowrap">{{
                            item.detraction_op_number }}</td>
                        <td v-if="props.huawei_project.status"
                            class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">
                            <div class="flex justify-center items-center">
                                <button @click="openEditAdditionalModal(item)">
                                    <EditIcon />
                                </button>
                                <button @click="confirmDeleteAdditional(item.id)">
                                    <DeleteIcon />
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div v-if="!props.search"
            class="flex flex-col items-center border-t bg-white px-5 py-5 xs:flex-row xs:justify-between">
            <pagination :links="props.real_earnings.links" />
        </div>

        <Modal :show="create_additional || editAdditionalModal">
            <div class="p-6">
                <h2 class="text-base font-medium leading-7 text-gray-900">
                    {{ create_additional ? 'Agregar Ingreso' : 'Actualizar Ingreso' }}
                </h2>
                <form @submit.prevent="create_additional ? submit(false) : submit(true)">
                    <div class="space-y-12">
                        <div class="border-b border-gray-900/10 pb-12">

                            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">

                                <div class="col-span-1">
                                    <InputLabel for="invoice_number" class="font-medium leading-6 text-gray-900">Nª de
                                        Factura
                                    </InputLabel>
                                    <div class="mt-2">
                                        <input type="text" v-model="form.invoice_number" id="invoice_number"
                                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                        <InputError :message="form.errors.invoice_number" />
                                    </div>
                                </div>

                                <div class="col-span-1">
                                    <InputLabel for="amount" class="font-medium leading-6 text-gray-900">Monto
                                    </InputLabel>
                                    <div class="mt-2">
                                        <input type="number" step="0.01" v-model="form.amount" id="amount"
                                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                        <InputError :message="form.errors.amount" />
                                    </div>
                                </div>

                                <div class="col-span-1">
                                    <InputLabel for="main_amount" class="font-medium leading-6 text-gray-900">Monto BCP
                                    </InputLabel>
                                    <div class="mt-2">
                                        <input type="number" step="0.01" v-model="form.main_amount" id="main_amount"
                                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                        <InputError :message="form.errors.main_amount" />
                                    </div>
                                </div>

                                <div class="col-span-1">
                                    <InputLabel for="detraction_amount" class="font-medium leading-6 text-gray-900">
                                        Monto
                                        Detracción</InputLabel>
                                    <div class="mt-2">
                                        <input type="number" step="0.01" v-model="form.detraction_amount"
                                            id="detraction_amount"
                                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                        <InputError :message="form.errors.detraction_amount" />
                                    </div>
                                </div>

                                <div class="col-span-1">
                                    <InputLabel for="main_op_number" class="font-medium leading-6 text-gray-900">N°
                                        Operación
                                        BCP</InputLabel>
                                    <div class="mt-2">
                                        <input type="text" v-model="form.main_op_number" id="main_op_number"
                                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                        <InputError :message="form.errors.main_op_number" />
                                    </div>
                                </div>

                                <div class="col-span-1">
                                    <InputLabel for="detraction_op_number" class="font-medium leading-6 text-gray-900">
                                        N°
                                        Operación Detracción</InputLabel>
                                    <div class="mt-2">
                                        <input type="text" v-model="form.detraction_op_number" id="detraction_op_number"
                                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                        <InputError :message="form.errors.detraction_op_number" />
                                    </div>
                                </div>

                                <div class="col-span-1">
                                    <InputLabel for="invoice_date" class="font-medium leading-6 text-gray-900">Fecha de
                                        Facturación</InputLabel>
                                    <div class="mt-2">
                                        <input type="date" v-model="form.invoice_date" id="invoice_date"
                                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                        <InputError :message="form.errors.invoice_date" />
                                    </div>
                                </div>

                                <div class="col-span-1">
                                    <InputLabel for="deposit_date" class="font-medium leading-6 text-gray-900">Fecha de
                                        Depósito
                                    </InputLabel>
                                    <div class="mt-2">
                                        <input type="date" v-model="form.deposit_date" id="deposit_date"
                                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                        <InputError :message="form.errors.deposit_date" />
                                    </div>
                                </div>

                                <div class="col-span-1">
                                    <InputLabel for="detraction_date" class="font-medium leading-6 text-gray-900">Fecha
                                        de
                                        Detracción</InputLabel>
                                    <div class="mt-2">
                                        <input type="date" v-model="form.detraction_date" id="detraction_date"
                                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                        <InputError :message="form.errors.detraction_date" />
                                    </div>
                                </div>

                            </div>

                            <div class="mt-6 flex items-center justify-end gap-x-6">
                                <SecondaryButton @click="closeModals">
                                    Cancelar
                                </SecondaryButton>
                                <button type="submit" :class="{ 'opacity-25': form.processing }"
                                    class="rounded-md bg-indigo-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Guardar</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </Modal>

        <Modal :show="importModal">
            <div class="p-6">
                <h2 class="text-base font-medium leading-7 text-gray-900">
                    Importar Excel
                </h2>
                <form @submit.prevent="verify">
                    <div class="space-y-12">
                        <div class="border-b border-gray-900/10 pb-12">
                            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">

                                <div class="col-span-2">
                                    <InputLabel for="file" class="font-medium leading-6 text-gray-900">Archivo
                                    </InputLabel>
                                    <div class="mt-2">
                                        <InputFile type="file" accept="xls,xlsx" v-model="importForm.file" id="file"
                                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                        <InputError :message="importForm.errors.file" />
                                    </div>
                                </div>
                            </div>

                            <div class="mt-6 flex items-center justify-end gap-x-6">
                                <SecondaryButton @click="closeImportModal">
                                    Cancelar
                                </SecondaryButton>
                                <button type="submit" :class="{ 'opacity-25': form.processing }"
                                    class="rounded-md bg-indigo-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Guardar</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </Modal>

        <Modal :show="verifyModal" :maxWidth="'sm'">
            <div class="p-6">
                <h2 class="text-base font-medium leading-7 text-gray-900 text-center">
                    ¿Está seguro de importar el excel?
                </h2>
                <p class="mt-1 text-sm text-gray-600 text-wrap">
                    Existen registros con el mismo N° Factura de los que se desean importar, los cuales se actualizarán
                    con la
                    nueva información.
                </p>
                <div class="space-y-12">
                    <div class="border-gray-900/10">
                        <div class="mt-6 flex items-center justify-end gap-x-3">
                            <SecondaryButton @click="reject"> No </SecondaryButton>
                            <PrimaryButton @click="importExcel"
                                class="rounded-md bg-indigo-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                                Si</PrimaryButton>
                        </div>
                    </div>
                </div>
            </div>
        </Modal>

        <ConfirmDeleteModal :confirmingDeletion="confirmingDocDeletion" itemType="Ingreso"
            :deleteFunction="deleteAdditional" @closeModal="closeModalDoc" />
        <ConfirmCreateModal :confirmingcreation="showModal" itemType="Ingreso" />
        <ConfirmUpdateModal :confirmingupdate="showModalEdit" itemType="Ingreso" />
        <SuccessOperationModal :confirming="confirmImport" :title="'Éxito'"
            :message="'Se importaron los datos correctamente.'" />
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ConfirmCreateModal from '@/Components/ConfirmCreateModal.vue';
import ConfirmUpdateModal from '@/Components/ConfirmUpdateModal.vue';
import ConfirmDeleteModal from '@/Components/ConfirmDeleteModal.vue';
import SuccessOperationModal from '@/Components/SuccessOperationModal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import InputError from '@/Components/InputError.vue';
import InputFile from '@/Components/InputFile.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Modal from '@/Components/Modal.vue';
import { ref, watch } from 'vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import TextInput from '@/Components/TextInput.vue';
import Pagination from '@/Components/Pagination.vue';
import Dropdown from '@/Components/Dropdown.vue';
import { formattedDate } from '@/utils/utils';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { DeleteIcon, EditIcon } from '@/Components/Icons/Index';

const props = defineProps({
    real_earnings: Object,
    huawei_project: Object,
    auth: Object,
    userPermissions: Array,
    search: String,
    total: Number
});

const backStatus = props.huawei_project.status == 1 ? '1' : (props.huawei_project.status == null ? '2' : '3');

const form = useForm({
    id: '',
    invoice_number: '',
    amount: '',
    invoice_date: '',
    deposit_date: '',
    main_amount: '',
    main_op_number: '',
    detraction_amount: '',
    detraction_date: '',
    detraction_op_number: '',
    huawei_project_id: props.huawei_project.id,
});

const importForm = useForm({
    file: null,
    zone: ''
});

const create_additional = ref(false);
const showModal = ref(false);
const showModalEdit = ref(false);
const confirmingDocDeletion = ref(false);
const docToDelete = ref(null);
const editAdditionalModal = ref(false);
const editingAdditional = ref(null);
const importModal = ref(false);
const confirmImport = ref(false);
const dropdownOpen = ref(false);
const originalAmount = ref(null);
const verifyModal = ref(false);

watch(() => form.amount, (newValue) => {
    if (newValue && !form.id) {
        form.main_amount = (newValue * 0.88).toFixed(2);
        form.detraction_amount = (newValue * 0.12).toFixed(2);
    }
    if (form.id && newValue !== originalAmount.value) {
        form.main_amount = (newValue * 0.88).toFixed(2);
        form.detraction_amount = (newValue * 0.12).toFixed(2);
    }
});

const reject = () => {
    importForm.reset();
    importForm.clearErrors();
    verifyModal.value = false;
    importModal.value = false;
}


const openImportModal = () => {
    importModal.value = true;
}

const closeImportModal = () => {
    importForm.reset();
    importForm.clearErrors();
    importModal.value = false;
}

const openCreateAdditionalModal = () => {
    create_additional.value = true;
};

const closeCreateModal = () => {
    originalAmount.value = 0;
    form.reset();
    form.clearErrors();
    create_additional.value = false;
}

const closeEditModal = () => {
    originalAmount.value = 0;
    form.reset();
    form.clearErrors();
    editAdditionalModal.value = false;
}

const openEditAdditionalModal = (additional) => {
    // Copia de los datos de la subsección existente al formulario
    editingAdditional.value = JSON.parse(JSON.stringify(additional));
    form.id = editingAdditional.value.id;
    form.invoice_number = editingAdditional.value.invoice_number;
    form.amount = editingAdditional.value.amount;
    form.invoice_date = editingAdditional.value.invoice_date;
    form.deposit_date = editingAdditional.value.deposit_date;
    form.main_amount = editingAdditional.value.main_amount;
    form.main_op_number = editingAdditional.value.main_op_number;
    form.detraction_amount = editingAdditional.value.detraction_amount;
    form.detraction_op_number = editingAdditional.value.detraction_op_number;
    form.detraction_date = editingAdditional.value.detraction_date;
    originalAmount.value = editingAdditional.value.amount;
    editAdditionalModal.value = true;
};

const closeModals = () => {
    originalAmount.value = 0;
    form.clearErrors();
    form.reset();
    editAdditionalModal.value = false;
    create_additional.value = false;
};

const verify = () => {
    let formData = new FormData();
    formData.append('file', importForm.file); // Agrega el archivo al FormData

    axios.post(route('huawei.projects.realearnings.verify', { huawei_project: props.huawei_project.id }), formData, {
        headers: {
            'Content-Type': 'multipart/form-data', // Especifica que estás enviando un archivo
        },
    })
        .then(res => {
            if (res.data.message == 'found') {
                verifyModal.value = true;
            } else {
                importExcel();
            }
        })
        .catch(error => {
            console.error('Error en la petición:', error);
        });
};

const submit = (update) => {
    if (!update) {
        form.post(route('huawei.projects.realearnings.store'), {
            onSuccess: () => {
                closeCreateModal();
                form.reset();
                showModal.value = true;
                setTimeout(() => {
                    showModal.value = false;
                }, 2000);
            }
        });
    } else {
        form.put(route('huawei.projects.realearnings.update', { huawei_project_real_earning: form.id }), {
            onSuccess: () => {
                closeEditModal();
                form.reset();
                showModalEdit.value = true;
                setTimeout(() => {
                    showModalEdit.value = false;
                }, 2000);
            }
        });
    }
};

const importExcel = () => {
    importForm.post(route('huawei.projects.realearnings.import', { huawei_project: props.huawei_project.id }), {
        onSuccess: () => {
            closeImportModal();
            confirmImport.value = true;
            verifyModal.value = false;
            setTimeout(() => {
                confirmImport.value = false;
            }, 2000);
        },
        onError: (e) => {
            console.error(e);
        }
    })
}

const confirmDeleteAdditional = (additionalId) => {
    docToDelete.value = additionalId;
    confirmingDocDeletion.value = true;
};

const closeModalDoc = () => {
    confirmingDocDeletion.value = false;
};

const deleteAdditional = () => {
    const docId = docToDelete.value;
    if (docId) {
        router.delete(route('huawei.projects.realearnings.delete', { huawei_project_real_earning: docId }), {
            onSuccess: () => closeModalDoc()
        });
    }
};

const searchForm = useForm({
    searchTerm: props.search ? props.search : '',
});

const search = () => {
    if (searchForm.searchTerm == '') {
        router.visit(route('huawei.projects.realearnings', { huawei_project: props.huawei_project.id }));
    } else {
        router.visit(route('huawei.projects.realearnings.search', { huawei_project: props.huawei_project.id, request: searchForm.searchTerm }));
    }
}

</script>
