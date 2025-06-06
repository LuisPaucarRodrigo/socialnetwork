<template>

  <Head title="Gestion de Documentos" />
  <AuthenticatedLayout :redirectRoute="backUrl">
    <template #header>
      Informe Fotográfico
    </template>
    <div v-if="!photoreport || auth.user.role_id === 1" class="inline-block min-w-full border-b-2 border-gray-200">
      <div v-if="preproject.status === null && !preproject.has_quote"
        class="flex gap-4 mb-2">
        <button @click="openCreateDocumentModal" type="button"
          class="rounded-md bg-indigo-500 px-4 py-2 text-center text-sm text-white hover:bg-indigo-300">
          {{ (photoreport && auth.user.role_id === 1) ? 'Editar' : '+ Agregar' }}
        </button>
        <button v-if="photoreport && auth.user.role_id === 1" @click="openDeleteModal" type="button"
          class="rounded-md bg-red-500 px-4 py-2 text-center text-sm text-white hover:bg-red-300">
          Eliminar
        </button>
      </div>
      <div v-if="auth.user.role_id === 1 && preproject.has_quote"
        class="inline-flex items-center p-2 mb-4 text-sm text-yellow-800 rounded-lg bg-yellow-50 dark:bg-gray-800 dark:text-yellow-300"
        role="alert">
        <svg class="flex-shrink-0 inline w-4 h-4 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
          fill="currentColor" viewBox="0 0 20 20">
          <path
            d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
        </svg>
        <span class="sr-only">Info</span>
        <div>
          <span class="font-small">Ya se hizo una cotización del proyecto en base a este informe fotográfico</span>
        </div>
      </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 lg:grid-cols-4 gap-4 mt-5">
      <div v-if="photoreport?.excel_report" class="bg-white p-4 rounded-md shadow md:col-span-2">
        <h2 class="text-sm font-semibold text-green-600 line-clamp-1 mb-2">{{ getDocumentName(photoreport?.excel_report)
        }}</h2>
        <div class="flex space-x-3 item-center">
          <a :href="route('preprojects.photoreport.download', { report_name: photoreport.excel_report })"
            target="_blank">
            <DownloadIcon />
          </a>
        </div>
      </div>
      <div v-if="photoreport?.pdf_report" class="bg-white p-4 rounded-md shadow md:col-span-2">
        <h2 class="text-sm font-semibold text-red-600 line-clamp-1 mb-2">{{ getDocumentName(photoreport?.pdf_report) }}
        </h2>
        <div class="flex space-x-3 item-center">
          <button @click="openPreviewDocumentModal(photoreport.id)">
            <ShowIcon />
          </button>
          <button @click="downloadPRPdf(photoreport.id)" target="_blank">
            <DownloadIcon />
          </button>
        </div>
      </div>
    </div>

    <Modal :show="create_document">
      <div class="p-6">
        <h2 class="text-base font-medium leading-7 text-gray-900 mb-6">
          Subir Documentos
        </h2>
        <form @submit.prevent="submit(
          e,
          photoreport?.id
            ? route('preprojects.photoreport.update', {
              photoreport: photoreport.id
            })
            : route('preprojects.photoreport.store'))">
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
    <SuccessOperationModal :confirming="showModal" :title="`Informe fotográfico guardado`"
      :message="`El informe fotográfico fue guardado con éxito.`" />
  </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ConfirmDeleteModal from '@/Components/ConfirmDeleteModal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputFile from '@/Components/InputFile.vue';
import Modal from '@/Components/Modal.vue';
import { ref } from 'vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import SuccessOperationModal from '@/Components/SuccessOperationModal.vue';
import { DownloadIcon, ShowIcon } from "@/Components/Icons/Index";

const { documents, preproject, photoreport, auth, userPermissions } = defineProps({
  photoreport: Object,
  preproject: Object,
  auth: Object,
  userPermissions: Array
});

const initial_state = {
  excel_report: null,
  pdf_report: null,
  preproject_id: preproject.id
}

let backUrl = (preproject?.status === undefined || preproject?.status === null)
  ? { route: 'preprojects.index', params: { type: preproject.cost_line_id } }
  : preproject.status == true
    ? { route: 'preprojects.index', params: { type: preproject.cost_line_id, preprojects_status: 1 } }
    : { route: 'preprojects.index', params: { type: preproject.cost_line_id, preprojects_status: 0 } }



const form = useForm({
  ...initial_state
});

const create_document = ref(false);
const showModal = ref(false);
const confirmReportDelete = ref(false);


const openCreateDocumentModal = () => {
  create_document.value = true;
};

const closeModal = () => {
  create_document.value = false;
};

const submit = (e, url) => {
  form.post(url, {
    onSuccess: () => {
      closeModal();
      form.reset();
      showModal.value = true
      setTimeout(() => {
        showModal.value = false
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


const getDocumentName = (documentTitle) => {
  const parts = documentTitle.split('_');
  return parts.length > 1 ? parts.slice(1).join('_') : documentTitle;
};

function downloadPRPdf(id) {
  const uniqueParam = `timestamp=${new Date().getTime()}`;
  const backendDocumentUrl = route('preprojects.photoreport_pdf.download', { pr_id: id }) + '?' + uniqueParam;
  window.open(backendDocumentUrl, '_blank');
};

function openPreviewDocumentModal(id) {
  const uniqueParam = `timestamp=${new Date().getTime()}`;
  const url = route('preprojects.photoreport.show', { pr_id: id }) + '?' + uniqueParam;
  window.open(url, '_blank');
}

</script>