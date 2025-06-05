<template>
    <Head title="Devoluciones Huawei" />
    <AuthenticatedLayout :redirectRoute="{route: 'huawei.inventory.show', params: {warehouse: 'Claro'}}">
      <template #header>
        {{ props.equipment ? 'Devolución de Equipos' : 'Devolución de Materiales' }}
      </template>
      <div class="min-w-full rounded-lg shadow">
        <div class="flex flex-col sm:flex-row gap-4 justify-between rounded-lg p-4">
            <!-- Sección de enlaces -->
            <div class="flex flex-col sm:flex-row gap-4 items-center sm:items-start">
                <div v-if="props.equipment">
                <Link :href="route('huawei.inventory.refunds', {warehouse: props.warehouse})" type="button" class="rounded-md bg-indigo-600 px-3 py-2 text-center text-white hover:bg-indigo-500">
                    Materiales
                </Link>
                </div>
                <div v-else>
                <Link :href="route('huawei.inventory.refunds', { warehouse: props.warehouse, equipment: 1 })" type="button" class="rounded-md bg-indigo-600 px-3 py-2 text-center text-white hover:bg-indigo-500">
                    Equipos
                </Link>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <p>Almacén</p>
                <select v-model="selectedWarehouse" id="code" @change="changeWarehouse($event.target.value)"
                    class="block w-full min-w-[150px] rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                    <option disabled>Seleccione Almacén</option>
                    <option v-for="(op, index) in props.data.operators" :key="index" :value="op">
                        {{ op }}
                    </option>
                </select>
            </div>


            <!-- Sección de búsqueda -->
            <div class="flex items-center mt-4 sm:mt-0 w-full sm:w-auto">
                <form @submit.prevent="search" class="flex items-center w-full sm:w-auto">
                <TextInput type="text" placeholder="Buscar..." v-model="searchForm.searchTerm" class="mr-2 flex-1" />
                <button type="submit" :class="{ 'opacity-25': searchForm.processing }"
                        class="ml-2 rounded-md bg-indigo-600 px-3 py-2 text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                    <svg width="20px" height="20px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
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
                            Nª de Pedido
                        </th>
                        <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 text-center">
                            Fecha de Pedido
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
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">{{ item.huawei_entry_detail.order_number }}</td>
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">{{ formattedDate(item.huawei_entry_detail.order_date) }}</td>
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">{{ item.huawei_entry_detail.huawei_entry?.guide_number }}</td>
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">{{ formattedDate(item.huawei_entry_detail.huawei_entry?.entry_date) }}</td>
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
                            Nª de Pedido
                            </th>
                            <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 text-center">
                            Fecha de Pedido
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
                                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">{{ item.huawei_entry_detail.order_number }}</td>
                                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">{{ formattedDate(item.huawei_entry_detail.order_date) }}</td>
                                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">{{ item.huawei_entry_detail.huawei_entry?.guide_number }}</td>
                                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">{{ formattedDate(item.huawei_entry_detail.huawei_entry?.entry_date) }}</td>
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
  import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
  import TextInput from '@/Components/TextInput.vue';
  import { formattedDate } from '@/utils/utils'
  import { ref } from 'vue';

  const props = defineProps({
    refunds: Object,
    equipment: String,
    search: String,
    warehouse: String,
    data: Object,
  });

  const selectedWarehouse = ref(props.warehouse);

  const hasPermission = (permission) => {
    return props.userPermissions.includes(permission);
  }

  const searchForm = useForm({
    searchTerm: props.search ? props.search : '',
  });

  const search = () => {
        if (searchForm.searchTerm == '') {
            if (props.equipment){
                router.visit(route('huawei.inventory.refunds', {warehouse: props.warehouse, equipment: 1}));
            } else {
                router.visit(route('huawei.inventory.refunds', {warehouse: props.warehouse}));
            }
        } else {
            if (props.equipment){
                router.visit(route('huawei.inventory.refunds.search', {warehouse: props.warehouse, request: searchForm.searchTerm, equipment: 1}));
            } else {
                router.visit(route('huawei.inventory.refunds.search', {warehouse: props.warehouse, request: searchForm.searchTerm}));
            }
        }
    }

   const changeWarehouse = (value) => {
    selectedWarehouse.value = value;
    if (props.search){
        if (props.equipment){
            router.visit(route('huawei.inventory.refunds.search', {warehouse: selectedWarehouse.value, request: props.search, equipment: 1}));
        }else{
            router.visit(route('huawei.inventory.refunds.search', {warehouse: selectedWarehouse.value, request: props.search}));
        }
    }else{
        if (props.equipment){
            router.visit(route('huawei.inventory.refunds', {warehouse: selectedWarehouse.value, equipment: 1}));
        }else{
            router.visit(route('huawei.inventory.refunds', {warehouse: selectedWarehouse.value}));
        }
    }
   }

  </script>
