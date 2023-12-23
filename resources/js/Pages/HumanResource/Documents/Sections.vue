<template>
    <div>
      <Head title="Gestion de Secciones" />
  
      <AuthenticatedLayout>
        <div class="inline-block min-w-full overflow-hidden rounded-lg shadow">
          <button @click="openCreateSectionModal" class="rounded-md bg-indigo-600 px-4 py-2 text-center text-sm text-white hover:bg-indigo-500">
            Crear Nueva Sección
          </button>
          <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 mt-5">
          <!-- Itera sobre las secciones recibidas como propiedades -->
          <div v-for="section in sections" :key="section.id" class="bg-white p-4 rounded-md shadow">
            <h2 class="text-xl font-semibold mb-2">{{ section.name }}</h2>
            <!-- Agrega cualquier otra información que desees mostrar en la tarjeta -->
            <button @click="confirmDeleteSection(section.id)" class="text-red-600 hover:underline">
              <TrashIcon class="h-4 w-4" />
            </button>  
        </div>
        </div>
        </div>
  
        <!-- Teleport para el modal -->
        <teleport to="body">
          <div v-if="isCreateSectionModalOpen" class="fixed inset-0 z-50 flex items-center justify-center">
            <div class="absolute inset-0 bg-gray-800 opacity-75" @click="closeCreateSectionModal"></div>
            <div class="modal-container">
              <div class="modal-content bg-white w-1/2 p-5 rounded-md relative">
                <button @click="closeCreateSectionModal" class="close-button absolute top-0 right-0 mt-2 mr-2">&#10006;</button>
                <form @submit.prevent="submit" class="mt-5">
                    <h1>Agregar nueva sección:</h1>
                    <br>
                    <InputLabel for="name" class="font-medium leading-6 text-gray-900">Nombre de la sección:</InputLabel>
                        <div class="mt-2">
                                <TextInput type="text" v-model="form.name" id="end_date" autocomplete="address-level1" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" /> 
                        </div>
                        <br>
                    <button type="submit" class="rounded-md bg-indigo-600 px-4 py-2 text-center text-sm text-white hover:bg-indigo-500">
                    Crear Sección
                  </button>
                </form>
              </div>
            </div>
          </div>
        </teleport>
      </AuthenticatedLayout>
    </div>
  </template>
  
  <script setup>
  import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
  import { ref } from 'vue';
  import InputLabel from '@/Components/InputLabel.vue';
  import TextInput from '@/Components/TextInput.vue';
  import InputError from '@/Components/InputError.vue';
  import { Head, useForm, router } from '@inertiajs/vue3';
  import { TrashIcon } from '@heroicons/vue/24/outline';

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

  h1{
    font-weight: bold;
    font-size: large;
    justify-content: center;
    display: flex;
  }
  </style>
  