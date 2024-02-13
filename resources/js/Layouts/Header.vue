<template>
  <header class="flex items-center justify-between border-b-4 border-indigo-600 bg-white px-6 py-4">
    <div class="flex items-center">
      <button @click="$page.props.showingMobileMenu = !$page.props.showingMobileMenu"
        class="text-gray-500 focus:outline-none lg:hidden">
        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M4 6H20M4 12H20M4 18H11" stroke="currentColor" stroke-width="2" stroke-linecap="round"
            stroke-linejoin="round" />
        </svg>
      </button>

      <!-- Botón para volver -->
      <!-- <button @click="goBack" class="ml-4 text-gray-500 focus:outline-none">
        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
        </svg>
      </button> -->
    </div>

    <div class="flex items-center">
      <dropdown>
        <template #trigger>
          <button @click="dropdownOpen = !dropdownOpen" class="relative block overflow-hidden">
            {{ $page.props.auth.user.name }}
          </button>
        </template>

        <template #content>
          <dropdown-link :href="route('profile.edit')">
            Perfil
          </dropdown-link>
          <div class="dropdown">
            <div class="dropdown-menu">
              <button @click="openScheduleModal"
                class="dropdown-item block w-full text-left px-4 py-2 text-sm text-black-700 hover:bg-indigo-600 hover:text-white focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                Horario
              </button>
            </div>
          </div>
          <dropdown-link class="w-full text-left" :href="route('logout')" method="post" as="button">
            Cerrar sesión
          </dropdown-link>
        </template>
      </dropdown>
    </div>
    <Modal :show="showModalSchedule">
      <div class="p-8">
        <h2 class="text-lg font-medium leading-7 text-gray-900 mb-3">
          Horario
        </h2>
        <div class="space-y-8">
          <div v-if="fileExists">
            <a href="http://localhost:8000/documents/schedule/EmployeesSchedule.xlsx"
              class="rounded-md bg-gray-200 px-6 py-2 text-sm font-semibold text-gray-700 shadow-sm hover:bg-gray-300 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-600">
              Descargar Archivo
            </a>
            <div class="mt-4" style="max-height: 600px; overflow-y: auto;">
              <table v-if="excelData" class="w-full whitespace-no-wrap overflow-auto border border-gray-200">
                <thead>
                  <tr class="border-b bg-gray-50 text-left text-sm font-semibold uppercase tracking-wide text-gray-500">
                    <th v-for="header in excelData[0]" :key="header"
                      class="border-b-2 border-gray-200 bg-gray-100 px-6 py-3 text-left text-sm font-semibold uppercase tracking-wider text-gray-600">
                      {{ header }}
                    </th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(row, index) in excelData" :key="index" class="text-gray-700">
                    <td v-for="cell in row" :key="cell" class="border-b border-gray-200 bg-white px-6 py-4 text-sm">
                      {{ cell }}
                    </td>
                  </tr>
                </tbody>
              </table>
              <p v-else class="mt-2">Cargando archivo...</p>
            </div>
          </div>
          <div v-else>
            Horario no Disponible
          </div>
          <div class="mt-8 flex items-center justify-end gap-x-6">
            <button @click="closeScheduleModal"> Cerrar </button>
          </div>
        </div>
      </div>
    </Modal>
  </header>
</template>
<script setup>
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import { ref } from 'vue'
import Modal from '@/Components/Modal.vue';
import * as XLSX from 'xlsx';

// const goBack = () => {
//   window.history.back();
//   window.onpopstate = function (event) {
//     location.reload();
//   };
// };


const showModalSchedule = ref(false);
const excelData = ref(null);
const fileExists = ref(null);

const closeScheduleModal = () => {
  showModalSchedule.value = false;
};

const openScheduleModal = async () => {
  showModalSchedule.value = true;
  try {
    // Realiza la solicitud solo cuando se abre el modal
    const response = await axios.get('http://localhost:8000/documents/schedule/EmployeesSchedule.xlsx', {
      responseType: 'blob'
    });

    const arrayBuffer = await response.data.arrayBuffer();

    const workbook = XLSX.read(new Uint8Array(arrayBuffer), { type: 'array' });

    const sheetName = workbook.SheetNames[0];

    const sheetData = XLSX.utils.sheet_to_json(workbook.Sheets[sheetName], { header: 1 });

    excelData.value = sheetData;
    fileExists.value = true;
  } catch (error) {
    excelData.value = null;
  }
};


</script>
  