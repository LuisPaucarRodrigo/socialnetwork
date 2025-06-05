<template>

  <Head title="Almacenes" />
  <AuthenticatedLayout :redirectRoute="'warehouses.warehouses'">
    <template #header>
      Almacenes
    </template>
    <div class="min-w-full p-3 rounded-lg shadow">
      <div class="flex gap-4">
      </div>
      <br>
      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-4 gap-3">

        <!-- SPECIAL WAREHOUSES -->
        <div v-for="item in special_warehouses" :key="item.id"
          class="bg-white p-3 rounded-md shadow-sm border border-gray-300 items-center">
          <div class="grid grid-cols-2">
            <h2 class="text-sm font-semibold mb-3">
              {{ item.name }}
            </h2>
            <div class="inline-flex justify-end gap-x-0 mb-4">
              <div class="flex space-x-3 justify-center">
                <button @click="informationWarehouse(item)">
                  <ShowIcon />
                </button>
              </div>
            </div>
          </div>
          <h3 class="text-sm font-semibold text-gray-700 line-clamp-1 mb-2">
            Descripción: {{ item.description }}
          </h3>
          <h3 class="text-sm font-semibold text-gray-700 line-clamp-1 mb-2">
            Encargado: {{ item.person_in_charge }}
          </h3>
          <div class="text-gray-500 text-sm">
            <div class="grid grid-cols-1 gap-y-1">
              <Link :href="route('inventory.special_products.index', { warehouse_id: item.id })"
                class="text-blue-600 underline whitespace-no-wrap hover:text-purple-600">Productos</Link>
              <Link :href="route('inventory.special_dispatch.index', { warehouse_id: item.id })"
                class="text-blue-600 underline whitespace-no-wrap hover:text-purple-600">Despachos</Link>
              <Link :href="route('inventory.special_refund.index',
                { warehouse_id: item.id })" class="text-blue-600 underline whitespace-no-wrap hover:text-purple-600">
              Devoluciones</Link>
            </div>
          </div>
        </div>


        <div v-for="item in warehouses" :key="item.id"
          class="bg-white p-3 rounded-md shadow-sm border border-gray-300 items-center">
          <div class="grid grid-cols-2">
            <h2 class="text-sm font-semibold mb-3">
              {{ item.name }}
            </h2>
            <div class="inline-flex justify-end gap-x-0 mb-4">
              <div class="flex space-x-3 justify-center">
                <button @click="informationWarehouse(item)">
                  <ShowIcon />
                </button>
              </div>
            </div>
          </div>
          <h3 class="text-sm font-semibold text-gray-700 line-clamp-1 mb-2">
            Descripción: {{ item.description }}
          </h3>
          <h3 class="text-sm font-semibold text-gray-700 line-clamp-1 mb-2">
            Encargado: {{ item.person_in_charge }}
          </h3>
          <div class="text-gray-500 text-sm">
            <div v-if="item.id === 3 || item.id === 4" class="grid grid-cols-1 gap-y-1">
              <Link
                :href="item.id === 3 ? route('warehouses.purchaseorders.approve', { warehouse: item.id }) : route('inventory.retrieval.entry.index')"
                class="text-blue-600 underline whitespace-no-wrap hover:text-purple-600">
              {{ item.id === 3 ? 'Compras' : 'Ingresos' }}</Link>
              <Link
                :href="item.id === 3 ? route('warehouses.conproco.products', { warehouse: item.id }) : route('inventory.retrieval.product.index')"
                class="text-blue-600 underline whitespace-no-wrap hover:text-purple-600">Productos</Link>
              <Link
                :href="item.id === 3 ? route('warehouses.dispatches', { warehouse: item.id }) : route('inventory.retrievalDispatch.index')"
                class="text-blue-600 underline whitespace-no-wrap hover:text-purple-600">Despachos</Link>
            </div>
            <div v-else-if="item.id === 5 || item.id === 6" class="grid grid-cols-1 gap-y-1">
              <Link v-if="item.id === 5" :href="route('warehouses.resource')"
                class="text-blue-600 underline whitespace-no-wrap hover:text-purple-600">
              Compras</Link>
              <Link v-if="item.id === 5" :href="route('warehouses.resource.active.index')"
                class="text-blue-600 underline whitespace-no-wrap hover:text-purple-600">Activos</Link>
              <Link v-if="item.id === 6" :href="route('warehouses.service.approve.index')"
                class="text-blue-600 underline whitespace-no-wrap hover:text-purple-600">
              Servicios</Link>
            </div>
          </div>
        </div>
      </div>
    </div>
    <Modal :show="showInformationWarehouse" :maxWidth="'md'">
      <div class="p-6">
        <h2 class="text-lg font-medium text-gray-900">
          Información de Almacen
        </h2>
        <div class="mt-4">
          <p class="text-sm text-gray-600"><span class="font-medium">Nombre:</span>
            {{ information.name }}</p>
          <p class="text-sm text-gray-600"><span class="font-medium">Descripcion:</span>
            {{ information.description }}</p>
          <p class="text-sm text-gray-600"><span class="font-medium">Encargado:</span>
            {{ information.person_in_charge }}
          </p>
          <p class="text-sm text-gray-600"><span class="font-medium">Categoria:</span>
            {{ information.category }}
          </p>
          <div class="mt-6 flex justify-end">
            <PrimaryButton @click="closeInformationWarehouse">Cerrar</PrimaryButton>
          </div>
        </div>
      </div>
    </Modal>
  </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import Modal from '@/Components/Modal.vue';
import { ref } from 'vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { ShowIcon } from "@/Components/Icons/Index";

const props = defineProps({
  warehouses: Object,
  special_warehouses: Object,
  auth: Object
});

const showInformationWarehouse = ref(false)
const information = ref([])

function informationWarehouse(item) {
  showInformationWarehouse.value = true
  information.value = item
}

function closeInformationWarehouse() {
  showInformationWarehouse.value = false
}
</script>
