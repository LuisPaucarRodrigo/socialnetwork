<template>

    <Head title="Gestion de Gastos Cuadrilla" />
    <AuthenticatedLayout :redirectRoute="'document.rrhh.status'">
        <template #header>
            {{ employee.name }} {{ employee.lastname }} 
        </template>
        <br>
        <div class="grid grid-cols-1 sm:grid-cols-4 gap-10">
            <div class="col-span-1 min-w-full rounded-lg shadow bg-white p-6 gap-6">
                <div v-for="pd in personalData" class="mb-4">
                    <p class="text-sm text-gray-700 font-medium">{{ pd.title }}</p>
                    <p class="text-lg text-gray-900 break-words">
                        {{ getProp({obj:employee, path:pd.propName, type:pd.propType}) }}
                    </p>
                </div>
            </div>

            <div class="col-span-1 sm:col-span-3 min-w-full rounded-lg shadow bg-white p-6 grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div class="mb-4">
                    <p class="text-sm text-gray-700 font-medium">Código de Solicitud:</p>
                    <p class="text-lg text-gray-900">
                        una solicitud
                    </p>
                </div>
                <div class="mb-4">
                    <p class="text-sm text-gray-700 font-medium">Título de Solicitud:</p>
                    <p class="text-lg text-gray-900">
                        titulo de solicitud
                    </p>
                </div>
                <div class="mb-4">
                    <p class="text-sm text-gray-700 font-medium">Estado:</p>
                    <p class="text-lg text-gray-900">
                        un estado
                    </p>
                </div>
                <div class="mb-4">
                    <p class="text-sm text-gray-700 font-medium">Fecha límite de compra:</p>
                    <p class="text-lg text-gray-900">
                        una fecha limite
                    </p>
                </div>
                <div class="mb-4">
                    <p class="text-sm text-gray-700 font-medium">IGV: </p>
                    <p class="text-lg text-gray-900 whitespace-nowrap">
                        un igv
                    </p>
                </div>
            </div>

        </div>



      
       
        <br>
        <br>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { EyeIcon } from '@heroicons/vue/24/outline';
import { Head } from '@inertiajs/vue3';
import { formattedDate } from '@/utils/utils';
import { principalData, personalData, getProp } from './constants';

const {
    employee
} = defineProps({
    employee:Object
})

console.log(employee)


</script>
