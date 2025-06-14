<template>
    <div class="mt-6 flex items-center justify-between gap-x-6">
        <div class="hidden sm:flex sm:items-center sm:space-x-3">
            <template v-if="!formSearch.statusProject">
                <PrimaryButton data-tooltip-target="add_monthly_project"
                    @click="createOrEditModal" type="button" customColor="bg-green-600 hover:bg-green-500">
                    <PlusCircleIcon color="text-white" />

                </PrimaryButton>
                <div id="add_monthly_project" role="tooltip"
                    class="absolute z-10 invisible inline-block px-2 py-1 text-xs font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                    + Agregar Proyecto
                    <div class="tooltip-arrow" data-popper-arrow></div>
                </div>
            </template>
            <Link
                :href="route('projectmanagement.pext.additional.index_rejected', { type })"
                class="rounded-md px-4 py-2 text-center text-sm text-white bg-red-600 hover:bg-red-500">
            No Proceden
            </Link>
            <PrimaryButton @click="completedProjects()" type="button"
                customColor="bg-green-600 hover:bg-green-500">
                {{ !formSearch.statusProject ? "Culminados" : "En Proceso" }}
            </PrimaryButton>
        </div>

        <div class="sm:hidden">
            <Dropdown align='left'>
                <template #trigger>
                    <button @click="dropdownOpen = !dropdownOpen"
                        class="relative block overflow-hidden rounded-md bg-gray-200 px-2 py-2 text-center text-sm text-white hover:bg-gray-100">
                        <MenuIcon />
                    </button>
                </template>

                <template #content class="origin-left">
                    <div>
                        <button v-if="!formSearch.statusProject"
                            @click="createOrEditModal"
                            class="dropdown-item block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-indigo-600 hover:text-white focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                            Agregar
                        </button>
                        <dropdown-link 
                            :href="route('projectmanagement.pext.additional.index_rejected', { type })">
                            No Proceden
                        </dropdown-link>
                        <button  @click="completedProjects"
                            class="dropdown-item block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-indigo-600 hover:text-white focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                            {{ !formSearch.statusProject ? "Culminados" : "En Proceso" }}
                        </button>
                    </div>
                </template>
            </Dropdown>
        </div>
        <div class="flex space-x-3">
            <Search v-model:search="formSearch.search" fields="Nombre,Codigo,CPE" />
        </div>
    </div>
</template>
<script setup>
import PrimaryButton from '@/Components/PrimaryButton.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import Search from '@/Components/Search.vue';
import { Link } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import { notifyError } from '@/Components/Notification';
import { MenuIcon, PlusCircleIcon } from '@/Components/Icons/Index';

const { userPermissions, type, searchCondition, createOrEditModal } = defineProps({
    userPermissions: Array,
    type: String,
    searchCondition: {
        type: String,
        Required: false
    },
    createOrEditModal: Function
})
const projects = defineModel('projects')


const formSearch = ref({
    search: '',
    statusProject: false
})

function completedProjects() {
    formSearch.value.statusProject = !formSearch.value.statusProject
    search()
}

watch(
    () => [
        formSearch.value.search,
        formSearch.value.statusProject,
    ],
    () => {
        search();
    },
    { deep: true }
);

const search = async () => {
    try {
        const response = await axios.post(route('projectmanagement.pext.additional.index', { type }), { searchQuery: formSearch.value.search, statusProject: formSearch.value.statusProject });
        projects.value = response.data;
    } catch (error) {
        notifyError('Error searching:', error);
    }
};

if (searchCondition) {
    search(searchCondition)
}
</script>