<template>
    <Head title="Huawei" />
    <AuthenticatedLayout>
      <template #header>
        {{ props.equipment ? 'Equipos de Huawei' : 'Materiales de Huawei' }}
      </template>
      <div class="min-w-full rounded-lg shadow">
        <Link :href="route('huawei.inventory.create')" type="button" class="flex-shrink-0">
            + Agregar
        </Link>
        <div class="overflow-x-auto mt-3">
            <div v-if="props.equipment">
                <div>
                        <table class="w-full whitespace-no-wrap">
                        <thead>
                        <tr class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                            <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                            Descripción del producto</th>
                            <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                            Código de Claro</th>
                            <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                            Marca</th>
                            <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                            Modelo</th>
                            <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                            Cantidad</th>
                        </tr>
                        </thead>
                        <tbody>
                            <div>
                                <tr v-for="item in equipments.data" :key="item.id" class="text-gray-700">
                                    <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">{{ item.name }}</td>
                                    <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">{{ item.claro_code }}</td>
                                    <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">{{ item.brand_model.name }}</td>
                                    <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">{{ item.brand_model.brand.name }}</td>
                                    <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                    <div class="flex items-center">
                                        <button @click=""
                                        class="text-orange-400 hover:underline mr-2">
                                        <EyeIcon class="h-5 w-5" />
                                        </button>
                                    </div>
                                    </td>
                                </tr>
                            </div>
                        </tbody>
                    </table>
                    </div>
                    <div
                        class="flex flex-col items-center border-t bg-white px-5 py-5 xs:flex-row xs:justify-between">
                        <pagination :links="props.equipments.links" />
                    </div>
                </div>
                <div v-else>
                    <div>
                        <table class="w-full whitespace-no-wrap">
                        <thead>
                        <tr class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                            <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                            Descripción del producto</th>
                            <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                            Código de Claro</th>
                            <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                            Marca</th>
                            <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                            Modelo</th>
                            <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                            Cantidad</th>
                        </tr>
                        </thead>
                        <tbody>
                            <div>
                                <tr v-for="item in materials.data" :key="item.id" class="text-gray-700">
                                    <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">{{ item.name }}</td>
                                    <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">{{ item.claro_code }}</td>
                                    <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">{{ item.brand_model.name }}</td>
                                    <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">{{ item.brand_model.brand.name }}</td>
                                    <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                    <div class="flex items-center">
                                        <button @click=""
                                        class="text-orange-400 hover:underline mr-2">
                                        <EyeIcon class="h-5 w-5" />
                                        </button>
                                    </div>
                                    </td>
                                </tr>
                            </div>
                        </tbody>
                    </table>
                    </div>
                    <div
                        class="flex flex-col items-center border-t bg-white px-5 py-5 xs:flex-row xs:justify-between">
                        <pagination :links="props.materials.links" />
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
    materials: [Object, null],
    equipments: [Object, null],
    brand_models: Object,
    brands: Object,
    equipment: Boolean
  });

  const hasPermission = (permission) => {
    return props.userPermissions.includes(permission);
  }

  </script>
