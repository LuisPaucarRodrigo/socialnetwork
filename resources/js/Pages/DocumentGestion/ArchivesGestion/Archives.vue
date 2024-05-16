<template>

    <Head title="Gestion de Documentos" />
    <AuthenticatedLayout :redirectRoute="'documents.index'">
      <template #header>
        {{ props.folder.path }}
      </template>
      <div class="flex gap-4 justify-between rounded-lg shadow">
        <div class="flex flex-col sm:flex-row gap-4 justify-between w-full">
            <div class="flex gap-4 items-center">
            <PrimaryButton @click="openCreateDocumentModal" type="button"
                class="block rounded-md bg-indigo-600 px-4 py-2 text-center text-sm text-white hover:bg-indigo-500">
                + Agregar Archivo
            </PrimaryButton>
            </div>
        </div> 
      </div>

  
      <div class="overflow-x-auto rounded-lg shadow mt-2">
        <table class="w-full whitespace-no-wrap">
            <thead>
                <tr class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                    <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                        Nombre del Archivo
                    </th>
                    <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                        Usuario
                    </th>
                    <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                        Tamaño
                    </th>
                    <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                        Versión
                    </th>
                    <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                        Permisos
                    </th>
                    <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                        Acciones
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="archive in archives.data" :key="archive.id" class="text-gray-700">
                    <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                        <p class="text-gray-900 whitespace-no-wrap">{{ getDocumentName(archive.name) }}</p>
                    </td>
                    <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">
                        <p class="text-gray-900 whitespace-no-wrap">{{ archive.user.name }}</p>
                    </td>
                    <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">
                        <p class="text-gray-900 whitespace-no-wrap">{{ archive.size }} kB</p>
                    </td>
                    <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">
                        <p class="text-gray-900 whitespace-no-wrap">{{ archive.version }}</p>
                    </td>
                    <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm justify-center">
                        <button v-if="props.auth.user.role_id === 1 || props.auth.user.role_id === archive.user_id" @click="openPermissionModal(archive.id, archive.users_active.map((item)=>item.id))" class="text-blue-600 underline">Administrar</button>
                        <button v-if="props.auth.user.role_id === 1 || archive.users_active.some(user => user.id === props.auth.user.id)" @click="" class="text-blue-600 underline">Observaciones</button>
                    </td>
                    <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                        <div class="flex space-x-3 item-center">
                            <button @click="downloadDocument(archive.id)" class="flex items-center text-blue-600 hover:underline">
                                <ArrowDownIcon class="h-4 w-4 ml-1" />
                            </button>
                            <button v-if="hasPermission('UserManager')" @click="confirmDeleteDocument(archive.id)"
                                class="flex items-center text-red-600 hover:underline">
                                <TrashIcon class="h-4 w-4" />
                            </button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="flex flex-col items-center border-t bg-white px-5 py-5 xs:flex-row xs:justify-between">
        <pagination :links="props.archives.links" />
    </div>


      <Modal :show="create_document">
        <div class="p-6">
          <h2 class="text-base font-medium leading-7 text-gray-900">
            Subir archivo
          </h2>
          <form @submit.prevent="submit">
            <div class="border-b border-gray-900/10 pb-12">
              <div class="mt-2">
                <InputLabel for="documentFile">Archivo</InputLabel>
                <div class="mt-2">
                    <InputFile type="file" v-model="form.archive" id="documentFile" :accept="'.' + $props.folder.archive_type" />
                    <InputError :message="form.errors.archive" />
                </div>
              </div>
              <div class="mt-6 flex items-center justify-end gap-x-6">
                <SecondaryButton @click="closeModal"> Cancelar </SecondaryButton>
                <PrimaryButton type="submit" :class="{ 'opacity-25': form.processing }">
                  Guardar
                </PrimaryButton>
              </div>
            </div>
          </form>
        </div>
      </Modal>

      <Modal :show="permissionModal">
        <div class="p-6">
          <h2 class="text-base font-medium leading-7 text-gray-900">
            Seleccione los usuarios
          </h2>
          <div class="inline-flex items-center p-2 mb-4  mt-4 text-sm text-yellow-800 rounded-lg bg-yellow-50 dark:bg-gray-800 dark:text-yellow-300" role="alert">
                <svg class="flex-shrink-0 inline w-4 h-4 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
                </svg>
                <span class="sr-only">Info</span>
                <div>
                    <span class="font-small">Los usuarios seleccionados podrán aprobar, desestimar y agregar observaciones a los archivos.</span>
                </div>
            </div>
        <form @submit.prevent="submitUsers">
            <div class="border-b border-gray-900/10 pb-12">
                <div class="mt-2">
                    <InputLabel for="userSelect">Usuarios</InputLabel>
                    <div class="mt-2">
                        <select multiple v-model="formUsers.users" id="userSelect" class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 border-gray-300 rounded-md">
                            <option v-for="user in $props.users" :value="user.id">{{ user.name }}</option>
                        </select>
                        <InputError :message="form.errors.users" />
                    </div>
                </div>
              <div class="mt-6 flex items-center justify-end gap-x-6">
                <SecondaryButton @click="closePermissionModal"> Cancelar </SecondaryButton>
                <PrimaryButton type="submit" :class="{ 'opacity-25': form.processing }">
                  Guardar
                </PrimaryButton>
              </div>
            </div>
          </form>
        </div>
      </Modal>
  
      <ConfirmDeleteModal :confirmingDeletion="confirmingDocDeletion" itemType="Archivo"
        :deleteFunction="deleteDocument" @closeModal="closeModalDoc" />
      <ConfirmCreateModal :confirmingcreation="showModal" itemType="Archivo" />  
      <ConfirmCreateModal :confirmingcreation="showAssignModal" itemType="Asignación" />  
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
  import Pagination from '@/Components/Pagination.vue';
  import PrimaryButton from '@/Components/PrimaryButton.vue';
  import TextInput from '@/Components/TextInput.vue';
  import Modal from '@/Components/Modal.vue';
  import { ref, computed, watch } from 'vue';
  import { Head, useForm, router } from '@inertiajs/vue3';
  import { TrashIcon, ArrowDownIcon } from '@heroicons/vue/24/outline';
  
  
  const props = defineProps({
    archives: Object,
    folder: Object,
    auth: Object,
    userPermissions: Array,
    users: Object
  });

  
  const hasPermission = (permission) => {
    return props.userPermissions.includes(permission);
  }
  
  const form = useForm({
    archive: null,
    folder_id: props.folder.id,
    user_id: props.auth.user.id,
  });

  const formUsers = useForm({
    archive_id: null,
    users: []
  });
  
  const create_document = ref(false);
  const showModal = ref(false);
  const showAssignModal = ref(false);
  const confirmingDocDeletion = ref(false);
  const docToDelete = ref(null);
  const permissionModal = ref(false);
  const openCreateDocumentModal = () => {
    create_document.value = true;
  };
  
  const closeModal = () => {
    create_document.value = false;
  };

  const openPermissionModal = (id, users) => {
    formUsers.archive_id = id;
    formUsers.users = users;
    permissionModal.value = true;
  }

  const closePermissionModal = () => {
    formUsers.reset();
    permissionModal.value = false;
  }
    
  const submit = () => {
    form.post(route('archives.post', {folder: props.folder.id}), {
      onSuccess: () => {
        closeModal();
        form.reset();
        showModal.value = true
        setTimeout(() => {
            showModal.value = false;
          router.visit(route('archives.show', {folder: props.folder.id}))
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

  const submitUsers = () => {
    formUsers.post(route('archives.assign.users', {folder: props.folder.id, archive: formUsers.archive_id}), {
      onSuccess: () => {
        closePermissionModal();
        formUsers.reset();
        showAssignModal.value = true
        setTimeout(() => {
            showAssignModal.value = false;
          router.visit(route('archives.show', {folder: props.folder.id}))
        }, 2000);
      },
      onError: () => {
        formUsers.reset();
      },
      onFinish: () => {
        formUsers.reset();
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
      router.delete(route('archives.destroy', { folder: props.folder.id, archive: docId }), {
        onSuccess: () => closeModalDoc()
      });
    }
  };
  
  function downloadDocument(documentId) {
    const backendDocumentUrl = route('archives.download', { folder: props.folder.id, archive: documentId });
    window.open(backendDocumentUrl, '_blank');
  };
  
  
  const getDocumentName = (documentTitle) => {
  const parts = documentTitle.split('-');
  return parts.length > 1 ? parts.slice(0, -1).join('-') : documentTitle;
};


  </script>