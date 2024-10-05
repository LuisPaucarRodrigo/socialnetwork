<template>
    <Head title="Recursos del Proyecto para Liquidar" />
    <AuthenticatedLayout :redirectRoute="{route: 'huawei.projects', params: {status: backStatus, prefix: 'Claro'}}">
      <template #header>
        Recursos del Proyecto {{ props.project_name }}
      </template>
      <div class="min-w-full rounded-lg shadow">
        <div class="flex gap-4 items-center justify-between">
            <Link :href="route('huawei.projects.liquidations.history', {huawei_project: props.huawei_project})" type="button" class="rounded-md bg-indigo-600 px-4 py-2 text-center text-sm text-white hover:bg-indigo-500">
                Historial
            </Link>
        </div>
        <div>
    <div>
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
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="item in equipments.data" :key="item.id" class="text-gray-700">
                        <td class="w-1/4 border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">{{ item.huawei_entry_detail.huawei_equipment_serie.huawei_equipment.name }}</td>
                        <td class="w-1/4 border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">{{ item.huawei_entry_detail.huawei_equipment_serie.serie_number }}</td>
                        <td class="w-1/4 border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">{{ item.huawei_entry_detail.unit_price ? 'S/. ' + item.huawei_entry_detail.unit_price.toFixed(2) : '-'}}</td>
                        <td class="w-1/4 border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">
                            <div v-if="item.quantity !== 0 && props.projectState == 1" class="flex items-center justify-center">
                                <button @click.prevent="openLiquidateModal(item, true)" class="rounded-md bg-indigo-600 px-4 py-2 text-center text-sm text-white hover:bg-indigo-500 whitespace-nowrap">
                                    Liquidar
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="flex flex-col items-center border-t bg-white px-5 py-5 xs:flex-row xs:justify-between">
            <pagination :links="props.equipments.links" />
        </div>
    </div>

    <div>
        <h2 class="text-lg font-medium leading-7 text-gray-900 m-5">Materiales</h2>
        <div class="overflow-x-auto mt-3">
            <table class="w-full whitespace-no-wrap">
                <thead>
                    <tr class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                        <th class="w-1/4 border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                            Descripción del Material
                        </th>
                        <th class="w-1/4 border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                            Cantidad Restante en Proyecto
                        </th>
                        <th class="w-1/4 border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                            Precio
                        </th>
                        <th class="w-1/4 border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="item in materials.data" :key="item.id" class="text-gray-700">
                        <td class="w-1/4 border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">{{ item.huawei_entry_detail.huawei_material.name }}</td>
                        <td class="w-1/4 border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">{{ item.quantity }}</td>
                        <td class="w-1/4 border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">{{ item.huawei_entry_detail.unit_price ? 'S/. ' + item.huawei_entry_detail.unit_price.toFixed(2) : '-'}}</td>
                        <td class="w-1/4 border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">
                            <div v-if="item.quantity !== 0 && props.projectState == 1" class="flex items-center justify-center">
                                <button @click.prevent="openLiquidateModal(item, false)" class="rounded-md bg-indigo-600 px-4 py-2 text-center text-sm text-white hover:bg-indigo-500 whitespace-nowrap">
                                    Liquidar
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="flex flex-col items-center border-t bg-white px-5 py-5 xs:flex-row xs:justify-between">
            <pagination :links="props.materials.links" />
        </div>
    </div>
</div>

      </div>

      <Modal :show="liquidate_modal">
          <div class="p-6">
            <h2 class="text-base font-medium leading-7 text-gray-900">Liquidar Recurso</h2>
            <form @submit.prevent="liquidate" class="grid grid-cols-2 gap-3">
              <!-- Tercera Fila -->
              <div class="col-span-2 grid grid-cols-2 gap-3">
                <div v-if="liquidate_material" class="col-span-2">
                    <InputLabel class="mb-1" for="quantity">Cantidad a Liquidar</InputLabel>
                    <input type="number" min="0" :max="liquidate_resource_id.quantity" v-model="form.liquidated_quantity" class="block w-full py-1.5 rounded-md sm:text-sm form-input focus:border-indigo-600" />
                    <InputError :message="form.errors.liquidated_quantity" />
                </div>
                <div v-if="liquidate_material" class="col-span-2">
                    <InputLabel class="mb-1" for="quantity">Cantidad a devolver a Inventario</InputLabel>
                    <input type="number" disabled readonly min="0" v-model="refund_quantity" class="block w-full py-1.5 rounded-md sm:text-sm form-input focus:border-indigo-600" />
                </div>
                <div v-if="liquidate_equipment" class="col-span-2">
                    <div class="inline-flex items-center p-2 mt-4 text-sm text-yellow-800 rounded-lg bg-yellow-50 dark:bg-gray-800 dark:text-yellow-300" role="alert">
                        <svg class="flex-shrink-0 inline w-4 h-4 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
                        </svg>
                        <span class="sr-only">Info</span>
                        <div>
                           <span class="font-small">El número de serie del equipo seleccionado no será devuelto a inventario y no podrá ser usado de nuevo.</span>
                        </div>
                    </div>
                </div>
                <div class="col-span-2">
                    <InputLabel class="mb-1" for="instalation_date">Fecha de Instalación/Liquidación</InputLabel>
                    <input type="date" v-model="form.instalation_date" class="block w-full py-1.5 rounded-md sm:text-sm form-input focus:border-indigo-600" />
                    <InputError :message="form.errors.instalation_date" />
                </div>
              </div>

              <!-- Botones de Acción -->
              <div class="col-span-2 mt-2 flex items-center justify-end gap-x-6">
                <SecondaryButton @click="closeLiquidateModal">Cancelar</SecondaryButton>
                <PrimaryButton type="submit" :class="{ 'opacity-25': form.processing }">Aceptar</PrimaryButton>
              </div>
            </form>
          </div>
        </Modal>

        <SuccessOperationModal :confirming="showModal" :title="`Éxito`"
            :message="`La liquidación del recurso se realizó correctamente.`" />
    </AuthenticatedLayout>
  </template>

  <script setup>

  import { Head, Link, useForm } from '@inertiajs/vue3';
  import Pagination from '@/Components/Pagination.vue';
  import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
  import Modal from '@/Components/Modal.vue';
  import { ref, watch } from 'vue';
  import InputLabel from '@/Components/InputLabel.vue';
  import InputError from '@/Components/InputError.vue';
  import SecondaryButton from '@/Components/SecondaryButton.vue';
  import SuccessOperationModal from '@/Components/SuccessOperationModal.vue';
  import PrimaryButton from '@/Components/PrimaryButton.vue';

  const props = defineProps({
    equipments: Object,
    materials: Object,
    huawei_project: String,
    project_name: String,
    projectState: Number
  });

  const backStatus = props.projectState == 1 ? '1' : (props.projectState == null ? '2' : '3');

  const liquidate_modal = ref(false);
  const showModal = ref(false);
  const liquidate_material = ref(false);
  const liquidate_equipment = ref(false);
  const liquidate_resource_id = ref(null);
  const refund_quantity = ref(null);

  const form = useForm({
    liquidated_quantity: '',
    huawei_project_resource_id: '',
    instalation_date: ''
  });

  const hasPermission = (permission) => {
    return props.userPermissions.includes(permission);
  }

  const openLiquidateModal = (item, equipment) => {
    liquidate_resource_id.value = item;
    if (equipment){
        liquidate_equipment.value = true;
    }else{
        liquidate_material.value = true
    }
    liquidate_modal.value = true;
  }

  const closeLiquidateModal = () => {
    form.reset();
    form.clearErrors();
    liquidate_equipment.value = false;
    liquidate_material.value = false;
    liquidate_resource_id.value = null,
    liquidate_modal.value = false
  }

  const liquidate = () => {
    form.huawei_project_resource_id = liquidate_resource_id.value.id;
    const url = liquidate_equipment.value ? route('huawei.projects.liquidations.post', {huawei_project: props.huawei_project, equipment: 1}) : route('huawei.projects.liquidations.post', {huawei_project: props.huawei_project});
    form.post(url, {
        onSuccess: () => {
            closeLiquidateModal();
            showModal.value = true;
            setTimeout(() => {
                showModal.value = false;
            }, 2000);
        },
    })
  }

  watch([liquidate_resource_id, () => form.liquidated_quantity], ([resource, liquidatedQuantity]) => {
    if (resource && liquidatedQuantity !== '') {
        const remainingQuantity = resource.quantity - parseFloat(liquidatedQuantity);
        refund_quantity.value = remainingQuantity >= 0 ? remainingQuantity : 0;
    } else {
        refund_quantity.value = null;
    }
  });

  </script>
