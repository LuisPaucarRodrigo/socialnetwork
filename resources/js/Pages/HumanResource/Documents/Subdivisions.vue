<template>
  <div>

    <Head title="Gestion de Secciones" />
    <AuthenticatedLayout :redirectRoute="'documents.sections'">
      <template #header>
        Gestión de Subdivisiones de la Sección: {{ section.name }}
      </template>
      <div class="inline-block min-w-full overflow-hidden rounded-lg shadow">
        <PrimaryButton @click="openCreateSubdivisionModal">
          Crear Nueva Subdivisión
        </PrimaryButton>
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead>
              <tr>
                <th scope="col"
                  class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Nombre
                </th>
                <th scope="col"
                  class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Acciones
                </th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="subdivision in subdivisions" :key="subdivision.id">
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm font-medium text-gray-900">{{ subdivision.name }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-left">
                  <button @click="confirmDeleteSubdivision(subdivision.id)" class="text-red-600 hover:underline">
                    <TrashIcon class="h-5 w-5" />
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

      </div>
      <Modal :show="isCreateSubdivisionModalOpen">
        <div class="p-6">
          <h2 class="text-base font-medium leading-7 text-gray-900">
            Agregar Subdivisión
          </h2>
          <form @submit.prevent="submit">
            <div class="space-y-12">
              <div class="border-b border-gray-900/10 pb-12">
                <div>
                  <InputLabel for="name">Agregar nueva subdivisión:</InputLabel>
                  <div class="mt-2">
                    <TextInput type="text" v-model="form.name" id="name" autocomplete="address-level1" />
                    <InputError :message="form.errors.name" />
                  </div>
                </div>
                <div class="mt-6 flex items-center justify-end gap-x-6">
                  <SecondaryButton @click="closeCreateSubdivisionModal"> Cancelar </SecondaryButton>
                  <PrimaryButton type="submit" :class="{ 'opacity-25': form.processing }">Guardar</PrimaryButton>
                </div>
              </div>
            </div>
          </form>
        </div>
      </Modal>
      <ConfirmCreateModal :confirmingcreation="showModal" itemType="subdivisión de documentos" />
      <ConfirmDeleteModal :confirmingDeletion="create_subdivision" itemType="subdivisión"
        :deleteFunction="deleteSubdivision" @closeModal="closeModalSubdivision" />
    </AuthenticatedLayout>
  </div>
</template>
    
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ConfirmCreateModal from '@/Components/ConfirmCreateModal.vue';
import ConfirmDeleteModal from '@/Components/ConfirmDeleteModal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import { TrashIcon } from '@heroicons/vue/24/outline';
import { ref } from 'vue';
import Modal from '@/Components/Modal.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const showModal = ref(false);

const props = defineProps({
  subdivisions: Object,
  section: Object
});

const form = useForm({
  name: '',
});

const isCreateSubdivisionModalOpen = ref(false);
const create_subdivision = ref(false);
const subdivisionToDelete = ref(null);

const openCreateSubdivisionModal = () => {
  isCreateSubdivisionModalOpen.value = true;
};

const closeCreateSubdivisionModal = () => {
  isCreateSubdivisionModalOpen.value = false;
};

const submit = () => {
  form.post(route('documents.storeSubdivision', { section: props.section.id }), {
    onSuccess: () => {
      closeCreateSubdivisionModal();
      form.reset();
      showModal.value = true
      setTimeout(() => {
        showModal.value = false;
        router.visit(route('documents.subdivisions', { section: props.section.id }))
      }, 2000);
    },
    onError: () => {
      closeModal();
    }
  });
};

const confirmDeleteSubdivision = (subdivisionId) => {
  subdivisionToDelete.value = subdivisionId;
  create_subdivision.value = true;
};

const closeModalSubdivision = () => {
  create_subdivision.value = false;
};

const deleteSubdivision = async () => {
  const subdivisionId = subdivisionToDelete.value;
  router.delete(route('documents.destroySubdivision', { section: props.section.id, subdivision: subdivisionId }), {
    onSuccess: () => {
      closeModalSubdivision();
    }
  })

};

</script>
    