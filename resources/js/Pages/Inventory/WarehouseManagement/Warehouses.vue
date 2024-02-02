<template>
    <Head title="Gestion de Almacenes" />
    <AuthenticatedLayout>
      <template #header>
        Gestión de almacenes
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
                <div v-for="item in warehouses.data" :key="item.id"
                    class="bg-white p-3 rounded-md shadow-sm border border-gray-300 items-center">
                    <div class="grid grid-cols-2">
                        <h2 class="text-sm font-semibold mb-3">
                            {{ item.name }}
                        </h2>
                        <div class="inline-flex justify-end gap-x-0 mb-4">
                            <!-- <Link class="flex items-start">
                            <EyeIcon class="h-4 w-4 text-blue-700" />
                            </Link> -->
                            <Link :href="route('warehouses.warehouse', {warehouse: item})" class="text-green-600 hover:underline"><EyeIcon class="h-4 w-4 ml-1" /></Link>
                            <button @click="openEditWarehouseModal(item)" class="text-orange-400 hover:underline mx-2"><PencilIcon class="h-4 w-4 ml-1" /></button>
                            <button @click="confirmDeleteWarehouse(item.id)" class="text-red-600 hover:underline"><TrashIcon class="h-4 w-4" /></button>
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
                            <!-- <Link class="text-blue-600 underline whitespace-no-wrap hover:text-purple-600">Tareas</Link> -->
                            <Link
                                :href="route('warehouses.products', {warehouse: item})"
                                class="text-blue-600 underline whitespace-no-wrap hover:text-purple-600">Productos</Link>
                            <Link
                                :href="route('warehouses.outputs', {warehouse: item.id})"
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
                <!--<div>
                    <div v-for="(headerId, index) in selectedHeaders" :key="index">
                    <InputLabel for="header" class="text-gray-700 mt-3">Cabecera {{ index + 1 }}:</InputLabel>
                    <select v-model="form.header_ids[index]" id="header" class="border rounded-md px-3 py-2 mb-3 w-full">
                        <option value="">Seleccionar Cabecera</option>
                        <option v-for="header in props.headers" :key="header.id" :value="header.id">{{ header.name }}</option>
                    </select>

                    <button @click.prevent="removeHeaderField(index)" class="rounded-md bg-red-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Eliminar Cabecera</button>
                    </div>

                    <button @click.prevent="addHeaderField" class="rounded-md bg-indigo-600 mt-2 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Añadir Cabecera</button>

                </div>-->

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

      <Modal :show="editWarehouseModal">
        <div class="p-6">
          <h2 class="text-base font-medium leading-7 text-gray-900">
            Editar Alamcén
          </h2>
          <form @submit.prevent="submitEdit">
            <div class="space-y-12">
              <div class="border-b border-gray-900/10 pb-12">

                <div>
                  <InputLabel for="name" class="font-medium leading-6 text-gray-900">Nombre</InputLabel>
                  <div class="mt-2">
                    <input type="text" v-model="formEdit.name" id="name"
                      class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                    <InputError :message="formEdit.errors.name" />
                  </div>
                </div>

                <div>
                  <InputLabel for="location" class="font-medium leading-6 text-gray-900 mt-3">Ubicación</InputLabel>
                  <div class="mt-2">
                    <input type="text" v-model="formEdit.location" id="location"
                      class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                    <InputError :message="formEdit.errors.location" />
                  </div>
                </div>

                <div>
                  <InputLabel for="manager" class="font-medium leading-6 text-gray-900 mt-3">Encargado</InputLabel>
                  <div class="mt-2">
                    <input type="text" v-model="formEdit.manager" id="manager"
                      class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                    <InputError :message="formEdit.errors.manager" />
                  </div>
                </div>
                
                <div class="mt-3">
                  <InputLabel for="headers" class="font-medium leading-6 text-gray-900">Cabeceras</InputLabel>
                  <select multiple v-model="formEdit.header_ids" id="headers" class="block w-full ...">
                    <option disabled>Selecciona una o varias</option>
                    <option v-for="header in filteredHeaders" :key="header.id" :value="header.id" >
                      {{ header.name }}
                    </option>

                  </select>
                  <InputError :message="formEdit.errors.header_ids" />
                </div>

                <div class="mt-6 flex items-center justify-end gap-x-6">
                  <SecondaryButton @click="closeEditModal"> Cancelar </SecondaryButton>
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
      <ConfirmUpdateModal :confirmingupdate="showModalEdit" itemType="Almacén" />
    </AuthenticatedLayout>
  </template>
    
  <script setup>
  import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
  import Pagination from '@/Components/Pagination.vue'
  import ConfirmCreateModal from '@/Components/ConfirmCreateModal.vue';
  import ConfirmUpdateModal from '@/Components/ConfirmUpdateModal.vue';
  import ConfirmDeleteModal from '@/Components/ConfirmDeleteModal.vue';
  import SecondaryButton from '@/Components/SecondaryButton.vue';
  import InputError from '@/Components/InputError.vue';
  import InputLabel from '@/Components/InputLabel.vue';
  import Modal from '@/Components/Modal.vue';
  import { ref, computed, watch, onMounted } from 'vue';
  import { Head, useForm, router, Link } from '@inertiajs/vue3';
  import { TrashIcon, PencilIcon, EyeIcon } from '@heroicons/vue/24/outline';

  const props = defineProps({
    warehouses: Object,
    headers: Object,
    warehouse_headers: Object
});
  
  const form = useForm({
    id: '',
    name: '',
    location: '',
    manager: '',
    header_ids: [],
  });

  const formEdit = useForm({
    id: '',
    name: '',
    location: '',
    manager: '',
    header_ids: [],
    headers: null
  });

  const headerArray = [
    1, 5, 7, 8, 10, 12, 15, 29
  ];

  let filteredHeaders = [];

  onMounted(() => {
    // Filtra los headers y elimina los que tienen id en headerArray
    filteredHeaders = props.headers.filter(header => !headerArray.includes(header.id));
  });
  
  const create_warehouse = ref(false);
  const showModal = ref(false);
  const showModalEdit = ref(false);
  const confirmingDocDeletion = ref(false);
  const docToDelete = ref(null);
  const editWarehouseModal = ref(false);
  const editingWarehouse = ref(null);
  
  const openCreateWarehouseModal = () => {
    create_warehouse.value = true;
  };

  const openEditWarehouseModal = (warehouse) => {
    // Copia de los datos de la subsección existente al formulario
    editingWarehouse.value = JSON.parse(JSON.stringify(warehouse));
    formEdit.id = editingWarehouse.value.id;
    formEdit.name = editingWarehouse.value.name;
    formEdit.location = editingWarehouse.value.location;

    formEdit.manager = editingWarehouse.value.manager;

    // Filtra los warehouse_headers asociados al almacén específico
    const warehouseHeaders = props.warehouse_headers.filter((wh) => wh.warehouse_id === warehouse.id);

    // Almacena directamente los objetos de cabecera en formEdit.headers
    formEdit.headers = warehouseHeaders.map((wh) => wh.header);

    // Almacena las ids de los headers en formEdit.header_ids
    formEdit.header_ids = formEdit.headers.map((header) => header.id);

    // Imprime los datos para depuración
    console.log(formEdit.headers);
    console.log(formEdit.header_ids);

    // Abre el modal de edición
    editWarehouseModal.value = true;
  };

    
  const closeModal = () => {
    create_warehouse.value = false;
  };

  const closeEditModal = () => {
    // Restablecer los valores del formulario
    form.reset();

    // Cerrar el modal de edición
    editWarehouseModal.value = false;
  };

  
  const submit = () => {
    console.log(form)
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

  const submitEdit = () => {
    console.log(formEdit.header_ids);
    formEdit.header_ids = [...headerArray, ...formEdit.header_ids];
    formEdit.put(route('warehouses.updateWarehouse', {warehouse: formEdit.id}, formEdit), {
      onSuccess: () => {
        closeModal();
        formEdit.reset();
        showModalEdit.value = true
        setTimeout(() => {
          showModalEdit.value = false;
          router.visit(route('warehouses.warehouses'))
        }, 2000);
      },
      onError: () => {
        formEdit.reset();
      },
      onFinish: () => {
        formEdit.reset();
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
  
    