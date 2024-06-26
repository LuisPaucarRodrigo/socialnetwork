<template>
    <Head title="Formulario y Tablas" />
    <AuthenticatedLayout>
      <template #header>
        <h1 class="text-lg font-semibold leading-7 text-gray-900">Formulario y Tablas</h1>
      </template>
      <div class="min-w-full rounded-lg shadow p-6">
        <form @submit.prevent="handleSubmit" class="mb-8">
          <div class="grid grid-cols-2 gap-6">
            <div>
              <label for="guide_number" class="block text-sm font-medium text-gray-700">Número de Guía</label>
              <input
                id="guide_number"
                type="text"
                v-model="form.guide_number"
                class="block w-full mt-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
              />
              <InputError :message="form.errors.guide_number" />
            </div>

            <div>
              <label for="entry_date" class="block text-sm font-medium text-gray-700">Fecha de Entrada</label>
              <input
                id="entry_date"
                type="date"
                v-model="form.entry_date"
                class="block w-full mt-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
              />
              <InputError :message="form.errors.entry_date" />
            </div>

          </div>

        </form>
        <div class="grid grid-cols-2 gap-x-12 gap-y-12">
          <div>
            <div class="flex items-center mb-4">
                <h2 class="text-lg font-medium leading-7 text-gray-900 mr-4">Tabla de Productos</h2>
                <button @click="" type="button" class="text-blue-500 hover:text-purple-500">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                        class="w-7 h-7">
                    <path stroke-linecap="round" stroke-linejoin="round"
                            d="M7.5 7.5h-.75A2.25 2.25 0 0 0 4.5 9.75v7.5a2.25 2.25 0 0 0 2.25 2.25h7.5a2.25 2.25 0 0 0 2.25-2.25v-7.5a2.25 2.25 0 0 0-2.25-2.25h-.75m-6 3.75 3 3m0 0 3-3m-3 3V1.5m6 9h.75a2.25 2.25 0 0 1 2.25 2.25v7.5a2.25 2.25 0 0 1-2.25 2.25h-7.5a2.25 2.25 0 0 1-2.25-2.25v-.75"/>
                    </svg>
                </button>
            </div>


            <div class="overflow-x-auto mt-3">
              <table class="w-full whitespace-no-wrap">
                <thead>
                  <tr class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                    <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                      Descripción del producto
                    </th>
                    <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                      Código de Claro
                    </th>
                    <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                      Marca
                    </th>
                    <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                      Modelo
                    </th>
                    <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                      Cantidad
                    </th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="item in equipments.data" :key="item.id" class="text-gray-700">
                    <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">{{ item.name }}</td>
                    <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">{{ item.claro_code }}</td>
                    <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">{{ item.brand_model.name }}</td>
                    <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">{{ item.brand_model.brand.name }}</td>
                    <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                      <div class="flex items-center">
                        <button class="text-orange-400 hover:underline mr-2"><EyeIcon class="h-5 w-5" /></button>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
          <div>
            <div class="flex items-center mb-4">
                <h2 class="text-lg font-medium leading-7 text-gray-900 mr-4">Tabla de Activos</h2>
                <button @click="" type="button" class="text-blue-500 hover:text-purple-500">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                        class="w-7 h-7">
                    <path stroke-linecap="round" stroke-linejoin="round"
                            d="M7.5 7.5h-.75A2.25 2.25 0 0 0 4.5 9.75v7.5a2.25 2.25 0 0 0 2.25 2.25h7.5a2.25 2.25 0 0 0 2.25-2.25v-7.5a2.25 2.25 0 0 0-2.25-2.25h-.75m-6 3.75 3 3m0 0 3-3m-3 3V1.5m6 9h.75a2.25 2.25 0 0 1 2.25 2.25v7.5a2.25 2.25 0 0 1-2.25 2.25h-7.5a2.25 2.25 0 0 1-2.25-2.25v-.75"/>
                    </svg>
                </button>
            </div>
            <div class="overflow-x-auto mt-3">
              <table class="w-full whitespace-no-wrap">
                <thead>
                  <tr class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                    <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                      Nombre del Activo
                    </th>
                    <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                      Tipo
                    </th>
                    <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                      Estado
                    </th>
                    <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                      Ubicación
                    </th>
                    <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                      Responsable
                    </th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="item in assets.data" :key="item.id" class="text-gray-700">
                    <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">{{ item.name }}</td>
                    <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">{{ item.type }}</td>
                    <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">{{ item.status }}</td>
                    <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">{{ item.location }}</td>
                    <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                      <div class="flex items-center">
                        <button class="text-orange-400 hover:underline mr-2"><EyeIcon class="h-5 w-5" /></button>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <div class="mt-6 flex items-center justify-end gap-x-3">
            <button type="button" @click="resetForm" class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-700">Cancelar</button>
            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-800">{{ form.id ? 'Actualizar' : 'Guardar' }}</button>
          </div>
      </div>
    </AuthenticatedLayout>
  </template>

  <script setup>
  import { ref } from 'vue';
  import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
  import { Head, Link } from '@inertiajs/vue3';
  import { EyeIcon } from '@heroicons/vue/24/outline';
  import InputError from '@/Components/InputError.vue';

  const props = defineProps({
    brand_models: Object,
    brands: Object,
    equipments: Object,
    materials: Object
  });

  const form = ref({
    name: '',
    unit: '',
    type: '',
    type_product: '',
    resource_type: '',
    description: '',
    errors: {}
  });

  const typeProductOptions = ref([
    { id: 1, name: 'Tipo 1' },
    { id: 2, name: 'Tipo 2' }
  ]);

  const resourceTypeOptions = ref([
    { id: 1, name: 'Recurso 1' },
    { id: 2, name: 'Recurso 2' }
  ]);

  const equipments = ref({
    data: [
      { id: 1, name: 'Producto 1', claro_code: '001', brand_model: { name: 'Marca 1', brand: { name: 'Modelo 1' } } },
      { id: 2, name: 'Producto 2', claro_code: '002', brand_model: { name: 'Marca 2', brand: { name: 'Modelo 2' } } }
    ]
  });

  const assets = ref({
    data: [
      { id: 1, name: 'Activo 1', type: 'Tipo A', status: 'Disponible', location: 'Almacén 1', responsible: 'Persona 1' },
      { id: 2, name: 'Activo 2', type: 'Tipo B', status: 'En uso', location: 'Almacén 2', responsible: 'Persona 2' }
    ]
  });

  const resetForm = () => {
    form.value = {
      name: '',
      unit: '',
      type: '',
      type_product: '',
      resource_type: '',
      description: '',
      errors: {}
    };
  };

  const handleSubmit = () => {
    // Validación y envío de formulario
  };
  </script>
