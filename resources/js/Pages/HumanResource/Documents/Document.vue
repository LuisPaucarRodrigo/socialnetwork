<template>

  <Head title="Gestion de Documentos" />
  <AuthenticatedLayout :redirectRoute="'documents.index'">
    <template #header>
      Documentos
    </template>
    <div class="flex gap-4 justify-between rounded-lg">
      <div class="flex flex-col sm:flex-row gap-4 justify-between w-full">
        <div class="flex gap-4 items-center">
          <PrimaryButton v-if="hasPermission('HumanResourceManager')" @click="openCreateDocumentModal" type="button"
            class="hidden sm:block rounded-md bg-indigo-600 px-4 py-2 text-center text-sm text-white hover:bg-indigo-500">
            + Agregar Documento
          </PrimaryButton>
          <PrimaryButton v-if="hasPermission('HumanResourceManager')" @click="management_section" type="button"
            class="hidden sm:block rounded-md bg-indigo-600 px-4 py-2 text-center text-sm text-white hover:bg-indigo-500">
            Gestionar Secciones
          </PrimaryButton>

          <div class="sm:hidden">
            <dropdown align='left'>
              <template #trigger>
                <button @click="dropdownOpen = !dropdownOpen"
                  class="relative block overflow-hidden rounded-md bg-gray-200 px-2 py-2 text-center text-sm text-white hover:bg-gray-100">
                  <svg width="25px" height="25px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M4 6H20M4 12H20M4 18H20" stroke="#000000" stroke-width="2" stroke-linecap="round"
                      stroke-linejoin="round" />
                  </svg>
                </button>
              </template>

              <template #content class="origin-left">
                <div> <!-- Alineación a la derecha -->
                  <div class="dropdown">
                    <div class="dropdown-menu">
                      <button @click="openCreateDocumentModal" type="button"
                        class="dropdown-item block w-full text-left px-4 py-2 text-sm text-black-700 hover:bg-indigo-600 hover:text-white focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                        + Agregar Documento
                      </button>
                    </div>
                  </div>
                  <div class="dropdown">
                    <div class="dropdown-menu">
                      <button @click="management_section" type="button"
                        class="dropdown-item block w-full text-left px-4 py-2 text-sm text-black-700 hover:bg-indigo-600 hover:text-white focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                        Gestionar Secciones
                      </button>
                    </div>
                  </div>
                </div>
              </template>
            </dropdown>
          </div>
          <div class="flex sm:hidden items-center ml-auto sm:ml-0">
            <form @submit.prevent="search" class="flex items-center w-full sm:w-auto">
              <TextInput type="text" placeholder="Buscar..." v-model="searchForm.searchTerm" />
              <button type="submit" :class="{ 'opacity-25': searchForm.processing }"
                class="ml-2 rounded-md bg-indigo-600 px-2 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                <svg width="30px" height="21px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path
                    d="M15.7955 15.8111L21 21M18 10.5C18 14.6421 14.6421 18 10.5 18C6.35786 18 3 14.6421 3 10.5C3 6.35786 6.35786 3 10.5 3C14.6421 3 18 6.35786 18 10.5Z"
                    stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
              </button>
            </form>
          </div>
        </div>

        <div class="flex sm:space-x-3  sm:flex-row flex-col sm:items-center">
          <InputLabel for="selectElement">Sección:</InputLabel>
          <select v-model="selectedSection" id="selectElement"
            class="rounded-md py-2 text-sm text-black border-indigo-600">
            <option :value="''">Todos</option>
            <option v-for="section in sections" :key="section.id" :value="section.id">
              {{ section.name }}
            </option>
          </select>

          <!-- Nuevo filtro para subdivisiones -->
          <InputLabel for="selectSubdivision">Subdivisión:</InputLabel>
          <select v-model="selectedSubdivision" id="selectSubdivision"
            class="rounded-md py-2 text-sm text-black border-indigo-600">
            <option :value="''">Todas</option>
            <option v-for="subdivision in subdivisionsForSelectedSection" :key="subdivision.id" :value="subdivision.id">
              {{ subdivision.name }}
            </option>
          </select>
        </div>
      </div>

      <div class="hidden sm:flex sm:items-center">
        <form @submit.prevent="search" class="flex items-center w-full sm:w-auto">
          <TextInput type="text" placeholder="Buscar..." v-model="searchForm.searchTerm" />
          <button type="submit" :class="{ 'opacity-25': searchForm.processing }"
            class="ml-2 rounded-md bg-indigo-600 px-2 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
            <svg width="30px" height="21px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path
                d="M15.7955 15.8111L21 21M18 10.5C18 14.6421 14.6421 18 10.5 18C6.35786 18 3 14.6421 3 10.5C3 6.35786 6.35786 3 10.5 3C14.6421 3 18 6.35786 18 10.5Z"
                stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
          </button>
        </form>
      </div>


    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 lg:grid-cols-4 gap-4 mt-5">
      <div v-for="document in filteredDocuments" :key="document.id"
        class="bg-white p-4 rounded-md shadow md:col-span-2">
        <h2 class="text-sm font-semibold text-gray-700 line-clamp-1 mb-2">{{ getDocumentName(document.title) }}</h2>
        <div class="flex space-x-3 item-center">
            <button v-if="document.title && /\.(pdf|png|jpe?g)$/.test(document.title)" @click="openPreviewDocumentModal(document.id)"
                class="flex items-center text-green-600 hover:underline">
                <EyeIcon class="h-4 w-4 ml-1" />
            </button>
          <button @click="downloadDocument(document.id)" class="flex items-center text-blue-600 hover:underline">
            <ArrowDownIcon class="h-4 w-4 ml-1" />
          </button>
          <button v-if="hasPermission('HumanResourceManager')" @click="openEditDocumentModal(document)" class="text-orange-200 hover:underline mr-2">
            <PencilIcon class="h-4 w-4 ml-1" />
          </button>
          <button v-if="hasPermission('UserManager')" @click="confirmDeleteDocument(document.id)"
            class="flex items-center text-red-600 hover:underline">
            <TrashIcon class="h-4 w-4" />
          </button>
        </div>
      </div>
    </div>
    <Modal :show="create_document || update_document">
      <div class="p-6">
        <h2 class="text-base font-medium leading-7 text-gray-900">
          {{ create_document ? 'Subir Documento' : 'Actualizar Documento' }}
        </h2>
        <form @submit.prevent="create_document ? submit() : submitEdit()">
          <div class="border-b border-gray-900/10 pb-12">
            <div class="mt-2">
              <InputLabel for="documentFile">Documento</InputLabel>
              <div class="mt-2">
                <InputFile type="file" v-model="form.document" id="documentFile" />
                <InputError :message="form.errors.document" />
              </div>
            </div>
            <div class="mt-2">
              <InputLabel for="documentSection">Sección:</InputLabel>
              <select v-model="form.section_id" id="documentSection" class="border rounded-md px-3 py-2 mb-3 w-full">
                <option value="">Seleccionar Seccion</option>
                <option v-for="section in sections" :key="section.id" :value="section.id">{{ section.name }}</option>
              </select>
              <InputError :message="form.errors.section_id" />
            </div>
            <div v-if="form.section_id">
              <InputLabel for="documentSubdivision">Subdivisión:</InputLabel>

              <template v-if="filteredSubdivisions.length > 0">
                <select v-model="form.subdivision_id" id="documentSubdivision"
                  class="border rounded-md px-3 py-2 mb-3 w-full">
                  <option value="">Seleccionar Subdivisión</option>
                  <option v-for="subdivision in filteredSubdivisions" :key="subdivision.id" :value="subdivision.id">
                    {{ subdivision.name }}</option>
                </select>
                <InputError :message="form.errors.subdivision_id" />
              </template>

              <template class="mt-2" v-else>
                <p class="text-red-500">Cree subdivisiones para esta sección.</p>
              </template>
            </div>


            <div class="mt-6 flex items-center justify-end gap-x-6">
              <SecondaryButton @click="create_document ? closeModal() : closeEditModal()"> Cancelar </SecondaryButton>
              <PrimaryButton type="submit" :class="{ 'opacity-25': form.processing }">
                Guardar
              </PrimaryButton>
            </div>
          </div>
        </form>
      </div>
    </Modal>

    <ConfirmDeleteModal :confirmingDeletion="confirmingDocDeletion" itemType="documento"
      :deleteFunction="deleteDocument" @closeModal="closeModalDoc" />
    <ConfirmCreateModal :confirmingcreation="showModal" itemType="documento" />
    <ConfirmUpdateModal :confirmingupdate="showEditModal" itemType="documento" />

  </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ConfirmCreateModal from '@/Components/ConfirmCreateModal.vue';
import ConfirmUpdateModal from '@/Components/ConfirmUpdateModal.vue';
import ConfirmDeleteModal from '@/Components/ConfirmDeleteModal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputFile from '@/Components/InputFile.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import Modal from '@/Components/Modal.vue';
import { ref, computed, watch } from 'vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import { TrashIcon, ArrowDownIcon, EyeIcon, PencilIcon } from '@heroicons/vue/24/outline';
import Dropdown from '@/Components/Dropdown.vue';


const props = defineProps({
  sections: Object,
  documents: Object,
  subdivisions: Object,
  userPermissions: Array,
  search: String
});

const hasPermission = (permission) => {
  return props.userPermissions.includes(permission);
}

const form = useForm({
  id: '',
  document: null,
  section_id: '',
  subdivision_id: '',
});

const filteredSubdivisions = ref([]);
const create_document = ref(false);
const update_document = ref(false);
const showModal = ref(false);
const showEditModal = ref(false);
const confirmingDocDeletion = ref(false);
const docToDelete = ref(null);
const editingDocument = ref(null);
const selectedSection = ref('');

const management_section = () => {
  router.get(route('documents.sections'));
};

const openCreateDocumentModal = () => {
  create_document.value = true;
};

const closeModal = () => {
  form.reset();
  create_document.value = false;
};

const openEditDocumentModal = (document) => {
  // Copia de los datos de la subsección existente al formulario
  editingDocument.value = JSON.parse(JSON.stringify(document));
  form.id = editingDocument.value.id;
  form.document = editingDocument.value.name;
  form.section_id = editingDocument.value.subdivision.section_id;
  form.subdivision_id = editingDocument.value.subdivision_id;
  update_document.value = true;
};

const closeEditModal = () => {
  form.reset();
  update_document.value = false;
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

const submitEdit = () => {
  form.post(route('documents.update', { id: form.id }), {
    onSuccess: () => {
      closeModal();
      form.reset();
      showEditModal.value = true
      setTimeout(() => {
        showEditModal.value = false;
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
  const routeToShow = route('documents.show', { document: documentId });
  window.open(routeToShow, '_blank');
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
  let filtered = props.documents;

  // Filtrar por sección
  if (selectedSection.value) {
    filtered = filtered.filter(document => document.subdivision.section_id === selectedSection.value);
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

const searchForm = useForm({
  searchTerm: props.search,
})

const search = () => {
  let data = { searchTerm: searchForm.searchTerm }
  router.get(route('documents.index'), data)
}
</script>
