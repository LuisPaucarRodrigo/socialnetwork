<template>
    <div>
      <Head title="Materiales Internos" />
      <AuthenticatedLayout>
        <template #header>
          Materiales Internos
        </template>
        <div class="inline-block min-w-full overflow-hidden rounded-lg shadow">
            <div class="flex gap-4 justify-between rounded-lg">
                    <div class="flex flex-col sm:flex-row gap-4 justify-between w-auto">
                    <button @click="openCreateModal" class="rounded-md bg-indigo-600 px-4 py-2 text-center text-sm text-white hover:bg-indigo-500 whitespace-nowrap">
                        Agregar Material
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
                    Unidad
                  </th>
                  <th scope="col"
                    class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider text-center">
                    Acciones
                  </th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr v-for="item in (props.search ? props.quick_materials : quick_materials.data)" :key="item.id">
                  <td class="px-6 py-4 whitespace-nowrap text-center">
                    <div class="text-sm font-medium text-gray-900">{{ item.description }}</div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-center">
                    <div class="text-sm font-medium text-gray-900">{{ item.unit }}</div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-center">
                    <div class="flex gap-2 justify-center items-center">
                        <Link class="text-green-600 hover:underline" :href="route('huawei.quickmaterials.details', {material_id: item.id})">
                            <EyeIcon class="h-5 w-5"/>
                        </Link>
                        <button @click="openUpdateSite(item)" class="text-orange-400 hover:underline">
                            <PencilSquareIcon class="h-5 w-5" />
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
          <div v-if="!props.search"
            class="flex flex-col items-center border-t bg-white px-5 py-5 xs:flex-row xs:justify-between">
            <pagination :links="props.quick_materials.links" />
        </div>
        </div>

        <Modal :show="isCreateModalOpen || isUpdateModalOpen">
          <div class="p-6">
            <h2 class="text-base font-medium leading-7 text-gray-900">
              {{ isCreateModalOpen ? 'Agregar Material' : 'Actualizar Material'}}
            </h2>
            <form @submit.prevent="isCreateModalOpen ? submit(false) : submit(true)">
              <div class="space-y-12">
                <div class="border-b border-gray-900/10 pb-12">
                    <div>
                    <InputLabel for="description">{{ isCreateModalOpen ? 'Agregar Material:' : 'Actualizar Material' }}</InputLabel>
                    <div class="mt-2">
                      <TextInput type="text" v-model="form.description" id="description" autocomplete="off" />
                      <InputError :message="form.errors.description" />
                    </div>
                  </div>
                    <div class="mt-2">
                        <InputLabel for="unit">Unidad</InputLabel>
                        <div class="mt-2">
                            <select v-model="form.unit" id="unit" class="block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                            <option value="" disabled>Selecciona una opción</option>
                            <option>Unidad</option>
                            <option>Metros</option>
                            <option>Kilogramos</option>
                            <option>Paquete</option>
                            </select>
                            <InputError :message="form.errors.unit" />
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


        <Modal :show="showConfirmNameModal" :maxWidth="'sm'">
            <div class="p-6">
                <h2 class="text-base font-medium leading-7 text-gray-900 text-center">
                    ¿Está seguro de crear o actualizar el material?
                </h2>
                <p class="mt-1 text-sm text-gray-600 text-wrap">
                    Actualmente, hay un material que tiene un nombre similar al que esta intentando registrar: <span class="font-black">{{ foundName }}</span>.               </p>
                <div class="space-y-12">
                <div class="border-gray-900/10">
                    <div class="mt-6 flex items-center justify-end gap-x-3">
                    <SecondaryButton @click="noAccept"> No </SecondaryButton>
                    <PrimaryButton @click="accept"
                        class="rounded-md bg-indigo-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Si</PrimaryButton>
                    </div>
                </div>
                </div>
            </div>
        </Modal>


        <ConfirmCreateModal :confirmingcreation="showModal" itemType="Material" />
        <ConfirmUpdateModal :confirmingupdate="showModalEdit" itemType="Material" />
        <ConfirmDeleteModal :confirmingDeletion="confirmingDocDeletion" itemType="Material"
        :deleteFunction="deleteAdditional" @closeModal="closeModalDoc" />

      </AuthenticatedLayout>
    </div>
  </template>

  <script setup>
  import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
  import ConfirmCreateModal from '@/Components/ConfirmCreateModal.vue';
  import ConfirmUpdateModal from '@/Components/ConfirmUpdateModal.vue';
  import SecondaryButton from '@/Components/SecondaryButton.vue';
  import InputLabel from '@/Components/InputLabel.vue';
  import TextInput from '@/Components/TextInput.vue';
  import InputError from '@/Components/InputError.vue';
  import { Head, useForm, router, Link } from '@inertiajs/vue3';
  // import { PencilSquareIcon, TrashIcon, EyeIcon } from '@heroicons/vue/24/outline';
  import { ref } from 'vue';
  import Modal from '@/Components/Modal.vue';
  import PrimaryButton from '@/Components/PrimaryButton.vue';
  import Pagination from '@/Components/Pagination.vue';
  import axios from 'axios';
  import ConfirmDeleteModal from '@/Components/ConfirmDeleteModal.vue';

  const showModal = ref(false);
  const showModalEdit = ref(false);

  const props = defineProps({
    quick_materials: Object,
    search: String
  });

  const form = useForm({
    id: '',
    description: '',
    unit: ''
  });

  const isCreateModalOpen = ref(false);
  const isUpdateModalOpen = ref(false);
  const editingSite = ref(null);
  const showConfirmNameModal = ref(false);
  const confirmingDocDeletion = ref(false);
const docToDelete = ref(null);
  const foundName = ref(null);
  const openCreateModal = () => {
    isCreateModalOpen.value = true;
  };

  const openUpdateSite = (item) => {
      editingSite.value = JSON.parse(JSON.stringify(item));
      form.id = editingSite.value.id;
      form.description = editingSite.value.description;
      form.unit = editingSite.value.unit;
      isUpdateModalOpen.value = true;
  };


  const closeCreateModal = () => {
    form.reset();
    isCreateModalOpen.value = false;
  };

  const closeUpdateModal = () => {
    form.reset();
    isUpdateModalOpen.value = false;
  };

  const noAccept = () => {
    foundName.value = null;
    form.reset();
    showConfirmNameModal.value = false;
    isCreateModalOpen.value = false;
    isUpdateModalOpen.value = false;
  }

  const accept = () => {
    if (isCreateModalOpen.value){
        form.post(route('huawei.quickmaterials.store'), {
            onSuccess: () => {
                closeCreateModal();
                form.reset();
                showConfirmNameModal.value = false;
                closeCreateModal();
                showModal.value = true
                setTimeout(() => {
                    showModal.value = false;
                }, 2000);
            }
        });
    }else{
        form.put(route('huawei.quickmaterials.update', { material: form.id}), {
            onSuccess: () => {
                closeUpdateModal();
                form.reset();
                showConfirmNameModal.value = false;
                closeUpdateModal();
                showModalEdit.value = true
                setTimeout(() => {
                    showModalEdit.value = false;
                }, 2000);
            }
        });
    }
  }

  const submit = (update) => {
      if (update) {
        axios.post(route('huawei.quickmaterials.verifyname', {update: form.id}), form)
        .then(res => {
            if (res.data.message == 'found'){
                foundName.value = res.data.name;
                showConfirmNameModal.value = true;
            }else{
                form.put(route('huawei.quickmaterials.update', { material: form.id}), {
                    onSuccess: () => {
                        closeUpdateModal();
                        form.reset();
                        showModalEdit.value = true
                        setTimeout(() => {
                            showModalEdit.value = false;
                        }, 2000);
                        }
                    });
            }
        })

      } else {
        axios.post(route('huawei.quickmaterials.verifyname'), form)
        .then(res => {
            if (res.data.message == 'found'){
                foundName.value = res.data.name;
                showConfirmNameModal.value = true;
            }else{
                form.post(route('huawei.quickmaterials.store'), {
                    onSuccess: () => {
                    closeCreateModal();
                    form.reset();
                    showModal.value = true
                    setTimeout(() => {
                        showModal.value = false;
                    }, 2000);
                    }
                });
            }
        })

      }
  };

  const searchForm = useForm({
    searchTerm: props.search ? props.search : '',
  });

  const search = () => {
    if (searchForm.searchTerm == ''){
        router.visit(route('huawei.quickmaterials'));
    }else{
        router.visit(route('huawei.quickmaterials.search', {request: searchForm.searchTerm}));
    }
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
    router.delete(route('huawei.quickmaterials.delete', { material: docId }), {
      onSuccess: () => {
        closeModalDoc();
        router.visit(route('huawei.quickmaterials'));
      }
    });
  }
};

  </script>
