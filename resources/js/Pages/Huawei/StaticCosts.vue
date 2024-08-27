<template>

    <Head title="Gestion de Costos Adicionales" />
    <AuthenticatedLayout
      :redirectRoute="{ route: 'huawei.projects.additionalcosts.summary', params: {huawei_project: props.huawei_project.id}}">
      <template #header>
        Costos Fijos del Proyecto {{ props.huawei_project.name }}
      </template>
      <div class="inline-block min-w-full overflow-hidden rounded-lg">
        <div class="flex items-center justify-end gap-4">
        <PrimaryButton v-if="props.huawei_project.status" @click="openCreateAdditionalModal" type="button" class="whitespace-nowrap">
            + Agregar
        </PrimaryButton>
        <a :href="route('huawei.projects.staticcosts.export', {huawei_project: props.huawei_project})" type="button"
            class="hidden sm:block rounded-md bg-green-600 px-4 py-2 text-center text-sm text-white hover:bg-green-500 whitespace-nowrap">
            Exportar Datos
        </a>
        <div class="flex items-center ml-auto"> <!-- Alinear elementos horizontalmente -->
            <form @submit.prevent="search" class="flex items-center">
                <TextInput type="text" placeholder="Buscar..." v-model="searchForm.searchTerm" class="mr-2" />
                <!-- Estilo para el botón de búsqueda -->
                <button type="submit"
                        :class="{ 'opacity-25': searchForm.processing }"
                        class="rounded-md bg-indigo-600 px-2 py-2 whitespace-no-wrap text-white shadow-sm hover:bg-indigo-500 focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-600 focus-visible:border-transparent">
                    <svg width="30px" height="21px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M15.7955 15.8111L21 21M18 10.5C18 14.6421 14.6421 18 10.5 18C6.35786 18 3 14.6421 3 10.5C3 6.35786 6.35786 3 10.5 3C14.6421 3 18 6.35786 18 10.5Z"
                            stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </button>
            </form>
        </div>
    </div>


      </div>
      <div class="overflow-x-auto h-[85vh]">
        <table class="w-full whitespace-no-wrap">
          <thead>
            <tr class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
              <th
                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                <TableHeaderFilter
                                labelClass="text-[11px]"
                                label="Tipo de Gasto"
                                :options="expenseTypes"
                                v-model="filterForm.selectedExpenseTypes"
                                width="w-48"
                            />
              </th>
              <th
                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                RUC</th>
              <th
                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">

                <TableHeaderFilter
                                labelClass="text-[11px]"
                                label="Zona"
                                :options="zones"
                                v-model="filterForm.selectedZones"
                                width="w-32"
                            />
              </th>
              <th
                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                <TableHeaderFilter
                                labelClass="text-[11px]"
                                label="Tipo de Documento"
                                :options="docTypes"
                                v-model="filterForm.selectedDocTypes"
                                width="w-32"
                            />
              </th>
              <th
                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                Numero de Doc</th>
              <th
                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                Fecha de Documento</th>
              <th
                class="border-b-2 border-gray-200 bg-gray-100 px-8 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                Monto</th>
              <th
                class="border-b-2 border-gray-200 bg-gray-100 px-8 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                Archivo</th>
              <th
                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                Descripción</th>
              <th v-if="props.huawei_project.status"
                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                Acciones</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="item in (props.search ? props.additional_costs : dataToRender)" :key="item.id" class="text-gray-700">
              <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm min-w-[150px]">{{ item.expense_type }}</td>
              <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">{{ item.ruc }}</td>
              <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">{{ item.zone }}</td>
              <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">{{ item.type_doc }}</td>
              <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">{{ item.doc_number }}</td>
              <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">{{ formattedDate(item.doc_date) }}</td>
              <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm whitespace-nowrap">{{ item.amount ? 'S/. ' + item.amount.toFixed(2) : '-' }}</td>
              <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">
                <button v-if="item.archive" @click="openArchive(item.id)" class="text-green-600 hover:underline mr-2">
                    <EyeIcon class="h-5 w-5 ml-1" />
                  </button>
              </td>
              <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">{{ item.description }}</td>
              <td v-if="props.huawei_project.status"
                class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                <div class="flex items-center">
                  <button @click="openEditAdditionalModal(item)" class="text-orange-400 hover:underline mr-2">
                    <PencilSquareIcon class="h-5 w-5 ml-1" />
                  </button>
                  <button @click="confirmDeleteAdditional(item.id)" class="text-red-600 hover:underline">
                    <TrashIcon class="h-5 w-5" />
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <div v-if="!props.search" class="flex flex-col items-center border-t bg-white px-5 py-5 xs:flex-row xs:justify-between">
        <pagination :links="props.additional_costs.links" />
      </div>
      <Modal :show="create_additional || editAdditionalModal">
    <div class="p-6">
        <h2 class="text-base font-medium leading-7 text-gray-900">
            {{ create_additional ? 'Agregar Costo Adicional' : 'Actualizar Costo Adicional' }}
        </h2>
        <form @submit.prevent="create_additional ? submit(false) : submit(true)">
            <div class="space-y-12">
                <div class="border-b border-gray-900/10 pb-12">
                    <!-- Cambiado a grid-cols-1 en pantallas pequeñas, y grid-cols-2 en pantallas medianas y grandes -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <InputLabel for="expense_type" class="font-medium leading-6 text-gray-900">Tipo de Gasto</InputLabel>
                            <div class="mt-2">
                                <select v-model="form.expense_type" id="expense_type"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option disabled value="">Seleccionar Gasto</option>
                                    <option>Hospedaje</option>
                                    <option>Movilidad</option>
                                    <option>Peaje</option>
                                    <option>Seguros y Pólizas</option>
                                    <option>Herramientas</option>
                                    <option>Fletes</option>
                                    <option>EPPs</option>
                                    <option>Gastos de Representación</option>
                                    <option>Consumibles</option>
                                    <option>Equipos</option>
                                    <option>Otros</option>
                                </select>
                                <InputError :message="form.errors.expense_type" />
                            </div>
                        </div>
                        <div>
                            <InputLabel for="ruc" class="font-medium leading-6 text-gray-900">RUC / DNI</InputLabel>
                            <div class="mt-2">
                                <input type="text" v-model="form.ruc" id="ruc" minlength="11" maxlength="11"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.ruc" />
                            </div>
                        </div>
                        <div>
                            <InputLabel for="zone" class="font-medium leading-6 text-gray-900">Zona</InputLabel>
                            <div class="mt-2">
                                <select v-model="form.zone" id="zone"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option disabled value="">Seleccionar Zona</option>
                                    <option>Arequipa</option>
                                    <option>Chala</option>
                                    <option>Moquegua</option>
                                    <option>Tacna</option>
                                    <option>MDD</option>
                                    <option>Otros</option>
                                </select>
                                <InputError :message="form.errors.zone" />
                            </div>
                        </div>
                        <div>
                            <InputLabel for="type_doc" class="font-medium leading-6 text-gray-900">Tipo de Documento</InputLabel>
                            <div class="mt-2">
                                <select v-model="form.type_doc" id="type_doc"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option disabled value="">Seleccionar Documento</option>
                                    <option>Efectivo</option>
                                    <option>Deposito</option>
                                    <option>Factura</option>
                                    <option>Boleta</option>
                                    <option>Voucher de Pago</option>
                                </select>
                                <InputError :message="form.errors.type_doc" />
                            </div>
                        </div>
                        <div>
                            <InputLabel for="doc_number" class="font-medium leading-6 text-gray-900">Numero de Documento</InputLabel>
                            <div class="mt-2">
                                <input type="text" v-model="form.doc_number" id="doc_number"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.doc_number" />
                            </div>
                        </div>
                        <div>
                            <InputLabel for="doc_date" class="font-medium leading-6 text-gray-900">Fecha de Documento</InputLabel>
                            <div class="mt-2">
                                <input type="date" v-model="form.doc_date" id="doc_date"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.doc_date" />
                            </div>
                        </div>
                        <div>
                            <InputLabel for="amount" class="font-medium leading-6 text-gray-900">Monto</InputLabel>
                            <div class="mt-2">
                                <input type="number" step="0.01" v-model="form.amount" id="amount"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.amount" />
                            </div>
                        </div>
                        <div>
                            <InputLabel for="description" class="font-medium leading-6 text-gray-900">Descripción</InputLabel>
                            <div class="mt-2">
                                <textarea v-model="form.description" id="description"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"></textarea>
                                <InputError :message="form.errors.description" />
                            </div>
                        </div>
                        <div class="md:col-span-2">
                            <InputLabel for="archive" class="font-medium leading-6 text-gray-900">Archivo</InputLabel>
                            <div class="mt-2">
                                <InputFile for="archive" v-model="form.archive" :accept="'.pdf'" class="font-medium leading-6 text-gray-900"/>
                                <InputError :message="form.errors.archive" />
                            </div>
                        </div>
                    </div>
                    <div class="mt-6 flex items-center justify-end gap-x-6">
                        <SecondaryButton @click="closeModals">Cancelar</SecondaryButton>
                        <button type="submit" :class="{ 'opacity-25': form.processing }"
                            class="rounded-md bg-indigo-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Guardar</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</Modal>


      <ConfirmDeleteModal :confirmingDeletion="confirmingDocDeletion" itemType="Costo Adicional"
        :deleteFunction="deleteAdditional" @closeModal="closeModalDoc" />
      <ConfirmCreateModal :confirmingcreation="showModal" itemType="Costo Adicional" />
      <ConfirmUpdateModal :confirmingupdate="showModalEdit" itemType="Costo Adicional" />
    </AuthenticatedLayout>
  </template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ConfirmCreateModal from '@/Components/ConfirmCreateModal.vue';
import ConfirmUpdateModal from '@/Components/ConfirmUpdateModal.vue';
import ConfirmDeleteModal from '@/Components/ConfirmDeleteModal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputFile from '@/Components/InputFile.vue';
import Modal from '@/Components/Modal.vue';
import { ref, watch } from 'vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import { TrashIcon, PencilSquareIcon, EyeIcon } from '@heroicons/vue/24/outline';
import { formattedDate } from '@/utils/utils';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import Pagination from '@/Components/Pagination.vue';
import TableHeaderFilter from "@/Components/TableHeaderFilter.vue";

const props = defineProps({
  additional_costs: Object,
  huawei_project: Object,
  auth: Object,
  userPermissions: Array,
  search: String
});

const dataToRender = ref(props.additional_costs.data);
const filterMode = ref(false);

const hasPermission = (permission) => {
  return props.userPermissions.includes(permission);
};

const form = useForm({
  id: '',
  expense_type: '',
  zone: '',
  ruc: '',
  type_doc: '',
  doc_number: '',
  doc_date: '',
  description: '',
  amount: null,
  archive: ''
});

const create_additional = ref(false);
const showModal = ref(false);
const showModalEdit = ref(false);
const confirmingDocDeletion = ref(false);
const docToDelete = ref(null);
const editAdditionalModal = ref(false);
const editingAdditional = ref(null);

const openCreateAdditionalModal = () => {
  create_additional.value = true;
};

const closeCreateModal = () => {
    form.reset();
    form.clearErrors();
    create_additional.value = false;
}

const closeEditModal = () => {
    form.reset();
    form.clearErrors();
    editAdditionalModal.value = false;
}

const openEditAdditionalModal = (additional) => {
  // Copia de los datos de la subsección existente al formulario
  editingAdditional.value = JSON.parse(JSON.stringify(additional));
  form.id = editingAdditional.value.id;
  form.expense_type = editingAdditional.value.expense_type;
  form.zone = editingAdditional.value.zone;
  form.ruc = editingAdditional.value.ruc;
  form.amount = editingAdditional.value.amount;
  form.type_doc = editingAdditional.value.type_doc;
  form.doc_number = editingAdditional.value.doc_number;
  form.doc_date = editingAdditional.value.doc_date;
  form.amount = editingAdditional.value.amount;
  form.description = editingAdditional.value.description;

  editAdditionalModal.value = true;
};

const closeModals = () => {
  form.clearErrors();
  form.reset();
  editAdditionalModal.value = false;
  create_additional.value = false;
};

const submit = (update) => {
    if (!update){
        form.post(route('huawei.projects.staticcosts.store', { huawei_project: props.huawei_project.id }), {
            onSuccess: () => {
            closeCreateModal();
            form.reset();
            showModal.value = true;
            setTimeout(() => {
                showModal.value = false;
                router.visit(route('huawei.projects.staticcosts', {huawei_project: props.huawei_project.id}));
            }, 2000);
            }
        });
    }else{
        form.post(route('huawei.projects.staticcosts.update', { huawei_project: props.huawei_project.id, static_additional_cost: form.id }), {
            onSuccess: () => {
            closeEditModal();
            form.reset();
            showModalEdit.value = true;
            setTimeout(() => {
                showModalEdit.value = false;
                router.visit(route('huawei.projects.staticcosts', {huawei_project: props.huawei_project.id}));
            }, 2000);
            }
        });
    }
};

const confirmDeleteAdditional = (additionalId) => {
  docToDelete.value = additionalId;
  confirmingDocDeletion.value = true;
};

const closeModalDoc = () => {
  confirmingDocDeletion.value = false;
};

const openArchive = (id) => {
    const routeToShow = route('huawei.projects.staticcosts.preview', {static_additional_cost: id});
    window.open(routeToShow, '_blank');
}

const deleteAdditional = () => {
  const docId = docToDelete.value;
  if (docId) {
    router.delete(route('huawei.projects.staticcosts.delete', { huawei_project: props.huawei_project.id, static_additional_cost: docId }), {
      onSuccess: () => closeModalDoc()
    });
  }
};

const searchForm = useForm({
    searchTerm: props.search ? props.search : '',
});

const search = () => {
    if (searchForm.searchTerm == ''){
        router.visit(route('huawei.projects.staticcosts', {huawei_project: props.huawei_project.id}));
    }else{
        router.visit(route('huawei.projects.staticcosts.search', {huawei_project: props.huawei_project.id, request: searchForm.searchTerm}));
    }
}

const filterForm = ref({
    search: "",
    selectedZones: ["Arequipa", "Chala", "Moquegua", "Tacna", "MDD"],
    selectedExpenseTypes: [
        "Hospedaje",
        "Movilidad",
        "Peaje",
        "Seguros y Pólizas",
        "Herramientas",
        "Fletes",
        "EPPs",
        "Gastos de Representación",
        "Consumibles",
        "Equipos",
        "Otros",
    ],
    selectedDocTypes: [
        "Efectivo",
        "Deposito",
        "Factura",
        "Boleta",
        "Voucher de Pago",
    ],
});

const zones = ["Arequipa", "Chala", "Moquegua", "Tacna", "MDD"];
const expenseTypes = [
    "Hospedaje",
    "Movilidad",
    "Peaje",
    "Seguros y Pólizas",
    "Herramientas",
    "Fletes",
    "EPPs",
    "Gastos de Representación",
    "Consumibles",
    "Equipos",
    "Otros",
];
const docTypes = [
    "Efectivo",
    "Deposito",
    "Factura",
    "Boleta",
    "Voucher de Pago",
];

watch(
    () => [
        filterForm.value.selectedZones,
        filterForm.value.selectedExpenseTypes,
        filterForm.value.selectedDocTypes,
    ],
    () => {
        filterMode.value = true;
        search_advance(filterForm.value);
    },
    { deep: true }
);

async function search_advance($data) {
    let res = await axios.post(
        route("huawei.projects.staticcosts.advancedsearch", {
            huawei_project_id: props.huawei_project.id,
        }),
        $data
    );
    dataToRender.value = res.data;
}

async function handleSearch() {
    filterMode.value = true;
    search_advance(filterForm.value);
}

</script>
