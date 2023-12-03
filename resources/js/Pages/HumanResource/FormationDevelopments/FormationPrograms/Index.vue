
<template>
    <Head title="Programas de Formación" />

    <AuthenticatedLayout>
        <template #header>
            Programas de Formación
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
                            class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                            Tipo de programa
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                            Acciones
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(formationProgram, i) in formationPrograms.data" :key="formationProgram.id"
                        class="text-gray-700">
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                            <p class="text-gray-900 whitespace-no-wrap">{{ i + 1 }}</p>
                        </td>
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                            <p class="text-gray-900 whitespace-no-wrap">{{ formationProgram.name }}</p>
                        </td>
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                            <p class="text-gray-900 whitespace-no-wrap">{{ formationProgram.description }}</p>
                        </td>
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                            <p class="text-gray-900 whitespace-no-wrap">{{ formationProgram.type }}</p>
                        </td>
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                            <div class="flex space-x-3 justify-center">
                                <Link :href="route('management.employees.formation_development.view',{id:formationProgram.id})">
                                    <EyeIcon class="h-6 w-6 text-teal-500" />
                                </Link>
                                <Link>
                                    <PencilSquareIcon class="h-6 w-6 text-blue-500" />
                                </Link>
                                <button @click="delete_program(formationProgram.id)">
                                    <TrashIcon class="h-6 w-6 text-red-500" />
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>

            <div class="flex flex-col items-center border-t bg-white px-5 py-5 xs:flex-row xs:justify-between">
                <pagination :links="formationPrograms.links" />
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
    formationPrograms: Object
})


const add_information = () => {
    router.get(route('management.employees.formation_development.formation_programs.create'));
}

const delete_program= (id) => {
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
            router.delete(`/management_employees/formation_development/delete/${id}`, {
                onSuccess: () => {
                    return Swal.fire({
                        title: "Éxito",
                        text: "Programa eliminado",
                        icon: "success",
                    })
                },
            })
        
        }
    });
}


</script>