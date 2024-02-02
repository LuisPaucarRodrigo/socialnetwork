<template>
    <Head title="Tarea1" />
    <AuthenticatedLayout>
        <template #header>
            {{ tasks.task }}
        </template>

        <div>
            <div class="max-h-40 overflow-y-auto">
                <div v-for="(comment, index) in comments.slice().reverse()" :key="comment.id" class="mb-2">
                    <p class="text-gray-700" :style="{ borderTop: index === 0 ? 'none' : '1px solid #ccc' }">
                        <b>Comentario:</b> {{ comment.comment }}
                    </p>
                </div>
            </div>
            <div class="mb-4 flex flex-col sm:flex-row items-center mt-5">
                <div class="relative flex-grow flex">
                    <textarea id="description" rows="2" v-model="newcomment.comment" @keyup.enter="addComment"
                        class="w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring focus:border-blue-300 resize-none"></textarea>
                    <button @click="addComment" type="button"
                        class="ml-2 px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring focus:border-indigo-300">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5" />
                        </svg>
                    </button>
                </div>
            </div>

        </div>

        <div class="grid grid-cols-2 gap-4 m-5 mb-10">
            <!-- Columna 1 - Fecha de Inicio -->
            <div>
                <label for="startDate" class="block text-sm font-medium text-gray-700">Fecha de Inicio</label>
                <p id="startDate" class="text-sm text-gray-500">{{ tasks.start_date }}</p>
            </div>

            <!-- Columna 2 - Fecha de Fin -->
            <div>
                <label for="endDate" class="block text-sm font-medium text-gray-700">Fecha de Fin</label>
                <p id="endDate" class="text-sm text-gray-500">{{ tasks.end_date }}</p>
            </div>
        </div>
        <div class="grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-6">
            <div class="sm:col-span-3">
                <div class="mb-4 flex items-center">
                    <label for="description" class="block text-sm font-medium text-gray-700 mr-2">Personal Encargado</label>
                    <button @click="showToAddEmployee" type="button">
                        <UserPlusIcon class="text-indigo-800 h-6 w-6 hover:text-purple-400" />
                    </button>
                </div>


                <div class="mt-2">
                    <div v-for="item in added_employees.project_employee" class="grid grid-cols-8 items-center my-2">
                        <p class=" text-sm col-span-7 line-clamp-2">{{ item.employee_information.name }} : {{ item.charge }}
                        </p>
                        <button type="button" :value="item.pivot.project_employee_id"
                            @click="delete_already_employee(item.pivot.project_employee_id)"
                            class="col-span-1 flex justify-end">
                            <TrashIcon class=" text-red-500 h-4 w-4 " />
                        </button>
                        <div class="border-b col-span-8 border-gray-900/10">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-6 flex items-center justify-end gap-x-6">
            <button type="submit" v-if="tasks.status === 'pendiente'" @click="openModalDelete()"
                class="rounded-md bg-red-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600">Eliminar</button>
        </div>
        <Modal :show="showModal" :maxWidth="'md'">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">
                    Agregar Personal a la tarea
                </h2>
                <div class="grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-6 mt-2">
                    <div class="sm:col-span-6">
                        <InputLabel for="name" class="font-medium leading-6 text-gray-900">Empleados dentro del Proyecto
                        </InputLabel>
                        <div class="mt-2">
                            <select required id="type" v-model="form.project_employee_id"
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                <option selected disabled value="">Seleccione uno</option>
                                <option v-for="item in employees.employees" :key="item.id" :value="item.id"> {{
                                    item.name }}
                                </option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex gap-3 justify-end">
                    <button
                        class="inline-flex items-center p-2 rounded-md font-semibold bg-red-500 text-white hover:bg-red-400"
                        type="button" @click="closeModal"> Cerrar </button>
                    <button
                        class="inline-flex items-center p-2 rounded-md font-semibold bg-indigo-500 text-white hover:bg-indigo-400"
                        type="submit" @click="submitForm"> Agregar </button>


                </div>
            </div>
        </Modal>
        <Modal :show="showModalNoEmployees" :maxWidth="'md'">
            <!-- Contenido del modal cuando no hay empleados -->
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">
                    No hay empleados disponibles
                </h2>
                <!-- Puedes agregar más contenido o personalizar según tus necesidades -->
                <p class="mt-2 text-sm text-gray-500">
                    Por favor, asigne empleados al proyecto antes de intentar agregarlos a la tarea.
                </p>
                <div class="mt-6 flex justify-end">
                    <button
                        class="inline-flex items-center p-2 rounded-md font-semibold bg-indigo-500 text-white hover:bg-indigo-400"
                        type="button" @click="closeModal"> Aceptar </button>
                </div>
            </div>
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
                <div class="mt-6 flex justify-end">
                    <button
                        class="inline-flex items-center p-2 rounded-md font-semibold bg-red-500 text-white hover:bg-red-400 mr-2"
                        type="button" @click="closeModalDelete()"> Cancelar
                    </button>
                    <button
                        class="inline-flex items-center p-2 rounded-md font-semibold bg-indigo-500 text-white hover:bg-indigo-400"
                        type="button" @click="delete_task(tasks.id)"> Aceptar </button>
                </div>
            </div>
        </Modal>
    </AuthenticatedLayout>
</template>
  
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import Modal from '@/Components/Modal.vue';
import InputLabel from '@/Components/InputLabel.vue';
import { UserPlusIcon, TrashIcon } from '@heroicons/vue/24/outline';

const props = defineProps({
    projects: Object,
    tasks: Object,
    comments: Object,
    employees: Object,
    added_employees: Object,
})

const { tasks } = props;
const newcomment = useForm({
    task_id: tasks.id,
    comment: '',
});

const addComment = () => {
    newcomment.post(route('tasks.edit.comment'), {
        onError: (errors) => {
            console.log('Errores de validación:', errors);
        }
    })
    newcomment.comment = '';
    const textarea = document.getElementById('description');
    textarea.scrollTop = textarea.scrollHeight;
}
const showModal = ref(false);
const showModalNoEmployees = ref(false);
const { employees } = props;

const showToAddEmployee = () => {
    if (employees.employees.length > 0) {
        showModal.value = true;
    } else {
        showModalNoEmployees.value = true;
    }
}
const closeModal = () => {
    showModal.value = false;
    showModalNoEmployees.value = false;
};

const project_employee_id = ref(null);

const form = useForm({
    task_id: tasks.id,
    project_employee_id: '',
});

const submitForm = () => {
    if (!form.project_employee_id) {
        console.error('Por favor, selecciona un empleado antes de enviar el formulario.');
        return;
    }

    form.post(route('tasks.add.employee'), {
        onSuccess: () => {
            closeModal();
        },
    });
    form.selectedEmployee = '';
};

const deluser = useForm({
    task_id: tasks.id,
    project_employee_id: '',
});

const delete_already_employee = (project_employee_id) => {
    deluser.project_employee_id = project_employee_id;

    deluser.post(route('tasks.delete.employee'), {
        onSuccess: () => {
            console.log('Se elimino correctamente')
        },
    });
}
const showModalDelete = ref(false);
const delete_task = (taskId) => {
    router.delete(route('tasks.delete', { taskId: taskId }))
}
const openModalDelete = () => {
    showModalDelete.value = true;
}
const closeModalDelete = () =>{
    showModalDelete.value = false;
}

</script>
  