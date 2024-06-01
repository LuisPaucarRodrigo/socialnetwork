<template>
    <Head title="Proyecto" />
    <AuthenticatedLayout :redirectRoute="'projectmanagement.index'">
        <template v-if="project" #header>
            Edición de proyecto
        </template>
        <template v-else #header>
            Creación de proyecto
        </template>
        <div class="min-w-full p-3 rounded-lg shadow">
            <form @submit.prevent="submit">
                <div class="pt-1">
                    <div class="border-b border-gray-900/10 mb-2">
                    </div>

                    <div class="border-b border-gray-900 pb-12">
                        <h2 v-if="project" class="text-base font-semibold leading-7 text-gray-900">{{ project.name }} | {{
                            project.code }}</h2>
                        <h2 v-else class="text-base font-semibold leading-7 text-gray-900">Registrar nuevo proyecto</h2>
                        <br>
                        <div v-if="!project" class="grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-6 mb-4">
                            <div class="sm:col-span-3">
                                <InputLabel for="preproject_id" class="font-medium leading-6 text-gray-900">Anteproyecto
                                </InputLabel>
                                <div class="mt-2">
                                    <select required id="preproject_id" v-model="form.preproject_id"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                        <option disabled value="">Seleccione uno</option>

                                        <option v-for="(item, i) in preprojects" :key="i" :value="item.id">{{ item.code }}</option>

                                    </select>
                                    <InputError :message="form.errors.preproject_id" />
                                </div>
                            </div>
                        </div>
                        <div v-if="project" class="grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-6 mb-4">
                            <div class="sm:col-span-3">
                                <InputLabel class="font-medium leading-6 text-gray-900">
                                    Nombre Proyecto
                                </InputLabel>
                                <div class="mt-2">
                                <InputLabel class="font-medium leading-6 text-gray-900">
                                    {{ project?.name }}
                                </InputLabel>
                                </div>
                            </div>
                            <div class="sm:col-span-3">
                                <InputLabel class="font-medium leading-6 text-gray-900">
                                    Código
                                </InputLabel>
                                <div class="mt-2">
                                <InputLabel class="font-medium leading-6 text-gray-900">
                                    {{ project?.code }}
                                </InputLabel>
                                </div>
                            </div>
                            <div class="sm:col-span-3">
                                <InputLabel class="font-medium leading-6 text-gray-900">
                                    Fecha de incio
                                </InputLabel>
                                <div class="mt-2">
                                <InputLabel class="font-medium leading-6 text-gray-900">
                                    {{ project?.start_date }}
                                </InputLabel>
                                </div>
                            </div>
                            <div class="sm:col-span-3">
                                <InputLabel class="font-medium leading-6 text-gray-900">
                                    Fecha de fin
                                </InputLabel>
                                <div class="mt-2">
                                <InputLabel class="font-medium leading-6 text-gray-900">
                                    {{ project?.end_date }}
                                </InputLabel>
                                </div>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-6">
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


                            <div class="sm:col-span-3" v-if="project && project?.remaining_budget  !== 0 ">
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
            <Modal :show="showModalMember">
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
                        <SecondaryButton type="button" @click="closeModal"> Cerrar </SecondaryButton>
                        <PrimaryButton type="submit"> Agregar </PrimaryButton>
                    </div>
                </form>
            </Modal>
        </div>
        <ConfirmCreateModal :confirmingcreation="showModal" itemType=" proyecto" />
        <ConfirmUpdateModal :confirmingupdate="showUpdateModal" itemType=" proyecto" />

        <SuccessOperationModal :confirming="showPersonalAddModal" :title="`Personal creado.`"
            :message="`El personal fue añadido.`" />
        <SuccessOperationModal :confirming="showPersonalRemoveModal" :title="`Personal removido.`"
            :message="`El personal fue removido.`"/>

        <ErrorOperationModal :showError="showEmployeeError" title="Presupuesto sobrepasado" message="El presupuesto ha sido sobrepasado" />
    </AuthenticatedLayout>
</template>
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ConfirmCreateModal from '@/Components/ConfirmCreateModal.vue';
import ConfirmUpdateModal from '@/Components/ConfirmUpdateModal.vue';
import SuccessOperationModal from '@/Components/SuccessOperationModal.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import Modal from '@/Components/Modal.vue';
import { ref } from 'vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import { UserPlusIcon, TrashIcon } from '@heroicons/vue/24/outline';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import ErrorOperationModal from '@/Components/ErrorOperationModal.vue';

const showModal = ref(false)
const showUpdateModal = ref(false)
const showEmployeeError = ref(false)

const { employees, start_date, numberOfProjects, project, preprojects } = defineProps({
    employees: Object,
    project: Object,
    preprojects: Object
})

const initialState = {
    preproject_id: '',
    priority: '',
    description: '',
    employees: []
}

const form = useForm(
    project ?
        { ...project }
        :
        { ...initialState }
)

const submit = () => {
    form.post(route('projectmanagement.store'), {
        onSuccess: () => {
            closeModal();
            project ? showUpdateModal.value = true : showModal.value = true
            setTimeout(() => {
                project ? showUpdateModal.value = false : showModal.value = false
                router.visit(route('projectmanagement.index'))
            }, 2000);
        },
        onError: () => {
            close();
        }
    })
}

const showModalMember = ref(false);
const empInitState = { employee: '', charge: '' }
const employeeToAdd = ref(JSON.parse(JSON.stringify(empInitState)))

const showPersonalAddModal = ref(false);
const showPersonalRemoveModal = ref(false);

const showToAddEmployee = () => {
    showModalMember.value = true;
}
const closeModal = () => {
    showModalMember.value = false;
};

const add_employee = () => {
    if (project) {
        router.post(route('projectmanagement.add.employee', { project_id: project.id }), { ...employeeToAdd.value },
            {
                onError: () => {
                    alert('SERVER ERROR')
                },
                onSuccess: () => {
                    showPersonalAddModal.value = true
                    setTimeout(() => {
                        showPersonalAddModal.value = false;
                    }, 1500);
                    router.visit(route('projectmanagement.update', {project_id: project.id}))
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

const delete_already_employee = (pivot_id, index) => {
    router.delete(route('projectmanagement.delete.employee', { pivot_id }), {
        onError: () => {
            alert('SERVER ERROR')
        },
        onSuccess: () => {
            showPersonalRemoveModal.value = true
            setTimeout(() => {
                showPersonalRemoveModal.value = false;
            }, 1500);
            form.employees.splice(index, 1);
        }
    })
}
</script>