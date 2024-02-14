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
            <div v-else class="grid grid-cols-2 gap-4">

              <div class="flex flex-col gap-2">
                <InputLabel for="name" class="font-medium leading-6 text-gray-900 mt-2">Nombre del Producto</InputLabel>
                <div class="mt-2">
                  <input type="text" id="name" v-model="form.name"
                    class="block w-full rounded-md border-0 py-1.5 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 p-2" />
                  <InputError :message="form.errors.name" />
                </div>
              </div>

              <div v-for="header in headers" :key="header.id" class="flex flex-col gap-2">
                <InputLabel for="header" class="font-medium leading-6 text-gray-900 mt-2">{{ header.type == 'boolean' ?
                  '¿Usar distinto precio en proyectos?' : header.name }}</InputLabel>
                <div class="mt-2">
                  <input :type="verifyType(header.type)" v-model="form.contentIds[header.id]"
                    :step="header.type == 'double' ? 0.01 : 1" :required="header.id === 8" :min="header.id == 8 ? 1 : 0"
                    id="header"
                    :class="header.type == 'boolean' ? 'block w-9 h-9 rounded-md border-0 py-1.5 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 p-2' : 'block w-full rounded-md border-0 py-1.5 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 p-2'" />
                  <InputError :message="form.errors.contentIds" />
                </div>
              </div>
            </div>
            <div class="mt-6 flex items-center justify-between gap-x-6">
              <button @click="closeModal" type="button"
                class="rounded-md bg-indigo-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                Cancelar
              </button>
              <button type="submit" :class="{ 'opacity-25': form.processing }"
                class="rounded-md bg-indigo-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
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
  warehouse: Object
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
