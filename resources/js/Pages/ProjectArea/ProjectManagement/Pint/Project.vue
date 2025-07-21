<template>

    <Head title="Proyectos Pint" />
    <AuthenticatedLayout :redirectRoute="'projectmanagement.index'">
        <template #header>
            Proyectos Pint
        </template>
        <div class="min-w-full">
            <div class="mt-6 flex items-center justify-between gap-x-6">
                <div class="hidden sm:flex sm:items-center sm:space-x-4">
                    <button v-permission="'add_pro_pint'" @click="add_project" type="button"
                        class="whitespace-nowrap inline-flex items-center px-4 py-2 border-2 border-gray-700 rounded-md font-semibold text-xs hover:text-gray-700 uppercase tracking-widest bg-gray-700 hover:underline hover:bg-gray-200 focus:border-indigo-600 focus:outline-none focus:ring-2 text-white">
                        + Agregar
                    </button>
                    <Link v-permission="'pro_pint_calendars'" :href="route('projectscalendar.index')"
                        class="inline-flex items-center px-4 py-2 border-2 border-gray-700 rounded-md font-semibold text-xs hover:text-gray-700 uppercase tracking-widest bg-gray-700 hover:underline hover:bg-gray-200 focus:border-indigo-600 focus:outline-none focus:ring-2 text-white">
                    Calendario
                    </Link>
                    <Link v-permission="'pro_pint_historial'" :href="route('projectmanagement.historial')"
                        class="inline-flex items-center px-4 py-2 border-2 border-gray-700 rounded-md font-semibold text-xs hover:text-gray-700 uppercase tracking-widest bg-gray-700 hover:underline hover:bg-gray-200 focus:border-indigo-600 focus:outline-none focus:ring-2 text-white">
                    Historial
                    </Link>
                    <Link v-permission="'pro_pint_additional_management'"
                        :href="route('projectmanagement.pext.additional.index', { type: 1 })"
                        class="bg-indigo-600 hover:bg-indigo-500 rounded-md px-4 py-2 text-center text-sm text-white">
                    P. Adicionales
                    </Link>
                </div>

                <div v-permission-or="['add_pro_pint', 'pro_pint_calendars', 'pro_pint_historial', 'pro_pint_additional_management']"
                    class="sm:hidden">
                    <dropdown align='left'>
                        <template #trigger>
                            <button @click="dropdownOpen = !dropdownOpen"
                                class="relative block overflow-hidden rounded-md bg-gray-200 px-2 py-2 text-center text-sm text-white hover:bg-gray-100">
                                <MenuIcon />
                            </button>
                        </template>

                        <template #content class="origin-left">
                            <div>
                                <div v-permission="'add_pro_pint'" class="dropdown">
                                    <div class="dropdown-menu">
                                        <button @click="add_project"
                                            class="dropdown-item block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-indigo-600 hover:text-white focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                                            Agregar
                                        </button>
                                    </div>
                                </div>
                                <dropdown-link v-permission="'pro_pint_calendars'"
                                    :href="route('projectscalendar.index')">
                                    Calendario
                                </dropdown-link>
                                <dropdown-link v-permission="'pro_pint_historial'"
                                 :href="route('projectmanagement.historial')">
                                    Historial
                                </dropdown-link>
                                <dropdown-link v-permission="'pro_pint_additional_management'"
                                    :href="route('projectmanagement.pext.additional.index', { type: 1 })">
                                    P. Adicionales
                                </dropdown-link>
                            </div>
                        </template>
                    </dropdown>
                </div>
                <Search fields="Descripción" :searchFunction="search" v-model:search="searchForm.searchQuery" />
            </div>
            <br>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-4 gap-3">
                <div v-for="item in projects.data || projects" :key="item.id"
                    class="bg-white p-3 rounded-md shadow-sm border border-gray-300 items-center">
                    <div class="grid grid-cols-2">
                        <h2 class="text-sm font-semibold mb-3">
                            N° {{ item.code }}
                        </h2>
                        <div v-if="auth.user.role_id === 1"
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
                            <Link v-permission="'edit_pro_pint'"
                                :href="route('projectmanagement.update', { project_id: item.id })"
                                class="flex items-start">
                            <EditIcon />
                            </Link>
                        </div>
                    </div>
                    <h3 class="text-sm font-semibold text-gray-700 line-clamp-3 mb-2">
                        {{ item.name }}
                    </h3>
                    <p v-if="item.cost_center_id === 1 && item.initial_budget === 0.00" class="text-red-500 text-sm">
                        No se definió un presupuesto
                    </p>
                    <div
                        :class="`text-gray-500 text-sm ${item.cost_center_id === 1 && item.initial_budget === 0.00 ? 'opacity-50 pointer-events-none' : ''}`">
                        <div class="grid grid-cols-1 gap-y-1">
                            <!-- <Link v-permission="'pro_pint_tasks'"
                                v-if="item.cost_center_id !== 1 || item.initial_budget > 0"
                                :href="route('tasks.index', { id: item.id })"
                                class="text-blue-600 underline whitespace-no-wrap hover:text-purple-600">Tareas
                            </Link> -->
                            <!-- <div v-permission="'pro_pint_one_calendar'">
                                <Link v-if="item.cost_center_id !== 1 || item.initial_budget > 0"
                                    :href="route('projectscalendar.show', { project: item.id })"
                                    class="text-blue-600 underline whitespace-no-wrap hover:text-purple-600">Calendario
                                </Link>
                                <span v-else class="text-gray-400">Calendario</span>
                            </div> -->
                            <!-- <div v-permission="'pro_pint_services'">
                                <Link v-if="item.cost_center_id !== 1 || item.initial_budget > 0"
                                    :href="route('projectmanagement.resources', { project_id: item.id })"
                                    class="text-blue-600 underline whitespace-no-wrap hover:text-purple-600">Servicios
                                </Link>
                                <span v-else class="text-gray-400">Servicios</span>
                            </div> -->

                            <div v-permission="'pro_pint_one_purchase_expenses'">
                                <Link v-if="item.cost_center_id !== 1 || item.initial_budget > 0"
                                    :href="route('projectmanagement.purchases_request.index', { project_id: item.id })"
                                    class="text-blue-600 underline whitespace-no-wrap hover:text-purple-600">Compras y
                                Gastos</Link>
                                <span v-else class="text-gray-400">Compras y Gastos</span>
                            </div>
                            <!-- <div v-permission="'pro_pint_assign_products'">
                                <Link v-if="item.cost_center_id !== 1 || item.initial_budget > 0"
                                    :href="route('projectmanagement.products', { project_id: item.id })"
                                    class="text-blue-600 underline whitespace-no-wrap hover:text-purple-600">
                                Asignar Productos
                                </Link>
                                <span v-else class="text-gray-400">Asignar Productos</span>
                            </div>
                            <div v-permission="'pro_pint_liquidation'">
                                <Link v-if="item.cost_center_id !== 1 || item.initial_budget > 0"
                                    :href="route('projectmanagement.liquidate', { project_id: item.id })"
                                    class="text-blue-600 underline whitespace-no-wrap hover:text-purple-600">
                                Liquidaciones
                                </Link>
                                <span v-else class="text-gray-400">Liquidaciones</span>
                            </div> -->
                            <div v-permission="'pro_pint_archives'">
                                <Link v-if="item.cost_center_id !== 1 || item.initial_budget > 0"
                                    :href="route('project.document.index', { path: `${item.code}_${item.id}`, project_id: item.id })"
                                    class="text-blue-600 underline whitespace-no-wrap hover:text-purple-600">
                                Archivos
                                </Link>
                                <span v-else class="text-gray-400">Archivos</span>
                            </div>
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
import { ref } from 'vue';
import { Head, router, Link, useForm } from '@inertiajs/vue3';
import Search from '@/Components/Search.vue';
import { EditIcon, MenuIcon } from '@/Components/Icons';

const props = defineProps({
    projects: Object,
    auth: Object
})


const projects = ref({ ...props.projects });
const confirmingProjectDeletion = ref(false);
const projectToDelete = ref('');

const add_project = () => {
    router.get(route('projectmanagement.create'));
}

const delete_project = () => {
    const projectId = projectToDelete.value;
    router.delete(route('projectmanagement.delete', { project_id: projectId }), {
        onSuccess: () => {
            closeModal()
            router.visit(route('projectmanagement.index'))
        }
    });
}

const confirmProjectDeletion = (employeeId) => {
    projectToDelete.value = employeeId;
    confirmingProjectDeletion.value = true;
};

const closeModal = () => {
    confirmingProjectDeletion.value = false;
};

const searchForm = useForm({
    searchQuery: ''
})

async function search() {
    try {
        const response = await axios.post(route('projectmanagement.index'), { searchQuery: searchForm.searchQuery });
        projects.value = response.data.projects;
    } catch (error) {
        console.error('Error searching:', error);
    }
};

</script>
