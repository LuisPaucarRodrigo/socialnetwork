<template>
    <div>
      <Head title="Detalles del Almacén" />
      <AuthenticatedLayout>
        <template #header>
          Contenido de las cabeceras
        </template>
        <div class="">
          <div class="space-y-12">
            <div class="border-b border-gray-900/10 pb-12">
              <div v-if="props.headers.length === 0" class="text-lg text-gray-500">
                No hay cabeceras asociadas.
              </div>
              <div class="grid grid-cols-2 gap-4">
                <div class="flex flex-col gap-2">
                  <InputLabel for="name" class="font-medium leading-6 text-gray-900 mt-2">Nombre del Producto</InputLabel>
                  <div class="mt-2">
                    <div class="block w-full rounded-md border-0 py-1.5 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 p-2">
                      {{ props.product.name }}
                    </div>
                  </div>
                </div>
  
                <div v-for="header in props.headers" :key="header.id" class="flex flex-col gap-2">
                  <InputLabel for="header" class="font-medium leading-6 text-gray-900 mt-2">{{ header.header.name }}</InputLabel>
                  <div class="mt-2">
                    <div class="block w-full rounded-md border-0 py-1.5 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 p-2">
                      {{ header.content }}
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </AuthenticatedLayout>
    </div>
  </template>
  
  <script setup>
    import { Head } from '@inertiajs/vue3';
    import InputLabel from '@/Components/InputLabel.vue';
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
  
    const props = defineProps(['product', 'headers']);
  </script>
  