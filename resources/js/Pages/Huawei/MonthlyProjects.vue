<template>

    <Head title="Proyectos" />
    <AuthenticatedLayout :redirectRoute="'huawei.monthlyprojects'">
        <template #header>
            Proyectos Mensuales de Huawei
        </template>
        <div class="min-w-full rounded-lg shadow">
            <div class="flex flex-col gap-4 justify-center sm:flex-row sm:justify-between rounded-lg items-center text-center sm:text-left">
                <div class="flex flex-col sm:flex-row gap-4 w-full justify-between items-center">
                    <div class="flex gap-4 items-center justify-center sm:justify-start">
                        <button @click="createOrEditModal" type="button"
                            class="hidden sm:block items-center px-4 py-2 border-2 border-gray-700 rounded-md font-semibold text-xs hover:text-gray-700 uppercase tracking-widest bg-gray-700 hover:underline hover:bg-gray-200 focus:border-indigo-600 focus:outline-none focus:ring-2 text-white whitespace-nowrap">
                            + Agregar
                        </button>
                    </div>
                    <div class="flex items-center justify-center sm:justify-end w-full sm:w-auto mt-4 sm:mt-0">
                        <form @submit.prevent="search" class="flex items-center w-full sm:w-auto">
                            <TextInput type="text" placeholder="Buscar..." v-model="searchForm.searchTerm" class="mr-2" />
                            <button type="submit" :class="{ 'opacity-25': searchForm.processing }"
                                class="ml-2 rounded-md bg-indigo-600 px-2 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                                <svg width="30px" height="21px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M15.7955 15.8111L21 21M18 10.5C18 14.6421 14.6421 18 10.5 18C6.35786 18 3 14.6421 3 10.5C3 6.35786 6.35786 3 10.5 3C14.6421 3 18 6.35786 18 10.5Z"
                                        stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <br>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-4 gap-3">
                <div v-for="item in (props.search ? props.projects : projects.data)" :key="item.id"
                    class="bg-white p-3 rounded-md shadow-sm border border-gray-300 items-center">
                    <div class="grid grid-cols-2">
                        <h2 class="text-sm font-semibold mb-3 mr-3">
                            {{ item.date }}
                        </h2>
                        <div class="inline-flex justify-end items-start gap-x-2">
                            <button @click.prevent="editProject(item)"
                                class="flex items-start">
                                <PencilSquareIcon class="h-5 w-5 text-yellow-400" />
                            </button>
                        </div>
                    </div>
                    <div class="flex gap-1">
                        <h3 class="text-sm font-semibold text-gray-700 line-clamp-1 mb-1 whitespace-nowrap">
                            {{ item.description }}
                        </h3>
                    </div>

                    <div class="text-gray-500 text-sm mt-1">
                            <div class="grid grid-cols-1 gap-y-1">
                                <Link
                                    :href="route('huawei.monthlyexpenses.expenses', { project: item.id })"
                                    class="text-blue-600 underline whitespace-no-wrap hover:text-purple-600">
                                    Gastos
                                </Link>
                            </div>
                        </div>
                </div>
            </div>
            <br>
            <div v-if="!props.search" class="flex flex-col items-center border-t px-5 py-5 xs:flex-row xs:justify-between">
                <pagination :links="props.projects.links" />
            </div>
        </div>

        <Modal :show="showModal">
            <div class="p-6">
                <h2 class="text-base font-medium leading-7 text-gray-900">
                    {{ form.id ? 'Actualizar Proyecto' : 'Crear Proyecto' }}
                </h2>
                <form @submit.prevent="form.id ? submit(true) : submit(false)">
                    <div class="space-y-12 mt-4">
                        <div class="border-b grid sm:grid-cols-2 gap-6 border-gray-900/10 pb-6">
                            <div>
                                <InputLabel for="date" class="font-medium leading-6 text-gray-900">
                                    Fecha de Proyecto
                                </InputLabel>
                                <div class="mt-2">
                                    <TextInput type="month" v-model="form.date" id="date" />
                                    <InputError :message="form.errors.date" />
                                </div>
                            </div>
                            <div>
                                <InputLabel for="description" class="font-medium leading-6 text-gray-900">Descripción
                                </InputLabel>
                                <div class="mt-2">
                                    <TextInput type="text" step="0.0001" v-model="form.description" id="description" />
                                    <InputError :message="form.errors.description" />
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center justify-between">
                            <div>
                                <InputLabel for="employees" class="hidden sm:block font-medium leading-6 text-gray-900">Empleados</InputLabel>
                            </div>
                            <div class="flex items-center gap-2">
                                <select v-model="selectedEmployee" id="employees"  class="block w-full min-w-[250px] rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option disabled value="">Selecciona un empleado</option>
                                    <option v-for="employee in availableEmployees" :key="employee.id" :value="employee">
                                        {{ employee.name + ' ' + employee.lastname }}
                                    </option>
                                </select>
                                <button type="button" @click.prevent="addEmployee">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-7 h-7 text-indigo-500">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full whitespace-no-wrap">
                                <thead>
                                    <tr class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                        <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 text-center">
                                            N°
                                        </th>
                                        <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 text-center">
                                            Nombre
                                        </th>
                                        <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 text-center">
                                            Apellido
                                        </th>
                                        <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600 text-center">

                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(employee, index) in form.employees" :key="index" class="text-gray-700">
                                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">{{ index + 1 }}</td>
                                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">{{ employee.name }}</td>
                                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">{{ employee.lastname }}</td>
                                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">
                                            <button @click.prevent="deleteEmployee(index)"
                                                class="flex items-start">
                                                <TrashIcon class="h-5 w-5 text-red-600" />
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-6 flex items-center justify-end gap-x-3">
                            <SecondaryButton @click="createOrEditModal">
                                Cancelar
                            </SecondaryButton>
                            <PrimaryButton type="submit" :class="{ 'opacity-25': form.processing }"
                                :disabled="form.processing">
                                Guardar
                            </PrimaryButton>
                        </div>
                    </div>
                </form>
            </div>
        </Modal>
        <SuccessOperationModal :confirming="showSuccessModal" :title="'Éxito'"
        :message="successMessage" />
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Pagination from '@/Components/Pagination.vue'
import { Head, router, Link, useForm } from '@inertiajs/vue3';
import { PencilSquareIcon, TrashIcon } from '@heroicons/vue/24/outline';
import TextInput from '@/Components/TextInput.vue';
import Modal from '@/Components/Modal.vue';
import { ref, computed } from 'vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import SuccessOperationModal from '@/Components/SuccessOperationModal.vue';

const props = defineProps({
    projects: Object,
    auth: Object,
    userPermissions:Array,
    search: String,
    employees: Object
})

const showModal = ref(false);
const showSuccessModal = ref(false);
const successMessage = ref('');
const selectedEmployee = ref('');
const huaweiIds = [37, 42, 26, 18, 32, 8, 43, 41, 39, 10, 44, 45];

const huaweiEmployees = props.employees.filter(employee => huaweiIds.includes(employee.id));


const hasPermission = (permission) => {
    return props.userPermissions.includes(permission);
}

const searchForm = useForm({
    searchTerm: props.search ? props.search : '',
})

const search = () => {
    if (searchForm.searchTerm == ''){
        router.visit(route('huawei.monthlyprojects'))
    }else{
        router.visit(route('huawei.monthlyprojects.search', { request: searchForm.searchTerm}));
    }
}

const initialState = {
    id: '',
    date: '',
    description: '',
    employees: [...huaweiEmployees]
}

const form = useForm({...initialState});

const editProject = (project) => {
    Object.assign(form, project);
    const newEmployees = project.huawei_monthly_employees ? project.huawei_monthly_employees.map(employee => ({
        id: employee.id,
        name: employee.name,
        lastname: employee.lastname
    })) : [];
    form.employees = newEmployees
    createOrEditModal();
}

const createOrEditModal = () => {
    if (showModal.value) {
        form.defaults({ ...initialState })
        selectedEmployee.value = ''
        form.reset()
    }
    showModal.value = !showModal.value
};

const submit = (update) => {
    if (!update){
        const url = route('huawei.monthlyprojects.store');
        form.post(url, {
            onSuccess: () => {
                createOrEditModal();
                form.reset();
                successMessage.value = 'Se creó el proyecto correctamente';
                showSuccessModal.value = true;
                setTimeout(() => {
                    showSuccessModal.value = false;
                }, 2000);
            }
        })
    }else{
        const url = route('huawei.monthlyprojects.update', {project: form.id});
        form.put(url, {
            onSuccess: () => {
                createOrEditModal();
                form.reset();
                successMessage.value = 'Se actualizó el proyecto correctamente';
                showSuccessModal.value = true;
                setTimeout(() => {
                    showSuccessModal.value = false;
                }, 2000);
            }
        })
    }
}

const addEmployee = () => {
    form.employees.push(selectedEmployee.value);
}

const availableEmployees = computed(() => {
    const selectedEmployeeIds = form.employees.map(employee => employee.id);
    return props.employees.filter(employee => !selectedEmployeeIds.includes(employee.id));
});

const deleteEmployee = (index) => {
    form.employees.splice(index, 1);
}

</script>
