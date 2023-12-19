<template>
    <Head title="Proyectos" />
    <AuthenticatedLayout>
        <template #header>
            {{ title }}
        </template>
        <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
            <div class="sm:col-span-3">
                <label for="project" class="block text-sm font-medium text-gray-700">Nombre</label>
                <input type="text" id="task"
                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring focus:border-blue-300">
                <!-- <InputError :message="form.errors.task" /> -->
            </div>

            <div class="sm:col-span-3">
                <label for="task" class="block text-sm font-medium text-gray-700">Categoria</label>
                <div class="flex">
                    <select
                        class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring focus:border-blue-300">
                        <option value="pendiente">Hardware</option>
                        <option value="proceso">Software</option>
                        <option value="detenido">Vehiculo</option>
                        <option value="detenido">Equipo de Oficina</option>
                        <option value="completado">Equipo de Campo</option>
                        <option value="detenido">Otros</option>
                    </select>
                </div>
                <!-- <InputError :message="form.errors.percentage" /> -->
            </div>
            <div class="sm:col-span-2">
                <label for="startDate" class="block text-sm font-medium text-gray-700">Codigo</label>
                <input type="text" id="start_date"
                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring focus:border-blue-300">
                <!-- <InputError :message="form.errors.start_date" /> -->
            </div>
            <div class="sm:col-span-1">
                <label for="tasks" class="block text-sm font-medium text-gray-700">Cantidad</label>
                <input type="number" id="task"
                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring focus:border-blue-300">
                <!-- <InputError :message="form.errors.task" /> -->
            </div>
            <!--Local -->
            <div class="sm:col-span-3">
                <label for="task" class="block text-sm font-medium text-gray-700">Ubicacion</label>
                <div class="flex">
                    <select
                        class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring focus:border-blue-300">
                        <option value="1">Conproco</option>
                        <option value="2">Social Network</option>
                    </select>
                </div>
                <!-- <InputError :message="form.errors.percentage" /> -->
            </div>

            <!-- Fecha de Inicio (date input) -->
            <div class="sm:col-span-2">
                <label for="startDate" class="block text-sm font-medium text-gray-700">Fecha de Adquisicion</label>
                <input type="date" id="start_date"
                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring focus:border-blue-300">
                <!-- <InputError :message="form.errors.start_date" /> -->
            </div>

            <!-- Descricion (text-Area) -->
            <div class="sm:col-span-4">
                <label for="startDate" class="block text-sm font-medium text-gray-700">Descripcion</label>
                <textarea type="text" id="start_date"
                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring focus:border-blue-300" />
                <!-- <InputError :message="form.errors.start_date" /> -->
            </div>
            <div class="sm:col-span-1">
                <label for="tasks" class="block text-sm font-medium text-gray-700">Estado</label>
                <input type="number" id="task"
                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring focus:border-blue-300">
                <!-- <InputError :message="form.errors.task" /> -->
            </div>

        </div>

        <!-- Botón de enviar -->
        <div class="mt-6 flex items-center justify-end gap-x-6">
            <button @click="submitForm"
                class="bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 focus:outline-none focus:ring focus:border-blue-300">
                Crear
            </button>
        </div>
    </AuthenticatedLayout>
</template>
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router, Link } from '@inertiajs/vue3';
import Pagination from '@/Components/Pagination.vue'
import { EyeIcon, PencilIcon, TrashIcon } from '@heroicons/vue/24/outline';

const props = defineProps({
    title: String
})

</script>