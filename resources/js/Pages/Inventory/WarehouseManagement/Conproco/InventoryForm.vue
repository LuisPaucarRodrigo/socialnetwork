<template>

  <Head title="Detalles del Almacén" />
  <AuthenticatedLayout :redirectRoute="{ route: 'warehouses.conproco.products', params: { warehouse: props.warehouseId } }">
    <template #header>
      Registrar Producto
    </template>
    <div class="">
      <form @submit.prevent="submit">
        <div class="space-y-12">
          <div class="border-b border-gray-900/10 pb-12">
            <div class="grid sm:grid-cols-2 gap-8">
              <div>
                <div class="flex gap-x-2 items-center">
                  <div class="sm:col-span-3 w-full">
                            <InputLabel for="purchase_product_id" class="font-medium leading-6 text-gray-900">
                                Producto
                            </InputLabel>
                            <div class="mt-2">
                                <input 
                                    @input="(e) => 
                                        handleAutocomplete(e,'purchase_product_id')"  
                                    list="options"
                                    type="text" 
                                    id="purchase_product_id" 
                                    autocomplete="off"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <datalist id="options">
                                    <option v-for="item in purchase_products" :value="item.code">
                                        {{ item.name }}
                                    </option>
                                </datalist>
                                <InputError 
                                    :message="form.errors.purchase_product_id"
                                />
                            </div>
                        </div>
                  <div v-if="form.purchase_product_id">
                    <InputLabel for="name">Unidad:</InputLabel>
                    <InputLabel class="flex mt-3">
                      {{ purchase_products.find(item => item.id == form.purchase_product_id)?.unit }}
                    </InputLabel>
                  </div>
                </div>
                <InputError :message="form.errors.purchase_product_id" />
              </div>
              <div>
                <InputLabel for="quantity">Cantidad</InputLabel>
                <div>
                  <TextInput type="number" id="quantity" v-model="form.quantity" />
                  <InputError :message="form.errors.quantity" />
                </div>
              </div>
              <div>
                <InputLabel for="unitary_price">Precio Unitario</InputLabel>
                <div>
                  <TextInput type="number" step="0.01" id="unitary_price" v-model="form.unitary_price" />
                  <InputError :message="form.errors.unitary_price" />
                </div>
              </div>

              <div>
                <InputLabel for="entry_date">Fecha de Entrada del Producto</InputLabel>
                <div>
                  <TextInput type="date" id="entry_date" v-model="form.entry_date" />
                  <InputError :message="form.errors.entry_date" />
                </div>
              </div>

              <div>
                <InputLabel for="referral_guide">Guía de Referencia</InputLabel>
                <div>
                  <TextInput type="text" id="referral_guide" v-model="form.referral_guide" />
                  <InputError :message="form.errors.referral_guide" />
                </div>
              </div>

              <div>
                <InputLabel for="observations">Observaciones</InputLabel>
                <div>
                  <textarea type="text" id="observations" v-model="form.observations"
                    class="block w-full rounded-md border-0 py-1.5 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 p-2" />
                </div>
              </div>
            </div>
            <br>
            <div class="mt-6 flex justify-end">
              <PrimaryButton type="submit" :class="{ 'opacity-25': form.processing }">
                Guardar
              </PrimaryButton>
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
import TextInput from '@/Components/TextInput.vue';
import ConfirmCreateModal from '@/Components/ConfirmCreateModal.vue';
import InputError from '@/Components/InputError.vue';
import { ref } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const props = defineProps({
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

const submit = () => {
  form.post(route('warehouses.storeNormalProduct', { warehouse: props.warehouseId }), {
    onSuccess: () => {
      form.reset();
      showModal.value = true
      setTimeout(() => {
        showModal.value = false;
        router.visit(route('warehouses.conproco.products', { warehouse: props.warehouseId }))
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

const selectedProduct = ref({code:""});

const handleAutocomplete = (e) => {
    const code = e.target.value;
    let findedProduct = props.purchase_products.find(item => item.code === code)
    if (findedProduct) {
        form.purchase_product_id = findedProduct.id
        selectedProduct.value = {...findedProduct}
    } else {
        form.purchase_product_id = null
        selectedProduct.value = {code:""}
    }
}

</script>