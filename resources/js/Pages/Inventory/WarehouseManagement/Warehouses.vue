<template>

  <Head title="Almacenes" />
  <AuthenticatedLayout :redirectRoute="'warehouses.warehouses'">
    <template #header>
      Almacenes
    </template>
    <div class="min-w-full p-3 rounded-lg shadow">
      <div class="flex gap-4">
      </div>
      <br>
      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-4 gap-3">
        <div
          class="bg-white p-3 rounded-md shadow-sm border border-gray-400 items-center">
          <div>
            <h2 class="text-sm font-semibold mb-3">
              ACTIVOS DE LA EMPRESA
            </h2>
          </div>
          <h3 class="text-sm font-semibold text-gray-700 line-clamp-1 mb-2">
            Ubicación: CCIP
          </h3>
          <h3 class="text-sm font-semibold text-gray-700 line-clamp-1 mb-2">
            Encargado: CCIP
          </h3>
          <div class="text-gray-500 text-sm">
            <div class="grid grid-cols-1 gap-y-1">
              <Link :href="route('resources.index')"
                class="text-blue-600 underline whitespace-no-wrap hover:text-purple-600">Ingresar</Link>
            </div>
          </div>
        </div>


      <div v-for="item in warehouses.data" :key="item.id"
        class="bg-white p-3 rounded-md shadow-sm border border-gray-300 items-center">
        <div class="grid grid-cols-2">
          <h2 class="text-sm font-semibold mb-3">
            {{ item.name }}
          </h2>
          <div class="inline-flex justify-end gap-x-0 mb-4">
            <div class="flex space-x-3 justify-center">
              <Link :href="route('warehouses.warehouse', { warehouse: item })" class="text-green-600 hover:underline">
              <EyeIcon class="h-4 w-4 ml-1" />
              </Link>
            </div>

          </div>
        </div>
        <h3 class="text-sm font-semibold text-gray-700 line-clamp-1 mb-2">
          Descripción: {{ item.description }}
        </h3>
        <h3 class="text-sm font-semibold text-gray-700 line-clamp-1 mb-2">
          Encargado: {{ item.person_in_charge }}
        </h3>
        <div class="text-gray-500 text-sm">
          <div class="grid grid-cols-1 gap-y-1">
            <Link :href="route('warehouses.products', { warehouse: item.id })"
              class="text-blue-600 underline whitespace-no-wrap hover:text-purple-600">Productos</Link>
            <Link :href="route('warehouses.outputs', { warehouse: item.id })"
              class="text-blue-600 underline whitespace-no-wrap hover:text-purple-600">Salidas</Link>
              <Link v-if="item.id === 3" :href="route('warehouses.purchaseorders.approve', { warehouse: item.id })"
              class="text-blue-600 underline whitespace-no-wrap hover:text-purple-600">Aprobar Ingresos Por Compras</Link>
          </div>
        </div>
      </div>
    </div>
    <br>
    <div class="flex flex-col items-center border-t px-5 py-5 xs:flex-row xs:justify-between">
      <pagination :links="warehouses.links" />
    </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Pagination from '@/Components/Pagination.vue'
import { Head, Link } from '@inertiajs/vue3';
import { EyeIcon } from '@heroicons/vue/24/outline';

const props = defineProps({
  warehouses: Object,
  auth: Object
});

</script>