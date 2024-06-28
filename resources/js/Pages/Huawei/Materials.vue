<template>
    <Head title="Huawei" />
    <AuthenticatedLayout>
      <template #header>
        {{ props.equipment ? 'Equipos de Huawei' : 'Materiales de Huawei' }}
      </template>
      <div class="min-w-full rounded-lg shadow">
        <div class="flex justify-between items-center">
            <div class="flex">
                <Link :href="route('huawei.inventory.create')" type="button" class="flex-shrink-0 rounded-md bg-indigo-600 px-4 py-2 mr-2 text-center text-sm text-white hover:bg-indigo-500">
                    + Agregar
                </Link>
                <div>
                    <Link v-if="props.equipment" :href="route('huawei.inventory.show')" type="button" class="rounded-md bg-indigo-600 px-4 py-2 text-center text-sm text-white hover:bg-indigo-500">
                        Materiales
                    </Link>
                    <Link v-else :href="route('huawei.inventory.show', {equipment: 1})" type="button" class="rounded-md bg-indigo-600 px-4 py-2 text-center text-sm text-white hover:bg-indigo-500">
                        Equipos
                    </Link>
                </div>
            </div>
            <div class="flex items-center">
                <form @submit.prevent="search" class="flex items-center">
                    <TextInput type="text" placeholder="Buscar..." v-model="searchForm.searchTerm" class="mr-2" /> <!-- Añadí mr-2 para separación del botón -->
                    <button type="submit"
                        :class="{ 'opacity-25': searchForm.processing }"
                        class="rounded-md bg-indigo-600 px-2 py-2 whitespace-no-wrap text-white shadow-sm hover:bg-indigo-500 focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-600 focus-visible:border-transparent">
                        <svg width="30px" height="21px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M15.7955 15.8111L21 21M18 10.5C18 14.6421 14.6421 18 10.5 18C6.35786 18 3 14.6421 3 10.5C3 6.35786 6.35786 3 10.5 3C14.6421 3 18 6.35786 18 10.5Z"
                                stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </button>
                </form>
            </div>
        </div>


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
                            Código de Claro
                        </th>
                        <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 text-center">
                            Marca
                        </th>
                        <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 text-center">
                            Modelo
                        </th>
                        <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 text-center">
                            Cantidad
                        </th>

                        <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 text-center">
                        </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in (props.search ? props.equipments : equipments.data)" :key="item.id" class="text-gray-700">
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">{{ item.name }}</td>
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">{{ item.claro_code }}</td>
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">{{ item.brand_model.name }}</td>
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">{{ item.brand_model.brand.name }}</td>
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">{{ item.quantity }}</td>
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">
                            <div class="flex items-center">
                                <Link :href="route('huawei.inventory.show.details', {id: item.id, equipment: 1})"
                                        class="text-green-600 hover:underline">
                                    <EyeIcon class="h-5 w-5" />
                                </Link>
                            </div>
                        </td>
                        </tr>
                    </tbody>
                    </table>
                </div>
                    <div v-if="!props.search"
                        class="flex flex-col items-center border-t bg-white px-5 py-5 xs:flex-row xs:justify-between">
                        <pagination :links="props.equipments.links" />
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
                                Código de Claro
                            </th>
                            <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 text-center">
                                Marca
                            </th>
                            <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 text-center">
                                Modelo
                            </th>
                            <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 text-center">
                                Cantidad
                            </th>
                            <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 text-center">
                            </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="item in (props.search ? props.materials : materials.data)" :key="item.id" class="text-gray-700">
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">{{ item.name }}</td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">{{ item.claro_code }}</td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">{{ item.brand_model.name }}</td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">{{ item.brand_model.brand.name }}</td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">{{ item.quantity }}</td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">
                                <div class="flex items-center text-center">
                                <Link :href="route('huawei.inventory.show.details', {id: item.id})"
                                        class="text-green-600 hover:underline">
                                    <EyeIcon class="h-5 w-5" />
                                </Link>
                                </div>
                            </td>
                            </tr>
                        </tbody>
                        </table>
                    </div>
                    <div v-if="!props.search"
                        class="flex flex-col items-center border-t bg-white px-5 py-5 xs:flex-row xs:justify-between">
                        <pagination :links="props.materials.links" />
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

  const props = defineProps({
    materials: [Object, null],
    equipments: [Object, null],
    brand_models: Object,
    brands: Object,
    equipment: [String, null],
    search: String
  });

  const hasPermission = (permission) => {
    return props.userPermissions.includes(permission);
  }

  const searchForm = useForm({
        searchTerm: props.search ? props.search : '',
    })

    const search = () => {
        if (searchForm.searchTerm == '') {
            if (props.equipment){
                router.visit(route('huawei.inventory.show', {equipment: 1}));
            } else {
                router.visit(route('huawei.inventory.show'));
            }
        } else {
            if (props.equipment){
                router.visit(route('huawei.inventory.show.search', {request: searchForm.searchTerm, equipment: 1}));
            } else {
                router.visit(route('huawei.inventory.show.search', {request: searchForm.searchTerm}));
            }
        }
    }

  </script>
