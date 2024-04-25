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

        <!-- SPECIAL WAREHOUSES -->
        <div v-for="item in special_warehouses" :key="item.id"
          class="bg-white p-3 rounded-md shadow-sm border border-gray-300 items-center">
          <div class="grid grid-cols-2">
            <h2 class="text-sm font-semibold mb-3">
              {{ item.name }}
            </h2>
            <div class="inline-flex justify-end gap-x-0 mb-4">
              <div class="flex space-x-3 justify-center">
                <Link :href="'#'" class="text-green-600 hover:underline">
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
              <Link :href="route('inventory.special_products.index', { warehouse_id: item.id })"
                class="text-blue-600 underline whitespace-no-wrap hover:text-purple-600">Productos</Link>
              <Link :href="route('inventory.special_dispatch.index', { warehouse_id: item.id })"
                class="text-blue-600 underline whitespace-no-wrap hover:text-purple-600">Despachos</Link>
              <Link :href="route('inventory.special_refund.index',
    { warehouse_id: item.id })" class="text-blue-600 underline whitespace-no-wrap hover:text-purple-600">
              Devoluciones</Link>
            </div>
          </div>
        </div>


        <div v-for="item in warehouses" :key="item.id"
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
              <Link
                :href="item.id === 3 ? route('warehouses.purchaseorders.approve', { warehouse: item.id }) : route('inventory.retrieval_entry.index')"
                class="text-blue-600 underline whitespace-no-wrap hover:text-purple-600">
              {{ item.id === 3 ? 'Compras' : 'Ingresos' }}</Link>
              <Link
                :href="item.id === 3 ? route('warehouses.products', { warehouse: item.id }) : route('inventory.retrievalProduct.index')"
                class="text-blue-600 underline whitespace-no-wrap hover:text-purple-600">Productos</Link>
              <Link
                :href="item.id === 3 ? route('warehouses.dispatches', { warehouse: item.id }) : route('inventory.retrievalDispatch.index')"
                class="text-blue-600 underline whitespace-no-wrap hover:text-purple-600">Despachos</Link>
            </div>
          </div>
        </div>

        <div class="bg-white p-3 rounded-md shadow-sm border border-gray-300 items-center">
          <div class="grid grid-cols-2">
            <h2 class="text-sm font-semibold mb-3">
              Activos
            </h2>
            <div class="inline-flex justify-end gap-x-0 mb-4">
              <div class="flex space-x-3 justify-center">
                <Link :href="route('warehouses.warehouse', { warehouse: 1 })" class="text-green-600 hover:underline">
                <EyeIcon class="h-4 w-4 ml-1" />
                </Link>
              </div>
            </div>
          </div>
          <h3 class="text-sm font-semibold text-gray-700 line-clamp-1 mb-2">
            Descripción: Activos
          </h3>
          <h3 class="text-sm font-semibold text-gray-700 line-clamp-1 mb-2">
            Encargado: Gustavo Flores
          </h3>
          <div class="text-gray-500 text-sm">
            <div class="grid grid-cols-1 gap-y-1">
              <Link :href="route('warehouses.resource')"
                class="text-blue-600 underline whitespace-no-wrap hover:text-purple-600">
              Compras</Link>
              <Link :href="route('warehouses.index.resource')"
                class="text-blue-600 underline whitespace-no-wrap hover:text-purple-600">Productos</Link>
            </div>
          </div>
        </div>

        <div class="bg-white p-3 rounded-md shadow-sm border border-gray-300 items-center">
          <div class="grid grid-cols-2">
            <h2 class="text-sm font-semibold mb-3">
              Servicios
            </h2>
            <div class="inline-flex justify-end gap-x-0 mb-4">
              <div class="flex space-x-3 justify-center">
                <Link :href="route('warehouses.warehouse', { warehouse: 1 })" class="text-green-600 hover:underline">
                <EyeIcon class="h-4 w-4 ml-1" />
                </Link>
              </div>
            </div>
          </div>
          <h3 class="text-sm font-semibold text-gray-700 line-clamp-1 mb-2">
            Descripción: Servicios
          </h3>
          <h3 class="text-sm font-semibold text-gray-700 line-clamp-1 mb-2">
            Encargado: Gustavo Flores
          </h3>
          <div class="text-gray-500 text-sm">
            <div class="grid grid-cols-1 gap-y-1">
              <Link :href="route('inventory.warehouses.service')"
                class="text-blue-600 underline whitespace-no-wrap hover:text-purple-600">
              Servicios</Link>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { EyeIcon } from '@heroicons/vue/24/outline';

const props = defineProps({
  warehouses: Object,
  special_warehouses: Object,
  auth: Object
});

</script>