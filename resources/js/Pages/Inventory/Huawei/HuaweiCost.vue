<template>

    <Head title="Datos de Cargas" />
    <AuthenticatedLayout>
      <template #header>
        Datos de Cargas
      </template>
      <div class="min-w-full rounded-lg shadow">
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
                    <button v-if="hasPermission('UserManager')" @click=""
                      class="text-orange-400 hover:underline mr-2">
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

    </AuthenticatedLayout>
  </template>

  <script setup>
  import { ref } from 'vue';
  import { useForm, router, Link, Head } from '@inertiajs/vue3';
  import Pagination from '@/Components/Pagination.vue';
  import PrimaryButton from '@/Components/PrimaryButton.vue';
  import { formattedDate } from '@/utils/utils'
  import { EyeIcon } from '@heroicons/vue/24/outline';
  import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

  const props = defineProps({
    loads: Object,
    userPermissions: Array,
  });

  const hasPermission = (permission) => {
  return props.userPermissions.includes(permission);
}

  </script>
