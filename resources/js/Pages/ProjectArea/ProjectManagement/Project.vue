<template>
    <Head title="Proyectos" />
    <AuthenticatedLayout>
        <template #header>
            Gestión de Proyectos
        </template>
        <div class="min-w-full p-3 rounded-lg shadow">
            <div class="flex gap-4">
                <button @click="add_project" type="button"
                    class="inline-flex items-center px-4 py-2 border-2 border-gray-700 rounded-md font-semibold text-xs hover:text-gray-700 uppercase tracking-widest bg-gray-700 hover:underline hover:bg-gray-200 focus:border-indigo-600 focus:outline-none focus:ring-2 text-white">
                    + Agregar
                </button>
                <Link :href="route('projectscalendar.index')"
                    class="inline-flex items-center px-4 py-2 border-2 border-gray-700 rounded-md font-semibold text-xs hover:text-gray-700 uppercase tracking-widest bg-gray-700 hover:underline hover:bg-gray-200 focus:border-indigo-600 focus:outline-none focus:ring-2 text-white">
                Calendario
                </Link>
            </div>
            <br>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-4 gap-3">
                <div v-for="item in projects.data" :key="item.id"
                    class="bg-white p-3 rounded-md shadow-sm border border-gray-300 items-center">
                    <div class="grid grid-cols-2">
                        <h2 class="text-sm font-semibold mb-3">
                            N° {{ item.code }}
                        </h2>
                        <div class="inline-flex justify-end gap-x-2">
                            <Link :href="route('projectmanagement.update', { project_id: item.id })"
                                class="flex items-start">
                            <PencilIcon class="h-4 w-4 text-teal-600" />
                            </Link>
                            <button class="flex items-start" @click="confirmProjectDeletion(item.id)">
                                <TrashIcon class="h-4 w-4 text-red-500" />
                            </button>
                        </div>
                    </div>
                    <h3 class="text-sm font-semibold text-gray-700 line-clamp-1 mb-2">
                        {{ item.name }}
                    </h3>
                    <div class="text-gray-500 text-sm">
                        <div class="grid grid-cols-1 gap-y-1">
                            <Link v-if="item.initial_budget" :href="route('projectscalendar.show', { project: item.id })"
                                class="text-blue-600 underline whitespace-no-wrap hover:text-purple-600">Calendario</Link>
                            <Link v-if="item.initial_budget"
                                :href="route('projectmanagement.resources', { project_id: item.id })"
                                class="text-blue-600 underline whitespace-no-wrap hover:text-purple-600">Asignar Activos
                            </Link>
                            <Link v-if="item.initial_budget"
                                :href="route('projectmanagement.purchases_request.index', { project_id: item.id })"
                                class="text-blue-600 underline whitespace-no-wrap hover:text-purple-600">Compras y
                            Gastos</Link>
                            <Link v-if="item.initial_budget"
                                :href="route('projectmanagement.products', { project_id: item.id })"
                                class="text-blue-600 underline whitespace-no-wrap hover:text-purple-600">Asignar Productos
                            </Link>
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
import { Head, router, Link } from '@inertiajs/vue3';
import { PencilIcon, TrashIcon } from '@heroicons/vue/24/outline';
import { ref } from 'vue';

const { projects } = defineProps({
    projects: Object,
})

const confirmingProjectDeletion = ref(false);
const projectToDelete = ref('');

const add_project = () => {
    router.get(route('projectmanagement.create'));
}

const delete_project = () => {
    const projectId = projectToDelete.value;
    router.delete(route('projectmanagement.delete', { project_id: projectId }), {
        onSuccess: () => closeModal()
    });
}

const confirmProjectDeletion = (employeeId) => {
    projectToDelete.value = employeeId;
    confirmingProjectDeletion.value = true;
};

const closeModal = () => {
    confirmingProjectDeletion.value = false;
};
</script>