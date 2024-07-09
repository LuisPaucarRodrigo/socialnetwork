<template>

    <Head title="Gestion de Costos Adicionales" />
    <AuthenticatedLayout
      :redirectRoute="{ route: 'huawei.projects'}">
      <template #header>
        Costos adicionales del Proyecto {{ props.huawei_project.name }}
      </template>
      <div class="inline-block min-w-full overflow-hidden rounded-lg">
        <div class="flex items-center justify-between gap-4">
    <PrimaryButton @click="openCreateAdditionalModal" type="button" class="whitespace-nowrap">
        + Agregar
    </PrimaryButton>
    <div class="flex items-center"> <!-- Alinear elementos horizontalmente -->
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
      <div class="overflow-x-auto">
        <table class="w-full whitespace-no-wrap">
          <thead>
            <tr class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
              <th
                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                Tipo de Gasto</th>
              <th
                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                RUC</th>
              <th
                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                Zona</th>
              <th
                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                Tipo de Documento</th>
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
                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                Descripción</th>
              <th
                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                Acciones</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="item in (props.search ? props.additional_costs : additional_costs.data)" :key="item.id" class="text-gray-700">
              <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">{{ item.expense_type }}</td>
              <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">{{ item.ruc }}</td>
              <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">{{ item.zone }}</td>
              <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">{{ item.type_doc }}</td>
              <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">{{ item.doc_number }}</td>
              <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">{{ formattedDate(item.doc_date) }}</td>
              <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm whitespace-nowrap">{{ item.amount ? 'S/. ' + item.amount.toFixed(2) : '-' }}</td>
              <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">{{ item.description }}</td>
              <td
                class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                <div class="flex items-center">
                  <button @click="openEditAdditionalModal(item)" class="text-orange-400 hover:underline mr-2">
                    <PencilSquareIcon class="h-4 w-4 ml-1" />
                  </button>
                  <button @click="confirmDeleteAdditional(item.id)" class="text-red-600 hover:underline">
                    <TrashIcon class="h-4 w-4" />
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
                <div class="grid grid-cols-2 gap-6">
                    <div>
                    <InputLabel for="expense_type" class="font-medium leading-6 text-gray-900">Tipo de Gasto</InputLabel>
                    <div class="mt-2">
                        <select v-model="form.expense_type" id="expense_type"
                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        <option disabled value="">Seleccionar Gasto</option>
                        <option>Combustible GEP</option>
                        <option>Combustible</option>
                        <option>Peaje</option>
                        <option>Otros</option>
                        </select>
                        <InputError :message="form.errors.expense_type" />
                    </div>
                    </div>
                    <div>
                    <InputLabel for="ruc" class="font-medium leading-6 text-gray-900">Ruc</InputLabel>
                    <div class="mt-2">
                        <input type="text" v-model="form.ruc" id="ruc" minlength="11" maxlength="11"
                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                        <InputError :message="form.errors.ruc" />
                    </div>
                    </div>
                    <div>
                    <InputLabel for="zone" class="font-medium leading-6 text-gray-900">Tipo de Gasto</InputLabel>
                    <div class="mt-2">
                        <select v-model="form.zone" id="zone"
                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        <option disabled value="">Seleccionar Zona</option>
                        <option>Arequipa</option>
                        <option>Chala</option>
                        <option>Juliaca</option>
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
                    <div class="col-span-1">
                    <InputLabel for="description" class="font-medium leading-6 text-gray-900">Descripción</InputLabel>
                    <div class="mt-2">
                        <textarea v-model="form.description" id="description"
                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                        <InputError :message="form.errors.description" />
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
import { ref } from 'vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import { TrashIcon, PencilSquareIcon } from '@heroicons/vue/24/outline';
import { formattedDate } from '@/utils/utils';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import Pagination from '@/Components/Pagination.vue';

const props = defineProps({
  additional_costs: Object,
  huawei_project: Object,
  auth: Object,
  userPermissions: Array,
  search: String
});

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
  amount: null
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
    create_additional.value = false;
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
        form.post(route('huawei.projects.additionalcosts.store', { huawei_project: props.huawei_project.id }), {
            onSuccess: () => {
            closeCreateModal();
            form.reset();
            showModal.value = true;
            setTimeout(() => {
                showModal.value = false;
            }, 2000);
            }
        });
    }else{
        form.put(route('huawei.projects.additionalcosts.update', { huawei_project: props.huawei_project.id, huawei_additional_cost: form.id }), {
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
    router.delete(route('huawei.projects.additionalcosts.delete', { huawei_project: props.huawei_project.id, huawei_additional_cost: docId }), {
      onSuccess: () => closeModalDoc()
    });
  }
};

const searchForm = useForm({
    searchTerm: props.search ? props.search : '',
});

const search = () => {
    if (searchForm.searchTerm == ''){
        router.visit(route('huawei.projects.additionalcosts', {huawei_project: props.huawei_project.id}));
    }else{
        router.visit(route('huawei.projects.additionalcosts.search', {huawei_project: props.huawei_project.id, request: searchForm.searchTerm}));
    }
}

</script>
