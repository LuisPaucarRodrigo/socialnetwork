<template>
    <Head title="Detalles" :redirectRoute="'huawei.inventory.show'"/>
    <AuthenticatedLayout>
      <template #header>
        Detalles
      </template>
      <div class="min-w-full rounded-lg shadow">
        <div class="overflow-x-auto mt-3">
            <div v-if="props.equipment">
                <div>
                    <table class="w-full whitespace-no-wrap">
                    <thead>
                        <tr class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 text-center">
                                    Descripción del producto
                                </th>
                                <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 text-center">
                                    Cantidad Ingresada
                                </th>
                                <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 text-center">
                                    Número de Guía de Entrada
                                </th>
                                <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 text-center">
                                    Fecha de Entrada
                                </th>
                                <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 text-center">
                                    N° Serie Ingresada
                                </th>
                            </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in entries.data" :key="item.id" class="text-gray-700">
                                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">{{ item.huawei_equipment_serie.huawei_equipment.name }}</td>
                                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">{{ item.quantity }}</td>
                                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">{{ item.huawei_entry.guide_number }}</td>
                                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">{{ formattedDate(item.huawei_entry.entry_date) }}</td>
                                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">{{ item.huawei_equipment_serie.serie_number }}</td>
                            </tr>
                    </tbody>
                    </table>
                </div>
                    <div
                        class="flex flex-col items-center border-t bg-white px-5 py-5 xs:flex-row xs:justify-between">
                        <pagination :links="props.entries.links" />
                    </div>
                </div>
                <div v-else>
                    <div>
                        <table class="w-full whitespace-no-wrap">
                        <thead>
                            <tr class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 text-center">
                                    Descripción del producto
                                </th>
                                <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 text-center">
                                    Cantidad Ingresada
                                </th>
                                <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 text-center">
                                    Número de Guía de Entrada
                                </th>
                                <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 text-center">
                                    Fecha de Entrada
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="item in entries.data" :key="item.id" class="text-gray-700">
                                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">{{ item.huawei_material.name }}</td>
                                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">{{ item.quantity }}</td>
                                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">{{ item.huawei_entry.guide_number }}</td>
                                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">{{ formattedDate(item.huawei_entry.entry_date) }}</td>
                            </tr>
                        </tbody>
                        </table>
                    </div>
                    <div
                        class="flex flex-col items-center border-t bg-white px-5 py-5 xs:flex-row xs:justify-between">
                        <pagination :links="props.entries.links" />
                    </div>
                </div>
            </div>
      </div>

    </AuthenticatedLayout>
  </template>

  <script setup>

  import { Head, Link } from '@inertiajs/vue3';
  import Pagination from '@/Components/Pagination.vue';
  import { formattedDate } from '@/utils/utils'
  import { EyeIcon } from '@heroicons/vue/24/outline';
  import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
  import Modal from '@/Components/Modal.vue';
  import PrimaryButton from '@/Components/PrimaryButton.vue';
  import SecondaryButton from '@/Components/SecondaryButton.vue';
  import { ref } from 'vue';

  const props = defineProps({
    entries: Object,
    equipment: [String, null]
  });

  const hasPermission = (permission) => {
    return props.userPermissions.includes(permission);
  }



  </script>
