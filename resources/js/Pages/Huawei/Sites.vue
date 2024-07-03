<template>
    <div>
      <Head title="Gestion de Secciones" />
      <AuthenticatedLayout>
        <template #header>
          Gestión SItes de huawei
        </template>
        <div class="inline-block min-w-full overflow-hidden rounded-lg shadow">
          <PrimaryButton @click="openCreateModal">
            Crear Nuevo Site
          </PrimaryButton>
          <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
              <thead>
                <tr>
                  <th scope="col"
                    class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider text-center">
                    Nombre
                  </th>
                  <th scope="col"
                    class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider text-center">
                    Acciones
                  </th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr v-for="site in sites.data" :key="site.id">
                  <td class="px-6 py-4 whitespace-nowrap text-center">
                    <div class="text-sm font-medium text-gray-900">{{ site.name }}</div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-center">
                    <div class="flex justify-center items-center">
                        <button @click="openUpdateSite(site)" class="text-orange-400 hover:underline">
                            <PencilSquareIcon class="h-5 w-5" />
                        </button>
                    </div>
                </td>
                </tr>
              </tbody>
            </table>
          </div>
          <div
            class="flex flex-col items-center border-t bg-white px-5 py-5 xs:flex-row xs:justify-between">
            <pagination :links="props.sites.links" />
        </div>
        </div>

        <Modal :show="isCreateModalOpen || isUpdateModalOpen">
          <div class="p-6">
            <h2 class="text-base font-medium leading-7 text-gray-900">
              {{ isCreateModalOpen ? 'Agregar Site' : 'Actualizar Site'}}
            </h2>
            <form @submit.prevent="isCreateModalOpen ? submit(false) : submit(true)">
              <div class="space-y-12">
                <div class="border-b border-gray-900/10 pb-12">
                  <div>
                    <InputLabel for="name">{{ isCreateModalOpen ? 'Agregar nuevo Site:' : 'Actualizar Site' }}</InputLabel>
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

  const showModal = ref(false);
  const showModalEdit = ref(false);

  const props = defineProps({
    sites: Object,
  });

  const form = useForm({
    id: '',
    name: '',
  });

  const isCreateModalOpen = ref(false);
  const isUpdateModalOpen = ref(false);
  const editingSite = ref(null);

  const openCreateModal = () => {
    isCreateModalOpen.value = true;
  };

  const openUpdateSite = (item) => {
      editingSite.value = JSON.parse(JSON.stringify(item));
      form.id = editingSite.value.id;
      form.name = editingSite.value.name;
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


  const submit = (update) => {
      if (update) {
          form.put(route('huawei.sites.put', { site: form.id}), {
          onSuccess: () => {
          closeUpdateModal();
          form.reset();
          showModalEdit.value = true
          setTimeout(() => {
              showModalEdit.value = false;
          }, 2000);
          }
      });
      } else {
          form.post(route('huawei.sites.post'), {
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
  };

  </script>
