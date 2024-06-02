<template>

    <Head title="Solicitudes del proyecto" />
    <AuthenticatedLayout :redirect-route="'warehouses.warehouses'">
        <template #header>
            Devoluciones de {{ warehouse.name }}c
        </template>
        <div class="min-w-full overflow-hidden rounded-lg shadow">
            <div class="flex gap-2 m-1 justify-end items-center">
                <select   
                    class="border rounded-md px-4 py-1 w-[150px]"
                    @change="optionChange"
                >
                    <option>Por Aprobar</option>
                    <option selected>Historial</option>
                </select>

            </div>
            <div class="overflow-x-auto">
                <table class="w-full ">
                    <thead>
                        <tr
                            class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                CPE
                            </th> 
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Guía de Remisión
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Fecha de Ingreso
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Sub Almacen
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Zona
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Código de Producto
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Cantidad
                            </th>
                           
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Unidad
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Producto
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Número de Serie
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <template v-for="item in refunds.data" :key="item.id">
                            
                            <tr  class="text-gray-700">
                                <td class="border-b border-gray-300 bg-white px-5 py-5 text-sm">
                                    <p class="text-gray-900 ">
                                        {{ item.project_entry_liquidation
                                               .project_entry
                                               .special_inventory
                                               .cpe }}
                                    </p>
                                </td>
                                <td class="border-b border-gray-300 bg-white px-5 py-5 text-sm">
                                    <p class="text-gray-900 ">
                                        {{ item.project_entry_liquidation
                                               .project_entry
                                               .special_inventory
                                               .referral_guide }}
                                    </p>
                                </td>
                                <td class="border-b border-gray-300 bg-white px-5 py-5 text-sm">
                                    <p class="text-gray-900 ">
                                        {{ item.created_at }}
                                    </p>
                                </td>
                                <td class="border-b border-gray-300 bg-white px-5 py-5 text-sm">
                                    <p class="text-gray-900 ">
                                        {{ item.project_entry_liquidation
                                               .project_entry
                                               .special_inventory
                                               .sub_warehouse }}
                                    </p>
                                </td>
                                
                                <td class="border-b border-gray-300 bg-white px-5 py-5 text-sm">
                                    <p class="text-gray-900 text-center ">
                                        {{ item.project_entry_liquidation
                                               .project_entry
                                               .special_inventory
                                               .zone }}
                                    </p>
                                </td>
                                <td class="border-b border-gray-300 bg-white px-5 py-5 text-sm">
                                    <p class="text-gray-900 text-center">
                                        {{ item.project_entry_liquidation
                                               .project_entry
                                               .special_inventory
                                               .purchase_product
                                               .code }}
                                    </p>
                                </td>
                                <td class="border-b border-gray-300 bg-white px-5 py-5 text-sm">
                                    <p class="text-gray-900 ">
                                        {{ item.quantity }}
                                    </p>
                                </td>
                                <td class="border-b border-gray-300 bg-white px-5 py-5 text-sm">
                                    <p class="text-gray-900 ">
                                        {{ item.project_entry_liquidation
                                               .project_entry
                                               .special_inventory
                                               .purchase_product
                                               .unit }}
                                    </p>
                                </td>
                                <td class="border-b border-gray-300 bg-white px-5 py-5 text-sm">
                                    <p class="text-gray-900 ">
                                        {{ item.project_entry_liquidation
                                               .project_entry
                                               .special_inventory
                                               .purchase_product
                                               .name }}
                                    </p>
                                </td>
                                <td class="border-b border-gray-300 bg-white px-5 py-5 text-sm">
                                    <p class="text-gray-900 ">
                                        {{ item.project_entry_liquidation
                                               .project_entry
                                               .special_inventory
                                               .product_serial_number }}
                                    </p>
                                </td>
                                <td class="border-b border-gray-300 bg-white px-5 py-5 text-sm">
                                    <div>
                                        <p v-if="item.state == true" :class="'text-green-500 whitespace-nowrap'">
                                            Aceptado
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        </template>
                    </tbody> 
                </table>
            </div>
            <div class="flex flex-col items-center border-t bg-white px-5 py-5 xs:flex-row xs:justify-between">
                <pagination :links="refunds.links" />
            </div>
        </div>
    </AuthenticatedLayout>
</template>


<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import { Head, router } from '@inertiajs/vue3';


const { warehouse, refunds } = defineProps({
    warehouse: Object,
    refunds: Object,
    auth: Object,
});




const optionChange = (e) => {
    if (e.target.value === "Por Aprobar" ) {
        router.get(route('inventory.special_refund.index', {warehouse_id: warehouse.id}))
    }
}



</script>