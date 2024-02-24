<template>
    <Head title="Depositos" />
    <AuthenticatedLayout>
        <template #header>
            Resumen Generado
        </template>

        <div class="min-w-full rounded-lg">
            <br>
            <div class="overflow-x-auto">
                <table class="w-full ">
                    <thead>
                        <tr
                            class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                            <th class="border-2 border-gray-200 bg-gray-100 px-3 py-3 text-xs font-semibold uppercase tracking-wider text-gray-600 text-center">
                                #
                            </th>
                            <th
                                v-for="(column, index) in columns"
                                :key="index"
                                class="border-2 border-gray-200 bg-gray-100 px-3 py-3 text-xs font-semibold uppercase tracking-wider text-gray-600 text-center"
                            >
                                {{ column }}
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item, i) in deposits" :key="item.id" class="text-gray-700">
                            <td class="text-center border border-gray-200 bg-white px-3 py-2 text-sm">
                                <p class="text-gray-900">{{ i + 1 }}</p>
                            </td>
                            <td v-if="item.operation_date" class="text-center sticky left-0 z-10 border border-gray-200 bg-white px-3 py-2 text-sm">
                                <p class="text-gray-900 ">{{ item.operation_date }}</p>
                            </td>
                            <td v-if="item.operation_code" class="text-center border border-gray-200 bg-white px-3 py-2 text-sm">
                                <p class="text-gray-900 ">{{ item.operation_code }}</p>
                            </td>
                            <td v-if="item.operation_description" class="text-center border border-gray-200 bg-white px-3 py-2 text-sm">
                                <p class="text-gray-900">{{ item.operation_description }}</p>
                            </td>
                            <td v-if="item.accounting_account" class="text-center border border-gray-200 bg-white px-3 py-2 text-sm">
                                <!-- Verifica si accounting_account está presente -->
                                <p class="text-gray-900">{{ item.accounting_account.code }}</p>
                            </td>
                            <td  v-if="item.denomination" class="border border-gray-200 bg-white px-3 py-2 text-sm">
                                <p class="text-gray-900">{{ item.denomination }}</p>
                            </td>
                            <td  v-if="item.observation" class="border border-gray-200 bg-white px-3 py-2 text-sm">
                                <p class="text-gray-900">{{ item.observation }}</p>
                            </td>
                            <td v-if="item.comission" class="text-right border border-gray-200 bg-white px-3 py-2 text-sm">
                                <!-- Verifica si comission está presente -->
                                <p class="text-gray-900">{{ (item.comission).toFixed(2) }}</p>
                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>
        </div>

    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

const { deposits, accounts } = defineProps({
    columns: Array,
    deposits: Object
})

const summaryForm = useForm({
    code: '',
    start_date: '',
    end_date: '',
    columns: []
});

</script>