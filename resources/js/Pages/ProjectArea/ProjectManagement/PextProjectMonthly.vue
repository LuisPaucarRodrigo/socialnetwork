<template>

    <Head title="Proyectos" />
    <AuthenticatedLayout :redirectRoute="'projectmanagement.pext.index'">
        <template #header>
            Proyectos Mensuales Pext
        </template>
        <Toaster richColors />
        <div class="min-w-full">
            <div class="mt-6 flex items-center justify-between gap-x-6">
                <div class="hidden sm:flex sm:items-center sm:space-x-3">
                    <PrimaryButton data-tooltip-target="add_preproject" v-if="hasPermission('ProjectManager')"
                        @click="createOrEditModal()" type="button" customColor="bg-green-600 hover:bg-green-500">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                    </PrimaryButton>
                    <div id="add_preproject" role="tooltip"
                        class="absolute z-10 invisible inline-block px-2 py-1 text-xs font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                        + Agregar Proyecto
                        <div class="tooltip-arrow" data-popper-arrow></div>
                    </div>

                    <div id="add_monthly_project" role="tooltip"
                        class="absolute z-10 invisible inline-block px-2 py-1 text-xs font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                        + Agregar Proyecto Mensual
                        <div class="tooltip-arrow" data-popper-arrow></div>
                    </div>
                    <Link :href="route('projectmanagement.pext.additional.index', { type: 2 })"
                        class="bg-indigo-600 hover:bg-indigo-500 rounded-md px-4 py-2 text-center text-sm text-white">
                    P. Adicionales
                    </Link>
                </div>

                <div class="sm:hidden">
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
                                    <div v-if="hasPermission('ProjectManager')" class="dropdown-menu">
                                        <button @click="createOrEditModal()"
                                            class="dropdown-item block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-indigo-600 hover:text-white focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                                            Agregar Proyecto
                                        </button>
                                    </div>
                                    <a
                                        class="block w-full text-left px-4 py-2 text-sm text-black-700 hover:bg-indigo-600 hover:text-white focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                                        Exportar
                                    </a>
                                </div>
                            </div>
                        </template>
                    </dropdown>
                </div>
                <div class="flex space-x-4">
                    <TextInput data-tooltip-target="search_fields" type="text" @input="search($event.target.value)"
                        placeholder="Buscar..." />
                    <div id="search_fields" role="tooltip"
                        class="absolute z-10 invisible inline-block px-2 py-1 text-xs font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                        Nombre
                        <div class="tooltip-arrow" data-popper-arrow></div>
                    </div>
                </div>
            </div>
            <br>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-4 gap-3">
                <div v-for="item in projects.data || projects" :key="item.id"
                    class="bg-white p-3 rounded-md shadow-sm border border-gray-300 items-center">
                    <div class="grid grid-cols-2">
                        <h2 class="text-sm font-semibold mb-3">
                            N° {{ item.code }}
                        </h2>
                        <!-- <div v-if="auth.user.role_id === 1 || hasPermission('ProjectManager')"
                            class="inline-flex justify-end items-start gap-x-2">
                            <button @click="() => {
                                router.post(route('projectmanagement.liquidation'), { project_id: item.id }, {
                                    onSuccess: () => router.visit(route('projectmanagement.index'))
                                })
                            }" v-if="item.status === null"
                                :class="`h-6 px-1 rounded-md bg-indigo-700 text-white text-sm  ${item.is_liquidable ? '' : 'opacity-60'}`"
                                :disabled="item.is_liquidable ? false : true">
                                Liquidar
                            </button>
                            <Link :href="route('projectmanagement.update', { project_id: item.id })"
                                class="flex items-start">
                            <QueueListIcon class="h-6 w-6 text-teal-700" />
                            </Link>
                        </div> -->
                    </div>
                    <h3 class="text-sm font-semibold text-gray-700 line-clamp-3 mb-2">
                        {{ item.name }}
                    </h3>
                    <p v-if="item?.initial_budget === 0.00" class="text-red-500 text-sm">
                        No se definió un presupuesto
                    </p>
                    <div class="text-sm ">
                        <div class="grid grid-cols-1 gap-y-1">
                            <Link v-if="item?.initial_budget > 0"
                                :href="route('tasks.index', { id: item.id })"
                                class="text-blue-600 underline whitespace-no-wrap hover:text-purple-600">Tareas
                            </Link>
                            <span v-else class="text-gray-400">Tareas</span>
                            <Link v-if="item?.initial_budget > 0"
                                :href="route('projectscalendar.show', { project: item.id })"
                                class="text-blue-600 underline whitespace-no-wrap hover:text-purple-600">Calendario
                            </Link>
                            <span v-else class="text-gray-400">Calendario</span>
                            <!-- <Link v-if="item?.initial_budget > 0"
                                :href="route('projectmanagement.resources', { project_id: item.id })"
                                class="text-blue-600 underline whitespace-no-wrap hover:text-purple-600">Servicios
                            </Link>
                            <span v-else class="text-gray-400">Servicios</span> -->
                            <Link v-if="item?.initial_budget > 0"
                                :href="route('projectmanagement.pext.expenses.index', { project_id: item.id, fixedOrAdditional: false })"
                                class="text-blue-600 underline whitespace-no-wrap hover:text-purple-600">Compras y
                            Gastos</Link>
                            <span v-else class="text-gray-400">Compras y Gastos</span>

                            <!-- <Link v-if="item?.initial_budget > 0"
                                :href="route('projectmanagement.products', { project_id: item.id })"
                                class="text-blue-600 underline whitespace-no-wrap hover:text-purple-600">
                                Asignar Productos
                            </Link>
                            <span v-else class="text-gray-400">Asignar Productos</span> -->


                            <!-- <Link v-if="item?.initial_budget > 0"
                                :href="route('projectmanagement.liquidate', { project_id: item.id })"
                                class="text-blue-600 underline whitespace-no-wrap hover:text-purple-600">
                            Liquidaciones
                            </Link>
                            <span v-else class="text-gray-400">Liquidaciones</span> -->
                            <!-- <Link v-if="item?.initial_budget > 0"
                                :href="route('project.document.index', {path: `${item.code}_${item.id}`, project_id: item.id})"
                                class="text-blue-600 underline whitespace-no-wrap hover:text-purple-600">
                            Archivos
                            </Link>
                            <span v-else class="text-gray-400">Archivos</span> -->
                        </div>
                    </div>
                </div>
            </div>
            <br>
            <div v-if="projects.data"
                class="flex flex-col items-center px-5 py-5 xs:flex-row xs:justify-between">
                <pagination :links="projects.links" />
            </div>
        </div>

        <Modal :show="showModal">
            <div class="p-6">
                <h2 class="text-base font-medium leading-7 text-gray-900">
                    {{ form.id ? 'Actualizar Asignacion' : 'Crear ' }}
                </h2>
                <form @submit.prevent="submit">
                    <div class="space-y-12 mt-4">
                        <div class="grid sm:grid-cols-2 gap-6">
                            <div v-if="!form.id">
                                <InputLabel for="pre_project_id">Ante Proyectos</InputLabel>
                                <div class="mt-2">
                                    <select id="pre_project_id" v-model="form.pre_project_id" required
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                        <option value="">Seleccionar Ante Proyecto</option>
                                        <option v-for="item in projectsOrPreproject" :key="item.id" :value="item.id">
                                            {{ item.code }}
                                        </option>
                                    </select>
                                    <InputError :message="form.errors.pre_project_id" />
                                </div>
                            </div>
                            <div class="">
                                <InputLabel for="manager">Gestor</InputLabel>
                                <div class="mt-2">
                                    <input type="text" v-model="form.manager" autocomplete="off" id="manager"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                    <InputError :message="form.errors.manager" />
                                </div>
                            </div>
                            <div class="">
                                <InputLabel for="assignation_date">Fecha de Asignación</InputLabel>
                                <div class="mt-2">
                                    <input type="date" v-model="form.assignation_date" autocomplete="off"
                                        id="assignation_date"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                    <InputError :message="form.errors.assignation_date" />
                                </div>
                            </div>

                            <div class="">
                                <InputLabel for="project_name">Nombre del Proyecto</InputLabel>
                                <div class="mt-2">
                                    <input type="text" v-model="form_name" autocomplete="off" id="project_name"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                    <InputError :message="form.errors_name" />
                                </div>
                            </div>
                            <div class="">
                                <InputLabel for="customer">Cliente</InputLabel>
                                <div class="mt-2">
                                    <select id="customer" v-model="form.customer"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                        <option value="">Seleccionar Cliente</option>
                                        <option>CICSA</option>
                                        <option>STL</option>
                                    </select>
                                    <InputError :message="form.errors.customer" />
                                </div>
                            </div>
                            <div class="">
                                <InputLabel for="project_code">Codigo de Proyecto</InputLabel>
                                <div class="mt-2">
                                    <input type="text" v-model="form_code" autocomplete="off" id="project_code"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                    <InputError :message="form.errors_code" />
                                </div>
                            </div>
                            <div class="">
                                <InputLabel for="cpe">CPE</InputLabel>
                                <div class="mt-2">
                                    <input type="text" v-model="form.cpe" autocomplete="off" id="cpe"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                    <InputError :message="form.errors.cpe" />
                                </div>
                            </div>
                            <div class="">
                                <InputLabel for="zone">Zona</InputLabel>
                                <div class="mt-2">
                                    <select id="zone" v-model="form.zone" autocomplete="off"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                        <option value="">Seleccionar Zona</option>
                                        <option>Arequipa</option>
                                        <option>Moquegua</option>
                                        <option>Tacna</option>
                                        <option>Cuzco</option>
                                        <option>Puno</option>
                                        <option>MDD</option>
                                    </select>
                                    <InputError :message="form.errors.zone" />
                                </div>
                            </div>
                            <div class="">
                                <InputLabel for="zone2">Zona2 (Opcional)</InputLabel>
                                <div class="mt-2">
                                    <select id="zone2" v-model="form.zone2" autocomplete="off"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                        <option value="">Seleccionar Zona</option>
                                        <option>Arequipa</option>
                                        <option>Moquegua</option>
                                        <option>Tacna</option>
                                        <option>Cuzco</option>
                                        <option>Puno</option>
                                        <option>MDD</option>
                                    </select>
                                    <InputError :message="form.errors.zone2" />
                                </div>
                            </div>
                        </div>
                        <div class="mt-6 flex items-center justify-end gap-x-3">
                            <SecondaryButton @click="createOrEditModal()">
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
    </AuthenticatedLayout>
</template>
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ConfirmDeleteModal from '@/Components/ConfirmDeleteModal.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import Pagination from '@/Components/Pagination.vue'
import Dropdown from '@/Components/Dropdown.vue';
import axios from 'axios';
import { ref } from 'vue';
import { Head, router, Link, useForm } from '@inertiajs/vue3';
import { QueueListIcon, TrashIcon } from '@heroicons/vue/24/outline';
import TextInput from '@/Components/TextInput.vue';
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import { formattedDate, setAxiosErrors } from '@/utils/utils';
import { notifyError, notify } from '@/Components/Notification';
import { Toaster } from 'vue-sonner';

const { project, userPermissions, auth } = defineProps({
    project: Object,
    userPermissions: Array,
    auth: Object,
})

const initialState = {
    id: "",
    user_id: auth.user.id,
    assignation_date: '',
    project_name: '',
    customer: '',
    project_code: '',
    cpe: '',
    zone: '',
    zone2: '',
    manager: '',
    user_name: auth.user.name,
    pre_project_id: '',
}

const form = useForm({ ...initialState })

const formExport = ref({
    startDate: "",
    endDate: ""
})

const modalExport = ref(false)
const projectsOrPreproject = ref(null)

const hasPermission = (permission) => {
    return userPermissions.includes(permission);
}

const showModal = ref(false)
const projects = ref(project);
// const confirmingProjectDeletion = ref(false);
// const projectToDelete = ref('');

function editProject(pext) {
    console.log(pext)
    // Object.assign(form, pext);
    form.defaults({ ...pext })
    form.reset()
    createOrEditModal()
}

// const delete_project = () => {
//     const projectId = projectToDelete.value;
//     router.delete(route('projectmanagement.delete', { project_id: projectId }), {
//         onSuccess: () => {
//             createOrEditModal()
//             router.visit(route('projectmanagement.index'))
//         }
//     });
// }

// const confirmProjectDeletion = (employeeId) => {
//     projectToDelete.value = employeeId;
//     confirmingProjectDeletion.value = true;
// };

const createOrEditModal = () => {
    requestProjectOrPreproject()
    if (showModal.value) {
        form.defaults({ ...initialState })
        form.reset()
    }
    showModal.value = !showModal.value
};

const search = async (search) => {
    try {
        const response = await axios.post(route('projectmanagement.pext.index'), { searchQuery: search });
        projects.value = response.data;
    } catch (error) {
        console.error('Error searching:', error);
    }
};

async function requestProjectOrPreproject() {
    let url = route('projectmanagement.pext.requestProjectOrPreproject')
    try {
        let response = await axios.get(url)
        projectsOrPreproject.value = response.data
    } catch (error) {
        console.error('Error project or preproject:', error);
    }
}
// 
async function submit() {
    try {
        const url = form.id ?
            route('projectmanagement.pext.storeOrUpdate', { 'pext_id': form.id ?? null }) :
            route('projectmanagement.pext.storeProjectAndAssignation')
        const response = await axios.post(url, form)
        const action = form.id ? 'update' : 'create'
        updatePext(response.data, action)
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

function updatePext(pext, action) {
    const validations = projects.value.data || projects.value
    const index = action === "create" ? null : validations.findIndex(item => item.id === pext.id)
    if (action === "create") {
        validations.unshift(pext);
        createOrEditModal()
        notify('Creacion Exitosa')
    } if (action === "update") {
        validations[index] = pext
        createOrEditModal()
        notify('Actualización Exitosa')
    } if (action === "delete") {
        validations[index].splice(index, 1)
        notify('Eliminación Exitosa')
    }

    if (validations.length > projects.value.per_page) {
        validations.pop();
    }
}

// function modalExportExcel() {
//     modalExport.value = !modalExport.value
// }

// async function exportExcel() {
//     const uniqueParam = `timestamp=${new Date().getTime()}`;
//     let url =
//         route("projectmanagement.pext.export.expenses") +
//         `?start_date=${encodeURIComponent(formExport.startDate)}&end_date=${encodeURIComponent(formExport.endDate)}&${uniqueParam}`;
//     window.location.href = url;
//     modalExportExcel()
// }
</script>
