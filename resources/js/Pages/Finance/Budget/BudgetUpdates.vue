<template>
    <Head title="Informe de Actualizaciones de Presupuesto" />
  
    <AuthenticatedLayout>
      <template #header>
        Informe de Actualizaciones de Presupuesto
      </template>
  
      <div class="inline-block min-w-full overflow-hidden rounded-lg shadow">
        <!-- Botón para agregar actualización de presupuesto -->
  
        <!-- Tabla de informe -->
        <table class="w-full whitespace-no-wrap">
          <thead>
            <tr class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
              <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                Nuevo Presupuesto
              </th>
              <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                Proyecto
              </th>
              <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                Razón
              </th>
              <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                Fecha de Actualización
              </th>
              <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                Usuario
              </th>
              <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                Fecha de Aprobación
              </th>

            </tr>
          </thead>
          <tbody>
            <!-- Iterar sobre las actualizaciones del presupuesto -->
            <tr v-for="update in budgetUpdates.data" :key="update.id" class="text-gray-700">
              <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                <p class="text-gray-900 whitespace-no-wrap">S/. {{ update.new_budget }}</p>
              </td>
              <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                <p class="text-gray-900 whitespace-no-wrap">{{ update.project.name }}</p>
              </td>
              <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                <p class="text-gray-900 whitespace-no-wrap">{{ update.reason }}</p>
              </td>
              <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                <p class="text-gray-900 whitespace-no-wrap">{{ update.update_date }}</p>
              </td>
              <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                <p class="text-gray-900 whitespace-no-wrap">{{ update.user.name }}</p>
              </td>
              <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                <p class="text-gray-900 whitespace-no-wrap">{{ update.approved_update_date }}</p>
              </td>
            </tr>
          </tbody>
        </table>
  
        <!-- Paginación -->
        <div class="flex flex-col items-center border-t bg-white px-5 py-5 xs:flex-row xs:justify-between">
          <pagination :links="budgetUpdates.links" />
        </div>
      </div>
    </AuthenticatedLayout>
  </template>
  
  <script setup>
  import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
  import Pagination from '@/Components/Pagination.vue'
  import { Head } from '@inertiajs/vue3';
  
  const props = defineProps({
      budgetUpdates: Object
  })

  </script>
  