<template>
    <Head title="Tareas" />
    <AuthenticatedLayout>
        <template #header>
            Nueva Tarea
        </template>
        <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
            <div class="sm:col-span-2">
                <label for="project" class="block text-sm font-medium text-gray-700">Proyecto</label>
                <select v-model="form.project_id"
                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring focus:border-blue-300">
                    <option v-for="project in projects" :key="project.id" :value="project.id">
                        {{ project.name }}
                    </option>
                </select>
            </div>

            <div class="sm:col-span-3">
                <label for="tasks" class="block text-sm font-medium text-gray-700">Tarea</label>
                <input type="text" id="task" v-model="form.task"
                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring focus:border-blue-300">
                <InputError :message="form.errors.task" />
            </div>
            <div class="sm:col-span-1">
                <label for="task" class="block text-sm font-medium text-gray-700">Porcentaje</label>
                <div class="flex">
                    <input type="number" id="percentage" v-model="form.percentage"
                        class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring focus:border-blue-300">
                    <span class="ml-2 self-center text-gray-500">%</span>
                </div>
                <InputError :message="form.errors.percentage" />
            </div>

            <!-- Fecha de Inicio (date input) -->
            <div class="sm:col-span-2">
                <label for="startDate" class="block text-sm font-medium text-gray-700">Fecha de Inicio</label>
                <input type="date" id="start_date" v-model="form.start_date"
                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring focus:border-blue-300">
                <InputError :message="form.errors.start_date" />
            </div>

            <!-- Fecha de Fin (date input) -->
            <div class="sm:col-span-2">
                <label for="endDate" class="block text-sm font-medium text-gray-700">Fecha de Fin</label>
                <input type="date" id="end_date" v-model="form.end_date"
                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring focus:border-blue-300">
                <InputError :message="form.errors.end_date" />
            </div>

            <!-- Estado (select) -->
            <div class="sm:col-span-2">
                <label for="status" class="block text-sm font-medium text-gray-700">Estado</label>
                <select v-model="form.status"
                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring focus:border-blue-300">
                    <option value="pendiente">Pendiente</option>
                    <option value="proceso">En Proceso</option>
                    <option value="detenido">Detenido</option>
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
import InputError from '@/Components/InputError.vue'
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    projects: Object
})

const form = useForm({
    project_id: '1',
    task: '',
    percentage: '',
    start_date: '',
    end_date: '',
    status: 'pendiente',
});


const submitForm = () => {
    form.post(route('tasks.create'), {
        onError: (errors) => {
            // Imprimir los errores de validación en la consola
            console.log('Errores de validación:', errors);
        }
    })
}
</script>
  