<template>
    <Head title="Agregar Entrada" />
    <AuthenticatedLayout>
      <template #header>
        <h1 class="text-lg font-semibold leading-7 text-gray-900">Agregar Entrada</h1>
      </template>
      <div class="min-w-full rounded-lg shadow p-6">
        <form @submit.prevent="handleSubmit" class="mb-8">
          <div class="grid grid-cols-2 gap-6">
            <div>
              <label for="guide_number" class="block text-sm font-medium text-gray-700">Número de Guía</label>
              <input
                id="guide_number"
                type="text"
                v-model="form.guide_number"
                class="block w-full mt-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
              />
              <InputError :message="form.errors.guide_number" />
            </div>

            <div>
              <label for="entry_date" class="block text-sm font-medium text-gray-700">Fecha de Entrada</label>
              <input
                id="entry_date"
                type="date"
                v-model="form.entry_date"
                class="block w-full mt-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
              />
              <InputError :message="form.errors.entry_date" />
            </div>

          </div>

        </form>
        <div class="grid grid-cols-2 gap-x-12 gap-y-12">
          <div>
            <div class="flex items-center mb-4">
                <h2 class="text-lg font-medium leading-7 text-gray-900 mr-4">Materiales</h2>
                <button @click="openMaterialModal" type="button" class="text-blue-500 hover:text-purple-500">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                        class="w-7 h-7">
                    <path stroke-linecap="round" stroke-linejoin="round"
                            d="M7.5 7.5h-.75A2.25 2.25 0 0 0 4.5 9.75v7.5a2.25 2.25 0 0 0 2.25 2.25h7.5a2.25 2.25 0 0 0 2.25-2.25v-7.5a2.25 2.25 0 0 0-2.25-2.25h-.75m-6 3.75 3 3m0 0 3-3m-3 3V1.5m6 9h.75a2.25 2.25 0 0 1 2.25 2.25v7.5a2.25 2.25 0 0 1-2.25 2.25h-7.5a2.25 2.25 0 0 1-2.25-2.25v-.75"/>
                    </svg>
                </button>
            </div>


            <div class="overflow-x-auto mt-3">
              <table class="w-full whitespace-no-wrap">
                <thead>
                  <tr class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                    <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                      Descripción del producto
                    </th>
                    <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                      Código de Claro
                    </th>
                    <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                      Marca
                    </th>
                    <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                      Modelo
                    </th>
                    <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                      Cantidad
                    </th>
                    <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                    </th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="item in newMaterials" :key="item.id" class="text-gray-700">
                    <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">{{ item.name }}</td>
                    <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">{{ item.claro_code }}</td>
                    <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">{{ props.brands.find(brand => brand.id == item.brand)?.name }}</td>
                    <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">{{ props.brand_models.find(model => model.id == item.brand_model)?.name }}</td>
                    <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">{{ item.quantity }}</td>
                    <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                      <div class="flex items-center">
                        <button class="text-orange-400 hover:underline mr-2"><EyeIcon class="h-5 w-5" /></button>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
          <div>
            <div class="flex items-center mb-4">
                <h2 class="text-lg font-medium leading-7 text-gray-900 mr-4">Equipos</h2>
                <button @click="" type="button" class="text-blue-500 hover:text-purple-500">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                        class="w-7 h-7">
                    <path stroke-linecap="round" stroke-linejoin="round"
                            d="M7.5 7.5h-.75A2.25 2.25 0 0 0 4.5 9.75v7.5a2.25 2.25 0 0 0 2.25 2.25h7.5a2.25 2.25 0 0 0 2.25-2.25v-7.5a2.25 2.25 0 0 0-2.25-2.25h-.75m-6 3.75 3 3m0 0 3-3m-3 3V1.5m6 9h.75a2.25 2.25 0 0 1 2.25 2.25v7.5a2.25 2.25 0 0 1-2.25 2.25h-7.5a2.25 2.25 0 0 1-2.25-2.25v-.75"/>
                    </svg>
                </button>
            </div>
            <div class="overflow-x-auto mt-3">
              <table class="w-full whitespace-no-wrap">
                <thead>
                  <tr class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                    <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                      Nombre del Activo
                    </th>
                    <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                      Tipo
                    </th>
                    <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                      Estado
                    </th>
                    <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                      Ubicación
                    </th>
                    <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                      Responsable
                    </th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="item in assets.data" :key="item.id" class="text-gray-700">
                    <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">{{ item.name }}</td>
                    <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">{{ item.type }}</td>
                    <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">{{ item.status }}</td>
                    <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">{{ item.location }}</td>
                    <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                      <div class="flex items-center">
                        <button class="text-orange-400 hover:underline mr-2"><EyeIcon class="h-5 w-5" /></button>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <div class="mt-6 flex items-center justify-end gap-x-3">
            <button type="button" @click="" class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-700">Cancelar</button>
            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-800">{{ form.id ? 'Actualizar' : 'Guardar' }}</button>
          </div>
      </div>

      <Modal :show="materialModal">
        <div class="p-6">
          <h2 class="text-base font-medium leading-7 text-gray-900">Agregar Material</h2>
          <form @submit.prevent="add_material" class="grid grid-cols-2 gap-3">
            <!-- Primera Fila -->
            <div class="col-span-2 grid grid-cols-2 gap-6">
              <div>
                <InputLabel class="mb-1" for="name">Nombre</InputLabel>
                <input v-model="materialForm.name" type="text" class="block w-full py-1.5 rounded-md sm:text-sm form-input focus:border-indigo-600" />
              </div>
              <div>
                <InputLabel class="mb-1" for="claro_code">Código de Claro</InputLabel>
                <input type="text" v-model="materialForm.claro_code" class="block w-full py-1.5 rounded-md sm:text-sm form-input focus:border-indigo-600" />
              </div>
            </div>

            <!-- Segunda Fila -->
            <div class="col-span-2 grid grid-cols-2 gap-6">
              <div>
                <div class="flex items-center gap-2">
                  <InputLabel for="brand" class="font-medium leading-6 text-gray-900 mb-1">Marca
                  </InputLabel>
                  <button type="button" @click="new_brand">
                      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-indigo-500">
                        <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                  </button>
                </div>
                <select v-model="materialForm.brand" class="block w-full py-1.5 rounded-md sm:text-sm form-input focus:border-indigo-600">
                  <option value="" disabled>Seleccionar Marca</option>
                  <option v-for="brand in props.brands" :key="brand.id" :value="brand.id">{{ brand.name }}</option>
                </select>
              </div>

              <div>
                <div class="flex items-center gap-2">
                  <InputLabel for="model" class="font-medium leading-6 text-gray-900 mb-1">Modelo
                  </InputLabel>
                  <button type="button" @click="new_brand_model">
                      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-indigo-500">
                        <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                  </button>  
                </div>              
                  <select v-model="materialForm.brand_model" class="block w-full py-1.5 rounded-md sm:text-sm form-input focus:border-indigo-600">
                  <option value="" disabled>Seleccionar Modelo</option>
                  <option v-for="model in filteredModels" :key="model.id" :value="model.id">{{ model.name }}</option>
                </select>
              </div>
            </div>

            <!-- Tercera Fila -->
            <div class="col-span-1">
              <InputLabel class="mb-1" for="quantity">Cantidad</InputLabel>
              <input type="number" min="0" v-model="materialForm.quantity" class="block w-full py-1.5 rounded-md sm:text-sm form-input focus:border-indigo-600" />
            </div>

            <!-- Botones de Acción -->
            <div class="col-span-2 mt-6 flex items-center justify-end gap-x-6">
              <SecondaryButton @click="closeMaterialModal">Cancelar</SecondaryButton>
              <PrimaryButton type="submit" :class="{ 'opacity-25': form.processing }">Guardar</PrimaryButton>
            </div>
          </form>
        </div>
      </Modal>

      <Modal :show="new_brand_modal || new_brand_model_modal">
            <form class="p-6" @submit.prevent="submitName">
                <h2 class="text-lg font-medium text-gray-900">
                    Nuevo(a) {{ new_brand_modal ? 'Marca' : 'Modelo' }}
                </h2>
                <div class="grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-6 mt-2">
                    <div class="sm:col-span-6">
                        <InputLabel for="name" class="font-medium leading-6 text-gray-900">Nombre</InputLabel>
                        <div class="mt-2">
                            <TextInput id="name" required :to-uppercase="true"
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                v-model="formname.name" />
                            <InputError :message="formname.errors.name" />
                        </div>
                    </div>

                    <div v-if="new_brand_model_modal" class="sm:col-span-6">
                        <InputLabel for="name" class="font-medium leading-6 text-gray-900">Marca</InputLabel>
                        <div class="mt-2">
                          <select v-model="formname.brand_id" class="block w-full py-1.5 rounded-md sm:text-sm form-input focus:border-indigo-600">
                            <option value="" disabled>Seleccionar Marca</option>
                            <option v-for="brand in props.brands" :key="brand.id" :value="brand.id">{{ brand.name }}</option>
                          </select>
                            <InputError :message="formname.errors.brand_id" />
                        </div>
                    </div>

                </div>
                <div class="mt-6 flex gap-3 justify-end">
                    <SecondaryButton type="button" @click="new_brand_modal ? close_new_brand() : close_new_brand_model()"> Cerrar </SecondaryButton>
                    <PrimaryButton type="submit"> Agregar </PrimaryButton>
                </div>
            </form>
        </Modal>

        <SuccessOperationModal :confirming="addSuccess" title="" message="" />

    </AuthenticatedLayout>
  </template>

  <script setup>
  import { ref, computed } from 'vue';
  import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
  import { Head, Link, useForm } from '@inertiajs/vue3';
  import { EyeIcon } from '@heroicons/vue/24/outline';
  import InputError from '@/Components/InputError.vue';
  import Modal from '@/Components/Modal.vue';
  import SecondaryButton from '@/Components/SecondaryButton.vue';
  import PrimaryButton from '@/Components/PrimaryButton.vue';
  import Pagination from '@/Components/Pagination.vue';
  import InputLabel from '@/Components/InputLabel.vue';
  import TextInput from '@/Components/TextInput.vue';
  import axios from 'axios';
  import SuccessOperationModal from '@/Components/SuccessOperationModal.vue';

  const props = defineProps({
    brand_models: Object,
    brands: Object,
    equipments: Object,
    materials: Object
  });

  const materialModal = ref(false);
  const equipmentModal = ref(false);
  const new_brand_modal = ref(false);
  const new_brand_model_modal = ref(false);
  const addSuccess = ref(false);

  const openMaterialModal = () => {
    materialModal.value = true;
  }

  const closeMaterialModal = () => {
    materialForm.reset();
    materialModal.value = false
  }

  const form = ref({
    name: '',
    unit: '',
    type: '',
    type_product: '',
    resource_type: '',
    description: '',
    errors: {}
  });

  const materialForm = useForm({
    name: '',
    claro_code: '',
    brand: '',
    brand_model: '',
    quantity: ''
  });

  const filteredModels = computed(() => {
    return props.brand_models.filter(model => model.brand_id === materialForm.brand);
  });

  const add_material = () => {
    newMaterials.value.push({ 
      name: materialForm.name, 
      claro_code: materialForm.claro_code,
      brand: materialForm.brand, 
      brand_model: materialForm.brand_model,
      quantity: materialForm.quantity 
    });

    materialForm.reset();
    console.log(newMaterials)
    closeMaterialModal();
  };

  const newMaterials = ref([]);

  const assets = ref({
    data: [
      { id: 1, name: 'Activo 1', type: 'Tipo A', status: 'Disponible', location: 'Almacén 1', responsible: 'Persona 1' },
      { id: 2, name: 'Activo 2', type: 'Tipo B', status: 'En uso', location: 'Almacén 2', responsible: 'Persona 2' }
    ]
  });

  const new_brand = () => {
    new_brand_modal.value = true;
  }

  const close_new_brand = () => {
    formname.reset();
    new_brand_modal.value = false;
  }

  const new_brand_model = () => {
    new_brand_model_modal.value = true;
  }

  const close_new_brand_model = () => {
    formname.reset();
    new_brand_model_modal.value = false;
  }

  const formname = useForm({
    name: '',
    brand_id: '',
    errors: {}
  });

  const submitName = () => {
    const url = new_brand_modal.value ? route('huawei.inventory.create.brand') : route('huawei.inventory.create.brandmodel')
    axios.post(url, { ...formname.data() })
        .then(response => {
            if (response.status === 200) {
                let newItem = response.data.new
                new_brand_modal.value
                    ? props.brands.push({ ...newItem })
                    : props.brand_models.push({ ...newItem })
                new_brand_modal.value 
                    ? close_new_brand()
                    : close_new_brand_model()
                addSuccess.value = true
                setTimeout(() => {
                    addSuccess.value = false
                }, 600)
                formname.reset()
            } else {
                throw new Error('Fallo en el servidor con status ' + response.status)
            }
        })
        .catch(error => {
          if (error.response && error.response.status === 422) {
            // Manejar errores de validación
            formname.errors = error.response.data.errors;
          } else {
            console.error(error);
          }
        })
};


  </script>
