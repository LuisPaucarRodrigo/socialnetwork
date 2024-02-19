<template>
  <Head title="Detalles del Almacén" />
  <AuthenticatedLayout>
    <template #header>
      Contenido de las cabeceras
    </template>
    <div class="">
      <form @submit.prevent="submit">
        <div class="space-y-12">
          <div class="border-b border-gray-900/10 pb-12">
            <div v-if="props.headers.length === 0" class="text-lg text-gray-500">
              No hay cabeceras asociadas.
            </div>
            <div v-else>
              <div>
                <p class="text-lg font-semibold text-gray-600">Datos Principales</p>
                <br>
                <hr class="border-t-2 border-gray-400">
                <br>

                <div class="grid grid-cols-2 gap-4">
                  <div>
                  <InputLabel for="name" class="font-medium leading-6 text-gray-900 mt-2">Nombre del Producto</InputLabel>
                  <div>
                      <input type="text" id="autocomplete" v-model="form.name" list="options" @change="handleSelection"
                          class="block w-full rounded-md border-0 py-1.5 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 p-2" />
                      <datalist id="options">
                          <option v-for="option in props.products" :value="option.name" :data-value="option.id">{{ option.name }}</option>
                      </datalist>
                  </div>

                </div>
                <template v-for="header in headers" :key="header.id">
                  <template v-if="mandatory.includes(header.id)">
                    <div>
                      <InputLabel :for="`header-${header.id}`" class="font-medium leading-6 text-gray-900 mt-2">
                        {{ header.type == 'boolean' ? '¿Usar distinto precio en proyectos?' : header.name }}
                      </InputLabel>
                      <div>
                        <input :type="verifyType(header.type)" v-model="form.contentIds[header.id]"
                          :step="header.type == 'double' ? 0.01 : 1" :required="header.id === 8" :min="header.id == 8 ? 1 : 0"
                          :id="`header-${header.id}`"
                          :class="header.type == 'boolean' ? 'block w-9 h-9 rounded-md border-0 py-1.5 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 p-2' : 'block w-full rounded-md border-0 py-1.5 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 p-2'" />
                        <InputError :message="form.errors.contentIds" />
                      </div>
                    </div>
                  </template>
                </template>

  
                </div>
              </div>
                      
              <div v-if="headers.some(header => guidesAndCodes.includes(header.id))" class="mt-10 mb-10">
                <p class="text-lg font-semibold text-gray-600">Guías y Códigos</p>
                <br>
                <hr class="border-t-2 border-gray-400">
                <br>
                <div class="grid grid-cols-2 gap-4">
                  <!-- Iteración para los campos -->
                  <template v-for="header in headers" :key="header.id">
                    <div v-if="guidesAndCodes.includes(header.id)" class="flex flex-col gap-2">
                      <InputLabel :for="`header-${header.id}`" class="font-medium leading-6 text-gray-900 mt-2">{{ header.type == 'boolean' ? '¿Usar distinto precio en proyectos?' : header.name }}</InputLabel>
                      <div class="mt-2">
                        <input :type="verifyType(header.type)" v-model="form.contentIds[header.id]"
                          :step="header.type == 'double' ? 0.01 : 1" :required="header.id === 8" :min="header.id == 8 ? 1 : 0"
                          :id="`header-${header.id}`"
                          :class="header.type == 'boolean' ? 'block w-9 h-9 rounded-md border-0 py-1.5 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 p-2' : 'block w-full rounded-md border-0 py-1.5 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 p-2'" />
                        <InputError :message="form.errors.contentIds" />
                      </div>
                    </div>
                  </template>
                </div>
              </div>

              
              <div v-if="headers.some(header => unitsPricesAndLegal.includes(header.id))" class="mb-10">
                <p class="text-lg font-semibold text-gray-600">Unidades y Legal</p>
                <br>
                <hr class="border-t-2 border-gray-400">
                <br>
                <div class="grid grid-cols-2 gap-4">
                  <!-- Iteración para los campos -->
                  <template v-for="header in headers" :key="header.id">
                    <div v-if="unitsPricesAndLegal.includes(header.id)" class="flex flex-col gap-2">
                      <InputLabel :for="`header-${header.id}`" class="font-medium leading-6 text-gray-900 mt-2">{{ header.type == 'boolean' ? '¿Usar distinto precio en proyectos?' : header.name }}</InputLabel>
                      <div class="mt-2">
                        <input :type="verifyType(header.type)" v-model="form.contentIds[header.id]"
                          :step="header.type == 'double' ? 0.01 : 1" :required="header.id === 8" :min="header.id == 8 ? 1 : 0"
                          :id="`header-${header.id}`"
                          :class="header.type == 'boolean' ? 'block w-9 h-9 rounded-md border-0 py-1.5 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 p-2' : 'block w-full rounded-md border-0 py-1.5 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 p-2'" />
                        <InputError :message="form.errors.contentIds" />
                      </div>
                    </div>
                  </template>
                </div>
              </div>
              <div v-if="headers.some(header => datesLocationsAndOthers.includes(header.id))" class="mb-10">
                <p class="text-lg font-semibold text-gray-600">Fechas y Ubicaciones</p>
                <br>
                <hr class="border-t-2 border-gray-400">
                <br>
                <div class="grid grid-cols-2 gap-4">
                  <!-- Iteración para los campos -->
                  <template v-for="header in headers" :key="header.id">
                    <div v-if="datesLocationsAndOthers.includes(header.id)" class="flex flex-col gap-2">
                      <InputLabel :for="`header-${header.id}`" class="font-medium leading-6 text-gray-900 mt-2">{{ header.type == 'boolean' ? '¿Usar distinto precio en proyectos?' : header.name }}</InputLabel>
                      <div class="mt-2">
                        <input :type="verifyType(header.type)" v-model="form.contentIds[header.id]"
                          :step="header.type == 'double' ? 0.01 : 1" :required="header.id === 8" :min="header.id == 8 ? 1 : 0"
                          :id="`header-${header.id}`"
                          :class="header.type == 'boolean' ? 'block w-9 h-9 rounded-md border-0 py-1.5 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 p-2' : 'block w-full rounded-md border-0 py-1.5 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 p-2'" />
                        <InputError :message="form.errors.contentIds" />
                      </div>
                    </div>
                  </template>
                </div>
              </div>

              <div class="mb-10">
                <p class="text-lg font-semibold text-gray-600">Estadísticas</p>
                <br>
                <hr class="border-t-2 border-gray-400">
                <br>
                <div class="grid grid-cols-2 gap-4"> 
                  <div class="flex flex-col gap-2">
                    <InputLabel for="total" class="font-medium leading-6 text-gray-900 mt-2">Total</InputLabel>
                    <div class="mt-2">
                      <input type="number" step="0.01" readonly disabled id="total"
                        class="block w-full rounded-md border-0 py-1.5 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 p-2 bg-blue-100" />
                    </div>
                  </div>
                  <div class="flex flex-col gap-2">
                    <InputLabel for="used" class="font-medium leading-6 text-gray-900 mt-2">Usado</InputLabel>
                    <div class="mt-2">
                      <input type="number" step="0.01" id="used" readonly disabled
                        class="block w-full rounded-md border-0 py-1.5 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 p-2 bg-blue-100" />
                    </div>
                  </div>
                  <div class="flex flex-col gap-2">
                    <InputLabel for="remain" class="font-medium leading-6 text-gray-900 mt-2">Sobra</InputLabel>
                    <div class="mt-2">
                      <input type="number" step="0.01" id="remain" readonly disabled
                        class="block w-full rounded-md border-0 py-1.5 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 p-2 bg-blue-100" />
                    </div>
                  </div>
                  <div class="flex flex-col gap-2">
                    <InputLabel for="amount sent" class="font-medium leading-6 text-gray-900 mt-2">Cantidad Enviada
                    </InputLabel>
                    <div class="mt-2">
                      <input type="number" step="0.01" id="amount sent" readonly disabled
                        class="block w-full rounded-md border-0 py-1.5 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 p-2 bg-blue-100" />
                    </div>
                  </div>
                  <div class="flex flex-col gap-2">
                    <InputLabel for="maintenance" class="font-medium leading-6 text-gray-900 mt-2">Mantenimiento</InputLabel>
                    <div class="mt-2">
                      <input type="number" step="0.01" id="maintenance" readonly disabled
                        class="block w-full rounded-md border-0 py-1.5 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 p-2 bg-blue-100" />
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <hr class="border-t-2 border-gray-400">
            <br>
            <div class="mt-6 flex items-center justify-between gap-x-6">
              <button @click="closeModal" type="button"
                class="w-full rounded-md bg-red-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                Cancelar
              </button>
              <button type="submit" :class="{ 'opacity-25': form.processing }"
                class="w-full rounded-md bg-indigo-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                Guardar
              </button>
            </div>
          </div>
        </div>
      </form>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { Head, useForm, router } from '@inertiajs/vue3';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import { ref } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
  headers: Object,
  warehouse: Object,
  products: Object
});

const showModal = ref(false);

const form = useForm({
  name: '',
  contentIds: {
    1: '',
    5: '',
    7: '',
    8: 1,
    10: '',
    12: '',
    15: '',
    29: '',
    32: false
  }
});

const handleSelection = (event) => {
  const selectedOption = event.target.value;
  const options = event.target.list.options;
  const selectedProduct = props.products.find(product => product.name === selectedOption);
  if (selectedProduct) {
    form.name = selectedProduct.name;
    const contents = selectedProduct.contents;
    contents.forEach(content => {
      const headerId = content.header_id;
      const contentValue = content.content;
      form.contentIds[headerId] = contentValue;
    });
  }
};

const mandatory = [
  1, 5, 7, 8, 10, 12, 15, 29, 32
];

const guidesAndCodes = [
  2, 3, 4, 6, 11, 20, 22, 27
];

const unitsPricesAndLegal = [
  9, 13, 16, 24, 25, 28, 30, 31
];

const datesLocationsAndOthers = [
  14, 17, 18, 19, 21, 23, 26
];


const submit = () => {

  form.post(route('warehouses.storeProduct', { warehouse: props.warehouse.id }), {
    onSuccess: () => {
      closeModal();
      form.reset();
      showModal.value = true
      setTimeout(() => {
        showModal.value = false;
        router.visit(route('warehouses.products', { warehouse: props.warehouse.id }))
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

const closeModal = () => {
  router.visit(route('warehouses.products', { warehouse: props.warehouse.id }))
};

const verifyType = (type) => {
  if (type == 'double') {
    return 'number'
  } else if (type == 'boolean') {
    return 'checkbox'
  } else if (type == 'number') {
    return 'number'
  } else if (type == 'date') {
    return 'date'
  }
  else {
    return 'text'
  }
}

</script>
