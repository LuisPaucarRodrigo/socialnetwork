<template>

    <Head title="Gestion de Empleados" />
    <AuthenticatedLayout :redirectRoute="'management.employees'">
        <template #header>
            Empleados Externos
        </template>

        <div class="min-w-full rounded-lg shadow">
            <div class="mt-6 sm:flex sm:gap-4 sm:justify-between">
                <div class="flex items-center justify-between gap-x-3 w-full">
                    <div v-if="hasPermission('HumanResourceManager')" class="hidden sm:flex sm:items-center space-x-4">
                        <PrimaryButton @click="modal_employees_external()" type="button">
                            + Agregar
                        </PrimaryButton>
                    </div>
                </div>

            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full table-auto whitespace-no-wrap">
                    <thead>
                        <tr
                            class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 w-auto">
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Nombre
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Apellido
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                DNI
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Telefono
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Fecha de Nacimiento
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Direccion
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Correo
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Correo Empresarial
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Salario
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Sctr
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="employee, i in (props.search === undefined ? employees.data : employees)"
                            :key="employee.id" class="text-gray-700">
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <img :src="employee.cropped_image" alt="Empleado" class="w-12 h-13 rounded-full">
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm w-auto">
                                <p class="text-gray-900 whitespace-no-wrap">{{ employee.name }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ employee.lastname }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ employee.dni }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ employee.phone1 }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">
                                    {{ employee.birthday }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">
                                    {{ employee.address }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">
                                    {{ employee.email }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">
                                    {{ employee.email_company }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">
                                    {{ employee.salary }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">
                                    {{ employee.sctr }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">
                                    Curriculum
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <div class="flex space-x-3 justify-center">
                                    <button v-if="hasPermission('HumanResourceManager')" type="button"
                                        @click="modal_employees_external(employee)"
                                        class="text-blue-900 whitespace-no-wrap">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M9 3.75H6.912a2.25 2.25 0 0 0-2.15 1.588L2.35 13.177a2.25 2.25 0 0 0-.1.661V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18v-4.162c0-.224-.034-.447-.1-.661L19.24 5.338a2.25 2.25 0 0 0-2.15-1.588H15M2.25 13.5h3.86a2.25 2.25 0 0 1 2.012 1.244l.256.512a2.25 2.25 0 0 0 2.013 1.244h3.218a2.25 2.25 0 0 0 2.013-1.244l.256-.512a2.25 2.25 0 0 1 2.013-1.244h3.859M12 3v8.25m0 0-3-3m3 3 3-3" />
                                        </svg>
                                    </button>
                                    <button v-if="hasPermission('HumanResourceManager')" type="button"
                                        @click="confirmUserDeletion(employee.id)"
                                        class="text-blue-900 whitespace-no-wrap">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-red-500">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <Modal :show="show_m_employee">
            <div class="p-6">
                <h2 class="text-base font-medium leading-7 text-gray-900">
                    Agregar Empleado
                </h2>
                <form @submit.prevent="submit">
                    <div class="border-b border-gray-900/10 pb-12">
                        <div class="mt-2">
                            <InputLabel for="name">Nombre</InputLabel>
                            <div class="mt-2">
                                <TextInput type="date" id="name" v-model="form.name" required />
                                <InputError :message="form.errors.name" />
                            </div>
                        </div>
                        <div class="mt-2">
                            <InputLabel for="lastname">Apellido</InputLabel>
                            <div class="mt-2">
                                <TextInput type="date" id="lastname" v-model="form.lastname" required />
                                <InputError :message="form.errors.lastname" />
                            </div>
                        </div>
                        <div class="mt-2">
                            <InputLabel for="gender">Genero</InputLabel>
                            <div class="mt-2">
                                <TextInput type="date" id="gender" v-model="form.gender" required />
                                <InputError :message="form.errors.gender" />
                            </div>
                        </div>
                        <div class="mt-2">
                            <InputLabel for="address">Direccion</InputLabel>
                            <div class="mt-2">
                                <TextInput type="text" id="address" v-model="form.address" required />
                                <InputError :message="form.errors.address" />
                            </div>
                        </div>
                        <div class="mt-2">
                            <InputLabel for="birthdate">Fecha de Nacimiento</InputLabel>
                            <div class="mt-2">
                                <TextInput type="date" id="birthdate" v-model="form.birthdate" required />
                                <InputError :message="form.errors.birthdate" />
                            </div>
                        </div>
                        <div class="mt-2">
                            <InputLabel for="dni">DNI</InputLabel>
                            <div class="mt-2">
                                <TextInput type="number" id="dni" v-model="form.dni" required />
                                <InputError :message="form.errors.dni" />
                            </div>
                        </div>
                        <div class="mt-2">
                            <InputLabel for="email">Correo Electronico</InputLabel>
                            <div class="mt-2">
                                <TextInput type="email" id="email" v-model="form.email" required />
                                <InputError :message="form.errors.email" />
                            </div>
                        </div>
                        <div class="mt-2">
                            <InputLabel for="email_company">Correo Electronico de Compañia</InputLabel>
                            <div class="mt-2">
                                <TextInput type="email" id="email_company" v-model="form.email_company" required />
                                <InputError :message="form.errors.email_company" />
                            </div>
                        </div>
                        <div class="mt-2">
                            <InputLabel for="phone1">Telefono</InputLabel>
                            <div class="mt-2">
                                <TextInput type="number" id="phone1" v-model="form.phone1" required />
                                <InputError :message="form.errors.phone1" />
                            </div>
                        </div>
                        <div class="mt-2">
                            <InputLabel for="salary">Salario</InputLabel>
                            <div class="mt-2">
                                <TextInput type="date" id="salary" v-model="form.salary" required />
                                <InputError :message="form.errors.salary" />
                            </div>
                        </div>
                        <div class="mt-2">
                            <InputLabel for="sctr">SCTR</InputLabel>
                            <div class="mt-2">
                                <TextInput type="date" id="sctr" v-model="form.sctr" required />
                                <InputError :message="form.errors.sctr" />
                            </div>
                        </div>
                        <div class="mt-2">
                            <InputLabel for="curriculum_vitae">Curriculum Vitae</InputLabel>
                            <div class="mt-2">
                                <InputFile type="file" v-model="form.curriculum_vitae" id="curriculum_vitae"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.curriculum_vitae" />
                            </div>
                        </div>
                        <div class="mt-2">
                            <InputLabel for="reentry_date">Foto de Usuario </InputLabel>
                            <div class="mt-2">
                                <ModalImage v-model="form.cropped_image" @imagenRecortada="handleImagenRecortada" />
                                <InputError :message="form.errors.cropped_image" />
                            </div>
                        </div>
                        <div class="mt-6 flex items-center justify-end gap-x-6">
                            <SecondaryButton @click="modal_employees_external()"> Cancel </SecondaryButton>
                            <PrimaryButton type="submit" :class="{ 'opacity-25': form.processing }"> Guardar
                            </PrimaryButton>
                        </div>
                    </div>
                </form>
            </div>
        </Modal>
        <ConfirmDeleteModal :confirmingDeletion="confirmingUserDeletion" itemType="empleado" deleteText="Eliminar"
            :deleteFunction="deleteEmployee" @closeModal="closeModal" />
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ConfirmDeleteModal from '@/Components/ConfirmDeleteModal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import ConfirmCreateModal from '@/Components/ConfirmCreateModal.vue';
import ConfirmUpdateModal from '@/Components/ConfirmUpdateModal.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import Modal from '@/Components/Modal.vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const confirmingUserDeletion = ref(false);
const employeeToDelete = ref(null);
const show_m_employee = ref(false);
const props = defineProps({
    employees: Object,
    userPermissions: Array,
})


const hasPermission = (permission) => {
    return props.userPermissions.includes(permission);
}

const form = useForm({
    id: null,
    name: '',
    lastname: '',
    gender: '',
    address: '',
    birthdate: '',
    dni: '',
    email: '',
    email_company: '',
    phone1: '',
    salary: '',
    sctr: '',
    curriculum_vitae: null,
    cropped_image: null
})


const submit = () => {
    form.post(route('management.external.storeorupdate', { external_id: form.id }), {
        onSuccess: () => {
            modal_employees_external();
            router.visit(route('management.employees'));
        },
        onError: () => {

        }
    })
}

const confirmUserDeletion = (employeeId) => {
    employeeToDelete.value = employeeId;
    confirmingUserDeletion.value = true;
};

const deleteEmployee = () => {
    const employeeId = employeeToDelete.value;
    if (employeeId) {
        router.delete(route('employees.external.delete', { id: employeeId }), {
            onSuccess: () => closeModal()
        });
    }
};

const closeModal = () => {
    confirmingUserDeletion.value = false;
};

function modal_employees_external(employee) {
    if (employee != null) {
        form.defaults({ ...employee })
    }
    show_m_employee.value = !show_m_employee.value
};

</script>