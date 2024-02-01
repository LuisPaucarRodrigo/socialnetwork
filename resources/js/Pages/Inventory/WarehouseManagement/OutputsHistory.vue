<template>
    <Head title="Proyectos" />
    <AuthenticatedLayout>
        <template #header>
            Historial
        </template>

        <div class="inline-block min-w-full overflow-hidden rounded-lg shadow">
            <div>
                <Link type="button" :href="route('warehouses.outputs', {warehouse: warehouse})"
                    class="rounded-md bg-zinc-600 px-4 py-2 text-center text-sm text-white hover:bg-zinc-500 ">
                    Volver
                </Link>
            </div>
            <div class="talwing mt-4">
                <table class="w-full whitespace-no-wrap">
                    <thead>
                        <tr
                            class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Descripcion
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Proyecto
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Cantidad Enviada
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Observaciones
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Fecha
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in output_products.data" :key="item.id" :class="[
                            'text-gray-700',
                        ]">
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ item.project_product?.product?.name }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ item.project_product?.project?.name }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ item.quantity }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ item.observation }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ formatearFecha(item.created_at) }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <div class="flex space-x-3 justify-center">
                                    <button class="text-red-500 whitespace-no-wrap" @click="confirmDeleteOutput(item.id)">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="flex flex-col items-center border-t bg-white px-5 py-5 xs:flex-row xs:justify-between">
            <pagination :links="output_products.links" />
        </div>
            
        <ConfirmDeleteModal :confirmingDeletion="confirmingDeletion" itemType="Salida de almacen" :deleteFunction="deleteOutput"
        @closeModal="closeModalDoc" />

        <SuccessOperationModal :confirming="successAsignation" title="Salida Eliminada" message="Salida registrada de forma exitosa" />
        <ErrorOperationModal :showError="errorAsignation" title="Cantidad Excedida"
        message="No es una cantidad válida" />
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import Pagination from '@/Components/Pagination.vue'
import ConfirmDeleteModal from '@/Components/ConfirmDeleteModal.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import Modal from '@/Components/Modal.vue';
import SuccessOperationModal from '@/Components/SuccessOperationModal.vue';
import ErrorOperationModal from '@/Components/ErrorOperationModal.vue';


const {output_products, warehouse} = defineProps({
    output_products: Object,
    warehouse: String
})

console.log(output_products.data)


//Modal functions
const confirmingDeletion = ref(false);
const outPutToDelete = ref(null);
const confirmDeleteOutput = (output_id) => {
    outPutToDelete.value = output_id;
    confirmingDeletion.value = true;
};
const closeModalDoc = () => {
    confirmingDeletion.value = false;
  };


//delete
const successAsignation = ref(false)
const errorAsignation = ref(false)
const deleteOutput = () => {
    const output_id = outPutToDelete.value;
    if (output_id) {
      router.delete(route('warehouses.output_delete', { output: output_id }), {
        onSuccess: () => {
            closeModalDoc()
            successAsignation.value = true
            setTimeout(() => {
                successAsignation.value = false
            },2000)
        }
      });
    }
  };



//formatear fecha and check quantity
function formatearFecha(fecha) {
  const date = new Date(fecha);
  const dia = date.getDate().toString().padStart(2, '0');
  const mes = (date.getMonth() + 1).toString().padStart(2, '0');
  const año = date.getFullYear();
  return `${dia}/${mes}/${año}`;
}

const sufficientQuantity = (form) => {
    const arrayValues = Object.values(project_products.data);
    let product = arrayValues.find((i) => i.id == form.project_product_id)
    return form.quantity <= product.quantity;
}

</script>