<template>
  <Head title="Gestion de Documentos" />
  <AuthenticatedLayout>
    <template #header>
      Informe Fotográfico
    </template>
    <div class="inline-block min-w-full border-b-2 border-gray-200">
      <div class="flex gap-4 mb-2">
        <button @click="openCreateDocumentModal" type="button"
          class="rounded-md bg-indigo-500 px-4 py-2 text-center text-sm text-white hover:bg-indigo-300">
          {{ photoreport ? 'Editar' : '+ Agregar' }}
        </button>
        <button v-if="photoreport" @click="openDeleteModal" type="button"
          class="rounded-md bg-red-500 px-4 py-2 text-center text-sm text-white hover:bg-red-300">
          Eliminar
        </button>
      </div>
      <div
        class="inline-flex items-center p-2 mb-4 text-sm text-yellow-800 rounded-lg bg-yellow-50 dark:bg-gray-800 dark:text-yellow-300"
        role="alert">
        <svg class="flex-shrink-0 inline w-4 h-4 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
          fill="currentColor" viewBox="0 0 20 20">
          <path
            d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
        </svg>
        <span class="sr-only">Info</span>
        <div>
          <span class="font-small">Solo se puede hacer cambios cuando NO exista una cotización para el cliente</span>
        </div>
      </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 lg:grid-cols-4 gap-4 mt-5">
      <div v-if="photoreport?.excel_report" class="bg-white p-4 rounded-md shadow md:col-span-2">
        <h2 class="text-sm font-semibold text-green-600 line-clamp-1 mb-2">{{ getDocumentName(photoreport?.excel_report)
        }}</h2>
        <div class="flex space-x-3 item-center">
          <a :href="route('preprojects.photoreport.download', { report_name: photoreport.excel_report })" target="_blank"
            class="flex items-center text-blue-600 hover:underline">
            <ArrowDownIcon class="h-4 w-4 ml-1" />
          </a>
        </div>
      </div>
      <div v-if="photoreport?.pdf_report" class="bg-white p-4 rounded-md shadow md:col-span-2">
        <h2 class="text-sm font-semibold text-red-600 line-clamp-1 mb-2">{{ getDocumentName(photoreport?.pdf_report) }}
        </h2>
        <div class="flex space-x-3 item-center">
          <button @click="openPreviewDocumentModal(photoreport?.pdf_report)"
            class="flex items-center text-green-600 hover:underline">
            <EyeIcon class="h-4 w-4 ml-1" />
          </button>
          <a :href="route('preprojects.photoreport.download', { report_name: photoreport.pdf_report })" target="_blank"
            class="flex items-center text-blue-600 hover:underline">
            <ArrowDownIcon class="h-4 w-4 ml-1" />
          </a>
        </div>
      </div>
    </div>

    <Modal :show="create_document">
      <div class="p-6">
        <h2 class="text-base font-medium leading-7 text-gray-900 mb-6">
          Subir Documentos
        </h2>
        <form @submit.prevent="submit">
          <div class="space-y-12">
            <div class="border-b border-gray-900/10 pb-6">
              <div>
                <InputLabel for="excel_report" class="font-medium leading-6 text-gray-900">Formato EXCEL</InputLabel>
                <InputLabel v-if="photoreport" class="font-medium leading-6 text-indigo-700 mt-2">Documento actual: {{
                  getDocumentName(photoreport?.excel_report) }}</InputLabel>
                <div class="mt-2">
                  <InputFile type="file" v-model="form.excel_report" id="excel_report" accept=".xls, .xlsx"
                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                  <InputError :message="form.errors.excel_report" />
                </div>
              </div>
              <div class="mt-4">
                <InputLabel for="pdf_report" class="font-medium leading-6 text-gray-900">Formato PDF</InputLabel>
                <InputLabel v-if="photoreport" class="font-medium leading-6 text-indigo-700 mt-2">Documento actual: {{
                  getDocumentName(photoreport?.pdf_report) }}</InputLabel>
                <div class="mt-2">
                  <InputFile type="file" v-model="form.pdf_report" id="pdf_report" accept=".pdf"
                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                  <InputError :message="form.errors.pdf_report" />
                </div>
              </div>
              <div class="mt-6 flex items-center justify-end gap-x-6">
                <SecondaryButton @click="closeModal"> Cancelar </SecondaryButton>
                <button type="submit" :class="{ 'opacity-25': form.processing }"
                  class="rounded-md bg-indigo-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Guardar</button>
              </div>
            </div>
          </div>
        </form>
      </div>
    </Modal>

    <ConfirmDeleteModal :confirmingDeletion="confirmReportDelete" itemType="informe fotográfico"
      :deleteFunction="() => deleteDocument(photoreport?.id)" @closeModal="closeModalDoc" />
    <ConfirmCreateModal :confirmingcreation="showModal" itemType="informe fotográfico" />
    <ConfirmUpdateModal :confirmingupdate="showModalUpdate" itemType="informe fotográfico" />
  </AuthenticatedLayout>
</template>
    
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ConfirmCreateModal from '@/Components/ConfirmCreateModal.vue';
import ConfirmDeleteModal from '@/Components/ConfirmDeleteModal.vue';
import ConfirmUpdateModal from '@/Components/ConfirmUpdateModal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputFile from '@/Components/InputFile.vue';
import Modal from '@/Components/Modal.vue';
import { ref, computed, watch } from 'vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import { TrashIcon, ArrowDownIcon, EyeIcon } from '@heroicons/vue/24/outline';

const { documents, preproject, photoreport } = defineProps({
  photoreport: Object,
  preproject: Object
});

const initial_state = {
  excel_report: null,
  pdf_report: null,
  preproject_id: preproject.id
}

const form = useForm({
  ...initial_state
});

const create_document = ref(false);
const showModal = ref(false);
const showModalUpdate = ref(false);
const confirmReportDelete = ref(false);

const documentToShow = ref(null);

const openCreateDocumentModal = () => {
  create_document.value = true;
};

const closeModal = () => {
  create_document.value = false;
};

const submit = () => {
  let url = preproject.photoreport ? route('preprojects.photoreport.update', { photoreport: photoreport.id }) : route('preprojects.photoreport.store')
  form.post(url, {
    onSuccess: () => {
      closeModal();
      form.reset();
      preproject ? showModalUpdate.value = true : showModal.value = true
      setTimeout(() => {
        preproject ? showModalUpdate.value = false : showModal.value = false
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




const openDeleteModal = () => {
  confirmReportDelete.value = true;
}
const closeModalDoc = () => {
  confirmReportDelete.value = false;
};
const deleteDocument = (id) => {
  router.delete(route('preprojects.photoreport.delete', { photoreport: id }), {
    onSuccess: () => closeModalDoc()
  });

};

function openPreviewDocumentModal(name) {
  documentToShow.value = name;
  const url = route('preprojects.photoreport.show', { report_name: name });
  // Abrir la URL en una nueva pestaña
  window.open(url, '_blank');
}

const getDocumentName = (documentTitle) => {
  const parts = documentTitle.split('_');
  return parts.length > 1 ? parts.slice(1).join('_') : documentTitle;
};

</script>
  
    