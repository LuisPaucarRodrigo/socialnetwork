<template>

    <Head title="Proyectos" />
    <AuthenticatedLayout :redirectRoute="'huawei.projects'">
        <template #header>
            Proyectos Huawei
        </template>
        <div class="min-w-full rounded-lg shadow">
            <div class="mt-6 flex items-center justify-between gap-x-6">
                <div class="sm:flex sm:items-center sm:space-x-4">
                    <Link :href="route('huawei.projects.create')" type="button"
                        class="inline-flex items-center px-4 py-2 border-2 border-gray-700 rounded-md font-semibold text-xs hover:text-gray-700 uppercase tracking-widest bg-gray-700 hover:underline hover:bg-gray-200 focus:border-indigo-600 focus:outline-none focus:ring-2 text-white">
                        + Agregar
                    </Link>
                </div>
            </div>
            <br>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-4 gap-3">
                <div v-for="item in projects.data" :key="item.id"
                    class="bg-white p-3 rounded-md shadow-sm border border-gray-300 items-center">
                    <div class="grid grid-cols-2">
                        <h2 class="text-sm font-semibold mb-3">
                            N° {{ item.code }}
                        </h2>
                        <div v-if="auth.user.role_id === 1 || hasPermission('ProjectManager') " class="inline-flex justify-end items-start gap-x-2">
                            <Link :href="route('huawei.projects.toupdate', {huawei_project: item.id})"
                                class="flex items-start">
                            <PencilIcon class="h-4 w-4 text-teal-600" />
                            </Link>
                        </div>
                    </div>
                    <h3 class="text-sm font-semibold text-gray-700 line-clamp-1 mb-2">
                        {{ item.name }}
                    </h3>
                    <div
                        :class="`text-gray-500 text-sm ${item.initial_budget === 0.00 ? 'opacity-50 pointer-events-none' : ''}`">
                        <div class="grid grid-cols-1 gap-y-1">
                            <Link
                                :href="route('projectmanagement.purchases_request.index', { project_id: item.id })"
                                class="text-blue-600 underline whitespace-no-wrap hover:text-purple-600">
                                Gastos
                            </Link>
                            <Link
                                :href="route('projectmanagement.products', { project_id: item.id })"
                                class="text-blue-600 underline whitespace-no-wrap hover:text-purple-600">
                                Asignar Productos
                            </Link>

                            <Link
                                :href="route('projectmanagement.liquidate', { project_id: item.id })"
                                class="text-blue-600 underline whitespace-no-wrap hover:text-purple-600">
                                Liquidaciones
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
            <br>
            <div class="flex flex-col items-center border-t px-5 py-5 xs:flex-row xs:justify-between">
                <pagination :links="props.projects.links" />
            </div>
        </div>

    </AuthenticatedLayout>
</template>
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import Pagination from '@/Components/Pagination.vue'
import Dropdown from '@/Components/Dropdown.vue';
import axios from 'axios';
import { ref } from 'vue';
import { Head, router, Link, useForm } from '@inertiajs/vue3';
import { PencilIcon, TrashIcon } from '@heroicons/vue/24/outline';
import Modal from '@/Components/Modal.vue';
import SuccessOperationModal from '@/Components/SuccessOperationModal.vue';
import InputError from '@/Components/InputError.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';

const props = defineProps({
    projects: Object,
    auth: Object,
    huawei_sites: Object,
    userPermissions:Array
})


const hasPermission = (permission) => {
    return props.userPermissions.includes(permission);
}

// const search = async ($search) => {
//     try {
//         const response = await axios.post(route('projectmanagement.index'), { searchQuery: $search });
//         projects.value = response.data.projects;
//     } catch (error) {
//         console.error('Error searching:', error);
//     }
// };

</script>
