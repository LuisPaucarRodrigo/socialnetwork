<template>

    <Head title="Datos de Huawei" />
    <AuthenticatedLayout>
      <template #header>
        Datos de Huawei
      </template>
      <div class="min-w-full rounded-lg shadow">
        <div class="mt-6 flex items-center justify-between gap-x-6">
          <div class="hidden sm:flex sm:items-center sm:space-x-4">
            <PrimaryButton v-if="hasPermission('UserManager')" @click="openImportModal" type="button">
              Importar Excel
            </PrimaryButton>
          </div>
        </div>

        <div class="overflow-x-auto mt-3">
          <table class="w-full whitespace-no-wrap">
            <thead>
              <tr class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                <th
                  class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                  N°</th>
                <th
                  class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                  Fecha</th>
                <th
                  class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                  CODSAP</th>
                <th
                  class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                  Descripción</th>
                <th
                  class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                  Serie</th>
                <th
                  class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                  Periodo</th>
                <th
                  class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                  Contrata</th>
                  <th
                  class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                  OC PAP</th>
                  <th
                  class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                  Sites</th>
                  <th
                  class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                  Estatus General</th>
                  <th
                  class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                  Status</th>
                  <th
                  class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                  Valor Monetario</th>
                  <th
                  class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                  Observación</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(item, index) in huaweiData" :key="index" class="text-gray-700">
                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">{{ index }}</td>
                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">{{ formattedDate(item.date) }}</td>
                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">{{ item.codsap }}</td>
                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">{{ item.description }}</td>
                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">{{ item.serie }}</td>
                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">{{ item.period }}</td>
                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">{{ item.hire }}</td>
                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">{{ item.oc_pap }}</td>
                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">{{ item.sites }}</td>
                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">{{ item.general_status }}</td>
                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">{{ item.status }}</td>
                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">{{ item.monetary_value }}</td>
                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">{{ item.observation }}</td>
                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                  <div class="flex items-center">
                    <button v-if="hasPermission('UserManager')" @click="openEditSubSectionModal(item)"
                      class="text-orange-200 hover:underline mr-2">
                      <PencilIcon class="h-4 w-4 ml-1" />
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <div
             class="flex flex-col items-center border-t bg-white px-5 py-5 xs:flex-row xs:justify-between">
              <pagination :links="props.huaweiData.links" />
          </div>
      </div>
      <Modal :show="importExcelModal">
        <div class="p-6">
            <h2 class="text-base font-medium leading-7 text-gray-900">
                Subir Archivo
            </h2>
            <form @submit.prevent="submit">
                <div class="space-y-12">
                <div class="border-b border-gray-900/10 pb-12">

                    <div class="mt-2">
                        <InputLabel for="file">Archivo Excel</InputLabel>
                        <div class="mt-2">
                            <input type="file" @change="handleFileUpload" id="file" />
                        </div>
                    </div>

                    <div class="mt-6 flex items-center justify-end gap-x-6">
                    <SecondaryButton @click="closeModal">Cancelar</SecondaryButton>
                    <PrimaryButton type="submit" :class="{ 'opacity-25': form.processing }">
                        Guardar
                    </PrimaryButton>
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
  import { useForm } from '@inertiajs/vue3';
  import Pagination from '@/Components/Pagination.vue';
  import TextInput from '@/Components/TextInput.vue';
  import PrimaryButton from '@/Components/PrimaryButton.vue';
  import { formattedDate } from '@/utils/utils'
  import { PencilIcon, EyeIcon } from '@heroicons/vue/24/outline';
  import Modal from '@/Components/Modal.vue';
  import InputError from '@/Components/InputError.vue';
  import InputLabel from '@/Components/InputLabel.vue';
  import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

  const props = defineProps({
    huaweiData: Object,
    project_id: Number,
    userPermissions: Array
  });

  const importExcelModal = ref(false);
  const file = ref(null);
  const showModal = ref(false);
  const excelData = ref({});
  const form = useForm({
    name: '',
    file: null,
    statusData: null,
  });

  const hasPermission = (permission) => {
  return props.userPermissions.includes(permission);
}

const closeModal = () => {
  form.reset();
  importExcelModal.value = false;
};

const handleFileUpload = (event) => {
  file.value = event.target.files[0];
  const reader = new FileReader();

  reader.onload = (e) => {
    const data = new Uint8Array(e.target.result);
    const workbook = XLSX.read(data, { type: 'array' });

    const sheetNames = workbook.SheetNames;

    // Recorrer todas las hojas del libro de Excel
    sheetNames.forEach(sheetName => {
      const worksheet = workbook.Sheets[sheetName];
      // Convertir la hoja de cálculo a JSON y asignarla a la variable correspondiente
      excelData.value[sheetName] = convertSheetToJson(worksheet);
    });

    // Asignar los datos leídos al formulario
    form.statusData = excelData.value['STATUS'];
  };

  reader.readAsArrayBuffer(file.value);
};

const convertSheetToJson = (worksheet) => {
  const jsonData = XLSX.utils.sheet_to_json(worksheet, { header: 1 });
  // Convertir la segunda columna en fecha si corresponde
  const formattedData = jsonData.map(row => {
    if (row.length >= 2 && typeof row[1] === 'number') {
      row[1] = parseExcelDate(row[1]);
    }
    return row;
  });
  return formattedData;
};

const parseExcelDate = (excelDate) => {
  if (excelDate === null) return null; // Retornar null si la fecha en Excel es null

  const jsDate = new Date((excelDate - (25567 + 2)) * 86400 * 1000); // Convertir número Excel a timestamp UNIX
  const formattedDate = jsDate.toISOString().substr(0, 10); // Formatear la fecha como "YYYY-MM-DD"
  return formattedDate;
};

const submit = () => {
    form.post(route('huawei.post', {project: props.project_id}), {
    onSuccess: () => {
      closeModal();
      showModal.value = true
      setTimeout(() => {
        showModal.value = false;
        router.visit(route('huawei.show', {project: props.project_id}))
      }, 2000);
    },
  });
};

  </script>
