<template>
    <Head title="Proyectos" />
    <AuthenticatedLayout>
        <template v-if="project" #header>
            Edición de proyecto
        </template>
        <template v-else #header>
            Creación de proyecto
        </template>
        <div class="min-w-full p-3 rounded-lg shadow">
            <form @submit.prevent="submit">
                <div class="pt-1">
                    <!-- Linea de borde abajo -->
                    <div class="border-b border-gray-900/10 mb-2">
                    </div>

                    <div class="border-b border-gray-900 pb-12">
                        <h2 v-if="project" class="text-base font-semibold leading-7 text-gray-900">{{ project.name }}-{{
                            project.code }}</h2>
                        <h2 v-else class="text-base font-semibold leading-7 text-gray-900">Registrar nuevo proyecto</h2>
                        <br>
                        <div class="grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-6">
                            <div class="sm:col-span-3">
                                <InputLabel for="start_date" class="font-medium leading-6 text-gray-900">Fecha de Inicio
                                </InputLabel>
                                <div class="mt-2">
                                    <TextInput type="Date" v-model="form.start_date" id="start_date" @input="setCode"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                    <InputError :message="form.errors.start_date" />
                                </div>
                            </div>

                            <div class="sm:col-span-3">
                                <InputLabel for="end_date" class="font-medium leading-6 text-gray-900">Fecha de fin
                                </InputLabel>
                                <div class="mt-2">
                                    <TextInput type="Date" v-model="form.end_date" id="end_date"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                    <InputError :message="form.errors.end_date" />
                                </div>
                            </div>
                            <div class="sm:col-span-3">
                                <InputLabel for="name" class="font-medium leading-6 text-gray-900">Nombre del proyecto
                                </InputLabel>
                                <div class="mt-2">
                                    <TextInput required type="text" v-model="form.name" id="name"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                    <InputError :message="form.errors.name" />
                                </div>
                            </div>

                            <div class="sm:col-span-3">
                                <InputLabel class="font-medium leading-6 text-gray-900">Código del proyecto:</InputLabel>
                                <div class="mt-2">
                                    <InputLabel class="font-medium leading-6 text-indigo-900">
                                        {{ form.code }}
                                    </InputLabel>
                                    <input hidden v-model="form.code">
                                </div>
                            </div>
                            <div class="sm:col-span-3">
                                <InputLabel for="priority" class="font-medium leading-6 text-gray-900">Prioridad
                                </InputLabel>
                                <div class="mt-2">
                                    <select required id="priority" v-model="form.priority"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                        <option disabled value="">Seleccione uno</option>
                                        <option value="Alta">Alta</option>
                                        <option value="Media">Media</option>
                                        <option value="Baja">Baja</option>
                                        <option value="otros">otros</option>
                                    </select>
                                    <InputError :message="form.errors.priority" />
                                </div>
                            </div>

                            <div class="sm:col-span-3">
                                <InputLabel for="description" class="font-medium leading-6 text-gray-900">Descripción
                                </InputLabel>
                                <div class="mt-2">
                                    <textarea required v-model="form.description" id="description"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"></textarea>
                                    <InputError :message="form.errors.description" />
                                </div>
                            </div>


                            <div class="sm:col-span-3">
                                <div class="flex gap-2">
                                    <InputLabel for="trainings" class="font-medium leading-6 text-gray-900">Miembros del
                                        equipo al proyecto
                                    </InputLabel>
                                    <button @click="showToAddEmployee" type="button">
                                        <UserPlusIcon class="text-indigo-800 h-6 w-6 hover:text-purple-400" />
                                    </button>
                                </div>

                                <div class="mt-2" v-if="project">
                                    <div v-for="(member, index) in form.employees" :key="index"
                                        class="grid grid-cols-8 items-center my-2">
                                        <p class=" text-sm col-span-7 line-clamp-2">{{ member.name }} {{
                                            member.lastname }}: {{ member.pivot.charge }} </p>
                                        <button type="button" @click="delete_already_employee(member.pivot.id, index)"
                                            class="col-span-1 flex justify-end">
                                            <TrashIcon class=" text-red-500 h-4 w-4 " />
                                        </button>
                                        <div class="border-b col-span-8 border-gray-900/10">
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-2" v-else>
                                    <div v-for="(member, index) in form.employees" :key="index"
                                        class="grid grid-cols-8 items-center my-2">
                                        <p class=" text-sm col-span-7 line-clamp-2">{{ member.employee.name }} {{
                                            member.employee.lastname }}: {{ member.charge }} </p>
                                        <button type="button" @click="delete_employee(index)"
                                            class="col-span-1 flex justify-end">
                                            <TrashIcon class=" text-red-500 h-4 w-4 " />
                                        </button>
                                        <div class="border-b col-span-8 border-gray-900/10">
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                </div>
                <div class="mt-3 flex items-center justify-end gap-x-6">
                    <button type="submit" :class="{ 'opacity-25': form.processing }"
                        class="rounded-md bg-indigo-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Guardar</button>
                </div>
            </form>
            <Modal :show="showModal">
                <form class="p-6" @submit.prevent="add_employee">
                    <h2 class="text-lg font-medium text-gray-900">
                        Agregar un miembro del equipo
                    </h2>
                    <div class="grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-6 mt-2">
                        <div class="sm:col-span-3">
                            <InputLabel for="name" class="font-medium leading-6 text-gray-900">Empleado
                            </InputLabel>
                            <div class="mt-2">
                                <select required id="type" v-model="employeeToAdd.employee"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option disabled value="">Seleccione uno</option>
                                    <option v-for="item in employees" :key="item.id" :value="item">{{ item.name }} {{
                                        item.lastname }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="sm:col-span-3">
                            <InputLabel class="font-medium leading-6 text-gray-900">Rol de la persona</InputLabel>
                            <div class="mt-2">
                                <select required id="type" v-model="employeeToAdd.charge"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option disabled value="">Seleccione uno</option>
                                    <option value="lider">lider</option>
                                    <option value="sublider">sublider</option>
                                    <option value="supervisor">supervisor</option>
                                    <option value="trabajador">trabajador</option>
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
                            type="submit"> Agregar </button>


                    </div>
                </form>
            </Modal>
        </div>
    </AuthenticatedLayout>
</template>
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue'
import Modal from '@/Components/Modal.vue';
import { UserPlusIcon, TrashIcon } from '@heroicons/vue/24/outline';

const { employees, start_date, numberOfProjects, project } = defineProps({
    employees: Object,
    start_date: String,
    numberOfProjects: Number,
    project: Object
})

const initialState = {
    name: '',
    code: start_date ? `${formatearFecha(start_date)}${formatearNumero(numberOfProjects)}` : '',
    start_date: start_date,
    end_date: '',
    priority: '',
    description: '',
    employees: []
}

const form = useForm(
    project ?
        numberOfProjects != null ? { ...project, code: `${formatearFecha(start_date)}${formatearNumero(numberOfProjects)}`, start_date } : start_date ? { ...project, start_date } : project
        :
        { ...initialState }
)
console.log(project)
const submit = () => {
    form.post(route('projectmanagement.store'))
}

//code of the project
const setCode = (e) => {
    let sd = e.target.value
    form.start_date = sd
    project ? router.visit(`/projectmanagement/update/${project.id}?start_date=${sd}`) : router.visit(`/projectmanagement/create?start_date=${sd}`)
}
function formatearFecha(fecha) {
    const date = new Date(fecha);
    const year = date.getFullYear();
    const month = (date.getMonth() + 1).toString().padStart(2, '0');
    return `${year}${month}`;
}
function formatearNumero(numero) {
    return `#P${numero?.toString().padStart(2, '0')}`;
}

//functions of modal
const showModal = ref(false);
const empInitState = { employee: '', charge: '' }
const employeeToAdd = ref(JSON.parse(JSON.stringify(empInitState)))

const showToAddEmployee = () => {
    showModal.value = true;
}
const closeModal = () => {
    showModal.value = false;
};
const add_employee = () => {
    console.log(project)
    if (project) {
        router.post(route('projectmanagement.add.employee', { project_id: project.id }), { ...employeeToAdd.value },
            {
                onError: () => {
                    alert('SERVER ERROR')
                },
                onSuccess: () => {
                    alert('PERSONAL AGREGADO')
                    form.employees.push(JSON.parse(JSON.stringify({
                        name: employeeToAdd.value.employee.name,
                        lastname: employeeToAdd.value.employee.lastname,
                        pivot: { charge: employeeToAdd.value.charge }
                    })));
                }
            }
        )
    } else {
        form.employees.push(JSON.parse(JSON.stringify(employeeToAdd.value)))
        employeeToAdd.value = JSON.parse(JSON.stringify(empInitState))
    }
}
const delete_employee = (index) => {
    form.employees.splice(index, 1);
}

//delete memeber for a create project
const delete_already_employee = (pivot_id, index) => {
    router.delete(route('projectmanagement.delete.employee', { pivot_id }), {
        onError: () => {
            alert('SERVER ERROR')
        },
        onSuccess: () => {
            alert('PERSONAL REMOVIDO')
            form.employees.splice(index, 1);
        }
    })
}


</script>