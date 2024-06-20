<template>
    <Head title="Proyectos" />
    <AuthenticatedLayout :redirectRoute="backUrl">
        <template #header>
            Productos para liquidar
        </template>

        <Link :href="route('projectmanagement.liquidate.history', {project_id: project_id})"
            class="rounded-md bg-indigo-600 px-4 py-2 text-center text-sm text-white hover:bg-indigo-500 ">
                Historial
        </Link>


        <div class="mt-5 inline-block min-w-full overflow-hidden rounded-lg shadow">
            <div>
            </div>
            <table class="w-full whitespace-no-wrap">
                <thead>
                    <tr class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-xs font-semibold uppercase tracking-wider text-gray-600 text-center">
                            Producto
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-xs font-semibold uppercase tracking-wider text-gray-600 text-center">
                            Cantidad
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-xs font-semibold uppercase tracking-wider text-gray-600 text-center">
                            Precio Unitario
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-xs font-semibold uppercase tracking-wider text-gray-600 text-center">
                            Observaciones
                        </th>
                        <th v-if="project.status === null && hasPermission('ProjectManager')"
                            class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-xs font-semibold uppercase tracking-wider text-gray-600 text-center">
                            Acciones
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="item in project_entries.data" :key="item.id">
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">
                            <p class="text-gray-900 whitespace-no-wrap">{{ item.special_inventory ? item.special_inventory.purchase_product.name : item.entry.inventory.purchase_product.name }}</p>
                        </td>
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">
                            <p class="text-gray-900 whitespace-no-wrap">{{ item.quantity }}</p>
                        </td>
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">
                            <p class="text-gray-900 whitespace-no-wrap">{{ item.unitary_price ? 'S/. ' + item.unitary_price : '-'}}</p>
                        </td>
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">
                            <p class="text-gray-900 whitespace-no-wrap">{{ item.observation }}</p>
                        </td>
                        <td v-if="project.status === null && hasPermission('ProjectManager')" class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">
                            <div class="flex space-x-3 justify-center">
                                <Link :href="route('projectmanagement.liquidate.form', {project_id: project_id, project_entry: item.id})"
                                    class="rounded-md bg-indigo-600 px-4 py-2 text-center text-sm text-white hover:bg-indigo-500 ">
                                    Liquidar
                                </Link>
                            </div>
                        </td>
                    </tr>
                </tbody>

            </table>
            <div class="flex flex-col items-center border-t bg-white px-5 py-5 xs:flex-row xs:justify-between">
                <pagination :links="project_entries.links" />
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import Pagination from '@/Components/Pagination.vue';


const { project_entries, project_id, liquidations, project, userPermissions } = defineProps({
    project_entries: Object,
    project_id: String,
    project: Object,
    userPermissions:Array
})

const hasPermission = (permission) => {
    return userPermissions.includes(permission);
}

let backUrl = project.status === null 
                ? 'projectmanagement.index' 
                : project.status == true 
                    ? 'projectmanagement.historial'
                    : 'projectmanagement.index' 

</script>