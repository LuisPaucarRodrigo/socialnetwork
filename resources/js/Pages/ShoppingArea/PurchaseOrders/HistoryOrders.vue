<template>
    <Head title="Ordenes de Compra" />
    <AuthenticatedLayout :redirectRoute="'purchaseorders.index'">
        <template #header>
            Ordenes
        </template>
        <div class="min-w-full overflow-hidden rounded-lg shadow">
            <div class="overflow-x-auto">
                <table class="w-full whitespace-no-wrap">
                    <thead>
                        <tr
                            class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Proyecto
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Descripcion
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Fecha de Emision
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Estado
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="order in orders.data" :key="order.id" class="text-gray-700">
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{
                                    order.purchase_quote.purchasing_requests.project?.name }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ order.purchase_quote.response }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ formattedDate(order.purchase_arrival_date) }}</p>
                            </td>

                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <select id="selectState" @change="updateState(order.id, $event.target.value)"
                                    :disabled="order.state == 'Completada'">
                                    <option selected disabled>{{ order.state }}</option>
                                    <option>Pendiente</option>
                                    <option>En Proceso</option>
                                    <option>Completada</option>
                                </select>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="flex flex-col items-center border-t bg-white px-5 py-5 xs:flex-row xs:justify-between">
                <pagination :links="orders.links" />
            </div>
        </div>

    </AuthenticatedLayout>
</template>
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import { Head } from '@inertiajs/vue3';
import { formattedDate } from '@/utils/utils';
import { EyeIcon } from '@heroicons/vue/24/outline';

const props = defineProps({
    orders: Object
})

</script>
