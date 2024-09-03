<template>

    <Head title="Proyectos Liquidados" />
    <AuthenticatedLayout :redirectRoute="'huawei.projects'">
        <template #header>
            Proyectos Liquidados de Huawei
        </template>
        <div class="min-w-full rounded-lg shadow">
            <div class="flex gap-4 justify-between items-center rounded-lg w-full">
                <div class="flex-shrink-0">
                    <Link :href="route('huawei.projects')" type="button"
                        class="px-4 py-2 border-2 border-gray-700 rounded-md font-semibold text-xs hover:text-gray-700 uppercase tracking-widest bg-gray-700 hover:underline hover:bg-gray-200 focus:border-indigo-600 focus:outline-none focus:ring-2 text-white whitespace-nowrap"
                        style="min-width: 80px; max-width: 120px;">
                        Proyectos
                    </Link>
                </div>
                <div class="flex items-center">
                    <form @submit.prevent="search" class="flex items-center">
                        <TextInput type="text" placeholder="Buscar..." v-model="searchForm.searchTerm" class="mr-2" />
                        <button type="submit" :class="{ 'opacity-25': searchForm.processing }"
                                class="rounded-md bg-indigo-600 px-2 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                            <svg width="30px" height="21px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M15.7955 15.8111L21 21M18 10.5C18 14.6421 14.6421 18 10.5 18C6.35786 18 3 14.6421 3 10.5C3 6.35786 6.35786 3 10.5 3C14.6421 3 18 6.35786 18 10.5Z"
                                    stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
            <br>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-4 gap-3">
                <div v-for="item in (props.search ? props.projects : projects.data)" :key="item.id"
                    class="bg-white p-3 rounded-md shadow-sm border border-gray-300 items-center">
                    <div class="grid grid-cols-2">
                        <h2 class="text-sm font-semibold mb-3">
                            N° {{ item.code }}
                        </h2>
                    </div>
                    <div class="flex gap-1">
                        <p class="text-sm font-semibold text-black">Nombre: </p>
                        <h3 class="text-sm font-semibold text-gray-700 line-clamp-1 mb-1 whitespace-nowrap">
                            {{ item.name }}
                        </h3>
                    </div>
                    <div class="flex gap-1">
                        <p class="text-sm font-semibold text-black">Site: </p>
                        <h3 class="text-sm font-semibold text-gray-700 line-clamp-1 mb-1 whitespace-nowrap">
                            {{ item.huawei_site.name }}
                        </h3>
                    </div>
                    <div class="flex gap-1">
                        <p class="text-sm font-semibold text-black">OT: </p>
                        <h3 class="text-sm font-semibold text-gray-700 line-clamp-1 mb-1 whitespace-nowrap">
                            {{ item.ot }}
                        </h3>
                    </div>
                    <div class="flex gap-1">
                        <p class="text-sm font-semibold text-black">DIU: </p>
                        <h3 class="text-sm font-semibold text-gray-700 line-clamp-1 mb-1 whitespace-nowrap">
                            {{ item.assigned_diu }}
                        </h3>
                    </div>
                    <div
                        class="text-gray-500 text-sm mt-1">
                        <div class="grid grid-cols-1 gap-y-1">
                            <Link
                                :href="route('huawei.projects.additionalcosts.summary', { huawei_project: item.id })"
                                class="text-blue-600 underline whitespace-no-wrap hover:text-purple-600">
                                Gestión de Gastos
                            </Link>
                            <Link
                                :href="route('huawei.projects.stages', { huawei_project: item.id })"
                                class="text-blue-600 underline whitespace-no-wrap hover:text-purple-600">
                                Imágenes
                            </Link>
                            <Link
                                :href="route('huawei.projects.resources', { huawei_project: item.id })"
                                class="text-blue-600 underline whitespace-no-wrap hover:text-purple-600">
                                Asignar Productos
                            </Link>
                            <Link
                                :href="route('huawei.projects.earnings', { huawei_project: item.id })"
                                class="text-blue-600 underline whitespace-no-wrap hover:text-purple-600">
                                Ingresos Proyectados
                            </Link>
                            <Link
                                :href="route('huawei.projects.realearnings', { huawei_project: item.id })"
                                class="text-blue-600 underline whitespace-no-wrap hover:text-purple-600">
                                Ingresos Reales
                            </Link>
                            <Link
                                :href="route('huawei.projects.liquidations', { huawei_project: item.id })"
                                class="text-blue-600 underline whitespace-no-wrap hover:text-purple-600">
                                Liquidaciones
                            </Link>
                            <Link
                                :href="route('huawei.projects.balance', { huawei_project: item.id })"
                                class="text-blue-600 underline whitespace-no-wrap hover:text-purple-600">
                                Balance del Proyecto
                            </Link>
                            <a v-if="item.pre_report" class="text-blue-600 underline whitespace-no-wrap hover:text-purple-600" :href="route('huawei.projects.prereport', {huawei_project: item.id})" target="_blank">
                                Reporte
                            </a>
                            <span v-else class="text-gray-400">Reporte</span>
                        </div>
                    </div>
                </div>
            </div>
            <br>
            <div v-if="!props.search" class="flex flex-col items-center border-t px-5 py-5 xs:flex-row xs:justify-between">
                <pagination :links="props.projects.links" />
            </div>
        </div>

    </AuthenticatedLayout>
</template>
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Pagination from '@/Components/Pagination.vue'
import { Head, router, Link, useForm } from '@inertiajs/vue3';
import TextInput from '@/Components/TextInput.vue';

const props = defineProps({
    projects: Object,
    auth: Object,
    userPermissions:Array,
    search: String,
})

const hasPermission = (permission) => {
    return props.userPermissions.includes(permission);
}

const searchForm = useForm({
    searchTerm: props.search ? props.search : '',
})

const search = () => {
    if (searchForm.searchTerm == ''){
        router.visit(route('huawei.projects.history'))
    }else{
        router.visit(route('huawei.projects.search.history', {request: searchForm.searchTerm}));
    }
}

</script>
