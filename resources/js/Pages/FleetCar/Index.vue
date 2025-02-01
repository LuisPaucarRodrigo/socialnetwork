<template>

    <Head title="Gestion de Empleados" />
    <AuthenticatedLayout :redirectRoute="'management.employees'">
        <template #header>
            Empleados
        </template>

        <div class="min-w-full rounded-lg shadow">
            <div class="mt-6 sm:flex sm:gap-4 sm:justify-between">
                <div class="flex items-center justify-between gap-x-3 w-full">
                    <div v-if="hasPermission('HumanResourceManager')" class="hidden sm:flex sm:items-center space-x-3">
                        <PrimaryButton @click="add_information" type="button">
                            + Agregar
                        </PrimaryButton>
                    </div>

                    <!-- Dropdown para pantallas pequeñas -->
                    <!-- <div v-if="hasPermission('HumanResourceManager')" class="sm:hidden">
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
                                <div>
                                    <div class="dropdown">
                                        <div class="dropdown-menu">
                                            <button @click="add_information"
                                                class="dropdown-item block w-full text-left px-4 py-2 text-sm text-black-700 hover:bg-indigo-600 hover:text-white focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                                                Agregar
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </dropdown>
                    </div> -->
                    <PrimaryButton @click="reentry" type="button">
                        {{ formSearch.state === 'Inactive' ? "Activos" : "Inactivos" }}
                    </PrimaryButton>
                </div>
                <div class="flex items-center mt-4 sm:mt-0">
                    <TextInput data-tooltip-target="search_fields" type="text" placeholder="Buscar..."
                        v-model="formSearch.search" />
                    <div id="search_fields" role="tooltip"
                        class="absolute z-10 invisible inline-block px-2 py-2 text-xs font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                        Placa,Marca,Modelo,Tipo
                        <div class="tooltip-arrow" data-popper-arrow></div>
                    </div>
                </div>
            </div>
            <div class="overflow-x-auto h-[72vh] rounded-lg shadow">
                <table class="w-full bg-white">
                    <thead class="sticky top-0 z-20">
                        <tr
                            class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 w-auto">
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                <div class="w-[190px]">
                                    <TableHeaderCicsaFilter label="Linea de Negocio" labelClass="text-gray-600"
                                        :options="cost_line" v-model="formSearch.cost_line" />
                                </div>
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Placa
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Modelo
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Marca
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Año
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Tipo
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Foto
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Dueño
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="car, i in cars.data || cars" :key="car.id" class="text-gray-700">
                            <!-- <td class="border-b border-gray-200 bg-white px-5 py-2 text-sm">
                                <p class="inline-block text-gray-900 whitespace-nowrap text-center w-[22px]">
                                    {{ props.search === undefined ?
                                        realNumeration(employees.per_page, employees.current_page, i)
                                        :
                                        i + 1

                                    }}
                                </p>
                            </td> -->
                            <!-- <td class="border-b border-gray-200 bg-white px-5 py-2 text-sm">
                                <img :src="car.cropped_image" alt="Empleado" class="w-12 h-13 rounded-full">
                            </td> -->
                            <td class="border-b border-gray-200 bg-white px-5 py-2 text-sm">
                                <p class="text-gray-900">{{ car.contract?.cost_line?.name }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-2 text-sm">
                                <p class="text-gray-900">{{ car.plate }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-2 text-sm">
                                <p class="text-gray-900">{{ car.model }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-2 text-sm">
                                <p class="text-gray-900">{{ car.brand }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-2 text-sm">
                                <p class="text-gray-900">{{ car.year }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-2 text-sm">
                                <p class="text-gray-900">{{ car.type }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-2 text-sm">
                                <p class="text-gray-900">{{ car.photo }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-2 text-sm">
                                <p class="text-gray-900">{{ car.user.name }}</p>
                            </td>
                            <!-- <td class="border-b border-gray-200 bg-white px-5 py-2 text-sm">
                                <p class="text-gray-900">
                                    {{ formattedDate(employee.contract.hire_date) }}
                                </p>
                            </td> -->
                            <!-- <td class="border-b border-gray-200 bg-white px-5 py-2 text-sm">
                                <div v-if="employee.contract.fired_date == null" class="flex space-x-3 justify-center">
                                    <Link class="text-blue-900"
                                        :href="route('management.employees.show', { id: employee.id })">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-teal-500">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    </Link>
                                    <Link v-if="hasPermission('HumanResourceManager')"
                                        class="text-blue-900"
                                        :href="route('management.employees.edit', { id: employee.id })">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-amber-400">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                    </svg>
                                    </Link>
                                    <button v-if="hasPermission('UserManager')" type="button"
                                        @click="confirmFired(employee.id)" class="text-blue-900">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M9 3.75H6.912a2.25 2.25 0 0 0-2.15 1.588L2.35 13.177a2.25 2.25 0 0 0-.1.661V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18v-4.162c0-.224-.034-.447-.1-.661L19.24 5.338a2.25 2.25 0 0 0-2.15-1.588H15M2.25 13.5h3.86a2.25 2.25 0 0 1 2.012 1.244l.256.512a2.25 2.25 0 0 0 2.013 1.244h3.218a2.25 2.25 0 0 0 2.013-1.244l.256-.512a2.25 2.25 0 0 1 2.013-1.244h3.859M12 3v8.25m0 0-3-3m3 3 3-3" />
                                        </svg>
                                    </button>
                                </div>
                                <button v-if="employee.contract.fired_date" type="button"
                                    @click="employee_fired_date(employee.id)" class="text-blue-900">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5" />
                                    </svg>
                                </button>
                            </td> -->
                        </tr>
                    </tbody>
                </table>
            </div>
            <div v-if="employees.data"
                class="flex flex-col items-center border-t bg-white px-5 py-5 xs:flex-row xs:justify-between">
                <pagination :links="employees.links" />
            </div>
        </div>

        <!-- <Modal :show="showModalReentry">
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
                            <InputLabel for="fired_date">Fecha de Despido:
                            </InputLabel>
                            <div class="mt-2">
                                <TextInput type="date" id="fired_date" v-model="form.fired_date" />
                                <InputError :message="form.errors.fired_date" />
                            </div>
                        </div>
                        <div class="mt-6">
                            <InputLabel for="days_taken">Dias Tomados:
                            </InputLabel>
                            <div class="mt-2">
                                <TextInput type="text" id="days_taken" v-model="form.days_taken" />
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
        <ConfirmUpdateModal :confirmingupdate="updateSchedule" itemType="Horario" /> -->
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
import { ref, watch } from 'vue';
import InputError from '@/Components/InputError.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import { formattedDate, realNumeration } from '@/utils/utils';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { notifyError } from '@/Components/Notification';
import TableHeaderCicsaFilter from '@/Components/TableHeaderCicsaFilter.vue';

// const confirmingUserDeletion = ref(false);
// const deleteButtonText = 'Eliminar';
// const employeeToDelete = ref(null);
// const employeeToFired = ref(null);
// const employeeReentry = ref(null);
// const showModalFired = ref(false);
// const createSchedule = ref(false);
// const updateSchedule = ref(false);
// const showModalReentry = ref(false);

const props = defineProps({
    car: Object,
    userPermissions: Array,
    costLine: Object
})

const cars = ref(props.car)

const hasPermission = (permission) => {
    return props.userPermissions.includes(permission);
}

// const form = useForm({
//     fired_date: '',
//     days_taken: '',
//     state: 'Inactive'
// })

// const form1 = useForm({
//     reentry_date: '',
// })

const cost_line = props.costLine.map(item => item.name)

const initialFormSearch = {
    cost_line: [...cost_line],
    search: ''
}

const formSearch = ref({ ...initialFormSearch })

// function reentry() {
//     if (formSearch.value.state === 'Active') {
//         formSearch.value.state = 'Inactive'
//     } else {
//         formSearch.value.state = 'Active'
//     }
// }

// watch(
//     () => [
//         formSearch.value.state,
//         formSearch.value.cost_line,
//         formSearch.value.search,
//     ],
//     () => {
//         search();
//     },
//     { deep: true }
// );

// function submit() {
//     let url = employeeReentry.value ? route('management.employees.reentry', { id: employeeReentry.value }) : route('management.employees.fired', employeeToFired.value)
//     let formData = employeeReentry.value ? form1 : form
//     router.put(url, formData, {
//         onSuccess: () => {
//             if (employeeReentry.value) {
//                 closeReentryModal();
//             } else {
//                 closeFiredModal();
//             }
//             closeReentryModal();
//             router.visit(route('management.employees'));
//         }
//     })
// }

// const confirmUserDeletion = (employeeId) => {
//     employeeToDelete.value = employeeId;
//     confirmingUserDeletion.value = true;
// };

// const confirmFired = (firedId) => {
//     employeeToFired.value = firedId
//     showModalFired.value = true
// }

// const closeFiredModal = () => {
//     showModalFired.value = false
// }

// const deleteEmployee = () => {
//     const employeeId = employeeToDelete.value;
//     if (employeeId) {
//         router.delete(route('management.employees.destroy', { id: employeeId }), {
//             onSuccess: () => closeModal()
//         });
//     }
// };

// const closeModal = () => {
//     confirmingUserDeletion.value = false;
// };

// const add_information = () => {
//     router.get(route('management.employees.create'));
// };

// const employee_fired_date = ($id) => {
//     employeeReentry.value = $id
//     showModalReentry.value = true
// };

// const closeReentryModal = () => {
//     showModalReentry.value = false;
// };

// async function search() {
//     let url = route('management.employees.search')
//     try {
//         let response = await axios.post(url, formSearch.value)
//         employees.value = response.data
//     } catch (error) {
//         if (error.response.data) {
//             notifyError('Server error', error.response.data)
//         } else {
//             notifyError("Network or other error:", error)
//         }
//     }
// }

</script>