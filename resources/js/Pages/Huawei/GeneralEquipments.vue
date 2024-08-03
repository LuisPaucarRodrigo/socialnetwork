<template>
    <Head title="Huawei" />
    <AuthenticatedLayout :redirectRoute="{route: 'huawei.inventory.show', params: {equipment: 1}}">
      <template #header>
        Equipos
      </template>
      <div class="min-w-full rounded-lg shadow">
        <div class="flex flex-col sm:flex-row gap-4 justify-between rounded-lg p-3">
          <!-- Sección de botones -->
          <div class="flex flex-col sm:flex-row gap-4 w-full items-center">
            <a :href="route('huawei.inventory.export')" type="button" class="rounded-md bg-green-600 px-4 py-2 text-center text-sm text-white hover:bg-green-500">
            Exportar
        </a>
          </div>
          <!-- Sección de búsqueda -->
          <div class="flex items-center w-full sm:w-auto">
            <form @submit.prevent="search" class="flex items-center w-full sm:w-auto">
            <TextInput type="text" placeholder="Buscar..." v-model="searchForm.searchTerm" class="mr-2 min-w-[150px] sm:min-w-[200px]" />
            <button type="submit" :class="{ 'opacity-25': searchForm.processing }"
                class="ml-2 rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                <svg width="20px" height="20px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M15.7955 15.8111L21 21M18 10.5C18 14.6421 14.6421 18 10.5 18C6.35786 18 3 14.6421 3 10.5C3 6.35786 6.35786 3 10.5 3C14.6421 3 18 6.35786 18 10.5Z"
                    stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </button>
        </form>
          </div>
        </div>

        <div>
          <div class="overflow-x-auto mt-3">
            <table class="w-full whitespace-no-wrap">
              <thead>
                <tr class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                  <th class="border-b-2 border-gray-200 bg-gray-100 px-2 py-1 text-xs font-semibold uppercase tracking-wider text-gray-600 text-center">
                    Nº
                  </th>
                  <th class="border-b-2 border-gray-200 bg-gray-100 px-2 py-1 text-xs font-semibold uppercase tracking-wider text-gray-600 text-center">
                    Fecha
                  </th>
                  <th class="border-b-2 border-gray-200 bg-gray-100 px-2 py-1 text-xs font-semibold uppercase tracking-wider text-gray-600 text-center">
                    Código SAP
                  </th>
                  <th class="border-b-2 border-gray-200 bg-gray-100 px-2 py-1 text-xs font-semibold uppercase tracking-wider text-gray-600 text-center">
                    Descripción del Equipo
                  </th>
                  <th class="border-b-2 border-gray-200 bg-gray-100 px-2 py-1 text-xs font-semibold uppercase tracking-wider text-gray-600 text-center">
                    Nº de Serie
                  </th>
                  <th class="border-b-2 border-gray-200 bg-gray-100 px-2 py-1 text-xs font-semibold uppercase tracking-wider text-gray-600 text-center">
                    Site Asignado
                  </th>
                  <th class="border-b-2 border-gray-200 bg-gray-100 px-2 py-1 text-xs font-semibold uppercase tracking-wider text-gray-600 text-center">
                    Estado
                  </th>
                  <th class="border-b-2 border-gray-200 bg-gray-100 px-2 py-1 text-xs font-semibold uppercase tracking-wider text-gray-600 text-center">
                    Fecha de Instalación
                  </th>
                  <th class="border-b-2 border-gray-200 bg-gray-100 px-2 py-1 text-xs font-semibold uppercase tracking-wider text-gray-600 text-center">
                    DIU Asignada
                  </th>
                  <th class="border-b-2 border-gray-200 bg-gray-100 px-2 py-1 text-xs font-semibold uppercase tracking-wider text-gray-600 text-center">
                    Valor Monetario
                  </th>
                  <th class="border-b-2 border-gray-200 bg-gray-100 px-2 py-1 text-xs font-semibold uppercase tracking-wider text-gray-600 text-center">
                    Observación
                  </th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(item, index) in (props.search ? props.equipments : equipments.data)" :key="item.id" class="text-gray-700">
                  <td class="border-b border-gray-200 bg-white px-2 py-1 text-xs text-center">{{ index + 1 }}</td>
                  <td class="border-b border-gray-200 bg-white px-2 py-1 text-xs text-center whitespace-nowrap">{{ formattedDate(item.huawei_entry.entry_date) }}</td>
                  <td class="border-b border-gray-200 bg-white px-2 py-1 text-xs text-center">{{ item.huawei_equipment_serie.huawei_equipment.claro_code }}</td>
                  <td class="border-b border-gray-200 px-2 py-1 text-xs text-center min-w-[200px]"
                  :class="{
                                        'bg-green-400': item.antiquation_state === 'Green',
                                        'bg-yellow-400': item.antiquation_state === 'Yellow',
                                        'bg-orange-400': item.antiquation_state === 'Orange',
                                        'bg-red-400': item.antiquation_state === 'Red',
                                        'bg-white': item.antiquation_state === 'none'
                                    }">{{ item.huawei_equipment_serie.huawei_equipment.name }}</td>
                  <td class="border-b border-gray-200 bg-white px-2 py-1 text-xs text-center">{{ item.huawei_equipment_serie.serie_number }}</td>
                  <td class="border-b border-gray-200 bg-white px-2 py-1 text-xs text-center">{{ item.assigned_site }}</td>
                  <td class="border-b border-gray-200 bg-white px-2 py-1 text-xs text-center">{{ item.instalation_state ? item.instalation_state : item.state }}</td>
                  <td class="border-b border-gray-200 bg-white px-2 py-1 text-xs text-center">{{  }}</td>
                  <td class="border-b border-gray-200 bg-white px-2 py-1 text-xs text-center">{{ item.assigned_diu }}</td>
                  <td class="border-b border-gray-200 bg-white px-2 py-1 text-xs text-center">{{ item.unit_price ? "S/. " + item.unit_price.toFixed(2) : '' }}</td>
                  <td class="border-b border-gray-200 bg-white px-2 py-1 text-xs text-center min-w-[200px]">{{ item.observation }}</td>
                </tr>
              </tbody>
            </table>
          </div>
          <div v-if="!props.search"
            class="flex flex-col items-center border-t bg-white px-4 py-3 xs:flex-row xs:justify-between">
            <pagination :links="props.equipments.links" />
          </div>
        </div>
      </div>
    </AuthenticatedLayout>
  </template>

  <script setup>
  import { Head, useForm, router } from '@inertiajs/vue3';
  import Pagination from '@/Components/Pagination.vue';
  import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
  import TextInput from '@/Components/TextInput.vue';
  import { formattedDate } from '@/utils/utils';

  const props = defineProps({
    equipments: Object,
    search: String,
  });

  const searchForm = useForm({
    searchTerm: props.search ? props.search : '',
  });

  const search = () => {
    if (searchForm.searchTerm == '') {
      router.visit(route('huawei.inventory.show', props.equipment ? { equipment: 1 } : {}));
    } else {
      router.visit(route('huawei.inventory.show.search', {
        request: searchForm.searchTerm,
        ...(props.equipment ? { equipment: 1 } : {})
      }));
    }
  };
  </script>
