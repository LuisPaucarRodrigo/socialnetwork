<template>

  <Head title="Almacenes" />
  <AuthenticatedLayout :redirectRoute="'warehouses.warehouses'">
    <template #header>
      Almacenes
    </template>
    <div class="min-w-full p-3 rounded-lg shadow">
      <div class="flex gap-4">
        <button @click="openCreateWarehouseModal" type="button"
          class="inline-flex items-center px-4 py-2 border-2 border-gray-700 rounded-md font-semibold text-xs hover:text-gray-700 uppercase tracking-widest bg-gray-700 hover:underline hover:bg-gray-200 focus:border-indigo-600 focus:outline-none focus:ring-2 text-white">
          + Agregar
        </button>
      </div>
      <br>
      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-4 gap-3">
        <div
          class="bg-white p-3 rounded-md shadow-sm border border-gray-400 items-center">
          <div>
            <h2 class="text-sm font-semibold mb-3">
              ACTIVOS DE LA EMPRESA
            </h2>
          </div>
          <h3 class="text-sm font-semibold text-gray-700 line-clamp-1 mb-2">
            Ubicación: CCIP
          </h3>
          <h3 class="text-sm font-semibold text-gray-700 line-clamp-1 mb-2">
            Encargado: CCIP
          </h3>
          <div class="text-gray-500 text-sm">
            <div class="grid grid-cols-1 gap-y-1">
              <Link :href="route('resources.index')"
                class="text-blue-600 underline whitespace-no-wrap hover:text-purple-600">Ingresar</Link>
            </div>
          </div>
        </div>


      <div v-for="item in warehouses.data" :key="item.id"
        class="bg-white p-3 rounded-md shadow-sm border border-gray-300 items-center">
        <div class="grid grid-cols-2">
          <h2 class="text-sm font-semibold mb-3">
            {{ item.name }}
          </h2>
          <div class="inline-flex justify-end gap-x-0 mb-4">
            <div class="flex space-x-3 justify-center">
              <Link :href="route('warehouses.warehouse', { warehouse: item })" class="text-green-600 hover:underline">
              <EyeIcon class="h-4 w-4 ml-1" />
              </Link>
              <button v-if="auth.user.role_id == 1" @click="confirmDeleteWarehouse(item.id)" class="text-red-600 hover:underline">
                <TrashIcon class="h-4 w-4" />
              </button>
            </div>

          </div>
        </div>
        <h3 class="text-sm font-semibold text-gray-700 line-clamp-1 mb-2">
          Ubicación: {{ item.location }}
        </h3>
        <h3 class="text-sm font-semibold text-gray-700 line-clamp-1 mb-2">
          Encargado: {{ item.manager }}
        </h3>
        <div class="text-gray-500 text-sm">
          <div class="grid grid-cols-1 gap-y-1">
            <Link :href="route('warehouses.products', { warehouse: item })"
              class="text-blue-600 underline whitespace-no-wrap hover:text-purple-600">Productos</Link>
            <Link :href="route('warehouses.outputs', { warehouse: item.id })"
              class="text-blue-600 underline whitespace-no-wrap hover:text-purple-600">Salidas</Link>
          </div>
        </div>
      </div>
    </div>
    <br>
    <div class="flex flex-col items-center border-t px-5 py-5 xs:flex-row xs:justify-between">
      <pagination :links="warehouses.links" />
    </div>
    </div>

    <Modal :show="create_warehouse">
      <div class="p-6">
        <h2 class="text-base font-medium leading-7 text-gray-900">
          Agregar Almacén
        </h2>
        <form @submit.prevent="submit">
          <div class="space-y-12">
            <div class="border-b border-gray-900/10 pb-12">

              <div>
                <InputLabel for="name" class="font-medium leading-6 text-gray-900">Nombre</InputLabel>
                <div class="mt-2">
                  <input type="text" v-model="form.name" id="name"
                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                  <InputError :message="form.errors.name" />
                </div>
              </div>

              <div>
                <InputLabel for="location" class="font-medium leading-6 text-gray-900 mt-3">Ubicación</InputLabel>
                <div class="mt-2">
                  <input type="text" v-model="form.location" id="location"
                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                  <InputError :message="form.errors.location" />
                </div>
              </div>

              <div>
                <InputLabel for="manager" class="font-medium leading-6 text-gray-900 mt-3">Encargado</InputLabel>
                <div class="mt-2">
                  <input type="text" v-model="form.manager" id="manager"
                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                  <InputError :message="form.errors.manager" />
                </div>
              </div>

              <div class="mt-3">
                <InputLabel for="headers" class="font-medium leading-6 text-gray-900">Cabeceras
                </InputLabel>
                <select multiple v-model="form.header_ids" id="headers" size="10"
                  class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 mt-3">
                  <option disabled>
                    Selecciona una o varias
                  </option>
                  <option v-for="header in filteredHeaders" :key="header.id" :value="header.id">
                    {{ header.name }}
                  </option>
                </select>
                <InputError :message="form.errors.header_ids" />
              </div>

              <div class="mt-6 flex items-center justify-end gap-x-6">
                <SecondaryButton @click="closeModal"> Cancelar </SecondaryButton>
                <button type="submit" :class="{ 'opacity-25': form.processing }"
                  class="rounded-md bg-indigo-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Guardar</button>
              </div>
            </div>
          </div>
        </form>
      </div>
    </Modal>

    <ConfirmDeleteModal :confirmingDeletion="confirmingDocDeletion" itemType="Almacén" :deleteFunction="deleteWarehouse"
      @closeModal="closeModalDoc" />
    <ConfirmCreateModal :confirmingcreation="showModal" itemType="Almacén" />

  </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Pagination from '@/Components/Pagination.vue'
import ConfirmCreateModal from '@/Components/ConfirmCreateModal.vue';
import ConfirmDeleteModal from '@/Components/ConfirmDeleteModal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Modal from '@/Components/Modal.vue';
import { ref, onMounted } from 'vue';
import { Head, useForm, router, Link } from '@inertiajs/vue3';
import { TrashIcon, EyeIcon } from '@heroicons/vue/24/outline';

const props = defineProps({
  warehouses: Object,
  headers: Object,
  warehouse_headers: Object,
  auth: Object
});

const form = useForm({
  id: '',
  name: '',
  location: '',
  manager: '',
  header_ids: [],
});



const headerArray = [
  1, 5, 7, 8, 10, 12, 15, 29, 32
];

let filteredHeaders = [];

onMounted(() => {
  filteredHeaders = props.headers.filter(header => !headerArray.includes(header.id));
});

const create_warehouse = ref(false);
const showModal = ref(false);
const confirmingDocDeletion = ref(false);
const docToDelete = ref(null);

const openCreateWarehouseModal = () => {
  create_warehouse.value = true;
};

const closeModal = () => {
  create_warehouse.value = false;
};


const submit = () => {
  form.header_ids = [...headerArray, ...form.header_ids];
  form.post(route('warehouses.storeWarehouse'), {
    onSuccess: () => {
      closeModal();
      form.reset();
      showModal.value = true
      setTimeout(() => {
        showModal.value = false;
        router.visit(route('warehouses.warehouses'))
      }, 2000);
    },
    onError: () => {
      form.reset();
    },
    onFinish: () => {
      form.reset();
    }
  });
};

const confirmDeleteWarehouse = (warehouseId) => {
  docToDelete.value = warehouseId;
  confirmingDocDeletion.value = true;
};

const closeModalDoc = () => {
  confirmingDocDeletion.value = false;
};

const deleteWarehouse = () => {
  const docId = docToDelete.value;
  if (docId) {
    router.delete(route('warehouses.destroyWarehouse', { warehouse: docId }), {
      onSuccess: () => closeModalDoc()
    });
  }
};

</script>