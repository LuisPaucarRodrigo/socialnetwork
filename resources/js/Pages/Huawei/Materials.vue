<template>
    <Head title="Huawei" />
    <AuthenticatedLayout>
      <template #header>
        {{ props.equipment ? 'Equipos de Huawei' : 'Materiales de Huawei' }}
      </template>
      <div class="min-w-full rounded-lg shadow">
        <div class="flex flex-col gap-4 justify-center sm:flex-row sm:justify-between rounded-lg items-center text-center sm:text-left">
    <div class="flex flex-col sm:flex-row gap-4 w-full justify-between items-center">
        <div class="flex gap-4 items-center justify-center sm:justify-start">
            <!-- Botones grandes visibles solo en pantallas sm y superiores -->
            <Link :href="route('huawei.inventory.create')" type="button"
                class="hidden sm:block rounded-md bg-indigo-600 px-4 py-2 text-center text-sm text-white hover:bg-indigo-500 whitespace-nowrap">
                + Agregar
            </Link>
            <div v-if="props.equipment" class="hidden sm:block">
                <Link :href="route('huawei.inventory.show', { warehouse: props.warehouse })" type="button"
                    class="rounded-md bg-indigo-600 px-4 py-2 text-center text-sm text-white hover:bg-indigo-500">
                    Materiales
                </Link>
            </div>
            <div v-else class="hidden sm:block">
                <Link :href="route('huawei.inventory.show', { warehouse: props.warehouse, equipment: 1 })" type="button"
                    class="rounded-md bg-indigo-600 px-4 py-2 text-center text-sm text-white hover:bg-indigo-500">
                    Equipos
                </Link>
            </div>
            <Link :href="route('huawei.inventory.refunds', {warehouse: 'Claro'})" type="button"
                class="hidden sm:block rounded-md bg-indigo-600 px-4 py-2 text-center text-sm text-white hover:bg-indigo-500">
                Devoluciones
            </Link>
            <a :href="route('huawei.inventory.general.equipments')" type="button"
                class="hidden sm:block rounded-md bg-indigo-600 px-4 py-2 text-center text-sm text-white hover:bg-indigo-500">
                General
            </a>

            <!-- Menú desplegable visible en pantallas pequeñas -->
            <div class="sm:hidden">
                <dropdown align="left">
                    <template #trigger>
                        <button @click="dropdownOpen = !dropdownOpen"
                            class="relative block overflow-hidden rounded-md bg-gray-200 px-2 py-2 text-center text-sm text-white hover:bg-gray-100">
                            <svg width="25px" height="25px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M4 6H20M4 12H20M4 18H20" stroke="#000000" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>
                        </button>
                    </template>

                    <template #content class="origin-left">
                        <div>
                            <div class="dropdown">
                                <div class="dropdown-menu">
                                    <Link :href="route('huawei.inventory.create')" type="button"
                                        class="dropdown-item block w-full text-left px-4 py-2 text-sm text-black-700 hover:bg-indigo-600 hover:text-white focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                                        + Agregar
                                    </Link>
                                </div>
                            </div>
                            <div class="dropdown" v-if="props.equipment">
                                <div class="dropdown-menu">
                                    <Link :href="route('huawei.inventory.show', { warehouse: props.warehouse })" type="button"
                                        class="dropdown-item block w-full text-left px-4 py-2 text-sm text-black-700 hover:bg-indigo-600 hover:text-white focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                                        Materiales
                                    </Link>
                                    <Link :href="route('huawei.inventory.refunds', {warehouse: 'Claro'})" type="button"
                                        class="dropdown-item block w-full text-left px-4 py-2 text-sm text-black-700 hover:bg-indigo-600 hover:text-white focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                                        Devoluciones
                                    </Link>
                                    <a :href="route('huawei.inventory.general.equipments')" type="button"
                                        class="dropdown-item block w-full text-left px-4 py-2 text-sm text-black-700 hover:bg-indigo-600 hover:text-white focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                                        General
                                    </a>
                                </div>
                            </div>
                            <div class="dropdown" v-else>
                                <div class="dropdown-menu">
                                    <Link :href="route('huawei.inventory.show', { warehouse: props.warehouse, equipment: 1 })" type="button"
                                        class="dropdown-item block w-full text-left px-4 py-2 text-sm text-black-700 hover:bg-indigo-600 hover:text-white focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                                        Equipos
                                    </Link>
                                </div>
                                <div class="dropdown-menu">
                                    <Link :href="route('huawei.inventory.refunds', {warehouse: 'Claro'})" type="button"
                                        class="dropdown-item block w-full text-left px-4 py-2 text-sm text-black-700 hover:bg-indigo-600 hover:text-white focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                                        Devoluciones
                                    </Link>
                                    <a :href="route('huawei.inventory.general.equipments')" type="button"
                                        class="dropdown-item block w-full text-left px-4 py-2 text-sm text-black-700 hover:bg-indigo-600 hover:text-white focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                                        General
                                    </a>
                                </div>
                            </div>
                        </div>
                    </template>
                </dropdown>
            </div>
        </div>

        <!-- Select Almacén -->
        <div class="flex items-center gap-2">
            <p>Almacén</p>
            <select v-model="selectedWarehouse" id="code" @change="changeWarehouse($event.target.value)"
                class="block w-full min-w-[150px] rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                <option disabled>Seleccione Almacén</option>
                <option value="1">Claro</option>
                <option value="2">Entel</option>
            </select>
        </div>

        <!-- Formulario de búsqueda -->
        <div class="flex items-center justify-center sm:justify-end w-full sm:w-auto mt-4 sm:mt-0">
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
                            Código SAP
                        </th>
                        <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 text-center">
                            Marca
                        </th>
                        <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 text-center">
                            Modelo
                        </th>
                        <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 text-center">
                            Cantidad Disponible
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
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center min-w-[250px]">{{ item.name }}</td>
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">{{ item.claro_code }}</td>
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">{{ item.brand_model.brand.name }}</td>
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">{{ item.brand_model.name }}</td>
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">{{ item.available_quantity }}</td>
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
                    <div class="overflow-x-auto mt-3">
                        <table class="w-full whitespace-no-wrap">
                        <thead>
                            <tr class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                            <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 text-center">
                                Descripción del Material
                            </th>
                            <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 text-center">
                                Código SAP
                            </th>
                            <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 text-center">
                                Cantidad Disponible
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
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">{{ item.available_quantity }}</td>
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
  import Dropdown from '@/Components/Dropdown.vue';
  import { ref } from 'vue';

  const props = defineProps({
    materials: [Object, null],
    equipments: [Object, null],
    brand_models: Object,
    brands: Object,
    equipment: [String, null],
    search: String,
    warehouse: String
  });

  const selectedWarehouse = ref(props.warehouse);

  const hasPermission = (permission) => {
    return props.userPermissions.includes(permission);
  }

  const searchForm = useForm({
        searchTerm: props.search ? props.search : '',
    })

    const search = () => {
        if (searchForm.searchTerm == '') {
            if (props.equipment){
                router.visit(route('huawei.inventory.show', {warehouse: props.warehouse, equipment: 1}));
            } else {
                router.visit(route('huawei.inventory.show', {warehouse: props.warehouse}));
            }
        } else {
            if (props.equipment){
                router.visit(route('huawei.inventory.show.search', {warehouse: props.warehouse, request: searchForm.searchTerm, equipment: 1}));
            } else {
                router.visit(route('huawei.inventory.show.search', {warehouse: props.warehouse, request: searchForm.searchTerm}));
            }
        }
    }

    const changeWarehouse = (value) => {
        selectedWarehouse.value = value;
        if (props.search){
            if (props.equipment){
                router.visit(route('huawei.inventory.show.search', {warehouse: selectedWarehouse.value, request: props.search, equipment: 1}));
            } else {
                router.visit(route('huawei.inventory.show.search', {warehouse: selectedWarehouse.value, request: props.search}));
            }
        }else{
            if (props.equipment){
                router.visit(route('huawei.inventory.show', {warehouse: selectedWarehouse.value, equipment: 1}));
            }else{
                router.visit(route('huawei.inventory.show', {warehouse: selectedWarehouse.value}));
            }
        }
    }

  </script>
