<template>
    <Head title="Tareas" />
    <AuthenticatedLayout>
        <template #header>
            Nueva Tarea
        </template>
        <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
            <div class="sm:col-span-3">
                <label for="project" class="block text-sm font-medium text-gray-700">Proyecto</label>
                <select id="project" v-model="project"
                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring focus:border-blue-300">
                    <option value="proyecto1">Proyecto 1</option>
                    <option value="proyecto2">Proyecto 2</option>
                    <option value="proyecto3">Proyecto 3</option>
                </select>
            </div>

            <div class="sm:col-span-3">
                <label for="task" class="block text-sm font-medium text-gray-700">Tarea</label>
                <input type="text" id="task" v-model="task"
                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring focus:border-blue-300">
            </div>
            <!-- Fecha de Inicio (date input) -->
            <div class="sm:col-span-2">
                <label for="startDate" class="block text-sm font-medium text-gray-700">Fecha de Inicio</label>
                <input type="date" id="startDate" v-model="startDate"
                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring focus:border-blue-300">
            </div>

            <!-- Fecha de Fin (date input) -->
            <div class="sm:col-span-2">
                <label for="endDate" class="block text-sm font-medium text-gray-700">Fecha de Fin</label>
                <input type="date" id="endDate" v-model="endDate"
                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring focus:border-blue-300">
            </div>

            <!-- Estado (select) -->
            <div class="sm:col-span-2">
                <label for="status" class="block text-sm font-medium text-gray-700">Estado</label>
                <select id="status" v-model="status"
                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring focus:border-blue-300">
                    <option value="pendiente">Pendiente</option>
                    <option value="en-proceso">En Proceso</option>
                    <option value="completado">Completado</option>
                </select>
            </div>
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
import { Head, router } from '@inertiajs/vue3';

</script>
  