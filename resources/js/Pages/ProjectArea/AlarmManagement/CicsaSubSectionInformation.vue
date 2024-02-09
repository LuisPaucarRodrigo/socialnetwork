<template>
    <Head title="Gestion de Miembro" />
    <AuthenticatedLayout>
      <template #header>
        Detalles del Miembro
      </template>
      <!-- Datos en formato de texto -->
      <div class="mt-5">
        <div class="flex flex-col gap-4">
          <div class="p-4 border rounded bg-white shadow">
            <div class="flex gap-2 items-center">
              <div class="text-lg font-semibold">Nombre:</div>
              <div class="text-lg text-gray-500">{{ props.subSection.name }}</div>
            </div>
            <div class="flex gap-2 items-center">
              <div class="text-lg font-semibold">Descripción:</div>
              <div class="text-lg text-gray-500">{{ props.subSection.description }}</div>
            </div>
            <div class="flex gap-2 items-center">
              <div class="text-lg font-semibold">Fecha de Inicio:</div>
              <div class="text-lg text-gray-500">{{ props.subSection.start_date }}</div>
            </div>
            <div class="flex gap-2 items-center">
              <div class="text-lg font-semibold">Fecha de Fin:</div>
              <div class="text-lg text-gray-500">{{ props.subSection.end_date }}</div>
            </div>
            <div class="flex gap-2 items-center">
              <div class="text-lg font-semibold">Requerimientos:</div>
              <div class="text-lg text-gray-500">{{ props.subSection.requirements }}</div>
            </div>
            <div class="flex gap-2 items-center">
              <div class="text-lg font-semibold">Apartado:</div>
              <div class="text-lg text-gray-500">{{ props.subSection.cicsa_section.name }}</div>
            </div>
          </div>
          <div class="inline-block text-center">
            <Link
              class="w-full rounded-md bg-indigo-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
              :href="route('sections.cicsaSubSections')">Ver todos los miembros</Link>
          </div>
        </div>
      </div>
    </AuthenticatedLayout>
  </template>
    
  <script setup>
  import { Head, Link } from '@inertiajs/vue3';
  import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
  
  const props = defineProps({
    subSection: Object,
  });
  
  </script>