<template>
    <Head title="Gestion de Documentos" />
    <AuthenticatedLayout>
      <template #header>
        Cotizaciones de artículos de proveedores
      </template>
      <div class="inline-block min-w-full border-b-2 border-gray-200">
        <div class="flex gap-4 mb-2">
          <button @click="openCreateDocumentModal" type="button"
            class="rounded-md bg-indigo-500 px-4 py-2 text-center text-sm text-white hover:bg-indigo-300">
             + Agregar
          </button>
        </div>
        <div class="inline-flex items-center p-2 mb-4 text-sm text-yellow-800 rounded-lg bg-yellow-50 dark:bg-gray-800 dark:text-yellow-300" role="alert">
          <svg class="flex-shrink-0 inline w-4 h-4 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
            <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
          </svg>
          <span class="sr-only">Info</span>
          <div>
            <span class="font-small">Solo se puede hacer cambios cuando no exista una cotización para el proyecto</span> 
          </div>
        </div>
      </div>
  
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mt-5">
        <div v-for="(item, i) in providersquotes.data" :key="i" class="bg-white p-4 rounded-md shadow">
            <h2 class="text-sm font-semibold text-gray-700 line-clamp-1 mb-2">{{ getDocumentName(item.provider_quote) }}</h2>
            <div class="flex space-x-3 item-center">
            <button @click="openPreviewDocumentModal(item.id)" class="flex items-center text-green-600 hover:underline">
                <EyeIcon class="h-4 w-4 ml-1" />
            </button>
            <a :href="route('preprojects.providersquotes.download', { providerquote_id: item.id })" 
                 class="flex items-center text-blue-600 hover:underline">
                <ArrowDownIcon class="h-4 w-4 ml-1" />
            </a>
            <button @click="openDeleteModal(item.id)" class="flex items-center text-red-600 hover:underline">
                <TrashIcon class="h-4 w-4" />
            </button>
            </div>
        </div>
      </div>
  
      <Modal :show="create_document">
        <div class="p-6">
          <h2 class="text-base font-medium leading-7 text-gray-900 mb-6">
            Subir Documento
          </h2>
          <form @submit.prevent="submit">
            <div class="space-y-12">
              <div class="border-b border-gray-900/10 pb-6">
                <div>
                  <div class="mt-2">
                    <InputFile type="file" v-model="form.provider_quote" id="provider_quote" accept=".pdf,.jpeg,.png, .jpg"
                      class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                    <InputError :message="form.errors.provider_quote" />
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
  
      <ConfirmDeleteModal :confirmingDeletion="confirmReportDelete" itemType="cotización de proveedor" :deleteFunction="deleteDocument"
        @closeModal="closeModalDoc" />
      <ConfirmCreateModal :confirmingcreation="showModal" itemType="cotización de proveedor" />
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
  
  const { preproject, providersquotes} = defineProps({
    providersquotes: Object,
    preproject: Object
  });

  const initial_state = {
    provider_quote: null,
    preproject_id: preproject.id
  }

  const form = useForm({
    ...initial_state
  });
  
  const create_document = ref(false);
  const showModal = ref(false);
  const confirmReportDelete = ref(false);
  
  const itemToShow = ref(null);
  
  const openCreateDocumentModal = () => {
    create_document.value = true;
  };
  
  const closeModal = () => {
    create_document.value = false;
  };
  const submit = () => {
    let url = route('preprojects.providersquotes.store')
    form.post(url, {
      onSuccess: () => {
        closeModal();
        form.reset();
        showModal.value = true
        setTimeout(() => {
          showModal.value = false;
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


  
  const idToDelete = ref(null)
  const openDeleteModal = (id) => {
    idToDelete.value = id
    confirmReportDelete.value = true;
  }
  const closeModalDoc = () => {
    confirmReportDelete.value = false;
  };
  const deleteDocument = () => {
    router.delete(route('preprojects.providersquotes.delete', { providerquote_id: idToDelete.value }),{
      onSuccess: () => closeModalDoc()
    });
  };
  
  function openPreviewDocumentModal(id) {
    itemToShow.value = id;
    const url = route('preprojects.providersquotes.show', { providerquote_id: id });

    // Abrir la URL en una nueva pestaña
    window.open(url, '_blank');
  }

  const getDocumentName = (documentTitle) => {
    const parts = documentTitle.split('_');
    return parts.length > 1 ? parts.slice(1).join('_') : documentTitle;
  }; 
  
  
  </script>
  
    