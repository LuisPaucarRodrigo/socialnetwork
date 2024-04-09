<template>
  <Head title="Detalles del Almacén" />
  <AuthenticatedLayout :redirectRoute="'warehouses.warehouses'">
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
            <div class="text-lg font-semibold">Descripción:</div>
            <div class="text-lg text-gray-500">{{ props.warehouse.description }}</div>
          </div>
          <div class="flex gap-2 items-center">
            <div class="text-lg font-semibold">Encargado:</div>
            <div class="text-lg text-gray-500">{{ props.warehouse.person_in_charge }}</div>
          </div>
          <div class="flex gap-2 items-center">
            <div class="text-lg font-semibold">Categoría:</div>
            <div class="text-lg text-gray-500">{{ props.warehouse.category }}</div>
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