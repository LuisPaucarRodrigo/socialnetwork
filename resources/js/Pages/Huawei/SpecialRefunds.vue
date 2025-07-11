<template>
    <div>
      <Head title="Devoluciones Especiales de Huawei" />
      <AuthenticatedLayout>
        <template #header>
          Devoluciones Especiales de Huawei
        </template>
        <div class="min-w-full rounded-lg shadow">
            <div class="flex gap-4 justify-between rounded-lg">
                    <div class="flex flex-col sm:flex-row gap-4 justify-between w-auto">
                    <button @click="openCreateModal" class="rounded-md bg-indigo-600 px-4 py-2 text-center text-sm text-white hover:bg-indigo-500 whitespace-nowrap">
                        + Agregar
                    </button>
                </div>

                <div class="flex items-center ml-auto sm:ml-0"> <!-- ml-auto para alinear a la derecha en pantallas grandes y sm:ml-0 para mantener en la izquierda en pantallas pequeñas -->
                    <form @submit.prevent="search" class="flex items-center w-full sm:w-auto">
                        <TextInput type="text" placeholder="Buscar..." v-model="searchForm.searchTerm" class="mr-2" />
                        <button type="submit" :class="{ 'opacity-25': searchForm.processing }"
                                class="ml-2 rounded-md bg-indigo-600 px-2 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                            <svg width="30px" height="21px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M15.7955 15.8111L21 21M18 10.5C18 14.6421 14.6421 18 10.5 18C6.35786 18 3 14.6421 3 10.5C3 6.35786 6.35786 3 10.5 3C14.6421 3 18 6.35786 18 10.5Z"
                                    stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>

            <div>
                <div class="overflow-x-auto mt-3">
                    <table class="w-full whitespace-no-wrap">
                    <thead>
                        <tr class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                        <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 text-center">
                            Descripción
                        </th>
                        <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 text-center">
                            DIU
                        </th>
                        <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 text-center">
                            Cantidad
                        </th>
                        <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 text-center">
                            Observaciones
                        </th>
                        <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 text-center">
                        </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in (props.search ? props.refunds : refunds.data)" :key="item.id" class="text-gray-700">
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center min-w-[250px]">{{ item.description }}</td>
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">{{ item.diu }}</td>
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">{{ item.quantity }}</td>
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">{{ item.observation }}</td>
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">
                            <div class="flex">
                                <button @click="openUpdateSite(item)" class="text-orange-400 hover:underline mr-2">
                                    <PencilSquareIcon class="h-5 w-5 ml-1" />
                                </button>
                                <button @click="confirmDeleteDocument(item.id)"
                                    class="flex items-center text-red-600 hover:underline">
                                    <TrashIcon class="h-5 w-5" />
                                </button>
                            </div>
                        </td>
                        </tr>
                    </tbody>
                    </table>
                </div>
            <div v-if="!props.search"
                class="flex flex-col items-center border-t bg-white px-5 py-5 xs:flex-row xs:justify-between">
                <pagination :links="props.refunds.links" />
            </div>
        </div>
        </div>
        <Modal :show="isCreateModalOpen || isUpdateModalOpen">
          <div class="p-6">
            <h2 class="text-base font-medium leading-7 text-gray-900">
              {{ isCreateModalOpen ? 'Agregar Devolución' : 'Actualizar Devolución'}}
            </h2>
            <form @submit.prevent="isCreateModalOpen ? submit(false) : submit(true)">
              <div class="space-y-12">
                <div class="border-b border-gray-900/10 pb-12">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <InputLabel for="description">Descripción</InputLabel>
                            <div class="mt-2">
                            <TextInput type="text" v-model="form.description" id="description" autocomplete="off" />
                            <InputError :message="form.errors.description" />
                            </div>
                        </div>
                        <div>
                            <InputLabel for="diu">DIU</InputLabel>
                            <div class="mt-2">
                            <TextInput type="text" v-model="form.diu" id="diu" autocomplete="off" />
                            <InputError :message="form.errors.diu" />
                            </div>
                        </div>
                        <div>
                            <InputLabel for="quantity">Cantidad</InputLabel>
                            <div class="mt-2">
                            <TextInput type="number" min="0" v-model="form.quantity" id="quantity" autocomplete="off" />
                            <InputError :message="form.errors.quantity" />
                            </div>
                        </div>
                        <div>
                            <InputLabel for="observation">Observaciones</InputLabel>
                            <div class="mt-2">
                            <textarea v-model="form.observation" id="observation" autocomplete="off" class="w-full p-2 border rounded border-gray-300"></textarea>
                            <InputError :message="form.errors.observation" />
                            </div>
                        </div>
                    </div>
                  <div class="mt-6 flex items-center justify-end gap-x-6">
                    <SecondaryButton @click="isCreateModalOpen ? closeCreateModal() : closeUpdateModal()"> Cancelar </SecondaryButton>
                    <PrimaryButton type="submit" :class="{ 'opacity-25': form.processing }">{{ isCreateModalOpen ? 'Guardar' : 'Actualizar' }}</PrimaryButton>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </Modal>

        <ConfirmCreateModal :confirmingcreation="showModal" itemType="Devolución" />
        <ConfirmUpdateModal :confirmingupdate="showModalEdit" itemType="Devolución" />
        <ConfirmDeleteModal :confirmingDeletion="confirmingDocDeletion" itemType="Devolución"
        :deleteFunction="deleteDocument" @closeModal="closeModalDoc" />
      </AuthenticatedLayout>
    </div>
  </template>

  <script setup>
  import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
  import ConfirmCreateModal from '@/Components/ConfirmCreateModal.vue';
  import ConfirmUpdateModal from '@/Components/ConfirmUpdateModal.vue';
  import SecondaryButton from '@/Components/SecondaryButton.vue';
  import InputLabel from '@/Components/InputLabel.vue';
  import ConfirmDeleteModal from '@/Components/ConfirmDeleteModal.vue';
  import TextInput from '@/Components/TextInput.vue';
  import InputError from '@/Components/InputError.vue';
  import { Head, useForm, router } from '@inertiajs/vue3';
  // import { TrashIcon, PencilSquareIcon } from '@heroicons/vue/24/outline';
  import { ref } from 'vue';
  import Modal from '@/Components/Modal.vue';
  import PrimaryButton from '@/Components/PrimaryButton.vue';
  import Pagination from '@/Components/Pagination.vue';

  const showModal = ref(false);
  const showModalEdit = ref(false);

  const props = defineProps({
    refunds: Object,
    search: String
  });

  const form = useForm({
    id: '',
    description: '',
    diu: '',
    quantity: '',
    observation: ''
  });

  const isCreateModalOpen = ref(false);
  const isUpdateModalOpen = ref(false);
  const editingSite = ref(null);
  const confirmingDocDeletion = ref(false);
  const docToDelete = ref(null);
  const showConfirmNameModal = ref(false);
  const foundName = ref(null);
  const openCreateModal = () => {
    isCreateModalOpen.value = true;
  };

  const openUpdateSite = (item) => {
      editingSite.value = JSON.parse(JSON.stringify(item));
      form.id = editingSite.value.id;
      form.description = editingSite.value.description;
      form.diu = editingSite.value.diu;
      form.quantity = editingSite.value.quantity;
      form.observation = editingSite.value.observation;
      isUpdateModalOpen.value = true;
  };


  const closeCreateModal = () => {
    form.reset();
    form.clearErrors();
    isCreateModalOpen.value = false;
  };

  const closeUpdateModal = () => {
    form.reset();
    form.clearErrors();
    isUpdateModalOpen.value = false;
  };

  const submit = (update) => {
    if (update){
        form.put(route('huawei.specialrefunds.update', {id: form.id}), {
            onSuccess: () =>{
                closeUpdateModal();
                showModalEdit.value = true;
                setTimeout(() => {
                    showModalEdit.value = false;
                }, 2000);
            }
        });
    }else{
        form.post(route('huawei.specialrefunds.store'), {
            onSuccess: () =>{
                closeCreateModal();
                showModal.value = true;
                setTimeout(() => {
                    showModal.value = false;
                }, 2000);
            }
        });
    }
  }

  const searchForm = useForm({
    searchTerm: props.search ? props.search : '',
  });

  const search = () => {
    if (searchForm.searchTerm == ''){
        router.visit(route('huawei.specialrefunds'));
    }else{
        router.visit(route('huawei.specialrefunds.search', {request: searchForm.searchTerm}));
    }
  }

  const confirmDeleteDocument = (documentId) => {
    docToDelete.value = documentId;
    confirmingDocDeletion.value = true;
  };

  const closeModalDoc = () => {
    confirmingDocDeletion.value = false;
  };

  const deleteDocument = () => {
    const docId = docToDelete.value;
    if (docId) {
        router.delete(route('huawei.specialrefunds.delete', { id: docId }), {
        onSuccess: () => closeModalDoc()
        });
    }
  };

  </script>
