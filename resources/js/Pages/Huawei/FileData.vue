<template>

    <Head title="Datos de Huawei" />
    <AuthenticatedLayout>
      <template #header>
        Datos de Huawei
      </template>
      <div class="min-w-full rounded-lg shadow">
        <div class="mt-6 flex items-center">
        <PrimaryButton v-if="hasPermission('UserManager')" @click="openImportModal" type="button" class="flex-shrink-0">
            Importar Excel
        </PrimaryButton>
        <PrimaryButton @click="openFilterModal" type="button" class="ml-4 flex-shrink-0">
            <svg width="20px" height="20px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M14 20.5H10C9.80189 20.4974 9.61263 20.4176 9.47253 20.2775C9.33244 20.1374 9.25259 19.9481 9.25 19.75V12L3.9 4.69C3.81544 4.58007 3.76395 4.44832 3.75155 4.31018C3.73915 4.17204 3.76636 4.03323 3.83 3.91C3.89375 3.78712 3.98984 3.68399 4.10792 3.61173C4.226 3.53947 4.36157 3.50084 4.5 3.5H19.5C19.6384 3.50084 19.774 3.53947 19.8921 3.61173C20.0101 3.68399 20.1062 3.78712 20.17 3.91C20.2336 4.03323 20.2608 4.17204 20.2484 4.31018C20.236 4.44832 20.1846 4.58007 20.1 4.69L14.75 12V19.75C14.7474 19.9481 14.6676 20.1374 14.5275 20.2775C14.3874 20.4176 14.1981 20.4974 14 20.5ZM10.75 19H13.25V11.75C13.2492 11.5907 13.302 11.4357 13.4 11.31L18 5H6L10.62 11.31C10.718 11.4357 10.7708 11.5907 10.77 11.75L10.75 19Z" fill="white"/>
            </svg>
        </PrimaryButton>
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
                  <th
                  class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                  </th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(item, index) in huaweiData.data" :key="index" class="text-gray-700">
                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">{{ index + 1 }}</td>
                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">{{ item.date ? formattedDate(item.date) : '' }}</td>
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
                    <button v-if="hasPermission('UserManager')" @click="openEditModal(item)"
                      class="text-orange-400 hover:underline mr-2">
                      <PencilSquareIcon class="h-5 w-5" />
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
            <div v-if="props.huaweiData.data.length > 0" class="inline-flex items-center p-2 mb-4  mt-4 text-sm text-yellow-800 rounded-lg bg-yellow-50 dark:bg-gray-800 dark:text-yellow-300" role="alert">
                    <svg class="flex-shrink-0 inline w-4 h-4 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
                    </svg>
                    <span class="sr-only">Info</span>
                        <div>
                    <span class="font-small">Al subir un nuevo excel para este proyecto se reemplazarán los datos actuales, para no perder información puede editar un registro en específico.</span>
                </div>
            </div>
            <form @submit.prevent="submit">
                <div class="space-y-12">
                <div class="border-b border-gray-900/10 pb-12">

                    <div class="mt-2">
                        <InputLabel for="file">Archivo Excel</InputLabel>
                        <div class="mt-2">
                            <input
                                type="file"
                                class="block w-full py-1.5 rounded-md sm:text-sm form-input focus:border-indigo-600"
                                @change="handleFileUpload"
                                accept=".xls, .xlsx"
                            />                   </div>
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

        <Modal :show="filterModal">
        <div class="p-6">
          <h2 class="text-base font-medium leading-7 text-gray-900">
            Filtrar Datos
          </h2>
          <form @submit.prevent="applyFilters">
            <div class="space-y-12">
              <div class="border-b border-gray-900/10 pb-12 grid grid-cols-2 gap-6">
                <!-- Fechas -->
                <div class="col-span-1">
                  <InputLabel for="startDate">Fecha de Inicio</InputLabel>
                  <div class="mt-2">
                    <input type="date" v-model="filterForm.startDate" class="block w-full py-1.5 rounded-md sm:text-sm form-input focus:border-indigo-600" />
                  </div>
                </div>
                <div class="col-span-1">
                  <InputLabel for="endDate">Fecha de Fin</InputLabel>
                  <div class="mt-2">
                    <input type="date" v-model="filterForm.endDate" class="block w-full py-1.5 rounded-md sm:text-sm form-input focus:border-indigo-600" />
                  </div>
                </div>
                <!-- Valor Monetario -->
                <div class="col-span-1">
                  <InputLabel for="minValue">Valor Monetario Mínimo</InputLabel>
                  <div class="mt-2">
                    <input type="number" step="0.01" v-model="filterForm.minValue" class="block w-full py-1.5 rounded-md sm:text-sm form-input focus:border-indigo-600" />
                  </div>
                </div>
                <div class="col-span-1">
                  <InputLabel for="maxValue">Valor Monetario Máximo</InputLabel>
                  <div class="mt-2">
                    <input type="number" step="0.01" v-model="filterForm.maxValue" class="block w-full py-1.5 rounded-md sm:text-sm form-input focus:border-indigo-600" />
                  </div>
                </div>
                <!-- General Status -->
                <div class="col-span-1">
                  <InputLabel for="generalStatus">Estatus General</InputLabel>
                  <div class="mt-2">
                    <select v-model="filterForm.generalStatus" class="block w-full py-1.5 rounded-md sm:text-sm form-input focus:border-indigo-600">
                      <option disabled value="">Selecciona una opción</option>
                      <option value="option1">Opción 1</option>
                      <option value="option2">Opción 2</option>
                      <!-- Agrega más opciones según sea necesario -->
                    </select>
                  </div>
                </div>
                <!-- Status -->
                <div class="col-span-1">
                  <InputLabel for="status">Status</InputLabel>
                  <div class="mt-2">
                    <select v-model="filterForm.status" class="block w-full py-1.5 rounded-md sm:text-sm form-input focus:border-indigo-600">
                      <option disabled value="">Selecciona una opción</option>
                      <option value="option1">Opción 1</option>
                      <option value="option2">Opción 2</option>
                      <!-- Agrega más opciones según sea necesario -->
                    </select>
                  </div>
                </div>
                <!-- Otros campos de texto -->
                <div class="col-span-1">
                  <InputLabel for="codsap">CODSAP</InputLabel>
                  <div class="mt-2">
                    <input type="text" v-model="filterForm.codsap" class="block w-full py-1.5 rounded-md sm:text-sm form-input focus:border-indigo-600" />
                  </div>
                </div>
                <div class="col-span-1">
                  <InputLabel for="serie">Serie</InputLabel>
                  <div class="mt-2">
                    <input type="text" v-model="filterForm.serie" class="block w-full py-1.5 rounded-md sm:text-sm form-input focus:border-indigo-600" />
                  </div>
                </div>
                <div class="col-span-1">
                  <InputLabel for="period">Periodo</InputLabel>
                  <div class="mt-2">
                    <input type="text" v-model="filterForm.period" class="block w-full py-1.5 rounded-md sm:text-sm form-input focus:border-indigo-600" />
                  </div>
                </div>
                <div class="col-span-1">
                  <InputLabel for="hire">Contrata</InputLabel>
                  <div class="mt-2">
                    <input type="text" v-model="filterForm.hire" class="block w-full py-1.5 rounded-md sm:text-sm form-input focus:border-indigo-600" />
                  </div>
                </div>
                <div class="col-span-1">
                  <InputLabel for="ocPap">OC PAP</InputLabel>
                  <div class="mt-2">
                    <input type="text" v-model="filterForm.ocPap" class="block w-full py-1.5 rounded-md sm:text-sm form-input focus:border-indigo-600" />
                  </div>
                </div>
                <div class="col-span-1">
                  <InputLabel for="sites">Sites</InputLabel>
                  <div class="mt-2">
                    <input type="text" v-model="filterForm.sites" class="block w-full py-1.5 rounded-md sm:text-sm form-input focus:border-indigo-600" />
                  </div>
                </div>
                <div class="col-span-2 mt-6 flex items-center justify-end gap-x-6">
                  <SecondaryButton @click="closeFilterModal">Cancelar</SecondaryButton>
                  <PrimaryButton type="submit" :class="{ 'opacity-25': form.processing }">Aplicar</PrimaryButton>
                </div>
              </div>
            </div>
          </form>
        </div>
      </Modal>

      <Modal :show="editModal">
        <div class="p-6">
          <h2 class="text-base font-medium leading-7 text-gray-900">
            Editar Registro
          </h2>
          <form @submit.prevent="submitEdit">
            <div class="space-y-12">
              <div class="border-b border-gray-900/10 pb-12 grid grid-cols-2 gap-6">

                <div class="col-span-1">
                  <InputLabel for="startDate">Fecha</InputLabel>
                  <div class="mt-2">
                    <input type="date" v-model="editingForm.date" class="block w-full py-1.5 rounded-md sm:text-sm form-input focus:border-indigo-600" />
                  </div>
                </div>

                <div class="col-span-1">
                  <InputLabel for="codsap">CODSAP</InputLabel>
                  <div class="mt-2">
                    <input type="text" v-model="editingForm.codsap" class="block w-full py-1.5 rounded-md sm:text-sm form-input focus:border-indigo-600" />
                  </div>
                </div>

                <div class="col-span-1">
                  <InputLabel for="description">Descripción</InputLabel>
                  <div class="mt-2">
                    <input type="text" v-model="editingForm.description" class="block w-full py-1.5 rounded-md sm:text-sm form-input focus:border-indigo-600" />
                  </div>
                </div>


                <div class="col-span-1">
                  <InputLabel for="serie">Serie</InputLabel>
                  <div class="mt-2">
                    <input type="text" v-model="editingForm.serie" class="block w-full py-1.5 rounded-md sm:text-sm form-input focus:border-indigo-600" />
                  </div>
                </div>


                <div class="col-span-1">
                  <InputLabel for="period">Periodo</InputLabel>
                  <div class="mt-2">
                    <input type="text" v-model="editingForm.period" class="block w-full py-1.5 rounded-md sm:text-sm form-input focus:border-indigo-600" />
                  </div>
                </div>


                <div class="col-span-1">
                  <InputLabel for="hire">Contrata</InputLabel>
                  <div class="mt-2">
                    <input type="text" v-model="editingForm.hire" class="block w-full py-1.5 rounded-md sm:text-sm form-input focus:border-indigo-600" />
                  </div>
                </div>


                <div class="col-span-1">
                  <InputLabel for="oc_pap">OC PAP</InputLabel>
                  <div class="mt-2">
                    <input type="text" v-model="editingForm.oc_pap" class="block w-full py-1.5 rounded-md sm:text-sm form-input focus:border-indigo-600" />
                  </div>
                </div>


                <div class="col-span-1">
                  <InputLabel for="sites">Sites</InputLabel>
                  <div class="mt-2">
                    <input type="text" v-model="editingForm.sites" class="block w-full py-1.5 rounded-md sm:text-sm form-input focus:border-indigo-600" />
                  </div>
                </div>

                <div class="col-span-1">
                  <InputLabel for="generalStatus">Estatus General</InputLabel>
                  <div class="mt-2">
                    <select v-model="editingForm.general_status" class="block w-full py-1.5 rounded-md sm:text-sm form-input focus:border-indigo-600">
                      <option disabled ="">Selecciona una opción</option>
                      <option value="option1">Opción 1</option>
                      <option value="option2">Opción 2</option>
                      <!-- Agrega más opciones según sea necesario -->
                    </select>
                  </div>
                </div>

                <div class="col-span-1">
                  <InputLabel for="status">Status</InputLabel>
                  <div class="mt-2">
                    <select v-model="editingForm.status" class="block w-full py-1.5 rounded-md sm:text-sm form-input focus:border-indigo-600">
                      <option disabled value="">Selecciona una opción</option>
                      <option value="option1">Opción 1</option>
                      <option value="option2">Opción 2</option>
                      <!-- Agrega más opciones según sea necesario -->
                    </select>
                  </div>
                </div>

                <div class="col-span-1">
                  <InputLabel for="monetary_value">Valor Monetario</InputLabel>
                  <div class="mt-2">
                    <input type="number" step="0.01" v-model="editingForm.monetary_value" class="block w-full py-1.5 rounded-md sm:text-sm form-input focus:border-indigo-600" />
                  </div>
                </div>

                <div class="col-span-1">
                  <InputLabel for="observation">Observación</InputLabel>
                  <div class="mt-2">
                    <input type="text" v-model="editingForm.observation" class="block w-full py-1.5 rounded-md sm:text-sm form-input focus:border-indigo-600" />
                  </div>
                </div>

                <div class="col-span-2 mt-6 flex items-center justify-end gap-x-6">
                  <SecondaryButton @click="closeEditModal">Cancelar</SecondaryButton>
                  <PrimaryButton type="submit" :class="{ 'opacity-25': form.processing }">Actualizar</PrimaryButton>
                </div>
              </div>
            </div>
          </form>
        </div>
      </Modal>

      <ConfirmCreateModal :confirmingcreation="showModal" itemType="Archivo" />
      <ConfirmUpdateModal :confirmingupdate="showModalConfirmEdit" itemType="Registro" />

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
  import ConfirmUpdateModal from '@/Components/ConfirmUpdateModal.vue';

  const props = defineProps({
    huaweiData: Object,
    project_id: String,
    userPermissions: Array,
  });

  const importExcelModal = ref(false);
  const file = ref(null);
  const filterModal = ref(false);
  const showModal = ref(false);
  const showModalConfirmEdit = ref(false);
  const excelData = ref({});
  const editingItem = ref (null);
  const editModal = ref(false);

  const form = useForm({
    name: '',
    file: null,
    statusData: null,
  });

  const filterForm = useForm({
    startDate: '',
    endDate: '',
    minValue: '',
    maxValue: '',
    generalStatus: '',
    status: '',
    codsap: '',
    serie: '',
    period: '',
    hire: '',
    ocPap: '',
    sites: ''
  });

  const editingForm = useForm({
    id: '',
    date: '',
    description: '',
    observation: '',
    monetary_value: '',
    general_status: '',
    status: '',
    codsap: '',
    serie: '',
    period: '',
    hire: '',
    oc_pap: '',
    sites: ''
  })

  const hasPermission = (permission) => {
  return props.userPermissions.includes(permission);
}

const openEditModal = (item) => {
  editingItem.value = JSON.parse(JSON.stringify(item));
  editingForm.id = editingItem.value.id;
  editingForm.date = editingItem.value.date;
  editingForm.codsap = editingItem.value.codsap;
  editingForm.description = editingItem.value.description;
  editingForm.serie = editingItem.value.serie;
  editingForm.period = editingItem.value.period;
  editingForm.hire = editingItem.value.hire;
  editingForm.oc_pap = editingItem.value.oc_pap;
  editingForm.sites = editingItem.value.sites;
  editingForm.general_status = editingItem.value.general_status;
  editingForm.status = editingItem.value.status;
  editingForm.monetary_value = editingItem.value.monetary_value;
  editingForm.observation = editingItem.value.observation;

  editModal.value = true;
};

const closeEditModal = () => {
    editingForm.reset();
    editModal.value = false;
}

const openImportModal = () => {
    importExcelModal.value = true;
}

const closeModal = () => {
  form.reset();
  importExcelModal.value = false;
};

const openFilterModal = () => {
  filterModal.value = true;
};

const closeFilterModal = () => {
  filterForm.reset();
  filterModal.value = false;
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

const applyFilters = () => {
    filterForm.post(route('huawei.filter', {project: props.project_id}), {
    onSuccess: () => {
      closeFilterModal();
    },
  });
};

const submitEdit = () => {
    editingForm.put(route('huawei.put', {project: props.project_id, itemToEdit: editingForm.id}), {
    onSuccess: () => {
      closeEditModal();
      showModalConfirmEdit.value = true
      setTimeout(() => {
        showModalConfirmEdit.value = false;
        router.visit(route('huawei.show', {project: props.project_id}))
      }, 2000);
    },
  });
};


  </script>
