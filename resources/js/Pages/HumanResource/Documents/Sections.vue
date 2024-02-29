<template>
  <div>
    <Head title="Gestion de Secciones" />
    <AuthenticatedLayout :redirectRoute="'documents.index'">
      <template #header>
        Gestión de Secciones
      </template>
      <div class="inline-block min-w-full overflow-hidden rounded-lg shadow">
        <button @click="openCreateSectionModal"
          class="rounded-md bg-indigo-600 px-4 py-2 text-center text-sm text-white hover:bg-indigo-500">
          Crear Nueva Sección
        </button>
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
              <tr v-for="section in sections" :key="section.id">
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm font-medium text-gray-900">{{ section.name }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-left">
                  <div class="flex items-center space-x-2">
                    <Link :href="route('documents.subdivisions', { section: section.id })"
                      class="text-blue-600 hover:underline">
                    <DocumentArrowUpIcon class="h-4 w-4" />
                    </Link>
                    <button @click="confirmDeleteSection(section.id)" class="text-red-600 hover:underline">
                      <TrashIcon class="h-4 w-4" />
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

      </div>
      <Modal :show="isCreateSectionModalOpen">
        <div class="p-6">
          <h2 class="text-base font-medium leading-7 text-gray-900">
            Agregar Sección
          </h2>
          <form @submit.prevent="submit">
            <div class="space-y-12">
              <div class="border-b border-gray-900/10 pb-12">
                <div>
                  <InputLabel for="name" class="font-medium leading-6 text-gray-900">Agregar nueva sección:
                  </InputLabel>
                  <div class="mt-2">
                    <TextInput type="text" v-model="form.name" id="name" autocomplete="address-level1"
                      class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                    <InputError :message="form.errors.name" />
                  </div>
                </div>
                <div class="mt-6 flex items-center justify-end gap-x-6">
                  <SecondaryButton @click="closeCreateSectionModal"> Cancel </SecondaryButton>
                  <button type="submit" :class="{ 'opacity-25': form.processing }"
                    class="rounded-md bg-indigo-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Guardar</button>
                </div>
              </div>
            </div>
          </form>
        </div>
      </Modal>
      <ConfirmCreateModal :confirmingcreation="showModal" itemType="sección de documentos" />
      <ConfirmDeleteModal :confirmingDeletion="create_section" itemType="sección" :deleteFunction="deleteSection"
        @closeModal="closeModalSection" />
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
import { Head, useForm, router, Link } from '@inertiajs/vue3';
import { TrashIcon, DocumentArrowUpIcon } from '@heroicons/vue/24/outline';
import { ref, defineProps } from 'vue';
import Modal from '@/Components/Modal.vue';

const showModal = ref(false);

const props = defineProps({
  sections: Object,
});

const form = useForm({
  name: '',
});

const isCreateSectionModalOpen = ref(false);
const create_section = ref(false);
const sectionToDelete = ref(null);

const openCreateSectionModal = () => {
  isCreateSectionModalOpen.value = true;
};

const closeCreateSectionModal = () => {
  isCreateSectionModalOpen.value = false;
};

const submit = () => {
  form.post(route('documents.storeSection'), {
    onSuccess: () => {
      closeCreateSectionModal();
      form.reset();
      showModal.value = true
      setTimeout(() => {
        showModal.value = false;
        router.visit(route('documents.sections'))
      }, 2000);
    },
    onError: () => {
      closeModal();
    }
  });
};

const confirmDeleteSection = (sectionId) => {
  sectionToDelete.value = sectionId;
  create_section.value = true;
};

const closeModalSection = () => {
  create_section.value = false;
};

const deleteSection = async () => {
  const sectionId = sectionToDelete.value;
  router.delete(route('documents.destroySection', { section: sectionId }), {
    onSuccess: () => {
      closeModalSection();
    },

  })

};

</script>
  