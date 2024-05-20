<template>

    <Head title="Gestion de Documentos" />
    <AuthenticatedLayout  :redirectRoute="{ route: 'documment.management.folders', params: { folder_id: folder?.upper_folder_id } }">
      <template #header>
        {{ props.folder.path }}
      </template>
      <div class="flex gap-4 justify-between rounded-lg shadow">
        <div class="flex flex-col sm:flex-row gap-4 justify-between w-full">
            <div v-if="props.folder.availability == 1">
              <div v-if="userHasPermission" class="flex gap-4 items-center">
                <PrimaryButton @click="openCreateDocumentModal" type="button"
                    class="block rounded-md bg-indigo-600 px-4 py-2 text-center text-sm text-white hover:bg-indigo-500">
                    + Agregar Archivo
                </PrimaryButton>
              </div>
            </div>
            <div v-if="props.folder.availability == 3">
              <div v-if="userHasPermission && props.folder.last_owner == props.auth.user.id" class="flex gap-4 items-center">
                <PrimaryButton @click="openCreateDocumentModal" type="button"
                    class="block rounded-md bg-indigo-600 px-4 py-2 text-center text-sm text-white hover:bg-indigo-500">
                    + Agregar Archivo
                </PrimaryButton>
              </div>
            </div>
        </div> 
      </div>

  
      <div class="overflow-x-auto rounded-lg shadow mt-2">
        <table class="w-full whitespace-no-wrap">
            <thead>
                <tr class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                  <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                        
                    </th>  
                  <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                        Nombre del Archivo
                    </th>
                    <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                        Usuario
                    </th>
                    <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                        Tamaño
                    </th>
                    <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                        Versión
                    </th>
                    <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                        Permisos
                    </th>
                    <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                        Acciones
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="archive in archives.data" :key="archive.id" class="text-gray-700">
                  <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm justify-center relative">
                      <img v-if="archive.type == 'stable'" src="/image/projectimage/stable.png" alt="" class="absolute top-0 left-0 w-10 h-10">

                      <svg v-if="['doc', 'docx'].includes(props.folder.archive_type)" height="30px" width="30px" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                          viewBox="0 0 512 512" xml:space="preserve">
                        <path style="fill:#E2E5E7;" d="M128,0c-17.6,0-32,14.4-32,32v448c0,17.6,14.4,32,32,32h320c17.6,0,32-14.4,32-32V128L352,0H128z"/>
                        <path style="fill:#B0B7BD;" d="M384,128h96L352,0v96C352,113.6,366.4,128,384,128z"/>
                        <polygon style="fill:#CAD1D8;" points="480,224 384,128 480,128 "/>
                        <path style="fill:#50BEE8;" d="M416,416c0,8.8-7.2,16-16,16H48c-8.8,0-16-7.2-16-16V256c0-8.8,7.2-16,16-16h352c8.8,0,16,7.2,16,16
                          V416z"/>
                        <g>
                          <path style="fill:#FFFFFF;" d="M92.576,384c-4.224,0-8.832-2.32-8.832-7.936v-72.656c0-4.608,4.608-7.936,8.832-7.936h29.296
                            c58.464,0,57.168,88.528,1.136,88.528H92.576z M100.64,311.072v57.312h21.232c34.544,0,36.064-57.312,0-57.312H100.64z"/>
                          <path style="fill:#FFFFFF;" d="M228,385.28c-23.664,1.024-48.24-14.72-48.24-46.064c0-31.472,24.56-46.944,48.24-46.944
                            c22.384,1.136,45.792,16.624,45.792,46.944C273.792,369.552,250.384,385.28,228,385.28z M226.592,308.912
                            c-14.336,0-29.936,10.112-29.936,30.32c0,20.096,15.616,30.336,29.936,30.336c14.72,0,30.448-10.24,30.448-30.336
                            C257.04,319.008,241.312,308.912,226.592,308.912z"/>
                          <path style="fill:#FFFFFF;" d="M288.848,339.088c0-24.688,15.488-45.92,44.912-45.92c11.136,0,19.968,3.328,29.296,11.392
                            c3.456,3.184,3.84,8.816,0.384,12.4c-3.456,3.056-8.704,2.688-11.776-0.384c-5.232-5.504-10.608-7.024-17.904-7.024
                            c-19.696,0-29.152,13.952-29.152,29.552c0,15.872,9.328,30.448,29.152,30.448c7.296,0,14.08-2.96,19.968-8.192
                            c3.952-3.072,9.456-1.552,11.76,1.536c2.048,2.816,3.056,7.552-1.408,12.016c-8.96,8.336-19.696,10-30.336,10
                            C302.8,384.912,288.848,363.776,288.848,339.088z"/>
                        </g>
                        <path style="fill:#CAD1D8;" d="M400,432H96v16h304c8.8,0,16-7.2,16-16v-16C416,424.8,408.8,432,400,432z"/>
                      </svg>
                      <svg v-if="props.folder.archive_type == 'pdf'" height="800px" width="800px" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" 
                           viewBox="0 0 512 512" xml:space="preserve">
                           <path style="fill:#E2E5E7;" d="M128,0c-17.6,0-32,14.4-32,32v448c0,17.6,14.4,32,32,32h320c17.6,0,32-14.4,32-32V128L352,0H128z"/>
                          <path style="fill:#B0B7BD;" d="M384,128h96L352,0v96C352,113.6,366.4,128,384,128z"/>
                          <polygon style="fill:#CAD1D8;" points="480,224 384,128 480,128 "/>
                          <path style="fill:#F15642;" d="M416,416c0,8.8-7.2,16-16,16H48c-8.8,0-16-7.2-16-16V256c0-8.8,7.2-16,16-16h352c8.8,0,16,7.2,16,16
                            V416z"/>
                          <g>
                            <path style="fill:#FFFFFF;" d="M101.744,303.152c0-4.224,3.328-8.832,8.688-8.832h29.552c16.64,0,31.616,11.136,31.616,32.48
                              c0,20.224-14.976,31.488-31.616,31.488h-21.36v16.896c0,5.632-3.584,8.816-8.192,8.816c-4.224,0-8.688-3.184-8.688-8.816V303.152z
                              M118.624,310.432v31.872h21.36c8.576,0,15.36-7.568,15.36-15.504c0-8.944-6.784-16.368-15.36-16.368H118.624z"/>
                            <path style="fill:#FFFFFF;" d="M196.656,384c-4.224,0-8.832-2.304-8.832-7.92v-72.672c0-4.592,4.608-7.936,8.832-7.936h29.296
                              c58.464,0,57.184,88.528,1.152,88.528H196.656z M204.72,311.088V368.4h21.232c34.544,0,36.08-57.312,0-57.312H204.72z"/>
                            <path style="fill:#FFFFFF;" d="M303.872,312.112v20.336h32.624c4.608,0,9.216,4.608,9.216,9.072c0,4.224-4.608,7.68-9.216,7.68
                              h-32.624v26.864c0,4.48-3.184,7.92-7.664,7.92c-5.632,0-9.072-3.44-9.072-7.92v-72.672c0-4.592,3.456-7.936,9.072-7.936h44.912
                              c5.632,0,8.96,3.344,8.96,7.936c0,4.096-3.328,8.704-8.96,8.704h-37.248V312.112z"/>
                          </g>
                          <path style="fill:#CAD1D8;" d="M400,432H96v16h304c8.8,0,16-7.2,16-16v-16C416,424.8,408.8,432,400,432z"/>
                        </svg>
                    </td>  
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
                    <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">
                        <div v-if="archive.type !== 'stable'" class="flex flex-col items-center space-y-2">
                            <button v-if="props.auth.user.role_id === 1 || props.auth.user.id === archive.user_id" @click="openPermissionModal(archive.id, archive.users_active.map((item) => item.id), archive.users_available)" class="text-blue-600 underline">
                                Administrar
                            </button>
                              <Link v-if="props.auth.user.role_id === 1 || archive.users_active.some(user => user.id === props.auth.user.id)" :href="route('archives.observations', {folder: props.folder.id, archive: archive.id})" class="text-blue-600 underline">
                                Observaciones
                              </Link>
                        </div>
                        <div v-else>
                          <p class="text-gray-900 whitespace-no-wrap">No permitido</p>
                        </div>
                    </td>

                    <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">
                        <div class="flex justify-center items-center space-x-3">
                            <button @click="downloadDocument(archive.id)" class="flex items-center text-blue-600 hover:underline">
                                <ArrowDownIcon class="h-4 w-4 ml-1" />
                            </button>
                            <button v-if="archive.disponibility && hasPermission('UserManager') && archive.approve_status" @click="openUpgradeModal(archive.id)" class="flex items-center text-green-600 hover:underline">
                              <BarsArrowUpIcon class="w-5" />
                            </button>
                            <button v-if="hasPermission('UserManager')" @click="confirmDeleteDocument(archive.id)" class="flex items-center text-red-600 hover:underline">
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
                            <option v-for="user in usersAvailable" :value="user.id">{{ user.name }}</option>
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

      <Modal :show="approvating">
            <div class="p-6">
                <h2 class="text-base font-medium leading-7 text-gray-900">
                    ¿Está seguro de mejorar este archivo?
                </h2>
                <p class="mt-1 text-sm text-gray-600">
                  Se creará una copia del archivo con la versión estable, y los futuros archivos se basarán en esta versión.                </p>
                    <div class="space-y-12">
                        <div class="border-gray-900/10">
                            <div class="mt-6 flex items-center justify-end gap-x-6">
                                <SecondaryButton @click="closeUpgradeModal"> Cancelar </SecondaryButton>
                                <button @click="confirmUpgrade(archive_id)" 
                                    class="rounded-md bg-indigo-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Confirmar</button>
                            </div>
                        </div>
                    </div>
            </div>
        </Modal>
  
      <ConfirmDeleteModal :confirmingDeletion="confirmingDocDeletion" itemType="Archivo"
        :deleteFunction="deleteDocument" @closeModal="closeModalDoc" />
      <ConfirmCreateModal :confirmingcreation="showModal" itemType="Archivo" />  
      <ConfirmApproveModal :confirmingapprove="showModalApprove" itemType="Archivo" />  
      <ConfirmCreateModal :confirmingcreation="showAssignModal" itemType="Asignación" />  
    </AuthenticatedLayout>
  </template>
  
  <script setup>
  import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
  import ConfirmCreateModal from '@/Components/ConfirmCreateModal.vue';
  import ConfirmApproveModal from '@/Components/ConfirmApproveModal.vue';
  import ConfirmDeleteModal from '@/Components/ConfirmDeleteModal.vue';
  import SecondaryButton from '@/Components/SecondaryButton.vue';
  import InputError from '@/Components/InputError.vue';
  import InputLabel from '@/Components/InputLabel.vue';
  import InputFile from '@/Components/InputFile.vue';
  import Pagination from '@/Components/Pagination.vue';
  import PrimaryButton from '@/Components/PrimaryButton.vue';
  import Modal from '@/Components/Modal.vue';
  import { ref } from 'vue';
  import { Head, useForm, router, Link } from '@inertiajs/vue3';
  import { TrashIcon, ArrowDownIcon, BarsArrowUpIcon } from '@heroicons/vue/24/outline';
  
  
  const props = defineProps({
    archives: Object,
    folder: Object,
    auth: Object,
    userPermissions: Array,
    userHasPermission: Boolean
  });

const usersAvailable = ref([]);


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
  const approvating = ref(false);
  const archive_id = ref(null);
  const showModal = ref(false);
  const showModalApprove = ref(false);
  const showAssignModal = ref(false);
  const confirmingDocDeletion = ref(false);
  const docToDelete = ref(null);
  const permissionModal = ref(false);
  import axios from 'axios';

  const openUpgradeModal = (id) => {
    archive_id.value = id;
    approvating.value = true;
  }

  const closeUpgradeModal = () => {
    archive_id.value = null;
    approvating.value = false;
  }

  const confirmUpgrade = () => {
    axios.post(route('archives.upgrade', { archive: archive_id.value }))
    .then(response => {
      closeUpgradeModal();
      showModalApprove.value = true;
      setTimeout(() => {
        showModalApprove.value = false;
        router.visit(route('archives.show', {folder: props.folder.id}))
      }, 2000);
    })
    .catch(error => {
      // Manejo de errores si es necesario
      console.error('Error al actualizar el archivo:', error);
    });
  }

  const openCreateDocumentModal = () => {
    create_document.value = true;
  };
  
  const closeModal = () => {
    form.reset()
    create_document.value = false;
  };

  const openPermissionModal = (id, users, availableUsers) => {
    usersAvailable.value = availableUsers;
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