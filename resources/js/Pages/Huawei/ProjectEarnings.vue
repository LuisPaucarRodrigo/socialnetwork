<template>

    <Head title="Gestion de Ingresos" />
    <AuthenticatedLayout
      :redirectRoute="{ route: 'huawei.projects'}">
      <template #header>
        Ingresos del Proyecto {{ props.huawei_project.name }}
      </template>
      <div class="flex flex-col sm:flex-row gap-4 justify-between rounded-lg p-4">
    <!-- Botones principales visibles en pantallas grandes -->
    <div class="flex flex-col sm:flex-row gap-4 items-center">
        <button @click.prevent="openCreateAdditionalModal" type="button"
            class="hidden sm:block rounded-md bg-indigo-600 px-4 py-2 text-center text-sm text-white hover:bg-indigo-500 whitespace-nowrap">
            + Agregar
        </button>
        <button @click.prevent="openImportModal" type="button"
            class="hidden sm:block rounded-md bg-green-600 px-4 py-2 text-center text-sm text-white hover:bg-green-500 whitespace-nowrap">
            Importar Datos
        </button>
        <a :href="route('huawei.projects.earnings.export', {huawei_project: props.huawei_project})" type="button"
            class="hidden sm:block rounded-md bg-green-600 px-4 py-2 text-center text-sm text-white hover:bg-green-500 whitespace-nowrap">
            Exportar Datos
        </a>
        <!-- Menú desplegable visible en pantallas pequeñas -->
        <div class="sm:hidden flex items-center">
            <dropdown align="left">
                <template #trigger>
                    <button @click="dropdownOpen = !dropdownOpen"
                        class="relative block overflow-hidden rounded-md bg-gray-200 px-2 py-2 text-center text-sm text-white hover:bg-gray-100">
                        <svg width="25px" height="25px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M4 6H20M4 12H20M4 18H20" stroke="#000000" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                    </button>
                </template>

                <template #content class="origin-left bg-white border rounded-lg shadow-lg mt-2">
                    <div>
                        <button @click.prevent="openCreateAdditionalModal" type="button" class="dropdown-item block w-full text-left px-4 py-2 text-sm text-black-700 hover:bg-indigo-600 hover:text-white focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                            + Agregar
                        </button>
                        <button @click.prevent="openImportModal" type="button" class="dropdown-item block w-full text-left px-4 py-2 text-sm text-black-700 hover:bg-indigo-600 hover:text-white focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                            Importar Datos
                        </button>
                        <a :href="route('huawei.projects.earnings.export', {huawei_project: props.huawei_project})" type="button" class="dropdown-item block w-full text-left px-4 py-2 text-sm text-black-700 hover:bg-indigo-600 hover:text-white focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                            Exportar Datos
                        </a>
                    </div>
                </template>
            </dropdown>
        </div>
    </div>

    <!-- Total y formulario de búsqueda -->
    <div class="flex flex-col sm:flex-row items-center gap-4 sm:gap-6">
        <p class="whitespace-nowrap text-sm font-semibold">Total: {{ props.total ? props.total.toFixed(2) : '' }}</p>
        <form @submit.prevent="search" class="flex items-center gap-2">
            <TextInput type="text" placeholder="Buscar..." v-model="searchForm.searchTerm" class="mr-2 min-w-[150px] sm:min-w-[200px]" />
            <button type="submit" :class="{ 'opacity-25': searchForm.processing }"
                    class="flex items-center rounded-md bg-indigo-600 px-2 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                <svg width="24px" height="24px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M15.7955 15.8111L21 21M18 10.5C18 14.6421 14.6421 18 10.5 18C6.35786 18 3 14.6421 3 10.5C3 6.35786 6.35786 3 10.5 3C14.6421 3 18 6.35786 18 10.5Z"
                        stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </button>
        </form>
    </div>
</div>

      <div class="overflow-x-auto">
        <table class="w-full whitespace-no-wrap">
          <thead>
            <tr class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                <th
                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-center text-gray-600">
                Código</th>
                <th
                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-center text-gray-600 min-w-[300px]">
                Descripción</th>
              <th
                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-center text-gray-600">
                Cantidad</th>
                <th
                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-center text-gray-600">
                Precio Unitario</th>
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
              <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center whitespace-nowrap">{{ item.code }}</td>
              <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">{{ item.description }}</td>
              <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">{{ item.quantity }}</td>
              <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center whitespace-nowrap">{{ item.unit_price ? "S/. " + item.unit_price.toFixed(2) : '-' }}</td>
              <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center whitespace-nowrap">{{ item.unit_price ? "S/. " + (item.unit_price * item.quantity).toFixed(2) : '-' }}</td>
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
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">

                        <div class="col-span-1">
                            <InputLabel for="quantity" class="font-medium leading-6 text-gray-900">Cantidad</InputLabel>
                            <div class="mt-2">
                                <input type="number" v-model="form.quantity" id="quantity"
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.quantity" />
                            </div>
                        </div>

                        <div class="col-span-1">
                            <InputLabel for="unit_price" class="font-medium leading-6 text-gray-900">Precio Unitario</InputLabel>
                            <div class="mt-2">
                                <input type="number" step="0.01" v-model="form.unit_price" id="unit_price"
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.unit_price" />
                            </div>
                        </div>

                        <div class="col-span-1 sm:col-span-2">
                            <InputLabel for="description" class="font-medium leading-6 text-gray-900">Descripción del Ingreso</InputLabel>
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

        <Modal :show="importModal">
            <div class="p-6">
                <h2 class="text-base font-medium leading-7 text-gray-900">
                    Importar Excel
                </h2>
                <form @submit.prevent="importExcel">
                <div class="space-y-12">
                    <div class="border-b border-gray-900/10 pb-12">
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">

                            <div class="col-span-2">
                                <InputLabel for="file" class="font-medium leading-6 text-gray-900">Archivo</InputLabel>
                                <div class="mt-2">
                                    <InputFile type="file" accept="xls,xlsx" v-model="importForm.file" id="file"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                    <InputError :message="importForm.errors.file" />
                                </div>
                            </div>
                            <div class="col-span-2">
                                <InputLabel for="zone" class="font-medium leading-6 text-gray-900">Zona</InputLabel>
                                <div class="mt-2">
                                    <select v-model="importForm.zone" id="zone"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                        <option value="" disabled>Seleccione una opción</option>
                                        <option value="1">B1</option>
                                        <option value="2">B2</option>
                                        <option value="3">B3</option>
                                        <option value="4">B4</option>
                                    </select>
                                    <InputError :message="importForm.errors.zone" />
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
import { ref } from 'vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import { TrashIcon, PencilSquareIcon } from '@heroicons/vue/24/outline';
import { formattedDate } from '@/utils/utils';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import Pagination from '@/Components/Pagination.vue';
import Dropdown from '@/Components/Dropdown.vue';

const props = defineProps({
  earnings: Object,
  huawei_project: Object,
  auth: Object,
  userPermissions: Array,
  search: String,
  total: Number
});

const hasPermission = (permission) => {
  return props.userPermissions.includes(permission);
};

const form = useForm({
  id: '',
  description: '',
  quantity: '',
  unit_price: '',
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
  form.quantity = editingAdditional.value.quantity;
  form.unit_price = editingAdditional.value.unit_price;
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

const importExcel = () => {
    importForm.post(route('huawei.projects.earnings.import', {huawei_project: props.huawei_project}), {
        onSuccess: () => {
            closeImportModal();
            confirmImport.value = true;
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
