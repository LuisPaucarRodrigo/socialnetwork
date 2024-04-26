<template>

    <Head title="Proyectos" />
    <AuthenticatedLayout :redirectRoute="'warehouses.warehouses'">
        <template #header>
            Registrar Salidas
        </template>

        <div class="inline-block min-w-full overflow-hidden rounded-lg shadow">
            <div>
                <Link type="button" :href="route('warehouses.outputs_history', { warehouse: warehouse })"
                    class="rounded-md bg-teal-600 px-4 py-2 text-center text-sm text-white hover:bg-teal-500 ">
                historial de salidas
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
                                Cantidad Asignada
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
                        <tr v-for="item in project_products.data" :key="item.id" :class="[
        'text-gray-700',
        {
            'border-l-8': true,
            'border-green-500': item.state === 'Completo',
            'border-red-500': item.state === 'Incompleto'
        }
    ]">
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ item.product?.name }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ item.quantity }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ item.total_output_project_product }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ item.observation }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ formatearFecha(item.created_at) }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <div v-if="item.state === 'Incompleto'" class="flex space-x-3 justify-center">
                                    <button class="text-blue-900 whitespace-no-wrap"
                                        @click="showToAddProduct(item.id, item.quantity, item.total_output_project_product)">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5m8.25 3v6.75m0 0-3-3m3 3 3-3M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" />
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
            <pagination :links="project_products.links" />
        </div>
        <Modal :show="showModal">
            <form class="p-6" @submit.prevent="submit">
                <h2 class="text-lg font-medium text-gray-900">
                    Registrar la salida
                </h2>
                <div class="grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-6 mt-2">
                    <div class="sm:col-span-3">
                        <InputLabel for="quantity">Cantidad</InputLabel>
                        <div class="mt-2">
                            <TextInput id="quantity" type="number" min="1" v-model="form.quantity" />
                        </div>
                    </div>

                    <div class="sm:col-span-3">
                        <InputLabel for="observation">Observaciones
                        </InputLabel>
                        <div class="mt-2">
                            <textarea id="observation" type="text" v-model="form.observation"
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                        </div>
                    </div>

                </div>
                <div class="mt-6 flex gap-3 justify-end">
                    <SecondaryButton type="button" @click="closeModal"> Cerrar </SecondaryButton>
                    <PrimaryButton type="submit"> Agregar </PrimaryButton>
                </div>
            </form>
        </Modal>
        <SuccessOperationModal :confirming="successAsignation" title="Salida registrada"
            message="Salida registrada de forma exitosa" />
        <ErrorOperationModal :showError="errorAsignation" title="Cantidad Excedida"
            message="No es una cantidad válida" />
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import Pagination from '@/Components/Pagination.vue'
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import Modal from '@/Components/Modal.vue';
import SuccessOperationModal from '@/Components/SuccessOperationModal.vue';
import ErrorOperationModal from '@/Components/ErrorOperationModal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';



const { project_products, warehouse } = defineProps({
    project_products: Object,
    warehouse: String
})

//Modal functions
const showModal = ref(false);
const showToAddProduct = (id, quantity, sent_quantity) => {
    showModal.value = true;
    form.project_product_id = id
    form.quantity = quantity - sent_quantity
}
const closeModal = () => {
    showModal.value = false;
};


//form
const successAsignation = ref(false)
const errorAsignation = ref(false)
const initialState = {
    project_product_id: '',
    quantity: '',
    observation: ''
}
const form = useForm({ ...initialState })
const submit = () => {
    if (sufficientQuantity(form)) {
        form.post(route('projectmanagement.outputs.store'), {
            onSuccess: () => {
                form.reset();
                successAsignation.value = true
                setTimeout(() => {
                    successAsignation.value = false
                }, 2000)
                closeModal()
            }
        })
    } else {
        errorAsignation.value = true
        setTimeout(() => {
            errorAsignation.value = false
        }, 1500)
    }
}

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
    return form.quantity <= (product.quantity - product.total_output_project_product);
}

</script>