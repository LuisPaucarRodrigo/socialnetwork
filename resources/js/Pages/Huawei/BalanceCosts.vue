<template>

    <Head title="Gestion de Costos Adicionales" />
    <AuthenticatedLayout :redirectRoute="{route: 'huawei.generalbalance.costs.summary'}">
      <template #header>
        Costos {{ props.type ? 'Variables' : 'Fijos' }} Generales
      </template>
      <div class="min-w-full rounded-lg">
        <div class="flex items-center justify-end gap-4">
        <PrimaryButton @click="openCreateAdditionalModal" type="button" class="whitespace-nowrap">
            + Agregar
        </PrimaryButton>

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

    <div class="overflow-x-auto h-[85vh]">
        <table class="w-full whitespace-no-wrap">
          <thead>
            <tr class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
              <th
                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                <TableHeaderFilter
                                labelClass="text-[11px]"
                                label="Tipo de Gasto"
                                :options="expenseTypesArray"
                                v-model="filterForm.selectedExpenseTypes"
                                width="w-48"
                            />
              </th>
              <th
                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">

                Cuadrilla
              </th>
              <th
                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                Fecha del Gasto</th>
              <th
                class="border-b-2 border-gray-200 bg-gray-100 px-8 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                Monto</th>
              <th
                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                </th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="item in (props.search ? props.costs : dataToRender)" :key="item.id" class="text-gray-700">
              <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm min-w-[150px]">{{ item.expense_type }}</td>
              <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">{{ item.zone }}</td>
              <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">{{ formattedDate(item.cost_date) }}</td>
              <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm whitespace-nowrap">{{ item.amount ? 'S/. ' + item.amount.toFixed(2) : '-' }}</td>
              <td
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
        <pagination :links="props.costs.links" />
      </div>
      </div>

      <Modal :show="create_additional || editAdditionalModal">
    <div class="p-6">
        <h2 class="text-base font-medium leading-7 text-gray-900">
            {{ create_additional ? 'Agregar Costo' : 'Actualizar Costo' }}
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
                                    <option v-for="(item, index) in expenseTypesArray" :key="index">{{ item }}</option>
                                </select>
                                <InputError :message="form.errors.expense_type" />
                            </div>
                        </div>
                        <div>
                            <InputLabel for="zone" class="font-medium leading-6 text-gray-900">Cuadrilla</InputLabel>
                            <div class="mt-2">
                                <select v-model="form.zone" id="zone"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option disabled value="">Seleccionar Cuadrilla</option>
                                    <option>HUAWEI</option>
                                        <option>HUAWEI-PUNO</option>
                                        <option>HUAWEI-AQP</option>
                                        <option>HUAWEI-CHALA</option>
                                        <option>HUAWEI-ILO</option>
                                        <option>HUAWEI-JULIACA</option>
                                        <option>HUAWEI-CUSCO</option>
                                        <option>HUAWEI-TACNA</option>
                                        <option>HUAWEI-LAPUNTA</option>
                                        <option>HUAWEI-ORCOPAMPA</option>
                                        <option>HUAWEI-ABANCAY</option>
                                        <option>HUAWEI-BANOSPAMPA</option>
                                        <option>HUAWEI-ELPALOMAR</option>
                                        <option>PDIAQP</option>
                                        <option>PERAL</option>
                                        <option>PUNO</option>
                                        <option>TACNA</option>
                                        <option>GUSTAVOHUAWEI</option>
                                        <option>DESAGUADERO</option>
                                </select>
                                <InputError :message="form.errors.zone" />
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
                            <InputLabel for="cost_date" class="font-medium leading-6 text-gray-900">Fecha del Gasto</InputLabel>
                            <div class="mt-2">
                                <input type="date" v-model="form.cost_date" id="cost_date"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"/>
                                <InputError :message="form.errors.cost_date" />
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
import Modal from '@/Components/Modal.vue';
import { ref, watch } from 'vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import { TrashIcon, PencilSquareIcon } from '@heroicons/vue/24/outline';
import { formattedDate } from '@/utils/utils';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import Pagination from '@/Components/Pagination.vue';
import TableHeaderFilter from "@/Components/TableHeaderFilter.vue";
import SuccessOperationModal from '@/Components/SuccessOperationModal.vue';
import InputFile from '@/Components/InputFile.vue';

const props = defineProps({
  costs: Object,
  auth: Object,
  userPermissions: Array,
  search: String,
  type: String
});

const dataToRender = ref(props.costs.data);
const filterMode = ref(false);
const expenseTypesArray = props.type ? [
    'Cochera', 'Combustible', 'Epps', 'Herramientas', 'Materiales'
] : [
    'Planilla', 'Transporte', 'Fletes', 'Alimentación', 'Consumibles', 'Hospedaje', 'Movilidad'
];

const hasPermission = (permission) => {
  return props.userPermissions.includes(permission);
};

const form = useForm({
  id: '',
  expense_type: '',
  zone: '',
  cost_date: '',
  amount: null,
});

const create_additional = ref(false);
const showModal = ref(false);
const showModalEdit = ref(false);
const confirmingDocDeletion = ref(false);
const docToDelete = ref(null);
const editAdditionalModal = ref(false);
const editingAdditional = ref(null);
const backRoute = props.type ? route('huawei.generalbalance.costs', {type: 1}) : route('huawei.generalbalance.costs');

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
  form.cost_date = editingAdditional.value.cost_date;
  form.amount = editingAdditional.value.amount;

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
        const url = props.type ? route('huawei.generalbalance.costs.store', {type: 1}) : route('huawei.generalbalance.costs.store');
        form.post(url, {
            onSuccess: () => {
            closeCreateModal();
            form.reset();
            showModal.value = true;
            setTimeout(() => {
                showModal.value = false;
                router.visit(backRoute);
            }, 2000);
            },
            onError: (e) => {
                console.error(e);
            }
        });
    }else{
        form.put(route('huawei.generalbalance.costs.update', { huawei_balance_cost: form.id }), {
            onSuccess: () => {
            closeEditModal();
            form.reset();
            showModalEdit.value = true;
            setTimeout(() => {
                showModalEdit.value = false;
                router.visit(backRoute);
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

const deleteAdditional = () => {
  const docId = docToDelete.value;
  if (docId) {
    router.delete(route('huawei.generalbalance.costs.delete', { huawei_balance_cost: docId }), {
      onSuccess: () => {
        closeModalDoc();
        router.visit(backRoute);
      }
    });
  }
};

const searchForm = useForm({
    searchTerm: props.search ? props.search : '',
});

const search = () => {
    if (searchForm.searchTerm == ''){
        router.visit(backRoute);
    }else{
        const url = props.type ? route('huawei.generalbalance.costs.search', {request: searchForm.searchTerm, type: 1}) : route('huawei.generalbalance.costs.search', {request: searchForm.searchTerm});
        router.visit(url);
    }
}

const filterForm = ref({
    search: "",
    selectedExpenseTypes: props.type ? [
        "Cochera",
        "Combustible",
        "Epps",
        "Herramientas",
        "Materiales",
    ] : [
        'Planilla', 'Transporte', 'Fletes', 'Alimentación', 'Consumibles', 'Hospedaje', 'Movilidad'
    ],
});

watch(
    () => [
        filterForm.value.selectedExpenseTypes,
        //filterForm.value.selectedZones,
    ],
    () => {
        filterMode.value = true;
        search_advance(filterForm.value);
    },
    { deep: true }
);

async function search_advance($data) {
    const url = props.type ? route('huawei.generalbalance.costs.advancedsearch', {type: 1}) : route('huawei.generalbalance.costs.advancedsearch');
    let res = await axios.post(
        url,
        $data
    );
    dataToRender.value = res.data;
}

async function handleSearch() {
    filterMode.value = true;
    search_advance(filterForm.value);
}

</script>
