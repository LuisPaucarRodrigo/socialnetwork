<template>

    <Head title="Proyectos Pext" />
    <AuthenticatedLayout :redirectRoute="'projectmanagement.index'">
        <template #header>
            Proyectos Pext
        </template>
        <div class="min-w-full rounded-lg shadow">
            <div class="mt-6 flex items-center justify-between gap-x-6">
                <div class="hidden sm:flex sm:items-center sm:space-x-4">
                    <button v-if="hasPermission('ProjectManager')" @click="add_project" type="button"
                        class="whitespace-nowrap inline-flex items-center px-4 py-2 border-2 border-gray-700 rounded-md font-semibold text-xs hover:text-gray-700 uppercase tracking-widest bg-gray-700 hover:underline hover:bg-gray-200 focus:border-indigo-600 focus:outline-none focus:ring-2 text-white">
                        + Agregar
                    </button>
                    <Link :href="route('projectmanagement.pext.additional.index', {type:2})"
                        class="bg-indigo-600 hover:bg-indigo-500 rounded-md px-4 py-2 text-center text-sm text-white">
                    P. Adicionales
                    </Link>
                    <!-- <Link :href="route('projectscalendar.index')"
                        class="inline-flex items-center px-4 py-2 border-2 border-gray-700 rounded-md font-semibold text-xs hover:text-gray-700 uppercase tracking-widest bg-gray-700 hover:underline hover:bg-gray-200 focus:border-indigo-600 focus:outline-none focus:ring-2 text-white">
                    Calendario
                    </Link> -->
                    <!-- <button @click="()=>router.visit(route('projectmanagement.historial'))" type="button"
                        class="inline-flex items-center px-4 py-2 border-2 border-gray-700 rounded-md font-semibold text-xs hover:text-gray-700 uppercase tracking-widest bg-gray-700 hover:underline hover:bg-gray-200 focus:border-indigo-600 focus:outline-none focus:ring-2 text-white">
                        Historial
                    </button> -->
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
                            <div> <!-- Alineación a la derecha -->
                                <div class="dropdown">
                                    <div v-if="hasPermission('ProjectManager')" class="dropdown-menu">
                                        <button @click="add_project"
                                            class="dropdown-item block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-indigo-600 hover:text-white focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                                            Agregar
                                        </button>
                                    </div>
                                </div>
                                <!-- <dropdown-link :href="route('projectscalendar.index')">
                                    Calendario
                                </dropdown-link>
                                <div class="dropdown">
                                    <div v-if="hasPermission('ProjectManager')" class="dropdown-menu">
                                        <button @click="()=>router.visit(route('projectmanagement.historial'))"
                                            class="dropdown-item block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-indigo-600 hover:text-white focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                                            Historial
                                        </button>
                                    </div>
                                </div> -->
                            </div>
                        </template>
                    </dropdown>
                </div>

                <input type="text" @input="search($event.target.value)" placeholder="Buscar...">
            </div>
            <br>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-4 gap-3">
                <div v-for="item in projects.data" :key="item.id"
                    class="bg-white p-3 rounded-md shadow-sm border border-gray-300 items-center">
                    <div class="grid grid-cols-2">
                        <h2 class="text-sm font-semibold mb-3">
                            N° Codigo
                            <!-- {{ item.code }} -->
                        </h2>
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
                    <h3 class="text-sm font-semibold text-gray-700 line-clamp-3 mb-2">
                        <!-- {{ item.name }} -->
                          Nombre de Prueba
                    </h3>
                    <p v-if="item?.initial_budget === 0.00" class="text-red-500 text-sm">
                        No se definió un presupuesto
                    </p>
                    <!-- :class="`text-gray-500 text-sm ${item?.initial_budget === 0.00 ? 'opacity-50 pointer-events-none' : ''}`" -->
                    <div
                        
                        >
                        <div class="grid grid-cols-1 gap-y-1">
                            <Link v-if="item?.initial_budget > 0"
                                :href="route('tasks.index', { id: item.id })"
                                class="text-blue-600 underline whitespace-no-wrap hover:text-purple-600">Tareas
                            </Link>
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
                                :href="route('projectmanagement.purchases_request.index', { project_id: item.id })"
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
            <div class="flex flex-col items-center border-t px-5 py-5 xs:flex-row xs:justify-between">
                <pagination :links="projects.links" />
            </div>
        </div>
        <ConfirmDeleteModal :confirmingDeletion="confirmingProjectDeletion" itemType="proyecto"
            :deleteFunction="delete_project" @closeModal="closeModal" />
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
import { Head, router, Link } from '@inertiajs/vue3';
import { QueueListIcon, TrashIcon } from '@heroicons/vue/24/outline';

const props = defineProps({
    projects: Object,
    auth: Object,
    userPermissions:Array
})

const hasPermission = (permission) => {
    return props.userPermissions.includes(permission);
}

const projects = ref(props.projects);
const confirmingProjectDeletion = ref(false);
const projectToDelete = ref('');

const add_project = () => {
    router.get(route('projectmanagement.create'));
}

const delete_project = () => {
    const projectId = projectToDelete.value;
    router.delete(route('projectmanagement.delete', { project_id: projectId }), {
        onSuccess: () => {closeModal()
        router.visit(route('projectmanagement.index'))}
    });
}

const confirmProjectDeletion = (employeeId) => {
    projectToDelete.value = employeeId;
    confirmingProjectDeletion.value = true;
};

const closeModal = () => {
    confirmingProjectDeletion.value = false;
};

const search = async ($search) => {
    try {
        const response = await axios.post(route('projectmanagement.index'), { searchQuery: $search });
        projects.value = response.data.projects;
    } catch (error) {
        console.error('Error searching:', error);
    }
};

</script>
