<template>
  <Head title="Detalles del Almacén" />
  <AuthenticatedLayout>
    <template #header>
      Detalles del Almacén
    </template>
    <!-- Datos en formato de texto -->
    <div class="mt-5">
      <div class="flex flex-col gap-4">
        <div class="p-4 border rounded bg-white shadow">
          <div class="flex gap-2 items-center">
            <div class="text-lg font-semibold">Nombre:</div>
            <div class="text-lg text-gray-500">{{ props.warehouse.name }}</div>
          </div>
          <div class="flex gap-2 items-center">
            <div class="text-lg font-semibold">Ubicación:</div>
            <div class="text-lg text-gray-500">{{ props.warehouse.location }}</div>
          </div>
          <div class="flex gap-2 items-center">
            <div class="text-lg font-semibold">Encargado:</div>
            <div class="text-lg text-gray-500">{{ props.warehouse.manager }}</div>
          </div>
          <div class="flex flex-col gap-2 ">
            <div class="text-lg font-semibold">Cabeceras:</div>
            <div v-if="props.warehouse.headers.length === 0" class="text-lg text-gray-500">No hay cabeceras asociadas.
            </div>
            <div v-else>
              <ul>
                <li v-for="header in props.warehouse.headers" :key="header.id" class="text-lg text-gray-500">{{
                  header.name }}</li>
              </ul>
            </div>
          </div>
        </div>
        <div class="inline-block text-center">
          <Link
            class="w-full rounded-md bg-indigo-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
            :href="route('warehouses.warehouses')">Ver todos los almacenes</Link>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
  
<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
  warehouse: Object,
});

</script>