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
      <button v-if="redirectRoute" @click="()=> router.visit(getRoute())"
        class="ml-4 text-gray-500 focus:outline-none">
        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
        </svg>
      </button>
      <a v-else href="javascript:window.history.back()"
        class="ml-4 text-gray-500 focus:outline-none">
        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
        </svg>
      </a>
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
            <button @click="download" class="rounded-md bg-gray-200 px-6 py-2 text-sm font-semibold text-gray-700 shadow-sm hover:bg-gray-300 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-600">
              Descargar Archivo
            </button>
            <div class="mt-4" style="max-height: 600px; overflow-y: auto;">
              <iframe :src="documentUrl" width="100%" height="100%"></iframe>
            </div>
          </div>
          <div v-else>
            Horario no Disponible
          </div>
          <div class="mt-8 flex items-center justify-end gap-x-3">
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
import axios from 'axios';
import { router } from '@inertiajs/vue3';

const props = defineProps({
  redirectRoute: [String, Object]
})

const getRoute = () => {
  if (typeof props.redirectRoute === 'string') {
    return route(props.redirectRoute);
  } else {
    return route(props.redirectRoute.route, props.redirectRoute.params);
  }
};

const showModalSchedule = ref(false);
const excelData = ref(null);
const fileExists = ref(null);
const documentUrl = ref(null);
const documentId = ref(null);

const closeScheduleModal = () => {
  showModalSchedule.value = false;
};

const openScheduleModal = async () => {
  showModalSchedule.value = true;
  const routeToShow = route('management.employees.schedule.latest');
  try {
    const response = await axios.get(routeToShow);
    if (response.data.hasSchedule) {
      fileExists.value = true;
      documentUrl.value = route('management.employees.schedule.preview', { schedule: response.data.schedule.id });
      documentId.value = response.data.schedule.id;
    } else {
      fileExists.value = false;
      documentUrl.value = '';
    }
  } catch (error) {
    console.error('Error al obtener el último horario:', error);
  }
};

function download() {
  const backendDocumentUrl = route('management.employees.schedule.download', { schedule: documentId.value });
  window.open(backendDocumentUrl, '_blank');
};

</script>
  