<template>
    <Head title="Devoluciones Huawei" />
    <AuthenticatedLayout :redirectRoute="'huawei.inventory.show'">
      <template #header>
        {{ props.equipment ? 'Devolución de Equipos' : 'Devolución de Materiales' }}
      </template>
      <div class="min-w-full rounded-lg shadow">
        <div class="flex gap-4 justify-between rounded-lg">
            <div class="flex flex-col sm:flex-row gap-4 justify-between w-full">
                <div class="flex gap-4 items-center">
                    <div v-if="props.equipment">
                        <Link :href="route('huawei.inventory.refunds')" type="button" class="rounded-md bg-indigo-600 px-4 py-2 text-center text-sm text-white hover:bg-indigo-500">
                            Materiales
                        </Link>
                    </div>
                    <div v-else>
                        <Link :href="route('huawei.inventory.refunds', { equipment: 1 })" type="button" class="rounded-md bg-indigo-600 px-4 py-2 text-center text-sm text-white hover:bg-indigo-500">
                            Equipos
                        </Link>
                    </div>
                </div>

            </div>
            <div class="flex items-center ml-auto sm:ml-0"> <!-- ml-auto para alinear a la derecha en pantallas grandes y sm:ml-0 para mantener en la izquierda en pantallas pequeñas -->
                <form @submit.prevent="search" class="flex items-center w-full sm:w-auto">
                    <TextInput type="text" placeholder="Buscar..." v-model="searchForm.searchTerm" class="mr-2" />
                    <button type="submit" :class="{ 'opacity-25': searchForm.processing }"
                            class="ml-2 rounded-md bg-indigo-600 px-2 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                        <svg width="30px" height="21px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M15.7955 15.8111L21 21M18 10.5C18 14.6421 14.6421 18 10.5 18C6.35786 18 3 14.6421 3 10.5C3 6.35786 6.35786 3 10.5 3C14.6421 3 18 6.35786 18 10.5Z"
                                stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </button>
                </form>
            </div>
        </div>


        <div>
            <div v-if="props.equipment">
                <div class="overflow-x-auto mt-3">
                    <table class="w-full whitespace-no-wrap">
                    <thead>
                        <tr class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                        <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 text-center">
                            Descripción del Equipo
                        </th>
                        <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 text-center">
                            Número de Serie
                        </th>
                        <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 text-center">
                            Guía de Entrada
                        </th>
                        <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 text-center">
                            Fecha de Entrada
                        </th>
                        <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 text-center">
                            Fecha de Devolución
                        </th>
                        <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 text-center">
                            Observaciones
                        </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in (props.search ? props.refunds : refunds.data)" :key="item.id" class="text-gray-700">
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">{{ item.huawei_entry_detail.huawei_equipment_serie.huawei_equipment.name }}</td>
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">{{ item.huawei_entry_detail.huawei_equipment_serie.serie_number }}</td>
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">{{ item.huawei_entry_detail.huawei_entry.guide_number }}</td>
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">{{ formattedDate(item.huawei_entry_detail.huawei_entry.entry_date) }}</td>
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">{{ formattedDate(item.created_at) }}</td>
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">{{ item.observation }}</td>
                        </tr>
                    </tbody>
                    </table>
                </div>
                    <div v-if="!props.search"
                        class="flex flex-col items-center border-t bg-white px-5 py-5 xs:flex-row xs:justify-between">
                        <pagination :links="props.refunds.links" />
                    </div>
                </div>
                <div v-else>
                    <div class="overflow-x-auto mt-3">
                        <table class="w-full whitespace-no-wrap">
                        <thead>
                            <tr class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                            <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 text-center">
                                Descripción del Material
                            </th>
                            <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 text-center">
                                Cantidad Devuelta
                            </th>
                            <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 text-center">
                            Guía de Entrada
                            </th>
                            <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 text-center">
                                Fecha de Entrada
                            </th>
                            <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 text-center">
                                Fecha de Devolución
                            </th>
                            <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 text-center">
                                Observaciones
                            </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="item in (props.search ? props.refunds : refunds.data)" :key="item.id" class="text-gray-700">
                                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">{{ item.huawei_entry_detail.huawei_material.name }}</td>
                                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">{{ item.quantity }}</td>
                                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">{{ item.huawei_entry_detail.huawei_entry.guide_number }}</td>
                                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">{{ formattedDate(item.huawei_entry_detail.huawei_entry.entry_date) }}</td>
                                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">{{ formattedDate(item.created_at) }}</td>
                                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">{{ item.observation }}</td>
                            </tr>
                        </tbody>
                        </table>
                    </div>
                    <div v-if="!props.search"
                        class="flex flex-col items-center border-t bg-white px-5 py-5 xs:flex-row xs:justify-between">
                        <pagination :links="props.refunds.links" />
                    </div>
                </div>
            </div>
      </div>


    </AuthenticatedLayout>
  </template>

  <script setup>

  import { Head, Link, useForm, router } from '@inertiajs/vue3';
  import Pagination from '@/Components/Pagination.vue';
  import { EyeIcon } from '@heroicons/vue/24/outline';
  import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
  import TextInput from '@/Components/TextInput.vue';
  import Dropdown from '@/Components/Dropdown.vue';
  import { formattedDate } from '@/utils/utils'

  const props = defineProps({
    refunds: Object,
    equipment: String,
    search: String
  });

  const hasPermission = (permission) => {
    return props.userPermissions.includes(permission);
  }

  const searchForm = useForm({
    searchTerm: props.search ? props.search : '',
  });

  const search = () => {
        if (searchForm.searchTerm == '') {
            if (props.equipment){
                router.visit(route('huawei.inventory.refunds', {equipment: 1}));
            } else {
                router.visit(route('huawei.inventory.refunds'));
            }
        } else {
            if (props.equipment){
                router.visit(route('huawei.inventory.refunds.search', {request: searchForm.searchTerm, equipment: 1}));
            } else {
                router.visit(route('huawei.inventory.refunds.search', {request: searchForm.searchTerm}));
            }
        }
    }

  </script>
