<template>

    <Head title="Proyectos Completados" />
    <AuthenticatedLayout :redirectRoute="'cicsa.index'">
        <template #header>
            Proyecto Completado
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
                                Estado
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Monto
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Fecha de Abono de Cuenta Corriente
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Numero de Transacción de Cuenta Corriente
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Monto de Cuenta Corriente
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Fecha de Abono de la detraccion
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Numero de Transacción de la detraccion
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Monto de la detraccion
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Encargado
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in charge_areas.data" :key="item.id" class="text-gray-700">
                            <td class="border-b border-gray-200 bg-white px-5 py-3 text-[13px]">
                                <p class="text-gray-900 text-center">
                                    {{ item.cicsa_assignation.project_name }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-3 text-[13px]">
                                <p class="text-gray-900 text-center">
                                    {{ item?.invoice_number }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-3 text-[13px]">
                                <p class="text-gray-900 text-center">
                                    {{ formattedDate(item?.invoice_date) }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 px-5 py-3 text-[13px]"
                                :class="!item.credit_to ? 'bg-red-200' : 'bg-white'">
                                <p class="text-gray-900 text-center">
                                    {{ item?.credit_to ? item.credit_to + ' día(s)' : '' }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 px-5 py-3 text-[13px]"
                                :class="!item?.deposit_date ? 'bg-red-200' : 'bg-white'">
                                <p class="text-gray-900 text-center">
                                    {{ formattedDate(item?.payment_date) }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 px-5 py-3 text-[13px]"
                                :class="item.days_late > 0 ? 'bg-red-200' : 'bg-white'">
                                <p class="text-gray-900 text-center">
                                    {{ item?.invoice_date && item?.credit_to ?
        item.days_late + ' día(s)' : '' }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 px-5 py-3 text-[13px]"
                                :class="item?.state === 'Con deuda' && !item?.credit_to ? 'bg-red-200' : 'bg-white'">
                                <p class="text-gray-900 text-center">
                                    {{ item?.invoice_date && item?.credit_to ?
        item?.state : '' }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-3 text-[13px] whitespace-nowrap">
                                <p class="text-gray-900 text-center">
                                    {{ item?.amount ? 'S/. ' +
        item?.amount.toFixed(2) : ''
                                    }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-3 text-[13px]">
                                <p class="text-gray-900 text-center">
                                    {{ formattedDate(item?.deposit_date) }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-3 text-[13px]">
                                <p class="text-gray-900 text-center">
                                    {{ item?.transaction_number_current }}
                                </p>
                            </td>
                            <td class="whitespace-nowrap border-b border-gray-200 bg-white px-5 py-3 text-[13px]">
                                <p class="text-gray-900 text-center">
                                    {{ item?.checking_account_amount ? 'S/. ' +
        item?.checking_account_amount.toFixed(2) : ''
                                    }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-3 text-[13px]">
                                <p class="text-gray-900 text-center">
                                    {{ formattedDate(item?.deposit_date_bank) }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-3 text-[13px]">
                                <p class="text-gray-900 text-center">
                                    {{ item?.transaction_number_bank }}
                                </p>
                            </td>
                            <td class="whitespace-nowrap border-b border-gray-200 bg-white px-5 py-3 text-[13px]">
                                <p class="text-gray-900 text-center">
                                    {{ item?.amount_bank ? 'S/. ' +
        item?.amount_bank.toFixed(2) : ''
                                    }}
                                </p>
                            </td>

                            <td class="border-b border-gray-200 px-5 py-3 text-[13px]"
                                :class="!item?.user_name ? 'bg-red-200' : 'bg-white'">
                                <p class="text-gray-900 text-center">
                                    {{ item?.user_name }}
                                </p>
                            </td>
                        </tr>
                        <tr class="sticky bottom-0 z-5">
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm" colspan="7">
                                Totales:
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm " colspan="3">
                                S/ {{ total_amount.toFixed(2) }}
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm" colspan="3">
                                S/ {{ total_checking_account_amount.toFixed(2) }}
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm" colspan="2">
                                S/ {{ total_amount_bank.toFixed(2) }}
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

const { charge_areas, total_checking_account_amount, total_amount_bank, total_amount } = defineProps({
    charge_areas: Object,
    total_amount: Object,
    total_checking_account_amount: Object,
    total_amount_bank: Object
})

</script>
