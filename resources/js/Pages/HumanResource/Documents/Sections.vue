<template>
  <div>

    <Head title="Gestion de Secciones" />

    <AuthenticatedLayout>
      <div class="inline-block min-w-full overflow-hidden rounded-lg shadow">
        <button @click="openCreateSectionModal"
          class="rounded-md bg-indigo-600 px-4 py-2 text-center text-sm text-white hover:bg-indigo-500">
          Crear Nueva Sección
        </button>
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead>
              <tr>
                <th scope="col" class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Nombre
                </th>
                <!-- Agrega más columnas según tus necesidades -->
                <th scope="col" class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Acciones
                </th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <!-- Itera sobre las secciones recibidas como propiedades -->
              <tr v-for="section in sections" :key="section.id">
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm font-medium text-gray-900">{{ section.name }}</div>
                </td>
                <!-- Agrega cualquier otra información que desees mostrar en la tabla -->
                <td class="px-6 py-4 whitespace-nowrap text-left">
                  <button @click="confirmDeleteSection(section.id)" class="text-red-600 hover:underline">
                    <TrashIcon class="h-4 w-4" />
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

      </div>

      <!-- Teleport para el modal -->
      <teleport to="body">
        <div v-if="isCreateSectionModalOpen" class="fixed inset-0 z-50 flex items-center justify-center">
          <div class="absolute inset-0 bg-gray-800 opacity-75" @click="closeCreateSectionModal"></div>
          <div class="modal-container">
            <div class="modal-content bg-white w-1/2 p-5 rounded-md relative">
              <button @click="closeCreateSectionModal"
                class="close-button absolute top-0 right-0 mt-2 mr-2">&#10006;</button>
              <form @submit.prevent="openModal()" class="mt-5">
                <h1>Agregar nueva sección:</h1>
                <br>
                <InputLabel for="name" class="font-medium leading-6 text-gray-900">Nombre de la sección:</InputLabel>
                <div class="mt-2">
                  <TextInput type="text" v-model="form.name" id="end_date" autocomplete="address-level1"
                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                </div>
                <br>
                <button type="submit"
                  class="rounded-md bg-indigo-600 px-4 py-2 text-center text-sm text-white hover:bg-indigo-500">
                  Crear Sección
                </button>
              </form>
            </div>
          </div>
        </div>
      </teleport>
      <ConfirmCreateModal :confirmingcreation="showModal" itemType="seccion de documentos"
        :nameText="'de la nueva seccion de documentos'" :createFunction="submit" @closeModal="closeModal" />
    </AuthenticatedLayout>
  </div>
</template>
  
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ConfirmCreateModal from '@/Components/ConfirmCreateModal.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import { TrashIcon } from '@heroicons/vue/24/outline';
import { ref, defineProps } from 'vue';

const props = defineProps({
  sections: Object,
});

const form = useForm({
  name: '',
});

const isCreateSectionModalOpen = ref(false);

const openCreateSectionModal = () => {
  isCreateSectionModalOpen.value = true;
};

const closeCreateSectionModal = () => {
  isCreateSectionModalOpen.value = false;
};

const submit = async () => {
  try {
    const response = await form.post(route('documents.storeSection'));
    closeCreateSectionModal();
    console.log(response);
  } catch (error) {
    console.error('Error al crear la sección:', error.message);
    console.error(error);
    console.log(form);
  }
};

const confirmDeleteSection = (sectionId) => {
  console.log('Confirm delete section:', sectionId);
  if (confirm('¿Estás seguro de que quieres eliminar esta sección?')) {
    deleteSection(sectionId);
  }
};

const deleteSection = async (sectionId) => {
  console.log('Deleting section:', sectionId);
  router.delete(`/document_sections/${sectionId}`);
};

const showModal = ref(false);

const openModal = () => {
  showModal.value = true;
}
const closeModal = () => {
  showModal.value = false;
}


</script>
  
<style scoped>
.modal-container {
  display: flex;
  align-items: center;
  justify-content: center;
  height: 100%;
}

.modal-content {
  width: 100%;
}

h1 {
  font-weight: bold;
  font-size: large;
  justify-content: center;
  display: flex;
}
</style>
  