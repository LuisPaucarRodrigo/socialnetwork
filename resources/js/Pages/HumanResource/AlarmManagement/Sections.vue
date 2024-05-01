<template>
    <Head title="Gestion de Apartados" />
    <AuthenticatedLayout :redirectRoute="'sections.subSections'">
      <template #header>
        Gestión de Apartados
      </template>
      <div class="inline-block min-w-full overflow-hidden rounded-lg shadow">
        <PrimaryButton @click="openCreateSectionModal">
          Crear Nuevo Apartado
        </PrimaryButton>
        <div class="overflow-x-auto mt-5">
          <table class="min-w-full whitespace-no-wrap">
            <thead>
              <tr class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                  Nombre
                </th>
                <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                  Acciones
                </th>
              </tr>
            </thead>
            <tbody>
              <tr class="bg-white" v-for="section in sections.data" :key="section.id">
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm font-medium text-gray-900">{{ section.name }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-left">
                  <div class="flex items-center space-x-2">
                    <button @click="confirmDeleteSection(section.id)" class="text-red-600 hover:underline">
                      <TrashIcon class="h-4 w-4" />
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <div
                class="flex flex-col items-center border-t bg-white px-5 py-5 xs:flex-row xs:justify-between">
                <pagination :links="sections.links" />
          </div>

      </div>
      <Modal :show="isCreateSectionModalOpen">
        <div class="p-6">
          <h2 class="text-base font-medium leading-7 text-gray-900">
            Agregar Apartado
          </h2>
          <form @submit.prevent="submit">
            <div class="space-y-12">
              <div class="border-b border-gray-900/10 pb-12">
                <div>
                  <InputLabel for="name">Agregar nuevo apartado:
                  </InputLabel>
                  <div class="mt-2">
                    <TextInput type="text" v-model="form.name" id="name" autocomplete="address-level1" />
                    <InputError :message="form.errors.name" />
                  </div>
                </div>
                <div class="mt-6 flex items-center justify-end gap-x-6">
                  <SecondaryButton @click="closeCreateSectionModal"> Cancel </SecondaryButton>
                  <PrimaryButton type="submit" :class="{ 'opacity-25': form.processing }">
                    Guardar
                  </PrimaryButton>
                </div>
              </div>
            </div>
          </form>
        </div>
      </Modal>
      <ConfirmCreateModal :confirmingcreation="showModal" itemType="seccion de documentos" />
      <ConfirmDeleteModal :confirmingDeletion="create_section" itemType="seccion" :deleteFunction="deleteSection"
        @closeModal="closeModalSection" />
    </AuthenticatedLayout>
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
import Pagination from '@/Components/Pagination.vue';

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
  form.post(route('sections.storeSection'), {
    onSuccess: () => {
      closeCreateSectionModal();
      form.reset();
      showModal.value = true
      setTimeout(() => {
        showModal.value = false;
        router.visit(route('sections.sections'))
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
  router.delete(route('sections.destroySection', { section: sectionId }), {
    onSuccess: () => {
      closeModalSection();
    }
  })

};

</script>