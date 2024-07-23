<template>

    <Head title="Gestion de Costos Adicionales" />
    <AuthenticatedLayout
      :redirectRoute="{ route: 'huawei.projects'}">
      <template #header>
        Costos adicionales del Proyecto {{ props.huawei_project.name }}
      </template>
      <div class="inline-block min-w-full overflow-hidden rounded-lg">
        <div class="flex items-center justify-end gap-4">
        <PrimaryButton v-if="props.huawei_project.status" @click="openCreateAdditionalModal" type="button" class="whitespace-nowrap">
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


      </div>
      <div class="overflow-x-auto">
        <table class="w-full whitespace-no-wrap">
          <thead>
            <tr class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
              <th
                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-center text-gray-600">
                Descripción</th>
              <th
                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-center text-gray-600">
                Monto</th>
              <th v-if="props.huawei_project.status"
                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-center text-gray-600">
                </th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="item in (props.search ? props.earnings : earnings.data)" :key="item.id" class="text-gray-700">
              <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">{{ item.description }}</td>
              <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">S/. {{ item.amount.toFixed(2) }}</td>
              <td v-if="props.huawei_project.status" class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">
                <div class="flex justify-center items-center">
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
        <pagination :links="props.earnings.links" />
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

                    <div class="col-span-2">
                        <InputLabel for="description" class="font-medium leading-6 text-gray-900">Descripción del Ingreso</InputLabel>
                        <div class="mt-2">
                            <textarea v-model="form.description" id="description"
                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                            <InputError :message="form.errors.description" />
                        </div>
                    </div>

                    <div class="col-span-2">
                        <InputLabel for="amount" class="font-medium leading-6 text-gray-900">Monto</InputLabel>
                        <div class="mt-2">
                            <input type="number" step="0.01" v-model="form.amount" id="amount"
                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                            <InputError :message="form.errors.amount" />
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


      <ConfirmDeleteModal :confirmingDeletion="confirmingDocDeletion" itemType="Ingreso"
        :deleteFunction="deleteAdditional" @closeModal="closeModalDoc" />
      <ConfirmCreateModal :confirmingcreation="showModal" itemType="Ingreso" />
      <ConfirmUpdateModal :confirmingupdate="showModalEdit" itemType="Ingreso" />
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
  earnings: Object,
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
  description: '',
  amount: '',
  huawei_project_id: props.huawei_project.id,
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
  form.description = editingAdditional.value.description;
  form.amount = editingAdditional.value.amount;
  form.huawei_project_id = editingAdditional.value.huawei_project_id;

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
        form.post(route('huawei.projects.earnings.store'), {
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
        form.put(route('huawei.projects.earnings.update', { huawei_project_earning: form.id }), {
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
    router.delete(route('huawei.projects.earnings.delete', { huawei_project_earning: docId }), {
      onSuccess: () => closeModalDoc()
    });
  }
};

const searchForm = useForm({
    searchTerm: props.search ? props.search : '',
});

const search = () => {
    if (searchForm.searchTerm == ''){
        router.visit(route('huawei.projects.earnings', {huawei_project: props.huawei_project.id}));
    }else{
        router.visit(route('huawei.projects.earnings.search', {huawei_project: props.huawei_project.id, request: searchForm.searchTerm}));
    }
}

</script>
