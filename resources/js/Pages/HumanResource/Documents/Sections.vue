<template>
  <div>
    <Head title="Gestion de Secciones" />
    <AuthenticatedLayout :redirectRoute="'documents.index'">
      <template #header>
        Gestión de Secciones
      </template>
      <div class="inline-block min-w-full overflow-hidden rounded-lg shadow">
        <PrimaryButton @click="openCreateSectionModal">
          Crear Nueva Sección
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
              <tr v-for="section in sections" :key="section.id">
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm font-medium text-gray-900">{{ section.name }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-left">
                  <div class="flex items-center space-x-2">
                    <Link :href="route('documents.subdivisions', { section: section.id })"
                      class="text-blue-600 hover:underline">
                    <DocumentArrowUpIcon class="h-5 w-5" />
                    </Link>
                    <button @click="openUpdateSectionModal(section)" class="text-orange-400 hover:underline">
                      <PencilSquareIcon class="h-5 w-5" />
                    </button>
                    <button @click="confirmDeleteSection(section.id)" class="text-red-600 hover:underline">
                      <TrashIcon class="h-5 w-5" />
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

      </div>
      <Modal :show="isCreateSectionModalOpen || isUpdateSectionModalOpen">
        <div class="p-6">
          <h2 class="text-base font-medium leading-7 text-gray-900">
            {{ isCreateSectionModalOpen ? 'Agregar Sección' : 'Actualizar Sección'}}
          </h2>
          <form @submit.prevent="isCreateSectionModalOpen ? submit(false) : submit(true)">
            <div class="space-y-12">
              <div class="border-b border-gray-900/10 pb-12">
                <div>
                  <InputLabel for="name">{{ isCreateSectionModalOpen ? 'Agregar nueva sección:' : 'Actualizar Secciòn:'}}
                  </InputLabel>
                  <div class="mt-2">
                    <TextInput type="text" v-model="form.name" id="name" autocomplete="address-level1" />
                    <InputError :message="form.errors.name" />
                  </div>
                </div>
                <div class="mt-6 flex items-center justify-end gap-x-6">
                  <SecondaryButton @click="isCreateSectionModalOpen ? closeCreateSectionModal() : closeUpdateSectionModal()"> Cancelar </SecondaryButton>
                  <PrimaryButton type="submit" :class="{ 'opacity-25': form.processing }">
                    {{ isCreateSectionModalOpen ? 'Guardar' : 'Actualizar' }}
                  </PrimaryButton>
                </div>
              </div>
            </div>
          </form>
        </div>
      </Modal>
      <ConfirmCreateModal :confirmingcreation="showModal" itemType="Sección de documentos" />
      <ConfirmUpdateModal :confirmingupdate="showModalEdit" itemType="Sección de documentos" />
      <ConfirmDeleteModal :confirmingDeletion="create_section" itemType="Sección" :deleteFunction="deleteSection"
        @closeModal="closeModalSection" />
    </AuthenticatedLayout>
  </div>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ConfirmCreateModal from '@/Components/ConfirmCreateModal.vue';
import ConfirmUpdateModal from '@/Components/ConfirmUpdateModal.vue';
import ConfirmDeleteModal from '@/Components/ConfirmDeleteModal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import { Head, useForm, router, Link } from '@inertiajs/vue3';
import { TrashIcon, DocumentArrowUpIcon, PencilSquareIcon } from '@heroicons/vue/24/outline';
import { ref } from 'vue';
import Modal from '@/Components/Modal.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const showModal = ref(false);
const showModalEdit = ref(false);

const props = defineProps({
  sections: Object,
});

const form = useForm({
  id: '',
  name: '',
});

const isCreateSectionModalOpen = ref(false);
const isUpdateSectionModalOpen = ref(false);
const editingSection = ref(null);
const create_section = ref(false);
const sectionToDelete = ref(null);

const openCreateSectionModal = () => {
  isCreateSectionModalOpen.value = true;
};

const openUpdateSectionModal = (item) => {
    editingSection.value = JSON.parse(JSON.stringify(item));
    form.id = editingSection.value.id;
    form.name = editingSection.value.name;
    isUpdateSectionModalOpen.value = true;
};

const closeCreateSectionModal = () => {
    form.reset();
    isCreateSectionModalOpen.value = false;
};

const closeUpdateSectionModal = () => {
    form.reset();
    isUpdateSectionModalOpen.value = false;
};

const submit = (update) => {
    if (update){
        form.put(route('documents.updateSection', {section: form.id}), {
            onSuccess: () => {
            closeUpdateSectionModal();
            form.reset();
            showModalEdit.value = true
            setTimeout(() => {
                showModalEdit.value = false;
                router.visit(route('documents.sections'))
            }, 2000);
            }
        });
    } else {
        form.post(route('documents.storeSection'), {
            onSuccess: () => {
            closeCreateSectionModal();
            form.reset();
            showModal.value = true
            setTimeout(() => {
                showModal.value = false;
                router.visit(route('documents.sections'))
            }, 2000);
            }
        });
    }
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
