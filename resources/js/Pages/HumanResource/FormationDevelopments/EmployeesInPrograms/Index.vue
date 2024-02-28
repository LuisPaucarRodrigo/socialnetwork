
<template>
    <Head title="Empleados en programas" />

    <AuthenticatedLayout :redirectRoute="'management.employees.formation_development'">
        <template #header>
            Personal en programas de formación
        </template>
        <div class="min-w-full">
            <div class="overflow-x-auto">
                <table class="w-full rounded-lg shadow">
                    <thead>
                        <tr
                            class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                N°
                            </th>
                            <th
                                class="sticky left-0 z-10 border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Nombre y apellidos
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Teléfono 1
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Telefono 2
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Email
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                <div class="lg:w-full w-96 grid grid-cols-2 gap-3">
                                    <p class="text-left">Programa</p>
                                    <p class="text-center">Inicio-Fin</p>
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item, i) in employees.data" :key="item.id"
                            class="text-gray-700">
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ i + 1 }}</p>
                            </td>
                            <td class="sticky left-0 z-10 border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ item.name }} {{ item.lastname }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ item.phone1 }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ item.phone2 }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ item.email }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                
                                <div v-for="ap in item.assignated_programs" class="lg:w-full w-96 grid grid-cols-2 gap-3 mt-2">
                                    <p class="text-left">{{ ap.formation_program.name }}</p>
                                    <p class="text-center">{{ formattedDate(ap.start_date)}} - {{formattedDate(ap.end_date)}}</p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="flex flex-col items-center border-t bg-white px-5 py-5 xs:flex-row xs:justify-between">
                <pagination :links="employees.links" />
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { EyeIcon, TrashIcon } from '@heroicons/vue/24/outline';
import { ref, defineProps } from 'vue';
import ConfirmDeleteModal from '@/Components/ConfirmDeleteModal.vue';
import {formattedDate} from '@/utils/utils.js';

const {employees} = defineProps({
    employees: Object
})



</script>