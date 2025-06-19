<template>

  <Head title="Datos de Cargas" />
  <AuthenticatedLayout>
    <template #header>
      Datos de Cargas
    </template>
    <div class="min-w-full rounded-lg shadow">
      <PrimaryButton @click="openImportModal" type="button" class="flex-shrink-0">
        Importar Excel
      </PrimaryButton>
      <div class="overflow-x-auto mt-3">
        <table class="w-full whitespace-no-wrap">
          <thead>
            <tr class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
              <th
                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                N°</th>
              <th
                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                Nombre de Carga</th>
              <th
                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                Fecha de Carga</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="item in huaweiData.data" :key="item.id" class="text-gray-700">
              <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">{{ item.id }}</td>
              <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">{{ item.name }}</td>
              <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">{{ formattedDate(item.created_at) }}</td>
              <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                <div class="flex items-center">
                  <button @click="">
                    <ShowIcon />
                  </button>
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
        <h2 class="text-base font-medium leading-7 text-gray-900">
          Subir Archivo
        </h2>
        <div v-if="props.huaweiData.data.length > 0"
          class="inline-flex items-center p-2 mb-4  mt-4 text-sm text-yellow-800 rounded-lg bg-yellow-50 dark:bg-gray-800 dark:text-yellow-300"
          role="alert">
          <svg class="flex-shrink-0 inline w-4 h-4 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
            fill="currentColor" viewBox="0 0 20 20">
            <path
              d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
          </svg>
          <span class="sr-only">Info</span>
          <div>
            <span class="font-small">Al subir un nuevo excel para este proyecto se reemplazarán los datos actuales, para
              no
              perder información puede editar un registro en específico.</span>
          </div>
        </div>
        <form @submit.prevent="submit">
          <div class="space-y-12">
            <div class="border-b border-gray-900/10 pb-12">

              <div class="mt-2">
                <InputLabel for="file">Archivo Excel</InputLabel>
                <div class="mt-2">
                  <input type="file"
                    class="block w-full py-1.5 rounded-md sm:text-sm form-input focus:border-indigo-600"
                    @change="handleFileUpload" accept=".xls, .xlsx" />
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

  </AuthenticatedLayout>
</template>

<script setup>

import { Head } from '@inertiajs/vue3';
import Pagination from '@/Components/Pagination.vue';
import { formattedDate } from '@/utils/utils'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { ShowIcon } from "@/Components/Icons";

const props = defineProps({
  loads: Object,
  userPermissions: Array,
});

</script>
