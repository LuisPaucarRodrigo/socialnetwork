<template>

    <Head title="Proyectos" />
    <AuthenticatedLayout :redirectRoute="'projectmanagement.pext.index'">
        <template #header>
            Proyectos Mensuales Pext
        </template>
        <Toaster richColors />
        <div class="min-w-full">
            <TableHeader :projects="projects" :userPermissions="userPermissions" :createOrEditModal="createOrEditModal"
                :search="search" />
            <br>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-4 gap-3">
                <ProjectCard v-for="item in projects.data || projects" :key="item.id" :item="item"
                    :user-permissions="userPermissions" :auth="auth" />
            </div>
            <br>
            <div v-if="projects.data" class="flex flex-col items-center px-5 py-5 xs:flex-row xs:justify-between">
                <pagination :links="projects.links" />
            </div>
        </div>
        <FormProject :auth="auth" :projects="projects" ref="formProject" />
    </AuthenticatedLayout>
</template>
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Pagination from '@/Components/Pagination.vue'
import axios from 'axios';
import { ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import { Toaster } from 'vue-sonner';
import ProjectCard from './components/ProjectCard.vue';
import FormProject from './components/FormProject.vue';
import TableHeader from './components/TableHeader.vue';

const { project, userPermissions, auth } = defineProps({
    project: Object,
    userPermissions: Array,
    auth: Object,
})

const projects = ref(project);
const formProject = ref(null)

function createOrEditModal() {
    formProject.value.createOrEditModal()
}
const search = async (search) => {
    try {
        const response = await axios.post(route('projectmanagement.pext.index'), { searchQuery: search });
        projects.value = response.data;
    } catch (error) {
        console.error('Error searching:', error);
    }
};
</script>
