<template>
    <Head title="Recursos del Proyecto" />
    <AuthenticatedLayout>
      <template #header>
        {{ props.equipment ? 'Equipos del Proyecto' : 'Materiales del Proyecto' }}
      </template>
      <div class="min-w-full rounded-lg shadow">
        <div class="flex gap-4 justify-between rounded-lg">
            <div class="flex flex-col sm:flex-row gap-4 justify-between w-full">
                <div class="flex gap-4 items-center">
                    <button @click="open_create" class="rounded-md bg-indigo-600 px-4 py-2 text-center text-sm text-white hover:bg-indigo-500 whitespace-nowrap">
                        + Agregar
                    </button>
                    <div v-if="props.equipment">
                        <Link :href="route('huawei.projects.resources', { huawei_project: props.huawei_project })" type="button" class="rounded-md bg-indigo-600 px-4 py-2 text-center text-sm text-white hover:bg-indigo-500">
                            Materiales
                        </Link>
                    </div>
                    <div v-else>
                        <Link :href="route('huawei.projects.resources', { huawei_project: props.huawei_project, equipment: 1 })" type="button" class="rounded-md bg-indigo-600 px-4 py-2 text-center text-sm text-white hover:bg-indigo-500">
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
                            Fecha de Salida de Almacén
                        </th>
                        <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 text-center">
                            Precio
                        </th>
                        <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 text-center">
                        </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in (props.search ? props.resources : resources.data)" :key="item.id" class="text-gray-700">
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">{{ item.huawei_entry_detail.huawei_equipment_serie.huawei_equipment.name }}</td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">{{ item.huawei_entry_detail.huawei_equipment_serie.serie_number }}</td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">{{ formattedDate(item.created_at) }}</td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">{{ item.huawei_entry_detail.unit_price ? 'S/. ' + item.huawei_entry_detail.unit_price : '-'}}</td>
                        </tr>
                    </tbody>
                    </table>
                </div>
                    <div v-if="!props.search"
                        class="flex flex-col items-center border-t bg-white px-5 py-5 xs:flex-row xs:justify-between">
                        <pagination :links="props.resources.links" />
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
                                Cantidad Asignada
                            </th>
                            <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 text-center">
                                Fecha de Salida de Almacén
                            </th>
                            <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 text-center">
                                Precio
                            </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="item in (props.search ? props.resources : resources.data)" :key="item.id" class="text-gray-700">
                                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">{{ item.huawei_entry_detail.huawei_material.name }}</td>
                                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">{{ item.quantity }}</td>
                                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">{{ formattedDate(item.created_at) }}</td>
                                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">{{ item.huawei_entry_detail.unit_price ? 'S/. ' + item.huawei_entry_detail.unit_price : '-'}}</td>
                            </tr>
                        </tbody>
                        </table>
                    </div>
                    <div v-if="!props.search"
                        class="flex flex-col items-center border-t bg-white px-5 py-5 xs:flex-row xs:justify-between">
                        <pagination :links="props.resources.links" />
                    </div>
                </div>
            </div>
      </div>

      <Modal :show="create_modal">
        <div class="p-6">
            <h2 class="text-base font-medium leading-7 text-gray-900">
                Asignar Recurso
            </h2>
            <form @submit.prevent="submit">
            <div class="space-y-12">
                <div class="border-b border-gray-900/10 pb-12">
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <InputLabel for="expense_type" class="font-medium leading-6 text-gray-900">{{ props.equipment ? 'Equipo': 'Material' }}</InputLabel>
                        <div class="mt-2">
                            <select v-model="form.resource" id="expense_type"
                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                <option disabled value="">Seleccionar {{ props.equipment ? 'Equipo' : 'Material' }}</option>
                                <option v-for="item in (props.equipment ? props.equipments : props.materials)" :key="item.id" :value="item.id">{{ item.name }}</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <InputLabel for="expense_type" class="font-medium leading-6 text-gray-900">{{ props.equipment ? 'Serie': 'Entrada' }}</InputLabel>
                        <div class="mt-2">
                            <select v-model="form.huawei_entry_detail_id" id="expense_type"
                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                <option disabled value="">Seleccionar {{ props.equipment ? 'Serie' : 'Entrada' }}</option>
                                <option v-for="item in filteredEntryDetails" :key="item.id" :value="item.id">
                                    {{ props.equipment ? (item.huawei_equipment_serie?.serie_number + ' - ' + (item.unit_price ? 'S/. ' + item.unit_price : 'No hay precio registrado')) : (item.huawei_material?.name + ' - ' + (item.unit_price ? 'S/. ' + item.unit_price : 'No hay precio registrado')) }}
                                </option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex items-center justify-end gap-x-6">
                    <SecondaryButton @click="close_create">
                    Cancelar
                    </SecondaryButton>
                    <button type="submit" :class="{ 'opacity-25': form.processing }"
                    class="rounded-md bg-indigo-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Guardar</button>
                </div>
                </div>
            </div>
            </form>
        </div>
        </Modal>

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
  import Modal from '@/Components/Modal.vue';
  import { ref, computed } from 'vue';
  import InputLabel from '@/Components/InputLabel.vue';
  import InputError from '@/Components/InputError.vue';
  import SecondaryButton from '@/Components/SecondaryButton.vue';

  const props = defineProps({
    resources: Object,
    equipment: String,
    huawei_project: String,
    equipments: [Object, null],
    materials: [Object, null],
    entry_details: Object,
    search: String
  });

  const create_modal = ref(false);

  const open_create = () => {
    create_modal.value = true;
  }

  const close_create = () => {
    create_modal.value = false;
  }

  const form = useForm({
    resource: '',
    huawei_entry_detail_id: '',
    quantity: ''
  });

  const filteredEntryDetails = computed(() => {
    if (props.equipment){
        return props.entry_details.filter(entry => entry.huawei_equipment_serie.huawei_equipment_id === form.resource);
    }else{
        return props.entry_details.filter(entry => entry.huawei_material_id === form.resource);
    }
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
