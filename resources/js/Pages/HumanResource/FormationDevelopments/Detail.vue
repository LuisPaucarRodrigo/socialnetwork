<template>

    <Head title="Gestion de Miembro" />
    <AuthenticatedLayout >
      <template #header>
        Programa de Formación
      </template>
      <!-- Datos en formato de texto -->
      <div class="mt-5">
        <div class="p-4 text-md border rounded bg-white shadow mb-10">
          <div class="mb-14">
            <p class="text-lg font-bold pb-1 border-b-2 border-gray-200 mb-6 text-blue-900">
                Empleado
            </p>
            <div class="grid lg:grid-cols-3 gap-y-6 gap-x-12">
              <div class="grid grid-cols-2 gap-2">
                <div class="font-semibold text-gray-600">Nombre:</div>
                <div class="text-gray-500">
                  {{ employee.name }} {{ employee.lastname }}
                </div>
              </div>
              <div class="grid grid-cols-2 gap-2">
                <div class="font-semibold text-gray-600">DNI:</div>
                <div class="text-gray-500">
                    {{ employee.dni }}
                </div>
              </div>
              <div class="col-start-1 grid grid-cols-2 gap-2">
                <div class="font-semibold text-gray-600">Teléfono 1:</div>
                <div class="text-gray-500">
                  {{ employee.phone1 }}
                </div>
              </div>
              <div class="grid grid-cols-2 gap-2">
                <div class="font-semibold text-gray-600">Teléfono 2:</div>
                <div class="text-gray-500">
                  {{ employee.phone2 }}
                </div>
              </div>
              <div class="col-start-1 grid grid-cols-2 gap-2">
                <div class="font-semibold text-gray-600">Email:</div>
                <div class="text-gray-500">
                 {{ employee.email }}
                </div>
              </div>
            </div>
          </div>
          
          <div class="mb-14">
            <p class="text-lg font-bold pb-1 border-b-2 border-gray-200 mb-6 text-blue-900">
                Programas de formación
            </p>
            <div class="grid lg:grid-cols-3 gap-12">
              <div class="lg:col-span-2">
                <table class="w-full">
                  <thead>
                      <tr class="border-b-2 border-gray-300">
                          <th class="font-semibold text-sm py-2">Estado</th>
                          <th class="font-semibold text-sm py-2">Nombre</th>
                          <th class="font-semibold text-sm py-2">Inicio - Fin</th>
                      </tr>
                  </thead>
                  <tbody>
                      <!-- Ejemplo de fila -->
                      <tr v-for="(ap, i) in employee.assignated_programs" class="border-b-2 border-gray-200" :key="i">
                          <td class="text-sm text-center py-2">
                              <span
                                class="inline-flex items-center gap-x-1 h-4 w-4 text-sm rounded-full font-medium text-white"
                                :class="[
                                        {
                                            'bg-gray-500': true,
                                            'bg-yellow-500': ap.urgent_state === 'medium',
                                            'bg-red-500': ap.urgent_state === 'high',
                                        }
                                    ]"
                                >
                              </span>
                          </td> <!-- Espacio vacío para la primera columna -->
                          <td class="text-sm text-center py-2">{{ ap.formation_program.name }}</td> <!-- Contenido de la segunda columna -->
                          <td class="text-sm text-center py-2">{{ formattedDate(ap.start_date) }} - {{ formattedDate(ap.end_date) }}</td> <!-- Contenido de la tercera columna -->
                      </tr>
                  </tbody>
              </table>
              </div>
            </div>
          </div>
        </div>
        <div class="flex justify-center">
          <Link
            class="rounded-md bg-indigo-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
            :href="route('management.employees.formation_development.employees_in_programs')">Ver todo el personal asignado</Link>
        </div>
      </div>
    </AuthenticatedLayout>
  </template>
  
  <script setup>
  import { Head, Link } from '@inertiajs/vue3';
  import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
  import { formattedDate } from '@/utils/utils.js';
  
  const { employee } = defineProps({
    employee: Object,
  });

  </script>