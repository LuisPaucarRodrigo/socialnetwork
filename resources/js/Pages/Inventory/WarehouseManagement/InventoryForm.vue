<template>
    <Head title="Detalles del Almacén" />
    <AuthenticatedLayout :redirectRoute="{ route: 'warehouses.products', params: { warehouse: props.warehouseId } }">
      <template #header>
        Registrar Producto
      </template>
      <div class="">
        <form @submit.prevent="submit">
          <div class="space-y-12">
            <div class="border-b border-gray-900/10 pb-12">
              <div>
                <div>

                  <div class="grid grid-cols-2 gap-4">
                    <div>
                        <InputLabel for="name" class="font-medium leading-6 text-gray-900 mt-2">Producto</InputLabel>
                        <select v-model="form.purchase_product_id" id="product-select" class="block w-full rounded-md border-0 py-1.5 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 p-2">
                            <option disabled value="">Selecciona un producto</option>
                            <option v-for="product in props.purchase_products" :value="product.id">{{ product.name }}</option>
                        </select>
                    </div>
                    <div>
                        <InputLabel for="quantity" class="font-medium leading-6 text-gray-900 mt-2">Cantidad</InputLabel>
                        <div>
                        <input type="number" id="quantity" v-model="form.quantity"
                            class="block w-full rounded-md border-0 py-1.5 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 p-2" />
                        </div>
                    </div>
                    <div>
                        <InputLabel for="unitary_price" class="font-medium leading-6 text-gray-900 mt-2">Precio Unitario</InputLabel>
                        <div>
                        <input type="number" step="0.01" id="unitary_price" v-model="form.unitary_price"
                            class="block w-full rounded-md border-0 py-1.5 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 p-2" />
                        </div>
                    </div>

                    <div>
                        <InputLabel for="entry_date" class="font-medium leading-6 text-gray-900 mt-2">Fecha de Entrada del Producto</InputLabel>
                        <div>
                        <input type="date" id="entry_date" v-model="form.entry_date"
                            class="block w-full rounded-md border-0 py-1.5 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 p-2" />
                        </div>
                    </div>

                    <div>
                        <InputLabel for="referral_guide" class="font-medium leading-6 text-gray-900 mt-2">Guía de Referencia</InputLabel>
                        <div>
                        <input type="text" id="referral_guide" v-model="form.referral_guide"
                            class="block w-full rounded-md border-0 py-1.5 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 p-2" />
                        </div>
                    </div>

                    <div>
                        <InputLabel for="observations" class="font-medium leading-6 text-gray-900 mt-2">Observaciones</InputLabel>
                        <div>
                        <textarea type="text" id="observations" v-model="form.observations"
                            class="block w-full rounded-md border-0 py-1.5 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 p-2" />
                        </div>
                    </div>
    
                  </div>
                </div>

              </div>
              <br>
              <div class="mt-6 flex items-center justify-between gap-x-6">
                <button @click="cancel" type="button"
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
      <ConfirmCreateModal :confirmingcreation="showModal" itemType="Producto" />
    </AuthenticatedLayout>
  </template>
  
  <script setup>
  import { Head, useForm, router } from '@inertiajs/vue3';
  import InputLabel from '@/Components/InputLabel.vue';
  import ConfirmCreateModal from '@/Components/ConfirmCreateModal.vue';
  import InputError from '@/Components/InputError.vue';
  import { ref } from 'vue';
  import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
  
  const props = defineProps({
    products: Object,
    purchase_products: Object,
    warehouseId: Number
  });

  const showModal = ref(false);
  
  const form = useForm({
    quantity: null,
    unitary_price: null,
    entry_date: '',
    observations: '',
    referral_guide: '',
    purchase_product_id: null
  });

  const cancel = () => {
    router.visit(route('warehouses.products', {warehouse: props.warehouseId}))
  };

  const submit = () => {

form.post(route('warehouses.storeNormalProduct', { warehouse: props.warehouseId }), {
  onSuccess: () => {
    form.reset();
    showModal.value = true
    setTimeout(() => {
      showModal.value = false;
      router.visit(route('warehouses.products', { warehouse: props.warehouseId }))
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
 
  </script>
  