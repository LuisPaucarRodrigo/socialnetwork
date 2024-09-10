<template>

    <Head title="CICSA Área de Cobranza" />
    <AuthenticatedLayout :redirectRoute="'cicsa.index'">
        <template #header>
            Proyecto Cicsa por Cobrar
        </template>
        <div class="min-w-full rounded-lg shadow">
            <div class="overflow-x-auto h-full">
                <table class="w-full whitespace-no-wrap">
                    <thead>
                        <tr
                            class="sticky top-0 z-20 border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Nombre de Proyecto
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Codigo de Proyecto
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                CPE
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Número de Factura
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Fecha de Factura
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Crédito a
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Fecha de Pago
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Días Atrasados
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Fecha de Abono
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Estado
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Monto
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Encargado
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in charge_areas.data ?? charge_areas" :key="item.id" class="text-gray-700">
                            <td class="border-b border-gray-200 bg-white px-5 py-3 text-[13px]" :class="!item.project_code ? 'bg-red-200' :'bg-white'">
                                <p class="text-gray-900 text-center">
                                    {{ item.project_name }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 px-5 py-3 text-[13px]" :class="!item.project_code ? 'bg-red-200' :'bg-white'">
                                <p class="text-gray-900 text-center">
                                    {{ item.project_code }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 px-5 py-3 text-[13px]" :class="!item.cpe ? 'bg-red-200' :'bg-white'">
                                <p class="text-gray-900 text-center">
                                    {{ item.cpe }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 px-5 py-3 text-[13px]" :class="!item.cicsa_charge_area?.invoice_number ? 'bg-red-200' :'bg-white'">
                                <p class="text-gray-900 text-center">
                                    {{ item.cicsa_charge_area?.invoice_number }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 px-5 py-3 text-[13px]" :class="!item.cicsa_charge_area?.invoice_date ? 'bg-red-200' :'bg-white'">
                                <p class="text-gray-900 text-center">
                                    {{ formattedDate(item.cicsa_charge_area?.invoice_date) }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 px-5 py-3 text-[13px]" :class="!item.cicsa_charge_area.credit_to ? 'bg-red-200' :'bg-white'">
                                <p class="text-gray-900 text-center">
                                    {{ item.cicsa_charge_area?.credit_to ? item.cicsa_charge_area.credit_to + ' día(s)'
                                    : '' }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 px-5 py-3 text-[13px]" :class="!item.cicsa_charge_area?.payment_date ? 'bg-red-200' :'bg-white'">
                                <p class="text-gray-900 text-center">
                                    {{ formattedDate(item.cicsa_charge_area?.payment_date) }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 px-5 py-3 text-[13px]" :class="item.cicsa_charge_area.days_late < 0 ? 'bg-red-200' :'bg-white'">
                                <p class="text-gray-900 text-center">
                                    {{ item.cicsa_charge_area?.invoice_date && item.cicsa_charge_area?.credit_to ?
                                        item.cicsa_charge_area.days_late + ' día(s)' : '' }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 px-5 py-3 text-[13px]" :class="!item.cicsa_charge_area?.deposit_date ? 'bg-red-200' :'bg-white'">
                                <p class="text-gray-900 text-center">
                                    {{ formattedDate(item.cicsa_charge_area?.deposit_date) }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 px-5 py-3 text-[13px]" :class="item.cicsa_charge_area?.state === 'Con deuda' && !item.cicsa_charge_area?.credit_to ? 'bg-red-200' :'bg-white'">
                                <p class="text-gray-900 text-center">
                                    {{ item.cicsa_charge_area?.invoice_date && item.cicsa_charge_area?.credit_to ?
                                    item.cicsa_charge_area?.state : '' }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 px-5 py-3 text-[13px] whitespace-nowrap" :class="!item.cicsa_charge_area?.amount ? 'bg-red-200' :'bg-white'">
                                <p class="text-gray-900 text-center">
                                    {{ item.cicsa_charge_area?.amount ? 'S/. ' +
                                        item.cicsa_charge_area?.amount.toFixed(2) : ''
                                    }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 px-5 py-3 text-[13px]" :class="!item.cicsa_charge_area?.user_name ? 'bg-red-200' :'bg-white'">
                                <p class="text-gray-900 text-center">
                                    {{ item.cicsa_charge_area?.user_name }}
                                </p>
                            </td>
                        </tr>
                        <tr class="sticky bottom-0 z-5">
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm whitespace-nowrap" colspan="10">Totales:
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm" colspan="2">
                                S/ {{ total_amount }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="flex flex-col items-center border-t bg-white px-5 py-5 xs:flex-row xs:justify-between">
                <pagination :links="charge_areas.links" />
            </div>
        </div>
    </AuthenticatedLayout>
</template>


<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import { Head } from '@inertiajs/vue3';
import { formattedDate } from '@/utils/utils.js';

const { charge_areas, total_amount } = defineProps({
    charge_areas: Object,
    total_amount: Object
})

</script>
