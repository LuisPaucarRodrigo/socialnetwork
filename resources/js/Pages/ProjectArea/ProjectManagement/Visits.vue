<template>
    <Head title="Gestion de Documentos" />
    <AuthenticatedLayout>
      <template #header>
        Visitas
      </template>
      <div class="inline-block min-w-full overflow-hidden rounded-lg shadow">
        <div class="flex gap-4">
          <button @click="openCreateVisitModal" type="button"
            class="rounded-md bg-indigo-600 px-4 py-2 text-center text-sm text-white hover:bg-indigo-500">
            + Agregar
          </button>       
        </div>
      </div>
  
      <div class="inline-block min-w-full overflow-hidden rounded-lg shadow">
        <div class="talwing mt-4">
            <table class="w-full whitespace-no-wrap">
            <thead>
                <tr class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                    Cliente
                </th>
                <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                    Teléfono
                </th>
                <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                    Descripción
                </th>
                <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                    Dirección
                </th>
                <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                    Fecha
                </th>
                <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                    Acciones
                </th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="visit in visits.data" :key="visit.id" class="text-gray-700">
                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                    <p class="text-gray-900 whitespace-no-wrap">{{ visit.customer }}</p>
                </td>
                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                    <p class="text-gray-900 whitespace-no-wrap">{{ visit.phone }}</p>
                </td>
                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                    <p class="text-gray-900 whitespace-no-wrap">{{ visit.description }}</p>
                </td>
                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                    <p class="text-gray-900 whitespace-no-wrap">{{ visit.address }}</p>
                </td>
                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                    <p class="text-gray-900 whitespace-no-wrap">{{ visit.date }}</p>
                </td>
                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                  <div class="h-full flex items-center justify-center">
                      <button @click="openPreviewDocumentModal(visit.id)" class="flex items-center text-green-600 hover:underline">
                          <EyeIcon class="h-4 w-4 mr-2" />
                      </button>
                      <button @click="confirmDeleteDocument(visit.id)" class="flex items-center text-red-600 hover:underline">
                          <TrashIcon class="h-4 w-4" />
                      </button>
                  </div>
              </td>

                </tr>
            </tbody>
            </table>
        </div>
        </div>
        <div class="flex flex-col items-center border-t bg-white px-5 py-5 xs:flex-row xs:justify-between">
            <pagination :links="visits.links" />
        </div>

  
      <Modal :show="create_visit">
        <div class="p-6">
          <h2 class="text-base font-medium leading-7 text-gray-900">
            Crear Visita
          </h2>
          <form @submit.prevent="submit">
            <div class="space-y-12">
              <div class="border-b border-gray-900/10 pb-12">

                <div>
                  <InputLabel for="customer" class="mt-2 font-medium leading-6 text-gray-900">Cliente</InputLabel>
                  <div class="mt-2">
                    <input type="text" v-model="form.customer" id="customer"
                      class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                    <InputError :message="form.errors.customer" />
                  </div>
                </div>

                <div>
                  <InputLabel for="phone" class="mt-2 font-medium leading-6 text-gray-900">Teléfono</InputLabel>
                  <div class="mt-2">
                    <input type="text" v-model="form.phone" id="phone" maxlength="9"
                      class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                    <InputError :message="form.errors.phone" />
                  </div>
                </div>

                <div>
                  <InputLabel for="description" class="mt-2 font-medium leading-6 text-gray-900">Descripción</InputLabel>
                  <div class="mt-2">
                    <input type="text" v-model="form.description" id="description"
                      class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                    <InputError :message="form.errors.description" />
                  </div>
                </div>

                <div>
                  <InputLabel for="address" class="mt-2 font-medium leading-6 text-gray-900">Dirección</InputLabel>
                  <div class="mt-2">
                    <input type="text" v-model="form.address" id="address"
                      class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                    <InputError :message="form.errors.address" />
                  </div>
                </div>

                <div>
                  <InputLabel for="date" class="mt-2 font-medium leading-6 text-gray-900">Fecha de Visita</InputLabel>
                  <div class="mt-2">
                    <input type="date" v-model="form.date" id="date"
                      class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                    <InputError :message="form.errors.date" />
                  </div>
                </div>

                <div>
                  <InputLabel for="observation" class="mt-2 font-medium leading-6 text-gray-900">Observaciones</InputLabel>
                  <div class="mt-2">
                    <textarea type="text" v-model="form.observation" id="observation"
                      class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                    <InputError :message="form.errors.observation" />
                  </div>
                </div>

                <div>
                  <InputLabel for="facade" class="mt-2 font-medium leading-6 text-gray-900">Foto de Fachada</InputLabel>
                  <div class="mt-2">
                    <InputFile type="file" v-model="form.facade" id="facade"
                      class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                    <InputError :message="form.errors.facade" />
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

      <teleport to="body">
        <div v-if="isPreviewDocumentModalOpen" class="fixed inset-0 z-50 flex items-center justify-center">
            <div class="absolute inset-0 bg-gray-800 opacity-75" @click="closePreviewDocumentModal"></div>
            <div class="flex items-center justify-center h-full w-3/4">
            <div class="bg-white p-5 rounded-md relative w-full h-3/5" >
                <button @click="closePreviewDocumentModal"
                class="close-button absolute top-0 right-0 mt-2 mr-2">&#10006;</button>
                <iframe :src="getDocumentUrl(documentToShow)" class="w-full h-full"></iframe>
            </div>
            </div>
        </div>
       </teleport>
  
      <ConfirmDeleteModal :confirmingDeletion="confirmingDocDeletion" itemType="Visita" :deleteFunction="deleteDocument"
        @closeModal="closeModalDoc" />
      <ConfirmCreateModal :confirmingcreation="showModal" itemType="Visita" />
    </AuthenticatedLayout>
  </template>
    
  <script setup>
  import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
  import ConfirmCreateModal from '@/Components/ConfirmCreateModal.vue';
  import ConfirmDeleteModal from '@/Components/ConfirmDeleteModal.vue';
  import SecondaryButton from '@/Components/SecondaryButton.vue';
  import InputError from '@/Components/InputError.vue';
  import InputLabel from '@/Components/InputLabel.vue';
  import Pagination from '@/Components/Pagination.vue'
  import InputFile from '@/Components/InputFile.vue';
  import Modal from '@/Components/Modal.vue';
  import { ref } from 'vue';
  import { Head, useForm, router } from '@inertiajs/vue3';
  import { TrashIcon, EyeIcon } from '@heroicons/vue/24/outline';
  
  const { visits } = defineProps({
    visits: Object,
  });
  
  const form = useForm({
    customer: '',
    phone: '',
    description: '',
    address: '',
    date: '',
    observation: '',
    facade: null,
  });
  
  const create_visit = ref(false);
  const showModal = ref(false);
  const confirmingDocDeletion = ref(false);
  const docToDelete = ref(null);
  const documentToShow = ref(null);
  const isPreviewDocumentModalOpen = ref(false);
  
  const closePreviewDocumentModal = () => {
    isPreviewDocumentModalOpen.value = false;
  };

  function openPreviewDocumentModal(documentId) {
    documentToShow.value = documentId;
    isPreviewDocumentModalOpen.value = true;
  }

  function getDocumentUrl(documentId) {
    return route('preprojects.previewVisit', { customer_visit: documentId});
  }

  const openCreateVisitModal = () => {
    create_visit.value = true;
  };
  
  const closeModal = () => {
    create_visit.value = false;
  };
  
  const submit = () => {
    console.log(form);
    form.post(route('preprojects.storeVisit'), {
      onSuccess: () => {
        closeModal();
        form.reset();
        showModal.value = true
        setTimeout(() => {
          showModal.value = false;
          router.visit(route('preprojects.visits'))
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
      router.delete(route('preprojects.destroyVisit', { customer_visit: docId }), {
        onSuccess: () => closeModalDoc()
      });
    }
  };

  </script>
  
    