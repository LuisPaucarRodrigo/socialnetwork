<template>

    <Head title="Tareas" />
    <AuthenticatedLayout :redirectRoute="backUrl">
        <template #header>
            Tareas
        </template>

        <div class="grid sm:grid-cols-5">
            <div class="col-span-full flex flex-col space-y-5">
                <div class="flex justify-start space-x-3">
                    <button v-if="project.status === null" @click="addTask" type="button"
                        class="rounded-md bg-indigo-600 px-4 py-2 text-center text-sm text-white hover:bg-indigo-500">
                        + Agregar
                    </button>

                    <button @click="showDuplicated" type="button"
                        class="rounded-md bg-indigo-600 px-4 py-2 text-center text-sm text-white hover:bg-indigo-500">
                        Duplicar
                    </button>
                </div>
            </div>
        </div>

        <p class="mt-3 font-medium text-gray-900">Fecha de Inicio del Proyecto:
            <span class="text-gray-600">{{ project.start_date }}</span>
        </p>
        <p class="font-medium text-gray-900">Fecha de Fin del Proyecto:
            <span class="text-gray-600">{{ project.end_date }}</span>
        </p>

        <div v-if="tasks.data.length > 0" class="mt-3">
            <p class="font-bold">Total tareas del Proyecto</p>
            <div class="w-full bg-gray-200 rounded-full h-6 dark:bg-gray-700 mt-2 pl-0.5">
                <div class="bg-blue-600 h-6 text-md font-bold text-blue-100 p-0.5 leading-none rounded-full flex items-center justify-center"
                    :style="`width: ${project.total_percentage_tasks}%`"> {{ project.total_percentage_tasks }}%</div>
            </div>

            <p class="font-bold">Total tareas del Proyecto Completadas</p>
            <div class="w-full bg-gray-200 rounded-full h-6 dark:bg-gray-700 mt-2 pl-0.5">
                <div class="bg-green-600 h-6 text-md font-bold text-blue-100 p-0.5 leading-none rounded-full flex items-center justify-center"
                    :style="`width: ${project.total_percentage_tasks_completed}%`"> {{
                        project.total_percentage_tasks_completed
                            != 0 ? project.total_percentage_tasks_completed : 0 }}%</div>
            </div>
        </div>

        <div class="tailwind mt-10">
            <div class="overflow-x-auto">
                <table class="w-full whitespace-no-wrap">
                    <thead>
                        <tr
                            class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Tarea
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Fecha de Inicio
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Fecha de Fin
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                % de Proyecto
                            </th>
                            <th v-if="project.status === null"
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-end text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Acciones
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <template v-if="tasks.data.length === 0">
                            <tr>
                                <td colspan="5" class="px-5 py-3 text-gray-500">
                                    Aun no hay tareas para el proyecto seleccionado.
                                </td>
                            </tr>
                        </template>
                        <template v-else>
                            <tr v-for="task in tasks.data" :key="task.id" :class="['text-gray-700']">
                                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                    <p class="text-gray-900 whitespace-no-wrap">{{ task.task }}</p>
                                    <p class="mt-1 text-xs leading-5 text-gray-500">Estado: {{ task.status }}</p>
                                </td>
                                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">
                                    <p class="text-gray-900 whitespace-no-wrap">{{ formattedDate(task.start_date) }}</p>
                                </td>
                                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">
                                    <p class="text-gray-900 whitespace-no-wrap">{{ formattedDate(task.end_date) }}</p>
                                </td>
                                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">
                                    <p class="text-gray-900 whitespace-no-wrap">{{ task.percentage }}%</p>
                                </td>
                                <td v-if="project.status === null"
                                    class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                    <div class="flex items-center gap-x-2 justify-end">
                                        <!-- Botones -->
                                        <template>
                                            <div v-if="task.status === 'pendiente'">
                                                <button @click="openModalStart(task)"
                                                    v-if="task.start_date && task.end_date" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2
                                                    px-4 rounded">
                                                    <PlayIcon />
                                                </button>
                                            </div>

                                            <div v-else-if="task.status === 'proceso' || task.status === 'detenido'">
                                                <!-- Botones en proceso o detenido -->
                                                <!-- <button @click="statustask(task.id, 'stop')"
                                                    v-if="task.status === 'proceso'"
                                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                                    <PauseIcon class="text-white-900 h-4 w-4" style="stroke-width:4;" />
                                                </button>
                                                <button @click="statustask(task.id, 'start')" v-else
                                                    class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                                                    <PlayPauseIcon class="text-white-900 h-4 w-4"
                                                        style="stroke-width:3;" />
                                                </button> -->
                                                <button @click="openModalComplete(task)">
                                                    <AcceptIcon />
                                                </button>
                                            </div>
                                            <p v-else class="text-green-500 font-bold py-2 px-4 rounded">
                                                Completado
                                            </p>
                                        </template>

                                        <template class="flex space-x-3 justify-center">
                                            <!-- <Link :href="route('tasks.show', { taskId: task.id })">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-teal-500">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                            </Link> -->
                                            <template>
                                                <button v-if="task.status === 'pendiente'"
                                                    @click="showModalDate(task.id)">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                        class="w-6 h-6 text-amber-400">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                                    </svg>
                                                </button>
                                                <span v-else class="text-gray-400">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                        class="w-6 h-6 text-gray-400">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                                    </svg>
                                                </span>
                                                <button type="button" @click="openModalDelete(task.id)"
                                                    class="text-blue-900 whitespace-no-wrap">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                        class="w-6 h-6 text-red-500">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                                    </svg>
                                                </button>
                                            </template>
                                        </template>
                                    </div>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
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
                <p class="mt-2 text-sm text-gray-500">
                    {{ selectedTask.task }}
                </p>
                <div class="mt-6 flex space-x-3 justify-end">
                    <SecondaryButton type="button" @click="closeModal"> Cancelar
                    </SecondaryButton>
                    <PrimaryButton v-if="typereq === 'start'" type="button"
                        @click="statustask(selectedTask.id, 'start')">
                        Iniciar
                    </PrimaryButton>
                    <PrimaryButton v-else type="button" @click="statustask(selectedTask.id, 'complete')"> Aceptar
                    </PrimaryButton>
                </div>
            </div>
        </Modal>
        <Modal :show="showModalDuplicated">
            <form @submit.prevent="submitDuplicated">
                <div class="p-6">
                    <h2 class="text-lg font-medium text-gray-900">
                        Duplicar Tareas
                    </h2>
                    <InputLabel for="project_id_duplicated">
                        Proyectos
                    </InputLabel>
                    <div class="mt-2">
                        <select v-model="form.project_id_duplicated" id="project_id_duplicated"
                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                            <option value="" disabled>Seleccionar Proyecto</option>
                            <option v-for="item in projects" :key="item.id" :value="item.id">{{ item.description }}
                            </option>
                        </select>
                        <InputError :message="form.errors.project_id_duplicated" />
                    </div>
                    <div class="mt-6 flex justify-end">
                        <SecondaryButton @click="closeDuplicated"> Cancelar </SecondaryButton>
                        <PrimaryButton type="submit" class="ml-3" :class="{ 'opacity-25': form.processing }">
                            Duplicar
                        </PrimaryButton>
                    </div>
                </div>
            </form>
        </Modal>

        <Modal :show="showModalEditDate">
            <form @submit.prevent="submitEditDate">
                <div class="p-6">
                    <h2 class="text-lg font-medium text-gray-900">
                        Fechas de Tareas
                    </h2>
                    <p class="mt-2 font-medium text-gray-900">Fecha de Inicio del Proyecto:
                        <span class="text-gray-600">{{ project.start_date }}</span>
                    </p>
                    <p class="font-medium text-gray-900">Fecha de Fin del Proyecto:
                        <span class="text-gray-600">{{ project.end_date }}</span>
                    </p>
                    <InputLabel for="start_date" class="font-medium leading-6 text-gray-900 mt-3">
                        Fecha de Inicio
                    </InputLabel>
                    <TextInput type="date" v-model="formDate.start_date" id="start_date" required />
                    <InputError :message="formDate.errors.start_date" />
                    <InputLabel for="end_date">
                        Fecha de Fin
                    </InputLabel>
                    <TextInput type="date" v-model="formDate.end_date" id="end_date" required />
                    <InputError :message="formDate.errors.end_date" />
                    <div class="mt-6 flex space-x-3 justify-end">
                        <SecondaryButton @click="closeModalDate"> Cancelar </SecondaryButton>
                        <PrimaryButton type="submit" class="ml-3" :class="{ 'opacity-25': formDate.processing }">
                            Guardar
                        </PrimaryButton>
                    </div>
                </div>
            </form>
        </Modal>
        <Modal :show="showModalDelete" :maxWidth="'md'">
            <!-- Contenido del modal cuando no hay empleados -->
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">
                    ¿Seguro de eliminar la Tarea?
                </h2>
                <!-- Puedes agregar más contenido o personalizar según tus necesidades -->
                <p class="mt-2 text-sm text-gray-500">
                    Esta seguro de eliminar la tarea? No se podran deshacer los cambios.
                </p>
                <div class="mt-6 flex space-x-3 justify-end">
                    <SecondaryButton type="button" @click="closeModalDelete()"> Cancelar
                    </SecondaryButton>
                    <PrimaryButton type="button" @click="delete_task()"> Aceptar </PrimaryButton>
                </div>
            </div>
        </Modal>
        <SuccessOperationModal :confirming="showConfirmDuplicated" title="Tareas"
            message="Tareas duplicadas con exito" />
        <ErrorOperationModal :showError="showErrorTask" title="Error"
            message="La suma de porcentajes de las tareas de ambos proyectos supera el 100%" />
    </AuthenticatedLayout>
</template>
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ref } from 'vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import SuccessOperationModal from '@/Components/SuccessOperationModal.vue';
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import ErrorOperationModal from '@/Components/ErrorOperationModal.vue';
import { formattedDate } from '@/utils/utils';
import { PlayIcon, AcceptIcon } from '@/Components/Icons/Index';

const { tasks, project, projects, userPermissions } = defineProps({
    tasks: Object,
    project: Object,
    projects: Object,
    userPermissions: Array
})

const hasPermission = (permission) => {
    return userPermissions.includes(permission);
}

let backUrl = project.cost_line_id === 1 ? project.status === null
    ? 'projectmanagement.index'
    : project.status == true
        ? 'projectmanagement.historial'
        : 'projectmanagement.index' : project.status === null
    ? 'projectmanagement.pext.index'
    : project.status == true
        ? 'projectmanagement.pext.historial'
        : 'projectmanagement.pext.index'

const taskIdDelete = ref(null);

const addTask = () => {
    router.get(route('tasks.create', { project_id: project.id }));
};

const statustask = (taskId, status) => {
    router.get(route('tasks.edit.status', { taskId: taskId, status: status }));
};

const form = useForm({
    project_id: project.id,
    project_id_duplicated: '',
})

const formDate = useForm({
    id: '',
    start_date: '',
    end_date: '',
    project_id: project.id
})

const showcompletetaskmodal = ref(false);
const selectedTask = ref(null);
const typereq = ref(null);
const showModalDuplicated = ref(false);
const showConfirmDuplicated = ref(false);
const showModalEditDate = ref(false);
const showModalDelete = ref(false);
const showErrorTask = ref(false);

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

function showDuplicated() {
    showModalDuplicated.value = true
}

function closeDuplicated() {
    showModalDuplicated.value = false
}

function showModalDate(id) {
    formDate.id = id
    showModalEditDate.value = true
}

function closeModalDate() {
    showModalEditDate.value = false
    formDate.reset();
}

function submitDuplicated() {
    const task = projects.find(item => item.id === form.project_id_duplicated)
    if (task.total_sum_task + project.total_sum_task < 100) {
        form.post(route('tasks.duplicated'), {
            onSuccess: () => {
                showConfirmDuplicated.value = true
                setTimeout(() => {
                    showConfirmDuplicated.value = false;
                    router.get(route('tasks.index', { id: project.id }))
                }, 3000);
            }
        })
    } else {
        showErrorTask.value = true
        setTimeout(() => {
            showErrorTask.value = false
        }, 2000);
    }
}

function submitEditDate() {
    formDate.post(route('tasks.edit.date'), {
        onSuccess: () => {
            router.get(route('tasks.index', { id: project.id }))
        },
    })
}

const delete_task = () => {
    router.delete(route('tasks.delete', { taskId: taskIdDelete.value }), {
        onSuccess: () => {
            router.get(route('tasks.index', { id: project.id }))
        }
    })

}

const openModalDelete = (taskId) => {
    showModalDelete.value = true;
    taskIdDelete.value = taskId
}

const closeModalDelete = () => {
    showModalDelete.value = false;
}

</script>