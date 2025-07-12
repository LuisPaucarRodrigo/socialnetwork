<template>

    <Head title="Tarea" />
    <AuthenticatedLayout :redirectRoute="{ route: 'tasks.index', params: { id: tasks.project_id } }">
        <template #header>
            Tarea: {{ tasks.task }}
        </template>

        <div class="mt-6  border-t border-gray-100">
            <div class="relative flex-grow flex mb-8">
                <textarea id="description" rows="2" v-model="newcomment.comment" placeholder="Agregar Observaciones"
                    @keyup.enter="addComment"
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
            <div class="grid grid-cols-2 gap-4 mb-10">
                <div>
                    <label for="startDate" class="block text-md font-medium text-gray-700">Fecha de Inicio</label>
                    <p id="startDate" class="text-md text-gray-500">{{ formattedDate(tasks.start_date) }}</p>
                </div>
                <div>
                    <label for="endDate" class="block text-md font-medium text-gray-700">Fecha de Fin</label>
                    <p id="endDate" class="text-md text-gray-500">{{ formattedDate(tasks.end_date) }}</p>
                </div>
            </div>
            <div class="grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-6">
                <div class="sm:col-span-3">
                    <div class="mb-4 flex items-center">
                        <label for="description" class="block text-sm font-medium text-gray-700 mr-2">Personal
                            Encargado</label>
                        <button v-if="tasks.status !== 'completado'" @click="showToAddEmployee" type="button">
                            <AddUserIcon />
                        </button>
                    </div>

                    <div class="mt-2">
                        <div v-for="item in added_employees.project_employee"
                            class="grid grid-cols-8 items-center my-2">
                            <p class=" text-sm col-span-7 line-clamp-2">{{ item.employee_information.name }} : {{
                                item.charge }}
                            </p>
                            <button v-if="auth.user.role_id === 1" type="button" :value="item.pivot.project_employee_id"
                                @click="delete_already_employee(item.pivot.project_employee_id)">
                                <DeleteIcon />
                            </button>
                            <div class="border-b col-span-8 border-gray-900/10">
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="max-h-40 overflow-y-auto pt-5">
                <h1>Observaciones</h1>
                <dd v-for="(comment, index) in comments.slice().reverse()" :key="comment.id"
                    class="mt-1 text-md leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                    - {{ comment.comment }}
                </dd>
            </div>
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
                                <option v-for="item in employeesToAssign" :key="item.id" :value="item.id">
                                    {{ item.employee_information.name }}
                                </option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex gap-3 justify-end">
                    <SecondaryButton type="button" @click="closeModal"> Cerrar </SecondaryButton>
                    <PrimaryButton type="submit" @click="submitForm"> Agregar </PrimaryButton>


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

    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import Modal from '@/Components/Modal.vue';
import InputLabel from '@/Components/InputLabel.vue';
import { formattedDate } from '@/utils/utils'
import SecondaryButton from '@/Components/SecondaryButton.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { AddUserIcon, DeleteIcon } from '@/Components/Icons';

const props = defineProps({
    projects: Object,
    tasks: Object,
    comments: Object,
    employeesToAssign: Object,
    added_employees: Object,
    auth: Object,
})

const { tasks } = props;
const newcomment = useForm({
    task_id: tasks.id,
    comment: '',
});

const addComment = () => {
    newcomment.post(route('tasks.add.comment'), {
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

const showToAddEmployee = () => {
    if (props.employeesToAssign.length > 0) {
        showModal.value = true;
    } else {
        showModalNoEmployees.value = true;
    }
}
const closeModal = () => {
    showModal.value = false;
    showModalNoEmployees.value = false;
};


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
        },
    });
}

</script>