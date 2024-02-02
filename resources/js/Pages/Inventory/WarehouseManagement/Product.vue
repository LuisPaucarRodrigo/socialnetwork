<template>
    <Head title="Gestion de Almacenes" />
    <AuthenticatedLayout>
      <template #header>
        Gestión de Productos del Almacén
      </template>
      <div class="min-w-full p-3 rounded-lg shadow">
            <div class="flex gap-4">
                <button @click="createProduct" type="button"
                    class="inline-flex items-center px-4 py-2 border-2 border-gray-700 rounded-md font-semibold text-xs hover:text-gray-700 uppercase tracking-widest bg-gray-700 hover:underline hover:bg-gray-200 focus:border-indigo-600 focus:outline-none focus:ring-2 text-white">
                    + Agregar
                </button>
            </div>
            <br>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-4 gap-3">
                <div v-for="item in props.products.data" :key="item.id"
                    class="bg-white p-3 rounded-md shadow-sm border border-gray-300 items-center">
                    <div class="grid grid-cols-2">
                        <h2 class="text-sm font-semibold mb-3">
                            {{ item.name }}
                        </h2>
                        <div class="inline-flex justify-end gap-x-0 mb-4">
                            <Link :href="route('warehouses.showProduct', {warehouse: props.warehouse.id, product: item.id})" class="text-green-400 hover:underline"><EyeIcon class="h-4 w-4 ml-1" /></Link>
                            <Link :href="route('warehouses.editProduct', {warehouse: props.warehouse.id, product: item.id})" class="text-orange-400 hover:underline mx-2"><PencilIcon class="h-4 w-4 ml-1" /></Link>
                            <button @click="confirmDeleteProduct(item.id)" class="text-red-600 hover:underline"><TrashIcon class="h-4 w-4" /></button>
                        </div>
                    </div>

                    <div v-for="productHeader in productHeaders" :key="productHeader.id">         
                        <h3 v-if="productHeader.header_id === 5 && productHeader.product_id === item.id" class="text-sm font-semibold text-gray-700 line-clamp-1 mb-2">
                            Proyecto Asignado: {{ productHeader.content }}
                        </h3>
                        <h3 v-if="productHeader.header_id === 7 && productHeader.product_id === item.id" class="text-sm font-semibold text-gray-700 line-clamp-1 mb-2">
                            Fecha: {{ productHeader.content }}
                        </h3>
                        <h3 v-if="productHeader.header_id === 12 && productHeader.product_id === item.id" class="text-sm font-semibold text-gray-700 line-clamp-1 mb-2">
                            N° de Serie: {{ productHeader.content }}
                        </h3>
                    </div>
                </div>
            </div>
            <br>
            <div class="flex flex-col items-center border-t px-5 py-5 xs:flex-row xs:justify-between">
                <pagination :links="products.links" />
            </div>
        </div>
  
      <ConfirmDeleteModal :confirmingDeletion="confirmingDocDeletion" itemType="Producto" :deleteFunction="deleteProduct"
        @closeModal="closeModalDoc" />
    </AuthenticatedLayout>
  </template>
    
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Pagination from '@/Components/Pagination.vue'
import ConfirmDeleteModal from '@/Components/ConfirmDeleteModal.vue';
import { ref } from 'vue';
import { Head, router, Link } from '@inertiajs/vue3';
import { TrashIcon, PencilIcon, EyeIcon } from '@heroicons/vue/24/outline';

const props = defineProps({
  products: Object,
  warehouse: Object
});


const productHeaders = props.products.data.flatMap(product => product.product_headers);
  const confirmingDocDeletion = ref(false);
  const docToDelete = ref(null);
  
  const confirmDeleteProduct = (productId) => {
    docToDelete.value = productId;
    confirmingDocDeletion.value = true;
  };
  
  const closeModalDoc = () => {
    confirmingDocDeletion.value = false;
  };

  const createProduct = () => {
    router.visit(route('warehouses.createProduct', { warehouse: props.warehouse.id }));
  };
  
  const deleteProduct = () => {
    const docId = docToDelete.value;
    if (docId) {
      router.delete(route('warehouses.destroyProduct', { warehouse: props.warehouse.id, product: docId }), {
        onSuccess: () => closeModalDoc()
      });
    }
  };

  </script>
  
    