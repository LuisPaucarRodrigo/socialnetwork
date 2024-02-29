<template>
  <Head title="Gestion de Documentos" />
  <AuthenticatedLayout :redirectRoute="'documents.index'">
    <template #header>
      Documentos
    </template>
    <div class="inline-block min-w-full overflow-hidden rounded-lg shadow">
      <div class="flex gap-4">
        <button @click="openCreateDocumentModal" type="button"
          class="rounded-md bg-indigo-600 px-4 py-2 text-center text-sm text-white hover:bg-indigo-500">
          + Agregar Documento
        </button>
        <button @click="management_section" type="button"
          class="rounded-md bg-indigo-600 px-4 py-2 text-center text-sm text-white hover:bg-indigo-500">
          Gestionar Secciones
        </button>
        <div class="flex items-center ml-auto">
          <label for="selectElement" class="mr-2 text-sm text-indigo-600">Seleccione una sección:</label>
          <select v-model="selectedSection" id="selectElement"
            class="rounded-md py-2 text-sm text-black border-indigo-600">
            <option value="">Todos</option>
            <option v-for="section in sections" :key="section.id" :value="section.id">{{ section.name }}</option>
          </select>

          <!-- Nuevo filtro para subdivisiones -->
          <label for="selectSubdivision" class="mr-2 text-sm text-indigo-600 ml-3">Seleccione una subdivisión:</label>
          <select v-model="selectedSubdivision" id="selectSubdivision"
            class="rounded-md py-2 text-sm text-black border-indigo-600">
            <option value="">Todas</option>
            <option v-for="subdivision in subdivisionsForSelectedSection" :key="subdivision.id" :value="subdivision.id">{{
              subdivision.name }}</option>
          </select>
        </div>
      </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 lg:grid-cols-4 gap-4 mt-5">
      <div v-for="document in filteredDocuments" :key="document.id" class="bg-white p-4 rounded-md shadow md:col-span-2">
        <h2 class="text-sm font-semibold text-gray-700 line-clamp-1 mb-2">{{ getDocumentName(document.title) }}</h2>
        <div class="flex space-x-3 item-center">
          <button @click="openPreviewDocumentModal(document.id)" class="flex items-center text-green-600 hover:underline">
            <EyeIcon class="h-4 w-4 ml-1" />
          </button>
          <button @click="downloadDocument(document.id)" class="flex items-center text-blue-600 hover:underline">
            <ArrowDownIcon class="h-4 w-4 ml-1" />
          </button>
          <button v-if="hasPermission('UserManager')" @click="confirmDeleteDocument(document.id)" class="flex items-center text-red-600 hover:underline">
            <TrashIcon class="h-4 w-4" />
          </button>
        </div>
      </div>
    </div>

    <Modal :show="create_document">
      <div class="p-6">
        <h2 class="text-base font-medium leading-7 text-gray-900">
          Subir Documento
        </h2>
        <form @submit.prevent="submit">
          <div class="space-y-12">
            <div class="border-b border-gray-900/10 pb-12">
              <div>
                <InputLabel for="documentFile" class="font-medium leading-6 text-gray-900">Documento</InputLabel>
                <div class="mt-2">
                  <InputFile type="file" v-model="form.document" id="documentFile"
                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                  <InputError :message="form.errors.document" />
                </div>
              </div>
              <div>
                <InputLabel for="documentSection" class="text-gray-700">Sección:</InputLabel>
                <select v-model="form.section_id" id="documentSection" class="border rounded-md px-3 py-2 mb-3 w-full">
                  <option value="">Seleccionar Seccion</option>
                  <option v-for="section in sections" :key="section.id" :value="section.id">{{ section.name }}</option>
                </select>
                <InputError :message="form.errors.section_id" />
              </div>
              <div v-if="form.section_id">
                <InputLabel for="documentSubdivision" class="text-gray-700">Subdivisión:</InputLabel>

                <template v-if="filteredSubdivisions.length > 0">
                  <select v-model="form.subdivision_id" id="documentSubdivision"
                    class="border rounded-md px-3 py-2 mb-3 w-full">
                    <option value="">Seleccionar Subdivisión</option>
                    <option v-for="subdivision in filteredSubdivisions" :key="subdivision.id" :value="subdivision.id">{{
                      subdivision.name }}</option>
                  </select>
                  <InputError :message="form.errors.subdivision_id" />
                </template>

                <template v-else>
                  <p class="text-red-500">Cree subdivisiones para esta sección.</p>
                </template>
              </div>


              <div class="mt-6 flex items-center justify-end gap-x-6">
                <SecondaryButton @click="closeModal"> Cancel </SecondaryButton>
                <button type="submit" :class="{ 'opacity-25': form.processing }"
                  class="rounded-md bg-indigo-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Guardar</button>
              </div>
            </div>
          </div>
        </form>
      </div>
    </Modal>

    <ConfirmDeleteModal :confirmingDeletion="confirmingDocDeletion" itemType="documento" :deleteFunction="deleteDocument"
      @closeModal="closeModalDoc" />
    <ConfirmCreateModal :confirmingcreation="showModal" itemType="documento" />
  </AuthenticatedLayout>
</template>
  
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ConfirmCreateModal from '@/Components/ConfirmCreateModal.vue';
import ConfirmDeleteModal from '@/Components/ConfirmDeleteModal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputFile from '@/Components/InputFile.vue';
import Modal from '@/Components/Modal.vue';
import { ref, computed, watch } from 'vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import { TrashIcon, ArrowDownIcon, EyeIcon } from '@heroicons/vue/24/outline';

const props = defineProps({
  sections: Object,
  documents: Object,
  subdivisions: Object,
  userPermissions:Array
});

const hasPermission = (permission) => {
    return props.userPermissions.includes(permission);
}

const form = useForm({
  document: null,
  section_id: '',
  subdivision_id: '',
});

const filteredSubdivisions = ref([]);
const create_document = ref(false);
const showModal = ref(false);
const confirmingDocDeletion = ref(false);
const docToDelete = ref(null);

const documentToShow = ref(null);
const selectedSection = ref('');

const management_section = () => {
  router.get(route('documents.sections'));
};

const openCreateDocumentModal = () => {
  create_document.value = true;
};

const closeModal = () => {
  create_document.value = false;
};

const submit = () => {
  form.post(route('documents.create'), {
    onSuccess: () => {
      closeModal();
      form.reset();
      showModal.value = true
      setTimeout(() => {
        showModal.value = false;
        router.visit(route('documents.index'))
      }, 2000);
    },
    onError: () => {
      form.reset();
    },
    onFinish: () => {
      form.reset();
    }
  });
};

const confirmDeleteDocument = (documentId) => {
  docToDelete.value = documentId;
  confirmingDocDeletion.value = true;
};

const closeModalDoc = () => {
  confirmingDocDeletion.value = false;
};

const deleteDocument = () => {
  const docId = docToDelete.value;
  if (docId) {
    router.delete(route('documents.destroy', { id: docId }), {
      onSuccess: () => closeModalDoc()
    });
  }
};

function downloadDocument(documentId) {
  const backendDocumentUrl = route('documents.download', { document: documentId });
  window.open(backendDocumentUrl, '_blank');
};

function openPreviewDocumentModal(documentId) {
  documentToShow.value = documentId;
  const url = route('documents.show', { document: documentId });

  // Abrir la URL en una nueva pestaña
  window.open(url, '_blank');
}

const getDocumentName = (documentTitle) => {
  const parts = documentTitle.split('_');
  return parts.length > 1 ? parts.slice(1).join('_') : documentTitle;
};

const selectedSubdivision = ref('');

const subdivisionsForSelectedSection = computed(() => {
  // Filtra las subdivisiones según la sección seleccionada
  return props.subdivisions.filter(subdivision => subdivision.section_id === selectedSection.value);
});

const filteredDocuments = computed(() => {
  let filtered = props.documents.data;

  // Filtrar por sección
  if (selectedSection.value) {
    filtered = filtered.filter(document => document.section_id === selectedSection.value);
  }

  // Filtrar por subdivisión
  if (selectedSubdivision.value) {
    filtered = filtered.filter(document => document.subdivision_id === selectedSubdivision.value);
  }

  return filtered.map(document => ({ ...document, title: getDocumentName(document.title) }));
});

watch(() => selectedSection, () => {
  // Reiniciar la selección de la subdivisión cuando cambia la sección
  selectedSubdivision.value = '';
});

watch(() => form.section_id, (newSectionId, oldSectionId) => {
  // Si la sección cambió, restablecemos la lista de subdivisiones
  if (newSectionId !== oldSectionId) {
    filteredSubdivisions.value = [];
  }

  // Filtra las subdivisiones según la nueva sección seleccionada
  if (newSectionId) {
    filteredSubdivisions.value = props.subdivisions.filter(subdivision => subdivision.section_id === newSectionId);
  }
});

</script>

  