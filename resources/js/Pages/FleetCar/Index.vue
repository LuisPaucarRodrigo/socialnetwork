<template>

    <Head title="Gestion de Empleados" />
    <AuthenticatedLayout :redirectRoute="'fleet.cars.index'">
        <Toaster richColors />
        <template #header>
            Vehiculos
        </template>

        <div class="min-w-full rounded-lg shadow">
            <div class="mt-6 sm:flex sm:gap-4 sm:justify-between">
                <div class="flex items-center justify-between gap-x-3 w-full">
                    <div class="hidden sm:flex sm:items-center space-x-3">
                        <PrimaryButton @click="openModalCreate()" type="button">
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
                            <th v-if="hasPermission(UserManager)"
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
                            <td v-if="hasPermission(UserManager)"
                                class="border-b border-gray-200 bg-white px-5 py-2 text-sm">
                                <p class="text-gray-900">{{ car.costline?.name }}</p>
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
                            <td class="border-b border-gray-200 bg-white px-5 py-2 text-sm">
                                <div class="flex space-x-3 justify-center">
                                    <Link v-if="hasPermission('CarManager')"
                                        :href="route('fleet.cars.show_documents', { car: car.id })" class="text-blue-900">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-blue-400">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15.75 17.25v3.375c0 .621-.504 1.125-1.125 1.125h-9.75a1.125 1.125 0 0 1-1.125-1.125V7.875c0-.621.504-1.125 1.125-1.125H6.75a9.06 9.06 0 0 1 1.5.124m7.5 10.376h3.375c.621 0 1.125-.504 1.125-1.125V11.25c0-4.46-3.243-8.161-7.5-8.876a9.06 9.06 0 0 0-1.5-.124H9.375c-.621 0-1.125.504-1.125 1.125v3.5m7.5 10.375H9.375a1.125 1.125 0 0 1-1.125-1.125v-9.25m12 6.625v-1.875a3.375 3.375 0 0 0-3.375-3.375h-1.5a1.125 1.125 0 0 1-1.125-1.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H9.75" />
                                    </svg>
                                    </Link>
                                    <button v-if="hasPermission('CarManager')" type="button" @click="openModalEdit(car)"
                                        class="text-blue-900">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-amber-400">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                        </svg>
                                    </button>
                                    <button v-if="hasPermission('UserManager')" type="button"
                                        @click="openModalDeleteCars(car.id)" class="text-blue-900">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M9 3.75H6.912a2.25 2.25 0 0 0-2.15 1.588L2.35 13.177a2.25 2.25 0 0 0-.1.661V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18v-4.162c0-.224-.034-.447-.1-.661L19.24 5.338a2.25 2.25 0 0 0-2.15-1.588H15M2.25 13.5h3.86a2.25 2.25 0 0 1 2.012 1.244l.256.512a2.25 2.25 0 0 0 2.013 1.244h3.218a2.25 2.25 0 0 0 2.013-1.244l.256-.512a2.25 2.25 0 0 1 2.013-1.244h3.859M12 3v8.25m0 0-3-3m3 3 3-3" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div v-if="cars.data"
                class="flex flex-col items-center border-t bg-white px-5 py-5 xs:flex-row xs:justify-between">
                <pagination :links="cars.links" />
            </div>
        </div>
        <Modal :show="showModalCar">
            <div class="p-6">
                <h2 class="text-base font-medium leading-7 text-gray-900">
                    {{ form.id ? "Editar UM" : "Nueva UM" }}
                </h2>
                <form @submit.prevent="submit">
                    <div class="grid grid-cols-1 gap-x-8 gap-y-4 sm:grid-cols-2">
                        <div class="mt-2">
                            <InputLabel for="cost_line_id">Linea de Costo
                            </InputLabel>
                            <div class="mt-2">
                                <select id="zone" v-model="form.cost_line_id" autocomplete="off"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option value="">Seleccionar Linea de Costo</option>
                                    <option v-for="item in costLine" :value="item.id">{{ item.name }}</option>
                                </select>
                                <InputError :message="form.errors.cost_line_id" />
                            </div>
                        </div>
                        <div class="mt-2">
                            <InputLabel for="plate">Placa
                            </InputLabel>
                            <div class="mt-2">
                                <TextInput type="text" id="plate" v-model="form.plate" />
                                <InputError :message="form.errors.plate" />
                            </div>
                        </div>
                        <div class="mt-6">
                            <InputLabel for="model">Modelo
                            </InputLabel>
                            <div class="mt-2">
                                <TextInput type="text" id="model" v-model="form.model" />
                                <InputError :message="form.errors.model" />
                            </div>
                        </div>

                        <div class="mt-6">
                            <InputLabel for="brand">Marca
                            </InputLabel>
                            <div class="mt-2">
                                <TextInput type="text" id="brand" v-model="form.brand" />
                                <InputError :message="form.errors.brand" />
                            </div>
                        </div>
                        <div class="mt-6">
                            <InputLabel for="year">Año
                            </InputLabel>
                            <div class="mt-2">
                                <TextInput type="text" id="year" v-model="form.year" />
                                <InputError :message="form.errors.year" />
                            </div>
                        </div>
                        <div class="mt-6">
                            <InputLabel for="type">Tipo
                            </InputLabel>
                            <div class="mt-2">
                                <TextInput type="text" id="type" v-model="form.type" />
                                <InputError :message="form.errors.type" />
                            </div>
                        </div>
                        <div class="mt-6">
                            <InputLabel for="photo">Foto
                            </InputLabel>
                            <div class="mt-2">
                                <TextInput type="text" id="photo" v-model="form.photo" />
                                <InputError :message="form.errors.photo" />
                            </div>
                        </div>
                    </div>
                    <div class="mt-6 flex items-center justify-end gap-x-3">
                        <SecondaryButton @click="openModalCar"> Cancel </SecondaryButton>
                        <PrimaryButton type="submit" :class="{ 'opacity-25': form.processing }">
                            Guardar
                        </PrimaryButton>
                    </div>

                </form>
            </div>
        </Modal>
        <Modal :show="showModalCar">
            <div class="p-6">
                <h2 class="text-base font-medium leading-7 text-gray-900">
                    {{ form.id ? "Editar UM" : "Nueva UM" }}
                </h2>
                <form @submit.prevent="submit">
                    <div class="grid grid-cols-1 gap-x-8 gap-y-4 sm:grid-cols-2">
                        <div class="mt-2">
                            <InputLabel for="cost_line_id">Linea de Costo
                            </InputLabel>
                            <div class="mt-2">
                                <select id="zone" v-model="form.cost_line_id" autocomplete="off"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option value="">Seleccionar Linea de Costo</option>
                                    <option v-for="item in costLine" :value="item.id">{{ item.name }}</option>
                                </select>
                                <InputError :message="form.errors.cost_line_id" />
                            </div>
                        </div>
                        <div class="mt-2">
                            <InputLabel for="plate">Placa
                            </InputLabel>
                            <div class="mt-2">
                                <InputFile id="plate" v-model="form.plate" />
                                <InputError :message="form.errors.plate" />
                            </div>
                        </div>
                        <div class="mt-6">
                            <InputLabel for="model">Modelo
                            </InputLabel>
                            <div class="mt-2">
                                <InputFile id="model" v-model="form.model" />
                                <InputError :message="form.errors.model" />
                            </div>
                        </div>

                        <div class="mt-6">
                            <InputLabel for="brand">Marca
                            </InputLabel>
                            <div class="mt-2">
                                <InputFile id="brand" v-model="form.brand" />
                                <InputError :message="form.errors.brand" />
                            </div>
                        </div>
                    </div>
                    <div class="mt-6 flex items-center justify-end gap-x-3">
                        <SecondaryButton @click="openModalCar"> Cancel </SecondaryButton>
                        <PrimaryButton type="submit" :class="{ 'opacity-25': form.processing }">
                            Guardar
                        </PrimaryButton>
                    </div>

                </form>
            </div>
        </Modal>
        <ConfirmDeleteModal :confirmingDeletion="showModalDeleteCars" itemType="vehiculo" :deleteFunction="deleteCars"
            @closeModal="openModalDeleteCars(null)" />
        <!-- <ConfirmCreateModal :confirmingcreation="createSchedule" itemType="Horario" />
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
import { formattedDate, realNumeration, setAxiosErrors, toFormData } from '@/utils/utils';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { notify, notifyError } from '@/Components/Notification';
import TableHeaderCicsaFilter from '@/Components/TableHeaderCicsaFilter.vue';
import { Toaster } from 'vue-sonner';
import InputFile from '@/Components/InputFile.vue';

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
    costLine: Object,
    auth: Object
})

const cars = ref(props.car)
const showModalCar = ref(false)
const typeCreate = ref(null)
const showModalDeleteCars = ref(false)
const car_id = ref(null)

const hasPermission = (permission) => {
    return props.userPermissions.includes(permission);
}

const initialForm = {
    id: '',
    brand: '',
    model: '',
    plate: '',
    year: '',
    type: '',
    photo: '',
    user_id: props.auth.user.id,
    cost_line_id: '',
}

const form = useForm({
    ...initialForm
})

const cost_line = props.costLine.map(item => item.name)

const initialFormSearch = {
    cost_line: [...cost_line],
    search: ''
}

const formSearch = ref({ ...initialFormSearch })

function openModalCar() {
    showModalCar.value = !showModalCar.value
}

function openModalCreate() {
    openModalCar()
    form.defaults({ ...initialForm })
    form.reset()
}

function openModalEdit(item) {
    typeCreate.value = "edit"
    openModalCar()
    form.defaults({ ...item })
    form.reset()
}

function openModalDeleteCars(id) {
    car_id.value = id
    showModalDeleteCars.value = !showModalDeleteCars.value
}

async function deleteCars() {
    let url = route('fleet.cars.destroy', { car: car_id.value })
    try {
        await axios.delete(url)
        updateCar(car_id.value, "delete")
    } catch (error) {
        console.log(error)
    }
}
// function reentry() {
//     if (formSearch.value.state === 'Active') {
//         formSearch.value.state = 'Inactive'
//     } else {
//         formSearch.value.state = 'Active'
//     }
// }

watch(
    () => [
        formSearch.value.cost_line,
        formSearch.value.search,
    ],
    () => {
        search();
    },
    { deep: true }
);

async function submit() {
    let url = typeCreate.value === "edit" ? route('fleet.cars.update', { car: form.id }) : route('fleet.cars.store')
    let method = typeCreate.value === "edit" ? 'put' : 'post'
    try {
        let response = await axios({
            url: url,
            method: method,
            data: form
        });
        updateCar(response.data, typeCreate.value)
    } catch (error) {
        console.log(error)
        if (error.response) {
            if (error.response.data.errors) {
                setAxiosErrors(error.response.data.errors, form)
            } else {
                notifyError("Server error:", error.response.data)
            }
        } else {
            notifyError("Network or other error:", error)
        }
    }
}

function updateCar(data, action) {
    const validations = cars.value.data || cars.value
    if (action === "create") {
        validations.unshift(data)
        openModalCar()
        notify('Creaciòn Exitosa')
    } else if (action === "edit") {
        let index = validations.findIndex(item => item.id === data.id)
        validations[index] = data
        openModalCar()
        notify('Actualización Exitosa')
    } else if (action === "delete") {
        let index = validations.findIndex(item => item.id === data)
        validations.splice(index)
        openModalDeleteCars(null)
        notify('Eliminacion Exitosa')
    }
}
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

async function search() {
    console.log(formSearch.value)
    let url = route('fleet.cars.search')
    try {
        let response = await axios.post(url, formSearch.value)
        cars.value = response.data
        console.log(response.data)
    } catch (error) {
        console.log(error)
        if (error.response.data) {
            notifyError('Server error', error.response.data)
        } else {
            notifyError("Network or other error:", error)
        }
    }
}

</script>