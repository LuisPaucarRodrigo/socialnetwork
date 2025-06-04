<template>

    <Head title="Proyectos" />
    <AuthenticatedLayout :redirectRoute="'projectmanagement.index'">
        <template #header>
            Proyectos Culminados
        </template>
        <div class="min-w-full ">
            <div class="mt-6 flex items-center justify-end">
                <div>
                    <TextInput type="text" @input="search($event.target.value)" placeholder="Buscar..." />
                </div>
            </div>
            <br>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-4 gap-3 rounded-lg shadow">
                <div v-for="item in projects.data" :key="item.id"
                    class="bg-white p-3 rounded-md shadow-sm border border-gray-300 items-center">
                    <div class="grid grid-cols-2">
                        <h2 class="text-sm font-semibold mb-3">
                            N° {{ item.code }}
                        </h2>
                        <div v-if="auth.user.role_id === 1" class="inline-flex justify-end items-start gap-x-2">
                            <Link :href="route('projectmanagement.update', { project_id: item.id })">
                            <EditIcon />
                            </Link>
                            <!-- <button class="flex items-start" @click="confirmProjectDeletion(item.id)">
                                <TrashIcon class="h-4 w-4 text-red-500" />
                            </button> -->
                        </div>
                    </div>
                    <h3 class="text-sm font-semibold text-gray-700 line-clamp-1 mb-2">
                        {{ item.name }}
                    </h3>
                    <p v-if="item.initial_budget === 0.00" class="text-red-500 text-sm">
                        No se definió un presupuesto
                    </p>
                    <div
                        :class="`text-gray-500 text-sm ${item.initial_budget === 0.00 ? 'opacity-50 pointer-events-none' : ''}`">
                        <div class="grid grid-cols-1 gap-y-1">
                            <Link v-if="item.initial_budget > 0" :href="route('tasks.index', { id: item.id })"
                                class="text-blue-600 underline whitespace-no-wrap hover:text-purple-600">Tareas
                            </Link>
                            <Link v-if="item.initial_budget > 0"
                                :href="route('projectscalendar.show', { project: item.id })"
                                class="text-blue-600 underline whitespace-no-wrap hover:text-purple-600">Calendario
                            </Link>
                            <span v-else class="text-gray-400">Calendario</span>
                            <Link v-if="item.initial_budget > 0"
                                :href="route('projectmanagement.resources', { project_id: item.id })"
                                class="text-blue-600 underline whitespace-no-wrap hover:text-purple-600">Servicios
                            </Link>
                            <span v-else class="text-gray-400">Servicios</span>
                            <Link v-if="item.initial_budget > 0"
                                :href="route('projectmanagement.purchases_request.index', { project_id: item.id })"
                                class="text-blue-600 underline whitespace-no-wrap hover:text-purple-600">Compras y
                            Gastos</Link>
                            <span v-else class="text-gray-400">Compras y Gastos</span>

                            <Link v-if="item.initial_budget > 0"
                                :href="route('projectmanagement.products', { project_id: item.id })"
                                class="text-blue-600 underline whitespace-no-wrap hover:text-purple-600">
                            Asignar Productos
                            </Link>
                            <span v-else class="text-gray-400">Asignar Productos</span>


                            <Link v-if="item.initial_budget > 0"
                                :href="route('projectmanagement.liquidate', { project_id: item.id })"
                                class="text-blue-600 underline whitespace-no-wrap hover:text-purple-600">
                            Liquidaciones
                            </Link>
                            <span v-else class="text-gray-400">Liquidaciones</span>
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
import Pagination from '@/Components/Pagination.vue'
import axios from 'axios';
import { ref } from 'vue';
import { Head, router, Link } from '@inertiajs/vue3';
import TextInput from '@/Components/TextInput.vue';
import EditIcon from '@/Components/Icons/EditIcon.vue';

const props = defineProps({
    projects: Object,
    auth: Object
})


const projects = ref(props.projects);
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

const search = async ($search) => {
    try {
        const response = await axios.post(route('projectmanagement.historial'), { searchQuery: $search });
        projects.value = response.data.projects;
    } catch (error) {
        console.error('Error searching:', error);
    }
};

</script>