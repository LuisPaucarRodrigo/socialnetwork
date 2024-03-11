<template>

    <Head title="Proyectos" />
    <AuthenticatedLayout :redirectRoute="'preprojects.index'">
        <template #header>
            Anteproyectos
        </template>
        <div class="min-w-full p-3 rounded-lg shadow">
            <div class="flex gap-4">
                <Link :href="route('preprojects.create')"
                    class="inline-flex items-center px-4 py-2 border-2 border-gray-700 rounded-md font-semibold text-xs hover:text-gray-700 uppercase tracking-widest bg-gray-700 hover:underline hover:bg-gray-200 focus:border-indigo-600 focus:outline-none focus:ring-2 text-white">
                    + Agregar
                </Link>
            </div>
            <br>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-4 gap-3">
                <div v-for="item in preprojects.data" :key="item.id"
                    class="bg-white p-3 rounded-md shadow-sm border border-gray-300 items-center">
                    <div class="grid grid-cols-2">
                        <h2 class="text-sm font-semibold mb-3">
                            N° {{ item.code }}
                        </h2>
                        <div v-if="auth.user.role_id === 1" class="inline-flex justify-end gap-x-2">
                            <Link :href="route('preprojects.create', {preproject_id: item.id})"
                                class="text-green-600 hover:underline mb-4 flex items-start">
                                <PencilIcon class="h-4 w-4" />
                            </Link>
                            <button class="flex items-start" @click="confirmProjectDeletion(item.id)">
                                <TrashIcon class="h-4 w-4 text-red-500" />
                            </button>
                        </div>
                    </div>
                    <h3 class="text-sm font-semibold text-gray-700 line-clamp-1 mb-2">
                        {{ item.customer }}
                    </h3>
                    <div class="grid grid-cols-1 gap-y-1 text-sm">
                        <div>
                            <Link :href="route('preprojects.imagereport.index', { preproject_id: item.id })"
                                class="text-blue-600 underline whitespace-no-wrap hover:text-purple-600">Descargar
                            imagenes
                            </Link>
                        </div>
                        <div>
                            <Link :href="route('preprojects.photoreport.index', { preproject_id: item.id })"
                                class="text-blue-600 underline whitespace-no-wrap hover:text-purple-600">Informe
                            fotográfico
                            </Link>
                        </div>
                        <div>
                            <Link :href="route('preprojects.providersquotes.index', {
        preproject_id: item.id
    })" class="text-blue-600 underline whitespace-no-wrap hover:text-purple-600">Cotización de
                            proveedores
                            </Link>
                        </div>
                        <div v-if="item.has_photo_report">
                            <Link :href="route('preprojects.quote', { preproject_id: item.id })"
                                class="text-blue-600 underline whitespace-no-wrap hover:text-purple-600">Cotización para
                            proyecto
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
            <br>
            <div class="flex flex-col items-center border-t px-5 py-5 xs:flex-row xs:justify-between">
                <pagination :links="preprojects.links" />
            </div>
        </div>

    
        <ConfirmDeleteModal :confirmingDeletion="confirmingProjectDeletion" itemType="Anteproyecto"
            :deleteFunction="delete_project" @closeModal="closeModal" />
        <ConfirmCreateModal :confirmingcreation="showModal" itemType="Anteproyecto" />
        <ConfirmUpdateModal :confirmingupdate="showModalEdit" itemType="Anteproyecto" />
    </AuthenticatedLayout>
</template>
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ConfirmDeleteModal from '@/Components/ConfirmDeleteModal.vue';
import ConfirmCreateModal from '@/Components/ConfirmCreateModal.vue';
import ConfirmUpdateModal from '@/Components/ConfirmUpdateModal.vue';
import Pagination from '@/Components/Pagination.vue'
import { Head, router, Link } from '@inertiajs/vue3';
import { PencilIcon, TrashIcon } from '@heroicons/vue/24/outline';
import { ref } from 'vue';


const { preprojects, visits, auth } = defineProps({
    preprojects: Object,
    visits: Object,
    auth: Object
})


const confirmingProjectDeletion = ref(false);
const projectToDelete = ref('');
const showModal = ref(false);
const showModalEdit = ref(false);


const delete_project = () => {
    const projectId = projectToDelete.value;
    router.delete(route('preprojects.destroy', { preproject: projectId }), {
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