<template>
    <Head title="Tarea1" />
    <AuthenticatedLayout>
        <template #header>
            Tarea 1
        </template>

        <div class="mb-4">
            <label for="description" class="block text-sm font-medium text-gray-700">Comentario</label>
            <textarea id="description" v-model="description" rows="4"
                class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring focus:border-blue-300"></textarea>
        </div>

        <div class="grid grid-cols-2 gap-4 m-5 mb-10">
            <!-- Columna 1 - Fecha de Inicio -->
            <div>
                <label for="startDate" class="block text-sm font-medium text-gray-700">Fecha de Inicio</label>
                <p id="startDate" class="text-sm text-gray-500">2023-01-01</p>
            </div>

            <!-- Columna 2 - Fecha de Fin -->
            <div>
                <label for="endDate" class="block text-sm font-medium text-gray-700">Fecha de Fin</label>
                <p id="endDate" class="text-sm text-gray-500">2023-12-31</p>
            </div>
        </div>


        <div class="mb-4 flex items-center">
            <label for="description" class="block text-sm font-medium text-gray-700 mr-2">Personal Encargado</label>

            <Link class="text-blue-900 whitespace-no-wrap" :href="route('tasks.new')">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 0110.374 21c-2.331 0-4.512-.645-6.374-1.766z" />
            </svg>
            </Link>
        </div>

        <div class="ml-5 mb-5 m-2">
            <ul class="list-disc pl-4">
                <li class="mb-2">
                    <p class="text-sm font-medium text-gray-700">Recurso 1</p>
                </li>
                <li class="mb-2">
                    <p class="text-sm font-medium text-gray-700">Recurso 2</p>
                </li>
            </ul>
        </div>
        <div class="mb-4 mb-5 flex items-center">
            <label for="description" class="block text-sm font-medium text-gray-700 mr-2">Recursos Asignados</label>

            <Link class="text-blue-900 whitespace-no-wrap" :href="route('tasks.new')">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>

            </Link>
        </div>
        <div class="ml-5 m-2">
            <ul class="list-disc pl-4">
                <li class="mb-2">
                    <p class="text-sm font-medium text-gray-700">Recurso 1</p>
                </li>
                <li class="mb-2">
                    <p class="text-sm font-medium text-gray-700">Recurso 2</p>
                </li>
            </ul>
        </div>

        <!-- Botón de enviar -->
        <div class="mt-6 flex items-center justify-end gap-x-6">
            <button @click="submitForm"
                class="bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 focus:outline-none focus:ring focus:border-blue-300">
                Enviar
            </button>
        </div>
    </AuthenticatedLayout>
</template>
  
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';

</script>
  