<template>
    <Head title="Gestion de Almacenes" />
    <AuthenticatedLayout>
      <template #header>
        Gestión de almacenes
      </template>
      <div class="inline-block min-w-full overflow-hidden rounded-lg shadow">
        <div class="flex gap-4">
          <button @click="openCreateWarehouseModal" type="button"
            class="rounded-md bg-indigo-600 px-4 py-2 text-center text-sm text-white hover:bg-indigo-500">
            + Agregar Alamcén
          </button>
          <button @click="management_headers" type="button"
            class="rounded-md bg-indigo-600 px-4 py-2 text-center text-sm text-white hover:bg-indigo-500">
            Gestionar Cabeceras
          </button>
        </div>
      </div>

    <!-- Tabla para mostrar las subsecciones -->
    <div class="mt-5">
    <div class="overflow-x-auto mt-3">
        <table class="w-full whitespace-no-wrap">
        <thead>
            <tr class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
            <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">Nombre</th>
            <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">Ubicación</th>
            <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">Capacidad</th>
            <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">Acciones</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="warehouse in props.warehouses" :key="warehouse.id" class="text-gray-700">
            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">{{ warehouse.name }}</td>
            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">{{ warehouse.location }}</td>
            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">{{ warehouse.capacity }}</td>
            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                <div class="flex items-center">
                    <Link :href="route('warehouses.warehouse', {warehouse: warehouse})" class="text-green-600 hover:underline mr-2"><EyeIcon class="h-4 w-4 ml-1" /></Link>
                    <button @click="openEditWarehouseModal(warehouse)" class="text-orange-200 hover:underline mr-2"><PencilIcon class="h-4 w-4 ml-1" /></button>
                    <button @click="confirmDeleteWarehouse(warehouse.id)" class="text-red-600 hover:underline"><TrashIcon class="h-4 w-4" /></button>
                </div>
            </td>
            </tr>
        </tbody>
        </table>
    </div>
    </div>

  
      <Modal :show="create_warehouse">
        <div class="p-6">
          <h2 class="text-base font-medium leading-7 text-gray-900">
            Agregar Alamcén
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
                  <InputLabel for="capacity" class="font-medium leading-6 text-gray-900 mt-3">Capacidad</InputLabel>
                  <div class="mt-2">
                    <input type="number" v-model="form.capacity" id="capacity"
                      class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                    <InputError :message="form.errors.capacity" />
                  </div>
                </div>

                <div>
                    <div v-for="(headerId, index) in selectedHeaders" :key="index">
                    <InputLabel for="header" class="text-gray-700 mt-3">Cabecera {{ index + 1 }}:</InputLabel>
                    <select v-model="form.header_ids[index]" id="header" class="border rounded-md px-3 py-2 mb-3 w-full">
                        <option value="">Seleccionar Cabecera</option>
                        <option v-for="header in props.headers" :key="header.id" :value="header.id">{{ header.name }}</option>
                    </select>

                    <button @click.prevent="removeHeaderField(index)" class="rounded-md bg-red-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Eliminar Cabecera</button>
                    </div>

                    <button @click.prevent="addHeaderField" class="rounded-md bg-indigo-600 mt-2 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Añadir Cabecera</button>

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
                  <InputLabel for="capacity" class="font-medium leading-6 text-gray-900 mt-3">Capacidad</InputLabel>
                  <div class="mt-2">
                    <input type="number" v-model="formEdit.capacity" id="capacity"
                      class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                    <InputError :message="formEdit.errors.capacity" />
                  </div>
                </div>

                <div>
                    <div>
                    </div>
    <div v-for="(header, index) in formEdit.headers" :key="index">
      <InputLabel :for="`header-${index}`" class="text-gray-700 mt-3">Cabecera {{ index + 1 }}:</InputLabel>
      <select
        v-model="formEdit.headers[index].id"
        :id="`header-${index}`"
        class="border rounded-md px-3 py-2 mb-3 w-full"
      >
        <option value="">Seleccionar Cabecera</option>
        <option
          v-for="headerOption in props.headers"
          :key="headerOption.id"
          :value="headerOption.id"
        >
          {{ headerOption.name }}
        </option>
      </select>

      <button
        @click.prevent="removeHeaderFieldEdit(index)"
        class="rounded-md bg-red-600 px-6 py-2 text-sm font-semibold text-white shadow-divhover:bg-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
      >
        Eliminar Cabecera
      </button>
    </div>

    <button
      @click.prevent="addHeaderFieldEdit"
      class="rounded-md bg-indigo-600 mt-2 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
    >
      Añadir Cabecera
    </button>
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
  import ConfirmCreateModal from '@/Components/ConfirmCreateModal.vue';
  import ConfirmUpdateModal from '@/Components/ConfirmUpdateModal.vue';
  import ConfirmDeleteModal from '@/Components/ConfirmDeleteModal.vue';
  import SecondaryButton from '@/Components/SecondaryButton.vue';
  import InputError from '@/Components/InputError.vue';
  import InputLabel from '@/Components/InputLabel.vue';
  import Modal from '@/Components/Modal.vue';
  import { ref, computed, watch } from 'vue';
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
    capacity: '',
    header_ids: [],
  });

  const formEdit = useForm({
    id: '',
    name: '',
    location: '',
    capacity: '',
    header_ids: [],
    headers: null
  });
  
  const create_warehouse = ref(false);
  const showModal = ref(false);
  const showModalEdit = ref(false);
  const confirmingDocDeletion = ref(false);
  const docToDelete = ref(null);
  const editWarehouseModal = ref(false);
  const editingWarehouse = ref(null);
  
  //create

  const selectedHeaders = ref([]);

  const addHeaderField = () => {
    const newHeaderId = ''; // Reemplaza con la lógica para obtener el ID de la cabecera seleccionada

    // Agrega el nuevo ID al formulario
    form.header_ids.push(newHeaderId);
    console.log(form)
    console.log(form.header_ids)
    // Agrega un nuevo elemento al array de selectedHeaders para mostrar otro campo de selección en el formulario
    selectedHeaders.value.push('');
    };

const removeHeaderField = (index) => {
  // Elimina el valor de form.header_ids en la posición dada por index
  form.header_ids.splice(index, 1);

  // Elimina el valor correspondiente de selectedHeaders
  selectedHeaders.value.splice(index, 1);
};

//edit
const selectedHeadersEdit = ref([]);

const addHeaderFieldEdit = () => {
  // Obtén el nuevo ID de cabecera seleccionado
  const newHeaderId = '';
    
  // Agrega un nuevo objeto al array de form.headers con el nuevo ID
  formEdit.headers.push({ id: newHeaderId });
  
};

const removeHeaderFieldEdit = (index) => {
  // Elimina el valor de form.header_ids en la posición dada por index
  formEdit.header_ids.splice(index, 1);

  // Elimina el objeto correspondiente de form.headers
  formEdit.headers.splice(index, 1);
};

  const management_headers = () => {
    router.get(route('warehouses.headers'));
  };
  
  const openCreateWarehouseModal = () => {
    create_warehouse.value = true;
  };

  const openEditWarehouseModal = (warehouse) => {
  // Copia de los datos de la subsección existente al formulario
  editingWarehouse.value = JSON.parse(JSON.stringify(warehouse));
  formEdit.id = editingWarehouse.value.id;
  formEdit.name = editingWarehouse.value.name;
  formEdit.location = editingWarehouse.value.location;
  formEdit.capacity = editingWarehouse.value.capacity;

  // Filtra los warehouse_headers asociados al almacén específico
  const warehouseHeaders = props.warehouse_headers.filter((wh) => wh.warehouse_id === warehouse.id);

  // Almacena directamente los objetos de cabecera en form.headers
  formEdit.headers = warehouseHeaders.map((wh) => wh.header);

  // Almacena las ids de los headers en form.header_ids
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
    formEdit.header_ids = formEdit.headers.map((header) => header.id);
    console.log(formEdit.header_ids);
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
  
    