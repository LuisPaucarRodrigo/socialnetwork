<template>
    <Head title="Gestion de Empleados" />
    <AuthenticatedLayout :redirectRoute="'management.employees'">
        <template #header>
            Empleados
        </template>

        <div class="min-w-full rounded-lg shadow">
            <div class="mt-6 sm:flex sm:gap-4 sm:justify-between">
                <div class="flex items-center justify-between gap-x-3 w-full">
                    <div v-if="hasPermission('HumanResourceManager')" class="hidden sm:flex sm:items-center space-x-4">
                        <PrimaryButton @click="add_information" type="button">
                            + Agregar
                        </PrimaryButton>

                        <Link :href="route('management.employees.schedule.index')"
                            class="rounded-md bg-indigo-600 px-4 py-2 text-center text-sm text-white hover:bg-indigo-500">
                        Horarios
                        </Link>
                    </div>

                    <!-- Dropdown para pantallas pequeñas -->
                    <div v-if="hasPermission('HumanResourceManager')" class="sm:hidden">
                        <dropdown align='left'>
                            <template #trigger>
                                <button @click="dropdownOpen = !dropdownOpen"
                                    class="relative block overflow-hidden rounded-md bg-gray-200 px-2 py-2 text-center text-sm text-white hover:bg-gray-100">
                                    <svg width="25px" height="25px" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path d="M4 6H20M4 12H20M4 18H20" stroke="#000000" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </button>
                            </template>

                            <template #content class="origin-left">
                                <div> <!-- Alineación a la derecha -->
                                    <div class="dropdown">
                                        <div class="dropdown-menu">
                                            <button @click="add_information"
                                                class="dropdown-item block w-full text-left px-4 py-2 text-sm text-black-700 hover:bg-indigo-600 hover:text-white focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                                                Agregar
                                            </button>
                                        </div>
                                    </div>
                                    <dropdown-link :href="route('management.employees.schedule.index')">
                                        Horarios
                                    </dropdown-link>
                                </div>
                            </template>
                        </dropdown>
                    </div>
                    <PrimaryButton @click="reentry" type="button">
                        {{ reentrystate == false ? "Inactivos" : "Activos" }}
                    </PrimaryButton>
                </div>
                <div class="flex items-center mt-4 sm:mt-0">
                    <form @submit.prevent="search" class="flex items-center w-full sm:w-auto">
                        <TextInput type="text" placeholder="Buscar..." v-model="searchForm.searchTerm" />
                        <button type="submit" :class="{ 'opacity-25': searchForm.processing }"
                            class="ml-2 rounded-md bg-indigo-600 px-2 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                            <svg width="30px" height="21px" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M15.7955 15.8111L21 21M18 10.5C18 14.6421 14.6421 18 10.5 18C6.35786 18 3 14.6421 3 10.5C3 6.35786 6.35786 3 10.5 3C14.6421 3 18 6.35786 18 10.5Z"
                                    stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full whitespace-no-wrap">
                    <thead>
                        <tr
                            class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
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
                                Fecha de Ingreso
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="employee in (props.search === undefined ? employees.data : employees)"
                            :key="employee.id" class="text-gray-700">
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <img :src="employee.cropped_image" alt="Empleado" class="w-12 h-13 rounded-full">
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
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
                                    {{ formattedDate(employee.contract.hire_date) }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <div v-if="employee.contract.fired_date == null" class="flex space-x-3 justify-center">
                                    <Link class="text-blue-900 whitespace-no-wrap"
                                        :href="route('management.employees.information.details', { id: employee.id })">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-teal-500">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    </Link>
                                    <Link v-if="hasPermission('HumanResourceManager')" class="text-blue-900 whitespace-no-wrap"
                                        :href="route('management.employees.edit', { id: employee.id })">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-amber-400">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                    </svg>
                                    </Link>
                                    <button v-if="hasPermission('UserManager')" type="button"
                                        @click="confirmUserDeletion(employee.id)"
                                        class="text-blue-900 whitespace-no-wrap">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-red-500">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                        </svg>
                                    </button>
                                    <button v-if="hasPermission('UserManager')" type="button"
                                        @click="confirmFired(employee.id)" class="text-blue-900 whitespace-no-wrap">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M9 3.75H6.912a2.25 2.25 0 0 0-2.15 1.588L2.35 13.177a2.25 2.25 0 0 0-.1.661V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18v-4.162c0-.224-.034-.447-.1-.661L19.24 5.338a2.25 2.25 0 0 0-2.15-1.588H15M2.25 13.5h3.86a2.25 2.25 0 0 1 2.012 1.244l.256.512a2.25 2.25 0 0 0 2.013 1.244h3.218a2.25 2.25 0 0 0 2.013-1.244l.256-.512a2.25 2.25 0 0 1 2.013-1.244h3.859M12 3v8.25m0 0-3-3m3 3 3-3" />
                                        </svg>
                                    </button>
                                </div>
                                <button v-if="employee.contract.fired_date" type="button"
                                    @click="employee_fired_date(employee.id)" class="text-blue-900 whitespace-no-wrap">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5" />
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div v-if="props.search === undefined"
                class="flex flex-col items-center border-t bg-white px-5 py-5 xs:flex-row xs:justify-between">
                <pagination :links="employees.links" />
            </div>
        </div>

        <Modal :show="showModalReentry">
            <div class="p-6">
                <h2 class="text-base font-medium leading-7 text-gray-900">
                    Reingreso del Empleado
                </h2>
                <form @submit.prevent="submit">
                    <div class="border-b border-gray-900/10 pb-12">
                        <div class="mt-2">
                            <InputLabel for="reentry_date">Fecha de
                                Reingreso o
                                Recontratacion:
                            </InputLabel>
                            <div class="mt-2">
                                <TextInput type="date" id="reentry_date" v-model="form1.reentry_date" required />
                                <InputError :message="form.errors.reentry_date" />
                            </div>
                        </div>
                        <div class="mt-6 flex items-center justify-end gap-x-6">
                            <SecondaryButton @click="closeReentryModal"> Cancel </SecondaryButton>
                            <PrimaryButton type="submit" :class="{ 'opacity-25': form.processing }"> Guardar
                            </PrimaryButton>
                        </div>
                    </div>
                </form>
            </div>
        </Modal>
        <Modal :show="showModalFired">
            <div class="p-6">
                <h2 class="text-base font-medium leading-7 text-gray-900">
                    Despido del Empleado
                </h2>
                <form @submit.prevent="submit">
                    <div class="border-b border-gray-900/10 pb-12">
                        <div class="mt-2">
                            <InputLabel for="fired_date">Fecha de Deceso:
                            </InputLabel>
                            <div class="mt-2">
                                <TextInput type="date" id="fired_date" v-model="form.fired_date"/>
                                <InputError :message="form.errors.fired_date" />
                            </div>
                        </div>
                        <div class="mt-6">
                            <InputLabel for="days_taken">Dias Tomados:
                            </InputLabel>
                            <div class="mt-2">
                                <TextInput type="text" id="days_taken" v-model="form.days_taken"/>
                                <InputError :message="form.errors.days_taken" />
                            </div>
                        </div>
                        <div class="mt-6 flex items-center justify-end gap-x-6">
                            <SecondaryButton @click="closeFiredModal"> Cancel </SecondaryButton>
                            <PrimaryButton type="submit" :class="{ 'opacity-25': form.processing }">
                                Guardar
                            </PrimaryButton>
                        </div>
                    </div>
                </form>
            </div>
        </Modal>
        <ConfirmDeleteModal :confirmingDeletion="confirmingUserDeletion" itemType="empleado"
            :deleteText="deleteButtonText" :deleteFunction="deleteEmployee" @closeModal="closeModal" />
        <ConfirmCreateModal :confirmingcreation="createSchedule" itemType="Horario" />
        <ConfirmUpdateModal :confirmingupdate="updateSchedule" itemType="Horario" />
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ConfirmDeleteModal from '@/Components/ConfirmDeleteModal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import Pagination from '@/Components/Pagination.vue';
import ConfirmCreateModal from '@/Components/ConfirmCreateModal.vue';
import ConfirmUpdateModal from '@/Components/ConfirmUpdateModal.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import Modal from '@/Components/Modal.vue';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import InputError from '@/Components/InputError.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import { formattedDate } from '@/utils/utils';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const confirmingUserDeletion = ref(false);
const deleteButtonText = 'Eliminar';
const employeeToDelete = ref(null);
const employeeToFired = ref(null);
const employeeReentry = ref(null);
const showModalFired = ref(false);
const createSchedule = ref(false);
const updateSchedule = ref(false);
const showModalReentry = ref(false);

const props = defineProps({
    employees: Object,
    boolean: Boolean,
    userPermissions: Array,
    search: String
})


const hasPermission = (permission) => {
    return props.userPermissions.includes(permission);
}

const reentrystate = ref(props.boolean);

const reentry = () => {
    if (props.boolean == true) {
        router.get(route('management.employees'))
    } else {
        reentrystate.value = true
        router.get(route('management.employees', { reentry: reentrystate.value }))
    }
}

const form = useForm({
    fired_date: '',
    days_taken: '',
    state: 'Inactive'
})

const form1 = useForm({
    reentry_date: '',
})


const submit = () => {
    if (employeeReentry.value != null) {
        router.put(route('management.employees.reentry', { id: employeeReentry.value }), form1, {
            onSuccess: () => {
                closeReentryModal();
                router.visit(route('management.employees'));
            }

        })
    } else {
        router.put(route('management.employees.fired', employeeToFired.value), form, {
            onSuccess: () => {
                closeFiredModal();
                router.visit(route('management.employees'));
            }

        })
    }
}

const confirmUserDeletion = (employeeId) => {
    employeeToDelete.value = employeeId;
    confirmingUserDeletion.value = true;
};

const confirmFired = (firedId) => {
    employeeToFired.value = firedId
    showModalFired.value = true
}

const closeFiredModal = () => {
    showModalFired.value = false
}

const deleteEmployee = () => {
    const employeeId = employeeToDelete.value;
    if (employeeId) {
        router.delete(route('management.employees.destroy', { id: employeeId }), {
            onSuccess: () => closeModal()
        });
    }
};

const closeModal = () => {
    confirmingUserDeletion.value = false;
};

const add_information = () => {
    router.get(route('management.employees.information'));
};

const employee_fired_date = ($id) => {
    employeeReentry.value = $id
    showModalReentry.value = true
};

const closeReentryModal = () => {
    showModalReentry.value = false;
};

const searchForm = useForm({
    searchTerm: props.search ? props.search : '',
})

const search = () => {
    if (searchForm.searchTerm == '') {
        if (!props.boolean == true) {
            reentrystate.value = false
            router.get(route('management.employees'))
        } else {
            reentrystate.value = true
            router.get(route('management.employees', {
                reentry: reentrystate.value
            }))
        }
    } else {
        router.get(route('management.employees.search'), {
            searchTerm: searchForm.searchTerm,
            isActive: !props.boolean
        });
    }
}

</script>