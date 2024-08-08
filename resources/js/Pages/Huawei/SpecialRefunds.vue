<template>
    <div>
      <Head title="Gestion de Sites" />
      <AuthenticatedLayout>
        <template #header>
          Devoluciones Especiales de Huawei
        </template>
        <div class="inline-block min-w-full overflow-hidden rounded-lg shadow">
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

          <div class="overflow-x-auto mt-2">
            <table class="min-w-full divide-y divide-gray-200">
              <thead>
                <tr>
                  <th scope="col"
                    class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider text-center">
                    Descripción
                  </th>
                  <th scope="col"
                    class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider text-center">
                    DIU
                  </th>
                  <th scope="col"
                    class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider text-center">
                    Cantidad
                  </th>
                  <th scope="col"
                    class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider text-center">
                    Observación
                  </th>
                  <th scope="col"
                    class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider text-center">

                  </th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr v-for="item in (props.search ? props.refunds : refunds.data)" :key="site.id">
                  <td class="px-6 py-4 whitespace-nowrap text-center">
                    <div class="text-sm font-medium text-gray-900">{{ item.description }}</div>
                    <div class="text-sm font-medium text-gray-900">{{ item.diu }}</div>
                    <div class="text-sm font-medium text-gray-900">{{ item.quantity }}</div>
                    <div class="text-sm font-medium text-gray-900">{{ item.observation }}</div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-center">
                    <div class="flex justify-center items-center">
                        <button @click="openUpdateSite(item)" class="text-orange-400 hover:underline">
                            <PencilSquareIcon class="h-5 w-5" />
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

        <Modal :show="isCreateModalOpen || isUpdateModalOpen">
          <div class="p-6">
            <h2 class="text-base font-medium leading-7 text-gray-900">
              {{ isCreateModalOpen ? 'Agregar Devolución' : 'Actualizar Devolución'}}
            </h2>
            <form @submit.prevent="isCreateModalOpen ? submit(false) : submit(true)">
              <div class="space-y-12">
                <div class="border-b border-gray-900/10 pb-12">
                  <div>
                    <InputLabel for="name">Descripción</InputLabel>
                    <div class="mt-2">
                      <TextInput type="text" v-model="form.name" id="name" autocomplete="address-level1" />
                      <InputError :message="form.errors.name" />
                    </div>
                  </div>
                  <div>
                    <InputLabel for="name">Descripción</InputLabel>
                    <div class="mt-2">
                      <TextInput type="text" v-model="form.name" id="name" autocomplete="address-level1" />
                      <InputError :message="form.errors.name" />
                    </div>
                  </div>
                  <div>
                    <InputLabel for="name">Descripción</InputLabel>
                    <div class="mt-2">
                      <TextInput type="text" v-model="form.name" id="name" autocomplete="address-level1" />
                      <InputError :message="form.errors.name" />
                    </div>
                  </div>
                  <div>
                    <InputLabel for="name">Descripción</InputLabel>
                    <div class="mt-2">
                      <TextInput type="text" v-model="form.name" id="name" autocomplete="address-level1" />
                      <InputError :message="form.errors.name" />
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

        <ConfirmCreateModal :confirmingcreation="showModal" itemType="Site" />
        <ConfirmUpdateModal :confirmingupdate="showModalEdit" itemType="Site" />

      </AuthenticatedLayout>
    </div>
  </template>

  <script setup>
  import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
  import ConfirmCreateModal from '@/Components/ConfirmCreateModal.vue';
  import ConfirmDeleteModal from '@/Components/ConfirmDeleteModal.vue';
  import ConfirmUpdateModal from '@/Components/ConfirmUpdateModal.vue';
  import SecondaryButton from '@/Components/SecondaryButton.vue';
  import InputLabel from '@/Components/InputLabel.vue';
  import TextInput from '@/Components/TextInput.vue';
  import InputError from '@/Components/InputError.vue';
  import { Head, useForm, router } from '@inertiajs/vue3';
  import { TrashIcon, PencilSquareIcon, ArrowDownIcon } from '@heroicons/vue/24/outline';
  import { ref } from 'vue';
  import Modal from '@/Components/Modal.vue';
  import PrimaryButton from '@/Components/PrimaryButton.vue';
  import Pagination from '@/Components/Pagination.vue';
  import axios from 'axios';

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
    console.log(form);
  }

  const searchForm = useForm({
    searchTerm: props.search ? props.search : '',
  });

  const search = () => {
    if (searchForm.searchTerm == ''){
        router.visit(route('huawei.sites'));
    }else{
        router.visit(route('huawei.sites.search', {request: searchForm.searchTerm}));
    }
  }

  </script>
