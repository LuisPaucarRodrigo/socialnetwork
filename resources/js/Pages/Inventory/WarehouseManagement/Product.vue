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
            
            <div class="inline-block min-w-full overflow-hidden rounded-lg shadow">
    <table class="w-full whitespace-no-wrap overflow-auto">
        <thead>
            <tr class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                    Nombre
                </th>
                <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                    Proyecto Asignado
                </th>
                <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                    Fecha
                </th>
                <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                    N° de Serie
                </th>
                <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                    Acciones
                </th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="item in props.products.data" :key="item.id" class="text-gray-700 border-b">
                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                    <h2 class="text-sm font-semibold mb-3">{{ item.name }}</h2>
                </td>
                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                    <div v-for="productHeader in productHeaders" :key="productHeader.id">         
                        <h3 v-if="productHeader.header_id === 5 && productHeader.product_id === item.id" class="text-sm font-semibold text-gray-700 line-clamp-1 mb-2">
                            {{ productHeader.content }}
                        </h3>
                    </div>
                </td>
                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                    <div v-for="productHeader in productHeaders" :key="productHeader.id">         
                        <h3 v-if="productHeader.header_id === 7 && productHeader.product_id === item.id" class="text-sm font-semibold text-gray-700 line-clamp-1 mb-2">
                            {{ productHeader.content }}
                        </h3>
                    </div>
                </td>
                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                  <div v-for="productHeader in productHeaders" :key="productHeader.id">         
                        <h3 v-if="productHeader.header_id === 12 && productHeader.product_id === item.id" class="text-sm font-semibold text-gray-700 line-clamp-1 mb-2">
                            {{ productHeader.content }}
                        </h3>
                    </div>
                </td>
                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                    <div class="inline-flex justify-end gap-x-0 mb-4">
                        <Link :href="route('warehouses.showProduct', {warehouse: props.warehouse.id, product: item.id})" class="text-green-400 hover:underline"><EyeIcon class="h-4 w-4 ml-1" /></Link>
                        <Link :href="route('warehouses.editProduct', {warehouse: props.warehouse.id, product: item.id})" class="text-orange-400 hover:underline mx-2"><PencilIcon class="h-4 w-4 ml-1" /></Link>
                        <button @click="confirmDeleteProduct(item.id)" class="text-red-600 hover:underline"><TrashIcon class="h-4 w-4" /></button>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
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

console.log(props.products)


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
  
    