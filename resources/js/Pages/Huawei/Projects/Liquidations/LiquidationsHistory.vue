<template>
    <Head title="Liquidaciones" />
    <AuthenticatedLayout :redirectRoute="{route: 'huawei.projects.liquidations', params: {huawei_project: props.huawei_project}}">
      <template #header>
        Liquidaciones del Proyecto {{ props.project_name }}
      </template>
      <div class="min-w-full rounded-lg shadow">
        <div class="flex gap-4 items-center justify-between">
            <Link v-if="equipment" :href="route('huawei.projects.liquidations.history', {huawei_project: props.huawei_project})" type="button" class="rounded-md bg-indigo-600 px-4 py-2 text-center text-sm text-white hover:bg-indigo-500">
                Materiales
            </Link>
            <Link v-else :href="route('huawei.projects.liquidations.history', {huawei_project: props.huawei_project, equipment: 1})" type="button" class="rounded-md bg-indigo-600 px-4 py-2 text-center text-sm text-white hover:bg-indigo-500">
                Equipos
            </Link>
        </div>
        <div>
            <div v-if="props.equipment">
                <h2 class="text-lg font-medium leading-7 text-gray-900 m-5">Equipos</h2>
                <div class="overflow-x-auto mt-3">
                    <table class="w-full whitespace-no-wrap">
                        <thead>
                            <tr class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                <th class="w-1/4 border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                    Descripción del Equipo
                                </th>
                                <th class="w-1/4 border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                    Número de Serie
                                </th>
                                <th class="w-1/4 border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                    Precio
                                </th>
                                <th class="w-1/4 border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                    Fecha de Instalación/Liquidación
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="item in liquidations.data" :key="item.id" class="text-gray-700">
                                <td class="w-1/4 border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">{{ item.huawei_project_resource.huawei_entry_detail.huawei_equipment_serie.huawei_equipment.name }}</td>
                                <td class="w-1/4 border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">{{ item.huawei_project_resource.huawei_entry_detail.huawei_equipment_serie.serie_number }}</td>
                                <td class="w-1/4 border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">{{ item.huawei_project_resource.huawei_entry_detail.unit_price ? 'S/. ' + item.huawei_project_resource.huawei_entry_detail.unit_price.toFixed(2) : '-'}}</td>
                                <td class="w-1/4 border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">{{ formattedDate(item.instalation_date) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="flex flex-col items-center border-t bg-white px-5 py-5 xs:flex-row xs:justify-between">
                    <pagination :links="props.liquidations.links" />
                </div>
            </div>

            <div v-else>
                <h2 class="text-lg font-medium leading-7 text-gray-900 m-5">Materiales</h2>
                <div class="overflow-x-auto mt-3">
                    <table class="w-full whitespace-no-wrap">
                        <thead>
                            <tr class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                <th class="w-1/4 border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                    Descripción del Material
                                </th>
                                <th class="w-1/4 border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                    Precio
                                </th>
                                <th class="w-1/4 border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                    Cantidad Liquidada
                                </th>
                                <th class="w-1/4 border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                    Fecha de Instalación/Liquidación
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="item in liquidations.data" :key="item.id" class="text-gray-700">
                                <td class="w-1/4 border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">{{ item.huawei_project_resource.huawei_entry_detail.huawei_material.name }}</td>
                                <td class="w-1/4 border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">{{ item.huawei_project_resource.huawei_entry_detail.unit_price ? 'S/. ' + item.huawei_project_resource.huawei_entry_detail.unit_price.toFixed(2) : '-'}}</td>
                                <td class="w-1/4 border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">{{ item.liquidated_quantity }}</td>
                                <td class="w-1/4 border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">{{ formattedDate(item.instalation_date) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="flex flex-col items-center border-t bg-white px-5 py-5 xs:flex-row xs:justify-between">
                    <pagination :links="props.liquidations.links" />
                </div>
            </div>
        </div>
      </div>

    </AuthenticatedLayout>
  </template>

  <script setup>

  import { Head, Link } from '@inertiajs/vue3';
  import Pagination from '@/Components/Pagination.vue';
  import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
  import { formattedDate } from '@/utils/utils'

  const props = defineProps({
    liquidations: Object,
    huawei_project: String,
    equipment: String,
    project_name: String
  });

  const hasPermission = (permission) => {
    return props.userPermissions.includes(permission);
  }

  </script>
