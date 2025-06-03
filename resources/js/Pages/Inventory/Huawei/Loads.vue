<template>

  <Head title="Datos de Cargas" />
  <AuthenticatedLayout :redirectRoute="'huawei.loads'">
    <template #header>
      Datos de Cargas
    </template>
    <div class="min-w-full rounded-lg shadow">
      <div class="mt-6 flex items-center">
        <PrimaryButton @click="openImportModal" type="button" class="flex-shrink-0">
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
            <tr v-for="item in loads.data" :key="item.id" class="text-gray-700">
              <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">{{ item.name }}</td>
              <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">{{ formattedDate(item.created_at) }}</td>
              <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                <div class="flex items-center">
                  <Link :href="route('huawei.loads.products', { loadId: item.id })">
                  <ShowIcon />
                  </Link>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="flex flex-col items-center border-t bg-white px-5 py-5 xs:flex-row xs:justify-between">
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
                  <input type="file" @change="handleFileUpload"
                    class="block w-full py-1.5 rounded-md sm:text-sm form-input focus:border-indigo-600"
                    accept=".xls, .xlsx" />
                  <InputError :message="formUpload.errors.file" />
                </div>
              </div>
              <div>
                <InputLabel for="anexe2" class="text-gray-700 mt-3">Zonas</InputLabel>
                <select v-model="formUpload.zone" id="anexe2" class="border rounded-md px-3 py-2 mb-3 w-full mt-3">
                  <option value="">Seleccionar Zona</option>
                  <option value="B1">B1</option>
                  <option value="B2">B2</option>
                  <option value="B3">B3</option>
                  <option value="B4">B4</option>
                </select>
                <InputError :message="formUpload.errors.zone" />
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
import { useForm, router, Link, Head } from '@inertiajs/vue3';
import Pagination from '@/Components/Pagination.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { formattedDate } from '@/utils/utils'
import Modal from '@/Components/Modal.vue';
import InputLabel from '@/Components/InputLabel.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ConfirmCreateModal from '@/Components/ConfirmCreateModal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import InputError from '@/Components/InputError.vue';
import ShowIcon from '@/Components/Icons/ShowIcon.vue';

const props = defineProps({
  loads: Object,
  userPermissions: Array,
});

const importExcelModal = ref(false);
const showModal = ref(false);

const formUpload = useForm({
  file: null,
  zone: ''
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
