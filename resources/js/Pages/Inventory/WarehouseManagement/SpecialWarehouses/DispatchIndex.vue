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
                    <option>Rechazados</option>
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
                                    <p class="text-gray-900 ">
                                        {{ item.special_inventory.purchase_product.unit }}
                                    </p>
                                </td>
                                <td class="border-b border-gray-300 bg-white px-5 py-5 text-sm">
                                    <p class="text-gray-900 ">
                                        {{ formattedDate(item.created_at) }}
                                    </p>
                                </td>
                                <td  class="border-b border-gray-300 bg-white px-5 py-5 text-sm">
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
                                </td>
                            </tr>
                        </template>
                    </tbody> 
                </table>
            </div>
            <div class="flex flex-col items-center border-t bg-white px-5 py-5 xs:flex-row xs:justify-between">
                <pagination :links="disToApToCom.links" />
            </div>
        </div>
       
    </AuthenticatedLayout>
</template>
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import { formattedDate } from '@/utils/utils';
import { Head, router } from '@inertiajs/vue3';


const { warehouse, disToApToCom, userPermissions } = defineProps({
    warehouse: Object,
    disToApToCom: Object,
    auth: Object
});



//Activate Deactivate
const setDispatchStatus = (id, state) => {
    router.post(
        route('inventory.special_dispatch.accept_decline', {project_entry_id:id}),
        {state},
    )
}


const optionChange = (e) => {
    if (e.target.value === "Rechazados" ) {
        router.get(route('inventory.special_dispatch.rejected', {warehouse_id: warehouse.id}))
    } else if (e.target.value === "Aprobados") {
        router.get(route('inventory.special_dispatch.approved', {warehouse_id: warehouse.id}))
    }
}

</script>