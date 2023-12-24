<template>
  <div>

    <Head title="Gestion de Documentos" />

    <AuthenticatedLayout>
      <div class="flex space-x-4">
        <button @click="openCreateDocumentModal" class="flex items-center text-indigo-600 hover:underline">
          <DocumentArrowUpIcon class="h-4 w-4" /> Agregar Documento
        </button>
        <div class="flex items-center">
          <label for="selectElement" class="mr-2 text-sm text-indigo-600">Seleccione una sección:</label>
          <select v-model="selectedSection" id="selectElement"
            class="rounded-md py-2 text-sm text-black hover:bg-gray-100 border-indigo-600">
            <option value="" key="all">Todos</option>
            <option v-for="section in sections" :key="section.id" :value="section.id">{{ section.name }}</option>
          </select>
        </div>
        <Link :href="route('documents.sections')" class="flex items-center text-indigo-600 hover:underline">
        <ArchiveBoxIcon class="h-4 w-4" /> Gestionar Secciones
        </Link>
      </div>


      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 lg:grid-cols-4 gap-4 mt-5">
        <!-- Itera sobre las secciones recibidas como propiedades -->
        <div v-for="document in filteredDocuments" :key="document.id"
          class="bg-white p-4 rounded-md shadow md:col-span-2">
          <h2 class="text-xl font-semibold mb-2">{{ document.title }}</h2>
          <!-- Agrega cualquier otra información que desees mostrar en la tarjeta -->
          <div class="flex items-center">
            <button @click="confirmDeleteDocument(document.id)" class="flex items-center text-red-600 hover:underline">
              <TrashIcon class="h-4 w-4" />
            </button>
            <button @click="downloadDocument(document.id)" class="flex items-center text-blue-600 hover:underline"
              style="margin-left: 1%;">
              <ArrowDownIcon class="h-4 w-4 ml-1" />
            </button>
            <button @click="openPreviewDocumentModal(document.id)"
              class="flex items-center text-green-600 hover:underline" style="margin-left: 2%;">
              <EyeIcon class="h-4 w-4 ml-1" />
            </button>
          </div>
        </div>
      </div>

      <teleport to="body">
        <div v-if="isPreviewDocumentModalOpen" class="fixed inset-0 z-50 flex items-center justify-center">
          <div class="absolute inset-0 bg-gray-800 opacity-75" @click="closePreviewDocumentModal"></div>
          <div class="modal-container flex items-center justify-center">
            <div class="modal-content bg-white p-5 rounded-md relative"
              style="width: 100% !important; height: 60% !important;">

              <button @click="closePreviewDocumentModal"
                class="close-button absolute top-0 right-0 mt-2 mr-2">&#10006;</button>
              <!-- Contenido del modal -->
              <iframe :src="getDocumentUrl(documentToShow)" width="100%" height="100%"></iframe>
              <!-- Cambiado a 100% de ancho y alto -->
            </div>
          </div>
        </div>
      </teleport>


      <!-- Modal para agregar documento -->
      <teleport to="body">
        <div v-if="isCreateDocumentModalOpen" class="fixed inset-0 z-50 flex items-center justify-center">
          <div class="absolute inset-0 bg-gray-800 opacity-75" @click="closeCreateDocumentModal"></div>
          <div class="modal-container flex items-center justify-center">
            <div class="modal-content bg-white w-1/2 p-5 rounded-md relative">
              <button @click="closeCreateDocumentModal"
                class="close-button absolute top-0 right-0 mt-2 mr-2">&#10006;</button>
              <form @submit.prevent="submitForm" class="mt-5">
                <label for="documentFile" class="text-gray-700">Documento:</label>
                <InputFile @change="handleFileChange" id="documentFile"
                  class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                <InputError :message="form.errors.document" />

                <label for="documentSection" class="text-gray-700">Sección:</label>
                <select v-model="form.section_id" id="documentSection" class="border rounded-md px-3 py-2 mb-3 w-full">
                  <option v-for="section in sections" :key="section.id" :value="section.id">{{ section.name }}</option>
                </select>

                <button type="submit"
                  class="rounded-md bg-indigo-600 px-4 py-2 text-center text-sm text-white hover:bg-indigo-500">
                  Agregar Documento
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
import InputFile from '@/Components/InputFile.vue';
import { ref, computed } from 'vue';
import { Head, useForm, router, Link } from '@inertiajs/vue3';
import { TrashIcon, ArrowDownIcon, EyeIcon, DocumentArrowUpIcon, ArchiveBoxIcon } from '@heroicons/vue/24/outline';

const props = defineProps({
  sections: Object,
  documents: Object,
});

const form = useForm({
  document: null,
  section_id: null,
});

const isCreateDocumentModalOpen = ref(false);
const documentToShow = ref(null);
const selectedSection = ref(null);
const isPreviewDocumentModalOpen = ref(false);

const openCreateDocumentModal = () => {
  isCreateDocumentModalOpen.value = true;
};

const closeCreateDocumentModal = () => {
  isCreateDocumentModalOpen.value = false;
};

const closePreviewDocumentModal = () => {
  isPreviewDocumentModalOpen.value = false;
};

function openPreviewDocumentModal(documentId) {
  documentToShow.value = documentId;
  isPreviewDocumentModalOpen.value = true;
}

function downloadDocument(documentId) {
  const backendDocumentUrl = route('documents.download', { document: documentId });

  // Abre una nueva ventana para descargar el archivo
  window.open(backendDocumentUrl, '_blank');
}

function getDocumentUrl(documentId) {
  return route('documents.show', { document: documentId });
}

const filteredDocuments = computed(() => {
  if (!selectedSection.value) {
    return props.documents.data;
  }
  return props.documents.data.filter(document => document.section_id === selectedSection.value);
});

const submitForm = async () => {
  try {
    const formData = new FormData();
    formData.append('document', form.document);
    formData.append('section_id', form.section_id);

    const response = await axios.post(route('documents.create'), formData, {
      headers: {
        'Content-Type': 'multipart/form-data',
      },
    });

    closeCreateDocumentModal();
    console.log(response);
    window.location.reload()
  } catch (error) {
    console.error('Error al crear el documento:', error.message);
    console.error(error);
    console.log(form);
  }
};

const handleFileChange = (event) => {
  const fileInput = event.target;
  const file = fileInput.files[0];
  form.document = file;
};


const confirmDeleteDocument = (documentId) => {
  console.log('Confirm delete document:', documentId);
  if (confirm('¿Estás seguro de que quieres eliminar este documento?')) {
    deleteDocument(documentId);
  }
};

const deleteDocument = async (documentId) => {
  console.log('Deleting document:', documentId);
  router.delete(`/documents/${documentId}/delete`);
};
</script>
  
<style scoped>
/* Estilos para las cards y otros estilos según sea necesario */
.document-card {
  /* Estilos para las cards de documentos */
  margin: 10px;
  padding: 10px;
  border: 1px solid #ddd;
  /* Otros estilos según sea necesario */
}

.modal-container {
  display: flex;
  align-items: center;
  justify-content: center;
  height: 150%;
  width: 75%;
}

.modal-content {
  width: 100%;
}
</style>
  