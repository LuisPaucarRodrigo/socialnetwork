<template>
  <Head title="Detalles del Almacén" />
  <AuthenticatedLayout :redirectRoute="{ route: 'warehouses.products', params: { warehouse: 1 } }">
    <template #header>
      Contenido de las cabeceras
    </template>
    <div class="">
      <form @submit.prevent="submit">
        <div class="space-y-12">
          <div class="border-b border-gray-900/10 pb-12">
            <div v-if="props.warehouse.headers.length === 0" class="text-lg text-gray-500">
              No hay cabeceras asociadas.
            </div>
            <div v-else class="grid grid-cols-2 gap-4">
              <div v-for="header in props.warehouse.headers" :key="header.id" class="flex flex-col gap-2">
                <InputLabel for="header" class="font-medium leading-6 text-gray-900 mt-2">{{ header.name }}</InputLabel>
                <div class="mt-2">
                  <input :type="header.type == 'double' ? 'number' : header.type" v-model="form.contentIds[header.id]"
                    :step="header.type == 'double' ? 0.01 : 1" id="header"
                    class="block w-full rounded-md border-0 py-1.5 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 p-2" />
                  <InputError :message="form.errors.contentIds" />
                </div>
              </div>
              <div class="flex flex-col gap-2">
                <InputLabel for="total" class="font-medium leading-6 text-gray-900 mt-2">Total</InputLabel>
                <div class="mt-2">
                  <input type="number" step="0.01" readonly disabled id="total"
                    class="block w-full rounded-md border-0 py-1.5 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 p-2" />
                </div>
              </div>
              <div class="flex flex-col gap-2">
                <InputLabel for="used" class="font-medium leading-6 text-gray-900 mt-2">Usado</InputLabel>
                <div class="mt-2">
                  <input type="number" step="0.01" id="used" readonly disabled
                    class="block w-full rounded-md border-0 py-1.5 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 p-2" />
                </div>
              </div>
              <div class="flex flex-col gap-2">
                <InputLabel for="remain" class="font-medium leading-6 text-gray-900 mt-2">Sobra</InputLabel>
                <div class="mt-2">
                  <input type="number" step="0.01" id="remain" readonly disabled
                    class="block w-full rounded-md border-0 py-1.5 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 p-2" />
                </div>
              </div>
              <div class="flex flex-col gap-2">
                <InputLabel for="amount sent" class="font-medium leading-6 text-gray-900 mt-2">Cantidad Enviada
                </InputLabel>
                <div class="mt-2">
                  <input type="number" step="0.01" id="amount sent" readonly disabled
                    class="block w-full rounded-md border-0 py-1.5 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 p-2" />
                </div>
              </div>
              <div class="flex flex-col gap-2">
                <InputLabel for="maintenance" class="font-medium leading-6 text-gray-900 mt-2">Mantenimiento</InputLabel>
                <div class="mt-2">
                  <input type="number" step="0.01" id="maintenance" readonly disabled
                    class="block w-full rounded-md border-0 py-1.5 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 p-2" />
                </div>
              </div>
            </div>
            <div class="mt-6 flex items-center justify-end gap-x-6">
              <SecondaryButton @click="closeModal">Cancelar</SecondaryButton>
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
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { ref, onMounted } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
  warehouse: Object,
});

const showModal = ref(false);

const form = useForm({
  contentIds: {},
});

onMounted(() => {
  // Asigna los valores previos al formulario
  props.warehouse.warehouse_headers.forEach((warehouseHeader) => {
    form.contentIds[warehouseHeader.header_id] = warehouseHeader.content || '';
  });

});

const submit = () => {
  form.post(route('warehouses.storeWarehouseHeader', { warehouse: props.warehouse.id }), {
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

const closeModal = () => {
  router.visit(route('warehouses.warehouses'))
};
</script>
