<template>

    <Head title="Solicitudes del proyecto" />
    <AuthenticatedLayout :redirect-route="'warehouses.warehouses'">
        <template #header>
            Despachos de {{ warehouse.name }}
        </template>
        <div class="min-w-full overflow-hidden rounded-lg shadow">
            <div class="flex gap-2 m-1 justify-end items-center">
                <select   
                    class="border rounded-md px-4 py-1 w-[150px]"
                    @change="optionChange"
                >
                    <option>Por Aprobar</option>
                    <option>Aprobados</option>
                    <option selected>Rechazados</option>
                </select>

            </div>
            <div class="overflow-x-auto">
                <table class="w-full ">
                    <thead>
                        <tr
                            class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Proyecto
                            </th> 
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                CPE
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Código
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Nombre
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Cantidad Solicitada
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Cantidad Actual de Salidas
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Unidad
                            </th>
                           
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Fecha de Solicitud
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <template v-for="item in disToApToCom.data" :key="item.id">
                            
                            <tr  class="text-gray-700">
                                <td class="border-b border-gray-300 bg-white px-5 py-5 text-sm">
                                    <p class="text-gray-900 ">{{ item.special_inventory.cpe }}</p>
                                </td>
                                <td class="border-b border-gray-300 bg-white px-5 py-5 text-sm">
                                    <p class="text-gray-900 ">{{ item.project.code }}</p>
                                </td>
                                <td class="border-b border-gray-300 bg-white px-5 py-5 text-sm">
                                    <p class="text-gray-900 ">
                                        {{ item.special_inventory.purchase_product.code }}
                                    </p>
                                </td>
                                <td class="border-b border-gray-300 bg-white px-5 py-5 text-sm">
                                    <p class="text-gray-900 ">
                                        {{ item.special_inventory.purchase_product.name }}
                                    </p>
                                </td>
                                
                                <td class="border-b border-gray-300 bg-white px-5 py-5 text-sm">
                                    <p class="text-gray-900 text-center ">{{ item.quantity }}</p>
                                </td>
                                <td class="border-b border-gray-300 bg-white px-5 py-5 text-sm">
                                    <p class="text-gray-900 text-center">{{ item.current_output_quantity }}</p>
                                </td>
                                <td class="border-b border-gray-300 bg-white px-5 py-5 text-sm">
                                    <p class="text-gray-900 ">
                                        {{ item.special_inventory.purchase_product.unit }}
                                    </p>
                                </td>
                                <td class="border-b border-gray-300 bg-white px-5 py-5 text-sm">
                                    <p class="text-gray-900 ">
                                        {{ formattedDate(item.created_at) }}
                                    </p>
                                </td>
                                <td class="border-b border-gray-300 bg-white px-5 py-5 text-sm">
                                    <div v-if="item.state === null"
                                        class="flex space-x-3 justify-center">
                                        <button 
                                            @click="()=>setDispatchStatus(item.id, true)"
                                            class="flex items-center text-blue-500 hover:underline">
                                            <svg 
                                                xmlns="http://www.w3.org/2000/svg" 
                                                fill="none" 
                                                viewBox="0 0 24 24"
                                                stroke-width="1.5" 
                                                stroke="currentColor" 
                                                class="w-6 h-6 text-green-500">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                            </svg>
                                        </button>
                                        <button 
                                            @click="()=>setDispatchStatus(item.id, false)"
                                            type="button"
                                            class="rounded-xl whitespace-no-wrap text-center text-sm text-red-900 hover:bg-red-200">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-red-500">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                            </svg>
                                        </button>
                                    </div>
                                    <div v-else>
                                        <p v-if="item.state == true" :class="'text-green-500 whitespace-nowrap'">
                                            Aceptado - <span class="text-red-500">Incompleto</span>
                                        </p>
                                        <p v-if="item.state == false" :class="'text-red-500'">
                                            Rechazado
                                        </p>
                                    </div>
                                </td>
                                <td class="border-b border-gray-300 bg-white px-5 py-5 text-sm">
                                    <button 
                                        @click="()=>showModalOutput(item.id)"
                                        class="text-blue-900 whitespace-no-wrap"
                                        :disabled="item.state?false:true"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" :class="`w-6 h-6 ${item.state?'':'opacity-50'}`">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5m8.25 3v6.75m0 0-3-3m3 3 3-3M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" />
                                        </svg>
                                    </button>
                                </td>
    
                                <!-- Expandible row -->
                                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                    <button 
                                        :disabled="item.project_entry_outputs.length>0?false:true"
                                        type="button" 
                                        @click="toggleDetails(item.project_entry_outputs)"
                                        :class="`text-blue-900 `">
                                        <svg v-if="row !== item.id" xmlns="http://www.w3.org/2000/svg"
                                            fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                            :class="`w-6 h-6 ${item.project_entry_outputs.length>0?'':'opacity-50'}`">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                        </svg>
                                        <svg v-else xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="m4.5 15.75 7.5-7.5 7.5 7.5" />
                                        </svg>
                                    </button>
                                </td>
    
                            </tr>
                            <template v-if="row == item.id">
                                
                                <tr class="bg-white h-16">
                                    <td colspan="11" class="py-5 px-10">
                                        <table class="w-full ">
                                            <thead>
                                                <tr
                                                    class="border-b-2 border-gray-200 bg-white text-left text-xs font-semibold uppercase tracking-wide text-gray-900">
                                                    <th
                                                        class="px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider ">
                                                        N° de Salida
                                                    </th> 
                                                    <th
                                                        class="px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider ">
                                                        Cantidad
                                                    </th>
                                                    <th
                                                        class="px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider ">
                                                        Fecha de Salida
                                                    </th>
                                                    
                                                    <th v-if="auth.user.role_id === 1"
                                                        class=" px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider ">
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="(subItem, i) in item.project_entry_outputs" :key="subItem.id"
                                                class="bg-gray-100">
                                                    <td class="border-b w-auto text-center border-gray-200 bg-white px-5 py-5 text-sm">
                                                        {{ i+1 }}
                                                    </td>
                                                    <td class="border-b text-center border-gray-200 bg-white px-5 py-5 text-sm">
                                                        <p class="text-gray-900 whitespace-no-wrap">
                                                            {{ subItem.quantity }}
                                                        </p>
                                                    </td>
                                                    <td class="border-b text-center border-gray-200 bg-white px-5 py-5 text-sm">
                                                        <p class="text-gray-900 whitespace-no-wrap">
                                                            {{ formattedDate(subItem.created_at) }}
                                                        </p>
                                                    </td>
                                                    <td v-if="auth.user.role_id === 1"
                                                        class="border-b text-center border-gray-200 bg-white px-5 py-5 text-sm">
                                                        <button 
                                                            @click="()=>deleteOutput(subItem.id)"
                                                            type="button"
                                                            class="text-blue-900 ">
                                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                                stroke-width="1.5" stroke="currentColor" class="w-4 h-4 text-red-500">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                                            </svg>
                                                        </button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>                               
                            </template>
                        </template>
                    </tbody> 
                </table>
            </div>
            <div class="flex flex-col items-center border-t bg-white px-5 py-5 xs:flex-row xs:justify-between">
                <pagination :links="disToApToCom.links" />
            </div>

            <Modal :show="showModal">
                <form class="p-6" @submit.prevent="submit">
                    <h2 class="text-lg font-medium text-gray-900">
                        Registrar la salida
                    </h2>
                    <div class="grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-6 mt-2">
                        <div class="sm:col-span-6">
                            <InputLabel for="quantity" class="font-medium leading-6 text-gray-900">Cantidad</InputLabel>
                            <div class="mt-2">
                                <input id="quantity" type="number" min="1" v-model="form.quantity"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError 
                                    :message="form.errors.quantity"
                                />
                            </div>
                        </div>
                    </div>
                    <div class="mt-6 flex gap-3 justify-end">
                        <button
                            class="inline-flex items-center p-2 rounded-md font-semibold bg-red-500 text-white hover:bg-red-400"
                            type="button" @click="closeModal"> Cerrar </button>
                        <button
                            class="inline-flex items-center p-2 rounded-md font-semibold bg-indigo-500 text-white hover:bg-indigo-400"
                            type="submit"> Agregar </button>
                    </div>
                </form>
            </Modal>

            
        </div>
        <ConfirmCreateModal :confirmingcreation="showSuccessModal" itemType="salida" />
        <!-- <ConfirmDeleteModal :confirmingDeletion="confirmDelete" itemType="producto"
            :nameText="`esta entrada del producto ${prod.name}`" :deleteFunction="deleteProduct"
            @closeModal="closeModal" />

        <ErrorOperationModal :showError="showError" title="No hay presupuesto"
            message="La cantidad disponible del presupuesto esta en cero o no está definido" /> -->
    </AuthenticatedLayout>
</template>
<script setup>
import ErrorOperationModal from '@/Components/ErrorOperationModal.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ConfirmDeleteModal from '@/Components/ConfirmDeleteModal.vue';
import ConfirmCreateModal from '@/Components/ConfirmCreateModal.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import Modal from '@/Components/Modal.vue';
import Pagination from '@/Components/Pagination.vue';
import { ref } from 'vue';
import { formattedDate } from '@/utils/utils';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
const showError = ref(false)


const { warehouse, disToApToCom } = defineProps({
    warehouse: Object,
    disToApToCom: Object,
    auth: Object,
});

//Expandible row
const row = ref(0);
const toggleDetails = (outputs) => {
    console.log(outputs[0].project_entry_id)
    if (row.value === outputs[0].project_entry_id) {
        row.value = 0;
    } else {
        row.value = outputs[0].project_entry_id;
    }
}

//Activate Deactivate
const setDispatchStatus = (id, state) => {
    router.post(
        route('inventory.special_dispatch.accept_decline', {project_entry_id:id}),
        {state},
    )
}


//Registrate output
const showModal = ref(false)
const showSuccessModal = ref(false)
const form = useForm({
    project_entry_id:'',
    quantity:''
})
                                    
const showModalOutput = (id) => {
    form.project_entry_id = id
    showModal.value = true
}

const closeModal = () => {
    showModal.value = false
    form.reset()
}


const submit = () => {
    form.post(route('inventory.special_dispatch_output.store'), {
        onSuccess: () => {
            closeModal();
            showSuccessModal.value = true
            setTimeout(()=>{
                showSuccessModal.value = false
            }, 2000)
        },
    })
}
const deleteOutput = (id) => {
    router.delete(route('inventory.special_dispatch_output.destroy', {
        project_entry_output_id:id
    }))
}


const optionChange = (e) => {
    if (e.target.value === "Por Aprobar" ) {
        router.get(route('inventory.special_dispatch.approved', {warehouse_id: warehouse.id}))
    } else if (e.target.value === "Aprobados") {
        router.get(route('inventory.special_dispatch.approved', {warehouse_id: warehouse.id}))
    }
}




</script>