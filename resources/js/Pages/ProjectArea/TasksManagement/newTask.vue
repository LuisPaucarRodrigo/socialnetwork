<template>
    <Head title="Tareas" />
    <AuthenticatedLayout :redirectRoute="{ route: 'tasks.index', params: { id: project.id } }">
        <template #header>
            Nueva Tarea
        </template>
        <p class="mt-2 font-medium text-gray-900">Fecha de Inicio del Proyecto:
                        <span class="text-gray-600">{{ project.start_date }}</span>
                    </p>
                    <p class="font-medium text-gray-900">Fecha de Fin del Proyecto:
                        <span class="text-gray-600">{{ project.end_date }}</span>
                    </p>
        <form @submit.prevent="submitForm" >
            <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6 mt-3">
            <div class="sm:col-span-4">
                <label for="tasks" class="block text-sm font-medium text-gray-700">Tarea</label>
                <input type="text" id="task" v-model="form.task"
                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring focus:border-blue-300"
                    list="tasksList"/>
                <datalist id="tasksList">
                    <option 
                        v-for="task in tasks" 
                        :value="task.task" 
                        :key="task.id">
                    </option>
                </datalist>
                <InputError :message="form.errors.task" />
            </div>
            <div class="sm:col-span-2">
                <label for="task" class="block text-sm font-medium text-gray-700">Porcentaje</label>
                <div class="flex">
                    <input type="number" id="percentage" v-model="form.percentage" :max="100-project.total_percentage_tasks"
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
            <div class="mt-6 flex items-center justify-end gap-x-6">
            <button type="submit"
                class="bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 focus:outline-none focus:ring focus:border-blue-300">
                Enviar
            </button>
        </div>
        </form>

        <!-- Botón de enviar -->
        

    </AuthenticatedLayout>
</template>
  
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue'
import { Head, useForm } from '@inertiajs/vue3';


const { tasks, project } = defineProps({
    tasks: Object,
    project: Object,
})

const form = useForm({
    project_id: project.id,
    task: '',
    percentage: '',
    start_date: '',
    end_date: '',
    status: 'pendiente',
});


const submitForm = () => {
    form.post(route('tasks.store'), {
        onError: (errors) => {
            console.log('Errores de validación:', errors);
        }
    })
}
</script>
  