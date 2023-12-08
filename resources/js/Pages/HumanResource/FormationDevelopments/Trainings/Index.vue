
<template>
    <Head title="Capacitaciones" />

    <AuthenticatedLayout>
        <template #header>
            Capacitaciones
        </template>
        <div class="inline-block min-w-full">
            <div class="flex items-center">
                <button @click="add_information" type="button"
                    class="rounded-md bg-indigo-600 px-4 py-2 text-center text-sm text-white hover:bg-indigo-500">
                    Agregar Informacion
                </button>
            </div>
            <br>
            <table class="w-full whitespace-no-wrap  overflow-hidden rounded-lg shadow">
                <thead>
                    <tr class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                            N°
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                            Nombre
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                            Descripción
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-xs font-semibold uppercase tracking-wider text-gray-600 text-center">
                            Acciones
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(training, i) in trainings.data" :key="training.id" class="text-gray-700">
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                            <p class="text-gray-900 whitespace-no-wrap">{{ i + 1 }}</p>
                        </td>
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                            <p class="text-gray-900 whitespace-no-wrap">{{ training.name }}</p>
                        </td>
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                            <p class="text-gray-900 whitespace-no-wrap">{{ training.description }}</p>
                        </td>
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm ">

                            <!-- <Link class="text-blue-900 whitespace-no-wrap"
                                :href="route('management.formationPrograms.information.details', { id: training.id })">
                            Mas Detalles</Link> -->
                            <div class="flex space-x-3 justify-center">
                                <Link>
                                <EyeIcon class="h-6 w-6 text-teal-500" />
                                </Link>
                                <Link
                                    :href="route('management.employees.formation_development.trainings.create', { id: training.id })">
                                <PencilSquareIcon class="h-6 w-6 text-blue-500" />
                                </Link>
                                <button @click="delete_training(training.id)">
                                    <TrashIcon class="h-6 w-6 text-red-500" />
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>

            <div class="flex flex-col items-center border-t bg-white px-5 py-5 xs:flex-row xs:justify-between">
                <pagination :links="trainings.links" />
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Pagination from '@/Components/Pagination.vue'
import { Head, Link, router } from '@inertiajs/vue3';
import { EyeIcon, TrashIcon, PencilSquareIcon } from '@heroicons/vue/24/outline';
import Swal from 'sweetalert2';

const props = defineProps({
    trainings: Object
})


const add_information = () => {
    router.get(route('management.employees.formation_development.trainings.create'));
}

const delete_training = (id) => {
    Swal.fire({
        title: "¿Estás seguro?",
        text: "Esto no se podrá deshacer",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sí, eliminar",
        cancelButtonText: "Cancelar"
    }).then((result) => {
        if (result.isConfirmed) {
            router.delete(`/management_employees/formation_development/trainings/delete/${id}`, {
                onSuccess: () => {
                    return Swal.fire({
                        title: "Éxito",
                        text: "Capacitación eliminada",
                        icon: "success",
                    })
                },
            })
        
        }
    });



}


</script>