<template>

    <Head title="Tareas" />
    <AuthenticatedLayout :redirectRoute="'projectmanagement.index'">
        <template #header>
            Tareas
        </template>

        <div class="grid sm:grid-cols-5">
            <div class="col-span-full flex flex-col space-y-5">
                <div class="flex justify-start">
                    <button @click="addTask" type="button"
                        class="rounded-md bg-indigo-600 px-4 py-2 text-center text-sm text-white hover:bg-indigo-500">
                        + Agregar
                    </button>
                </div>
            </div>
        </div>

        <div v-if="tasks.data.length > 0" class="mt-6">
            <p class="font-bold">Total tareas del Proyecto</p>
            <div class="w-full bg-gray-200 rounded-full h-6 dark:bg-gray-700 mt-2 pl-0.5">
                <div class="bg-blue-600 h-6 text-md font-bold text-blue-100 p-0.5 leading-none rounded-full flex items-center justify-center"
                    :style="`width: ${project.total_percentage_tasks}%`"> {{ project.total_percentage_tasks }}%</div>
            </div>

            <p class="font-bold">Total tareas del Proyecto Completadas</p>
            <div class="w-full bg-gray-200 rounded-full h-6 dark:bg-gray-700 mt-2 pl-0.5">
                <div class="bg-green-600 h-6 text-md font-bold text-blue-100 p-0.5 leading-none rounded-full flex items-center justify-center"
                    :style="`width: ${project.total_percentage_tasks_completed }%`"> {{ project.total_percentage_tasks_completed != 0 ? project.total_percentage_tasks_completed : 0 }}%</div>
            </div>
        </div>

        <div class="tailwind mt-10">
            <ul role="list" class="divide-y divide-gray-100">

                <div class="overflow-x-auto">
                    <div v-if="tasks.data.length === 0">
                        <p class="text-gray-500">Aun no hay tareas para el proyecto seleccionado.</p>
                    </div>
                    <div v-else class="min-w-[800px]">
                        <div class="flex font-bold justify-between gap-x-6 py-2 border-b-2 border-gray-300">
                            <div class="w-[500px]">
                                Tarea
                            </div>
                            <div class=" flex items-center whitespace-nowrap">
                                % de Proyecto
                            </div>
                            <div class="flex items-center gap-x-2 w-[400px] justify-center">
                            </div>
                        </div>
                        <div v-for="task in tasks.data" :key="task.id" class="flex justify-between gap-x-6 py-5">
                            <div class="w-[500px]">
                                <p class="text-sm font-semibold leading-6 text-gray-900">{{ task.task }}</p>
                                <p class="mt-1 text-xs leading-5 text-gray-500">Estado: {{ task.status }}</p>
                            </div>
                            <div class=" flex items-center">
                                <p class="text-sm font-semibold leading-6 text-gray-900">{{ task.percentage }}%</p>
                            </div>
                            <div class="flex items-center gap-x-2 w-[400px] justify-end">
                                <!-- Botones -->
                                <button @click="openModalStart(task)" v-if="task.status === 'pendiente'"
                                    class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                    <PlayIcon class="text-white-900 h-4 w-4" style="stroke-width:4;" />
                                </button>
                                <div v-else-if="task.status === 'proceso' || task.status === 'detenido'">
                                    <!-- Botones en proceso o detenido -->
                                    <button @click="statustask(task.id, 'stop')" v-if="task.status === 'proceso'"
                                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                        <PauseIcon class="text-white-900 h-4 w-4" style="stroke-width:4;" />
                                    </button>
                                    <button @click="statustask(task.id, 'start')" v-else
                                        class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                                        <PlayPauseIcon class="text-white-900 h-4 w-4" style="stroke-width:3;" />
                                    </button>
                                    <button @click="openModalComplete(task)"
                                        class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                        <CheckIcon class="text-white-900 h-4 w-4" style="stroke-width:4;" />
                                    </button>
                                </div>
                                <p v-else class="text-red-500 font-bold py-2 px-4 rounded">
                                    Completado
                                </p>
                                <template class="flex space-x-3 justify-center">
                                    <Link :href="route('tasks.edit', { taskId: task.id })">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-teal-500">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    </Link>
                                    <Link :href="route('tasks.edit', { taskId: task.id })">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-amber-400">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                    </svg>
                                    </Link>
                                </template>

                            </div>
                        </div>
                        <div
                            class="flex flex-col items-center border-t bg-white px-5 py-5 xs:flex-row xs:justify-between">
                            <pagination :links="tasks.links" />
                        </div>

                    </div>
                </div>
            </ul>
        </div>
        <Modal :show="showcompletetaskmodal" :maxWidth="'md'">
            <!-- Contenido del modal cuando no hay empleados -->
            <div class="p-6">
                <h2 v-if="typereq === 'start'" class="text-lg font-medium text-gray-900">
                    ¿Iniciar la Tarea?
                </h2>
                <h2 v-else class="text-lg font-medium text-gray-900">
                    ¿Esta seguro de marcar la tarea como completa?
                </h2>
                <!-- Puedes agregar más contenido o personalizar según tus necesidades -->
                <p class="mt-2 text-sm text-gray-500">
                    {{ selectedTask.task }}
                </p>
                <div class="mt-6 flex justify-end">
                    <!-- Estructura condicional para el botón -->
                    <button
                        class="inline-flex items-center p-2 rounded-md font-semibold bg-red-500 text-white hover:bg-red-400 mr-2"
                        type="button" @click="closeModal"> Cancelar
                    </button>
                    <button v-if="typereq === 'start'"
                        class="inline-flex items-center p-2 rounded-md font-semibold bg-green-500 text-white hover:bg-green-400"
                        type="button" @click="statustask(selectedTask.id, 'start')"> Iniciar
                    </button>
                    <button v-else
                        class="inline-flex items-center p-2 rounded-md font-semibold bg-blue-500 text-white hover:bg-blue-400"
                        type="button" @click="statustask(selectedTask.id, 'complete')"> Aceptar
                    </button>
                </div>
            </div>
        </Modal>
    </AuthenticatedLayout>
</template>
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ref } from 'vue';
import { Head, router, Link } from '@inertiajs/vue3';
import { PlayIcon, PauseIcon, PlayPauseIcon, CheckIcon } from '@heroicons/vue/24/outline';
import Modal from '@/Components/Modal.vue';
import Pagination from '@/Components/Pagination.vue'

const { tasks, project } = defineProps({
    tasks: Object,
    project: Object,
})

const addTask = () => {
    router.get(route('tasks.new', { project_id: project.id }));
};

const statustask = (taskId, status) => {
    router.get(route('tasks.edit.status', { taskId: taskId, status: status }));
};

const showcompletetaskmodal = ref(false);
const selectedTask = ref(null);
const typereq = ref(null);
const openModalComplete = (task_id) => {
    selectedTask.value = task_id;
    typereq.value = 'complete';
    showcompletetaskmodal.value = true;
}
const openModalStart = (task_id) => {
    selectedTask.value = task_id;
    typereq.value = 'start';
    showcompletetaskmodal.value = true;
}
const closeModal = () => {
    showcompletetaskmodal.value = false;
}


</script>