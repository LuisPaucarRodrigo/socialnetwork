<template>

    <Head title="Gestion de Documentos" />
    <AuthenticatedLayout :redirectRoute="{route: 'archives.show', params: {folder: props.folder_id}}">
      <template #header>
        Observaciones del Archivo
      </template>
      <div class="flex gap-4 justify-between rounded-lg shadow">
        <div class="flex flex-col sm:flex-row gap-4 justify-between w-full">
            <div v-if="props.canObservate" class="flex gap-4 items-center">
            <PrimaryButton @click="openCreateObservationModal" type="button"
                class="block rounded-md bg-indigo-600 px-4 py-2 text-center text-sm text-white hover:bg-indigo-500">
                + Agregar Observación
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
                    <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                        Usuario Propietario
                    </th>
                    <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                        Usuario Evaluador
                    </th>
                    <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                        Observación
                    </th>
                    <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                        Resultado de Evaluación
                    </th>
                    <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                        Fecha de Evaluación
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="archiveUser in archiveUsers.data" :key="archive.id" class="text-gray-700">
                    <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                        <p class="text-gray-900 whitespace-no-wrap">{{ getDocumentName(archiveUser.archive.name) }}</p>
                    </td>
                    <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">
                        <p class="text-gray-900 whitespace-no-wrap">{{ archiveUser.archive.user.name }}</p>
                    </td>
                    <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">
                        <p class="text-gray-900 whitespace-no-wrap">{{ archiveUser.user.name }}</p>
                    </td>
                    <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">
                        <p class="text-gray-900 whitespace-no-wrap">{{ archiveUser.observation }}</p>
                    </td>
                    <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">
                        <p class="text-gray-900 whitespace-no-wrap">{{ archiveUser.state }}</p>
                    </td>
                    <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">
                        <p class="text-gray-900 whitespace-no-wrap">{{ formattedDate(archiveUser.evaluation_date) }}</p>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="flex flex-col items-center border-t bg-white px-5 py-5 xs:flex-row xs:justify-between">
        <pagination :links="props.archiveUsers.links" />
    </div>


    <Modal :show="create_observation">
        <div class="p-6">
            <h2 class="text-base font-medium leading-7 text-gray-900">
                Subir archivo
            </h2>
            <form @submit.prevent="submit">
                <div class="border-b border-gray-900/10 pb-12">
                    <div class="mt-4">
                        <InputLabel for="state">Resultado de Evaluación</InputLabel>
                        <div class="mt-2">
                            <select v-model="form.state" id="state" class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                <option disabled value=''>Seleccione una opción</option>
                                <option value="Observado">Observado</option>
                                <option value="Desestimado">Desestimado</option>
                                <option value="Aprobado">Aprobado</option>
                            </select>
                            <InputError :message="form.errors.state" />
                        </div>
                    </div>
                    <div class="mt-4">
                        <InputLabel for="observations">Observaciones</InputLabel>
                        <div class="mt-2">
                            <textarea v-model="form.observations" id="observations" rows="4" class="block w-full mt-1 border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm rounded-md"></textarea>
                            <InputError :message="form.errors.observations" />
                        </div>
                    </div>
                    <div class="mt-6 flex items-center justify-end gap-x-6">
                        <SecondaryButton @click="closeModal">Cancelar</SecondaryButton>
                        <PrimaryButton type="submit" :class="{ 'opacity-25': form.processing }">
                            Guardar
                        </PrimaryButton>
                    </div>
                </div>
            </form>
        </div>
    </Modal>


      <ConfirmCreateModal :confirmingcreation="showModal" itemType="Observación" />  
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
  import { formattedDate } from '@/utils/utils.js';
  
  const props = defineProps({
    archive: Object,
    archiveUsers: Object,
    auth: Object,
    folder_id: String,
    canObservate: Boolean,
    userPermissions: Array,
  });

  const hasPermission = (permission) => {
    return props.userPermissions.includes(permission);
  }
  
  const form = useForm({
    state: '',
    observations: '',
    user_id: props.auth.user.id
  });

  const create_observation = ref(false);
  const showModal = ref(false);

  const openCreateObservationModal = () => {
    create_observation.value = true;
  };
  
  const closeModal = () => {
    form.reset()
    create_observation.value = false;
  };
 
  const submit = () => {
    form.post(route('archives.observations.save', {archive: props.archive.id}), {
      onSuccess: () => {
        closeModal();
        form.reset();
        showModal.value = true
        setTimeout(() => {
            showModal.value = false;
          router.visit(route('archives.observations', {folder: props.folder_id, archive: props.archive.id}))
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

  const getDocumentName = (documentTitle) => {
    const parts = documentTitle.split('-');
    return parts.length > 1 ? parts.slice(0, -1).join('-') : documentTitle;
  };


  </script>