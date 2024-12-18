<template>

    <Head title="Proyectos" />
    <AuthenticatedLayout :redirectRoute="'projectmanagement.pext.index'">
        <template #header>
            Proyectos Adicionales
        </template>
        <Toaster richColors />
        <div class="min-w-full rounded-lg shadow">
            <div class="mt-6 flex items-center justify-between gap-x-6">
                <div class="hidden sm:flex sm:items-center sm:space-x-3">
                    <PrimaryButton data-tooltip-target="add_monthly_project" v-if="hasPermission('ProjectManager')"
                        @click="createOrEditModal" type="button" customColor="bg-green-600 hover:bg-green-500">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                    </PrimaryButton>
                    <div id="add_monthly_project" role="tooltip"
                        class="absolute z-10 invisible inline-block px-2 py-1 text-xs font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                        + Agregar Proyecto
                        <div class="tooltip-arrow" data-popper-arrow></div>
                    </div>
                    <!-- <PrimaryButton data-tooltip-target="export" type="button"
                        customColor="bg-green-600 hover:bg-green-500" @click="modalExportExcel">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M9.29289 1.29289C9.48043 1.10536 9.73478 1 10 1H18C19.6569 1 21 2.34315 21 4V9C21 9.55228 20.5523 10 20 10C19.4477 10 19 9.55228 19 9V4C19 3.44772 18.5523 3 18 3H11V8C11 8.55228 10.5523 9 10 9H5V20C5 20.5523 5.44772 21 6 21H7C7.55228 21 8 21.4477 8 22C8 22.5523 7.55228 23 7 23H6C4.34315 23 3 21.6569 3 20V8C3 7.73478 3.10536 7.48043 3.29289 7.29289L9.29289 1.29289ZM6.41421 7H9V4.41421L6.41421 7ZM19 12C19.5523 12 20 12.4477 20 13V19H23C23.5523 19 24 19.4477 24 20C24 20.5523 23.5523 21 23 21H19C18.4477 21 18 20.5523 18 20V13C18 12.4477 18.4477 12 19 12ZM11.8137 12.4188C11.4927 11.9693 10.8682 11.8653 10.4188 12.1863C9.96935 12.5073 9.86526 13.1318 10.1863 13.5812L12.2711 16.5L10.1863 19.4188C9.86526 19.8682 9.96935 20.4927 10.4188 20.8137C10.8682 21.1347 11.4927 21.0307 11.8137 20.5812L13.5 18.2205L15.1863 20.5812C15.5073 21.0307 16.1318 21.1347 16.5812 20.8137C17.0307 20.4927 17.1347 19.8682 16.8137 19.4188L14.7289 16.5L16.8137 13.5812C17.1347 13.1318 17.0307 12.5073 16.5812 12.1863C16.1318 11.8653 15.5073 11.9693 15.1863 12.4188L13.5 14.7795L11.8137 12.4188Z"
                                fill="#ffffff" />
                        </svg>
                    </PrimaryButton>
                    <div id="export" role="tooltip"
                        class="absolute z-10 invisible inline-block px-2 py-1 text-xs font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                        Exportar Excel
                        <div class="tooltip-arrow" data-popper-arrow></div>
                    </div> -->
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
                                        <button @click="createOrEditModal"
                                            class="dropdown-item block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-indigo-600 hover:text-white focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                                            Agregar
                                        </button>
                                    </div>
                                </div>
                                <div class="">
                                    <a
                                        class="block w-full text-left px-4 py-2 text-sm text-black-700 hover:bg-indigo-600 hover:text-white focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                                        Exportar
                                    </a>
                                </div>
                            </div>
                        </template>
                    </dropdown>
                </div>
                <div class="flex items-center mt-4 space-x-3 sm:mt-0">
                    <TextInput data-tooltip-target="search_fields" type="text" @input="search($event.target.value)"
                        placeholder="Buscar ..." />
                </div>
                <div id="search_fields" role="tooltip"
                    class="absolute z-10 invisible inline-block px-2 py-2 text-xs font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                    Nombre,Codigo,CPE
                    <div class="tooltip-arrow" data-popper-arrow></div>
                </div>
            </div>
            <br>
            <!-- <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-4 gap-3">
                <div v-for="item in projects.data || projects" :key="item.id"
                    class="bg-white p-3 rounded-md shadow-sm border border-gray-300 items-center">
                    <div class="grid grid-cols-2">
                        <p class="col-start-1 col-span-2 text-sm font-semibold mb-3">
                            Proyecto: {{ item.project_name }}
                        </p>
                        <div v-if="hasPermission('ProjectManager')" class="inline-flex justify-end items-start gap-x-2">
                            <button type="button" class="text-blue-900 whitespace-no-wrap" @click="editProject(item)">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-amber-400">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                </svg>
                            </button>
                        </div>
                    </div>
                    <p class="text-sm font-semibold text-gray-700 line-clamp-3 mb-2">
                        Fecha: {{ formattedDate(item.assignation_date) }}
                    </p>
                    <p class="text-sm font-semibold text-gray-700 line-clamp-3 mb-2">
                        Cliente: {{ item.customer }}
                    </p>
                    <p class="text-sm font-semibold text-gray-700 line-clamp-3 mb-2">
                        Centro de Costos: {{ item.project?.cost_center?.name }}
                    </p>
                    <p class="text-sm font-semibold text-gray-700 line-clamp-3 mb-2">
                        Codigo: {{ item.project_code }}
                    </p>
                    <p class="text-sm font-semibold text-gray-700 line-clamp-3 mb-2">
                        CPE: {{ item.cpe }}
                    </p>
                    <p class="text-sm font-semibold text-gray-700 line-clamp-3 mb-2">
                        Zonas: {{ item.zone }}
                    </p>
                    <div class="grid grid-cols-1 gap-y-1">
                        <Link :href="route('pext.additional.expense.index', { cicsa_assignation_id: item.id })"
                            class="text-blue-600 underline whitespace-no-wrap hover:text-purple-600">Gastos
                        </Link>
                    </div>
                </div>
            </div> -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-4 gap-3">
                <div v-for="item in projects.data || projects" :key="item.id"
                    class="bg-white p-3 rounded-md shadow-sm border border-gray-300 items-center">
                    <div class="grid grid-cols-2">
                        <p class="col-start-1 col-span-1 text-sm font-semibold mb-3">
                            Nombre: {{ item.project_name }}
                        </p>
                        <div v-if="hasPermission('ProjectManager')" class="inline-flex justify-end items-start gap-x-2">
                            <button type="button" class="text-blue-900 whitespace-no-wrap" @click="editProject(item)">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-amber-400">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                </svg>
                            </button>
                        </div>
                        <p class="col-start-1 col-span-2 text-sm font-semibold mb-3">
                            Fecha: {{ formattedDate(item.assignation_date) }}
                        </p>
                        <p class="col-start-1 col-span-2 text-sm font-semibold mb-3">
                            Cliente: {{ item.customer }}
                        </p>
                        <p class="col-start-1 col-span-2 text-sm font-semibold mb-3">
                            Centro de Costos: {{ item.project?.cost_center?.name }}
                        </p>
                        <p class="col-start-1 col-span-2 text-sm font-semibold mb-3">
                            Codigo: {{ item.project_code }}
                        </p>
                        <p class="col-start-1 col-span-2 text-sm font-semibold mb-3">
                            CPE: {{ item.cpe }}
                        </p>
                        <p class="col-start-1 col-span-2 text-sm font-semibold mb-3">
                            Zonas: {{ item.zone }}
                        </p>
                        <!-- <div v-if="auth.user.role_id === 1 || hasPermission('ProjectManager') " class="inline-flex justify-end items-start gap-x-2">
                            <button
                                @click="()=>{router.post(route('projectmanagement.liquidation'),{project_id: item.id}, {
                                    onSuccess: () => router.visit(route('projectmanagement.index'))
                                })}"
                                v-if="item.status === null"
                                :class="`h-6 px-1 rounded-md bg-indigo-700 text-white text-sm  ${item.is_liquidable ? '': 'opacity-60'}`"
                                :disabled="item.is_liquidable ? false: true"
                            >
                                Liquidar
                            </button>
                            <Link :href="route('projectmanagement.update', { project_id: item.id })"
                                class="flex items-start">
                            <QueueListIcon class="h-6 w-6 text-teal-700" />
                            </Link>
                        </div> -->
                    </div>
                    <!-- <h3 class="text-sm font-semibold text-gray-700 line-clamp-3 mb-2">
                        {{ item.project_name }}
                    </h3> -->
                    <!-- :class="`text-gray-500 text-sm ${item?.initial_budget === 0.00 ? 'opacity-50 pointer-events-none' : ''}`" -->
                    <div>
                        <div class="grid grid-cols-1 gap-y-1">
                            <!-- <Link :href="route('tasks.index', { id: item.project.id })"
                                class="text-blue-600 underline whitespace-no-wrap hover:text-purple-600">Tareas
                            </Link>
                            <Link :href="route('projectscalendar.show', { project: item.project.id })"
                                class="text-blue-600 underline whitespace-no-wrap hover:text-purple-600">Calendario
                            </Link> -->

                            <!-- <Link v-if="item?.initial_budget > 0"
                                :href="route('projectmanagement.resources', { project_id: item.id })"
                                class="text-blue-600 underline whitespace-no-wrap hover:text-purple-600">Servicios
                            </Link>
                            <span v-else class="text-gray-400">Servicios</span> -->
                            <Link
                                :href="route('pext.additional.expense.index', { project_id: item.id, fixedOrAdditional: false })"
                                class="text-blue-600 underline whitespace-no-wrap hover:text-purple-600">Compras y
                            Gastos</Link>

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
                class="flex flex-col items-center border-t px-5 py-5 xs:flex-row xs:justify-between">
                <pagination :links="projects.links" />
            </div>
        </div>

        <Modal :show="showModal">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-800 border-b-2 border-gray-100">
                    {{ form.id ? 'Editar Asignación' : 'Nueva Asignación' }}
                </h2>
                <br>
                <form @submit.prevent="submit">
                    <div class="grid grid-cols-1 gap-x-8 gap-y-4 sm:grid-cols-2">
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
                                <input type="text" v-model="form.project_name" autocomplete="off" id="project_name"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.project_name" />
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
                            <InputLabel for="cost_center">Centro de Costos</InputLabel>
                            <div class="mt-2">
                                <select id="cost_center" v-model="form.cost_center_id"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option value="">Seleccionar Centro de Costo</option>
                                    <option v-for="item in cost_line.cost_center" :key="item.id" :value="item.id">{{
                                        item.name
                                        }}
                                    </option>
                                </select>
                                <InputError :message="form.errors.cost_center_id" />
                            </div>
                        </div>
                        <div class="">
                            <InputLabel for="project_code">Codigo de Proyecto</InputLabel>
                            <div class="mt-2">
                                <input type="text" v-model="form.project_code" autocomplete="off" id="project_code"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.project_code" />
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
                    <br>
                    <div class="mt-6 flex justify-end">
                        <SecondaryButton type="button" @click="createOrEditModal"> Cancelar </SecondaryButton>
                        <PrimaryButton class="ml-3 tracking-widest uppercase text-xs"
                            :class="{ 'opacity-25': form.processing }" :disabled="form.processing" type="submit">
                            Guardar
                        </PrimaryButton>
                    </div>
                </form>
            </div>
        </Modal>
        <!-- <Modal :show="modalExport">
            <div class="p-6">
                <h2 class="text-base font-medium leading-7 text-gray-900">
                    Exportar Excel
                </h2>
                <form @submit.prevent="exportExcel">
                    <div class="space-y-12 mt-4">
                        <div class="grid sm:grid-cols-2 gap-6 pb-6">
                            <div>
                                <InputLabel for="startDate" class="font-medium leading-6 text-gray-900">
                                    Fecha de Inicio
                                </InputLabel>
                                <div class="mt-2">
                                    <TextInput type="date" v-model="formExport.startDate" id="startDate" required />
                                </div>
                            </div>
                            <div>
                                <InputLabel for="endDate" class="font-medium leading-6 text-gray-900">
                                    Fecha de Fin
                                </InputLabel>
                                <div class="mt-2">
                                    <TextInput type="date" v-model="formExport.endDate" id="endDate" required
                                        :min="formExport.startDate" />
                                </div>
                            </div>
                        </div>
                        <div class="mt-6 flex items-center justify-end gap-x-3">
                            <SecondaryButton @click="modalExportExcel">
                                Cancelar
                            </SecondaryButton>
                            <PrimaryButton type="submit" :class="{ 'opacity-25': formExport.processing }"
                                :disabled="formExport.processing">
                                Exportar
                            </PrimaryButton>
                        </div>
                    </div>
                </form>
            </div>
        </Modal> -->
    </AuthenticatedLayout>
</template>
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Pagination from '@/Components/Pagination.vue'
import Dropdown from '@/Components/Dropdown.vue';
import axios from 'axios';
import { ref } from 'vue';
import { Head, router, Link, useForm } from '@inertiajs/vue3';
import TextInput from '@/Components/TextInput.vue';
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import { formattedDate, setAxiosErrors } from '@/utils/utils';
import { notifyError, notify } from '@/Components/Notification';
import { Toaster } from 'vue-sonner';

const { project, auth, userPermissions, cost_line } = defineProps({
    project: Object,
    userPermissions: Array,
    auth: Object,
    cost_line: Object
})

const initialState = {
    id: null,
    user_id: auth.user.id,
    assignation_date: '',
    project_name: '',
    cost_line_id: cost_line.id,
    cost_center_id: '',
    customer: '',
    project_code: '',
    cpe: '',
    zone: '',
    zone2: '',
    manager: '',
    user_name: auth.user.name,
}

const form = useForm({ ...initialState })
// const formExport = ref({
//     startDate: "",
//     endDate: ""
// })
// const modalExport = ref(false)

const hasPermission = (permission) => {
    return userPermissions.includes(permission);
}

const showModal = ref(false)
const projects = ref(project);
// const confirmingProjectDeletion = ref(false);
// const projectToDelete = ref('');

function editProject(pext) {
    form.defaults({ ...pext, cost_center_id: pext.project.cost_center.id })
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
    if (showModal.value) {
        form.defaults({ ...initialState })
        form.reset()
    }
    showModal.value = !showModal.value

};

const search = async ($search) => {
    try {
        const response = await axios.post(route('projectmanagement.pext.additional.index'), { searchQuery: $search });
        projects.value = response.data;
    } catch (error) {
        notifyError('Error searching:', error);
    }
};

async function submit() {
    let url = route('projectmanagement.pext.additional.store', { 'project_id': form.id ?? null })
    try {
        let response = await axios.post(url, form)
        let action = form.id ? 'update' : 'create'
        updatePext(response.data, action)
    } catch (error) {
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
