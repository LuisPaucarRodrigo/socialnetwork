<template>

    <Head title="Datos de Cargas" />
    <AuthenticatedLayout>
      <template #header>
        Datos de Cargas
      </template>
      <div class="min-w-full rounded-lg shadow">
        <div class="mt-6 flex items-center">
        <PrimaryButton v-if="hasPermission('UserManager')" @click="openImportModal" type="button" class="flex-shrink-0">
            Importar Excel
        </PrimaryButton>
    </div>


        <div class="overflow-x-auto mt-3">
          <table class="w-full whitespace-no-wrap">
            <thead>
              <tr class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                <th
                  class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                  Nombre de Carga</th>
                <th
                  class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                  Fecha de Carga</th>
                  <th
                  class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                  </th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(item, index) in loads.data" :key="index" class="text-gray-700">
                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">{{ item.name }}</td>
                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">{{ formattedDate(item.created_at) }}</td>
                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                  <div class="flex items-center">
                    <button v-if="hasPermission('UserManager')" @click="openEditModal(item)"
                      class="text-green-600 hover:underline mr-2">
                      <EyeIcon class="h-5 w-5" />
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <div
             class="flex flex-col items-center border-t bg-white px-5 py-5 xs:flex-row xs:justify-between">
              <pagination :links="props.loads.links" />
          </div>
      </div>
      <Modal :show="importExcelModal">
            <div class="p-6">
                <h2 class="text-base font-medium leading-7 text-gray-900">Subir Archivo</h2>
                <form @submit.prevent="submit">
                    <div class="space-y-12">
                        <div class="border-b border-gray-900/10 pb-12">
                            <div class="mt-2">
                                <InputLabel for="file">Archivo Excel</InputLabel>
                                <div class="mt-2">
                                    <input type="file" @change="handleFileUpload" class="block w-full py-1.5 rounded-md sm:text-sm form-input focus:border-indigo-600" accept=".xls, .xlsx" />
                                    <InputError :message="formUpload.errors.file" />
                                </div>
                            </div>
                            <div class="mt-6 flex items-center justify-end gap-x-6">
                                <SecondaryButton @click="closeModal">Cancelar</SecondaryButton>
                                <PrimaryButton type="submit" :class="{ 'opacity-25': formUpload.processing }">Guardar</PrimaryButton>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </Modal>

      <ConfirmCreateModal :confirmingcreation="showModal" itemType="Archivo" />
    </AuthenticatedLayout>
  </template>

  <script setup>
  import { ref } from 'vue';
  import * as XLSX from 'xlsx';
  import { useForm, router, Link, Head } from '@inertiajs/vue3';
  import Pagination from '@/Components/Pagination.vue';
  import PrimaryButton from '@/Components/PrimaryButton.vue';
  import { formattedDate } from '@/utils/utils'
  import { PencilSquareIcon, EyeIcon } from '@heroicons/vue/24/outline';
  import Modal from '@/Components/Modal.vue';
  import InputLabel from '@/Components/InputLabel.vue';
  import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
  import ConfirmCreateModal from '@/Components/ConfirmCreateModal.vue';
  import SecondaryButton from '@/Components/SecondaryButton.vue';
  import InputFile from '@/Components/InputFile.vue';
  import TextInput from '@/Components/TextInput.vue';
  import InputError from '@/Components/InputError.vue';

  const props = defineProps({
    loads: Object,
    userPermissions: Array,
  });

  const importExcelModal = ref(false);
  const showModal = ref(false);

  const formUpload = useForm({
    file: null,
  });

  const hasPermission = (permission) => {
    return props.userPermissions.includes(permission);
  }

const openImportModal = () => {
    importExcelModal.value = true;
}

const closeModal = () => {
  formUpload.reset();
  importExcelModal.value = false;
};



const handleFileUpload = (event) => {
    const file = event.target.files[0];
    formUpload.file = file;
};

const submit = async () => {
    const formData = new FormData();
    formData.append('file', formUpload.file);

    await formUpload.post(route('huawei.loads.import'), {
        data: formData,
        onSuccess: () => {
            closeModal();
            showModal.value = true;
            setTimeout(() => {
                showModal.value = false;
                router.visit(route('huawei.loads'));
            }, 2000);
        },
    });
};


  </script>
