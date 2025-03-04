<template>

  <Head title="Detalles del Almacén" />
  <AuthenticatedLayout
    :redirectRoute="{ route: 'projectmanagement.liquidate', params: { project_id: props.project_id } }">
    <template #header>
      Liquidar Producto
    </template>
    <div>
      <form @submit.prevent="submit">
        <div class="space-y-12">
          <div class="border-b border-gray-900/10 pb-12">
            <div>
              <div>
                <div>
                  <div>
                    <div>
                      <h2 class="font-medium leading-6 text-gray-500 mt-2 text-sm">Cantidad Total: {{
                        props.project_entry.quantity }}</h2>
                    </div>

                    <div>
                      <InputLabel for="liquidated_quantity" class="font-medium leading-6 text-gray-900 mt-2">Cantidad a
                        Liquidar</InputLabel>
                      <div>
                        <!-- Establecer el atributo max -->
                        <input type="number" id="liquidated_quantity" :max="props.project_entry.quantity"
                          v-model="form.liquidated_quantity"
                          class="block w-full rounded-md border-0 py-1.5 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 p-2" />
                        <InputError :message="form.errors.quantity" />
                      </div>
                    </div>
                    <div>
                      <InputLabel for="refund_quantity" class="font-medium leading-6 text-gray-900 mt-2">Cantidad a
                        Devolver
                      </InputLabel>
                      <div>
                        <input type="number" id="refund_quantity" disabled readonly v-model="form.refund_quantity"
                          class="block w-full rounded-md border-0 py-1.5 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 p-2" />
                      </div>
                    </div>

                    <div v-if="form.refund_quantity > 0 || form.refund_quantity">
                      <div v-if="props.project_entry.special_inventory">
                        <InputLabel for="devolution" class="font-medium leading-6 text-gray-900 mt-2">Seleccione el
                          almacén
                        </InputLabel>
                        <div class="mt-2 flex">
                          <!-- Alineación horizontal de los radiobuttons -->
                          <div class="flex items-center">
                            <input type="radio" id="devolution_0" name="devolution" value="0"
                              v-model="form.devolution_value"
                              class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300">
                            <label for="devolution_0" class="ml-2 block text-sm text-gray-900">Recuperos</label>
                          </div>
                          <div class="flex items-center ml-6">
                            <input type="radio" id="devolution_1" name="devolution" value="1"
                              v-model="form.devolution_value"
                              class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300">
                            <label for="devolution_1" class="ml-2 block text-sm text-gray-900">Devoluciones</label>
                          </div>
                        </div>

                      </div>

                      <div v-else>
                        <input type="hidden" name="devolution_value" :value="form.devolution_value = 0">
                        <div
                          class="inline-flex items-center p-2 mb-4  mt-4 text-sm text-yellow-800 rounded-lg bg-yellow-50 dark:bg-gray-800 dark:text-yellow-300"
                          role="alert">
                          <svg class="flex-shrink-0 inline w-4 h-4 me-3" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                            <path
                              d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
                          </svg>
                          <span class="sr-only">Info</span>
                          <div>
                            <span class="font-small">La cantidad devuelta irá automáticamente al almacén de
                              recuperos.</span>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div>
                      <InputLabel for="observations" class="font-medium leading-6 text-gray-900 mt-2">Observaciones
                      </InputLabel>
                      <div>
                        <textarea type="text" id="observations" v-model="form.observations"
                          class="block w-full rounded-md border-0 py-1.5 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 p-2" />
                      </div>
                    </div>

                  </div>
                </div>

              </div>
              <br>
              <div class="mt-6 flex justify-end">
                <button type="submit" :class="{ 'opacity-25': form.processing }"
                  class="rounded-md bg-indigo-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                  Liquidar
                </button>
              </div>
            </div>
          </div>
        </div>
      </form>
    </div>
    <SuccessOperationModal :confirming="showModal" title="Producto liquidado" message="La liquidación fue exitosa" />
  </AuthenticatedLayout>
</template>

<script setup>
import { Head, useForm, router } from '@inertiajs/vue3';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import { ref, watch } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import SuccessOperationModal from '@/Components/SuccessOperationModal.vue';

const props = defineProps({
  project_entry: Object,
  project_id: String
});

const showModal = ref(false);

const form = useForm({
  liquidated_quantity: null,
  refund_quantity: null,
  devolution_value: 0,
  observations: ''
});

const submit = () => {
  form.post(route('projectmanagement.liquidate.post', { project_id: props.project_id, project_entry: props.project_entry.id }), {
    onSuccess: () => {
      form.reset();
      showModal.value = true
      setTimeout(() => {
        showModal.value = false;
        router.visit(route('projectmanagement.liquidate', { project_id: props.project_id }))
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

watch(() => form.liquidated_quantity, (newVal) => {
  const totalQuantity = parseFloat(props.project_entry.quantity) || 0;
  const liquidatedQuantity = parseFloat(newVal) || 0;
  const refundQuantity = totalQuantity - liquidatedQuantity;

  // Actualizar el valor de form.refund_quantity
  form.refund_quantity = refundQuantity;
});
</script>